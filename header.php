<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 *
 * ============================================================================
 *
 * FastAuth
 * FastAuth é um enxuto, simples e fácil de usar (easy-to-use) autenticador em
 * PHP. Seu principal objetivo é eliminar o máximo a configuração, sendo simples
 * de colocar em qualquer aplicação PHP.
 *
 * @author     Jean Carlo de Elias Moreira | https://www.jeancarloem.com
 * @license    MPL2 | http://mozilla.org/MPL/2.0/.
 * @copyright  © 2017 Jean Carlo EM
 * @git        https://github.com/JeanCarloEM/FastAuth
 * @site       https://opensource.jeancarloem.com/FastAuth
 * @dependency Passmeter | https://github.com/JeanCarloEM/Passmeter
 */

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;
use jeancarloem\Wordpress\plugins\MPTEditor as mpte;

require_once "functions.php";
?><!DOCTYPE html>
<html <?php
language_attributes();
echo (have_posts() && STinyTheme::showThemeOG()) ? ' prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" itemscope itemtype="http://schema.org/Blog"' : '';
?>>
  <head><?php
    if (\have_posts() && STinyTheme::showThemeOG()) {
      /* A EXECUÇÃO DE \THE_POST() AQUI FAZ COM QUE O ARTIGO NAO SEJA CARREGADO
       * CORRETAMENTE POSTERIORMENTE, POR CONTA DO FATOR DE ITERAÇÃO
       * POR CONTA DISSO, NO FINAL DA CONSTRUÇÃO DO HEADER, TEM-SE QUE RESETAR
       * O ITERADOR DO WP-QUERY COM O COMANDO \rewind_posts();
       */
      \the_post();
      $titulo = \get_the_title();
      $url = \get_the_permalink();
      $sitename = \get_bloginfo('name');
      $excerto = @\get_the_excerpt();
      $excerto = empty(\trim($excerto)) ? STinyTheme::getVar('og_text') : $excerto;

      $tagsAdnCategoria = implode(' ', \wp_get_post_categories(\get_the_ID(), ['fields' => 'names'])) . " " . \implode(' ', \wp_get_post_tags(get_the_ID(), ['fields' => 'names']));
      $tags = \implode(' ', \array_flip(\array_flip(explode(' ', $tagsAdnCategoria))));

      echo "\n\t<!-- START - Open Graph for Facebook, Google+ and Twitter Card Tags 2.2.4.2 -->";
      echo "\n\t<!-- Facebook / OG -->";
      echo "\n\t<meta property='og:title' content='" . $titulo . "' />";
      echo "\n\t<meta property='og:url' content='" . $url . "' />";

      echo "\n\t<meta property='og:description' content='" . $excerto . "' />";
      echo "\n\t<meta property='og:site_name ' content='" . $sitename . "' />";

      $image = @\jeancarloem\Wordpress\Admins\tools\select::getThumb(false, true);

      $image_url = (is_array($image) && !empty($image) && (array_key_exists('0', $image))) ? (!empty(@$image[0][0]) ? $image[0][0] : @$image[0]['url']) : '';

      if ((@count($image) >= 1) || (is_string($image) && !empty($image))) {
        echo "\n\t<meta property='og:image' content='" . $image_url . "' />";

        if (@count($image[0]) > 1) {
          echo "\n\t<meta property='og:image:type' content='" . @$image[0][1]['sizes']['thumbnail']['mime-type'] . "' />";
          echo "\n\t<meta property='og:image:width' content='" . @$image[0][1]["width"] . "' />";
          echo "\n\t<meta property='og:image:height' content='" . @$image[0][1]["height"] . "' />";
        }
      }

      echo "\n\t<meta property='og:type' content='article' />";
      echo "\n\t<meta property='article:published_time' content='" . get_the_time("c") . "' />";
      echo "\n\t<meta property='article:modified_time' content='" . get_the_modified_time("c") . "' />";
      echo "\n\t<meta property='article:tag' content='$tags' />";
      echo "\n\t<meta property='article:author' content='" . \get_the_author() . "' />";

      /*
       *
       */
      echo "\n\t<!-- Google+ / Schema.org -->";
      echo "\n\t<meta itemprop='name' content='" . $titulo . "' />";
      echo "\n\t<meta itemprop='headline' content='" . $titulo . "' />";
      echo "\n\t<meta itemprop='url' content='" . $url . "' />";
      echo "\n\t<meta itemprop='description' content='" . $excerto . "' />";
      echo "\n\t<meta itemprop='image' content='$image_url'/>";
      echo "\n\t<meta itemprop='datePublished' content='" . get_the_time("c") . "' />";
      echo "\n\t<meta itemprop='dateModified' content='" . get_the_modified_time("c") . "' />";
      echo "\n\t<meta itemprop='author' content='" . \get_the_author() . "' />";

      /*
       *
       */
      echo "\n\t<!--Twitter CARDs -->";
      echo "\n\t<meta name = 'twitter:title' content='" . $titulo . "' />";
      echo "\n\t<meta name = 'twitter:url' content='" . $url . "' />";
      echo "\n\t<meta name = 'twitter:image' content='$image_url' />";
      echo "\n\t<meta name = 'twitter:description' content='" . $excerto . "' />";
      echo "\n\t<meta name = 'twitter:card' content='summary_large_image' />";

      /*
       *
       */
      echo "\n\t<!-- SEO -->";
      echo "\n\t<meta name = 'description' content='" . $excerto . "' />";
      echo "\n\t<meta name = 'publisher' content='" . STinyTheme::Publicador() . "' />";
      echo "\n\t<meta name='author' content='" . get_the_author() . "' />";
      echo "\n\t<link rel='canonical' href='" . $url . "' />";
      echo "\n\t<!--END - Open Graph for Facebook, Google+ and Twitter Card Tags 2.2.4.2 -->";
    }

    # https://stackoverflow.com/questions/23849377/html-5-favicon-support
    $ico_raiz = STinyTheme::getVar('favicon_url_raiz');

    echo "\n\t<!-- START - ICONES -->";
    echo "\n\t<meta name='apple-mobile-web-app-title' content='$sitename'>";
    echo "\n\t<meta name='application-name' content='$sitename'>";
    echo "\n\t<meta name='msapplication-TileColor' content='" . STinyTheme::getVar('msapplication_TileColor', '#000000') . "'>";
    echo "\n\t<meta name='theme-color' content='" . STinyTheme::getVar('theme_color', '#000000') . "'>\n";

    if (!empty($ico_raiz)) {
      $ico_raiz = rtrim($ico_raiz, '/');
      ?>
      <link rel='apple-touch-icon' sizes='180x180' href='<?php echo $ico_raiz; ?>/180x180.png'>
      <link rel='icon' type='image/png' sizes='512x512' href='<?php echo $ico_raiz; ?>/512x512.png'>
      <link rel='icon' type='image/png' sizes='256x256' href='<?php echo $ico_raiz; ?>/256x256.png'>
      <link rel='icon' type='image/png' sizes='32x32' href='<?php echo $ico_raiz; ?>/32x32.png'>
      <link rel='icon' type='image/png' sizes='16x16' href='<?php echo $ico_raiz; ?>/16x16.png'>
      <link rel='manifest' href='<?php echo $ico_raiz; ?>/site.webmanifest'>
      <link rel='mask-icon' href='<?php echo $ico_raiz; ?>/safari-pinned-tab.svg' color='<?php echo STinyTheme::getVar('mask_icon', '#000000'); ?>'>
      <meta name='msapplication-TileImage' content='<?php echo $ico_raiz; ?>/144x144.png'>
      <meta name='msapplication-config' content='<?php echo $ico_raiz ?>/browserconfig.xml' />
      <!--[if IE]><link rel="shortcut icon" href="<?php echo $ico_raiz; ?>/favicon.ico"><![endif]-->
      <?php
    } else {
      $p180 = trim(STinyTheme::getVar("ico_t180x180"));
      $p512 = trim(STinyTheme::getVar("ico_t512x512"));
      $p256 = trim(STinyTheme::getVar("ico_t256x256"));
      $p144 = trim(STinyTheme::getVar("ico_t144x144"));
      $p32 = trim(STinyTheme::getVar("ico_t32x32"));
      $p16 = trim(STinyTheme::getVar("ico_t16x16"));
      $favicon_icon_url = trim(STinyTheme::getVar("favicon_icon_url"));

      $manifest = trim(STinyTheme::getVar("webmanifest"));
      $safari_pinned_tab = trim(STinyTheme::getVar("safari_pinned_tab"));
      $browserconfig = trim(STinyTheme::getVar("browserconfig"));

      if ($p180) {
        echo "\n\t<link rel='apple-touch-icon' sizes='180x180' href='$p180'>";
      }
      if ($p512) {
        echo "\n\t<link rel='icon' type='image/png' sizes='512x512' href='$p512'>";
      }
      if ($p256) {
        echo "\n\t<link rel='icon' type='image/png' sizes='256x256' href='$p256'>";
      }
      if ($p32) {
        echo "\n\t<link rel='icon' type='image/png' sizes='32x32' href='$p32'>";
      }
      if ($p16) {
        echo "\n\t<link rel='icon' type='image/png' sizes='16x16' href='$p16'>";
      }
      if ($manifest) {
        echo "\n\t<link rel='manifest' href='$manifest'>";
      }
      if ($safari_pinned_tab) {
        echo "\n\t<link rel='mask-icon' href='$safari_pinned_tab' color='" . STinyTheme::getVar('mask_icon', '#000000') . "'>";
      }
      if ($p144) {
        echo "\n\t<meta name='msapplication-TileImage' content='$p144'>";
      }
      if ($browserconfig) {
        echo "\n\t<meta name='msapplication-config' content='$browserconfig' />";
      }
      if ($favicon_icon_url) {
        echo "<!--[if IE]><link rel='shortcut icon' href='$favicon_icon_url'><![endif]-->";
      }
    }

    echo "\n\t<!-- END - ICONES -->";
    ?>

    <title><?php echo $titulo . " | " . $sitename; ?></title>
    <meta charset="<?php bloginfo('charset');
    ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php
    /*
     *
     */
    echo "\n\t<!-- RICH SNIPPETYS :: START -->";
    STinyTheme::rich();
    echo "\t<!-- RICH SNIPPETYS :: END -->\n";
    ?>

    <style><?php echo file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . "carregandoPagina.css"); ?></style>

    <!-- CSS PRINCIPAL -->
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css' integrity='sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ' crossorigin='anonymous'>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/assets/css/main.css?v=<?php echo \filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'assets/css/main.css'); ?>" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/assets/css/header.css?v=<?php echo \filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'assets/css/header.css'); ?>" rel="stylesheet" />
    <?php if (jwpt\STinyTheme::getVar('ganalytics_ua')) { ?>
      <!-- START :: GOOGLE ANALYTICS -->
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo jwpt\STinyTheme::getVar('ganalytics_ua'); ?>"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
          dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '<?php echo jwpt\STinyTheme::getVar('ganalytics_ua'); ?>');
      </script>
      <!-- END :: GOOGLE ANALYTICS -->
    <?php } ?>

    <!-- START :: WP HEADER -->
    <?php echo jwpt\STinyTheme::get_wp_head(); ?>
    <!-- END :: WP HEADER -->

    <!--
    ## THEME SECTION
    -->

    <!-- SLIDER -->
    <script type="text/javascript" src="<?php echo bloginfo('template_directory'); ?>/assets/js/jcemslider.min.js?v=<?php echo \filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'assets/js/jcemslider.min.js'); ?>">"></script>

    <!-- CONTROLES DO TEMA -->
    <script type="text/javascript" src="<?php echo bloginfo('template_directory'); ?>/assets/js/main.min.js?v=<?php echo \filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'assets/js/main.min.js'); ?>"></script>

    <style>
      body > div.pagebody{
        display: none;
      }
      <?php echo STinyTheme::getMeta('headerJsCSS_css'); ?>
    </style>
    <script><?php echo STinyTheme::getMeta('headerJsCSS_js'); ?></script>
  </head><?php
  /* EXECUTADO PARA QUE A ITERAÇÃO DE \THE_POST() RECOMEÇE E NÃO INTERFIRA NA
   * EXIBIÇÃO DO POST
   */
  \rewind_posts();
  ?>

  <body <?php body_class(); ?>>
    <div id='carregandoPagina' class='carregandoPagina'><span></span><span></span><span></span><span></span></div>

    <?php
    for ($i = 0; $i < 7; $i++) {
      flush();
    }
    ?>

    <section class="body pagebody<?php echo (STinyTheme::getVar('iasd_bar') ? ' iasd' : '') . (STinyTheme::imagemThumbOverload() ? " thumb_overload" : '') . (jwpt\STinyTheme::imagemNormal() ? "" : " imagem_macro" . (jwpt\STinyTheme::imagemMaxCover() ? " macro_cover" : (jwpt\STinyTheme::imagemFitHeight() ? " macro_fit" : ""))); ?>" style='display: none;'>
      <?php
      $cbk = STinyTheme::getVar('iasd_bar_color') ?? '#003366';
      $color = STinyTheme::getVar('iasd_color') ?? '#fff';
      $cbk = "background: $cbk;";

      if (STinyTheme::showIasdBar()) {
        $iasd_link = jwpt\STinyTheme::getVar('iasd_link');
        echo "<header class='fixed iasd' style='$cbk'>" . ($iasd_link ? "<a href='$iasd_link' class='nosymbol' target='_blank'>" : '') . preg_replace('|#ccc|i', $color, file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'assets/img/iasd.svg')) . ($iasd_link ? "</a>" : '') . "</header>";
      }
      ?>
      <header class="first fixed">
        <div class="colunabarra icon">
          <?php
          $field = esc_attr(get_option(STinyTheme::PREFIX . 'logotipoimage'));
          if (!$field) {
            $field = get_bloginfo('template_directory') . "/assets/img/prelogo.svg";
          }
          ?>

          <a class='nosimbol' href="<?php echo esc_attr(get_option(STinyTheme::PREFIX . 'logolink')) ?>"><img src="<?php echo $field; ?>" alt="Logo" class="mainlogo" /></a>

          <div class='ico menu'><div class='fa'></div></div>
          <div class="ico follow"><div class="fas fa-caret-down before"></div></div>
          <div class='ico topo'><a href="#topo" class="social fas fa-arrow-up topo"></a></div>
          <ul class="social">
            <?php
            foreach (STinyTheme::getSociais() as $key => $value) {
              $field = STinyTheme::getVar($value);
              if ($field) {
                echo "<li><a href='$field' target='_blanc' class='social $value'><span>" . ucfirst($value) . "</span></a></li>";
              }
            }

            if (STinyTheme::showIasdBar()) {
              echo "<li><a href='http://novotempo.com/' target='_blanc' class='social novotempo'><span>Novo Tempo</span></a></li>";
            }
            ?>
          </ul>
        </div>
      </header>

      <nav class="second"  id="topo">
        <!-- MENU SECUNDARIO - TOPO HEADER -->
        <div class="colunabarra">
          <?php
          \wp_nav_menu(array('theme_location' => 'topo'));
          ?>

          <div class="lupa"></div>
        </div>
      </nav>

      <nav class="search">
        <div class="colunabarra">
          <form method="get" action='<?php echo get_option('home') . '/'; ?>' accept-charset='utf-8'>
            <div class="igroup">
              <input name="s" id='d' placeholder="Insira as palavras-chave aqui" type="text" />
              <span>
                <button type="submit">Buscar</button>
              </span>
            </div>
          </form>
        </div>
      </nav>

      <?php
      if (jwpt\STinyTheme::imagemMax()) {
        global $first_post;
        $first_post = true;
        echo '<header class="third thumb' . (jwpt\STinyTheme::imagemMaxCover() ? " cover" : (jwpt\STinyTheme::imagemFitHeight() ? " fitheight" : '')) . '"' . ((jwpt\STinyTheme::imagemMaxCover() || jwpt\STinyTheme::imagemFitHeight() && jwpt\STinyTheme::imagemFitHeightValue()) ? "style='height:" . jwpt\STinyTheme::imagemFitHeightValue() . " !important;'" : '') . '>';
        \get_template_part('.slider.jcemslider');
        echo '</header>';
      }

      global $dontShowSectionInnerWrapper, $dontShowMasterDivColunaBarra;

      if (!$dontShowMasterDivColunaBarra) {
        ?>
        <div class="wrapper colunabarra">
          <?php
        }
        /* THUMBNAIL MAXIMIZADO */
        if (jwpt\STinyTheme::imagemNormal(true)) {
          ?><header class='third'>
            <div class="sitedescri">
              <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a> &mdash; <span><?php echo get_bloginfo('description'); ?></span></h1>
            </div><!-- /brand -->

            <nav class="navmenu">
              <?php
              \wp_nav_menu(array('theme_location' => 'title'));
              ?>
            </nav>

            <div class="clear"></div>
          </header><?php
        }

        if (!$dontShowSectionInnerWrapper) {
          ?>
          <section class="inner wrapper"><?php
          }
          $conteudo = jwpt\STinyTheme::getWPMenuSemUL('title', true);

          echo <<<EOF
      <script>
        jQuery(document).ready(function(){
          jQuery('section.body > nav.second ul').html(jQuery('section.body > nav.second ul').html() + "<hr class='menuseparator' />$conteudo");
        });
      </script>
EOF;
          ?>
