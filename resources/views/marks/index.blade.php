@extends('layouts.app')

@section('content')
@php
    $isTeacher = Auth::user()->role === 'teacher';
@endphp

<div class="container max-w-4xl mx-auto mt-6">
    <h2 class="text-2xl font-bold mb-6">
        {{ $isTeacher ? 'Add Mark for Student' : 'Add My Mark' }}
    </h2>

    @if (session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('marks.store') }}" class="bg-white p-6 rounded shadow">
        @csrf

        <div class="mb-4">
            <label for="subject" class="block font-medium">Subject</label>
            <input type="text" id="subject" name="subject" class="w-full p-2 border rounded" required value="{{ old('subject') }}">
            @error('subject') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label for="score" class="block font-medium">Score</label>
            <input type="number" id="score" name="score" class="w-full p-2 border rounded" min="0" max="100" required value="{{ old('score') }}">
            @error('score') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        @if($isTeacher)
            <div class="mb-4">
                <label for="user_id" class="block font-medium">Student</label>
                <select id="user_id" name="user_id" class="w-full p-2 border rounded" required>
                    <option value="">-- Select Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" data-class="{{ $student->class_name }}"
                            {{ old('user_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->class_name }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>
        @endif

        <div class="mb-4">
            <label for="class_name" class="block font-medium">Class Name</label>
            @if($isTeacher)
                <input type="text" id="class_name" name="class_name" class="w-full p-2 border rounded bg-gray-100" value="{{ old('class_name') }}">
            @else
                <select id="class_name" name="class_name" class="w-full p-2 border rounded" required>
                    <option value="">-- Select Class --</option>
                    @foreach(['Class A', 'Class B', 'Class C'] as $class)
                        <option value="{{ $class }}" {{ old('class_name') == $class ? 'selected' : '' }}>{{ $class }}</option>
                    @endforeach
                </select>
            @endif
            @error('class_name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            {{ $isTeacher ? 'Save Mark' : 'Add Mark' }}
        </button>
    </form>

    {{-- Dashboard --}}
    <div class="mt-12">
        <h1 class="text-2xl font-bold mb-4">Marks Dashboard</h1>

        @if($isTeacher)
            @if($groupedMarks->count())
                @foreach($groupedMarks as $class => $marks)
                    <h2 class="text-xl font-semibold mt-6 mb-2">Class: {{ $class }}</h2>
                    <table class="table-auto w-full border border-gray-300 mb-4">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2 text-left">Student Name</th>
                                <th class="border p-2 text-left">Subject</th>
                                <th class="border p-2 text-left">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marks as $mark)
                                <tr>
                                    <td class="border p-2">{{ optional($mark->user)->name ?? 'N/A' }}</td>
                                    <td class="border p-2">{{ $mark->subject }}</td>
                                    <td class="border p-2">{{ $mark->score }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @else
                <p class="text-gray-600 mt-4">No marks available to display.</p>
            @endif
        @else
            @if($marks->count())
                <h2 class="text-xl font-semibold mt-6 mb-2">Your Marks</h2>
                <table class="table-auto w-full border border-gray-300 mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2 text-left">Subject</th>
                            <th class="border p-2 text-left">Score</th>
                            <th class="border p-2 text-left">Class</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($marks as $mark)
                            <tr>
                                <td class="border p-2">{{ $mark->subject }}</td>
                                <td class="border p-2">{{ $mark->score }}</td>
                                <td class="border p-2">{{ $mark->class_name ?? 'Unknown' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600 mt-4">You have no marks yet.</p>
            @endif
        @endif
    </div>
</div>

{{-- Auto-fill class_name for teachers --}}
@if($isTeacher)
    @once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userSelect = document.getElementById('user_id');
            const classInput = document.getElementById('class_name');

            function updateClassName() {
                const selectedOption = userSelect.options[userSelect.selectedIndex];
                const className = selectedOption.getAttribute('data-class');
                classInput.value = className || '';
            }

            userSelect.addEventListener('change', updateClassName);
            updateClassName(); // auto-run on load
        });
    </script>
    @endpush
    @endonce
@endif
@endsection
