<x-layouts.app :title="$title">
    <section class="w-full h-full">
        <x-page-title :title="$title" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-5">
            <x-cards.absensi variant="primary" title="Absen Masuk" text="{{ $jam_masuk }}" icon="fas fa-sign-in"
                url="{{ route('data-absensi.in') }}" />
            <x-cards.absensi variant="danger" title="Absen Pulang" text="{{ $jam_pulang }}" icon="fas fa-sign-out"
                url="{{ route('data-absensi.out') }}" />
        </div>
        <div class="flex flex-col gap-4">
            @if (auth()->user()->role == 'admin')
                <div class="card w-full md:max-w-sm">
                    <div class="card-header">
                        <h5 class="card-title">Filter</h5>
                    </div>
                    <div class="card-body">
                        <form method="get" action="{{ route('data-absensi') }}" class="space-y-2">
                            <div>
                                <label class="label-text" for="from_date">Dari Tanggal</label>
                                <input type="date" class="input" id="from_date" name="from_date"
                                    value={{ $from_date }} required />
                                <div>
                                    @error('from_date')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="label-text" for="from_date">Dari Tanggal</label>
                                <input type="date" class="input" id="from_date" name="to_date"
                                    value={{ $to_date }} required />
                                <div>
                                    @error('to_date')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="label-text" for="keyword">Karyawan</label>
                                <input type="text" class="input " id="keyword" name="keyword"
                                    placeholder="Nama Karyawan" value="{{ $keyword }}" />
                            </div>
                            <div>
                                <button class="btn btn-primary w-full" type="submit">
                                    <i class="fas fa-filter"></i>
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            <div class="card">
                @if (auth()->user()->role == 'admin')
                    <div class="card-header flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('data-absensi.rekap', ['from_date' => $from_date, 'to_date' => $to_date, 'keyword' => $keyword]) }}"
                                target="_blank" class="btn btn-primary">
                                <i class="fas fa-calendar"></i>
                                Rekap Data
                            </a>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <div class="w-full overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Karyawan</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Jam Masuk</th>
                                    <th class="text-center">Jam Pulang</th>
                                    <th class="text-center">Lama Kerja</th>
                                    <th class="text-center">Lokasi</th>
                                    <th class="text-center">
                                        <i class="fas fa-cog">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($datas->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Tidak ada data absensi</td>
                                    </tr>
                                @endif
                                @foreach ($datas as $absensi)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $absensi->user->name }}</td>
                                        <td class="text-center">{{ $absensi->tanggal }}</td>
                                        <td class="text-center">{{ $absensi->jam_masuk }}</td>
                                        <td class="text-center">{{ $absensi->jam_pulang }}</td>
                                        <td class="text-center">{{ $absensi->lama_kerja }}</td>
                                        <td class="text-center">
                                            <div class="flex justify-center gap-1">
                                                @if ($absensi->jam_masuk)
                                                    <a href="{{ $absensi->lokasi_masuk_gmap }}" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-map-marker"></i>
                                                        Lokasi Masuk
                                                    </a>
                                                @endif

                                                @if ($absensi->jam_pulang)
                                                    <a href="{{ $absensi->lokasi_pulang_gmap }}" target="_blank"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-map-marker"></i>
                                                        Lokasi Pulang
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary" id="btn_detail_{{ $absensi->id }}"
                                                data-foto_masuk="{{ $absensi->foto_masuk_asset }}"
                                                data-foto_pulang="{{ $absensi->foto_pulang_asset }}"
                                                onClick="showModalDetail({{ $absensi->id }})">Detail</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $datas->links() }}
                    </div>
                </div>
            </div>
        </div>

    </section>

    @push('modals')
        <div id="modal-detail"
            class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 hidden [--is-layout-affect:true] overlay-backdrop-open:bg-neutral-800/80 "
            role="dialog" tabindex="-1">
            <div class="modal-dialog overlay-open:opacity-100 overlay-open:duration-300 !bg-transparent">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Detail</h3>
                        <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                            aria-label="Close" onClick="closeModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onClick="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    {{-- @vite('resources/js/modal_absensi.js'); --}}
    {{-- @push('scripts')
        <script type="module">
            showModalDetail(1)
        </script>
    @endpush --}}


</x-layouts.app>
