<?php
$header_button = get_option('wpuprojectid_header_button');
echo '<div class="cc-header-main__wrapper">';
echo '<div class="centered-container cc-header-main">';
echo '<header class="header-main" role="banner">';
wputh_display_title();
wputh_display_toggle();
echo '<div class="header-main__menu"><div class="header-main__menu-inner">';
wp_nav_menu(array(
    'depth' => 2,
    'theme_location' => 'main',
    'fallback_cb' => 'wputh_default_menu',
    'container' => 'div',
    'container_class' => 'main-menu__wrapper',
    'menu_class' => 'main-menu'
));
echo wputh_get_social_links_html('header__social', 'icon');
echo wputh_maincontent_languages();
if ($header_button) {
    echo '<div class="header-main__menu-aside">' . get_wpu_acf_link($header_button, 'wpuprojectid-button wpuprojectid-button--go') . '</div>';
}
echo '</div></div>';
echo '</header>';
echo '</div>';
echo '</div>';
