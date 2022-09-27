<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';
get_header();
echo '<div class="centered-container cc-block-page404 section-m">';
echo '<div class="block-page404">';
echo '<h1 class="block-page404__title">' . __('404 Error', 'wpuprojectid') . '</h1>';
echo '<div class="block-page404__content">';
echo '<p>' . __('Sorry, but this page doesn&rsquo;t exists.', 'wpuprojectid') . '</p>';
echo '<p><a href="' . get_site_url() . '" class="wpuprojectid-button"><span>' . __('Back to the homepage', 'wpuprojectid') . '</span></a></p>';
echo '</div>';
echo '</div>';
echo '</div>';
get_footer();
