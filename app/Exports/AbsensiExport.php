<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromView, ShouldAutoSize
{
    protected $from_date;
    protected $to_date;
    protected $keyword;

    public function __construct($from_date, $to_date, $keyword)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->keyword = $keyword;
    }


    public function view(): View
    {
        $from_date = Carbon::parse($this->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($this->to_date)->format('Y-m-d');
        $keyword = $this->keyword;
        $absensis = Absensi::with('user');

        if ($this->from_date && $this->to_date) {
            $absensis = $absensis->whereBetween('tanggal', [$from_date, $to_date]);
        }

        if ($this->keyword) {
            $absensis = $absensis->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        }

        $absensis = $absensis->get();

        return view('excel.data-absensi', [
            'absensis' => $absensis
        ]);
    }
}
