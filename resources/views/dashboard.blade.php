<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p>Welcome, {{ Auth::user()->name }}</p>

                <!-- View Marks Button -->
                <a href="{{ route('marks.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    View Marks
                    <body class="dark:bg-darkBg dark:text-darkText bg-white text-black">
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
