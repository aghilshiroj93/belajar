<?php
require 'inc/db.php';
$id = $_GET['id'] ?? null;
if (!$id) die('ID transaksi diperlukan');
$stmt = $pdo->prepare('SELECT * FROM transaksi WHERE id=?');
$stmt->execute([$id]);
$trx = $stmt->fetch();
$stmt = $pdo->prepare('SELECT td.*, p.nama FROM transaksi_detail td JOIN produk p ON p.id=td.produk_id WHERE td.transaksi_id=?');
$stmt->execute([$id]);
$items = $stmt->fetchAll();
require 'inc/header.php';
?>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .struk-container,
        .struk-container * {
            visibility: visible;
        }

        .struk-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
            border: none !important;
        }

        .no-print {
            display: none !important;
        }

        .watermark {
            opacity: 0.1 !important;
        }
    }

    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 6rem;
        font-weight: bold;
        color: #e5e7eb;
        opacity: 0.3;
        z-index: 0;
        pointer-events: none;
        white-space: nowrap;
    }

    .struk-container {
        position: relative;
        background: white;
        border: 2px dashed #e5e7eb;
        z-index: 1;
    }

    .receipt-line {
        border-bottom: 1px dashed #d1d5db;
        margin: 0.5rem 0;
    }

    .dotted-line {
        border-bottom: 1px dotted #9ca3af;
        margin: 0.25rem 0;
    }
</style>

