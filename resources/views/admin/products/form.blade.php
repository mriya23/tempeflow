@extends('layouts.storefront')

@section('content')
<style>
    /* Admin Base Styles (Copied from Dashboard for consistency) */
    .admin-dashboard {
        --color-primary: #2D5A3D;
        --color-primary-light: #E8F0EA;
        --color-surface: #FFFFFF;
        --color-background: #F8F9FA;
        --color-border: #E5E7EB;
        --color-text-primary: #1F2937;
        --color-text-secondary: #6B7280;
        --color-text-muted: #9CA3AF;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --radius-lg: 12px;
        --radius-xl: 16px;
    }

    .admin-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
    }

    .admin-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        transition: all 0.15s ease;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
    }

    .admin-btn-primary {
        background: var(--color-primary);
        color: white;
        border: none;
    }

    .admin-btn-primary:hover {
        background: #234A31;
    }

    .admin-btn-secondary {
        background: white;
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
    }

    .admin-btn-secondary:hover {
        background: var(--color-background);
        border-color: #D1D5DB;
    }

    .admin-input, .admin-textarea {
        width: 100%;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        background: var(--color-surface);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .admin-input:focus, .admin-textarea:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.1);
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-hint {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    .form-error {
        font-size: 12px;
        color: #DC2626;
        margin-top: 4px;
    }
</style>

<section class="admin-dashboard max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500" style="margin-bottom: 8px;">
                {{ isset($product) ? 'Perbarui informasi produk' : 'Lengkapi informasi produk di bawah ini' }}
            </p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-secondary h-9 px-4">
            Batal
        </a>
    </div>

    <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="admin-card p-6 space-y-6">
            <!-- Basic Info -->
            <div class="grid sm:grid-cols-2 gap-6">
                <div class="form-group sm:col-span-2">
                    <label>Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $product->title ?? '') }}" class="admin-input" placeholder="Contoh: Tempe Daun Pisang Premium" required>
                    @error('title') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" class="admin-input" placeholder="0" min="0" required>
                    @error('price') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Tag / Kategori</label>
                    <input type="text" name="tag" value="{{ old('tag', $product->tag ?? '') }}" class="admin-input" placeholder="Contoh: organic">
                    <div class="form-hint">Digunakan untuk filter (opsional)</div>
                    @error('tag') <div class="form-error">{{ $message }}</div> @enderror
                </div>


                 <div class="form-group">
                    <label>Status Produk</label>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="w-4 h-4 text-green-700 rounded border-gray-300 focus:ring-green-700" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active" class="!mb-0 !font-normal cursor-pointer select-none">Tampilkan produk ini di toko</label>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label>Deskripsi Produk <span class="text-red-500">*</span></label>
                <textarea name="desc" rows="4" class="admin-textarea" placeholder="Jelaskan detail produk..." required>{{ old('desc', $product->desc ?? '') }}</textarea>
                @error('desc') <div class="form-error">{{ $message }}</div> @enderror
            </div>



            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="submit" class="admin-btn admin-btn-primary h-10 px-6">
                    {{ isset($product) ? 'Simpan Perubahan' : 'Simpan Produk' }}
                </button>
            </div>
        </div>
    </form>
</section>

<script>
    function previewImage(input) {
        const preview = document.getElementById('img-preview');
        const placeholder = document.getElementById('img-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
