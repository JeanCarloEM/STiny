<?php

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;
use jeancarloem\MPTEditor as mpte;

$next = $prev = null;

if (!empty(jwpt\STinyTheme::getSeriePosts())) {
  $next = jwpt\STinyTheme::getNextPostOfSerieFromPost();
  $prev = jwpt\STinyTheme::getPrevPostOfSerieFromPost();
}

if (empty($next) || empty($prev)) {
  $next = \get_next_post();
  $prev = \get_previous_post();
}

global $article_class, $first_post;

if ($next || $prev) {
  $first_post = false;
  $apost = $post;

  echo "<div class='colunas sequencia_interaction'>";

  if (!empty($prev)) {
    $post = $prev;
    \setup_postdata($post);
    $article_class = 'sequencia_interaction prev';
    \get_template_part('.artigo');
  } else {
    echo "<article class='force_second_collums'><br /><br /></article>";
  }

  if (!empty($next)) {
    $post = $next;
    \setup_postdata($next);
    $article_class = 'sequencia_interaction next';
    \get_template_part('.artigo');
  }

  $article_class = '';
  $post = $apost;
  \setup_postdata($apost);

  echo "</div>";
}
