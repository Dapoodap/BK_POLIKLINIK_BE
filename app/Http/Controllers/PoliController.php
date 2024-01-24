<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PoliController extends Controller
{
    public function Index()
    {
        $poli = Poli::all();
        return response()->json($poli);
    }
    public function postPoli(request $req)
    {
          $validator  = Validator::make($req->all(), [
            'nama_poli'  => 'required:unique:poli',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $poli = new Poli();
        $poli->id = Str::uuid(); // Membuat UUID baru
        $poli->nama_poli = $req->input('nama_poli');
        $poli->keterangan = $req->input('keterangan');
        $poli->save();
        return response()->json([
            "message" => "poli added"
        ],201);
    }
}
