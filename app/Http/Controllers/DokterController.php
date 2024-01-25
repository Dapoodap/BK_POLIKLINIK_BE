<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class DokterController extends Controller
{
    public function Index()
    {
        $dokter = Dokter::with('poli')->get();
        return response()->json([
            'success' => true,
            'message' => 'daftar data dokter',
            'data' => $dokter
        ], 200);
    }
    public function postDokter(Request $req)
    {
        {
            $validator  = Validator::make($req->all(), [
                'nama'      => 'required',
                'username'  => 'required|unique:dokter', // Perhatikan penambahan 'unique:pasien'
                'alamat'    => 'required',
                'no_hp'     => 'required',
                'id_poli'    => 'required',
                'password'  => 'required',
          ]);
  
          if ($validator->fails()) {
              return response()->json($validator->errors(), 400);
          }
    
          $dokter = new Dokter();
          $dokter->id = Str::uuid(); // Membuat UUID baru
          $dokter->nama = $req->input('nama');
          $dokter->username = $req->input('username');
          $dokter->alamat = $req->input('alamat');
          $dokter->no_hp = $req->input('no_hp');
          $dokter->id_poli = $req->input('id_poli');
          $dokter->role = $req->input('role');
          $dokter->password = bcrypt($req->input('password')); // Gunakan bcrypt untuk menyandikan password
          $dokter->save();
          return response()->json([
              "message" => "dokter added"
          ],201);
      }
    }
    public function LoginDokter(request $req)
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
        $user = Dokter::where('username', $credentials['username'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login successful
            $customClaims = ['id' => $user->id,'role'=>$user->role];
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
        $dokter = Dokter::findOrFail($id);

        if (!$dokter) {
            return response()->json([
                'error' => 'dokter not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'data dokter',
            'data' => $dokter
        ], 200);
    } 
}
