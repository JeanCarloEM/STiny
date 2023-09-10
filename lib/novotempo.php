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
\add_action('init', __NAMESPACE__ . '\shortcode_novotempo_init');

/*
 *
 */

function shortcode_novotempo_init() {
  /* ADICIONA ESTILO CSS */
  \wp_enqueue_style(\basename(__FILE__, '.php'), \get_template_directory_uri() . "/assets/css/." . basename(__FILE__, '.php') . ".css");

  /* ADICIONA O SHORTCODE */
  \add_shortcode('tvnovotempo', __NAMESPACE__ . '\shortcode_tvnovotempo');

  /*
   * gutenberg block
   */
  \wp_enqueue_script(
          'sunset',
          \get_template_directory_uri() . '/assets/js/tvnovotempo.js',
          [],
          \filemtime(\realpath(\plugin_dir_path(__DIR__) . 'assets/js/tvnovotempo.js')),
          true
  );
}

/*
 *
 */

function shortcode_tvnovotempo_programas_array() {
  return [
      [
          "url" => "http://novotempo.com/caixademusic",
          "thumb" => "http://stat10.novotempo.com/images/aovivo/t1_23.jpg",
          "title" => "Caixa de Música"
      ], [
          "url" => "http://novotempo.com/vidaesaude",
          "thumb" => "http://stat10.novotempo.com/images/aovivo/t1_129.jpg",
          "title" => "Vida &amp; Saúde"
      ], [
          "url" => "http://novotempo.com/namiradaverdade",
          "thumb" => "http://stat10.novotempo.com/images/aovivo/t1_72.jpg",
          "title" => "Na Mira da Verdade"
      ], [
          "url" => 'http://novotempo.com/anjosdaesperanca',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_2.jpg',
          "title" => 'Anjos'
      ], [
          "url" => 'http://novotempo.com/consultoriodefamilia',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_17.jpg',
          "title" => 'Consultório de Família'
      ], [
          "url" => 'http://novotempo.com/codigoaberto',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_13.jpg',
          "title" => 'Código Aberto'
      ], [
          "url" => 'http://novotempo.com/tiaceceu',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_64.jpg',
          "title" => 'Tia Cecéu'
      ], [
          "url" => 'http://novotempo.com/conexaojovem',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_21.jpg',
          "title" => 'Conexão Jovem'
      ], [
          "url" => 'http://novotempo.com/saldoextra',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_108.jpg',
          "title" => 'Saldo Extra'
      ], [
          "url" => 'http://novotempo.com/felizsabado',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_53.jpg',
          "title" => 'Feliz Sábado'
      ], [
          "url" => 'http://novotempo.com/arenadofuturo',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_3.jpg',
          "title" => 'Arena do Futuro'
      ], [
          "url" => 'http://novotempo.com/licoesdabiblia',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_68.jpg',
          "title" => 'Lições da Bíblia'
      ], [
          "url" => 'http://novotempo.com/revista',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_98.jpg',
          "title" => 'Revista'
      ], [
          "url" => 'http://novotempo.com/educacao',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_38.jpg',
          "title" => 'Educação'
      ], [
          "url" => 'http://novotempo.com/semtabus',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_114.jpg',
          "title" => 'Sem Tabus'
      ], [
          "url" => 'http://novotempo.com/bibliafacil',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_12.jpg',
          "title" => 'Bíblia Fácil'
      ], [
          "url" => 'http://novotempo.com/180graus',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_90.jpg',
          "title" => '180 Graus'
      ], [
          "url" => 'http://novotempo.com/lugardepaz',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_70.jpg',
          "title" => 'Lugar de paz'
      ], [
          "url" => 'http://novotempo.com/armarinhodaarte',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_283.jpg',
          "title" => 'Armarinho da arte'
      ], [
          "url" => 'http://novotempo.com/viva',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_124.jpg',
          "title" => 'Viva uma experiência real'
      ], [
          "url" => 'http://novotempo.com/larefamilia',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_284.jpg',
          "title" => 'Lar e família'
      ], [
          "url" => 'http://novotempo.com/feparahoje',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_52.jpg',
          "title" => 'Fé para Hoje'
      ], [
          "url" => 'http://novotempo.com/missao360',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_329.jpg',
          "title" => 'Missão 360'
      ], [
          "url" => 'http://novotempo.com/origens',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_319.jpg',
          "title" => 'Origens'
      ], [
          "url" => 'http://novotempo.com/redacaont',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_327.jpg',
          "title" => 'Redação NT'
      ], [
          "url" => 'http://novotempo.com/alemdosfatos',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_337.jpg',
          "title" => 'Além dos Fatos'
      ], [
          "url" => 'http://novotempo.com/hiperlinkados',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_328.jpg',
          "title" => 'Hiperlinkados'
      ], [
          "url" => 'http://novotempo.com/feemacao',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_325.jpg',
          "title" => 'Fé em Ação'
      ], [
          "url" => 'http://novotempo.com/minhavez',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_252.jpg',
          "title" => 'Minha Vez'
      ], [
          "url" => 'http://novotempo.com/adoracao',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_374.jpg',
          "title" => 'Adoração'
      ], [
          "url" => 'http://novotempo.com/vidamaisviva',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_373.jpg',
          "title" => 'Vida mais viva'
      ], [
          "url" => 'http://novotempo.com/maranatha',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_322.jpg',
          "title" => 'Maranatha'
      ], [
          "url" => 'http://novotempo.com/claramente',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_382.jpg',
          "title" => 'Claramente'
      ], [
          "url" => 'http://novotempo.com/jornaldant',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_340.jpg',
          "title" => 'Jornal da NT'
      ], [
          "url" => 'http://novotempo.com/escolabiblicant',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_383.jpg',
          "title" => 'Escola Bíblica NT'
      ], [
          "url" => 'http://novotempo.com/teologos',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_384.jpg',
          "title" => 'Teólogos'
      ], [
          "url" => 'http://novotempo.com/perfilmusical',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_92.jpg',
          "title" => 'Perfil Musical'
      ], [
          "url" => 'http://novotempo.com/evidencias',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_49.jpg',
          "title" => 'Evidências'
      ], [
          "url" => 'https://www.youtube.com/channel/UCEx40aN81ymFpGPtzWA6Qvw/',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_99.jpg',
          "title" => 'Reavivados Por Sua Palavra'
      ], [
          "url" => 'http://novotempo.com/realidadeempauta',
          "thumb" => 'http://stat10.novotempo.com/images/aovivo/t1_397.jpg',
          "title" => 'Realidade em Pauta'
      ],
  ];
}

