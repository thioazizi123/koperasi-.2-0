<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavingController extends Controller
{
    public function wajib()
    {
        return view('simpanan.wajib');
    }

    public function pokok()
    {
        return view('simpanan.pokok');
    }

    public function operasional()
    {
        return view('simpanan.operasional');
    }
}
