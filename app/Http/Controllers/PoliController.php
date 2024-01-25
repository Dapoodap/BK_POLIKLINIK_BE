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
        return response()->json([
            'success' => true,
            'message' => 'daftar data poli',
            'data' => $poli
        ], 200);
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
    public function getPoliById($id)
    {
        $poli = Poli::find($id);

        if (!$poli) {
            return response()->json([
                'error' => 'Poli not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'data poli',
            'data' => $poli
        ], 200);
    }
    public function editPoli(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'nama_poli'  => 'required|unique:poli,nama_poli,' . $id,
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $poli = Poli::find($id);

        if (!$poli) {
            return response()->json([
                'error' => 'Poli not found',
            ], 404);
        }

        $poli->nama_poli = $req->input('nama_poli');
        $poli->keterangan = $req->input('keterangan');
        $poli->save();

        return response()->json([
            "message" => "Poli updated",
            "data" => $poli,
        ], 200);
    }

    public function deletePoli($id)
    {
        $poli = Poli::find($id);

        if (!$poli) {
            return response()->json([
                'error' => 'Poli not found',
            ], 404);
        }

        $poli->delete();

        return response()->json([
            "message" => "Poli deleted",
        ], 200);
    }
}
