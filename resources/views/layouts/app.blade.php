<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('meta')
    <title>ระบบยืม-คืนพัสดุ</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/js-datepicker/dist/datepicker.min.css">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            padding-top: 70px;
        }
        .table td.fit,
        .table th.fit {
            white-space: nowrap;
            width: 1%;
        }
        .hiddenRow {
            padding: 0 !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    ระบบยืม-คืนพัสดุ
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('home')) ? 'active' : '' }}" aria-current="page" href="/home">ระบบยืมวัสดุ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('kuru')) ? 'active' : '' }}" aria-current="page" href="/kuru">ระบบครุภัณฑ์</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('log')) ? 'active' : '' }}" href="/log">ประวัติการทำรายการ</a>
                        </li>
                        @can('staff-action')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('staff/edit') ? 'active' : '' }}" href="/staff/edit">แก้ไขรายการอุปกรณ์</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('staff/logmonitor') ? 'active' : '' }}" href="/staff/logmonitor">ยืนยันการทำรายการ</a>
                        </li>
                        @endcan

                        {{-- <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </li> --}}
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                          </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/js-datepicker"></script>
    @yield('script')
</body>
</html>
