<?php

return [

    /*
    |--------------------------------------------------------------------------
    | バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | これらの言語行はバリデータクラスで使用されるデフォルトのエラーメッセージです。
    | いくつかのルールは複数のバージョンがあります。必要に応じて調整してください。
    |
    */

    'accepted'             => ':attributeを承認してください。',
    'active_url'           => ':attributeは有効なURLではありません。',
    'after'                => ':attributeには、:date以降の日付を指定してください。',
    'after_or_equal'       => ':attributeには、:date以降もしくは同日時の日付を指定してください。',
    'alpha'                => ':attributeにはアルファベットのみ使用できます。',
    'alpha_dash'           => ':attributeには英数字・ダッシュ(-)・アンダースコア(_)のみ使用できます。',
    'alpha_num'            => ':attributeには英数字のみ使用できます。',
    'array'                => ':attributeには配列を指定してください。',
    'before'               => ':attributeには、:date以前の日付を指定してください。',
    'before_or_equal'      => ':attributeには、:date以前もしくは同日時の日付を指定してください。',
    'between'              => [
        'numeric' => ':attributeは:min〜:maxの間で指定してください。',
        'file'    => ':attributeのファイルは:min〜:maxキロバイトでなければなりません。',
        'string'  => ':attributeは:min〜:max文字で指定してください。',
        'array'   => ':attributeの項目は:min〜:max個指定してください。',
    ],
    'boolean'              => ':attributeには、trueかfalseを指定してください。',
    'confirmed'            => ':attributeと:attribute確認が一致しません。',
    'date'                 => ':attributeは有効な日付ではありません。',
    'date_equals'          => ':attributeは:dateと同じ日付でなければなりません。',
    'date_format'          => ':attributeの形式は:formatと一致しません。',
    'different'            => ':attributeと:otherには異なるものを指定してください。',
    'digits'               => ':attributeは:digits桁で指定してください。',
    'digits_between'       => ':attributeは:min桁から:max桁で指定してください。',
    'dimensions'           => ':attributeの画像サイズが無効です。',
    'distinct'             => ':attributeの値が重複しています。',
    'email'                => ':attributeには有効なメールアドレスを指定してください。',
    'exists'               => '選択された:attributeは正しくありません。',
    'file'                 => ':attributeにはファイルを指定してください。',
    'filled'               => ':attributeは必須です。',
    'gt'                   => [
        'numeric' => ':attributeは:valueより大きくなければなりません。',
        'file'    => ':attributeのファイルは:valueキロバイトより大きくなければなりません。',
        'string'  => ':attributeは:value文字より長くなければなりません。',
        'array'   => ':attributeの項目は:value個より多くなければなりません。',
    ],
    'gte'                  => [
        'numeric' => ':attributeは:value以上でなければなりません。',
        'file'    => ':attributeのファイルは:valueキロバイト以上でなければなりません。',
        'string'  => ':attributeは:value文字以上でなければなりません。',
        'array'   => ':attributeの項目は:value個以上でなければなりません。',
    ],
    'image'                => ':attributeには画像ファイルを指定してください。',
    'in'                   => '選択された:attributeは正しくありません。',
    'in_array'             => ':attributeは:otherに存在しません。',
    'integer'              => ':attributeには整数を指定してください。',
    'ip'                   => ':attributeには有効なIPアドレスを指定してください。',
    'ipv4'                 => ':attributeには有効なIPv4アドレスを指定してください。',
    'ipv6'                 => ':attributeには有効なIPv6アドレスを指定してください。',
    'json'                 => ':attributeには有効なJSON文字列を指定してください。',
    'lt'                   => [
        'numeric' => ':attributeは:valueより小さくなければなりません。',
        'file'    => ':attributeのファイルは:valueキロバイトより小さくなければなりません。',
        'string'  => ':attributeは:value文字より短くなければなりません。',
        'array'   => ':attributeの項目は:value個より少なくなければなりません。',
    ],
    'lte'                  => [
        'numeric' => ':attributeは:value以下でなければなりません。',
        'file'    => ':attributeのファイルは:valueキロバイト以下でなければなりません。',
        'string'  => ':attributeは:value文字以下でなければなりません。',
        'array'   => ':attributeの項目は:value個以下でなければなりません。',
    ],
    'max'                  => [
        'numeric' => ':attributeは:max以下でなければなりません。',
        'file'    => ':attributeのファイルは:maxキロバイト以下でなければなりません。',
        'string'  => ':attributeは:max文字以下でなければなりません。',
        'array'   => ':attributeの項目は:max個以下でなければなりません。',
    ],
    'mimes'                => ':attributeには:typeタイプのファイルを指定してください。',
    'mimetypes'            => ':attributeには:typeタイプのファイルを指定してください。',
    'min'                  => [
        'numeric' => ':attributeは:min以上でなければなりません。',
        'file'    => ':attributeのファイルは:minキロバイト以上でなければなりません。',
        'string'  => ':attributeは:min文字以上でなければなりません。',
        'array'   => ':attributeの項目は:min個以上でなければなりません。',
    ],
    'not_in'               => '選択された:attributeは正しくありません。',
    'not_regex'            => ':attributeの形式が正しくありません。',
    'numeric'              => ':attributeには数字を指定してください。',
    'password'             => 'パスワードが間違っています。',
    'present'              => ':attributeは存在している必要があります。',
    'prohibited'           => ':attributeは入力禁止です。',
    'prohibited_if'        => ':otherが:valueの場合、:attributeは入力禁止です。',
    'prohibited_unless'    => ':otherが:valueでない場合、:attributeは入力禁止です。',
    'prohibits'            => ':attributeは:otherの入力を禁止します。',
    'regex'                => ':attributeの形式が正しくありません。',
    'required'             => '【検証】:attributeは必ず入力してください。',
    'required_array_keys'  => ':attributeには以下の値が必要です: :values。',
    'required_if'          => ':otherが:valueの場合、:attributeは必須です。',
    'required_unless'      => ':otherが:valueでない場合、:attributeは必須です。',
    'required_with'        => ':valuesが存在する場合、:attributeは必須です。',
    'required_with_all'    => ':valuesがすべて存在する場合、:attributeは必須です。',
    'required_without'     => ':valuesが存在しない場合、:attributeは必須です。',
    'required_without_all' => ':valuesがすべて存在しない場合、:attributeは必須です。',
    'same'                 => ':attributeと:otherが一致しません。',
    'size'                 => [
        'numeric' => ':attributeは:sizeでなければなりません。',
        'file'    => ':attributeのファイルは:sizeキロバイトでなければなりません。',
        'string'  => ':attributeは:size文字でなければなりません。',
        'array'   => ':attributeの項目は:size個でなければなりません。',
    ],
    'starts_with'          => ':attributeは次のいずれかで始まらなければなりません: :values。',
    'string'               => ':attributeは文字列でなければなりません。',
    'timezone'             => ':attributeは有効なタイムゾーンでなければなりません。',
    'unique'               => ':attributeは既に存在しています。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'url'                  => ':attributeは有効なURL形式でなければなりません。',
    'uuid'                 => ':attributeは有効なUUIDでなければなりません。',

    /*
    |--------------------------------------------------------------------------
    | カスタムバリデーションメッセージ
    |--------------------------------------------------------------------------
    |
    | 属性名.ルール名 の形で指定すると特定の属性・ルールに対するカスタムメッセージを設定できます。
    |
    */

    'custom' => [
        // 例:
        // 'email' => [
        //     'required' => 'メールアドレスは必須です。',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 属性名の日本語表記変換
    |--------------------------------------------------------------------------
    |
    | メッセージ中の :attribute をわかりやすい日本語名に置き換えます。
    |
    */

    'attributes' => [
        'email'    => 'メールアドレス',
        'password' => 'パスワード',
    ],

];
