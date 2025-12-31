<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Send Emergency Notification') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Select a doctor and send an urgent message immediately.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            @if ($errors->any())
                <div class="rounded-3xl border border-rose-200 bg-rose-50 text-rose-800 px-6 py-4">
                    <p class="text-sm font-semibold">Please fix the errors below:</p>
                    <ul class="list-disc ml-5 mt-2 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Emergency Alert</h3>

                    <form method="POST" action="{{ route('receptionist.emergency.store') }}" class="mt-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Doctor <span class="text-rose-600">*</span>
                                </label>
                                <select name="doctor_id" required
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Emergency Message <span class="text-rose-600">*</span>
                                </label>
                                <textarea name="message" rows="5" required
                                          class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
                                          placeholder="Describe the emergency for the doctor...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                                Send Emergency Alert
                            </button>

                            <a href="{{ route('receptionist.appointments.index') }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
