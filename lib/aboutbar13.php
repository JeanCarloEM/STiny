<?php

/*
  Template Name: Top Slider with Text and Flag titles
 */

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;

/*
 * ADICIONA O BLOCO GUTENBERG
 */
//\add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\gutenberg_block_aboutbar13');
\add_action('init', __NAMESPACE__ . '\aboutbar13_init');

/*
 *
 */

function aboutbar13_init() {
  /* ADICIONA ESTILO CSS */
  \wp_enqueue_style(\basename(__FILE__, '.php'), \get_template_directory_uri() . "/assets/css/." . basename(__FILE__, '.php') . ".css");
  \wp_enqueue_style(\basename(__FILE__, '.php') . '-blockadim', \get_template_directory_uri() . "/assets/css/blocks/" . basename(__FILE__, '.php') . ".admin.css");

  /* ADICIONA O SHORTCODE */
  \add_shortcode('aboutbar13', __NAMESPACE__ . '\shotcode_aboutbar13');
  \add_shortcode('aboutbar13master', __NAMESPACE__ . '\shotcode_aboutbar13_master');
  \add_shortcode('aboutbar13col', __NAMESPACE__ . '\shotcode_aboutbar13_col');

  /*
   * gutenberg block
   */

  \wp_enqueue_script(
          hash('sha1', \get_template_directory_uri() . '/assets/js/blocks/functions.js'),
          \get_template_directory_uri() . '/assets/js/blocks/functions.js',
          ['wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/blocks/functions.js')),
          true
  );
  \wp_enqueue_script(
          'gutenberg-aboutbar13-js',
          \get_template_directory_uri() . '/assets/js/blocks/aboutbar13.js',
          ['wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/blocks/aboutbar13.js')),
          true
  );


  \register_block_type('gutenberg-aboutbar13/AboutBar1-3', array(
      'editor_script' => 'gutenberg-aboutbar13-js',
  ));
}

/*
 * CONTEUDO DE $content NAO PODE TER <P>
 */

function shotcode_aboutbar13($args = null, $content = null) {
  if (empty($content)) {
    $p = \do_shortcode(jwpt\STinyTheme::getVar("about_13_1"));
    $c1 = jwpt\STinyTheme::getVar("about_13_2");
    $c2 = jwpt\STinyTheme::getVar("about_13_3");
    $c3 = jwpt\STinyTheme::getVar("about_13_4");
  } else {
    /* REMOVER A PORCARIA DOS <P> E </P> QUE O WORDPRESS INSISTE EM ADICIONAR
     * A FUNCAO 'shortcode_unautop' NAO FUNCIONA
     */
    $content = \do_shortcode(preg_replace('/(<p>|<\/p>|[\r\n]+)/is', '', $content));

    /* EXPLODINDO O CONTEUDO */
    \preg_match_all('/(?<blocos>\s*<(?<tagname>(article|a)+)(?<param>(\s*[^"\'=>]+(\s*=\s*("(?:[^"\\\\]|\\\\.)*"|\'(?:[^\'\\\\]|\\\\.)*\'))?)*)>(?<content>.*?)<\/\k<tagname>>\s*)/is', $content, $mts, PREG_OFFSET_CAPTURE);

    if ($mts) {
      $p = (@$mts['blocos'][0][0]);
      $c1 = (@$mts['blocos'][1][0]);
      $c2 = (@$mts['blocos'][2][0]);
      $c3 = (@$mts['blocos'][3][0]);
    }
  }

  if ((@$p) && ((empty($content)) || (jwpt\STinyTheme::getVar("about_13_show")))) {
    return preg_replace("|\s*[\r\n]\s*|is", '', <<<EOF
    <div class="about-bar">
      <div class="columns3">
        {$c1}
        {$c2}
        {$c3}
      </div>
      <div class="about">
        {$p}
      </div>
    </div>
EOF
    );
  }
}

/*
 *
 */

function shotcode_aboutbar13_master($args = null, $content = null) {
  $args = is_array($args) ? $args : [];

  $args['more'] = @$args['more'] ?? "Saiba Mais";
  $args['title'] = @$args['title'] ?? "Sem Título";
  $args['link'] = @$args['link'] ?? "#";
  $args['ico'] = @$args['ico'] ?? "";

  $content = \do_shortcode(\preg_replace('/(<p>|<\/p>|\s*[\r\n]+\s*)/is', '', $content));
  return preg_replace("|\s*[\r\n]+[\s]*|is", '', <<<EOF
  <article>
    <i class="{$args['ico']}"></i>
    <h3 class="ico-about">{$args['title']}</h3>
    <div class="content">{$content}</div>
    <a target="_blank" href="{$args['link']}" class="more">{$args['more']}</a>
  </article>
EOF
  );
}

/*
 *
 */

function shotcode_aboutbar13_col($args = null, $content = null) {
  $args = is_array($args) ? $args : [];

  $args['title'] = @$args['title'] ?? "Sem Título";
  $args['ico'] = @$args['ico'] ?? "";

  $content = \do_shortcode(\preg_replace('/(<p>|<\/p>|[\r\n])/is', '', $content));

  return preg_replace("|\s*[\r\n]+[\s]*|is", '', <<<EOF
  <a target="_blank" href="http://www.adventistas.org/pt/institucional/missao-e-servico/saude/" id="vitality" class="callout">
    <i class="{$args['ico']}"></i>
    <h4>{$args['title']}</h4>
    <span>{$content}</span>
  </a>
EOF
  );
}
