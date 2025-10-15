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

    <!-- <script>
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
    </script> -->
    <script>
        // Mobile sidebar functionality
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const drawer = document.getElementById('drawer');
        const backdrop = document.getElementById('backdrop');

        function openMobileSidebar() {
            drawer.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            drawer.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        openSidebar.addEventListener('click', openMobileSidebar);
        closeSidebar.addEventListener('click', closeMobileSidebar);
        backdrop.addEventListener('click', closeMobileSidebar);

        // Set current year in footer
        document.getElementById('yMini').textContent = new Date().getFullYear();

        // Close mobile sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMobileSidebar();
            }
        });
    </script>
    </body>

    </html>