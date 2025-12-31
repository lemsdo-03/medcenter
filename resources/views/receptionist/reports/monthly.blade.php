<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Monthly Reports') }}
                </h2>
               
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Generate Report</h3>

                    <form method="GET" action="{{ route('receptionist.reports.monthly') }}" class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Select Month</label>
                                <input type="month" name="month" value="{{ $month }}" required
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    @if($hasData)
                        @php
                            $cards = [
                                ['label' => 'Total Appointments', 'value' => $totalAppointments, 'style' => 'bg-slate-50 border-slate-200 text-slate-900'],
                                ['label' => 'Paid',              'value' => $paidAppointments,  'style' => 'bg-emerald-50 border-emerald-100 text-emerald-800'],
                                ['label' => 'Unpaid',            'value' => $unpaidAppointments,'style' => 'bg-amber-50 border-amber-100 text-amber-800'],
                                ['label' => 'Completed',         'value' => $completedAppointments,'style' => 'bg-blue-50 border-blue-100 text-blue-800'],
                                ['label' => 'Cancelled',         'value' => $cancelledAppointments,'style' => 'bg-rose-50 border-rose-100 text-rose-800'],
                            ];

                            $statusStyles = [
                                'scheduled' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                            ];
                            $statusDot = [
                                'scheduled' => 'bg-blue-500',
                                'completed' => 'bg-emerald-500',
                                'cancelled' => 'bg-rose-500',
                            ];

                            $payStyles = [
                                true  => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                false => 'bg-amber-50 text-amber-700 border-amber-100',
                            ];
                            $payDot = [
                                true  => 'bg-emerald-500',
                                false => 'bg-amber-500',
                            ];
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
                            @foreach($cards as $c)
                                <div class="rounded-3xl border p-4 {{ $c['style'] }}">
                                    <p class="text-xs font-semibold uppercase tracking-wide opacity-80">
                                        {{ $c['label'] }}
                                    </p>
                                    <p class="mt-2 text-2xl font-bold">
                                        {{ $c['value'] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="text-lg font-semibold text-slate-900">Appointments</h3>
                                @if($appointments->count() > 20)
                                    <span class="text-xs text-slate-500">Showing first 20 of {{ $appointments->count() }}</span>
                                @endif
                            </div>

                            <div class="mt-4 overflow-x-auto rounded-3xl border border-slate-200">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-slate-50">
                                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                            <th class="py-3 px-4">Date</th>
                                            <th class="py-3 px-4">Patient</th>
                                            <th class="py-3 px-4">Doctor</th>
                                            <th class="py-3 px-4">Status</th>
                                            <th class="py-3 px-4">Payment</th>
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($appointments->take(20) as $appointment)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="py-3 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                                    {{ $appointment->appointment_date->format('M d H:i') }}
                                                </td>
                                                <td class="py-3 px-4 text-slate-900">
                                                    {{ $appointment->patient->full_name }}
                                                </td>
                                                <td class="py-3 px-4 text-slate-800">
                                                    {{ $appointment->doctor->name }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="inline-flex items-center rounded-full px-3 py-1 border text-xs font-semibold {{ $statusStyles[$appointment->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                                        <span class="mr-2 h-2 w-2 rounded-full {{ $statusDot[$appointment->status] ?? 'bg-slate-400' }}"></span>
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="inline-flex items-center rounded-full px-3 py-1 border text-xs font-semibold {{ $payStyles[(bool)$appointment->is_paid] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                                        <span class="mr-2 h-2 w-2 rounded-full {{ $payDot[(bool)$appointment->is_paid] ?? 'bg-slate-400' }}"></span>
                                                        {{ $appointment->is_paid ? 'Paid' : 'Unpaid' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($appointments->count() > 20)
                                <p class="mt-2 text-sm text-slate-500">
                                    Showing first 20 of {{ $appointments->count() }} appointments.
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                            <p class="mt-4 text-lg font-semibold text-slate-900">No records found</p>
                            <p class="mt-1 text-sm text-slate-500">No appointments exist for the selected month.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
