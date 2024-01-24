<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    public function Index()
    {
        $admin = Admin::all();
        return response()->json($admin);
    }
    public function PostAdmin(request $req)
    {
          $validator  = Validator::make($req->all(), [
            'name'      => 'required',
            'username'  => 'required:unique:admin',
            'role'      => 'required',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $admin = new Admin();
        $admin->id = Str::uuid(); // Membuat UUID baru
        $admin->name = $req->input('name');
        $admin->username = $req->input('username');
        $admin->role = $req->input('role');
        $admin->password = bcrypt($req->input('password')); // Gunakan bcrypt untuk menyandikan password
        $admin->save();
        return response()->json([
            "message" => "admin added"
        ],201);
    }
    public function LoginAdmin(request $req)
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
        $user = Admin::where('username', $credentials['username'])->first();
        
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
        $admin = Admin::findOrFail($id);

        if (!$admin) {
            return response()->json([
                'error' => 'Admin not found',
            ], 404);
        }

        return response()->json($admin);
    } 
}
