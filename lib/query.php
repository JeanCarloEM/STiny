<?php

/*
  Template Name: Top Slider with Text and Flag titles
 */

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;
use jeancarloem\Wordpress\Admins\tools as tls;

/*
 * ADICIONA O BLOCO GUTENBERG
 */
//\add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\gutenberg_block_query');
\add_action('init', __NAMESPACE__ . '\query_init');

/*
 *
 */

function query_init() {
  /* ADICIONA ESTILO CSS */
  \wp_enqueue_style(\basename(__FILE__, '.php'), \get_template_directory_uri() . "/assets/css/." . basename(__FILE__, '.php') . ".css");
  \wp_enqueue_style(hash('sha1', \get_template_directory_uri() . '/assets/css/blocks/jcem.min.css'), \get_template_directory_uri() . "/assets/css/blocks/jcem.admin.css");
  \wp_enqueue_style(\basename(__FILE__, '.php') . '-blockadim', \get_template_directory_uri() . "/assets/css/blocks/" . basename(__FILE__, '.php') . ".admin.css");

  /* ADICIONA O SHORTCODE */
  \add_shortcode('jcemquery', __NAMESPACE__ . '\shotcode_query');

  /*
   * gutenberg block
   */

  \wp_enqueue_script(
          hash('sha1', \get_template_directory_uri() . '/assets/js/blocks/functions.min.js'),
          \get_template_directory_uri() . '/assets/js/blocks/functions.min.js',
          ['wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/blocks/functions.min.js')),
          true
  );
  \wp_enqueue_script(
          'gutenberg-query-js',
          \get_template_directory_uri() . '/assets/js/blocks/query.min.js',
          ['wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/blocks/query.min.js')),
          true
  );


  \register_block_type('jcem/query', array(
      'editor_script' => 'gutenberg-query-js',
  ));
}

/*
 * CONTEUDO DE $content NAO PODE TER <P>
 */

function shotcode_query($args = null, $content = null) {
  $content = preg_replace('/<br(\*\/)?>/is', '', $content);
  $content = preg_replace('/<hr(\*\/)?>/is', '', $content);
  $content = preg_replace('/<(\/\s*)?p>/is', '', $content);
  $content = preg_replace('/\s*[\r\n]+\s*/is', '', $content);
  $content = trim($content);

  if (is_array($args)) {
    foreach ($args as $key => &$value) {
      $value = preg_replace('/<br(\*\/)?>/is', '', $value);
      $value = preg_replace('/<hr(\*\/)?>/is', '', $value);
      $value = preg_replace('/<(\/\s*)?p>/is', '', $value);
      $value = preg_replace('/\s*[\r\n]+\s*/is', '', $value);
    }
  }

  if (!\preg_match_all('/(<(?<tagname>\w+)(?<param>(\s*[^"\'=>]+(\s*=\s*("(?:[^"\\\\]|\\\\.)*"|\'(?:[^\'\\\\]|\\\\.)*\')))*)>(?<content>.*?)<\/\k<tagname>>\s*)*/is', $content, $mts)) {
    return '';
  }

  $content = $mts["content"][0];

  $params = [
      'posttype' => '',
      'categorias' => '',
      'tags' => '',
      'onlyexcerpt' => '',
      'postqtd' => '',
      'more' => '',
      'mode' => '',
      'prehtml' => '',
      'poshtml' => '',
      'parser' => '',
      'functionpass' => '',
      'filters' => ''
  ];

  $filters = [
      'found_posts' => ''
      , 'found_posts_query' => ''
      , 'post_limits' => ''
      , 'posts_clauses' => ''
      , 'posts_request' => ''
      , 'posts_distinct' => ''
      , 'posts_fields' => ''
      , 'posts_groupby' => ''
      , 'posts_join' => ''
      , 'posts_join_paged' => ''
      , 'posts_orderby' => ''
      , 'posts_request' => ''
      , 'posts_results' => ''
      , 'posts_search' => ''
      , 'posts_where' => ''
      , 'posts_where_paged' => ''
      , 'the_posts' => ''];


  /*
   * AQUI QUEBRAMOS A AS TAGS DE DENTRO PARA PEGAR SEUS VALORES
   */
  tls\Select::tagsContentToJson($content, $params);
  tls\Select::tagsContentToJson($params['filters'], $filters, true);
    
  $filters = base64_encode(\json_encode($filters));
  
  $params['functionpass'] = \json_decode(jcemquery_trataraspastextArea(html_entity_decode($params['functionpass'])), true);

  $retorno = '';

  $params['mode'] = ((empty($params['mode'])) || ($params['mode'] === 'forshortcode')) ? 'forshortcode' : $params['mode'];
  $shortcode = (!\array_key_exists('mode', $params) || (empty($params['mode'])) || ($params['mode'] === 'forshortcode'));

  $params['more'] = base64_encode(jcemquery_trataraspastextArea($params['more']));

  /*
   * EXECUTAMOS A QUERY
   */
  tls\select::uriquery("{$params['posttype']}/{$params['categorias']}/{$params['tags']}/{$params['onlyexcerpt']}/{$params['postqtd']}/{$params['more']}/$filters", function($item, $post_type, $categorias, $tags, $onlyExcerpt, $postQtd, $more_options, $filters) use (&$retorno, $params) {

    $retorno .= is_callable(__NAMESPACE__ . '\\jcemquery_mode_' . $params['mode']) ? (__NAMESPACE__ . '\\jcemquery_mode_' . $params['mode'])($item, $params, $post_type, $categorias, $tags, $onlyExcerpt, $postQtd, $more_options, $filters) : (is_callable($params['mode']) ? $params['mode']($item, $params, $post_type, $categorias, $tags, $onlyExcerpt, $postQtd, $more_options) : $item);
  }, !$shortcode, $shortcode);

  return jcemquery_trataraspastextArea(html_entity_decode($params['prehtml'] ?? '')) . ($retorno ?? $content) . jcemquery_trataraspastextArea(html_entity_decode($params['poshtml'] ?? ''));
}

