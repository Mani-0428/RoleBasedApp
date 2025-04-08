@extends('layouts.app')

@section('content')
<div class="container max-w-xl mx-auto mt-6">
    <h2 class="text-lg font-bold mb-4">Select Class to View Marks</h2>

    <form action="{{ route('teacher.view-marks') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="class_name" class="block mb-2 font-medium">Class</label>
            <select name="class_name" id="class_name" class="w-full border rounded p-2" required>
                <option value="">-- Select Class --</option>
                <option value="Class A">Class A</option>
                <option value="Class B">Class B</option>
                <option value="Class C">Class C</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            View Marks
        </button>
    </form>
</div>
@endsection
