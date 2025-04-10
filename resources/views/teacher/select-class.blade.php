@extends('layouts.app')

@section('content')
<div class="container max-w-xl mx-auto mt-6">
    <h2 class="text-lg font-bold mb-4">View Marks for Your Assigned Class</h2>

    <form action="{{ route('teacher.view-marks') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="class_name" class="block mb-2 font-medium">Class</label>

            @php
                $assignedClass = Auth::user()->assigned_class;
            @endphp

            <select name="class_name" id="class_name" class="w-full border rounded p-2 bg-gray-100 cursor-not-allowed" readonly disabled>
                <option value="{{ $assignedClass }}" selected>{{ $assignedClass }}</option> 
                {{-- The options are disabled and readonly to prevent changes --}}
            </select>

            {{-- Hidden input to actually submit the value since disabled inputs aren't submitted --}}
            <input type="hidden" name="class_name" value="{{ $assignedClass }}">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            View Marks
        </button>
    </form>
</div>
@endsection
