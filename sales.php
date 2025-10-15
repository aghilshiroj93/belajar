<?php
require 'inc/db.php';
// fetch products for dropdown
$products = $pdo->query('SELECT id,nama,harga,stok FROM produk ORDER BY nama')->fetchAll();
require 'inc/header.php';
?>

<!-- Header Section -->
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Transaksi Penjualan</h1>
            <p class="text-gray-600 mt-1">Lakukan transaksi penjualan dengan mudah</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                <i class="fas fa-shopping-cart mr-1"></i>
                POS System
            </span>
            <span id="transactionTime" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                <?= date('d M Y H:i') ?>
            </span>
        </div>
    </div>
</div>

<!-- Main Transaction Form -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Products & Cart -->
    <div class="lg:col-span-2">
        <!-- Products Cart Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-shopping-basket text-blue-500"></i>
                    Keranjang Belanja
                </h3>
            </div>

            <form id="salesForm" method="post" action="save_sale.php">
                <div class="overflow-x-auto">
                    <table id="cart" class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                                <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="cart-row hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <select name="produk_id[]" class="product-select w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200">
                                        <option value="">-- Pilih Produk --</option>
                                        <?php foreach ($products as $p): ?>
                                            <option value="<?= $p['id'] ?>"
                                                data-harga="<?= $p['harga'] ?>"
                                                data-stok="<?= $p['stok'] ?>"
                                                class="<?= $p['stok'] == 0 ? 'text-red-500' : '' ?>">
                                                <?= htmlspecialchars($p['nama']) ?>
                                                <?= $p['stok'] == 0 ? '(Stok Habis)' : '(Stok: ' . $p['stok'] . ')' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">Rp</span>
                                        <input name="harga[]" class="harga w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700" readonly>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <input name="qty[]" type="number" class="qty w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200" value="1" min="1" max="1">
                                </td>
                                <td class="py-4 px-6">
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">Rp</span>
                                        <input name="total_item[]" class="total_item w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700 font-medium" readonly>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <button type="button" class="remove-row text-red-500 hover:text-red-700 transition-colors duration-200 p-2 rounded-lg hover:bg-red-50" title="Hapus Baris">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Add Row Button -->
                <div class="p-4 border-t border-gray-100">
                    <button id="addRow" type="button" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus-circle"></i>
                        Tambah Baris Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column - Payment Summary -->
    <div class="space-y-6">
        <!-- Payment Summary Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-receipt text-blue-500"></i>
                Ringkasan Pembayaran
            </h3>

            <div class="space-y-4">
                <!-- Subtotal -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Subtotal</span>
                    <div class="relative">
                        <span class="absolute left-0 -ml-4 text-gray-400">Rp</span>
                        <input id="subtotal" name="subtotal" readonly class="bg-transparent border-none text-right text-lg font-bold text-gray-800 w-32" value="0">
                    </div>
                </div>

                <!-- Tax -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pajak (%)</span>
                    <input id="pajakPercent" name="pajak_percent" value="0" type="number"
                        class="w-20 px-3 py-1.5 border border-gray-200 rounded-lg text-right focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200">
                </div>

                <!-- Tax Amount -->
                <div class="flex justify-between items-center text-red-600" id="taxAmountContainer" style="display: none;">
                    <span>Jumlah Pajak</span>
                    <span id="taxAmount" class="font-medium">Rp 0</span>
                </div>

                <!-- Grand Total -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-lg font-semibold text-gray-800">Grand Total</span>
                    <div class="relative">
                        <span class="absolute left-0 -ml-6 text-gray-400">Rp</span>
                        <input id="grand_total" name="grand_total" readonly
                            class="bg-transparent border-none text-right text-2xl font-bold text-blue-600 w-40" value="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Input Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-green-500"></i>
                Pembayaran
            </h3>

            <div class="space-y-4">
                <!-- Amount Paid -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">Rp</span>
                        <input id="jumlah_bayar" name="jumlah_bayar" type="number" step="0.01"
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-200 focus:border-green-500 transition-all duration-200 text-lg font-medium">
                    </div>
                </div>

                <!-- Change -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kembalian</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">Rp</span>
                        <input id="kembalian" name="kembalian" readonly
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-lg font-bold text-green-600">
                    </div>
                </div>

                <!-- Quick Payment Buttons -->
                <div class="pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bayar Cepat</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" class="quick-payment bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium transition-colors" data-multiplier="1">
                            Exact
                        </button>
                        <button type="button" class="quick-payment bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium transition-colors" data-multiplier="1.05">
                            +5%
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <button type="submit" form="salesForm"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-3.5 rounded-xl font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i>
                Simpan Transaksi
            </button>

            <button type="button" id="resetForm"
                class="w-full bg-gray-500 hover:bg-gray-600 text-white py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-redo"></i>
                Reset Form
            </button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update transaction time every minute
        function updateTransactionTime() {
            const now = new Date();
            document.getElementById('transactionTime').textContent =
                now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }) + ' ' +
                now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
        }
        setInterval(updateTransactionTime, 60000);

        // Add new row
        document.getElementById('addRow').addEventListener('click', function() {
            const tbody = document.querySelector('#cart tbody');
            const newRow = tbody.querySelector('.cart-row').cloneNode(true);

            // Clear values
            newRow.querySelector('.product-select').value = '';
            newRow.querySelector('.harga').value = '';
            newRow.querySelector('.qty').value = '1';
            newRow.querySelector('.total_item').value = '';
            newRow.querySelector('.qty').max = '1';

            tbody.appendChild(newRow);
            attachRowEvents(newRow);
        });

        // Attach events to a row
        function attachRowEvents(row) {
            const productSelect = row.querySelector('.product-select');
            const hargaInput = row.querySelector('.harga');
            const qtyInput = row.querySelector('.qty');
            const totalInput = row.querySelector('.total_item');
            const removeBtn = row.querySelector('.remove-row');

            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const harga = selectedOption.dataset.harga || '0';
                const stok = selectedOption.dataset.stok || '0';

                hargaInput.value = formatCurrency(harga);
                qtyInput.max = stok;
                qtyInput.value = '1';
                calculateRowTotal(this);
                calculateGrandTotal();
            });

            qtyInput.addEventListener('input', function() {
                calculateRowTotal(this);
                calculateGrandTotal();
            });

            removeBtn.addEventListener('click', function() {
                if (document.querySelectorAll('.cart-row').length > 1) {
                    row.remove();
                    calculateGrandTotal();
                } else {
                    alert('Minimal harus ada satu baris produk');
                }
            });
        }

        // Calculate row total
        function calculateRowTotal(element) {
            const row = element.closest('.cart-row');
            const harga = parseFloat(row.querySelector('.harga').value.replace(/[^\d]/g, '')) || 0;
            const qty = parseInt(row.querySelector('.qty').value) || 0;
            const total = harga * qty;

            row.querySelector('.total_item').value = formatCurrency(total);
        }

        // Calculate grand total
        function calculateGrandTotal() {
            let subtotal = 0;
            document.querySelectorAll('.total_item').forEach(input => {
                subtotal += parseFloat(input.value.replace(/[^\d]/g, '')) || 0;
            });

            const pajakPercent = parseFloat(document.getElementById('pajakPercent').value) || 0;
            const taxAmount = subtotal * (pajakPercent / 100);
            const grandTotal = subtotal + taxAmount;

            document.getElementById('subtotal').value = formatCurrency(subtotal);
            document.getElementById('grand_total').value = formatCurrency(grandTotal);

            // Show/hide tax amount
            const taxContainer = document.getElementById('taxAmountContainer');
            const taxAmountElement = document.getElementById('taxAmount');

            if (pajakPercent > 0) {
                taxContainer.style.display = 'flex';
                taxAmountElement.textContent = formatCurrency(taxAmount);
            } else {
                taxContainer.style.display = 'none';
            }

            calculateChange();
        }

        // Calculate change
        function calculateChange() {
            const grandTotal = parseFloat(document.getElementById('grand_total').value.replace(/[^\d]/g, '')) || 0;
            const jumlahBayar = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
            const kembalian = jumlahBayar - grandTotal;

            document.getElementById('kembalian').value = formatCurrency(Math.max(0, kembalian));

            // Visual feedback for change
            const changeInput = document.getElementById('kembalian');
            if (kembalian < 0) {
                changeInput.classList.remove('text-green-600');
                changeInput.classList.add('text-red-600');
            } else {
                changeInput.classList.remove('text-red-600');
                changeInput.classList.add('text-green-600');
            }
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        // Quick payment buttons
        document.querySelectorAll('.quick-payment').forEach(button => {
            button.addEventListener('click', function() {
                const grandTotal = parseFloat(document.getElementById('grand_total').value.replace(/[^\d]/g, '')) || 0;
                const multiplier = parseFloat(this.dataset.multiplier);
                const quickAmount = Math.ceil(grandTotal * multiplier);

                document.getElementById('jumlah_bayar').value = quickAmount;
                calculateChange();
            });
        });

        // Reset form
        document.getElementById('resetForm').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin mereset form transaksi?')) {
                document.getElementById('salesForm').reset();
                document.querySelectorAll('.cart-row').forEach((row, index) => {
                    if (index > 0) row.remove();
                });

                const firstRow = document.querySelector('.cart-row');
                firstRow.querySelector('.product-select').value = '';
                firstRow.querySelector('.harga').value = '';
                firstRow.querySelector('.qty').value = '1';
                firstRow.querySelector('.total_item').value = '';

                calculateGrandTotal();
            }
        });

        // Attach events to tax and payment inputs
        document.getElementById('pajakPercent').addEventListener('input', calculateGrandTotal);
        document.getElementById('jumlah_bayar').addEventListener('input', calculateChange);

        // Initialize events for first row
        attachRowEvents(document.querySelector('.cart-row'));
    });
</script>

<?php require 'inc/footer.php'; ?>