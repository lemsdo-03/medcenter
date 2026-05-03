<x-patient-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900 leading-tight">Dashboard</h2>
        <p class="text-sm text-slate-500 mt-1">Welcome back, {{ $patient->first_name }}.</p>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-5">
            <p class="text-xs text-slate-500">Upcoming</p>
            <p class="text-2xl font-semibold text-slate-900 mt-1">{{ $upcomingAppointments->count() }}</p>
            <p class="text-sm text-slate-600">Appointments</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5">
            <p class="text-xs text-slate-500">Unread</p>
            <p class="text-2xl font-semibold text-slate-900 mt-1">{{ $unreadNotifications }}</p>
            <p class="text-sm text-slate-600">Notifications</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5">
            <p class="text-xs text-slate-500">New</p>
            <p class="text-2xl font-semibold text-slate-900 mt-1">{{ $unreadMessages }}</p>
            <p class="text-sm text-slate-600">Messages</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Upcoming Appointments --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-900">Upcoming Appointments</h3>
                <a href="{{ route('patient.doctors.index') }}" class="text-sm text-emerald-700 font-semibold hover:underline">Book New</a>
            </div>

            @forelse($upcomingAppointments as $apt)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                    <div>
                        <p class="font-medium text-slate-900">Dr. {{ $apt->doctor->name }}</p>
                        <p class="text-sm text-slate-500">{{ $apt->appointment_date->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                        {{ ucfirst($apt->status) }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-slate-500">No upcoming appointments.</p>
            @endforelse
        </div>

        {{-- Recent Notifications --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-900">Recent Notifications</h3>
                <a href="{{ route('patient.notifications.index') }}" class="text-sm text-emerald-700 font-semibold hover:underline">View All</a>
            </div>

            @forelse($recentNotifications as $notif)
                <div class="py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-medium text-slate-900 {{ !$notif->is_read ? '' : 'text-slate-600' }}">{{ $notif->title }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ Str::limit($notif->message, 60) }}</p>
                        </div>
                        @if(!$notif->is_read)
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mt-2 flex-shrink-0"></span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-sm text-slate-500">No notifications yet.</p>
            @endforelse
        </div>
    </div>

</x-patient-layout>
