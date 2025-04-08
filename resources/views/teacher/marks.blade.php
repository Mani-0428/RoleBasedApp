@extends('layouts.app')

@section('content')
<div class="container max-w-2xl mx-auto mt-6">
    <h2 class="text-xl font-bold mb-4">View Marks by Class</h2>

    {{-- Show form only for teacher --}}
    @if(Auth::user()->role === 'teacher')
        <form method="POST" action="{{ route('teacher.view-marks') }}" class="mb-6">
            @csrf
            <div class="mb-4">
                <label for="class_name" class="block text-sm font-medium text-gray-700">Select Class</label>
                <select name="class_name" id="class_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Choose Class --</option>
                    <option value="Class A" {{ (old('class_name') == 'Class A' || (isset($className) && $className == 'Class A')) ? 'selected' : '' }}>Class A</option>
                    <option value="Class B" {{ (old('class_name') == 'Class B' || (isset($className) && $className == 'Class B')) ? 'selected' : '' }}>Class B</option>
                    <option value="Class C" {{ (old('class_name') == 'Class C' || (isset($className) && $className == 'Class C')) ? 'selected' : '' }}>Class C</option>
                </select>
                @error('class_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <x-primary-button>Show Marks</x-primary-button>
        </form>
    @endif

    {{-- Display marks only if available --}}
    @isset($marks)
        <h3 class="text-lg font-semibold mb-2">Marks for {{ $className }}</h3>

        @if($marks->isEmpty())
            <p>No marks available for this class.</p>
        @else
            <table class="w-full table-auto border">
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
        @endif
    @endisset

    {{-- Show SQL queries in browser console (for debugging) --}}
    @if(isset($queries))
        <script>
            const sqlQueries = @json($queries);
            console.log("🔍 Executed SQL Queries:");
            sqlQueries.forEach((query, index) => {
                console.log(`Query ${index + 1}:`, query.sql);
                console.log("Bindings:", query.bindings);
                console.log(`Time: ${query.time}ms`);
            });
        </script>
    @endif
</div>
@endsection
