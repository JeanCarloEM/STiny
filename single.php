<?php

# ONTEM O CABECALHO
require_once "header.php";

global $article_class;

if (have_posts()) {

  global $first_post, $post;

  echo "<section class='artigos'>";

  the_post();

  $first_post = true;
  $article_class = '';

  get_template_part('.artigo');
  $first_post = false;

  get_template_part('.sequentialposts');
  get_template_part('.relatedposts');

  echo "</section>";
}

# INCLUI O RODAPE
require_once "footer.php";
