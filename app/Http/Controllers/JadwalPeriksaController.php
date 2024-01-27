<?php

namespace App\Http\Controllers;

use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JadwalPeriksaController extends Controller
{
    public function index()
    {
        $jadwal_periksa = JadwalPeriksa::with('dokter.poli')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data jadwal periksa',
            'data' => $jadwal_periksa
        ], 200);
    }

    public function ById($id)
    {
        $jadwal_periksa = JadwalPeriksa::with('dokter')->find($id);

        if (!$jadwal_periksa) {
            return response()->json([
                'error' => 'Jadwal not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data jadwal periksa',
            'data' => $jadwal_periksa
        ], 200);
    }

    public function postJadwal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_dokter' => 'required|string',
            'hari' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'tanggal' => 'required|date',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $existingActiveRecord = JadwalPeriksa::where('status', 'Y')->where('id_dokter', $request->input('id_dokter'))->first();
        if ($existingActiveRecord && $request->input('status') === 'Y') {
            return response()->json([
                'error' => 'Jadwal Dengan Status Aktif Ditemukan',
            ], 400);
        }

        $jadwal_periksa = JadwalPeriksa::create(
            array_merge(
                $validator->validated(),
                ['id' => Str::uuid()]
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Jadwal periksa berhasil disimpan',
            'data' => $jadwal_periksa
        ], 201);
    }

    public function updateJadwal(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_dokter' => 'required|string',
            'hari' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'tanggal' => 'required|date',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $existingActiveRecord = JadwalPeriksa::where('status', 'Y')->where('id_dokter', $request->input('id_dokter'))->first();
        if ($existingActiveRecord && $request->input('status') === 'Y') {
            return response()->json([
                'error' => 'Jadwal Dengan Status Aktif Ditemukan',
            ], 400);
        }

        $jadwal_periksa = JadwalPeriksa::findOrFail($id);

        if ($jadwal_periksa) {
            $jadwal_periksa->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Jadwal periksa berhasil diubah',
                'data' => $jadwal_periksa
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Jadwal periksa tidak ditemukan',
        ], 404);
    }

    public function destroyJadwal($id)
    {
        $jadwal_periksa = JadwalPeriksa::find($id);

        if ($jadwal_periksa) {
            $jadwal_periksa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal periksa berhasil dihapus',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Jadwal periksa tidak ditemukan',
        ], 404);
    }

    public function getAllJadwalByDokterId($dokterId)
{
    $jadwalPeriksa = JadwalPeriksa::where('id_dokter', $dokterId)
        ->with('dokter.poli')
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'Daftar jadwal periksa berdasarkan Dokter ID',
        'data' => $jadwalPeriksa,
    ], 200);
}
public function getAllJadwalByPoliId($poliId)
{
    $jadwalPeriksa = JadwalPeriksa::whereHas('dokter.poli', function ($query) use ($poliId) {
        $query->where('id', $poliId);
    })->with('dokter.poli')->get();

    return response()->json([
        'success' => true,
        'message' => 'Daftar jadwal periksa berdasarkan Poli ID',
        'data' => $jadwalPeriksa,
    ], 200);
}

}
