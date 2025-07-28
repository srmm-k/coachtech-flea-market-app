<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>flea-market</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
<link rel="stylesheet" href="{{ asset('css/common.css') }}" />
@yield('css')
</head>

<body>
<header class="header">
        <div class="header__inner">
            <div class="header-left"> <a href="/">
                <img class="top-header__logo" src="{{ asset('img/logo.svg') }}" alt="">
                </a>
                <div class="widget-content">
                    <form action="{{ route('search') }}" class="search-box-form" method="GET">
                        <input class="search-box-text" name="keyword" value="{{ request('keyword') }}" placeholder=" なにをお探しですか？" type="text">
                    </form>
                </div>
            </div>
            <nav>
                <ul class="header-list">
                    <!-- ログイン状態でログアウトボタン、未ログイン時はログインボタン -->
                    @auth
                        <li class="header-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="header-link logout-button">ログアウト</button>
                            </form>
                        </li>
                    @else
                        <li class="header-item">
                            <a href="{{ route('login') }}" class="header-link">ログイン</a>
                        </li>
                    @endauth
                    
                        <li class="header-item">
                            <a href="{{ route('mypage') }}" class="header-link">マイページ</a>
                        </li>
                    
                    <li class="header-item">
                        <a href="{{ route('sell') }}" class="header-listing-button">出品</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    @yield('script')
</body>

</html>