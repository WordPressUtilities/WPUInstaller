<?php
echo '<div class="centered-container cc-header-main">';
echo '<header class="header-main" role="banner">';
wputh_display_title();
wputh_display_toggle();
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
echo '</header>';
echo '</div>';
