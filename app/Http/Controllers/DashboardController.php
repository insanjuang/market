<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            if (auth()->user()->level == 1) {
            return view('pages.dashboard.admin');
        } else {
            return view('pages.dashboard.kasir');
        }
        }
  
        return redirect("login")->withSuccess('You are not allowed to access');
    //     $kategori = Kategori::count();
    //     $produk = Produk::count();
    //     $supplier = Supplier::count();
    //     $member = Member::count();

    //     $tanggal_awal = date('Y-m-01');
    //     $tanggal_akhir = date('Y-m-d');

    //     $data_tanggal = array();
    //     $data_pendapatan = array();

    //     while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
    //         $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

    //         $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
    //         $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
    //         $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

    //         $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
    //         $data_pendapatan[] += $pendapatan;

    //         $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
    //     }

    //     $tanggal_awal = date('Y-m-01');

    //     if (auth()->user()->level == 1) {
    //         return view('admin.dashboard', compact('kategori', 'produk', 'supplier', 'member', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
    //     } else {
    //         return view('kasir.dashboard');
    //     }
    }
}
