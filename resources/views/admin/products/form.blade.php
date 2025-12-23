@extends('layouts.storefront')

@section('content')
<style>
    .product-form-container {
        background: #f5f7f5;
        min-height: calc(100vh - 80px);
        padding: 40px 24px;
    }
    
    .product-form-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }
    
    .product-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }
    
    .product-form-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    
    .product-form-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-top: 4px;
    }
    
    .product-form-actions {
        display: flex;
        gap: 12px;
    }
    
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
    }
    
    .btn-cancel:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }
    
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #2D5A3D;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: white;
        cursor: pointer;
        transition: all 0.15s;
        box-shadow: 0 2px 8px rgba(45, 90, 61, 0.3);
    }
    
    .btn-submit:hover {
        background: #234a31;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 90, 61, 0.4);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 32px;
        align-items: start;
    }
    
    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .form-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 24px 28px;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .card-icon.green {
        background: #ecfdf5;
    }
    
    .card-icon.blue {
        background: #eff6ff;
    }
    
    .card-icon svg {
        width: 24px;
        height: 24px;
    }
    
    .card-icon.green svg {
        color: #059669;
    }
    
    .card-icon.blue svg {
        color: #2563eb;
    }
    
    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }
    
    .card-subtitle {
        font-size: 13px;
        color: #6b7280;
        margin-top: 2px;
    }
    
    .card-body {
        padding: 28px;
    }
    
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        background: #fafafa;
        min-height: 280px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }
    
    .upload-area:hover {
        border-color: #2D5A3D;
        background: #f0fdf4;
    }
    
    .upload-area.has-image {
        border-style: solid;
        border-color: #2D5A3D;
    }
    
    .upload-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    
    .upload-icon svg {
        width: 28px;
        height: 28px;
        color: #9ca3af;
    }
    
    .upload-text {
        font-size: 15px;
        font-weight: 500;
        color: #374151;
    }
    
    .upload-hint {
        font-size: 13px;
        color: #9ca3af;
        margin-top: 6px;
    }
    
    .upload-preview {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .upload-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
    }
    
    .upload-area:hover .upload-overlay {
        display: flex;
    }
    
    .upload-overlay-text {
        background: white;
        color: #111827;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
    }
    
    .form-label .required {
        color: #dc2626;
        margin-left: 2px;
    }
    
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        background: #fafafa;
        transition: all 0.15s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #2D5A3D;
        background: white;
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.1);
    }
    
    .form-input::placeholder {
        color: #9ca3af;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
    
    .input-group {
        display: flex;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        overflow: hidden;
        background: #fafafa;
        transition: all 0.15s;
    }
    
    .input-group:focus-within {
        border-color: #2D5A3D;
        background: white;
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.1);
    }
    
    .input-prefix {
        padding: 14px 16px;
        background: #f3f4f6;
        border-right: 1px solid #d1d5db;
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
    }
    
    .input-group input {
        flex: 1;
        border: none;
        padding: 14px 16px;
        font-size: 14px;
        background: transparent;
        outline: none;
    }
    
    .form-select {
        width: 100%;
        padding: 14px 40px 14px 16px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        background: #fafafa url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") right 12px center no-repeat;
        background-size: 20px;
        appearance: none;
        cursor: pointer;
        transition: all 0.15s;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #2D5A3D;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.1);
    }
    
    .form-textarea {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        background: #fafafa;
        resize: none;
        transition: all 0.15s;
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: #2D5A3D;
        background: white;
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.1);
    }
    
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;
        border-top: 1px solid #f3f4f6;
        margin-top: 8px;
    }
    
    .toggle-label {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }
    
    .toggle-hint {
        font-size: 13px;
        color: #6b7280;
        margin-top: 2px;
    }
    
    .toggle-switch {
        position: relative;
        width: 52px;
        height: 28px;
        background: #d1d5db;
        border-radius: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 22px;
        height: 22px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        transition: transform 0.2s;
    }
    
    .toggle-input:checked + .toggle-switch {
        background: #2D5A3D;
    }
    
    .toggle-input:checked + .toggle-switch::after {
        transform: translateX(24px);
    }
    
    .form-error {
        color: #dc2626;
        font-size: 13px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="product-form-container">
    <div class="product-form-wrapper">
        <!-- Header -->
        <div class="product-form-header">
            <div>
                <h1 class="product-form-title">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h1>
                <p class="product-form-subtitle">{{ isset($product) ? 'Perbarui informasi produk Anda' : 'Lengkapi data produk tempe yang akan dijual' }}</p>
            </div>
            <div class="product-form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" form="product-form" class="btn-submit">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($product) ? 'Simpan' : 'Tambah Produk' }}
                </button>
            </div>
        </div>

        <!-- Form -->
        <form id="product-form" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="form-grid">
                <!-- Left Card: Image -->
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-icon green">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Foto Produk</h3>
                            <p class="card-subtitle">Wajib diisi</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="upload-area" onclick="document.getElementById('image').click()" id="upload-zone">
                            <img id="img-preview" 
                                 src="{{ isset($product) && $product->img_path ? asset($product->img_path) : '' }}" 
                                 class="upload-preview {{ isset($product) && $product->img_path ? '' : 'hidden' }}"
                                 alt="Preview">
                            
                            <div id="img-placeholder" class="{{ isset($product) && $product->img_path ? 'hidden' : '' }}" style="text-align: center;">
                                <div class="upload-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <p class="upload-text">Klik untuk upload</p>
                                <p class="upload-hint">PNG, JPG hingga 2MB</p>
                            </div>
                            
                            <div class="upload-overlay" id="change-overlay">
                                <span class="upload-overlay-text">Ganti Foto</span>
                            </div>
                        </div>
                        
                        <input type="file" name="image" id="image" style="display: none;" accept="image/*" onchange="previewImage(this)">
                        @error('image')
                            <div class="form-error">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Right Card: Details -->
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-icon blue">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Detail Produk</h3>
                            <p class="card-subtitle">Informasi yang ditampilkan ke pembeli</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Nama Produk -->
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="required">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $product->title ?? '') }}" 
                                   class="form-input" placeholder="Contoh: Tempe Daun Pisang Premium" required>
                            @error('title') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <!-- Harga & Kategori -->
                        <div class="form-group">
                            <div class="form-row">
                                <div>
                                    <label class="form-label">Harga <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-prefix">Rp</span>
                                        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" 
                                               placeholder="0" min="0" required>
                                    </div>
                                    @error('price') <div class="form-error">{{ $message }}</div> @enderror
                                </div>
                                <div>
                                    <label class="form-label">Kategori <span class="required">*</span></label>
                                    <select name="tag" class="form-select" required>
                                        <option value="" disabled {{ old('tag', $product->tag ?? '') ? '' : 'selected' }}>Pilih kategori</option>
                                        <option value="Tempe Plastik" {{ old('tag', $product->tag ?? '') == 'Tempe Plastik' ? 'selected' : '' }}>Tempe Plastik</option>
                                        <option value="Tempe Daun Pisang" {{ old('tag', $product->tag ?? '') == 'Tempe Daun Pisang' ? 'selected' : '' }}>Tempe Daun Pisang</option>
                                    </select>
                                    @error('tag') <div class="form-error">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label class="form-label">Deskripsi <span class="required">*</span></label>
                            <textarea name="desc" rows="5" class="form-textarea" 
                                      placeholder="Jelaskan detail produk, bahan, ukuran, dan keunggulannya..." required>{{ old('desc', $product->desc ?? '') }}</textarea>
                            @error('desc') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <!-- Status -->
                        <div class="toggle-row">
                            <div>
                                <p class="toggle-label">Tampilkan di Toko</p>
                                <p class="toggle-hint">Produk akan terlihat oleh pembeli jika aktif</p>
                            </div>
                            <label style="cursor: pointer;">
                                <input type="checkbox" name="is_active" value="1" class="toggle-input" style="display: none;" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                                <div class="toggle-switch"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('img-preview');
        const placeholder = document.getElementById('img-placeholder');
        const uploadZone = document.getElementById('upload-zone');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                uploadZone.classList.add('has-image');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form Validation
    document.getElementById('product-form').addEventListener('submit', function(e) {
        let errors = [];
        
        const preview = document.getElementById('img-preview');
        if (preview.classList.contains('hidden')) {
            errors.push("Foto produk wajib diupload");
        }

        const title = this.querySelector('input[name="title"]').value.trim();
        const price = this.querySelector('input[name="price"]').value.trim();
        const tag = this.querySelector('select[name="tag"]').value;
        const desc = this.querySelector('textarea[name="desc"]').value.trim();

        if (!title) errors.push("Nama produk wajib diisi");
        if (!price) errors.push("Harga wajib diisi");
        if (!tag) errors.push("Kategori wajib dipilih");
        if (!desc) errors.push("Deskripsi wajib diisi");

        if (errors.length > 0) {
            e.preventDefault();
            showToast(errors[0]);
        }
    });

    function showToast(message) {
        let existing = document.getElementById('error-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'error-toast';
        toast.style.cssText = 'position: fixed; top: 24px; right: 24px; z-index: 9999; transform: translateY(-20px); opacity: 0; transition: all 0.3s ease;';
        toast.innerHTML = `
            <div style="background: white; border-left: 4px solid #dc2626; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border-radius: 10px; padding: 16px 20px; display: flex; align-items: flex-start; gap: 12px; min-width: 320px;">
                <div style="color: #dc2626; flex-shrink: 0;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p style="font-weight: 600; color: #111827; font-size: 14px; margin: 0;">Mohon Lengkapi Data</p>
                    <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">${message}</p>
                </div>
            </div>
        `;

        document.body.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
        });

        setTimeout(() => {
            toast.style.transform = 'translateY(-20px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }
</script>
@endsection
