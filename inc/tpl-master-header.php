<?php

/* ----------------------------------------------------------
  Vars
---------------------------------------------------------- */

$master_header_title = get_field('master_header_title');
if (!$master_header_title) {
    $master_header_title = get_the_title();
}
$master_header_image = get_field('master_header_image');
$master_header_type = get_field('master_header_header_type');
$master_header_intro = get_field('master_header_header_intro');

/* Extra attributes */

/* ----------------------------------------------------------
  Content
---------------------------------------------------------- */

/* Wrapper
-------------------------- */

echo '<div class="centered-container cc-master-header section" data-master-header-type="' . esc_attr($master_header_type) . '">';
echo '<div class="master-header">';

/* Main title
-------------------------- */

echo '<h1 class="master-header__title">' . $master_header_title . '</h1>';

/* Intro
-------------------------- */

if ($master_header_intro) {
    echo '<div class="master-header__content">';
    echo wpautop($master_header_intro);
    echo '</div>';
}

/* Image
-------------------------- */

if ($master_header_image) {
    echo '<picture class="master-header__image">';
    echo get_wpu_acf_image($master_header_image, 'big', array('loading' => 'eager'));
    echo '</picture>';
}

/* End of wrapper
-------------------------- */

echo '</div>';
echo '</div>';
