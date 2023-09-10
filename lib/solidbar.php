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
\add_action('init', __NAMESPACE__ . '\shortcode_solidbar_horizontal_init');

/*
 *
 */

function shortcode_solidbar_horizontal_init() {
  /* ADICIONA ESTILO CSS */
  \wp_enqueue_style(\basename(__FILE__, '.php'), \get_template_directory_uri() . "/assets/css/." . basename(__FILE__, '.php') . ".css");

  /* ADICIONA O SHORTCODE */
  \add_shortcode('solidbarh', __NAMESPACE__ . '\shortcode_solidbar_horizontal_model');

  /* ITEM */
  \add_shortcode('solidbarh_item', __NAMESPACE__ . '\shortcode_solidbar_horizontal_item');
}

/*
 * CONTEUDO DE $content NAO PODE TER <P>
 */

function shortcode_solidbar_horizontal_model($args = [], $content = null) {
  $codereplace = function(string $content, string $code, string $value) {
    $code = "<!-- END :: $code -->";
    return preg_replace(
            "/\s*$code\s*/is",
            "$value\n\t$code\n",
            $content
    );
  };

  $container_class = '';
  $container_props = '';
  shortcode_sldtxt_props_build(is_array($args) && array_key_exists('container', $args) ? $args['container'] : [], $container_class, $container_props);

  $box_class = '';
  $box_props = '';
  shortcode_sldtxt_props_build(is_array($args) && array_key_exists('box', $args) ? $args['box'] : [], $box_class, $box_props);

  $ct_class = '';
  $ct_props = '';
  shortcode_sldtxt_props_build(is_array($args) && array_key_exists('ct', $args) ? $args['box'] : [], $ct_class, $ct_props);

  $name = "x" . hash('sha1', \random_bytes(12));

  $conteudo = <<<EOF

<!-- START :: SOLIDBAR -->

<div data-name='$name' data-keys='' class='solidbar_container$container_class'$container_props>
  <!-- START :: INPUTS -->
  <!-- END :: INPUTS -->

  <div class='solidbar_bar$container_class'$container_props>
    <!-- START :: LABELS -->
    <!-- END :: LABELS -->
  </div>

  <!-- START :: PAINELS -->
  <!-- END :: PAINELS -->
</div>

<!-- END :: SOLIDBAR -->

EOF;

  $content = \preg_replace_callback('/<(?<tagname>painel)\s*(?<param>(\s*[^"\'=>]+(\s*=\s*("(?:[^"\\\\]|\\\\.)*"|\'(?:[^\'\\\\]|\\\\.)*\'))?)*)>(?<content>.*?)<\/\k<tagname>>\s*/si', function($mt) use(&$conteudo) {
    $class = '';
    $props = '';
    $icon = '';
    $subclass = '';
    $caption = '';

    if (\array_key_exists('param', $mt) && !empty($mt['param'])) {
      \preg_replace_callback('/(?<prop>\w+)\s*=\s*(?<value>("(?:[^"\\\\]|\\\\.)*"|\'(?:[^\'\\\\]|\\\\.)*\'))/si', function($mt2) use (&$class, &$props, &$icon, &$subclass, &$caption) {
        $mt2['value'] = substr(trim(preg_replace('/s*[\r\n]+\s*/is', '', $mt2['value'])), 1, -1);

        switch (\strtolower(\trim($mt2['prop']))) {
          case 'class':
            $class .= " {$mt2['value']}";
            break;

          case 'icon':
            $icon .= " {$mt2['value']}";
            break;

          case 'subclass':
            $subclass .= " {$mt2['value']}";
            break;

          case 'caption':
            $caption .= " {$mt2['value']}";
            break;

          default:
            $props .= " " . \strtolower(\trim($mt2['prop'])) . "'{$mt2['value']}'";
            break;
        }
      }, $mt['param']);
    }

    shortcode_solidbar_callableAdd($conteudo, $class, $props, $icon, $subclass, $caption, $mt['content'], $name);
    return '';
  }, \shortcode_unautop(\do_shortcode(\shortcode_unautop($content))));

  return $codereplace($conteudo, 'LABELS', $content);
}

