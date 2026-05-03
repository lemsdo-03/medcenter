<x-patient-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900 leading-tight">Submit Feedback</h2>
        <p class="text-sm text-slate-500 mt-1">Rate your visit or file a complaint.</p>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm" x-data="{ type: '{{ old('type', 'rating') }}' }">
            @if($appointments->isEmpty())
                <div class="text-center py-8">
                    <p class="text-slate-500">You need a completed appointment before you can leave feedback.</p>
                </div>
            @else
                <form method="POST" action="{{ route('patient.ratings.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-slate-700">Appointment</label>
                        <select name="appointment_id" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                            <option value="">Select an appointment</option>
                            @foreach($appointments as $apt)
                                <option value="{{ $apt->id }}" {{ old('appointment_id') == $apt->id ? 'selected' : '' }}>
                                    Dr. {{ $apt->doctor->name }} - {{ $apt->appointment_date->format('M d, Y') }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('appointment_id')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Type</label>
                        <div class="mt-2 flex gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="rating" x-model="type" class="hidden peer">
                                <span class="inline-block px-5 py-2 rounded-2xl border text-sm font-medium transition
                                    peer-checked:bg-emerald-700 peer-checked:text-white peer-checked:border-emerald-700
                                    border-slate-200 text-slate-700 hover:bg-slate-50">Rating</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="complaint" x-model="type" class="hidden peer">
                                <span class="inline-block px-5 py-2 rounded-2xl border text-sm font-medium transition
                                    peer-checked:bg-rose-700 peer-checked:text-white peer-checked:border-rose-700
                                    border-slate-200 text-slate-700 hover:bg-slate-50">Complaint</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div x-show="type === 'rating'">
                        <label class="text-sm font-medium text-slate-700">Rating (1-5)</label>
                        <div class="mt-2 flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" {{ old('rating') == $i ? 'checked' : '' }}>
                                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl border text-lg font-semibold transition
                                        peer-checked:bg-emerald-700 peer-checked:text-white peer-checked:border-emerald-700
                                        border-slate-200 text-slate-700 hover:bg-slate-50">{{ $i }}</span>
                                </label>
                            @endfor
                        </div>
                        <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" x-text="type === 'rating' ? 'Comment' : 'Describe your complaint'"></label>
                        <textarea name="comment" rows="4" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">{{ old('comment') }}</textarea>
                        <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                            class="px-5 py-3 rounded-2xl text-white text-sm font-semibold transition"
                            :class="type === 'complaint' ? 'bg-rose-700 hover:bg-rose-800' : 'bg-emerald-700 hover:bg-emerald-800'">
                            Submit
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-patient-layout>
