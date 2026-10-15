<div class="card">
    <h2 class="sec">Images</h2>
    <p class="hint">JPG, PNG or WebP, up to 2 MB each, at most {{ $maxImages }} images. The primary image is shown first on the website.</p>

    <label for="product-uploads">Add images</label>
    <input class="f" id="product-uploads" type="file" accept="image/jpeg,image/png,image/webp" multiple wire:model="uploads" @error('uploads') aria-invalid="true" aria-describedby="product-uploads-error" @enderror>
    <p class="hint" wire:loading wire:target="uploads">Uploading...</p>

    @error('uploads')
        <p class="err-msg" id="product-uploads-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
    @enderror

    @if ($images->isEmpty())
        <div class="empty">
            <div class="empty-ic" aria-hidden="true"><i class="bi bi-image"></i></div>
            <h3>No images yet</h3>
            <p>Choose one or more files above. The first image becomes the primary image.</p>
        </div>
    @else
        <ul class="imgs">
            @foreach ($images as $image)
                <li wire:key="image-{{ $image->id }}" @class(['img', 'is-primary' => $image->is_primary])>
                    <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" loading="lazy">
                    <div class="img-bar">
                        @if ($image->is_primary)
                            <span class="pill ok">Primary</span>
                        @else
                            <button class="btn" type="button" wire:click="setPrimary({{ $image->id }})">Make primary</button>
                        @endif
                        <span class="img-acts">
                            <button class="ib" type="button" wire:click="moveUp({{ $image->id }})" @disabled($loop->first) aria-label="Move image {{ $loop->iteration }} earlier"><i class="bi bi-arrow-left" aria-hidden="true"></i></button>
                            <button class="ib" type="button" wire:click="moveDown({{ $image->id }})" @disabled($loop->last) aria-label="Move image {{ $loop->iteration }} later"><i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                            <button class="ib bad" type="button" wire:click="delete({{ $image->id }})" wire:confirm="Delete this image? This cannot be undone." aria-label="Delete image {{ $loop->iteration }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
