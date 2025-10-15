// assets/script.js - handles cart interactions
document.addEventListener('DOMContentLoaded', function(){
  function recalcRow(row){
    const harga = parseFloat(row.querySelector('.product-select').selectedOptions[0]?.dataset?.harga || 0) || parseFloat(row.querySelector('.harga').value || 0);
    row.querySelector('.harga').value = harga.toFixed(2);
    const qty = parseInt(row.querySelector('.qty').value || 0);
    const total = harga * qty;
    row.querySelector('.total_item').value = total.toFixed(2);
  }
  function recalcAll(){
    const rows = document.querySelectorAll('.cart-row');
    let subtotal = 0;
    rows.forEach(r=>{ recalcRow(r); subtotal += parseFloat(r.querySelector('.total_item').value || 0); });
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    const pajakPercent = parseFloat(document.getElementById('pajakPercent').value || 0);
    const pajak = subtotal * (pajakPercent/100);
    const grand = subtotal + pajak;
    document.getElementById('grand_total').value = grand.toFixed(2);
    const bayar = parseFloat(document.getElementById('jumlah_bayar').value || 0);
    document.getElementById('kembalian').value = (bayar - grand).toFixed(2);
  }

  document.getElementById('addRow')?.addEventListener('click', function(){
    const tbody = document.querySelector('#cart tbody');
    const tr = document.querySelector('.cart-row').cloneNode(true);
    // reset values
    tr.querySelectorAll('input').forEach(i=>i.value='');
    tr.querySelector('.qty').value = 1;
    tbody.appendChild(tr);
  });

  document.body.addEventListener('change', function(e){
    if(e.target.matches('.product-select')){
      const row = e.target.closest('.cart-row');
      recalcRow(row);
      recalcAll();
    }
    if(e.target.matches('#pajakPercent')) recalcAll();
  });
  document.body.addEventListener('input', function(e){
    if(e.target.matches('.qty') || e.target.matches('#jumlah_bayar')) recalcAll();
  });
  document.body.addEventListener('click', function(e){
    if(e.target.matches('.remove-row')){
      const row = e.target.closest('.cart-row');
      row.remove(); recalcAll();
    }
  });

  recalcAll();
});