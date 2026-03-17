<?php

/* ----------------------------------------------------------
  Vars
---------------------------------------------------------- */

$master_header_title = get_field('master_header_title', wpuacfflex_get_master_header_group());
if (!$master_header_title) {
    $master_header_title = wpuacfflex_master_header_get_fallback_title();
}
$master_header_title = apply_filters('master_header_title', $master_header_title);
$master_header_image = apply_filters('master_header_image', get_field('master_header_image', wpuacfflex_get_master_header_group()));
$master_header_type = apply_filters('master_header_type', get_field('master_header_header_type', wpuacfflex_get_master_header_group()));
$master_header_intro = apply_filters('master_header_intro', get_field('master_header_intro', wpuacfflex_get_master_header_group()));
$master_header_cta = apply_filters('master_header_cta', get_field('master_header_cta', wpuacfflex_get_master_header_group()));
$master_header_cta2 = apply_filters('master_header_cta2', get_field('master_header_cta2', wpuacfflex_get_master_header_group()));

/* Content */

$master_header_title_html = wpuprojectid_title($master_header_title, array(
    'classname' => 'master-header__title',
    'tag' => is_front_page() ? 'h2' : 'h1'
));

$master_header_intro_html = get_wpu_acf_text($master_header_intro, array(
    'classname' => 'master-header__content cssc-content'
));

$master_header_cta_html = '';
if ($master_header_cta || $master_header_cta2) {
    $master_header_cta_html .= '<div class="master-header__cta">';
    $master_header_cta_html .= get_wpu_acf_link($master_header_cta, 'wpuprojectid-button');
    $master_header_cta_html .= get_wpu_acf_link($master_header_cta2, 'wpuprojectid-button wpuprojectid-button--secondary');
    $master_header_cta_html .= '</div>';
}

$master_header_image_html = '';
if ($master_header_image) {
    $master_header_image_html .= '<picture class="master-header__image">';
    $master_header_image_html .= get_wpu_acf_image($master_header_image, 'big', array('loading' => 'eager'));
    $master_header_image_html .= '</picture>';
}

/* Extra attributes */

/* ----------------------------------------------------------
  Content
---------------------------------------------------------- */

echo '<div class="centered-container cc-master-header section" data-master-header-type="' . esc_attr($master_header_type) . '">';
echo '<div class="master-header">';
echo $master_header_title_html;
echo $master_header_intro_html;
echo $master_header_cta_html;
echo $master_header_image_html;
echo '</div>';
echo '</div>';
