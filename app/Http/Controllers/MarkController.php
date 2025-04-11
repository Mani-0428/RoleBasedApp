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
        $students = Auth::user()->isTeacher()
            ? User::where('role', 'student')->select('id', 'name', 'class_name')->get()
            : collect();

        return view('marks.index', compact('students'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'subject' => 'required|string|max:255',
            'score'   => 'required|integer|min:0|max:100',
        ];

        if ($user->isTeacher()) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        if ($user->isTeacher()) {
            $student = User::findOrFail($validated['user_id']);
        } else {
            $student = $user;
            $validated['user_id'] = $user->id;
        }

        $validated['class_name'] = $student->class_name ?? 'Unknown';

        Mark::create($validated);

        return redirect()->route('marks.index')->with('success', 'Mark added successfully!');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            $students = User::where('role', 'student')->get();
            $groupedMarks = Mark::with('user')->get()->groupBy(fn($mark) => $mark->class_name ?? 'Unknown');
            return view('marks.index', compact('groupedMarks', 'students'));
        }

        $marks = Mark::where('user_id', $user->id)->latest()->get();
        return view('marks.index', compact('marks'));
    }
}
