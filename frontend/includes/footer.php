<?php $root = $root ?? (defined('ROOT_URL') ? ROOT_URL : './'); ?>
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <img src="<?= $root ?>assets/images/logo.png" style="height:70px; margin-bottom:32px; filter:brightness(0) invert(1);">
                    <p style="line-height:2; max-width:400px; font-size:16px; color:var(--c-text-muted);" data-i18n="footer_desc">المنصة الأولى في الوطن العربي لحجز الملاعب الرياضية بكل سهولة وأمان عبر .</p>
                </div>
                <div>
                    <h4 style="color:#ffffff; margin-bottom:32px; font-size:20px; font-weight:900;" data-i18n="footer_links_title">روابط سريعة</h4>
                    <ul style="list-style:none; display:flex; flex-direction:column; gap:20px; padding:0;">
                        <li><a href="<?= $root ?>index.php" class="footer-link" data-i18n="footer_home">الرئيسية</a></li>
                        <li><a href="<?= $root ?>stadiums.php" class="footer-link" data-i18n="footer_stadiums">الملاعب</a></li>
                        <li><a href="<?= $root ?>community.php" class="footer-link" data-i18n="nav_community">المجتمع</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="color:#ffffff; margin-bottom:32px; font-size:20px; font-weight:900;" data-i18n="footer_contact_title">تواصل معنا</h4>
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <div style="display:flex; align-items:center; gap:12px; font-size:15px; color:var(--c-text-muted);">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            support@kicktime.com
                        </div>
                        <div style="display:flex; align-items:center; gap:12px; font-size:15px; color:var(--c-text-muted);">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            +20 123 456 789
                        </div>
                    </div>
                </div>
            </div>
            <div style="border-top:1px solid var(--c-border); padding-top:40px; text-align:center; font-size:14px; color:var(--c-text-muted);">
                &copy; 2026 . <span data-i18n="footer_rights">جميع الحقوق محفوظة</span>
            </div>
        </div>
    </footer>

    <!-- Modals Container -->
    <div id="modals-container"></div>

    <!-- Scripts at Bottom (defer for non-blocking load) -->
    <script src="<?= $root ?>assets/js/api.js?v=3" defer></script>
    <script src="<?= $root ?>assets/js/components.js?v=3" defer></script>
    <script src="<?= $root ?>assets/js/app.js?v=3" defer></script>
</body>
</html>
