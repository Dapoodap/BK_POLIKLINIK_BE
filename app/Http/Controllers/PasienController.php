<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class PasienController extends Controller
{
    public function Index()
    {
        $pasien = Pasien::all();
        return response()->json([
            'success' => true,
            'message' => 'daftar data pasien',
            'data' => $pasien
        ], 200);
    }
    public function postPasien(Request $req)
    { {
            $validator  = Validator::make($req->all(), [
                'nama'      => 'required',
                'username'  => 'required|unique:pasien', // Perhatikan penambahan 'unique:pasien'
                'alamat'    => 'required',
                'no_hp'     => 'required',
                'no_ktp'    => 'required',
                'password'  => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $jumlahPasien = Pasien::count();

            // Membuat nomor RM dengan format YYYYMM-XXX
            $nomorRM = date('Ym') . '-' . sprintf('%03d', $jumlahPasien + 1);
            $pasien = new Pasien();
            $pasien->id = Str::uuid(); // Membuat UUID baru
            $pasien->nama = $req->input('nama');
            $pasien->username = $req->input('username');
            $pasien->alamat = $req->input('alamat');
            $pasien->no_hp = $req->input('no_hp');
            $pasien->no_ktp = $req->input('no_ktp');
            $pasien->role = $req->input('role');
            $pasien->no_rm = $nomorRM;
            $pasien->password = bcrypt($req->input('password')); // Gunakan bcrypt untuk menyandikan password
            $pasien->save();
            return response()->json([
                "message" => "pasien added"
            ], 201);
        }
    }
    public function LoginPasien(request $req)
    {
        $validator = Validator::make($req->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input',
                'details' => $validator->errors(),
            ], 422);
        }

        $credentials = $req->only('username', 'password');
        $user = Pasien::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login successful
            $customClaims = ['id' => $user->id, 'role' => $user->role];
            $token = JWTAuth::claims($customClaims)->fromUser($user);

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ], 200);
        } else {
            // Invalid credentials
            return response()->json([
                'error' => 'Invalid credentials',
            ], 401);
        }
    }
    public function getById($id)
    {
        $pasien = Pasien::findOrFail($id);

        if (!$pasien) {
            return response()->json([
                'error' => 'pasien not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'data pasien',
            'data' => $pasien
        ], 200);
    }
    public function updatePasien(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'nama' => 'required',
            'username' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'no_ktp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pasien = Pasien::findOrFail($id);

        if (!$pasien) {
            return response()->json([
                'error' => 'Pasien not found',
            ], 404);
        }

        // Update the pasien information
        $pasien->nama = $req->input('nama');
        $pasien->alamat = $req->input('alamat');
        $pasien->no_hp = $req->input('no_hp');
        $pasien->no_ktp = $req->input('no_ktp');
        $pasien->username = $req->input('username');
        $pasien->save();

        return response()->json([
            "message" => "Pasien updated",
            "data" => $pasien,
        ], 200);
    }
    public function deletePasien($id)
    {
        $pasien = Pasien::findOrFail($id);

        if (!$pasien) {
            return response()->json([
                'error' => 'Pasien not found',
            ], 404);
        }

        // Delete the pasien
        $pasien->delete();

        return response()->json([
            "message" => "Pasien deleted",
        ], 200);
    }
}
