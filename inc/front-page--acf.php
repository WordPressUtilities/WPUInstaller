<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';
get_header();
the_post();
?>
<div class="main-content">
    <?php echo get_wpu_acf_flexible_content('content-home-blocks'); ?>
</div>
<?php
get_footer();
