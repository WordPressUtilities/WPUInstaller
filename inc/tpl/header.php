<h1 class="logo"><a href="<?php echo home_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo.png'; ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" /></a></h1>
<?php
wp_nav_menu( array(
    'depth' => 2,
    'theme_location' => 'main',
    'menu_class' => 'main-menu'
) );
?>