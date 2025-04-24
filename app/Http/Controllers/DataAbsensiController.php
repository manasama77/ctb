<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Exports\AbsensiExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\DataAbsensiResource;

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
                $query->where('name', 'like', '%' . $keyword . '%');
            });
        }

        if ($from_date && $to_date) {
            $data = $data->whereBetween('created_at', [$from_date, $to_date]);
        }

        $data = $data->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('pages.data-absensi', [
            'title' => $title,
            'datas' => $data,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'keyword' => $keyword,
        ]);
    }

    public function rekap(Request $request)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : now()->startOfMonth();
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : now()->endOfMonth();
        $keyword = $request->keyword ?? null;

        $file_name = $from_date->format('Y-m-d') . '-' . $to_date->format('Y-m-d') . '-Absensi-.xlsx';

        return Excel::download(new AbsensiExport($from_date, $to_date, $keyword), $file_name);
    }
}
