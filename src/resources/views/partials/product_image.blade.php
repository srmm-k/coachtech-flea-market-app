<div class="image-wrapper">
    <img src="{{ asset('storage/' . $listing->image_path) }}" alt="商品画像" width="200">
    @if ($listing->buyer_id)
        <span class="sold-ribbon"></span>
    @endif
</div>
