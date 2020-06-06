<?php
$footer_items = wputh_get_menu_items('footer');
?><div class="centered-container cc-footer">
    <footer class="footer">
        <div class="footer-copyright"><?php echo '&copy; ' . date('Y') . ' - ' . get_bloginfo('name'); ?></div>
        <?php if ($footer_items): ?>
        <div class="footer-links"><?php echo '<ul><li>' . implode('</li><li>', $footer_items) . '</li></ul>'; ?></div>
        <?php endif;?>
    </footer>
</div>
