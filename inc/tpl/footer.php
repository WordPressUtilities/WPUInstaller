<?php

/* ----------------------------------------------------------
  Footer
---------------------------------------------------------- */

echo '<div class="centered-container cc-footer section"><footer class="footer">';

/* Logo */
echo '<div class="footer-logo"><img src="' . get_header_image() . '" alt="' . esc_attr(get_bloginfo('name')) . '" loading="lazy" /></div>';

/* Menu */
wp_nav_menu(array(
    'depth' => 2,
    'theme_location' => 'footer',
    'fallback_cb' => 'wputh_default_menu',
    'container' => 'div',
    'container_class' => 'footer-menu__wrapper',
    'menu_class' => 'footer-menu'
));

/* Social links */
echo wputh_get_social_links_html('footer__social', 'icon');

/* Lang switcher */
echo wputh_maincontent_languages();


echo '</footer></div>';

/* ----------------------------------------------------------
  Copyright
---------------------------------------------------------- */

$footer_copyright = '&copy; ' . date('Y') . ' - ' . get_bloginfo('name');
$copy_items = wputh_get_menu_items('copy');

echo '<div class="centered-container cc-copyright section"><div class="copyright">';
echo '<div class="footer-copyright">' . $footer_copyright . '</div>';
if ($copy_items):
    echo '<div class="footer-links">';
    echo '<ul><li>' . implode('</li><li>', $copy_items) . '</li></ul>';
    echo '</div>';
endif;
echo '</div></div>';
