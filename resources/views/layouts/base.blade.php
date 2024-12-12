<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'نظام إدارة الفواتير') }}</title>

    <!-- Fonts -->
    <link href="{{ asset('assets/fonts/Rubik-Regular.ttf') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/fonts/Rubik-Bold.ttf') }}" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('assets/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>@font-face {
        font-family: 'Rubik';
        src: url('{{ asset('assets/fonts/Rubik-Regular.ttf') }}') format('truetype');
        font-weight: normal;
    }

    @font-face {
        font-family: 'Rubik';
        src: url('{{ asset('assets/fonts/Rubik-Bold.ttf') }}') format('truetype');
        font-weight: bold;
    }
        body {
            font-family: 'Rubik', sans-serif;
        }
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .navbar-brand {
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .navbar-nav {
            margin-top: 1rem;
        }
        .nav-link {
            color: #fff;
            transition: color 0.2s ease;
        }
        .nav-link:hover {
            color: #ccc;
        }
        .nav-link.active {
            color: #fff;
            background-color: #555;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">نظام إدارة الفواتير</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                الرئيسية                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                            الفواتير
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                            العملاء
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            المنتجات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            التقارير
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @hasSection('content')
        @yield('content')
    @else
        {{ $slot ?? '' }}
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')

    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2024 نظام إدارة الفواتير. جميع الحقوق محفوظة.</p>
        <div>
            <a href="#" class="text-white">Privacy Policy</a> |
            <a href="#" class="text-white">Terms of Service</a>
        </div>
    </footer>
</body>
</html>
