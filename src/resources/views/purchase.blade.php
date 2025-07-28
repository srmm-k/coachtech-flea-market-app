@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-left">
        <div class="product-image-name">
            <img src="{{ asset('storage/' . $listing->image_path) }}" alt="{{ $listing->product_name }}">
            <div class="product-info">
                <h2>{{ $listing->product_name }}</h2>
                <p>¥{{ number_format($listing->price) }}</p>
            </div>
        </div>

        <div class="payment-section">
            <label for="payment_method">支払い方法</label>
            <select name="payment_method" id="payment_method" form="purchase-form">
                <option value="" selected disabled>選択してください</option>
                <option value="convenience" {{ old('payment_method') == 'convenience' ? 'selected' : ''}}>コンビニ払い</option>
                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>カード払い</option>
            </select>
            @error('payment_method')
            <p class="form-text">{{ $message }}</p>
            @enderror
        </div>

        <div class="address-section">
            <div class="address-header">
                <label>配送先</label>
                <a href="{{ route('address.edit', ['item_id' => $listing->id]) }}" class="edit-link">変更する</a>
            </div>
            <div class="address-grid">

                <div class="label-cell">〒{{ $profile->postcode }}</div>
                <div class="error-cell">
                    @error('postcode')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="label-cell">{{ $profile->address }}</div>
                <div class="error-cell">
                    @error('address')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="label-cell">{{ $profile->building_name }}</div>
                <div class="error-cell">
                    @error('building_name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>


    </div>

    <div class="purchase-right">
        <p class="product-price">商品代金<span class="price">¥{{ number_format($listing->price) }}</span></p>
        <p class="payment-method">支払い方法<span class="space-left" id="selected-payment"></span></p>

        <form id="purchase-form" action="{{ route('purchase.store', ['id' => $listing->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="listing_id" value="{{ $listing->id }}">
            <input type="hidden" name="price" value="{{ $listing->price }}">
            <input type="hidden" name="profile_id" value="{{ $profile->id }}">

            <!-- 配送先 hidden -->
            <input type="hidden" name="postcode" value="{{ $profile->postcode }}">
            <input type="hidden" name="address" value="{{ $profile->address }}">
            <input type="hidden" name="building_name" value="{{ $profile->building_name }}">
            <button type="submit" class="purchase-button">購入する</button>

        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>

<script>
    const stripe = Stripe(@json(config('services.stripe.key')));

    function updatePaymentMethod() {
        const paymentSelect = document.getElementById('payment_method');
        const display = document.getElementById('selected-payment');
        if (!paymentSelect || !display) return;

        const selectedValue = paymentSelect.value;

        if (!selectedValue){
            display.innerText = '選択してください';
        } else if (selectedValue === 'card') {
            display.innerText = 'カード払い';
        }else if (selectedValue === 'convenience'){
            display.innerText = 'コンビニ払い'
        }
    }

    window.addEventListener('load', function () {
        updatePaymentMethod();
        document.getElementById('payment_method').addEventListener('change', updatePaymentMethod);
    });

    document.getElementById('purchase-form').addEventListener('submit', async function (e) {
        e.preventDefault();//デフォルトフォーム送信を停止

    const paymentSelect = document.getElementById('payment_method');
    const selectedPaymentMethod = paymentSelect.value;
    const price = parseInt(document.querySelector('input[name="price"]').value);
    const form = e.target;
    const purchaseUrl = form.action;

    //フォームデータを取得
    const formData = new FormData(form);
    formData.append('payment_method', selectedPaymentMethod); //選択された支払い方法を追加

    //支払い方法に応じて処理を分岐
    if (selectedPaymentMethod === 'card') {
        //カード払いの場合、Stripe Checkoutへリダイレクト
        try {
            const response = await fetch(purchaseUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });

        if (!response.ok) {
            const errorData = await response.json();
                alert('カード決済処理中にエラーが発生しました: ' + (errorData.error || '不明なエラー'));
                return;
                }

        const data = await response.json();
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Stripe CheckoutのURLが取得できませんでした。');
                }
                } catch (error) {
                console.error('Fetch error:', error);
                alert('カード決済処理中に予期せぬエラーが発生しました。');
            }

        } else if (selectedPaymentMethod === 'convenience') {
            // コンビニ払いの場合
        try {
        const response = await fetch(purchaseUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
                },
                body: formData
            });

        const data = await response.json();

        if (!response.ok) {
                // バックエンドからエラーレスポンスが返された場合
                alert('コンビニ決済処理中にエラーが発生しました: ' + (data.error || '不明なエラー'));
                return;
            }

        // client_secret を使ってStripe.jsでコンビニ支払いを確定
        const { error, paymentIntent } = await stripe.confirmKonbiniPayment(data.client_secret, {
            payment_method: {
                billing_details: {
                    name: '{{ Auth::user()->name }}', // ユーザー名
                    email: '{{ Auth::user()->email }}', // ユーザーメールアドレス
                },
            },

        payment_method_options: {
            konbini: {
                // 支払い時にユーザーから電話番号などを取得する場合はここに追加
                // confirmation_number: 'ユーザーの電話番号など'
            }
        },

            return_url: '{{ route('purchase.success') }}', // 支払い完了後のリダイレクト先
        });

        if (error) {
            // Stripe.jsからのエラー
                alert('コンビニ決済の確定中にエラーが発生しました: ' + error.message);
                window.location.href = '{{route('mypage') }}';
            } else if (paymentIntent && paymentIntent.status === 'requires_action') {
                alert('コンビニ支払いのお支払い情報が表示されました。お支払い情報を控えて、マイページに戻ります。');
                window.location.href = '{{ route('mypage') }}';
            } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                // 予期せぬPaymentIntentステータス
                window.location.href = '{{ route('purchase.success') }}';
            } else {
                alert('予期せぬ決済ステータスが発生しました。');
                window.location.href = '{{ route('mypage') }}';
            }

            } catch (error) {
                console.error('Fetch error for convenience payment:', error);
                alert('コンビニ決済処理中に予期せぬエラーが発生しました。');
                window.location.href = '{{ route('mypage') }}';
            }
        } else {
            form.submit();
        }
    });


</script>
@endsection