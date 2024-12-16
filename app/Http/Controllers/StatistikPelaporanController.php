<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelaporan;

class StatistikPelaporanController extends Controller
{
        public function getStatistikPelaporan()
    {
        // Get statistics grouped by report type and date
        $statistik = Pelaporan::selectRaw('DATE(created_at) as date, jenis_laporan, COUNT(id_pelaporan) as count')
            ->groupBy('date', 'jenis_laporan')  // Group by date and report type
            ->orderBy('date', 'asc')
            ->get();

        // Return data in JSON format
        return response()->json($statistik);
    }

}
