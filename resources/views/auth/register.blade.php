<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role Dropdown -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Register As')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required onchange="toggleClassField(this.value)">
                <option value="">-- Select Role --</option>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Class Dropdown (only for student) -->
        <div class="mt-4" id="classField" style="display: none;">
            <x-input-label for="class_name" :value="__('Class')" />
            <select id="class_name" name="class_name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Select Class --</option>
                <option value="Class A" {{ old('class_name') === 'Class A' ? 'selected' : '' }}>Class A</option>
                <option value="Class B" {{ old('class_name') === 'Class B' ? 'selected' : '' }}>Class B</option>
                <option value="Class C" {{ old('class_name') === 'Class C' ? 'selected' : '' }}>Class C</option>
            </select>
            <x-input-error :messages="$errors->get('class_name')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- JS -->
    <script>
        function toggleClassField(role) {
            const classField = document.getElementById('classField');
            classField.style.display = (role === 'student') ? 'block' : 'none';
        }

        // Show class dropdown if validation fails and role is student
        window.onload = () => {
            toggleClassField(document.getElementById('role').value);
        };
    </script>
</x-guest-layout>
