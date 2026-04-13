<div class="dropdown-wrapper inline-block">
    <button type="button" onclick="toggleDropdown('menu-{{ $item->uuid }}', this)"
        class="inline-flex items-center p-1.5 text-gray-300 hover:text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
        </svg>
    </button>
    <div id="menu-{{ $item->uuid }}" class="dropdown-menu">
        <div class="py-1">
            @php 
                $routePrefix = ($permissions['userRole'] === 'admin') ? 'admin.umkm.' : 'umkm.'; 
                $approveRoute = ($permissions['userRole'] === 'admin') ? $routePrefix . 'approve' : $routePrefix . 'verify';
            @endphp
            @if ($permissions['canEdit'])
                <a href="{{ route($routePrefix . 'edit', $item->uuid) }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            @endif
            @if ($permissions['canVerify'])
                <div class="border-t border-gray-100 mt-1 pt-1">
                    <p class="px-4 py-1 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                        Ubah Status</p>
                    @if ($item->status !== 'aktif' || ($item->user && !$item->user->is_active))
                        <button type="button" onclick="submitAction('{{ route($approveRoute, $item->uuid) }}', 'POST')"
                            class="flex items-center w-full px-4 py-2 text-sm text-green-600 hover:bg-green-50 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ ($item->status === 'aktif' && $item->user && !$item->user->is_active) ? 'Aktifkan Akun' : 'Aktifkan' }}
                        </button>
                    @endif
                    @if ($item->status !== 'nonaktif')
                        <button type="button" onclick="submitAction('{{ route($routePrefix . 'reject', $item->uuid) }}', 'POST')"
                            class="flex items-center w-full px-4 py-2 text-sm text-orange-600 hover:bg-orange-50 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Nonaktifkan
                        </button>
                    @endif
                </div>
            @endif
            @if ($permissions['canCreateAccount'] && !$item->user_id)
                <div class="{{ $permissions['canVerify'] ? '' : 'border-t border-gray-100 mt-1 pt-1' }}">
                    <button type="button" onclick="submitAction('{{ route($routePrefix . 'create-account', $item->uuid) }}', 'POST')"
                        class="flex items-center w-full px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Buat Akun
                    </button>
                </div>
            @endif
            @if ($permissions['canDelete'])
                <div class="border-t border-gray-100 mt-1 pt-1">
                    <button type="button" onclick="confirmDelete('{{ $item->uuid }}','{{ addslashes($item->nama_usaha) }}')"
                        class="flex items-center w-full px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
