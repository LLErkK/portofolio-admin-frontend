<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AdminExperienceController extends Controller
{
    public function get()
    {
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' =>$token
        ])->get(env('API_BASE_URL') . '/api/experience');

        $experiences = $response->json()['data'];
        
        return view('admin.experience.index')->with('experiences', $experiences);
    }

    public function store(Request $request)
{
    $token = Session::get('admin_token');

    $response = Http::withHeaders([
        'Authorization' => $token
    ])->post(env('API_BASE_URL') . '/api/experience', $request->all());

    if (!$response->successful()) {
        // Ambil error dari API (jika format JSON standar Laravel)
        $errors = $response->json('errors') ?? [];

        $message = 'Terjadi kesalahan. Pastikan kolom nama perusahaan, posisi, dan tahun mulai diisi.';

        // Tambah detail error jika tersedia
        if (!empty($errors)) {
            $message .= ' ';
            foreach ($errors as $field => $msgs) {
                $message .= ucfirst($field) . ': ' . implode(', ', $msgs) . '. ';
            }
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $message);
    }

    return redirect()->route('admin.experience.index')->with('success', 'Pengalaman kerja berhasil ditambahkan.');
}

    public function edit($id){
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization'=>$token
        ])->get(env('API_BASE_URL') . '/api/experience/' . $id);

        $experience = $response->json()['data'];
        return view('admin.experience.edit',['experience'=>$experience]);
    }
    public function update(Request $request,$id){
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->patch(env('API_BASE_URL') . '/api/experience/' . $id, $request);

        if($response->successful()){
            return redirect()->route('admin.experience.index')->with('success', 'project updated');
        }else{
            return redirect()->route('admin.experience.edit',['id'=>$id])->with('failed', 'eror could not update your experience');
        }
    }

    public function destroy($id)
    {
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization'=> $token 
        ])->delete(env('API_BASE_URL') . '/api/experience/' . $id);

        if ($response->successful()) {
            return redirect()->route('admin.experience.index')->with('success', 'Project deleted.');
        } else {
            return redirect()->route('admin.experience.index')->with('error', 'Gagal menghapus project.');
        }
    }

}
