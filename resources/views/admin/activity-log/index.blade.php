@extends('layouts.app')

@section('title', 'Audit Log Aktivitas')
@section('page-title', 'Audit Log')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-wrapper .ts-control {
            border-radius: 0.5rem;
            padding: 0.4rem 0.7rem;
            font-size: 0.8125rem;
            border-color: #e5e7eb;
            background-color: white !important;
        }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
            border-color: #6366f1;
        }
        .ts-dropdown {
            border-radius: 0.5rem;
            margin-top: 3px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #e5e7eb;
            font-size: 0.8125rem;
        }
        .log-row:hover { background: #f8faff; }
        .properties-pre {
            font-size: 0.7rem;
            max-height: 200px;
            overflow-y: auto;
            background: #f8fafc;
            border-radius: 0.5rem;
            padding: 8px 10px;
            white-space: pre-wrap;
            word-break: break-all;
            border: 1px solid #e2e8f0;
        }
    </style>
@endpush

@section('content')
<div class="space-y-5">

    {{-- ── PAGE HEADER ─────────────────────────────────────── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-semibold text-gray-900">Audit Log Aktivitas</h1>
            <p class="text-xs text-gray-500 mt-0.5">Rekam jejak semua aksi penting yang terjadi di sistem</p>
        </div>
        <button type="button" onclick="document.getElementById('purge-modal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium rounded-xl transition-all border border-red-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Hapus Log Lama
        </button>
    </div>

    {{-- ── STAT CARDS ──────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl p-5 text-white shadow-md">
            <p class="text-xs text-indigo-200 uppercase tracking-wider font-medium">Total Log</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($stats['total']) }}</p>
            <p class="text-xs text-indigo-200 mt-1">Semua waktu</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-card">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Aktivitas Hari Ini</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['today']) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-card">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Login Hari Ini</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($stats['logins']) }}</p>
            <p class="text-xs text-gray-400 mt-1">Sesi masuk</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-card">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Hapus Hari Ini</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ number_format($stats['deletes']) }}</p>
            <p class="text-xs text-gray-400 mt-1">Aksi delete</p>
        </div>
    </div>

    {{-- ── CHART + EVENT DIST ───────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Aktivitas 7 hari --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-card p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Aktivitas 7 Hari Terakhir</h3>
            <div style="position:relative; height:180px;">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        {{-- Distribusi Event --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-card p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Distribusi Event</h3>
            <div class="space-y-2.5">
                @php $maxCount = $eventCounts->max() ?: 1; @endphp
                @foreach($eventCounts as $evt => $cnt)
                    @php
                        $pct = round(($cnt / $maxCount) * 100);
                        $colors = [
                            'login'        => ['bar' => 'bg-emerald-400', 'badge' => 'bg-emerald-100 text-emerald-700'],
                            'logout'       => ['bar' => 'bg-gray-300',    'badge' => 'bg-gray-100 text-gray-600'],
                            'create'       => ['bar' => 'bg-blue-400',    'badge' => 'bg-blue-100 text-blue-700'],
                            'update'       => ['bar' => 'bg-amber-400',   'badge' => 'bg-amber-100 text-amber-700'],
                            'delete'       => ['bar' => 'bg-red-400',     'badge' => 'bg-red-100 text-red-700'],
                            'approve'      => ['bar' => 'bg-teal-400',    'badge' => 'bg-teal-100 text-teal-700'],
                            'reject'       => ['bar' => 'bg-orange-400',  'badge' => 'bg-orange-100 text-orange-700'],
                            'verify'       => ['bar' => 'bg-cyan-400',    'badge' => 'bg-cyan-100 text-cyan-700'],
                            'toggle_status'=> ['bar' => 'bg-violet-400',  'badge' => 'bg-violet-100 text-violet-700'],
                        ];
                        $color = $colors[$evt] ?? ['bar' => 'bg-slate-300', 'badge' => 'bg-slate-100 text-slate-600'];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-medium {{ $color['badge'] }} px-2 py-0.5 rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $evt)) }}
                            </span>
                            <span class="text-xs font-semibold text-gray-600">{{ $cnt }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $color['bar'] }} rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if($eventCounts->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada data</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── LOG TABLE ───────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-card overflow-hidden">

        {{-- Header + Filter --}}
        <div class="px-5 py-4 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Daftar Aktivitas</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ number_format($logs->total()) }} log ditemukan</p>
                </div>
                <button type="button" onclick="toggleFilter()"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-xl transition-all
                    {{ request()->hasAny(['event','causer_id','causer_role','dari','sampai','q','subject_type'])
                        ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filter
                    @if(request()->hasAny(['event','causer_id','causer_role','dari','sampai','q','subject_type']))
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center">
                            {{ collect(['event','causer_id','causer_role','dari','sampai','q','log_name'])->filter(fn($k)=>request($k))->count() }}
                        </span>
                    @endif
                </button>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['event','causer_id','causer_role','dari','sampai','q','subject_type','log_name']) ? '' : 'hidden' }} mt-4">
                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-100">
                    <form method="GET" action="{{ route('admin.activity-log.index') }}"
                          class="flex flex-wrap items-end gap-3">

                        {{-- Search --}}
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">Cari</label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama user, deskripsi, IP..."
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>

                        {{-- Event --}}
                        <div class="w-full sm:w-40">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">Event</label>
                            <select name="event" id="event-select"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Semua Event</option>
                                @foreach($eventList as $evt)
                                    <option value="{{ $evt }}" {{ request('event') === $evt ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $evt)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- User --}}
                        <div class="flex-1 min-w-[160px]">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">User</label>
                            <select name="causer_id" id="user-select"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Semua User</option>
                                @foreach($userList as $u)
                                    <option value="{{ $u->id }}" {{ request('causer_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->username }} ({{ $u->role }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Role --}}
                        <div class="w-full sm:w-32">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">Role</label>
                            <select name="causer_role"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Semua Role</option>
                                <option value="admin"  {{ request('causer_role') === 'admin'  ? 'selected' : '' }}>Admin</option>
                                <option value="unit"   {{ request('causer_role') === 'unit'   ? 'selected' : '' }}>Unit</option>
                                <option value="umkm"   {{ request('causer_role') === 'umkm'   ? 'selected' : '' }}>UMKM</option>
                                <option value="system" {{ request('causer_role') === 'system' ? 'selected' : '' }}>System</option>
                            </select>
                        </div>

                        {{-- Dari --}}
                        <div class="w-full sm:w-36">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">Dari Tanggal</label>
                            <input type="date" name="dari" value="{{ request('dari') }}"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>

                        {{-- Sampai --}}
                        <div class="w-full sm:w-36">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">Sampai Tanggal</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm">
                                Terapkan
                            </button>
                            @if(request()->hasAny(['event','causer_id','causer_role','dari','sampai','q','subject_type','log_name']))
                                <a href="{{ route('admin.activity-log.index') }}"
                                    class="inline-flex items-center justify-center p-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-400 rounded-lg transition-colors shadow-sm"
                                    title="Reset Filter">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-44">Waktu</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-28">Event</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-28">Subjek</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-32">IP / Info</th>
                        <th class="px-5 py-3 w-16"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="log-row transition-colors cursor-pointer"
                            onclick="toggleDetail('log-{{ $log->id }}')">

                            {{-- Waktu --}}
                            <td class="px-5 py-3.5">
                                <p class="text-xs font-medium text-gray-700">
                                    {{ $log->created_at->translatedFormat('d M Y') }}
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5">
                                    {{ $log->created_at->format('H:i:s') }}
                                </p>
                                <p class="text-[11px] text-gray-300">
                                    {{ $log->created_at->diffForHumans() }}
                                </p>
                            </td>

                            {{-- User --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                                        {{ $log->causer_role === 'admin' ? 'bg-indigo-100 text-indigo-700'
                                            : ($log->causer_role === 'unit' ? 'bg-blue-100 text-blue-700'
                                            : ($log->causer_role === 'umkm' ? 'bg-amber-100 text-amber-700'
                                            : 'bg-gray-100 text-gray-500')) }}">
                                        {{ strtoupper(substr($log->causer_name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800">{{ $log->causer_name ?? 'System' }}</p>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-medium
                                            {{ $log->causer_role === 'admin' ? 'bg-indigo-50 text-indigo-600'
                                                : ($log->causer_role === 'unit' ? 'bg-blue-50 text-blue-600'
                                                : ($log->causer_role === 'umkm' ? 'bg-amber-50 text-amber-600'
                                                : 'bg-gray-50 text-gray-500')) }}">
                                            {{ ucfirst($log->causer_role ?? '-') }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Event --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $log->event_badge_class }}">
                                    {{ $log->event_label }}
                                </span>
                            </td>

                            {{-- Deskripsi --}}
                            <td class="px-5 py-3.5">
                                <p class="text-xs text-gray-700 leading-relaxed line-clamp-2">
                                    {{ $log->description ?? '-' }}
                                </p>
                            </td>

                            {{-- Subjek --}}
                            <td class="px-5 py-3.5">
                                @if($log->subject_type)
                                    <span class="text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-medium">
                                        {{ $log->subject_label_short }}
                                    </span>
                                    @if($log->subject_label)
                                        <p class="text-[11px] text-gray-500 mt-0.5 truncate max-w-[120px]">
                                            {{ $log->subject_label }}
                                        </p>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- IP --}}
                            <td class="px-5 py-3.5">
                                <p class="text-[11px] font-mono text-gray-500">{{ $log->ip_address ?? '-' }}</p>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-3.5 text-right" onclick="event.stopPropagation()">
                                <form action="{{ route('admin.activity-log.destroy', $log->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus log ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Detail (expanded) --}}
                        <tr id="log-{{ $log->id }}" class="hidden bg-indigo-50/40">
                            <td colspan="7" class="px-5 py-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold mb-1">User Agent</p>
                                        <p class="text-xs text-gray-600 break-all">{{ $log->user_agent ?? '-' }}</p>
                                    </div>
                                    @if($log->properties)
                                        <div>
                                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold mb-1">Perubahan Data</p>
                                            @if(isset($log->properties['old']) || isset($log->properties['new']))
                                                <div class="flex gap-3">
                                                    @if(isset($log->properties['old']) && count($log->properties['old']))
                                                        <div class="flex-1">
                                                            <p class="text-[10px] font-semibold text-red-500 mb-1">Sebelum</p>
                                                            <pre class="properties-pre text-red-700">{{ json_encode($log->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if(isset($log->properties['new']) && count($log->properties['new']))
                                                        <div class="flex-1">
                                                            <p class="text-[10px] font-semibold text-emerald-600 mb-1">Sesudah</p>
                                                            <pre class="properties-pre text-emerald-700">{{ json_encode($log->properties['new'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <pre class="properties-pre">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">Belum Ada Log Aktivitas</p>
                                    <p class="text-xs text-gray-400">Log akan muncul saat admin/<br>unit melakukan aksi di sistem.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ── PURGE MODAL ─────────────────────────────────────────────── --}}
<div id="purge-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('purge-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
        <h3 class="text-base font-semibold text-gray-900 mb-1">Hapus Log Lama</h3>
        <p class="text-sm text-gray-500 mb-5">Log yang dibuat sebelum tanggal yang dipilih akan dihapus permanen.</p>
        <form action="{{ route('admin.activity-log.destroy-bulk') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Hapus log sebelum tanggal</label>
                <input type="date" name="before_date" required
                    value="{{ now()->subMonths(3)->format('Y-m-d') }}"
                    class="block w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
            </div>
            <div class="mb-5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="confirm" value="yes" required class="rounded">
                    <span class="text-sm text-gray-600">Saya yakin ingin menghapus log-log ini</span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('purge-modal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                    Hapus Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        // Filter toggle
        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        // Detail row toggle
        function toggleDetail(id) {
            const row = document.getElementById(id);
            if (row) row.classList.toggle('hidden');
        }

        // TomSelect
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#event-select', { create:false, allowEmptyOption:true, placeholder:'Pilih event...' });
            new TomSelect('#user-select',  { create:false, allowEmptyOption:true, placeholder:'Cari user...',
                sortField:{ field:'text', direction:'asc' } });
        });

        // Daily Chart
        const dailyLabels = @json($dailyActivity->pluck('label'));
        const dailyCounts = @json($dailyActivity->pluck('count'));

        const ctx = document.getElementById('dailyChart').getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 180);
        grad.addColorStop(0, 'rgba(99,102,241,0.3)');
        grad.addColorStop(1, 'rgba(99,102,241,0)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Aktivitas',
                    data: dailyCounts,
                    backgroundColor: 'rgba(99,102,241,0.7)',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#94a3b8',
                        bodyColor: '#f8fafc',
                        padding: 10,
                        callbacks: { label: ctx => ctx.parsed.y + ' aksi' }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#94a3b8' } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, color: '#94a3b8', stepSize: 1 } }
                }
            }
        });
    </script>
@endpush
