<?php

namespace App\Http\Controllers;

use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PeriksaController extends Controller
{
    public function Index()
    {
        $periksa = Periksa::with('daftar_poli', 'daftar_poli.pasien', 'daftar_poli.jadwal_periksa', 'daftar_poli.jadwal_periksa.dokter')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data periksa',
            'data' => $periksa
        ], 200);
    }
    public function ByDaftarPoliId($id)
    {
        $periksa = Periksa::with('daftar_poli', 'daftar_poli.pasien', 'daftar_poli.jadwal_periksa', 'daftar_poli.jadwal_periksa.dokter' )->where('id_daftar_poli', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data periksa',
            'data' => $periksa
        ], 200);
    }
    public function ById($id)
    {
        $periksa = Periksa::with('daftar_poli', 'daftar_poli.pasien', 'daftar_poli.jadwal_periksa')->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail data periksa',
            'data' => $periksa
        ], 200);
    }
    public function postPeriksa(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id_daftar_poli' => 'required|string',
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
            'biaya_periksa' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $periksa = new Periksa();
        $periksa->id = Str::uuid(); // Membuat UUID baru
        $periksa->id_daftar_poli = $req->input('id_daftar_poli');
        $periksa->tanggal = $req->input('tanggal');
        $periksa->catatan = $req->input('catatan');
        $periksa->biaya_periksa = $req->input('biaya_periksa');
        $periksa->save();
        return response()->json([
            "message" => "periksa added"
        ],201);
    }
    public function updatePeriksa(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'id_daftar_poli' => 'required|string',
            'tgl_periksa' => 'required|date',
            'catatan' => 'required|string',
            'biaya_periksa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $periksa = Periksa::find($id);

        if (!$periksa) {
            return response()->json([
                'success' => false,
                'message' => 'Data periksa tidak ditemukan',
                'data' => ''
            ], 404);
        }

        $periksa->update(
            $validator->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Data periksa berhasil diubah',
            'data' => $periksa
        ], 200);
    }

    public function destroyPeriksa($id)
    {
        $periksa = Periksa::find($id);

        if (!$periksa) {
            return response()->json([
                'success' => false,
                'message' => 'Data periksa tidak ditemukan',
                'data' => ''
            ], 404);
        }

        $periksa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data periksa berhasil dihapus',
            'data' => $periksa
        ], 200);
    }
}
