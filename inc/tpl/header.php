<?php

/* Wrapper start */
echo '<div class="cc-header-main__wrapper"><div class="centered-container cc-header-main"><header class="header-main" role="banner">';

wputh_display_title();
wputh_display_toggle();

/* Main menu (overlay on mobile) */
echo '<div class="header-main__menu"><div class="header-main__menu-inner">';

/* Menu */
wp_nav_menu(array(
    'depth' => 2,
    'theme_location' => 'main',
    'fallback_cb' => 'wputh_default_menu',
    'container' => 'div',
    'container_class' => 'main-menu__wrapper',
    'menu_class' => 'main-menu'
));

/* Social links */
echo wputh_get_social_links_html('header__social', 'icon');

/* Lang switcher */
echo wputh_maincontent_languages();

/* Buttons */
$header_button = wputh_l18n_get_option('wpuprojectid_header_button');
$header_button2 = wputh_l18n_get_option('wpuprojectid_header_button2');
if ($header_button || $header_button2) {
    echo '<div class="header-main__menu-aside">';
    echo get_wpu_acf_link($header_button2, 'wpuprojectid-button wpuprojectid-button--secondary');
    echo get_wpu_acf_link($header_button, 'wpuprojectid-button');
    echo '</div>';
}

echo '</div></div>';

/* Wrapper end */
echo '</header></div></div>';
