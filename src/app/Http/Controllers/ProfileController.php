<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        
        $rules = array_merge(
            (new ProfileRequest)->rules(), // ①画像用のProfileRequestのバリデーション
            (new AddressRequest)->rules() // ②住所用のAddressRequestのバリデーション
        );

        $messages = array_merge(
            (new ProfileRequest)->messages(),
            (new AddressRequest)->messages()
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        // ユーザー名を更新
        $user->name = $request->input('name');
        $user->save();

        // プロフィールの取得 or 作成
        $profile = Profile::firstOrCreate(['user_id' => $user->id]);

        // プロフィール画像処理
        if ($request->hasFile('profile_image')) {
            if ($profile->profile_image) {
                Storage::disk('public')->delete($profile->profile_image);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $profile->profile_image = $path;
        }

        // その他プロフィール情報の更新
        $profile->fill([
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building_name' => $request->building_name,
        ]);

        $profile->save();

        return redirect('/')
        ->with('success', 'プロフィールが更新されました')
        ->withInput();
    }

    public function editAddress($item_id)
    {
        $profile = auth()->user()->profile;
        return view('address.edit', compact('profile', 'item_id'));
    }

    public function updateAddress(Request $request, $item_id)
    {

        $profile = auth()->user()->profile;

        $profile->update($request->only('postcode', 'address', 'building_name'));

        return redirect()->route('purchase.start', ['id' => $item_id])->with('success', '配送先を変更しました');
    }
}
