<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AdminProjectController extends Controller
{
    public function index()
{
    $token = Session::get('admin_token');

    $response = Http::withHeaders([
        'Authorization' => $token
    ])->get(env('API_BASE_URL') . '/api/project');

    $projects = $response->json()['data'];

    // ✅ decode images setiap project
    foreach ($projects as &$project) {
        if (isset($project['images']) && is_string($project['images'])) {
            $project['images'] = json_decode($project['images'], true);
        }
    }

    return view('admin.project.index')->with('projects', $projects);
}


    public function edit($id)
{
    $token = Session::get('admin_token');

    $response = Http::withHeaders([
        'Authorization' => $token
    ])->get(env('API_BASE_URL') . "/api/project/{$id}");

    $project = $response->json()['data'];

    // ✅ decode images agar bisa digunakan di Blade
    if (isset($project['images']) && is_string($project['images'])) {
        $project['images'] = json_decode($project['images'], true);
    }

    return view('admin.project.edit', ['project' => $project]);
}


public function store(Request $request)
{
    \Log::info('User dari request:', ['user' => $request->user()]);
    $token = Session::get('admin_token');
    $formData = $request->except('images');
    
    $http = Http::withHeaders([
        'Authorization' => $token,
    ]);

    // Cek apakah ada file gambar dikirim
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            $http->attach(
                "images[$index]",
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            );
        }
    }

    // Kirim data ke API
    $response = $http->post(env('API_BASE_URL') . '/api/project', $formData);

    

    return redirect()->route('admin.project.index');
}




public function update(Request $request, $id)
{
    $token = session('admin_token');

    $multipart = [
        ['name' => 'name', 'contents' => $request->input('name')],
        ['name' => 'description', 'contents' => $request->input('description')],
        ['name' => 'link', 'contents' => $request->input('link')],
        ['name' => 'tech_stack', 'contents' => $request->input('tech_stack')],
    ];

    if ($request->filled('deleted_images')) {
        foreach ($request->input('deleted_images') as $image) {
            $multipart[] = [
                'name' => 'deleted_images[]',
                'contents' => $image
            ];
        }
    }

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $multipart[] = [
                'name' => 'images[]',
                'contents' => fopen($image->getPathname(), 'r'),
                'filename' => $image->getClientOriginalName(),
            ];
        }
    }

    $response = Http::withHeaders([
        'Authorization' => $token,
    ])->asMultipart()->post(env('API_BASE_URL') . "/api/project/{$id}", $multipart);

    if ($response->successful()) {
        return redirect()->route('admin.project.index')->with('success', 'Proyek berhasil diperbarui');
    } else {
        return back()->withErrors($response->json())->withInput();
    }
}

 public function destroy($id){
    $token = Session::get('admin_token');

    $response = Http::withHeaders([
        'Authorization' => $token
    ])->delete(env('API_BASE_URL') . "/api/project/{$id}");

    

    if ($response->successful()) {
        return redirect()->route('admin.project.index')->with('success', 'Project deleted.');
    } else {
        return redirect()->route('admin.project.index')->with('error', 'Gagal menghapus project.');
    }

 }

}
