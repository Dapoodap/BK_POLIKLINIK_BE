<?php

namespace App\Http\Controllers;

use App\Models\DetailPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DetailPeriksaController extends Controller
{
    public function Index()
    {
        $detail_periksa = DetailPeriksa::with('periksa','periksa.daftar_poli','periksa.daftar_poli.pasien', 'obat')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data detail_periksa',
            'data' => $detail_periksa
        ], 200);
    }
    public function GetByPeriksaId($id)
    {
        $detail_periksa = DetailPeriksa::with('periksa','periksa.daftar_poli','periksa.daftar_poli.jadwal_periksa','periksa.daftar_poli.jadwal_periksa.dokter','periksa.daftar_poli.pasien', 'obat')->where('id_periksa', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data detail_periksa',
            'data' => $detail_periksa
        ], 200);
    }
    public function ById($id)
    {
        $detail_periksa = DetailPeriksa::with('periksa', 'obat')->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail data detail_periksa',
            'data' => $detail_periksa
        ], 200);
    }
    public function postDetail(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id_periksa' => 'required|string',
            'id_obat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $detail = new DetailPeriksa();
        $detail->id = Str::uuid(); // Membuat UUID baru
        $detail->id_periksa = $req->input('id_periksa');
        $detail->id_obat = $req->input('id_obat');
        $detail->save();
        return response()->json([
            "message" => "detail periksa added"
        ], 201);
    }
    public function updateDetail(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'id_periksa' => 'required|string',
            'id_obat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $detail_periksa = DetailPeriksa::findOrFail($id);

        if (!$detail_periksa) {
            return response()->json([
                'success' => false,
                'message' => 'Data detail_periksa tidak ditemukan',
            ], 404);
        }

        $detail_periksa->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data detail_periksa berhasil diubah',
            'data' => $detail_periksa
        ], 200);
    }

    public function destroyDetail($id)
    {
        $detail_periksa = DetailPeriksa::find($id);

        if (!$detail_periksa) {
            return response()->json([
                'success' => false,
                'message' => 'Data detail_periksa tidak ditemukan',
            ], 404);
        }

        $detail_periksa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data detail_periksa berhasil dihapus',
        ], 200);
    }

    public function destroyByIdObat($id, $idPeriksa)
    {
        $detail_periksa = DetailPeriksa::where('id_obat', $id)->where('id_periksa', $idPeriksa)->first();

        if (!$detail_periksa) {
            return response()->json([
                'success' => false,
                'message' => 'Data detail_periksa tidak ditemukan',
            ], 404);
        }

        $detail_periksa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data detail_periksa berhasil dihapus',
        ], 200);
    }
    public function destroyDetailByPeriksaId($idPeriksa)
    {
        $affectedRows = DetailPeriksa::where('id_periksa', $idPeriksa)->delete();
    
        if ($affectedRows === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data detail_periksa yang dihapus berdasarkan ID periksa',
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Semua data detail_periksa berhasil dihapus berdasarkan ID periksa',
        ], 200);
    }
    
}
