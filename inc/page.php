<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';

get_header();
the_post();

echo '<div class="centered-container cc-master-header section-m" data-master-header-type="default">';
echo '<div class="master-header">';
echo '<h1 class="master-header__title">' . get_the_title() . '</h1>';
echo '</div>';
echo '</div>';

?>
<div class="main-content">
    <div class="centered-container cc-default-content section-m">
        <div class="default-content">
            <?php the_content() ?>
        </div>
    </div>
</div>
<?php
get_footer();
