<header class="header-main">
<?php
wputh_display_title();
wp_nav_menu( array(
    'depth' => 2,
    'theme_location' => 'main',
    'container_class' => 'main-menu__wrapper',
    'menu_class' => 'main-menu'
) );
echo wputh_get_social_links_html();
?>
</header>
