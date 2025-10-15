<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $title ?? 'Simple POS' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">
    <?php $active = $active ?? ''; ?>

    <!-- FRAME -->
    <div class="min-h-screen flex">

        <!-- SIDEBAR -->
        <aside id="sidebar" class="w-72 hidden md:flex md:flex-col bg-white border-r">
            <!-- Brand -->
            <div class="h-16 px-5 flex items-center gap-3 border-b">
                <span class="h-9 w-9 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 shadow"></span>
                <div class="leading-tight">
                    <div class="font-semibold">Toko Roti</div>
                    <div class="text-xs text-gray-500">Simple POS</div>
                </div>
            </div>

            <!-- Menu -->
            <nav class="p-3 space-y-1 text-sm">
                <a href="index.php"
                    class="group flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50
         <?= $active === 'dashboard' ? 'bg-amber-50 ring-1 ring-amber-200 text-amber-700' : '' ?>">
                    <!-- Icon Dashboard -->
                    <svg class="w-5 h-5 <?= $active === 'dashboard' ? 'text-amber-600' : 'text-gray-500 group-hover:text-gray-700' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="products.php"
                    class="group flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50
         <?= $active === 'products' ? 'bg-amber-50 ring-1 ring-amber-200 text-amber-700' : '' ?>">
                    <!-- Icon Produk -->
                    <svg class="w-5 h-5 <?= $active === 'products' ? 'text-amber-600' : 'text-gray-500 group-hover:text-gray-700' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-width="2" d="M4 7h16l-2 10a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L4 7m2.5-4h11L20 7H4l2.5-4zM8 11h8" />
                    </svg>
                    <span>Produk</span>
                </a>

                <a href="sales.php"
                    class="group flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50
         <?= $active === 'sales' ? 'bg-amber-50 ring-1 ring-amber-200 text-amber-700' : '' ?>">
                    <!-- Icon Transaksi -->
                    <svg class="w-5 h-5 <?= $active === 'sales' ? 'text-amber-600' : 'text-gray-500 group-hover:text-gray-700' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-width="2" d="M3 5h18M7 9v10m10-10v10M8 15h4m-4-4h8" />
                    </svg>
                    <span>Transaksi</span>
                </a>

                <a href="reports.php"
                    class="group flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50
         <?= $active === 'reports' ? 'bg-amber-50 ring-1 ring-amber-200 text-amber-700' : '' ?>">
                    <!-- Icon Rekap/Report -->
                    <svg class="w-5 h-5 <?= $active === 'reports' ? 'text-amber-600' : 'text-gray-500 group-hover:text-gray-700' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-width="2" d="M9 17V7m6 10V9m-9 8V9M3 5h18v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5z" />
                    </svg>
                    <span>Rekapitulasi</span>
                </a>
            </nav>

            <!-- Footer mini di sidebar -->
            <div class="mt-auto p-3 text-[11px] text-gray-400 border-t">
                © <span id="yMini"></span> Simple POS
            </div>
        </aside>

        <!-- MAIN (topbar kecil + konten) -->
        <div class="flex-1 min-w-0">
            <!-- Topbar kecil untuk mobile -->
            <div class="h-16 px-4 flex items-center justify-between bg-white border-b md:hidden">
                <div class="flex items-center gap-2">
                    <span class="h-7 w-7 rounded-lg bg-amber-500"></span>
                    <span class="font-semibold">Toko Roti</span>
                </div>
                <button id="openSidebar" class="p-2 rounded-lg border" aria-label="Buka sidebar">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Drawer sidebar (mobile) -->
            <div id="backdrop" class="fixed inset-0 bg-black/30 hidden md:hidden"></div>
            <aside id="drawer" class="fixed inset-y-0 left-0 w-72 bg-white border-r p-3 -translate-x-full transition-transform md:hidden z-50">
                <div class="h-12 flex items-center justify-between">
                    <div class="font-semibold">Menu</div>
                    <button id="closeSidebar" class="p-2 rounded-lg hover:bg-gray-50" aria-label="Tutup">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="space-y-1 text-sm">
                    <a href="index.php" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 <?= $active === 'dashboard' ? 'bg-amber-50 ring-1 ring-amber-200 text-amber-700' : '' ?>">
                        <svg class="w-5 h-5 <?= $active === 'dashboard' ? 'text-amber-600' : 'text-gray-500' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="products.php" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 <?= $active === 'products' ? 'bg-amber-50 ring-1 ring-amber-200 text-amber-700' : '' ?>">
                        <svg class="w-5 h-5 <?= $active === 'products' ? 'text-amber-600' : 'text-gray-500' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-width="2" d="M4 7h16l-2 10a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L4 7m2.5-4h11L20 7H4l2.5-4zM8 11h8" />
                        </svg>
                        <span>Produk</span>
                    </a>
                    <a href="sales.php" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 <?= $active === 'sales' ? 'bg-amber-50 ring-1 ring-amber-200 text-amber-700' : '' ?>">
                        <svg class="w-5 h-5 <?= $active === 'sales' ? 'text-amber-600' : 'text-gray-500' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-width="2" d="M3 5h18M7 9v10m10-10v10M8 15h4m-4-4h8" />
                        </svg>
                        <span>Transaksi</span>
                    </a>
                    <a href="reports.php" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 <?= $active === 'reports' ? 'bg-amber-50 ring-1 ring-amber-200 text-amber-700' : '' ?>">
                        <svg class="w-5 h-5 <?= $active === 'reports' ? 'text-amber-600' : 'text-gray-500' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-width="2" d="M9 17V7m6 10V9m-9 8V9M3 5h18v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5z" />
                        </svg>
                        <span>Rekapitulasi</span>
                    </a>
                </nav>
            </aside>

            <!-- CONTENT -->
            <main class="p-4 md:p-6">