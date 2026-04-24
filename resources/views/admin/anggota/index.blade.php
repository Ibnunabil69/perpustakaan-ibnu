<x-app-layout>
    <x-slot name="header">Kelola Anggota</x-slot>

    @php
        $currentUrl = route('admin.anggota.index');
        $queryParams = request()->except(['sort', 'dir', 'page']);
    @endphp

    <div class="space-y-5">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Anggota</h3>
            <p class="text-sm text-gray-500">Kelola data warga sekolah yang terdaftar</p>
        </div>

        <x-ui.card>
            <div class="px-5 py-4 border-b border-amber-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full">
                    <form action="{{ $currentUrl }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-2 flex-1">
                        @foreach(request()->except(['search', 'per_page', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        
                        <div class="relative flex-1 md:w-64">
                            <x-ui.input type="text" name="search" :value="$search ?? ''" placeholder="Cari nama atau email..." class="!pl-9 w-full" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400 text-sm"></i>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <x-ui.select name="per_page" class="w-20" :value="$perPage" onchange="this.form.submit()">
                                <x-ui.select-option value="10">10</x-ui.select-option>
                                <x-ui.select-option value="25">25</x-ui.select-option>
                                <x-ui.select-option value="50">50</x-ui.select-option>
                            </x-ui.select>
                            
                            <x-ui.button type="submit" variant="primary" size="icon" class="flex-shrink-0" title="Filter">
                                <i class="ri-filter-3-line"></i>
                            </x-ui.button>
                        </div>
                    </form>

                    <div class="hidden md:block w-px h-8 bg-amber-100 mx-1"></div>

                    <x-ui.button href="{{ route('admin.anggota.create') }}" class="flex-shrink-0">
                        <i class="ri-add-line mr-1.5"></i> Baru
                    </x-ui.button>
                </div>
            </div>
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
                    <x-ui.th>No. Telepon</x-ui.th>
                    <x-ui.th align="center">Email</x-ui.th>
                    <x-ui.th align="center">Terdaftar</x-ui.th>
                    <x-ui.th align="right">Aksi</x-ui.th>
                </x-slot>

                @forelse ($users as $user)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <x-ui.td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-100 to-orange-200 text-amber-700 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="font-semibold text-gray-900 leading-tight">{{ $user->name }}</div>
                            </div>
                        </x-ui.td>
                        <x-ui.td>
                            @if($user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                    <i class="ri-whatsapp-line text-lg"></i>
                                    <span class="text-sm font-mono tracking-tighter">{{ $user->phone }}</span>
                                </a>
                            @else
                                <span class="text-gray-300 text-xs italic">Belum ada No. Telp</span>
                            @endif
                        </x-ui.td>
                        <x-ui.td align="center">
                            <span class="text-sm text-gray-500">{{ $user->email }}</span>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <span class="text-gray-700">{{ $user->created_at->format('d M Y') }}</span>
                        </x-ui.td>
                        <x-ui.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button href="{{ route('admin.anggota.edit', $user->id) }}" variant="action" size="icon" title="Edit">
                                    <i class="ri-edit-2-line"></i>
                                </x-ui.button>
                                <x-ui.button type="button" variant="danger-action" size="icon" title="Hapus"
                                    @click="$dispatch('open-modal', { name: 'delete-user-{{ $user->id }}' })">
                                    <i class="ri-delete-bin-line"></i>
                                </x-ui.button>

                                @push('modals')
                                    <x-ui.modal name="delete-user-{{ $user->id }}" title="Hapus Anggota">
                                        <div class="space-y-3">
                                            <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto text-rose-500 mb-4">
                                                <i class="ri-user-unfollow-line text-3xl"></i>
                                            </div>
                                            <p class="text-sm text-gray-600 text-center">
                                                Hapus akun anggota <span class="font-bold text-gray-800">"{{ $user->name }}"</span>?
                                                <br>
                                                <span class="text-[10px] text-gray-400 mt-2 block italic">Akses anggota ke sistem akan segera dicabut.</span>
                                            </p>
                                        </div>
                                        <x-slot name="footer">
                                            <form action="{{ route('admin.anggota.destroy', $user->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <x-ui.button type="submit" variant="danger">Ya, Hapus Akun</x-ui.button>
                                            </form>
                                            <x-ui.button @click="$dispatch('close-modal', { name: 'delete-user-{{ $user->id }}' })" variant="outline">Batal</x-ui.button>
                                        </x-slot>
                                    </x-ui.modal>
                                @endpush
                            </div>
                        </x-ui.td>
                    </tr>
                @empty
                    <x-ui.empty-state icon="ri-group-line" title="Belum ada anggota" colspan="5" />
                @endforelse
            </x-ui.table>

            @if($users->hasPages() || $users->total() > 0)
                <div class="px-5 py-4 bg-gray-50/50 border-t border-amber-50">
                    {{ $users->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
