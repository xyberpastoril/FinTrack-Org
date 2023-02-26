<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Assets -->
    <link rel="stylesheet" href="/assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/fontawesome-free-6.3.0-web/css/all.min.css">
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css"
    />

    <script src="/assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/fontawesome-free-6.3.0-web/js/all.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/scripts/choices.min.js"></script>
    <!-- Scripts -->
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'FinTrack-Org') }}
                </a>
                @if(session('semester'))
                    <p class="mt-0 pb-0 mb-0 d-none d-md-block">
                        Current Semester:
                        <strong>
                            @if(session('semester')->semester == 1)
                                First Semester,
                            @else
                                Second Semester,
                            @endif
                            AY {{ session('semester')->year }} - {{ session('semester')->year + 1 }}
                        </strong>
                    </p>
                @endif
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                            </li>

                            @if(Auth::user()->is_admin == 1 && session('semester'))
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown1" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Administration
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown1">
                                        <a class="dropdown-item" href="{{ route('users.index') }}">{{ __('Users') }}</a>

                                        <a class="dropdown-item" href="{{ route('degreePrograms.index') }}">{{ __('Degree Programs') }}</a>

                                        <a class="dropdown-item disabled" href="javascript:void(0)">{{ __('Semesters') }}</a>

                                        <a class="dropdown-item" href="{{ route('students.enrolled.index') }}">{{ __('Students') }}</a>
                                    </div>
                                </li>

                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown1" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Organization
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown1">

                                        <a class="dropdown-item" href="{{ route('attendances.index') }}">{{ __('Attendance') }}</a>

                                        <a class="dropdown-item" href="{{ route('payment.index') }}">{{ __('Payment') }}</a>

                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item" href="{{ route('events.index') }}">{{ __('Events') }}</a>

                                        <a class="dropdown-item" href="{{ route('fees.index') }}">{{ __('Fees') }}</a>

                                        <a class="dropdown-item" href="{{ route('items.index') }}">{{ __('Items') }}</a>

                                        <a class="dropdown-item disabled text-muted" href="javascript:void(0)">{{ __('Receipts') }}</a>

                                        <a class="dropdown-item disabled text-muted" href="javascript:void(0)">{{ __('Reports') }}</a>
                                    </div>
                                </li>
                            @elseif(Auth::user()->is_admin == 0 && session('semester'))
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown1" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Organization
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown1">
                                        <a class="dropdown-item" href="{{ route('attendances.index') }}">{{ __('Attendance') }}</a>

                                        <a class="dropdown-item disabled" href="javascript:void(0)">{{ __('Payment') }}</a>
                                    </div>
                                </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('account.index') }}">
                                        {{ __('Account') }}
                                    </a>

                                    <div class="dropdown-divider"></div>

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

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
