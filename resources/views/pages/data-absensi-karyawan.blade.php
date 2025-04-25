<x-layouts.app :title="$title">
    <section class="w-full h-full">
        <x-page-title :title="$title" />
        <div class="grid grid-cols-2 gap-3 mb-5">
            <x-cards.absensi variant="primary" title="Absen Masuk" text="" icon="fas fa-sign-in" />
        </div>
        <div class="flex flex-col gap-4">
            <div class="card">
                <div class="card-body">
                    <div class="w-full overflow-x-auto">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                        <td colspan="7" class="text-center py-4">Tidak ada data absensi</td>
                                    </tr>
                                @endif
                                @foreach ($datas as $absensi)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
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
            <div class="modal-dialog overlay-open:opacity-100 overlay-open:duration-300">
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
