<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $response = Http::post(env('API_BASE_URL') . '/api/users/login', [
        'username' => $request->username,
        'password' => $request->password,
    ]);

    if ($response->successful()) {
        $json = $response->json();

        if (isset($json['data']['token'])) {
            $token = $json['data']['token'];
            Session::put('admin_token', $token);

            $profile = Http::withHeaders([
                'Authorization' => $token
            ])->get(env('API_BASE_URL') . '/api/profile'); 
            if ($profile->successful() && isset($profile['data']['name'])) {
                Session::put('admin_name', $profile['data']['name']);
                Session::put('admin_id', $profile['data']['id']);

                \Log::info('Profile data disimpan ke session', [
                'id' => $profile['data']['id'],
                'name' => $profile['data']['name']
            ]);
            }
            
            return redirect()->route('admin.dashboard');
        }
    }

    // Tambahkan debug log jika mau
    logger()->warning('Login gagal', ['response' => $response->json()]);

    return redirect()->back()->with('error', 'Login gagal. Periksa username dan password.');
}

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        
        $response = Http::post(env('API_BASE_URL') . '/api/users/register', [
        'username' => $request->username,
        'password' => $request->password,
        'name'=>$request->name
    ]);
    
    if($response->successful()){
        return view('auth.login');
        
    }
    return view('auth.register');
    }


    public function logout(Request $request)
{
    $token = Session::get('admin_token');
    
    if ($token) {
        $response = Http::withHeaders([
    'Authorization' => $token
    ])->delete(env('API_BASE_URL') . '/api/users/logout');

        

        if ($response->failed()) {
            // Log jika gagal
            logger()->error('Logout API failed', ['response' => $response->json()]);
        }
    } else {
        logger()->warning('No token found in session during logout');
    }

    // Setelah dipastikan, baru hapus session
    Session::forget('admin_token');
    Session::forget('admin_name');
    Session::flush();

    return redirect('/admin/login')->with('success', 'Berhasil logout');
}

}
