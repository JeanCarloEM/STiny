<?php

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;
use jeancarloem\Wordpress\Admins\tools as tls;

/* ADICIONA O SHORTCODE */
\add_shortcode('section', __NAMESPACE__ . '\shortcode_section');
\add_shortcode('section_jump', __NAMESPACE__ . '\shortcode_section_jump');
\add_shortcode('section_clear', __NAMESPACE__ . '\shortcode_section_clear');

/*
 *
 */

function shortcode_section_jump($args = [], $content = '') {
  $style = "style='margin-top:0 !important; margin-bottom:0 !important; padding:0 !important; height:0!important;'";

  return \preg_replace('/\s*[\r\n]+\s/i', '', <<<EOF

<!-- START :: SECTION_JUMP SHORTCODE -->
<section $style><div class="wrapper colunabarra" $style></div></section>
<!-- END :: SECTION_JUMP SHORTCODE -->

EOF
  );
}

/*
 *
 */

function shortcode_section($args = [], $content = '') {
  $params = '';
  $class = '';
  $pre_coluna = '';
  $propswrapper = ["class" => ''];
  $style = '';

  if (is_array($args)) {
    foreach ($args as $key => $value) {
      $key = trim(strtolower($key));
      $value = jwpt\STinyTheme::tratarAspasUTF8(\html_entity_decode($value));

      if ($key === 'class') {
        $class .= " $value";
      } else if ($key === 'colunabarra') {
        $propswrapper['class'] .= " $value";
      } else if ($key === 'precoluna') {
        $pre_coluna = "<div class='wrapper colunabarra'>$value</div>";
      } else if ($key === 'colunabarraprops') {
        tls\Select::propsToVetor($value, $propswrapper);
        //$propswrapper .= " $value";
      } else if ($key === 'style') {
        $style .= " $value";
      } else if ($key === 'bg') {
        $style .= "background: $value;";
      } else if (strpos($key, 'coluna') === 0) {        
        $key = \trim(\preg_replace('/^coluna/is', '', $key));
        $propswrapper[$key] .= $value;
      } else {
        $params .= " $key='$value'";
      }
    }
  }

  $class = empty($class) ? '' : " class='$class'";
  $style = empty($style) ? '' : " style='$style'";

  $propswrapper_ = '';
  foreach ($propswrapper as $key => $value) {
    if (trim($key) !== 'class') {
      $propswrapper_ .= " $key='$value'";
    }
  }

  return \preg_replace('/\s*[\r\n]+\s/i', '', <<<EOF

<!-- START :: SECTION SHORTCODE -->
<section$class$params$style>

  $pre_coluna
  <div class="wrapper colunabarra {$propswrapper['class']}"$propswrapper_>
EOF
          .
          \shortcode_unautop(\do_shortcode(\shortcode_unautop($content)))
          . <<<EOF
  </div>

</section>
<!-- END :: SECTION SHORTCODE -->

EOF
  );
}


/*
 * 
 */
function shortcode_section_clear($args = [], $content = '') {
  $args = is_array($args) ? $args : [];
  $args['style'] = @$args['style'] . 'padding:0 !important;';
  $args['colunastyle'] = @$args['colunastyle'] . 'padding: 0 !important;';

  return shortcode_section($args, $content);
}