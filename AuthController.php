<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }   

    public function login(Request $request){
        $validatedata = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

         // Cek kredensial pengguna
         $user = User::where('Email', $request->email)->first();

         if ($user && Hash::check($request->password, $user->Password)) {
             Auth::login($user);
             return redirect()->intended('/admin'); // Redirect ke halaman admin jika berhasil login
         }
 
         return back()->withErrors([
             'email' => 'Email atau password salah.',
         ]);
     }
 
     // Proses logout
     public function logout(Request $request)
     {
         Auth::logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();
 
         return redirect('/login');
    }

    public function showRegisterForm(){
        return view('auth.register');
    }

    public function register(Request $request){
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:Administrator,Petugas,Peminjam'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
        ]);

        $user = User::create([
            'Username' => $request->username,
            'Password' => Hash::make($request->password),
            'Email' => $request->email,
            'role' => $request->role, // Menyimpan role yang dipilih
            'NamaLengkap' => $request->nama_lengkap,
            'Alamat' => $request->alamat,
        ]);

        Auth::login($user); 

        return redirect()->intented('/admin');
    }
}