<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header Actions -->
    <div class="no-print flex items-center justify-between bg-white p-4 rounded-lg shadow-sm border">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Transaksi</h1>
            <p class="text-gray-600">Struk dan informasi lengkap transaksi</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="sales.php"
                class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            <button onclick="downloadStruk()"
                class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-download"></i>
                Download
            </button>
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-print"></i>
                Cetak
            </button>
        </div>
    </div>

    <!-- Receipt Container -->
    <div class="struk-container p-8 rounded-lg shadow-lg relative overflow-hidden">
        <!-- Watermark -->
        <div class="watermark">NOTA ASLI - <?= strtoupper($trx['kode']) ?></div>

        <!-- Receipt Header -->
        <div class="text-center mb-6 relative z-10">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-store text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">TOKO ROTI BAHAGIA</h2>
            <p class="text-gray-600 text-sm mt-1">Jl. Merdeka No. 123, Jakarta</p>
            <p class="text-gray-600 text-sm">Telp: (021) 1234-5678</p>
        </div>

        <div class="receipt-line"></div>

        <!-- Transaction Info -->
        <div class="grid grid-cols-2 gap-4 mb-4 text-sm relative z-10">
            <div>
                <div class="flex justify-between dotted-line">
                    <span class="text-gray-600">Kode Transaksi:</span>
                    <span class="font-semibold"><?= htmlspecialchars($trx['kode']) ?></span>
                </div>
                <div class="flex justify-between dotted-line">
                    <span class="text-gray-600">Kasir:</span>
                    <span class="font-semibold">Admin</span>
                </div>
            </div>
            <div>
                <div class="flex justify-between dotted-line">
                    <span class="text-gray-600">Tanggal:</span>
                    <span class="font-semibold"><?= date('d/m/Y', strtotime($trx['tanggal'])) ?></span>
                </div>
                <div class="flex justify-between dotted-line">
                    <span class="text-gray-600">Waktu:</span>
                    <span class="font-semibold"><?= date('H:i:s', strtotime($trx['tanggal'])) ?></span>
                </div>
            </div>
        </div>

        <div class="receipt-line"></div>

        <!-- Items Table -->
        <div class="mb-4 relative z-10">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-300">
                        <th class="text-left pb-2 font-semibold text-gray-700">Produk</th>
                        <th class="text-right pb-2 font-semibold text-gray-700">Harga</th>
                        <th class="text-center pb-2 font-semibold text-gray-700">Qty</th>
                        <th class="text-right pb-2 font-semibold text-gray-700">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $index => $it): ?>
                        <tr class="<?= $index % 2 === 0 ? 'bg-gray-50' : '' ?>">
                            <td class="py-2 text-gray-800"><?= htmlspecialchars($it['nama']) ?></td>
                            <td class="py-2 text-right text-gray-700">Rp <?= number_format($it['harga'], 0, ',', '.') ?></td>
                            <td class="py-2 text-center text-gray-700"><?= $it['qty'] ?></td>
                            <td class="py-2 text-right text-gray-800 font-medium">Rp <?= number_format($it['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="receipt-line"></div>

        <!-- Payment Summary -->
        <div class="space-y-2 text-sm mb-6 relative z-10">
            <div class="flex justify-between">
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-medium">Rp <?= number_format($trx['subtotal'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Pajak (<?= number_format(($trx['pajak'] / $trx['subtotal']) * 100, 0) ?>%):</span>
                <span class="font-medium">Rp <?= number_format($trx['pajak'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2 mt-2">
                <span class="text-gray-800">Grand Total:</span>
                <span class="text-blue-600">Rp <?= number_format($trx['grand_total'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="grid grid-cols-2 gap-6 text-sm mb-6 relative z-10">
            <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-green-700 font-semibold">Bayar:</span>
                    <span class="text-green-700 font-bold">Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?></span>
                </div>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-blue-700 font-semibold">Kembalian:</span>
                    <span class="text-blue-700 font-bold">Rp <?= number_format($trx['kembalian'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <div class="receipt-line"></div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-500 mt-6 relative z-10">
            <p class="mb-1">Terima kasih atas kunjungan Anda</p>
            <p class="mb-2">Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan</p>
            <p class="font-semibold">*** <?= $trx['kode'] ?> ***</p>
            <p class="mt-2">Struk ini merupakan bukti pembayaran yang sah</p>
        </div>

        <!-- Security Features -->
        <div class="text-center mt-4 relative z-10">
            <div class="inline-block border-2 border-dashed border-gray-300 px-4 py-1 rounded-lg">
                <span class="text-xs text-gray-500 font-mono">VALID</span>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="no-print bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Tambahan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="text-blue-600 font-bold text-2xl mb-1"><?= count($items) ?></div>
                <div class="text-blue-700">Total Item</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="text-green-600 font-bold text-2xl mb-1"><?= array_sum(array_column($items, 'qty')) ?></div>
                <div class="text-green-700">Total Quantity</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                <div class="text-purple-600 font-bold text-2xl mb-1"><?= number_format($trx['grand_total'], 0, ',', '.') ?></div>
                <div class="text-purple-700">Nilai Transaksi</div>
            </div>
        </div>
    </div>
</div>

<script>
    function downloadStruk() {
        // Create a temporary iframe for printing
        const printFrame = document.createElement('iframe');
        printFrame.style.position = 'absolute';
        printFrame.style.left = '-9999px';
        printFrame.style.top = '0';
        document.body.appendChild(printFrame);

        const printDocument = printFrame.contentDocument || printFrame.contentWindow.document;

        // Get the struk container HTML
        const strukHTML = document.querySelector('.struk-container').outerHTML;

        // Create a complete HTML document for printing
        const htmlContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Struk <?= $trx['kode'] ?></title>
            <style>
                body { 
                    font-family: 'Arial', sans-serif; 
                    margin: 0; 
                    padding: 20px;
                    background: white;
                }
                .watermark {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-45deg);
                    font-size: 6rem;
                    font-weight: bold;
                    color: #e5e7eb;
                    opacity: 0.1;
                    z-index: 0;
                    pointer-events: none;
                    white-space: nowrap;
                }
                .receipt-line {
                    border-bottom: 1px dashed #d1d5db;
                    margin: 0.5rem 0;
                }
                .dotted-line {
                    border-bottom: 1px dotted #9ca3af;
                    margin: 0.25rem 0;
                }
            </style>
        </head>
        <body>
            ${strukHTML}
        </body>
        </html>
    `;

        printDocument.open();
        printDocument.write(htmlContent);
        printDocument.close();

        // Wait for content to load then trigger print
        printFrame.onload = function() {
            printFrame.contentWindow.print();
            // Remove the iframe after printing
            setTimeout(() => {
                document.body.removeChild(printFrame);
            }, 1000);
        };
    }

    // Add keyboard shortcut for printing
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
    });
</script>

<?php require 'inc/footer.php'; ?>