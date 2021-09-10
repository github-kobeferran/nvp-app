<nav class="navbar navbar-expand-md navbar-light border ">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{url('storage/images/system/icons/nvp-logo-pic.jpg')}}" alt="" style="max-width: 55px;" class="img-fluid">
            <span class="ml-2">NVP ANIMAL CLINIC </span>
        </a>
    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('LOGIN') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('REGISTER') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->first_name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            @if (auth()->user()->isAdmin())
                            
                                <a class="dropdown-item" href="{{ route('user.admin') }}"                                >
                                    {{auth()->user()->first_name}}
                                </a>
                            
                            @else

                                <a class="dropdown-item" href="/user">
                                    {{auth()->user()->first_name}}
                                </a>

                            @endif

                            <a class="dropdown-item" href="/inventory">
                                Shop
                            </a>

                            <a class="dropdown-item" href="/services">
                                Services
                            </a>


                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>