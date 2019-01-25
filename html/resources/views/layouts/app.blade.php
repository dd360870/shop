<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ secure_asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div class="d-flex flex-column" style="min-height:100vh;" id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item rounded @if(isset($type) ? ($type==1) : false) active @endif">
                            <a class="nav-link" href="/men">MEN</a>
                        </li>
                        <li class="nav-item rounded @if(isset($type) ? ($type==2) : false) active @endif">
                            <a class="nav-link" href="/women">WOMEN</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <span class="navbar-text" style="color:black; margin:0 20px 0 0; cursor:pointer;">
                            <a href="/shopping-cart" id="shopping-cart"
                                data-toggle="popover"
                                data-placement="bottom"
                                title="購物車"
                                data-content="">
                                購物車[{{ count(Session::get('cart', [])) }}]
                            </a>
                        </span>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else
                            @if(Auth::user()->admin == 1)
                                <li class="nav-item form-inline">
                                    <a href="/admin" role="button" class="btn btn-sm btn-danger" style="margin:auto;">Admin Panel</a>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('order') }}">
                                        @lang('My Orders')
                                    </a>
                                    <a class="dropdown-item" href="{{ route('user') }}">
                                        @lang('Setting')
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        <footer class="mt-auto">
            <div class="py-2 bg-light border-top" style="width:100%;">
                <div class="container" style="text-align:center;">
                    <span class="text-muted">Copyright © 2019 by Ruzy</span>
                </div>
            </div>
        </footer>
        @yield('script')
    </div>
    
</body>

<script type="text/javascript">
$('#shopping-cart').popover({
  trigger: 'hover',
  html: true,
  delay: { "show": 100, "hide": 300 },
})

function refreshCart(animation = false) {
    $.ajax({
        type: "GET",
        url: '/shopping-cart/detail',
        //data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            console.log(data); // show response from the php script.
            let str = '';
            if(data.count > 0) {
                str = '<table class="table table-bordered" style="text-align: center;"><tr><th>商品名稱</th><th>數量</th><th>單價</th></tr>';
                data.detail.forEach(e => {
                    str += '<tr><td>'+e.name+'</td><td>'+e.buyAmount+'</td><td>'+e.price+'</td></tr>';
                });
                str += '<tr><td colspan="2" class="border-right-0" style="text-align: left;">總價：</td><td class="border-left-0">NT$ '+data.total+'</td></tr></table>';
            } else {
                str = "<p style='padding: 30px;'>沒有商品</p>";
            }
            $('#shopping-cart').attr('data-content', str);
            $('#shopping-cart').html('購物車['+data.count+']');
            if(animation) {
                /*$('#shopping-cart').animate({
                    fontSize: "2em",
                }, 200, function() {
                    $(this).animate({
                        fontSize: "1em",
                    }, 100);
                });*/
            }
        }
    });
}

$(document).ready(function() {
    refreshCart();
})
</script>
</html>
