    </main>
    </div><!-- /flex-1 -->
    </div><!-- /frame -->

    <!-- FOOTER GLOBAL -->
    <footer class="bg-white border-t">
        <div class="max-w-6xl mx-auto px-4 py-4 text-xs text-gray-500 flex items-center justify-between">
            <span>© <span id="year"></span> Simple POS</span>
            <span>Made for Toko Roti</span>
        </div>
    </footer>

    <script>
        // Tahun
        document.getElementById('year').textContent = new Date().getFullYear();
        document.getElementById('yMini').textContent = new Date().getFullYear();

        // Drawer mobile
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const drawer = document.getElementById('drawer');
        const backdrop = document.getElementById('backdrop');

        function openDrawer() {
            drawer.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        }

        function closeDrawer() {
            drawer.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }
        openBtn?.addEventListener('click', openDrawer);
        closeBtn?.addEventListener('click', closeDrawer);
        backdrop?.addEventListener('click', closeDrawer);
    </script>
    </body>

    </html>