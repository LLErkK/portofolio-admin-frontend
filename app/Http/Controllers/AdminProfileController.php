<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AdminProfileController extends Controller
{
    //bisa saja pakai header Authorization
    public function edit()
    {
        $token = Session::get('admin_token');
        //mungkin pakai header
        $response = Http::withHeaders([
                'Authorization' => $token
            ])->get(env('API_BASE_URL') . '/api/profile');

        if($response->successful()){
            $profile = $response['data'];
            return view('admin.profile.edit', compact('profile'));
        }else{
            return view('admin.profile.edit');
        }
    }

    public function update(Request $request)
{
    
    $token = Session::get('admin_token');
    //token ada
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'bio' => 'nullable|string',
        'linkedin' => 'nullable|url',
        'github' => 'nullable|url',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
    ]);

    $data = [
        ['name' => 'name', 'contents' => $request->name],
        ['name' => 'bio', 'contents' => $request->bio],
        ['name' => 'linkedin', 'contents' => $request->linkedin],
        ['name' => 'github', 'contents' => $request->github],
    ];

    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $data[] = [
            'name' => 'photo',
            'contents' => fopen($photo->getPathname(), 'r'),
            'filename' => $photo->getClientOriginalName(),
        ];
    }

    $response = Http::withHeaders([
                'Authorization' => $token
            ])
        ->asMultipart()
        ->post(env('API_BASE_URL') . '/api/profile/update', $data);
        
    if ($response->successful()) {
        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui');
    }

    return back()->with('error', 'Gagal memperbarui profil');
}

}
