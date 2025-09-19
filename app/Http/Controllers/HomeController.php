<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\DataKamar;
use App\Models\DataPenghuni;
use Illuminate\Http\Request;
use App\Models\PosterBeranda;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    // public function home()
    // {
    //     $posters = PosterBeranda::latest()->take(5)->get();
    //     $galeris = Galeri::latest()->get(); // ambil semua galeri, bisa juga ->take(12)
    
    //     return view('home.home', compact('posters', 'galeris'));
    // }
    public function home()
{
    $posters = PosterBeranda::latest()->take(5)->get();
    $galeris = Galeri::latest()->get();

    // Statistik data penghuni
    $jumlahPenghuni = DataPenghuni::count();
    $penghuniBelumBayar = DataPenghuni::whereHas('tagihan', function ($q) {
        $q->where('status', 'belum_dibayar');
    })->count();

    // Statistik kamar
    $jumlahKamarTerpakai = DataKamar::where('status_kamar', 'terpakai')->count();
    $jumlahKamarKosong = DataKamar::where('status_kamar', 'kosong')->count();
    $jumlahKamarRenovasi = DataKamar::where('status_kamar', 'renovasi')->count();

    // Statistik berdasarkan fasilitas dan lokasi
    $statFasilitas = DataKamar::select('fasilitas', DB::raw('count(*) as total'))
        ->groupBy('fasilitas')->pluck('total', 'fasilitas');

    $statLokasi = DataKamar::select('lokasi', DB::raw('count(*) as total'))
        ->groupBy('lokasi')->pluck('total', 'lokasi');

    return view('home.home', compact(
        'posters',
        'galeris',
        'jumlahPenghuni',
        'penghuniBelumBayar',
        'jumlahKamarTerpakai',
        'jumlahKamarKosong',
        'jumlahKamarRenovasi',
        'statFasilitas',
        'statLokasi'
    ));
}

    public function submitWA()
    {
        return view('ciantra/submitWA');
    }
    
}
