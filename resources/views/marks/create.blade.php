@extends('layouts.app')

@section('content')
<div class="container max-w-xl mx-auto mt-6">
    <h2 class="text-xl font-bold mb-4">
        {{ Auth::user()->role === 'teacher' ? 'Add Mark for Student' : 'Add My Mark' }}
    </h2>

    @if (session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('marks.store') }}">
        @csrf

        {{-- Subject --}}
        <div class="mb-4">
            <label for="subject" class="block font-medium">Subject</label>
            <input type="text" id="subject" name="subject" class="w-full p-2 border rounded" required value="{{ old('subject') }}">
            @error('subject')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Score --}}
        <div class="mb-4">
            <label for="score" class="block font-medium">Score</label>
            <input type="number" id="score" name="score" class="w-full p-2 border rounded" min="0" max="100" required value="{{ old('score') }}">
            @error('score')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Student selection for teachers only --}}
        @if(Auth::user()->role === 'teacher')
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
                @error('user_id')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        @endif

        {{-- Class Name --}}
<div class="mb-4">
    <label for="class_name" class="block font-medium">Class Name</label>
    @if(Auth::user()->role === 'teacher')
        <input type="text" id="class_name" name="class_name" class="w-full p-2 border rounded bg-gray-100"
            value="{{ old('class_name') }}" readonly>
    @else
        <select id="class_name" name="class_name" class="w-full p-2 border rounded" required>
            <option value="">-- Select Class --</option>
            <option value="Class A" {{ old('class_name') == 'Class A' ? 'selected' : '' }}>Class A</option>
            <option value="Class B" {{ old('class_name') == 'Class B' ? 'selected' : '' }}>Class B</option>
            <option value="Class C" {{ old('class_name') == 'Class C' ? 'selected' : '' }}>Class C</option>
        </select>
    @endif
    @error('class_name')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>


        {{-- Submit Button --}}
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            {{ Auth::user()->role === 'teacher' ? 'Save Mark' : 'Add Mark' }}
        </button>
    </form>
</div>

{{-- Auto-fill class_name for teachers --}}
@if(Auth::user()->role === 'teacher')
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userSelect = document.getElementById('user_id');
            const classInput = document.getElementById('class_name');

            userSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const className = selectedOption.getAttribute('data-class');
                classInput.value = className || '';
            });

            // Trigger change on page load if old value exists
            if (userSelect.value) {
                const selectedOption = userSelect.querySelector(`option[value="{{ old('user_id') }}"]`);
                if (selectedOption) {
                    classInput.value = selectedOption.getAttribute('data-class');
                }
            }
        });
    </script>
    @endpush
@endif
@endsection
