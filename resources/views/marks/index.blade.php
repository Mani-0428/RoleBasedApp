@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Marks Dashboard</h1>

    @if(Auth::user()->role === 'teacher') 
        @if(isset($groupedMarks) && $groupedMarks->count())
            @foreach($groupedMarks as $class => $marks)
                <h2 class="text-xl font-bold mt-6 mb-2">Class: {{ $class }}</h2>

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
                                <td class="border p-2">{{ $mark->user->name ?? 'N/A' }}</td>
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
            <h2 class="text-xl font-bold mt-6 mb-2">Your Marks</h2>

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

@if(session('query_log'))
    <script>
        fetch('/log-sql')
            .then(res => res.json())
            .then(data => {
                console.log("SQL Queries:");
                data.forEach((query, index) => {
                    console.log(`#${index + 1}:`, query.sql);
                    console.log("Bindings:", query.bindings);
                    console.log("Time:", query.time + 'ms');
                });
            });
    </script>
@endif
@endsection
