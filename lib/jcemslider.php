<?php

namespace jeancarloem\Wordpress\Temas\STiny;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . '.functions.php';

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;

\add_action('init', __NAMESPACE__ . '\jcemslider_init');

function jcemslider_init() {
  \wp_enqueue_style(\basename(__FILE__, '.php') . '-css', \get_template_directory_uri() . "/assets/css/." . basename(__FILE__, '.php') . ".css");

  \wp_enqueue_script(
          hash('sha1', \get_template_directory_uri() . '/assets/js/blocks/functions.js'),
          \get_template_directory_uri() . '/assets/js/blocks/functions.js',
          ['wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/blocks/functions.min.js')),
          true
  );

  \wp_enqueue_script(
          \basename(__FILE__, '.php') . '-js',
          \get_template_directory_uri() . "/assets/js/" . basename(__FILE__, '.php') . ".min.js",
          ['wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/blocks/' . basename(__FILE__, '.php') . '.min.js')),
          true
  );

  \wp_enqueue_script(
          'gutenberg-jcemslider-editor-js',
          \get_template_directory_uri() . '/assets/js/blocks/jcemslider.min.js',
          ['wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/blocks/jcemslider.min.js')),
          true
  );

  /* ADICIONA O SHORTCODE */
  \add_shortcode('jcemslider', __NAMESPACE__ . '\shortcode_sldtxt');
  \add_shortcode('jcemslider_image', __NAMESPACE__ . '\shortcode_sldtxt_mgi');

  \register_block_type('jcemslider/jcemslider', array(
      'editor_script' => 'gutenberg-jcemslider-editor-js',
  ));
}

/*
 *
 */

function shortcode_sldtxt_props_build($args, &$class, &$props) {
  if (is_string($args) && (!empty($args))) {
    try {
      $args = \json_decode($args, true);
    } catch (\Exception $e) {

    }
  }

  if (is_array($args)) {
    foreach ($args as $key => $value) {
      if (trim(strtolower($key)) === 'class') {
        $class .= " $value";
      } else {
        $props .= " $key='$value'";
      }
    }
  }
}

/*
 *
 */

function shortcode_sldtxt_mgi_build($count, $url, $ttl, $text, $link = '', $tag = 'a', $itemprops = []) {
  //$tag = (!empty($link) && filter_var($link, FILTER_VALIDATE_URL)) ? 'a' : 'div';
  $link = (!empty($link) && filter_var($link, FILTER_VALIDATE_URL)) ? " href='$link'" : '';

  $divtext_props = '';
  $divtext_class = '';
  $divcnt_props = '';
  $divcnt_class = '';
  $title_class = '';
  $title_props = '';
  $div_class = '';
  $div_props = '';

  if (is_array($itemprops) && !empty($itemprops)) {
    if (array_key_exists('title', $itemprops)) {
      shortcode_sldtxt_props_build(@$itemprops['title'] ?? [], $title_class, $title_props);
    }

    if (array_key_exists('text', $itemprops)) {
      shortcode_sldtxt_props_build(@$itemprops['text'] ?? [], $divtext_class, $divtext_props);
    }

    if (array_key_exists('cnt', $itemprops)) {
      shortcode_sldtxt_props_build(@$itemprops['cnt'] ?? [], $divcnt_class, $divcnt_props);
    }

    if (array_key_exists('mgi', $itemprops)) {
      shortcode_sldtxt_props_build(@$itemprops['div'] ?? [], $div_class, $div_props);
    }
  }

  return trim(preg_replace('|\s*[\r\n]+\s*|is', '', <<<EOF
<$tag class='qdr$div_class' ndc='$count'$link$div_props>
  <div class='mgi' style='background-image: url("$url") !important;'>
    <img src='$url' />
  </div>
  <div class='cnt$divcnt_class'$divcnt_props>
    <h3 class='ttl$title_class'$title_props>$ttl</h3>
    <div class='text$divtext_class'$divtext_props>$text</div>
  </div>
</$tag>
EOF
  ));
}

/*
 *
 */

function shortcode_sldtxt_url_toid($url) {
  return "sldtxt_" . hash('sha1', $url);
}

/*
 *
 */

function shortcode_sldtxt_mgi($args = null, $content = null): string {
  if ((!array_key_exists('url', $args)) || (empty($args['url']))) {
    return "";
  }

  if ((!array_key_exists('count', $args)) || (empty($args['url']))) {
    return "\n<br />PROPRIEDADE 'count' INVALIDA</br >\n";
  }

  if ((!filter_var($args['url'], FILTER_VALIDATE_URL)) || (@base64_encode(@\base64_decode($data))) === $data) {
    return "\n<br />URL INVALIDA</br >\n";
  }

  $args = is_array($args) ? $args : [];
  $args['title'] = @$args['title'] ?? "";

  return shortcode_sldtxt_mgi_build($args['count'], $args['url'], $args['title'], $content, @$args['link'] ?? '');
}

