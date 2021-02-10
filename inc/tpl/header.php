<div class="centered-container cc-header-main">
<header class="header-main">
<?php
wputh_display_title();
wp_nav_menu( array(
    'depth' => 2,
    'theme_location' => 'main',
    'fallback_cb' => 'wputh_default_menu',
    'container' => 'div',
    'container_class' => 'main-menu__wrapper',
    'menu_class' => 'main-menu'
) );
echo wputh_get_social_links_html('header__social','icon');
echo wputh_maincontent_languages();
?>
<a href="#" class="nav-toggle"><span></span></a>
</header>
</div>
