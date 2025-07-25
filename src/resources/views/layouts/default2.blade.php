<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>flea-market</title>
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
<link rel="stylesheet" href="{{ asset('css/default2.css') }}" />
@yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                <img class="top-header__logo" src="{{ asset('img/logo.svg') }}" alt="">
            </a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>