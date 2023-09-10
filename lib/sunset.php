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
\add_action('init', __NAMESPACE__ . '\shortcode_sunset_init');

/*
 *
 */

function shortcode_sunset_init() {
  /* ADICIONA ESTILO CSS */
  \wp_enqueue_style(\basename(__FILE__, '.php'), \get_template_directory_uri() . "/assets/css/." . basename(__FILE__, '.php') . ".css");

  /* ADICIONA O SHORTCODE | TAG ==> <label> */
  \add_shortcode('sunset', __NAMESPACE__ . '\shortcode_sunset');

  /* ADICIONA O SHORTCODE | TAG ==> <painel> */
  \add_shortcode('sunsetbar', __NAMESPACE__ . '\shortcode_sunset_bar');

  /* ADICIONA O SHORTCODE | TAG ==> <label> */
  \add_shortcode('sunset2in1', __NAMESPACE__ . '\shortcode_sunset_2in1_bar');

  /*
   * gutenberg block
   */
  \wp_enqueue_script(
          'sunset-js',
          \get_template_directory_uri() . '/assets/js/sunset.min.js',
          [],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/sunset.min.js')),
          true
  );
}

/*
 * CONTEUDO DE $content NAO PODE TER <P>
 * TAG ==> <LABEL>
 */

function shortcode_sunset($args = [], $content = '') {
  $params = '';
  $class = '';
  $day = false;

  if (is_array($args)) {
    foreach ($args as $key => $value) {
      if (trim(strtolower($key)) === 'class') {
        $class .= " $value";
      } else
        $params .= " $key='$value'";

      if (trim(strtolower($key)) === 'data-day') {
        $day = true;
      }
    }
  }
  $params .= $day ? '' : "data-day='5'"; /* SEXTA-FEIRA POR PADRAO, POR PADRAO */

  return "<label class='solidbar_box sunset_box'><i class='fas fa-sun'></i>"
          . "<div class='sunset_caption'>$content</div>"
          . "<div class='sunset$class' $params>"
          . "<div class='cssloading lds-ring'>"
          . "<div></div>"
          . "<div></div>"
          . "<div></div>"
          . "<div></div>"
          . "</div>"
          . "</div>"
          . "</label>";
}

/*
 * CONTEUDO DE $content NAO PODE TER <P>
 * TAG ==> <painel>
 * PARA USO EM SOLIDBAR
 */

function shortcode_sunset_bar($args = [], $content = '') {
  $params = '';
  $class = '';
  $caption = '';
  $day = false;

  if (is_array($args)) {
    foreach ($args as $key => $value) {
      if (trim(strtolower($key)) === 'class') {
        $class .= " $value";
      } else if (trim(strtolower($key)) === 'caption') {
        $caption .= $value;
      } else
        $params .= " $key='$value'";

      if (trim(strtolower($key)) === 'data-day') {
        $day = true;
      }
    }
  }
  $params .= $day ? '' : "data-day='5'"; /* SEXTA-FEIRA POR PADRAO, POR PADRAO */

  return "\n" . preg_replace('/\s*[\r\n]+\s*/is', '', <<<EOF
<painel class='sunset_box' subclass='sunset_caption' icon='fas fa-sun' caption="
  <div class='sunset_caption'>$caption</div>
  <div class='sunset$class' $params>
    <div class='cssloading lds-ring'>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>">
  $content
</painel>
EOF
  );
}

/*
 * CONTEUDO DE $content NAO PODE TER <P>
 * TAG ==> <painel>
 * PARA USO EM SOLIDBAR
 */

function shortcode_sunset_2in1_bar($args = [], $content = '') {
  $params = '';
  $class = '';
  $caption = '';
  $day = false;

  if (is_array($args)) {
    foreach ($args as $key => $value) {
      if (trim(strtolower($key)) === 'class') {
        $class .= " $value";
      } else if (trim(strtolower($key)) === 'caption') {
        $caption .= $value;
      } else if (trim(strtolower($key)) === 'data-day') {
        $day = (int) $value;
      } else
        $params .= " $key='$value'";
    }
  }

  $day = ($day !== false) ? $day : 5;
  $day2 = (($day + 1) > 6) ? 0 : ($day + 1);

  return "<label class='solidbar_box sunset_box'><i class='fas fa-sun'></i>"
          . "<div class='sunset_caption'>$caption</div>"
          . "<div class='sunset$class' $params data-day='$day'>"
          . "<div class='cssloading lds-ring'>"
          . "<div></div>"
          . "<div></div>"
          . "<div></div>"
          . "<div></div>"
          . "</div>"
          . "</div>"
          . " <span class='sunset_caption'>|</span><div class='sunset$class' $params data-day='$day2'>"
          . "<div class='cssloading lds-ring'>"
          . "<div></div>"
          . "<div></div>"
          . "<div></div>"
          . "<div></div>"
          . "</div>"
          . "</div>"
          . "</label>"
  ;
}
