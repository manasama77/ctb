<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Models\Absensi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DataAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Data Absensi';

        $from_date = $request->from_date ?? now()->startOfMonth();
        $to_date = $request->to_date ?? now()->endOfMonth();
        $keyword = $request->keyword ?? null;

        $data = Absensi::with('user');

        if ($keyword) {
            $data = $data->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', '%'.$keyword.'%');
            });
        }

        if ($from_date && $to_date) {
            $data = $data->whereBetween('created_at', [$from_date, $to_date]);
        }

        if (Auth::user()->role == 'karyawan') {
            $data = $data->where('user_id', Auth::id());
        }

        $data = $data->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $jam_masuk = null;
        $jam_pulang = null;
        $data_absensi = Absensi::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::now()->format('Y-m-d'))
            ->first();

        if ($data_absensi) {
            $jam_masuk = $data_absensi->jam_masuk ? Carbon::parse($data_absensi->jam_masuk)->format('H:i:s') : null;
            $jam_pulang = $data_absensi->jam_pulang ? Carbon::parse($data_absensi->jam_pulang)->format('H:i:s') : null;
        }

        return view('pages.data-absensi', [
            'title' => $title,
            'datas' => $data,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'keyword' => $keyword,
            'jam_masuk' => $jam_masuk,
            'jam_pulang' => $jam_pulang,
        ]);
    }

    public function rekap(Request $request)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : now()->startOfMonth();
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : now()->endOfMonth();
        $keyword = $request->keyword ?? null;

        $file_name = $from_date->format('Y-m-d').'-'.$to_date->format('Y-m-d').'-Absensi-.xlsx';

        return Excel::download(new AbsensiExport($from_date, $to_date, $keyword), $file_name);
    }

    public function in()
    {
        $title = 'Absen Masuk';

        return view('pages.data-absensi-karyawan-in', [
            'title' => $title,
        ]);
    }

    public function in_store(Request $request)
    {
        try {
            $request->validate([
                'location' => ['required', 'string'],
                'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            $check = Absensi::where('user_id', Auth::id())
                ->whereDate('tanggal', Carbon::now()->format('Y-m-d'))
                ->first();

            if ($check) {
                if ($check->jam_masuk) {
                    return redirect()->route('data-absensi.in')->withErrors('Anda sudah absen masuk hari ini');
                }
            }

            Absensi::create([
                'user_id' => Auth::id(),
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'jam_masuk' => Carbon::now()->format('H:i:s'),
                'lokasi_masuk' => $request->location,
                'foto_masuk' => $request->file('foto')->store('absensi', 'public'),
            ]);

            return redirect()->route('data-absensi')->with('success', 'Absen masuk berhasil');
        } catch (Exception $e) {
            dd($e->getMessage());

            return redirect()->route('data-absensi.in')->withErrors('Absen masuk gagal: '.$e->getMessage());
        }
    }

    public function out()
    {
        $title = 'Absen Pulang';

        return view('pages.data-absensi-karyawan-out', [
            'title' => $title,
        ]);
    }

    public function out_store(Request $request)
    {
        try {
            $request->validate([
                'location' => ['required', 'string'],
                'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            $check = Absensi::where('user_id', Auth::id())
                ->whereDate('tanggal', Carbon::now()->format('Y-m-d'))
                ->first();

            if (! $check) {
                return redirect()->route('data-absensi.out')->withErrors('Anda belum absen masuk hari ini');
            }

            if (! $check->jam_masuk) {
                return redirect()->route('data-absensi.out')->withErrors('Anda belum absen masuk hari ini');
            } elseif ($check->jam_pulang) {
                return redirect()->route('data-absensi.out')->withErrors('Anda sudah absen pulang hari ini');
            }

            $check->update([
                'jam_pulang' => Carbon::now()->format('H:i:s'),
                'lokasi_pulang' => $request->location,
                'foto_pulang' => $request->file('foto')->store('absensi', 'public'),
            ]);

            return redirect()->route('data-absensi')->with('success', 'Absen pulang berhasil');
        } catch (Exception $e) {
            dd($e->getMessage());

            return redirect()->route('data-absensi.in')->withErrors('Absen pulang gagal: '.$e->getMessage());
        }
    }
}
