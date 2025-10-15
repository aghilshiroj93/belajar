<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $title ?? 'Simple POS' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }

        .content-transition {
            transition: margin-left 0.3s ease-in-out;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .shadow-soft {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
        }

        .active-indicator {
            position: relative;
        }

        .active-indicator::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: #f59e0b;
            border-radius: 2px;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
    <?php $active = $active ?? ''; ?>

    <!-- Main Layout Container -->
    <div class="min-h-screen flex">

        <!-- Desktop Sidebar -->
        <aside id="sidebar" class="w-80 hidden md:flex flex-col bg-white shadow-soft sidebar-transition z-40">
            <!-- Brand Header -->
            <div class="h-20 px-6 flex items-center gap-4 border-b border-gray-100">
                <div class="h-12 w-12 rounded-2xl gradient-bg flex items-center justify-center shadow-md">
                    <i class="fas fa-bread-slice text-white text-lg"></i>
                </div>
                <div class="leading-tight">
                    <div class="font-bold text-gray-800">Toko Roti Bahagia</div>
                    <div class="text-xs text-gray-500 font-medium">Point of Sale System</div>
                </div>
            </div>

            <!-- User Info -->
            <!-- <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">AD</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-800 truncate">Admin Toko</div>
                        <div class="text-xs text-gray-500 truncate">admin@tokoroti.com</div>
                    </div>
                    <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse" title="Online"></div>
                </div>
            </div> -->

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="index.php"
                    class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                          <?= $active === 'dashboard' ? 'bg-primary-50 text-primary-700 border-r-4 border-primary-500 font-semibold active-indicator' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' ?>">
                    <div class="w-6 text-center">
                        <i class="fas fa-chart-pie <?= $active === 'dashboard' ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' ?>"></i>
                    </div>
                    <span class="font-medium">Dashboard</span>
                    <?php if ($active === 'dashboard'): ?>
                        <div class="ml-auto h-2 w-2 rounded-full bg-primary-500"></div>
                    <?php endif; ?>
                </a>

                <a href="products.php"
                    class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                          <?= $active === 'products' ? 'bg-primary-50 text-primary-700 border-r-4 border-primary-500 font-semibold active-indicator' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' ?>">
                    <div class="w-6 text-center">
                        <i class="fas fa-box-open <?= $active === 'products' ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' ?>"></i>
                    </div>
                    <span class="font-medium">Produk</span>
                    <?php if ($active === 'products'): ?>
                        <div class="ml-auto h-2 w-2 rounded-full bg-primary-500"></div>
                    <?php endif; ?>
                </a>

                <a href="sales.php"
                    class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                          <?= $active === 'sales' ? 'bg-primary-50 text-primary-700 border-r-4 border-primary-500 font-semibold active-indicator' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' ?>">
                    <div class="w-6 text-center">
                        <i class="fas fa-shopping-cart <?= $active === 'sales' ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' ?>"></i>
                    </div>
                    <span class="font-medium">Transaksi</span>
                    <?php if ($active === 'sales'): ?>
                        <div class="ml-auto h-2 w-2 rounded-full bg-primary-500"></div>
                    <?php endif; ?>
                </a>

                <a href="reports.php"
                    class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                          <?= $active === 'reports' ? 'bg-primary-50 text-primary-700 border-r-4 border-primary-500 font-semibold active-indicator' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' ?>">
                    <div class="w-6 text-center">
                        <i class="fas fa-chart-bar <?= $active === 'reports' ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' ?>"></i>
                    </div>
                    <span class="font-medium">Rekapitulasi</span>
                    <?php if ($active === 'reports'): ?>
                        <div class="ml-auto h-2 w-2 rounded-full bg-primary-500"></div>
                    <?php endif; ?>
                </a>

                <!-- Additional Menu Items -->
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <a href="customers.php"
                        class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-800">
                        <div class="w-6 text-center">
                            <i class="fas fa-users text-gray-400 group-hover:text-gray-600"></i>
                        </div>
                        <span class="font-medium">Pelanggan</span>
                    </a>

                    <a href="settings.php"
                        class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-800">
                        <div class="w-6 text-center">
                            <i class="fas fa-cog text-gray-400 group-hover:text-gray-600"></i>
                        </div>
                        <span class="font-medium">Pengaturan</span>
                    </a>
                </div>
            </nav>

            <!-- Footer -->
            <div class="p-4 border-t border-gray-100">
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <div class="text-xs text-gray-500 mb-2">Sistem POS v2.0</div>
                    <div class="text-[10px] text-gray-400">
                        © <span id="yMini"><?= date('Y') ?></span> Toko Roti Bahagia
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Mobile Header -->
            <header class="h-16 bg-white shadow-soft border-b border-gray-100 md:hidden sticky top-0 z-30">
                <div class="h-full px-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button id="openSidebar" class="p-2 rounded-xl hover:bg-gray-50 transition-colors" aria-label="Buka menu">
                            <i class="fas fa-bars text-gray-600 text-lg"></i>
                        </button>
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-xl gradient-bg flex items-center justify-center">
                                <i class="fas fa-bread-slice text-white text-sm"></i>
                            </div>
                            <span class="font-bold text-gray-800">Toko Roti</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Notification Bell -->
                        <button class="p-2 rounded-xl hover:bg-gray-50 relative transition-colors" aria-label="Notifikasi">
                            <i class="fas fa-bell text-gray-600"></i>
                            <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500"></span>
                        </button>

                        <!-- User Avatar Mobile -->
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                            <span class="text-white font-semibold text-xs">AD</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Desktop Header -->
            <header class="hidden md:flex h-20 bg-white shadow-soft border-b border-gray-100 items-center justify-between px-8">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-800"><?= $title ?? 'Dashboard' ?></h1>
                    <nav class="flex text-sm text-gray-500 mt-1">
                        <a href="index.php" class="hover:text-primary-600 transition-colors">Dashboard</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-800 font-medium"><?= $title ?? 'Overview' ?></span>
                    </nav>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text"
                            placeholder="Cari..."
                            class="pl-10 pr-4 py-2.5 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-primary-200 focus:bg-white transition-all w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3">
                        <!-- Notification -->
                        <button class="relative p-2.5 rounded-xl hover:bg-gray-50 transition-colors group" aria-label="Notifikasi">
                            <i class="fas fa-bell text-gray-600 group-hover:text-primary-600 transition-colors"></i>
                            <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500"></span>
                        </button>

                        <!-- Messages -->
                        <button class="relative p-2.5 rounded-xl hover:bg-gray-50 transition-colors group" aria-label="Pesan">
                            <i class="fas fa-envelope text-gray-600 group-hover:text-primary-600 transition-colors"></i>
                            <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-blue-500"></span>
                        </button>

                        <!-- User Menu -->
                        <div class="flex items-center gap-3 pl-3 border-l border-gray-200">
                            <div class="text-right">
                                <div class="font-medium text-gray-800">Admin Toko</div>
                                <div class="text-xs text-gray-500">Administrator</div>
                            </div>
                            <div class="relative group">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center cursor-pointer">
                                    <span class="text-white font-semibold">AD</span>
                                </div>
                                <!-- Dropdown Menu -->
                                <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-40">
                                    <a href="profile.php" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-user text-gray-400 w-5"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    <a href="settings.php" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-cog text-gray-400 w-5"></i>
                                        <span>Pengaturan</span>
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="logout.php" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt text-red-400 w-5"></i>
                                        <span>Keluar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile Sidebar Overlay -->
            <div id="backdrop" class="fixed inset-0 bg-black/50 hidden md:hidden z-40 transition-opacity"></div>

            <!-- Mobile Sidebar -->
            <aside id="drawer" class="fixed inset-y-0 left-0 w-80 bg-white shadow-2xl transform -translate-x-full sidebar-transition md:hidden z-50">
                <div class="h-16 px-4 flex items-center justify-between border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl gradient-bg flex items-center justify-center">
                            <i class="fas fa-bread-slice text-white"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">Toko Roti</div>
                            <div class="text-xs text-gray-500">POS System</div>
                        </div>
                    </div>
                    <button id="closeSidebar" class="p-2 rounded-xl hover:bg-gray-50 transition-colors" aria-label="Tutup menu">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>

                <!-- Mobile Navigation -->
                <nav class="p-4 space-y-2">
                    <a href="index.php" class="flex items-center gap-4 px-4 py-3 rounded-xl <?= $active === 'dashboard' ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600' ?>">
                        <i class="fas fa-chart-pie w-5 text-center <?= $active === 'dashboard' ? 'text-primary-600' : 'text-gray-400' ?>"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="products.php" class="flex items-center gap-4 px-4 py-3 rounded-xl <?= $active === 'products' ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600' ?>">
                        <i class="fas fa-box-open w-5 text-center <?= $active === 'products' ? 'text-primary-600' : 'text-gray-400' ?>"></i>
                        <span>Produk</span>
                    </a>
                    <a href="sales.php" class="flex items-center gap-4 px-4 py-3 rounded-xl <?= $active === 'sales' ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600' ?>">
                        <i class="fas fa-shopping-cart w-5 text-center <?= $active === 'sales' ? 'text-primary-600' : 'text-gray-400' ?>"></i>
                        <span>Transaksi</span>
                    </a>
                    <a href="reports.php" class="flex items-center gap-4 px-4 py-3 rounded-xl <?= $active === 'reports' ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600' ?>">
                        <i class="fas fa-chart-bar w-5 text-center <?= $active === 'reports' ? 'text-primary-600' : 'text-gray-400' ?>"></i>
                        <span>Rekapitulasi</span>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 content-transition">
                <!-- Content will be inserted here -->