/*
 *
 */

function shortcode_sldtx_htmlmodel(string $slider = '', $cls = '', $size = 0, int $js = 0, string $bg = '', $props = []): string {
  $div_class = '';
  $div_props = '';
  shortcode_sldtxt_props_build(is_array($props) && array_key_exists('jcemslider', $props) ? $props['jcemslider'] : [], $div_class, $div_props);

  $mgs_class = '';
  $mgs_props = '';
  shortcode_sldtxt_props_build(is_array($props) && array_key_exists('mgs', $props) ? $props['mgs'] : [], $mgs_class, $mgs_props);

  $seletor_class = '';
  $seletor_props = '';
  shortcode_sldtxt_props_build(is_array($props) && array_key_exists('seletor', $props) ? $props['seletor'] : [], $seletor_class, $seletor_props);

  $pvnt_class = '';
  $pvnt_props = '';
  shortcode_sldtxt_props_build(is_array($props) && array_key_exists('pvnt', $props) ? $props['pvnt'] : [], $pvnt_class, $pvnt_props);

  $cls = \is_string($cls) ? $cls : implode(' ', $cls);

  $props = '';
  $imgsize = '';
  if (((strpos($cls, 'rightnav') !== false) || (strpos($cls, 'leftnav') !== false))) {
    $props .= " style='height: $size !important;" . ($bg ? "background: $bg !important;" : "") . "' height";
  } else {
    $props .= ($bg ? "style='background: $bg !important;'" : "");
    $imgsize = ($size > 0) ? "style='height: $size !important;'" : '';
  }

  if ($size > 1) {
    $props .= " height";
  }

  if (empty($slider)) {
    $slider = preg_replace('/[^\w]/i', '', \base64_encode(\random_bytes(16)));
  }

  $props .= (!$js ? '' : " js='$js'") . $div_props;

  return <<<EOF

<!-- START :: SLIDER -->

<div data-slider='$slider' class='jcemslider $cls$div_class' data-keys='$key'$props>

  <!-- START :: INPUTS -->
  <!-- END :: INPUTS -->

  <!-- START :: MGS -->
  <div class='mgs$mgs_class'$mgs_props$imgsize>
    <!-- START :: MGI -->
    <!-- END :: MGI -->
  </div>
  <!-- END :: MGS -->

  <!-- START :: NAV PVNT -->
  <nav class='pvnt$pvnt_class'$pvnt_props>
    <!-- START :: PVNT -->
    <!-- END :: PVNT -->
  </nav>
  <!-- END :: NAV PVNT -->

  <!-- START :: NAV SELETOR -->
  <nav class='seletor$seletor_class'$seletor_props>
    <!-- START :: SELETOR -->
    <!-- END :: SELETOR -->
  </nav>
  <!-- END :: NAV SELETOR -->

</div>

<!-- END :: SLIDER -->

EOF;
}

/*
 *
 */

