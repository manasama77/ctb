<?php

use App\Models\Absensi;
use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Title};
use Illuminate\Support\Collection;

new #[Layout('components.layouts.app')] #[Title('Dashboard')] class extends Component {
    public $title = 'DASHBOARD';
    public $total_absensi_saya = 0;
    public $total_absensi = 0;
    public $total_karyawan = 0;
    public $total_admin = 0;
    public $data_belum_absen;
    public $data_absen_masuk;
    public $data_absen_pulang;

    public function mount(): void
    {
        $this->total_absensi_saya = $this->hitungAbsensiSaya();
        $this->total_absensi = $this->hitungAbsensi();
        $this->total_karyawan = $this->hitungKaryawan();
        $this->total_admin = $this->hitungAdmin();
        $this->data_belum_absen = $this->dataBelumAbsen();
        $this->data_absen_masuk = $this->dataAbsenMasuk();
        $this->data_absen_pulang = $this->dataAbsenPulang();
    }

    #[Computed]
    public function hitungAbsensiSaya(): int
    {
        $user_id = Auth::user()->id;
        $absensi = Absensi::where('user_id', $user_id)
            ->whereMonth('tanggal', now()->format('m'))
            ->whereYear('tanggal', now()->format('Y'))
            ->count();

        return $absensi;
    }

    #[Computed]
    public function hitungAbsensi(): int
    {
        $absensi = Absensi::whereMonth('tanggal', now()->format('m'))
            ->whereYear('tanggal', now()->format('Y'))
            ->count();

        return $absensi;
    }

    #[Computed]
    public function hitungKaryawan(): int
    {
        $karyawan = User::where('role', 'karyawan')->count();

        return $karyawan;
    }

    #[Computed]
    public function hitungAdmin(): int
    {
        $admin = User::where('role', 'admin')->count();

        return $admin;
    }

    #[Computed]
    public function dataBelumAbsen(): Collection
    {
        $belumAbsen = User::select('name')
            ->whereDoesntHave('absensi', function ($query) {
                $query->whereDate('tanggal', now());
            })
            ->get();

        return $belumAbsen;
    }

    #[Computed]
    public function dataAbsenMasuk(): Collection
    {
        $absenMasuk = User::select('name')
            ->whereHas('absensi', function ($query) {
                $query->whereDate('tanggal', now())->whereNotNull('jam_masuk');
            })
            ->get();
        return $absenMasuk;
    }

    #[Computed]
    public function dataAbsenPulang(): Collection
    {
        $absenPulang = User::select('name')
            ->whereHas('absensi', function ($query) {
                $query->whereDate('tanggal', now())->whereNotNull('jam_pulang');
            })
            ->get();
        return $absenPulang;
    }
};
?>

<section class="w-full h-full">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <x-page-title :title="$title" />

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            <x-cards.counter variant="danger" title="Total Absensi Saya" total="{{ $total_absensi_saya }}"
                icon="fas fa-user" />
            @if (auth()->user()->role == 'admin')
                <x-cards.counter variant="info" title="Total Absensi" total="{{ $total_absensi }}"
                    icon="fas fa-user-group" />
                <x-cards.counter variant="primary" title="Total Karyawan" total="{{ $total_karyawan }}"
                    icon="fas fa-user-group" />
                <x-cards.counter variant="warning" title="Total Admin" total="{{ $total_admin }}"
                    icon="fas fa-user-group" />
            @endif
        </div>
        @if (auth()->user()->role == 'admin')
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <x-cards.base-card>
                    <h1 class="text-lg font-semibold mb-2 text-base-100 dark:text-white">Belum Absen Hari Ini</h1>
                    <table
                        class="w-full table-auto border-collapse border border-neutral-200 dark:border-neutral-700 overflow-auto text-sm ">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-neutral-800">
                                <th
                                    class="border border-neutral-200 dark:border-neutral-700 py-2 text-center max-w-[10px] text-base-100 dark:text-white bg-neutral-300 ">
                                    #</th>
                                <th
                                    class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-left text-base-100 dark:text-white bg-neutral-300">
                                    Nama
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data_belum_absen->isEmpty())
                                <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-700/50">
                                    <td colspan="2"
                                        class="border border-neutral-200 dark:border-neutral-700 py-2 text-center text-base-100 dark:text-white bg-neutral-300 dark:!bg-neutral-700/50">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endif
                            @foreach ($data_belum_absen as $data)
                                <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-700/50">
                                    <td class="border border-neutral-200 dark:border-neutral-700 py-2 text-center ">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">
                                        {{ $data->name }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-cards.base-card>

                <x-cards.base-card>
                    <h1 class="text-lg font-semibold mb-2 text-base-100 dark:text-white">Sudah Absen Masuk Hari Ini</h1>
                    <table
                        class="w-full table-auto border-collapse border border-neutral-200 dark:border-neutral-700 overflow-auto text-sm">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-neutral-800">
                                <th
                                    class="border border-neutral-200 dark:border-neutral-700 py-2 text-center max-w-[10px] text-base-100 dark:text-white bg-neutral-300">
                                    #</th>
                                <th
                                    class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-left text-base-100 dark:text-white bg-neutral-300">
                                    Nama
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data_absen_masuk->isEmpty())
                                <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-700/50">
                                    <td colspan="2"
                                        class="border border-neutral-200 dark:border-neutral-700 py-2 text-center text-base-100 dark:text-white bg-neutral-300 dark:!bg-neutral-700/50">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endif
                            @foreach ($data_absen_masuk as $data)
                                <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-700/50">
                                    <td class="border border-neutral-200 dark:border-neutral-700 py-2 text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">
                                        {{ $data->name }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-cards.base-card>

                <x-cards.base-card>
                    <h1 class="text-lg font-semibold mb-2 text-base-100 dark:text-white">Sudah Absen Pulang Hari Ini
                    </h1>
                    <table
                        class="w-full table-auto border-collapse border border-neutral-200 dark:border-neutral-700 overflow-auto text-sm">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-neutral-800">
                                <th
                                    class="border border-neutral-200 dark:border-neutral-700 py-2 text-center max-w-[10px] text-base-100 dark:text-white bg-neutral-300">
                                    #</th>
                                <th
                                    class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-left text-base-100 dark:text-white bg-neutral-300">
                                    Nama
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data_absen_pulang->isEmpty())
                                <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-700/50">
                                    <td colspan="2"
                                        class="border border-neutral-200 dark:border-neutral-700 py-2 text-center dark:!bg-neutral-700/50">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endif
                            @foreach ($data_absen_pulang as $data)
                                <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-700/50">
                                    <td class="border border-neutral-200 dark:border-neutral-700 py-2 text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">
                                        {{ $data->name }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-cards.base-card>
            </div>
        @endif
    </div>
</section>