/*
 *
 */

function jcemquery_get_primary_thumb_in_query(array &$item, &$functionpass): string {
  $functionpass = is_array($functionpass) ? $functionpass : [];
  if (((!\array_key_exists('attachments', $item)) || (empty($item["attachments"]))) && (!empty($functionpass)) && \array_key_exists('defaultthumb', $functionpass) && (!empty($functionpass['defaultthumb']))) {
    $functionpass["defaultthumb"] = is_array($functionpass['defaultthumb']) ? $functionpass['defaultthumb'] : [
        "url" => $functionpass['defaultthumb']
    ];
  }

  $defaultthumb = !array_key_exists('defaultthumb', $functionpass) ? '' : (is_string($functionpass["defaultthumb"]) ? $functionpass["defaultthumb"] : (!is_array($functionpass["defaultthumb"]) ? '' : ($functionpass["defaultthumb"][0]['url'] ?? $functionpass["defaultthumb"]['url']) ) );

  return (array_key_exists('attachments', $item) && !empty($item['attachments'])) ? (is_string($item['attachments']) ? $item['attachments'] : (\is_array($item['attachments']) ? $item['attachments'][0]['url'] : $defaultthumb)) : $defaultthumb;
}

/*
 *
 */

function jcemquery_trataraspastextArea($val) {
  return jwpt\STinyTheme::tratarAspasUTF8($val);
}

/*
 *
 */

function jcemquery_mode_parser($items, $params, $post_type, $categorias, $tags, $onlyExcerpt, $postQtd, $more_options, $filters) {
  $itm = '';

  if (\array_key_exists('parser', $params) && !empty($params['parser']) && is_array($items) && !empty($items)) {
    foreach ($items as $key => $item) {
      if (\is_array($item) && !empty($item)) {
        $itm .= \preg_replace_callback('/#\{\$(\w+)\}/is', function($mts) use ($item, $params, $post_type, $categorias, $tags, $onlyExcerpt, $postQtd, $more_options, $filters) {
          return (is_array($mts) && !empty($mts) && is_string($mts[1]) && \array_key_exists($mts[1], $item)) ? $item[$mts[1]] : $mts[0];
        }, $params['parser']);
      }
    }
  }


  return $itm;
}

/*
 *
 */

