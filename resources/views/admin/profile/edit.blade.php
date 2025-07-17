@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Profil Admin</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $profile['name'] ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Bio</label>
            <textarea name="bio" class="form-control">{{ old('bio', $profile['bio'] ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Linkedin</label>
            <textarea name="linkedin" class="form-control">{{ old('linkedin', $profile['linkedin'] ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Github</label>
            <textarea name="github" class="form-control">{{ old('github', $profile['github'] ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Foto Profil</label><br>
            @if (!empty($profile['photo']))
                <img src="{{ env('API_BASE_URL') . '/storage/' . $profile['photo'] }}" width="100"><br><br>
            @endif

            <input type="file" name="photo" id="inputPhoto" accept="image/*" class="form-control">
            <div id="preview" class="mt-3"></div>
        </div>

        <button type="submit" class="btn btn-primary">Update Profil</button>
    </form>
</div>

<!-- Modal Crop -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <img id="imageToCrop" class="w-100">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="cropBtn" class="btn btn-primary">Crop & Simpan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let cropper;
const image = document.getElementById('imageToCrop');
const inputPhoto = document.getElementById('inputPhoto');

inputPhoto.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (event) {
            image.src = event.target.result;
            new bootstrap.Modal(document.getElementById('cropModal')).show();
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 1,
    });
});

document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

document.getElementById('cropBtn').addEventListener('click', function () {
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
    });

    canvas.toBlob(function (blob) {
        const file = new File([blob], 'cropped.jpg', { type: 'image/jpeg' });

        // Replace the input file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        inputPhoto.files = dataTransfer.files;

        // Show preview
        document.getElementById('preview').innerHTML = `
            <p>Pratinjau Hasil Crop:</p>
            <img src="${URL.createObjectURL(blob)}" class="img-thumbnail" width="150"/>
        `;

        // Tutup modal
        bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
    });
});
</script>
@endpush