/*
 * CONTEUDO DE $content NAO PODE TER <P>
 */

function shortcode_tvnovotempo($args = [], $content = '') {
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

  $progs = shortcode_tvnovotempo_programas_array();
  $per_quadro = 6;

  $progs_quadros = \ceil(\count($progs) / $per_quadro);
  $prog_index = 0;
  $quadros = '';
  $retorno = shortcode_sldtx_htmlmodel('', 'covercontent', '', 7000, "rgba(255,255,255,.05)");

  for ($i = 0; $i < $progs_quadros; $i++) {
    $block_progs = '';
    for ($j = 0; $prog_index < count($progs) && $j < $per_quadro; $j++, $prog_index++) {
      $block_progs .= <<<EOF

<a class="subitem" href='{$progs[$prog_index]['url']}' target='_blank'>
  <div class='subitem cover'><img src='{$progs[$prog_index]['thumb']}' /></div>
  <h3 class='subitem title'><div>{$progs[$prog_index]['title']}</div></h3>
  <div class='subitem content'>{$progs[$prog_index]['content']}</div>
</a>
EOF;
    }

    shortcode_sldtx_callableAdd($retorno, '', '', '', $block_progs, '', 'div', [
        "text" => [
            "class" => "colunas$per_quadro"
        ]
    ]);
  }
  return $retorno;
}