function jcemquery_mode_jcemslider($items, $params, $post_type, $categorias, $tags, $onlyExcerpt, $postQtd, $more_options, $filters, $args = '') {
  $inputs = '';
  $prev_next = '';
  $imgs = '';
  $miniaturas = '';

  /* NOME DO GRUPO DE INPUT RADIOS */
  $slider = 'jcemslider_' . strtolower(hash('sha1', \random_bytes(24)));

  foreach ($items as $key => &$value) {
    /* ID DO INPUT */
    $input_id = 'jcemslider_' . strtolower(hash('sha1', \random_bytes(24)));

    /*
     * INPUTS
     */
    $inputs .= "<input type='radio' id='$input_id' class='sldtxt' name='$slider' ndc='$key'" . ($key === 0 ? ' checked ' : '') . "/>";

    $img_url = jcemquery_get_primary_thumb_in_query($value, $params['functionpass']);

    /*
     * ARTIGOS / IMAGENS
     */
    $imgs .= <<<EOF
<a class='qdr' ndc='$key' href='{$value['url']}' target='_blank'>
  <div class='mgi' style='background-image: url("$img_url") !important;'>
    <img src='$img_url' />
  </div>
  <div class='cnt'>
    <div class='ttl'>{$value['title']}</div>
    <div class='text'>{$value['content_html']}</div>
  </div>
</a>
EOF;

    /*
     * BOTOES PREV E NEXT
     */
    $prev_next .= "<label for='$input_id' class='pvnt fas' ndc='$key'></label>";

    /*
     * SELETOR / MINIATURAS
     */
    $miniaturas .= <<<EOF
  <label for='$input_id' ndc='$key' style="background-image: url('$img_url');">
    <img src='$img_url' />
  </label>
EOF;
  }

  /* PROCESSANDO VARIAVEIS PASSADAS PARA OS DIVS */

  $div_class = '';
  $div_props = '';
  $mgs_class = '';
  $mgs_props = '';

  if (is_array($params['functionpass'])) {
    if (\array_key_exists('jcemslider', $params['functionpass'])) {
      if (\array_key_exists('class', $params['functionpass']['jcemslider'])) {
        $div_class = (\implode(' ', \array_keys(\array_flip(\explode(' ', $params['functionpass']['jcemslider']['class'])))));
        unset($params['functionpass']['jcemslider']['class']);
      }

      foreach ($params['functionpass']['jcemslider'] as $key => $value) {
        $div_props .= is_string($value) ? " $key='$value'" : '';
      }
    }

    if (\array_key_exists('mgs', $params['functionpass'])) {
      if (\array_key_exists('class', $params['functionpass']['mgs'])) {
        $mgs_class = (\implode(' ', \array_keys(\array_flip(\explode(' ', $params['functionpass']['mgs']['class'])))));
        unset($params['functionpass']['mgs']['class']);
      }

      foreach ($params['functionpass']['mgs'] as $key => $value) {
        $mgs_props .= is_string($value) ? " $key='$value'" : '';
      }
    }
  }


  $div_class = " class='jcemslider $div_class'";
  $mgs_class = " class='mgs $mgs_class'";

  return <<<EOF
<!-- START :: SLIDER -->
<div data-slider='$slider'$div_class$div_props>
  $inputs
  <div$mgs_class$mgs_props>
    $imgs
  </div>

  <nav class='pvnt'>
    $prev_next
  </nav>

  <nav class='seletor'>
    $miniaturas
  </nav>
</div>
<!-- END :: SLIDER -->
EOF;
}

/*
 *
 */

function jcemquery_mode_article($items, $params, $post_type, $categorias, $tags, $onlyExcerpt, $postQtd, $more_options, $filters, $args = '', $tag = 'a') {
  $inputs = '';
  $prev_next = '';
  $imgs = '';
  $miniaturas = '';

  /* NOME DO GRUPO DE INPUT RADIOS */
  $slider = 'jcemslider_' . strtolower(hash('sha1', \random_bytes(24)));

  $artigos = '';

  foreach ($items as $key => &$value) {
    $img_url = jcemquery_get_primary_thumb_in_query($value, $params['functionpass']);

    $tag_class = $tag === 'a' ? "class='article'" : '';

    $value['content_html'] = \strip_tags($value['content_html']);

    /*
     * ARTIGOS / IMAGENS
     */
    $artigos .= <<<EOF
<$tag $tag_class href='{$value['url']}' target='_blank'>
  <div class='thumb cover'><div class='img' style='background-image: url("$img_url") !important;'></div></div>
  <h1 class='title'>{$value['title']}</h1>
  <div class='content'>{$value['content_html']}</div>
</$tag>
EOF;
  }

  $div_class = '';
  $div_props = '';

  if ($params['functionpass']) {
    if (\array_key_exists('section', $params['functionpass'])) {
      if (\array_key_exists('class', $params['functionpass']['section'])) {
        $v = &$params['functionpass']['section']['class'];
        $div_class = \implode(' ', is_string($v) ? \array_keys(\array_flip(\explode(' ', $v))) : $v);
        unset($params['functionpass']['section']['class']);
      }

      foreach ($params['functionpass']['section'] as $key => $value) {
        $div_props .= is_string($value) ? " $key='$value'" : '';
      }
    }
  }

  return <<<EOF
<section class="articles_items $div_class"$div_props>
  $artigos
</section>
EOF;
}
