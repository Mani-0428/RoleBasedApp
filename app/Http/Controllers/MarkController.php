<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mark;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'teacher') {
            $students = User::where('role', 'student')->get();
            return view('marks.create', compact('students'));
        }

        return view('marks.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'subject' => 'required|string',
            'score' => 'required|integer|min:0|max:100',
        ];

        if ($user->role === 'teacher') {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        if ($user->role === 'teacher') {
            $student = User::find($validated['user_id']);
            $validated['class_name'] = $student->class_name ?? 'Unknown';
        } else {
            $validated['user_id'] = $user->id;
            $validated['class_name'] = $user->class_name ?? 'Unknown';
        }

        Mark::create($validated);

        return redirect()->route('marks.index')->with('success', 'Mark added successfully!');
    }

    public function index()
    {
        $user = Auth::user();

        // Log SQL queries
        DB::listen(function ($query) {
            logger('SQL: ' . $query->sql);
            logger('Bindings: ' . json_encode($query->bindings));
            logger('Time: ' . $query->time . 'ms');
        });

        if ($user->role === 'teacher') {
            $marks = Mark::with('user')->get();

            $groupedMarks = $marks->groupBy(function ($mark) {
                return $mark->class_name ?? 'Unknown';
            });

            return view('marks.index', compact('groupedMarks'))->with('query_log', true);
        }

        // Student view
        $marks = Mark::where('user_id', $user->id)->latest()->get();

        return view('marks.index', compact('marks'))->with('query_log', true);
    }
}
