<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ObatController extends Controller
{
    public function index()
    {
        $obat = Obat::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data obat',
            'data' => $obat
        ], 200);
    }
    public function ById($id)
    {
        $obat = Obat::find($id);

        if (!$obat) {
            return response()->json([
                'error' => 'obat not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Detail data obat',
            'data' => $obat
        ], 200);
    }
    public function postObat(request $req)
    {
          $validator  = Validator::make($req->all(), [
            'nama_obat' => 'required',
            'kemasan' => 'required',
            'harga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $obat = new Obat();
        $obat->id = Str::uuid(); // Membuat UUID baru
        $obat->nama_obat = $req->input('nama_obat');
        $obat->kemasan = $req->input('kemasan');
        $obat->harga = $req->input('harga');
        $obat->save();
        return response()->json([
            "message" => "obat added"
        ],201);
    }
    public function updateObat(Request $request, $id)
    {
        $obat = Obat::find($id);

        if ($obat) {
            $validator = Validator::make($request->all(), [
                'nama_obat' => 'required',
                'kemasan' => 'required',
                'harga' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $obat->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil diupdate',
                'data' => $obat
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Obat tidak ditemukan',
        ], 404);
    }
    public function destroyObat($id)
    {
        $obat = Obat::find($id);

        if ($obat) {
            $obat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil dihapus',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Obat tidak ditemukan',
        ], 404);
    }
}
