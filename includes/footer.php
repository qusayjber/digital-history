<?php
// includes/footer.php
// Digital History - Site Footer
?>
    </main>
    
    <footer class="site-footer" role="contentinfo">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">🌐 <span>DIGITAL<span>HISTORY</span></span></div>
                    <p><?php echo t('hero.description'); ?></p>
                    <div class="footer-social">
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" aria-label="Discord"><i class="fab fa-discord"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4><?php echo t('footer.about'); ?></h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>about.php"><?php echo t('footer.about'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>contact.php"><?php echo t('footer.contact'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>sources.php"><?php echo t('footer.sources'); ?></a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>timeline.php"><?php echo t('nav.timeline'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>computing.php"><?php echo t('nav.computing'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>programming.php"><?php echo t('nav.programming'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>internet.php"><?php echo t('nav.internet'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>ai.php"><?php echo t('nav.ai'); ?></a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>museum.php"><?php echo t('nav.museum'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>lab.php"><?php echo t('nav.lab'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>games.php"><?php echo t('nav.games'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>future.php"><?php echo t('nav.future'); ?></a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>privacy.php"><?php echo t('footer.privacy'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>terms.php"><?php echo t('footer.terms'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>cookie-policy.php">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p><?php echo t('footer.copyright'); ?></p>
                <p class="footer-version">v1.0 | Powered by Digital History</p>
            </div>
        </div>
    </footer>
    
    <!-- Main JavaScript -->
    <script src="<?php echo ASSETS_URL; ?>js/main.js"></script>
    
    <!-- Page specific JavaScript -->
    <?php if (isset($pageJS)): ?>
    <script><?php echo $pageJS; ?></script>
    <?php endif; ?>
    
    <!-- Analytics (optional) -->
    <?php if (isset($analyticsCode)): ?>
    <script async src="<?php echo $analyticsCode; ?>"></script>
    <?php endif; ?>
</body>
</html>