@extends('layouts.app')

@section('content')
<div class="container max-w-2xl mx-auto mt-6">
    <h2 class="text-xl font-bold mb-4">View Marks by Class & Subject</h2>

    {{-- Show form only for teacher --}}
    @if(Auth::user()->role === 'teacher')
        @php
            $classes = ['Class A', 'Class B', 'Class C'];
            $subjects = ['Math', 'Science', 'English', 'computer', 'History', 'tamil'];
            $selectedClass = old('class_name', $className ?? '');
            $selectedSubject = old('subject', $subject ?? '');
        @endphp

        <form method="POST" action="{{ route('teacher.view-marks') }}" class="mb-6">
            @csrf
            {{-- Class Filter --}}
            <div class="mb-4">
                <label for="class_name" class="block text-sm font-medium text-gray-700">Select Class</label>
                <select name="class_name" id="class_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Choose Class --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class }}" {{ $selectedClass === $class ? 'selected' : '' }}>{{ $class }}</option>
                    @endforeach
                </select>
                @error('class_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Subject Filter --}}
            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700">Select Subject</label>
                <select name="subject" id="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- All Subjects --</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj }}" {{ $selectedSubject === $subj ? 'selected' : '' }}>{{ $subj }}</option>
                    @endforeach
                </select>
                @error('subject')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <x-primary-button>Show Marks</x-primary-button>
        </form>
    @endif

    {{-- Display marks if available --}}
    @isset($marks)
        <h3 class="text-lg font-semibold mb-2">
            Marks for {{ $className }}{{ isset($subject) && $subject ? ' - ' . $subject : '' }}
        </h3>

        @if($marks->isEmpty())
            <p>No marks available for this selection.</p>
        @else
            <table class="w-full table-auto border mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Student Name</th>
                        <th class="px-4 py-2 border">Subject</th>
                        <th class="px-4 py-2 border">Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marks as $mark)
                        <tr>
                            <td class="px-4 py-2 border">{{ $mark->user->name }}</td>
                            <td class="px-4 py-2 border">{{ $mark->subject }}</td>
                            <td class="px-4 py-2 border">{{ $mark->score }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Export to CSV Button --}}
            <form method="POST" action="{{ route('teacher.export-marks') }}" class="mb-4">
                @csrf
                <input type="hidden" name="class_name" value="{{ $className }}">
                <input type="hidden" name="subject" value="{{ $subject }}">
                <x-primary-button class="bg-green-600 hover:bg-green-700">Export to CSV</x-primary-button>
            </form>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $marks->links() }}
            </div>
        @endif
    @endisset

    {{-- Show SQL queries in browser console (for debug purposes) --}}
    @if(isset($queries))
        <script>
            const sqlQueries = @json($queries);
            console.group("🔍 Executed SQL Queries:");
            sqlQueries.forEach((query, index) => {
                console.log(`Query ${index + 1}:`, query.sql);
                console.log("Bindings:", query.bindings);
                console.log(`Time: ${query.time}ms`);
            });
            console.groupEnd();
        </script>
    @endif
</div>
@endsection
