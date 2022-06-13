<?php
$categories = get_the_category();
echo '<div class="loop loop-basic">';
echo '<div class="loop-basic__image">';
the_post_thumbnail('medium');
echo '</div>';
echo '<div class="loop-basic__metas">';
if (!empty($categories)) {
    echo '<a class="project-tag" href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
}
echo wputh_get_time_tag('d/m/Y', get_the_ID());
echo '</div>';
echo '<div class="loop-basic__content">';
echo '<a class="loop__link" href="' . get_the_permalink() . '">';
echo '<h3 class="loop-basic__name">' . get_the_title() . '</h3>';
echo '</a>';
echo '</div>';
echo '</div>';
