<?php
require 'inc/db.php';
// fetch products for dropdown
$products = $pdo->query('SELECT id,nama,harga,stok FROM produk ORDER BY nama')->fetchAll();
require 'inc/header.php';
?>

<!-- Header Section -->
<div class="mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Transaksi Baru</h1>
            <p class="text-gray-600 mt-1">Kelola penjualan dengan mudah dan cepat</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span class="text-sm font-medium">POS Aktif</span>
            </div>
            <div class="text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                <i class="fas fa-clock mr-2"></i>
                <span id="transactionTime"><?= date('d M Y H:i') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Main Transaction Form -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Products Section -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-blue-500"></i>
                    Daftar Produk
                </h3>
            </div>

            <form id="salesForm" method="post" action="save_sale.php">
                <div class="p-1">
                    <table id="cart" class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left text-sm text-gray-600 font-medium">
                                <th class="py-3 px-4">Produk</th>
                                <th class="py-3 px-4">Harga</th>
                                <th class="py-3 px-4">Qty</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="cart-row hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">
                                    <select name="produk_id[]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                                        <option value="">Pilih produk...</option>
                                        <?php foreach ($products as $p): ?>
                                            <option value="<?= $p['id'] ?>"
                                                data-harga="<?= $p['harga'] ?>"
                                                data-stok="<?= $p['stok'] ?>"
                                                class="<?= $p['stok'] == 0 ? 'text-red-500' : '' ?>">
                                                <?= htmlspecialchars($p['nama']) ?>
                                                <?= $p['stok'] == 0 ? ' (Stok Habis)' : ' (Stok: ' . $p['stok'] . ')' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                        <input name="harga[]" class="harga w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" readonly>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <input name="qty[]" type="number" class="qty w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" value="1" min="1" max="1">
                                </td>
                                <td class="py-3 px-4">
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                        <input name="total_item[]" class="total_item w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-800 font-medium" readonly>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <button type="button" class="remove-row text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Add Row Button -->
                <div class="p-4 border-t border-gray-200">
                    <button id="addRow" type="button" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus"></i>
                        Tambah Baris
                    </button>
                </div>

                <!-- Hidden fields for raw values -->
                <input type="hidden" id="hidden_subtotal" name="subtotal_raw" value="0">
                <input type="hidden" id="hidden_pajak_percent" name="pajak_percent_raw" value="0">
                <input type="hidden" id="hidden_grand_total" name="grand_total_raw" value="0">
                <input type="hidden" id="hidden_jumlah_bayar" name="jumlah_bayar_raw" value="0">
                <input type="hidden" id="hidden_kembalian" name="kembalian_raw" value="0">
            </form>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="space-y-6">
        <!-- Summary Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-receipt text-blue-500"></i>
                Ringkasan
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Subtotal</span>
                    <div class="text-right">
                        <span class="text-sm text-gray-500 mr-1">Rp</span>
                        <input id="subtotal" name="subtotal" readonly class="bg-transparent border-none text-gray-800 font-semibold text-lg w-32 text-right" value="0">
                    </div>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Pajak (%)</span>
                    <input id="pajakPercent" name="pajak_percent" value="0" type="number"
                        class="w-20 px-2 py-1 border border-gray-300 rounded text-right focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="flex justify-between items-center text-red-600 text-sm" id="taxAmountContainer" style="display: none;">
                    <span>Jumlah Pajak</span>
                    <span id="taxAmount">Rp 0</span>
                </div>

                <div class="border-t border-gray-200 pt-3 mt-2">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">Grand Total</span>
                        <div class="text-right">
                            <span class="text-sm text-gray-500 mr-1">Rp</span>
                            <input id="grand_total" name="grand_total" readonly
                                class="bg-transparent border-none text-blue-600 font-bold text-xl w-36 text-right" value="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-credit-card text-green-500"></i>
                Pembayaran
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Jumlah Bayar</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input id="jumlah_bayar" name="jumlah_bayar" type="number" step="0.01"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-2">Kembalian</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input id="kembalian" name="kembalian" readonly
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 font-semibold">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="block text-sm text-gray-600 mb-2">Bayar Cepat</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" class="quick-payment bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded text-sm transition-colors" data-multiplier="1">
                            Exact Amount
                        </button>
                        <button type="button" class="quick-payment bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded text-sm transition-colors" data-multiplier="1.05">
                            +5%
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <button type="submit" form="salesForm"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-check"></i>
                Simpan Transaksi
            </button>

            <button type="button" id="resetForm"
                class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2.5 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-refresh"></i>
                Reset Form
            </button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update transaction time
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

            const hargaEl = newRow.querySelector('.harga');
            const totalEl = newRow.querySelector('.total_item');
            if (hargaEl && hargaEl.dataset) delete hargaEl.dataset.raw;
            if (totalEl && totalEl.dataset) delete totalEl.dataset.raw;

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
                const hargaNum = parseFloat(selectedOption.dataset.harga) || 0;
                const stok = selectedOption.dataset.stok || '0';

                hargaInput.dataset.raw = hargaNum;
                hargaInput.value = formatCurrency(hargaNum);
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
            const hargaInputEl = row.querySelector('.harga');
            let harga = parseFloat(hargaInputEl.dataset.raw);
            if (!isFinite(harga)) {
                const rawStr = (hargaInputEl.value || '').replace(/\./g, '').replace(',', '.').replace(/[^0-9\.\-]/g, '');
                harga = parseFloat(rawStr) || 0;
            }
            const qty = parseInt(row.querySelector('.qty').value) || 0;
            const total = harga * qty;

            const totalInput = row.querySelector('.total_item');
            totalInput.value = formatCurrency(total);
            totalInput.dataset.raw = total.toFixed(2);
        }

        // Calculate grand total
        function calculateGrandTotal() {
            let subtotal = 0;
            document.querySelectorAll('.total_item').forEach(input => {
                if (input.dataset && input.dataset.raw) {
                    subtotal += parseFloat(input.dataset.raw) || 0;
                } else {
                    const raw = (input.value || '').replace(/\./g, '').replace(',', '.').replace(/[^0-9\.\-]/g, '');
                    subtotal += parseFloat(raw) || 0;
                }
            });

            const pajakPercent = parseFloat(document.getElementById('pajakPercent').value) || 0;
            const taxAmount = subtotal * (pajakPercent / 100);
            const grandTotal = subtotal + taxAmount;

            document.getElementById('subtotal').value = formatCurrency(subtotal);
            document.getElementById('grand_total').value = formatCurrency(grandTotal);

            document.getElementById('hidden_subtotal').value = subtotal.toFixed(2);
            document.getElementById('hidden_pajak_percent').value = pajakPercent;
            document.getElementById('hidden_grand_total').value = grandTotal.toFixed(2);

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
            const grandTotalRaw = parseFloat(document.getElementById('hidden_grand_total').value) || 0;
            const jumlahBayar = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
            const kembalian = jumlahBayar - grandTotalRaw;

            const kembalianInput = document.getElementById('kembalian');
            kembalianInput.value = formatCurrency(Math.max(0, kembalian));

            document.getElementById('hidden_jumlah_bayar').value = jumlahBayar.toFixed(2);
            document.getElementById('hidden_kembalian').value = Math.max(0, kembalian).toFixed(2);

            // Visual feedback
            if (kembalian < 0) {
                kembalianInput.classList.remove('text-green-600');
                kembalianInput.classList.add('text-red-600');
            } else {
                kembalianInput.classList.remove('text-red-600');
                kembalianInput.classList.add('text-green-600');
            }
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        // Quick payment buttons
        document.querySelectorAll('.quick-payment').forEach(button => {
            button.addEventListener('click', function() {
                const grandTotal = parseFloat(document.getElementById('hidden_grand_total').value) || 0;
                const multiplier = parseFloat(this.dataset.multiplier);
                const quickAmount = Math.ceil(grandTotal * multiplier);

                document.getElementById('jumlah_bayar').value = quickAmount;
                calculateChange();
            });
        });

        // Form submit handler
        document.getElementById('salesForm').addEventListener('submit', function(e) {
            calculateGrandTotal();
            calculateChange();

            document.querySelectorAll('input[name="total_item[]"]').forEach(function(inp) {
                const raw = inp.dataset.raw ? inp.dataset.raw : (parseFloat(inp.value.replace(/\./g, '').replace(',', '.') || 0)).toFixed(2);
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'total_item_raw[]';
                hidden.value = raw;
                e.target.appendChild(hidden);
            });

            document.querySelectorAll('input[name="harga[]"]').forEach(function(inp) {
                const raw = inp.dataset && inp.dataset.raw ? parseFloat(inp.dataset.raw).toFixed(2) : (parseFloat(inp.value.replace(/\./g, '').replace(',', '.') || 0).toFixed(2));
                inp.value = raw;
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

        // Attach events
        document.getElementById('pajakPercent').addEventListener('input', calculateGrandTotal);
        document.getElementById('jumlah_bayar').addEventListener('input', calculateChange);

        // Initialize
        document.querySelectorAll('.cart-row').forEach(function(row) {
            attachRowEvents(row);
            const select = row.querySelector('.product-select');
            if (select && select.value) {
                calculateRowTotal(select);
            }
        });
        calculateGrandTotal();
    });
</script>

<?php require 'inc/footer.php'; ?>