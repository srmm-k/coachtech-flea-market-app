<div class="image-wrapper">
    <img src="{{ asset('storage/' . $listing->image_path) }}" alt="商品画像">
    @if ($listing->buyer_id)
        <span class="sold-ribbon"></span>
    @endif
</div>