/*
 *
 */

function shortcode_solidbar_horizontal_item_label(&$for, $key, string $class, string $props, string $icon, string $subclass, string $content): string {
  $for = ($for === null) ? null : empty($for) ? "x" . hash('sha1', \random_bytes(12)) : $for;
  $fortag = empty($for) ? '' : " for='$for'";

  return <<<EOF

<label$fortag ndc='$key' class='solidbar_box $class' $props>
  <i class='solidbar_icon $icon'></i>

  <div class='content $subclass'>
    $content
  </div>
</label>

EOF;
}

/*
 *
 */

function shortcode_solidbar_horizontal_item($args = [], $content = null) {
  $params = '';
  $class = '';
  $subclass = '';
  $caption = '';
  $icon = '';
  $day = false;

  if (is_array($args)) {
    foreach ($args as $key => $value) {
      if (trim(strtolower($key)) === 'class') {
        $class .= " $value";
      } else if (trim(strtolower($key)) === 'caption') {
        $caption .= $value;
      } else if (trim(strtolower($key)) === 'subclass') {
        $subclass .= $value;
      } else if (trim(strtolower($key)) === 'icon') {
        $icon .= $value;
      } else
        $params .= " $key='$value'";

      if (trim(strtolower($key)) === 'data-day') {
        $day = true;
      }
    }
  }
  $params .= $day ? '' : "data-day='5'"; /* SEXTA-FEIRA POR PADRAO, POR PADRAO */

  return "\n" . preg_replace('/\s*[\r\n]+\s*/is', '', <<<EOF
<painel class='$class' subclass='$subclass' icon='$icon' caption="$caption">
  $content
</painel>
EOF
  );
}

/*
 *
 */

function shortcode_solidbar_callableAdd(string &$conteudo, string $class, string $props, string $icon, string $subclass, string $caption, string $content, string $name = null) {
  $codereplace = function(string $content, string $code, string $value) {
    $code = "<!-- END :: $code -->";
    return preg_replace(
            "/\s*$code\s*/is",
            "$value\n\t$code\n",
            $content
    );
  };

  $for = (!empty($content)) ? "i" . hash('sha1', \random_bytes(12)) : null;

  if (empty($conteudo)) {
    $conteudo = shortcode_solidbar_horizontal_model();
  }

  if (!empty($content)) {
    if (empty($name)) {
      \preg_match('/\s*data\-name\s*\=\s*(?<aspas>\'|")\s*([\w]+)\s*\k<aspas>/si', $conteudo, $mts);
      $name = \trim($mts[2]);
    }

    $conteudo = \preg_replace_callback('/data-keys\s*=\s*(?<aspas>\'|")\s*([\w]*)\s*\k<aspas>/si', function($mt) use(&$key) {
      $key = (\is_numeric($mt[2]) ? $mt[2] : -1) + 1;
      return "data-keys='$key'";
    }, $conteudo);

    $conteudo = $codereplace(
            $codereplace(
                    $codereplace(
                            $conteudo,
                            "PAINELS",
                            "<div class='solidbar_painel' ndc='$key'>$content</div>"
                    ),
                    "LABELS",
                    shortcode_solidbar_horizontal_item_label($for, $key, $class, $props, $icon, $subclass, $caption)
            ),
            "INPUTS",
            "<input type='radio' id='" . $for . "' class='sldtxt' name='solidbar_$name' ndc='$key'" . (($key === 0) ? ' checked' : '') . " />"
    );
  } else {
    $conteudo = $codereplace(
            $conteudo,
            "LABELS",
            shortcode_solidbar_horizontal_item_label($for, '', $class, $props, $icon, $subclass, $caption)
    );
  }
}
