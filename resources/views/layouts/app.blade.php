<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | DistribuHub </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4338ca;
            --accent: #22d3ee;
            --bg-soft: #f6f7fb;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-soft);
            color: #1f2937;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(160deg, #4338ca 0%, #6366f1 55%, #22d3ee 130%);
            color: #fff;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding: 24px 16px;
        }

        .sidebar .brand {
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: .2px;
            margin-bottom: 36px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .brand .logo-mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.78);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 4px;
            font-weight: 500;
            position: relative;
            transition: all .15s ease;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .sidebar a.active {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            font-weight: 600;
        }

        .sidebar a.active::before {
            content: '';
            position: absolute;
            left: -16px;
            top: 8px;
            bottom: 8px;
            width: 4px;
            border-radius: 4px;
            background: #fff;
        }

        .main-content {
            margin-left: 250px;
            padding: 28px 32px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-header h2 {
            font-weight: 700;
            margin: 0;
        }

        .card-soft {
            border: none;
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(67, 56, 202, 0.08);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn,
        .form-control,
        .form-select {
            border-radius: 10px;
        }

        .ts-control {
            border-radius: 10px !important;
            border-color: #dee2e6 !important;
        }

        .ts-control.focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15) !important;
        }

        .ts-dropdown {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead {
            background: #eef0fd;
        }

        .table thead th {
            font-weight: 700;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            border-bottom: none;
            color: #5b5f82;
        }

        .badge-soft {
            background: #eef0fd;
            color: var(--primary-dark);
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 8px;
        }

        .main-content {
            animation: fadeIn .25s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pagination {
            margin-top: 16px;
            margin-bottom: 0;
            justify-content: flex-end;
            gap: 4px;
        }

        .pagination .page-link {
            border: none;
            border-radius: 8px !important;
            color: #4b4f6b;
            font-weight: 500;
            padding: 6px 12px;
        }

        .pagination .page-item.active .page-link {
            background: var(--primary);
            color: #fff;
        }

        .pagination .page-item.disabled .page-link {
            background: transparent;
            color: #c3c5d6;
        }

        .pagination .page-link:hover {
            background: #eef0fd;
            color: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="brand">
            <span class="logo-mark">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="white" />
                    <path d="M2 17L12 22L22 17" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 12L12 17L22 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity="0.6" />
                </svg>
            </span>
            DistribuHub
        </div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i> Dashboard
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-box"></i> Produk
        </a>
        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customer
        </a>
        <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Transaksi
        </a>
    </div>

    <div class="main-content">
        @if (session('success'))
        <div class="alert alert-success rounded-3 shadow-sm">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger rounded-3 shadow-sm">
            <i class="bi bi-x-circle-fill me-1"></i> {{ session('error') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        function confirmDelete(formId, message) {
            Swal.fire({
                title: 'Yakin?',
                text: message || 'Data yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    @yield('scripts')
</body>

</html>