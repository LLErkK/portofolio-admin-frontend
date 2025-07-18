<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AdminEducationController extends Controller
{
    //get all
    public function get(){
        $token = Session::get('admin_token');

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get(env('API_BASE_URL') . '/api/education');

        $educations = $response->json()['data'];
        //kurang view index
        return view('admin.education.index')->with('educations', $educations);

    }

    public function store(Request $request)
    {
        //test
        
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post(env('API_BASE_URL') . '/api/education',$request->all());

        if (!$response->successful()) {
        // Ambil error dari API (jika format JSON standar Laravel)
        $errors = $response->json('errors') ?? [];

        $message = 'Terjadi kesalahan. Pastikan kolom nama sekolah, tahun masuk dan tahun lulus tidak kosong!';

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

        return redirect()->route('admin.education.index')->with('success', 'Riwayat pendidikan berhasil ditambahkan');
    }

    public function edit($id)  
    {
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get(env('API_BASE_URL') . '/api/education');

        $education = $response->json()['data'];
        return view('admin.education.edit',['education' =>$education[0]]);

    }

    public function update(Request $request,$id)  {
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->patch(env('API_BASE_URL') . '/api/education/' . $id,$request->all());
        

        if($response->successful()){
            return redirect()->route('admin.education.index')->with('success', 'education updated');
        }else{
            return redirect()->route('admin.education.edit',['id'=>$id])->with('failed', 'eror could not update your education');
        }
    }

    public function destroy($id)  {
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->delete(env('API_BASE_URL') . '/api/education/' . $id);

        if ($response->successful()) {
            return redirect()->route('admin.education.index')->with('success', 'Education deleted.');
        } else {
            dd($response);
            return redirect()->route('admin.education.index')->with('error', 'Gagal menghapus Riwayat pendidikan.');
        }
    }
}
