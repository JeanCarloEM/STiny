<?php

/*
  Template Name: Search Page
 */

# ONTEM O CABECALHO
require_once "header.php";

if (have_posts()) {

  global $first_post, $contador_articles, $nothumb_article, $article_class;

  echo "<section class='artigos'>";

  $first_post = false;
  $contador_articles = 0;
  $nothumb_article = false;
  $article_class = '';

  while (have_posts()) {
    the_post();
    $contador_articles++;

    if ($contador_articles === 5) {
      echo "<div class='colunas'>";
    }

    $nothumb_article = ($contador_articles >= 16) ?? true;

    if ($first_post) {
      get_template_part('.artigo');
      $first_post = false;
    } else {
      get_template_part('.artigo');
    }
  }

  if ($contador_articles >= 5) {
    echo "</div>";
  }

  echo "</section>";
}

# INCLUI O RODAPE
require_once "footer.php";
