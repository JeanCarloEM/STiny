<?php

$next = \get_next_post();
$prev = \get_previous_post();

global $article_class, $first_post;

$related = get_posts(
        array(
            'category__in' => wp_get_post_categories($post->ID),
            'numberposts' => 6,
            'post__not_in' => array($post->ID)
        )
);

if ($related) {
  echo "<div class='colunas caixa'>";
  $first_post = false;
  $article_class = '';
  $apost = $post;

  foreach ($related as $rel) {
    $post = $rel;
    \setup_postdata($rel);
    \get_template_part('.artigo');
  }

  wp_reset_postdata();
}