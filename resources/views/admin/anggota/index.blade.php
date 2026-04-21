<x-app-layout>
    <x-slot name="header">Kelola Anggota</x-slot>

    @php
        $currentUrl = route('admin.anggota.index');
        $queryParams = request()->except(['sort', 'dir', 'page']);
    @endphp

    <div class="space-y-4">
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 flex-wrap">
                <form action="{{ $currentUrl }}" method="GET" class="w-full sm:w-64">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <x-ui.input type="text" name="search" :value="$search ?? ''" placeholder="Cari nama atau email..." icon="ri-search-2-line" />
                </form>

                <form action="{{ $currentUrl }}" method="GET" class="flex items-center gap-2">
                    @foreach(request()->except(['per_page', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <x-ui.select name="per_page" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </x-ui.select>
                </form>
            </div>

            <x-ui.button href="{{ route('admin.anggota.create') }}">
                <i class="ri-add-line"></i> Tambah Anggota
            </x-ui.button>
        </div>

        <x-ui.card>
            <x-ui.table>
                <x-slot name="head">
                    <x-ui.th>
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'name', 'dir' => ($sortBy === 'name' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Nama
                            @if($sortBy === 'name')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
                    <x-ui.th align="center">
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'email', 'dir' => ($sortBy === 'email' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Email
                            @if($sortBy === 'email')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
                    <x-ui.th align="center">
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'created_at', 'dir' => ($sortBy === 'created_at' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Terdaftar
                            @if($sortBy === 'created_at')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
                    <x-ui.th align="right">Aksi</x-ui.th>
                </x-slot>

                @forelse ($users as $user)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <x-ui.td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-100 to-orange-200 text-amber-700 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                            </div>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <span class="text-gray-500">{{ $user->email }}</span>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <span class="text-gray-700">{{ $user->created_at->format('d M Y') }}</span>
                        </x-ui.td>
                        <x-ui.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button href="{{ route('admin.anggota.edit', $user->id) }}" variant="action" size="icon" title="Edit">
                                    <i class="ri-edit-2-line"></i>
                                </x-ui.button>
                                <form action="{{ route('admin.anggota.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus anggota ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger-action" size="icon" title="Hapus">
                                        <i class="ri-delete-bin-line"></i>
                                    </x-ui.button>
                                </form>
                            </div>
                        </x-ui.td>
                    </tr>
                @empty
                    <x-ui.empty-state icon="ri-group-line" title="Belum ada anggota" colspan="4" />
                @endforelse
            </x-ui.table>

            @if($users->hasPages() || $users->total() > 0)
                <div class="px-4 py-3 border-t border-amber-100 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                    </p>
                    {{ $users->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
