<?php

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;
use jeancarloem\MPTEditor as mpte;

global $first_post, $nothumb_article, $contador_articles, $article_class;
?>

<!-- ARTIGO INTEIRO -->
<article class="post<?php echo $article_class ? " $article_class" : '' ?>"><?php
  echo!(($first_post) && (\is_single() || (\is_page()))) ? "<a  class='nosimbol header' href='" . \get_the_permalink() . "' title='" . \get_the_title() . "'>" : '';
  ?>
  <header>
    <?php
    /* SOMENTE EXIBE COMO ALTERADO SE O POST FOI ALTERADO > 28 DIAS DEPOIS */
    if (get_the_modified_time('U') > (get_the_time('U') + (60 * 60 * 24 * 20))) {
      ?>
      <!-- FLAG ATUALIZADO -->
      <div class='flag_data update'>
        <?php echo "<span>" . get_the_modified_time('Y') . '</span><span>' . strtoupper(get_the_modified_time('M')) . '</span><span>' . get_the_modified_time('d') . '</span>';
        ?>
      </div>
    <?php } else { ?>
      <!-- FLAG CRIADO -->
      <div class='flag_data'>
        <?php echo "<span>" . get_the_time('Y') . '</span><span>' . strtoupper(get_the_time('M')) . '</span><span>' . get_the_time('d') . '</span>';
        ?>
      </div>
      <?php
    }
    ?>
    <!-- SLIDESHOW -->
    <?php        
    if (!$nothumb_article && (($first_post && jwpt\STinyTheme::imagemNormal()) || !$first_post)) {
      get_template_part('.slider.jcemslider');      
    } else {
      echo "&nbsp;";
    }
    ?>

    <!-- TITULO -->
    <h1 class="title">
      <div>
        <div class="post-meta">
          <?php if (comments_open()) : ?>
            <span class="comments-link">
              <?php comments_popup_link(__('Comentar', 'break'), __('1 Coment�rio', 'break'), __('% Comentários', 'break')); ?>
            </span>
          <?php endif; ?>
        </div>
        <?php
        echo \get_the_title();
        ?>
      </div>
    </h1>
  </header>
  <?php echo!(($first_post) && (\is_single() || (\is_page()))) ? '</a>' : ''; ?>

  <!-- CONTEUDO -->
  <div class="content">
    <?php
    if ($first_post) {
      echo '<!-- START :: WP CONTENT -->';
      echo jwpt\STinyTheme::get_wp_content();
      echo '<!-- START :: WP CONTENT -->';
      wp_link_pages();
    } else {
      echo '<!-- START :: WP EXCERPT -->';
      echo \get_the_excerpt() ?? \get_post_field('post_excerpt', $post->ID);
      echo '<!-- START :: WP EXCERPT -->';
      echo '<div class="article_more"><a  class=\'nosimbol\' href="' . get_permalink() . '"><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i></a></div>';
    }
    ?>
  </div>

  <?php
  if (($first_post) || ($contador_articles === 0)) {
    $publicado = get_the_time("d/M/Y");
    $modificado = get_the_modified_time('d/M/Y');

    # EXIBINDO MODIFICAÇÃO
    echo "<div class='edicao'>Originalmente publicado em <b>$publicado</b>." . (($publicado !== $modificado) ? " Atualizado em <b>$modificado</b>." : '') . "</div>";


    if (!STinyTheme::getPostTypeFieldContent('autor') && (!in_array(\get_post_type($post->ID), ['attachment', 'revision', 'nav_menu_item']))) {
      \get_template_part('.autor', 'index');
    }
    if (!STinyTheme::getPostTypeFieldContent('metas') && (!in_array(\get_post_type($post->ID), ['attachment', 'revision', 'nav_menu_item']))) {
      ?>
      <nav class="meta clearfix">
        <div class="category"><h6>Assuntos</h6><?php echo get_the_category_list(); ?></div>
        <div class="tags"><h6>Ingredientes</h6><?php echo get_the_tag_list('', ''); ?></div>
        <?php
        $field = (get_option(STinyTheme::PREFIX . 'advertencia'));
        if ($field) {
          echo "<div class='notes'><h6>Avisos:</h6><p class='align_justify'>$field</p></div>";
        }
        ?>
      </nav>
      <?php
    }
  }
  ?>
</article>