function shortcode_sldtx_callableAdd(string &$conteudo, $item_img_url, $item_link = '', $item_title = '', $item_content = '', $slider = '', $itemtag = 'a', $itemprops = []) {

  $codereplace = function(string $content, string $code, string $value) {
    $code = "<!-- END :: $code -->";
    return preg_replace(
            "/\s*$code\s*/is",
            "$value\n\t$code\n",
            $content
    );
  };

  $id = shortcode_sldtxt_url_toid(empty($item_img_url) ? hash('sha1', \random_bytes(8)) : hash('sha1', $item_img_url . \random_bytes(3)));

  $key = null;

  if (empty($conteudo)) {
    $key = 0;

    if (empty($slider)) {
      $slider = preg_replace('/[^\w]/i', '', \base64_encode(\random_bytes(16)));
    }

    $conteudo = shortcode_sldtx_htmlmodel();
  }

  if (empty($slider)) {
    \preg_match('/\s*data\-slider\s*\=\s*(?<aspas>\'|")\s*([\w]+)\s*\k<aspas>/si', $conteudo, $mts);
    $slider = \trim($mts[2]);
  }

  if (empty($key) && ($key !== 0)) {
    $conteudo = \preg_replace_callback('/data-keys\s*=\s*(?<aspas>\'|")\s*([\w]*)\s*\k<aspas>/si', function($mt) use(&$key) {
      $key = (\is_numeric($mt[2]) ? $mt[2] : -1) + 1;
      return "data-keys='$key'";
    }, $conteudo);
  }

  $inButton = (is_array($itemprops) && array_key_exists('linkmode', $itemprops) && ($itemprops['linkmode'] === 'button'));
  $inTitle = (is_array($itemprops) && array_key_exists('linkmode', $itemprops) && ($itemprops['linkmode'] === 'title'));

  if (($itemtag === 'a') && ($inButton || $inTitle)) {
    $itemtag = 'div';
  }

  $item_link = empty($item_link) ? '' : " href='$item_link'";

  $item_title = $inTitle ? "<a$item_link target='blank'>$item_title</a>" : $item_title;
  $item_content .= ($inButton && !empty($item_link)) ? "<br /><a class='linkbutton'$item_link target='blank'>" . (is_array($itemprops) && (array_key_exists('gotext', $itemprops)) ? $itemprops['gotext'] : 'Saiba mais...') . "</a>" : '';


  $conteudo = $codereplace(
          $codereplace(
                  $codereplace(
                          $codereplace(
                                  $conteudo,
                                  "SELETOR",
                                  "<label for='" . $id . "' ndc='$key' style=\"background-image: url('$item_img_url');\"><img src='$item_img_url' /></label>"
                          ),
                          "PVNT",
                          "<label for='" . $id . "' class='pvnt fas' ndc='$key'></label>"
                  ),
                  "MGI",
                  shortcode_sldtxt_mgi_build($key, $item_img_url, $item_title, $item_content, $item_link, $itemtag, $itemprops)
          ),
          "INPUTS",
          "<input type='radio' id='" . $id . "' class='sldtxt' name='sldtxt_$slider' ndc='$key'" . (($key === 0) ? ' checked' : '') . " />"
  );
}

/*
 *
 */

function shortcode_sldtxt($args = null, $content = null) {
  $args = empty($args) ? [] : $args;

  if ((!array_key_exists('slider', $args)) || (empty($args['slider']))) {
    $args['slider'] = preg_replace('/[^\w]/i', '', \base64_encode(\random_bytes(16)));
  }

  $contador = -1;
  $urls = [];
  $shortcode = (\strpos($content, "url=") !== false);

  $content = \preg_replace_callback('/url=(\'|")([^\'"]+)(\'|")/is',
          function($m) use (&$contador, &$urls) {
    if (!in_array($m[2], $urls)) {
      $urls[] = $m[2];
      $contador++;
      return $m[0] . " count='$contador'";
    }

    return '';
  }, $content);

  if ($shortcode) {
    $content = \do_shortcode($content);
  }

  $content = preg_replace('/\s*[\r\n]+\s*/is', '', $content);
  $content = preg_replace('/\s*<p>\s*(<br( ?\/)?>)?\s*<a \s*/is', '<a ', $content);
  $content = preg_replace('/\s*\/a>\s*(<br( ?\/)?>)?\s*<\/p>\s*/is', '/a>', $content);

  $style = "style='" . (@$args['bg'] ? " background: {$args['bg']};" : '') . "'";

  $_args = '';
  if (array_key_exists('js', $args)) {
    $_args .= ' ' . (empty($args['js']) ? 'js' : " js='{$args['js']}'");
  }

  $r = "<div$_args data-slider='{$args['slider']}'" . (@$args['height'] ? ' height' : '') . " class='jcemslider" . (@$args['mode'] ? " {$args['mode']}" : '') . "'$style>";

  foreach ($urls as $key => $value) {
    $r .= "<input type='radio' id='" . shortcode_sldtxt_url_toid($value) . "' class='sldtxt' name='sldtxt_{$args['slider']}' ndc='$key'" . (($key === 0) ? ' checked' : '') . " />";
  }

  $r .= "<div class='mgs'" . (@$args['height'] ? " style='height:{$args['height']} !important;'" : '') . ">" . <<<EOF
      $content
    </div>
    <nav class='pvnt'>
EOF;

  foreach ($urls as $key => $value) {
    $r .= "<label for='" . shortcode_sldtxt_url_toid($value) . "' class='pvnt fas' ndc='$key'></label>";
  }

  $r .= "</nav><nav class='seletor'>";
  foreach ($urls as $key => $value) {
    $r .= "<label for='" . shortcode_sldtxt_url_toid($value) . "' ndc='$key' style=\"background-image: url('$value');\"><img src='$value' /></label>";
  }

  return preg_replace('|\s*[\r\n]+\s*|is', '', $r . "</nav></div>");
}
