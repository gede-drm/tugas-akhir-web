<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>ApartemenKu</title>

    <meta name="description" content="" />
    <meta name="author" content="Gede Darma (160420008)">

    <!-- Favicon -->
    <link rel="icon" href="#" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/DataTables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <p class="display-7 text-primary mb-1"><strong>ApartemenKu</strong></p>
                            </div>
                            <div class="row">
                                <p class="fs-6">Manajemen</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    {{-- Satpam --}}
                    <li class="menu-item {{ request()->is('satpam*') ? ' active' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-check-shield"></i>
                            <div data-i18n="Account Settings">Satpam</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('security.checkin') }}" class="menu-link">
                                    <div data-i18n="Account">Checkin/out Satpam</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('security.index') }}" class="menu-link">
                                    <div data-i18n="Notifications">Daftar Satpam</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Perizinan --}}
                    <li class="menu-item {{ request()->is('perizinan*') ? ' active' : '' }}">
                        <a href="{{ route('permission.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div>Perizinan</div>
                        </a>
                    </li>

                    {{-- Tenant --}}
                    <li class="menu-item {{ request()->is('tenant*') ? ' active' : '' }}">
                        <a href="{{ route('tenant.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-store"></i>
                            <div>Tenant</div>
                        </a>
                    </li>

                    {{-- Unit --}}
                    <li class="menu-item {{ request()->is('unit*') ? ' active' : '' }}">
                        <a href="{{ route('unit.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-building"></i>
                            <div>Unit</div>
                        </a>
                    </li>

                    {{-- Tower --}}
                    <li class="menu-item {{ request()->is('tower*') ? ' active' : '' }}">
                        <a href="{{ route('tower.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-buildings"></i>
                            <div>Tower</div>
                        </a>
                    </li>

                    {{-- Logout --}}
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Logout</span></li>
                    <li class="menu-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn menu-link btn-logout">
                                <i class="menu-icon tf-icons bx bx-log-out"></i>
                                <div>Logout</div>
                            </button>
                        </form>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- DataTables -->
    <script src="{{ asset('assets/DataTables/datatables.js') }}"></script>
    <!-- Script  -->
    @yield('script')
</body>

</html>
