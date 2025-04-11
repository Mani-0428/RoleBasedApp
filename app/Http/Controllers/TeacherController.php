<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TeacherController extends Controller
{
    // Show the class selection form
    public function selectClass()
    {
        return view('teacher.select-class');
    }

    // View marks with pagination and optional subject filter
    public function viewMarks(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
            'subject' => 'nullable|string',
        ]);

        $queries = [];

        // Listen to SQL queries
        DB::listen(function ($query) use (&$queries) {
            $queries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ];
        });

        $className = $request->input('class_name');
        $subject = $request->input('subject');

        $query = Mark::with('user')->where('class_name', $className);

        if (!empty($subject)) {
            $query->where('subject', $subject);
        }

        $marks = $query->paginate(3); // ✅ Pagination added

        return view('teacher.marks', compact('marks', 'className', 'subject', 'queries'));
    }

    // Export filtered marks to CSV
    public function exportMarks(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
            'subject' => 'nullable|string',
        ]);

        $className = $request->class_name;
        $subject = $request->subject;

        $query = Mark::with('user')->where('class_name', $className);

        if (!empty($subject)) {
            $query->where('subject', $subject);
        }

        $marks = $query->get();

        $csvData = [];
        $csvData[] = ['Student Name', 'Subject', 'Score'];

        foreach ($marks as $mark) {
            $csvData[] = [$mark->user->name, $mark->subject, $mark->score];
        }

        $filename = 'marks_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'r+');

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $contents = stream_get_contents($handle);
        fclose($handle);

        return Response::make($contents, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
