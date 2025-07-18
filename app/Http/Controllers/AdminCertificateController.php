<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use function Illuminate\Log\log;

class AdminCertificateController extends Controller
{
    //mendapatkan semua data kemudian redirect ke index
    public function get()  {
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get(env('API_BASE_URL') . '/api/certificate');

        $certificates = $response->json()['data'];
        
        return view('admin.certificate.index')->with('certificates',$certificates);
    }

    public function store(Request $request)
    {
        $token = Session::get('admin_token');

        $requestHttp = Http::withHeaders([
            'Authorization' => $token,
        ]);

        // Jika ada file gambar, attach
        if ($request->hasFile('image')) {
            $requestHttp->attach(
                'image',
                fopen($request->file('image')->getPathname(), 'r'),
                $request->file('image')->getClientOriginalName()
            );
        }

        // Kirim data lain (tanpa file kalau tidak ada)
        $response = $requestHttp->post(env('API_BASE_URL') . '/api/certificate', [
            'title' => $request->input('title'),
            'issuer' => $request->input('issuer'),
            'year' => $request->input('year'),
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.certificate.index')->with('success', 'Sertifikat berhasil ditambahkan!');
        } else {
            return redirect()->route('admin.certificate.index')->with('error', 'Gagal menambahkan sertifikat.');
        }
    }
   
public function update(Request $request, $id)
{
    $token = Session::get('admin_token');
    $image = $request->file('image');

    $multipart = [
        [
            'name'     => 'title',
            'contents' => $request->input('title'),
        ],
        [
            'name'     => 'issuer',
            'contents' => $request->input('issuer'),
        ],
        [
            'name'     => 'year',
            'contents' => $request->input('year'),
        ],
    ];

    if ($image) {
        $multipart[] = [
            'name'     => 'image',
            'contents' => fopen($image->getPathname(), 'r'),
            'filename' => $image->getClientOriginalName(),
        ];
    }

    $response = Http::withHeaders([
        'Authorization' => $token,
    ])->send('POST', env('API_BASE_URL') . "/api/certificate/{$id}", [
        'multipart' => $multipart
    ]);

    if ($response->successful()) {
        return redirect()->route('admin.certificate.index')->with('success', 'Sertifikat berhasil diperbarui!');
    } else {
        Log::error('Response:', [
    'status' => $response->status(),
    'body' => $response->body()
]);
dd($response,$multipart,$request);
        return redirect()->route('admin.certificate.edit', $id)->with('error', 'Gagal memperbarui sertifikat.');
    }
}


    public function edit($id)
    {
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get(env('API_BASE_URL') . "/api/certificate/{$id}");

        $certificate = $response->json()['data'];

        return view('admin.certificate.edit',['certificate'=>$certificate]);

    }

    public function destroy($id)
    {
        $token = Session::get('admin_token');
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->delete(env('API_BASE_URL') . "/api/certificate/{$id}");

        if ($response->successful()) {
            return redirect()->route('admin.certificate.index')->with('success', 'certificate deleted.');
        } else {
            return redirect()->route('admin.certificate.index')->with('error', 'Gagal menghapus project.');
        }

    }
}
