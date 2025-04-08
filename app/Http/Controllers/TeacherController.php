<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mark;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    // Show the class selection form
    public function selectClass()
    {
        return view('teacher.select-class'); // Blade view: resources/views/teacher/select-class.blade.php
    }

    // Show marks for selected class and pass SQL queries to Blade
    public function viewMarks(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
        ]);

        $queries = [];

        // Capture SQL queries
        DB::listen(function ($query) use (&$queries) {
            $queries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time
            ];
        });

        $className = $request->input('class_name');

        $marks = Mark::with('user')
                    ->where('class_name', $className)
                    ->get();

        // Return marks view and pass SQL queries
        return view('teacher.marks', compact('marks', 'className', 'queries'));
    }
}
