<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DaftarPoliController extends Controller
{
    public function index()
    {
        $daftar_poli = DaftarPoli::with('pasien', 'jadwal_periksa')->get();


        return response()->json([
            'success' => true,
            'message' => 'Daftar data daftar poli',
            'data' => $daftar_poli
        ], 200);
    }
    public function ById ($id)
    {
        $daftar_poli = DaftarPoli::with('pasien', 'jadwal_periksa')->find($id);

        if (!$daftar_poli) {
            return response()->json([
                'error' => 'pendaftaran not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Detail data daftar poli',
            'data' => $daftar_poli
        ], 200);

    }
    public function postDaftar (Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id_pasien' => 'required|string',
            'id_jadwal' => 'required|string',
            'keluhan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $nomorantrian = DaftarPoli::where('id_jadwal', $req->id_jadwal)->max('no_antrian');
        if (!$nomorantrian) {
            $nomorselanjutnya = 1;
        } else {
            $nomorselanjutnya = $nomorantrian + 1;
        }
        $daftar = new DaftarPoli();
        $daftar->id = Str::uuid(); // Membuat UUID baru
        $daftar->id_pasien = $req->input('id_pasien');
        $daftar->id_jadwal = $req->input('id_jadwal');
        $daftar->keluhan = $req->input('keluhan');
        $daftar->no_antrian = $nomorselanjutnya;
        $daftar->save();
        return response()->json([
            "message" => "pendaftaran added"
        ],201);
    }
    public function updateDaftar(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'id_pasien' => 'required|string',
            'id_jadwal' => 'required|string',
            'keluhan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $daftar_poli = DaftarPoli::find($id);

        if (!$daftar_poli) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $daftar_poli->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $daftar_poli
        ], 200);
    }
    public function destroyDaftar($id)
    {
        $daftar_poli = DaftarPoli::find($id);

        if (!$daftar_poli) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $daftar_poli->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ], 200);
    }
    public function getByPasienId($id)
    {
        $daftar_poli = DaftarPoli::with('pasien', 'jadwal_periksa')->where('id_pasien', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Detail data daftar poli',
            'data' => $daftar_poli
        ], 200);
    }
}
