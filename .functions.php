<?php
/*
  Theme Name: STiny
  Theme URI: https://lab.jeancarloem.com/wordpress/STiny
  Author: Jean Carlo de Elias Moreira
  Author URI: https://jeancarloem.com
  Description: A super simple theme that can be customized using LESS variables
  Version: 1
  License: GNU General Public License
 */

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;
use jeancarloem\Wordpress\Admins\tools as tls;

if (!class_exists('STinyTheme')) {
  if (\is_admin()) {
    add_action('admin_menu', ['jeancarloem\Wordpress\Temas\STiny\STinyTheme', 'admin_menu']);
    add_action('network_admin_menu', ['jeancarloem\Wordpress\Temas\STiny\STinyTheme', 'network_admin_menu']);

    add_action('show_user_profile', ['jeancarloem\Wordpress\Temas\STiny\STinyTheme', 'user_edit_action']);
    add_action('edit_user_profile', ['jeancarloem\Wordpress\Temas\STiny\STinyTheme', 'user_edit_action']);
    add_action('personal_options_update', ['jeancarloem\Wordpress\Temas\STiny\STinyTheme', 'user_update_action']);
    add_action('edit_user_profile_update', ['jeancarloem\Wordpress\Temas\STiny\STinyTheme', 'user_update_action']);
  }

  /* POR ENQUANTO ISTO NAO EXISTE NO WORDPRESS
   * NAO FUNCIONA, MAS DEIXADO AQUI PARA O CASO DE UM DIA VIR A EXISTIR
   * https://wordpress.stackexchange.com/questions/200622/how-to-trigger-function-on-theme-delete
   */
  register_uninstall_hook(__FILE__, ["jeancarloem\Wordpress\Temas\STiny\STinyTheme", 'uninstall']);
  /* SIDEBAR PARA RODAPE */
  add_action('widgets_init', ["jeancarloem\Wordpress\Temas\STiny\STinyTheme", 'footerWidgets']);
  /* ITENS DE MENU */
  register_nav_menus([
      'topo' => __('Barra de Menu Superior', 'STiny'),
      'title' => __('Menu de Título', 'STiny'),
      'bottom' => __('Menu de Rodapé', 'STiny'),
  ]);


  /*
   *
   */
  foreach (scandir(__DIR__ . DIRECTORY_SEPARATOR . 'lib') as $filename) {
    $filename = __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $filename;
    if (is_file($filename)) {
      require_once $filename;
    }
  }

  /*
   *
   */

  abstract class STinyTheme extends wpa\PagesConstruct {

    const PREFIX = "stiny_";

    public static $optionsFields = [], $sociais = [], $metaboxs = [];

    static function uninstall() {
      return static::removeAllOptions();
    }

    /*
     *
     */

    public static function footerWidgets() {
      $pdr = array(
          'class' => '',
          'before_widget' => '<side class="widget %2$s">',
          'after_widget' => '</side>',
          'before_title' => '<h4 class="title">',
          'after_title' => '</h4>'
      );

      $args = [];
      for ($i = 0; $i < 3; $i++) {
        register_sidebar(array_merge([
            "id" => "footer_widget_$i",
            "name" => "Footer Widget $i",
            "description" => (($i === 0) ? "Conteúdo para o widget do rodaté com colunas, " : (($i === 1) ? "Conteudo de Rodapé inteiro (sem coluna) com fundo escuro" : "Conteúdo do Rodape Inferior (sem coluna) sem fundo ")) . " #" . ($i + 1) . "."
                        ], $pdr));
      }
    }

    /*
     *
     */

    public static function getFooterWidget($id = null) {
      if (is_int($id)) {
        if (function_exists('get_sidebar')) {
          \ob_start();
          \dynamic_sidebar("footer_widget_$id");
          return \ob_get_clean();
        }
      } else {
        $r = '';

        for ($i = 0; $i < 5; $i++) {
          $r .= static::getFooterWidget($i);
        }

        return $r;
      }
    }

    public static function isWidgetActive($id) {
      if (is_int($id)) {
        if (function_exists('is_active_sidebar')) {
          return \is_active_sidebar("footer_widget_$id");
        }
      }
    }

    public static function &getSociais() {
      if (empty(self::$sociais)) {
        self::$sociais = ["facebook", "twitter", "flickr", "youtube", "vimeo", "instagram", "pinterest", "reddit", "tumblr"];
        sort(self::$sociais);
        self::$sociais = array_merge(["aboutme", "github", "linkedin"], self::$sociais);
      }

      return self::$sociais;
    }

    /*
     *
     */

    public static function user_edit_action($user) {
      $checked = (isset($user->stiny_perfil_organizacional) && $user->stiny_perfil_organizacional) ? ' checked="checked"' : '';
      ?>
      <h3>Organização</h3>
      <table class="form-table metabox-location-advanced postbox-container" role="presentation">
        <tr class="user-rich-editing-wrap">
          <th scope="row">Perfil Organizacional?</th>
          <td>
            <label for="stiny_perfil_organizacional"><input name="stiny_perfil_organizacional" type="checkbox" id="stiny_perfil_organizacional" value="1"<?php echo $checked; ?>>Sim</label>
          </td>
        </tr>

        <tr class="user-profile-picture wrap jcem_wpAdmin ">
          <th>Imagem 1200x630px Organizacional</th>
          <td class='form fields'><div class="input stiny_perfil_organizacional_logo image">
              <input name="stiny_perfil_organizacional_logo" placeholder="URL" type="text" value="<?php echo $user->stiny_perfil_organizacional_logo; ?>">
              <button type="button" class="upload">Selecionar</button>
            </div></td>
        </tr>
      </table>

      <?php
    }

    public static function user_update_action($user_id) {
      \update_user_meta($user_id, 'stiny_perfil_organizacional', isset($_POST['stiny_perfil_organizacional']));
      \update_user_meta($user_id, 'stiny_perfil_organizacional_logo', $_POST['stiny_perfil_organizacional_logo']);
    }

    /*
     *
     */

    public static function richGenericdata($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo) {
      $prev = [
          "@context" => "https://schema.org",
          "headline" => \get_the_title(),
          "mainEntityOfPage" => \get_the_permalink(),
          "description" => $excerto,
          "datePublished" => \get_the_time("c"),
          "dateModified" => \get_the_modified_time('c')
      ];

      $prev["image"] = (\count($imgs_urls) > 1) ? $imgs_urls : $imgs_urls[0];

      $tags = \implode(' ', \array_flip(\array_flip(explode(' ', $tagsAdnCategoria))));

      if (!empty($tags)) {
        $prev["keywords"] = $tags;
      }

      if ($autorLogo) {
        $prev["author"]["logo"] = $autorLogo;
      }

      return $prev;
    }

    public static function richNewsArticle($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, $tipo = 'Article') {
      return static::richArtigo($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, 'NewsArticle');
    }

    public static function richBlogPosting($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, $tipo = 'Article') {
      return static::richArtigo($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, 'BlogPosting');
    }

    public static function richArticle($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, $tipo = 'Article') {
      return static::richArtigo($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, 'Article');
    }

    public static function richVideo($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, $tipo = 'Article') {
      $link_player = trim(static::getMeta('rich_video_url'));
      $file_url = trim(static::getMeta('rich_video_fileurl'));

      if (empty($link_player) && empty($file_url)) {
        return false;
      }

      $r = static::richArtigo($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, 'Article');
      $r["@type"] = 'VideoObject';
      $r['abstract'] = $r['description'];
      $r['name'] = $r['headline'];
      $r['uploadDate'] = $r['datePublished'];

      if (array_key_exists('image', $r)) {
        $r['thumbnailUrl'] = $r['image'];
      }

      if (array_key_exists('publisher', $r)) {
        $r['creator'] = $r['publisher'];
      }

      $d = trim(static::getMeta('rich_video_duration'));

      if (!empty($d)) {
        $d = explode(':', $d);

        if (count($d) === 2) {
          $d[0] = trim($d[0]);
          $d[0] = empty($d[0]) ? 0 : $d[0];

          $d[1] = trim($d[1]);
          $d[1] = empty($d[1]) ? 0 : $d[1];
          $r['duration'] = "PT{$d[0]}M{$d[1]}S";
        }
      }


      if (!empty($link_player)) {
        $r['embedUrl'] = $link_player;
      }

      if (!empty($file_url)) {
        $r['contentUrl'] = $file_url;
      }

      $d = trim(static::getMeta('rich_video_width'));
      if (!empty($d)) {
        $r['width'] = $d;
      }

      $d = trim(static::getMeta('rich_video_height'));
      if (!empty($d)) {
        $r['height'] = $d;
      }

      $d = trim(static::getMeta('rich_video_genero'));
      if (!empty($d)) {
        $r['genre'] = $d;
      }

      $d = trim(static::getMeta('rich_video_license'));
      if (!empty($d)) {
        $r['license'] = $d;
      }

      return $r;
    }

    public static function richArtigo($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo, $tipo = 'Article') {
      $prev = [
          "@type" => $tipo,
          "author" => [
              "@type" => $autor_tipo,
              "name" => \get_the_author(),
              "image" => $autorImage
          ],
          "publisher" => [
              "@type" => "Organization",
              "name" => static::Publicador(),
              "logo" => [
                  "@type" => "ImageObject",
                  "url" => static::getVar('rich_snippets_org_logo'),
                  "width" => 600,
                  "height" => 60
              ]
          ],
      ];

      return \array_merge($prev, static::richGenericdata($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo));
    }

    public static function richWebSite($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo) {
      $prev = [
          "@type" => "WebSite",
          "name" => "JeanCarloEM",
          "url" => \get_the_permalink()
      ];

      return \array_merge($prev, static::richGenericdata($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo));
    }

    /*
     * https://developer.wordpress.org/reference/functions/get_avatar/
     */

    public static function avatar_url($id_or_email = false, $size = 256, $default = '', $alt = '', $args = null) {
      $id_or_email = (\is_int($id_or_email) && ($id_or_email > 0)) ? $id_or_email : \get_the_author_meta('ID');

      $defaults = array(
          // get_avatar_data() args.
          'size' => 256,
          'height' => null,
          'width' => null,
          'default' => get_option('avatar_default', 'mystery'),
          'force_default' => false,
          'rating' => get_option('avatar_rating'),
          'scheme' => null,
          'alt' => '',
          'class' => null,
          'force_display' => false,
          'extra_attr' => '',
      );

      if (empty($args)) {
        $args = array();
      }

      $args['size'] = (int) $size;
      $args['default'] = $default;
      $args['alt'] = $alt;

      $args = \wp_parse_args($args, $defaults);

      if (empty($args['height'])) {
        $args['height'] = $args['size'];
      }
      if (empty($args['width'])) {
        $args['width'] = $args['size'];
      }

      if (\is_object($id_or_email) && isset($id_or_email->comment_ID)) {
        $id_or_email = \get_comment($id_or_email);
      }

      /**
       * Filters whether to retrieve the avatar URL early.
       *
       * Passing a non-null value will effectively short-circuit get_avatar(), passing
       * the value through the {@see 'get_avatar'} filter and returning early.
       *
       * @since 4.2.0
       *
       * @param string|null $avatar      HTML for the user's avatar. Default null.
       * @param mixed       $id_or_email The Gravatar to retrieve. Accepts a user_id, gravatar md5 hash,
       *                                 user email, WP_User object, WP_Post object, or WP_Comment object.
       * @param array       $args        Arguments passed to get_avatar_url(), after processing.
       */
      $avatar = \apply_filters('pre_get_avatar', null, $id_or_email, $args);

      if (!\is_null($avatar)) {
        /** This filter is documented in wp-includes/pluggable.php */
        return \apply_filters('get_avatar', $avatar, $id_or_email, $args['size'], $args['default'], $args['alt'], $args);
      }

      if (!$args['force_display'] && !\get_option('show_avatars')) {
        return false;
      }

      $args = \get_avatar_data($id_or_email, $args);

      $url = $args['url'];

      if (!$url || \is_wp_error($url)) {
        return 'https://secure.gravatar.com/avatar/?s=512&d=mm&r=g';
      }

      return $url;
    }

    /*
     *
     */

    public static function rich() {
      $pub_img = @\getimagesize(@\get_avatar(@\get_the_author_id(), '60'));

      $imgs = jwpt\STinyTheme::thumbs_post_OR_QueryPost();
      $imgs_urls = [];
      if (is_array($imgs)) {
        foreach ($imgs as $key => $value) {
          $imgs_urls[] = $value['url'];
        }
      } else if (is_string($imgs)) {
        $imgs_urls[] = $imgs;
      }

      $excerto = @\get_the_excerpt();
      $excerto = empty(\trim($excerto)) ? STinyTheme::getVar('og_text') : $excerto;

      $autor_tipo = \get_user_meta(\get_the_author_meta('ID'), 'stiny_perfil_organizacional', true) ? "Organization" : 'Person';

      $avatar = static::avatar_url();

      $autorLogo = (\strtolower(\trim($autor_tipo) === 'organization')) ? [
          "@type" => "ImageObject",
          "url" => $avatar
              ] : false;

      $autorImage = ((\strtolower(\trim($autor_tipo) === 'organization')) && \get_user_meta(\get_the_author_meta('ID'), 'stiny_perfil_organizacional_logo', true)) ? \get_user_meta(\get_the_author_meta('ID'), 'stiny_perfil_organizacional_logo', true) : $avatar;
      $autorImage = (empty($autorImage)) ? STinyTheme::getVar('og_defaultimage') : $autorImage;

      $richs = [];
      if (!empty(static::getDefaultRichOfPost())) {
        $richs[] = static::getDefaultRichOfPost();
      }

      foreach ($richs as $key => $value) {
        $json = [__CLASS__, 'rich' . $value]($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo);
        echo "\n\t<!-- SNIPPETY :: $value --><script type=\"application/ld+json\">" . \json_encode($json, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK) . "</script>\n";
      }


      $richs = ['richVideo'];
      foreach ($richs as $key => $value) {
        $d = [__CLASS__, $value];
        if (\is_callable($d)) {
          $d = $d($imgs_urls, $excerto, $autor_tipo, $autorImage, $autorLogo);
          if (!empty($d) && (\is_array($d))) {
            echo "\n\t<!-- SNIPPETY :: $value --><script type=\"application/ld+json\">" . \json_encode($d, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK) . "</script>\n";
          }
        }
      }
    }

    /*
     *
     */

    public static function getDefaultRichOfPost() {
      $pdr = static::getMeta('rich_default_option');

      $pdr = empty($pdr) ? static::getVar('rich_post_default') : $pdr;

      $pdr = empty($pdr) ? ( (\get_post_type() === 'page') ? 'WebSite' : ((\get_post_type() === 'post') ? 'Article' : 'disable')) : $pdr;

      return ($pdr === 'disable') ? false : $pdr;
    }

    /*
     *
     *
     */

    public static function &getMetaBoxOptions($post) {
      if (empty(self::$metaboxs)) {
        self::$metaboxs = [];

        /*
         * RICH SNIPPETYS
         * https://technicalseo.com/tools/schema-markup-generator/
         */
        self::$metaboxs[] = [
            "id" => 'headerJsCSS',
            "title" => 'CSS e JS',
            "function" => [static::className(), 'buildBoxMetaHTML'],
            'forms' => []
        ];

        self::$metaboxs[count(self::$metaboxs) - 1]['forms'][] = [
            'title' => "Código para inclusão no Header",
            'info' => 'Código CSS e JS, não inclua a tag script ou style.',
            'campos' => [
                self::createCampoMeta('headerJsCSS_css', 'textarea', 'CSS'),
                self::createCampoMeta('headerJsCSS_js', 'textarea', 'JS')
            ]
        ];

        self::$metaboxs[] = [
            "id" => 'rich',
            "title" => 'Rich Snippets - Schema Markup Generator',
            "function" => [static::className(), 'buildBoxMetaHTML'],
            'forms' => []
        ];

        self::$metaboxs[count(self::$metaboxs) - 1]['forms'][] = [
            'title' => "RICH PADRÃO",
            'info' => 'Selecione o rich padrão para este post.^Se você não selecionar um item, definiremos um automáticamente para artigos, o tipo "artigo" e para páginas, "site".',
            'campos' => [
                self::createCampoMeta('rich_default_option', 'select', 'Rich Padrão', ['options' => [
                        'Automático (Padrão)' => '',
                        'Nenhum' => 'disable',
                        'WebSite' => 'WebSite',
                        'Artigo' => 'Article',
                        'Artigo de Notícia' => 'NewsArticle',
                        'Artigo de Blog' => 'BlogPosting',
            ]]),
                self::createCampoMeta('rich_rating', 'checkbox', 'Habilitar Votação')
            ]
        ];

        self::$metaboxs[count(self::$metaboxs) - 1]['forms'][] = [
            'title' => "HowTo Básico",
            'info' => 'Se este post for um HowTo, então prencha as informações abaixa, levando em conta que cada passo, ferramenta ou material deve ser informado <b>um por linha</b>. .',
            'campos' => [
                self::createCampoMeta('rich_howto_stgeps', 'textarea', 'Passos, um por linha'),
                self::createCampoMeta('rich_howto_materiais', 'textarea', 'Materiais, um por linha'),
                self::createCampoMeta('rich_howto_tools', 'textarea', 'Ferramentas, um por linha'),
                self::createCampoMeta('rich_howto_time', 'number', 'Tempo Estimado'),
            ]
        ];

        self::$metaboxs[count(self::$metaboxs) - 1]['forms'][] = [
            'title' => "Vídeo",
            'info' => 'Se este post for um vídeo, então prencha as informações abaixo',
            'campos' => [
                self::createCampoMeta('rich_video_width', 'number', 'Width'),
                self::createCampoMeta('rich_video_height', 'number', 'Height'),
                self::createCampoMeta('rich_video_fileurl', 'text', 'URL do arquivo'),
                self::createCampoMeta('rich_video_url', 'text', 'URL para exibição'),
                self::createCampoMeta('rich_video_duration', 'text', 'Duração'),
                self::createCampoMeta('rich_video_license', 'text', 'Licença'),
                self::createCampoMeta('rich_video_genero', 'text', 'Gênero'),
            ]
        ];

        self::$metaboxs[count(self::$metaboxs) - 1]['forms'][] = [
            'title' => "RICH PERSONALIZADO",
            'info' => 'Defina Richs personalizados adicionais. Coloque eles corretamente fomatados e completos, incluindo a tag \'script\'. Um bom lugar para gerar os rich online é em <a href="https://technicalseo.com/tools/schema-markup-generator/">//technicalseo.com/tools/schema-markup-generator/</a>.',
            'campos' => [
                self::createCampoMeta('rich_adicional', 'textarea', 'Script de richs personalizados adicionais'),
            ]
        ];

        self::$metaboxs[] = [
            "id" => 'thumb_mode',
            "title" => 'Modo de exibição da Imagem de Destaque',
            "function" => [static::className(), 'buildBoxMetaHTML'],
            'forms' => []
        ];

        self::$metaboxs[count(self::$metaboxs) - 1]['forms'][] = [
            'title' => "",
            'info' => 'Defina como a imagem deste post/página deve ser exibida, se nao habilitado, será como a configuração padrão definida no tema.',
            'campos' => [
                self::createCampoMeta('imagem_macro_por_post', 'checkbox', 'Habilitar/Enable Personalização deste Post'),
                self::createCampoMeta('imagem_macro', 'radio', 'Destaque Interna', ['value' => 'normal']),
                self::createCampoMeta('imagem_macro', 'radio', 'Destaque Maximizada', ['value' => 'max']),
                self::createCampoMeta('imagem_macro', 'radio', 'Destaque Maximizada Cover Altura Fixa', ['value' => 'maxcover']),
                self::createCampoMeta('imagem_macro', 'radio', 'Destaque Maximizada Altura Fixa', ['value' => 'fitheight']),
                self::createCampoMeta('imagem_macro_cover_height', 'text', 'Altura Máxima Imagem'),
                self::createCampoMeta('imagem_macro_sob_menu', 'checkbox', 'Imagem Maximizada oveload'),
            ]
        ];

        self::$metaboxs[] = [
            "id" => 'thumb_querypost',
            "title" => 'Imagem de Destaque (thumbnail) baseado em Query',
            "function" => [static::className(), 'buildBoxMetaHTML'],
            'forms' => []
        ];

        self::$metaboxs[count(self::$metaboxs) - 1]['forms'][] = [
            'title' => "",
            'info' => 'Defina as imagens de destaque (thumbails) baseado em uma query de posts. Ao habilitar as imagens de destaques (thumbnaisl) não serão exibidos (excedto de <i>Mesclar com thumbnails</i> estiver habilitado), mas apenas as imagens dos posts da query - exceot para imagem OG, como facebook, google entre outros, que será a imagem de destaque.',
            'campos' => [
                self::createCampoMeta('imagem_query_post', 'checkbox', 'Habilitar/Enable Thumbnail Query Posts'),
                self::createCampoMeta('imagem_query_post_merge_with_thumbs', 'checkbox', 'Mesclar com thumbnails'),
                self::createCampoMeta('imagem_query_posttype', 'text', 'Post Type'),
                self::createCampoMeta('imagem_query_cats', 'text', 'Categorias'),
                self::createCampoMeta('imagem_query_tags', 'text', 'Tags'),
                self::createCampoMeta('imagem_query_qtd', 'text', 'Quantidade'),
                self::createCampoMeta('imagem_query_more', 'textarea', 'Mais Configurações'),
                self::createCampoMeta('imagem_query_functionpass', 'textarea', 'Passagem de Configuração'),
                self::createCampoMeta('imagem_query_showtitle', 'checkbox', 'Exibir Títulos'),
                self::createCampoMeta('imagem_query_showexcerpt', 'checkbox', 'Exibir Excerto'),
                self::createCampoMeta('imagem_query_nolink', 'radio', 'Link na Imagem', ['value' => '']),
                self::createCampoMeta('imagem_query_nolink', 'radio', 'Link no Título', ['value' => 'title']),
                self::createCampoMeta('imagem_query_nolink', 'radio', 'Link com Botão', ['value' => 'button']),
            ]
        ];
      }

      return self::$metaboxs;
    }

    /*
     *
     */

    public static function thumb_querypost_query($vetor = false) {
      if ($vetor) {
        return [
            static::getMeta('imagem_query_posttype'),
            static::getMeta('imagem_query_cats'),
            static::getMeta('imagem_query_tags'),
            0,
            static::getMeta('imagem_query_qtd'),
            static::getMeta('imagem_query_more')
        ];
      }

      return static::getMeta('imagem_query_posttype') . "/" . static::getMeta('imagem_query_cats') . "/" . static::getMeta('imagem_query_tags') . "/" . static::getMeta('imagem_query_qtd') . "/" . static::getMeta('imagem_query_qtd') . "/" . static::getMeta('imagem_query_more');
    }

    /*
     *
     */

    public function thumbs_post_OR_QueryPost(bool $get_default_thumb = false, int $qtd_img = 0): array {
      $imgs = [];

      if (!$get_default_thumb && static::thumb_querypost_enabled()) {
        $functionpass = static::thumb_querypost_functionpass();
        $showexcerpt = static::thumb_querypost_showexcerpt();
        $showtitle = static::thumb_querypost_showtitle();
        $nolink = static::thumb_querypost_nolink();

        /*
         * EXECUTAMOS A QUERY
         */
        tls\select::uriquery(static::thumb_querypost_query(), function($item, $post_type, $categorias, $tags, $onlyExcerpt, $postQtd, $more_options) use (&$imgs, $functionpass, $qtd_img, $showexcerpt, $showtitle, $nolink) {
          if (($qtd_img <= 0) || (count($imgs) < $qtd_img)) {
            $imgs[] = [
                "url" => jcemquery_get_primary_thumb_in_query($item, $functionpass),
                "title" => $showtitle ? $item['title'] : '',
                "content_html" => $showexcerpt ? $item['excerpt'] : '',
                "link" => $item['url']
            ];
          }
        }, false, false);
      }

      if (static::thumb_querypost_merge() || !static::thumb_querypost_enabled()) {
        $imgs2 = wpa\tools\select::getThumb($get_default_thumb);

        if ($get_default_thumb || !\class_exists("jeancarloem\Wordpress\plugins\MPTEditor\MPTEditor")) {
          $imgs2[0] = is_string($imgs2[0]) ? ["url" => $imgs2[0]] : $imgs2[0];
          $imgs2[0]["title"] = \get_the_title();
          $imgs2[0]["content_html"] = \get_the_excerpt() ?? ( \get_post_field('post_excerpt', $post->ID) ?? \get_the_content_feed('json') );
        }

        if ($imgs && $imgs2 && (count($imgs) > 0) && (count($imgs2) > 0) && !$get_default_thumb && static::thumb_querypost_enabled()) {
          $imgs1 = count($imgs) > count($imgs2) ? $imgs : $imgs2;
          $imgs2 = count($imgs) <= count($imgs2) ? $imgs : $imgs2;

          $i1 = -1;
          $i2 = -1;
          $imgs = [];

          for ($key = 0; $key < (count($imgs1) + count($imgs2)); $key++) {
            $imgs[] = ($key % 2 === 0) ? (($i1 < count($imgs1)) ? $imgs1[++$i1] : $imgs2[++$i2]) : (($i2 < count($imgs2)) ? $imgs2[++$i2] : $imgs1[++$i1]);
          }
        } else {
          $imgs = empty($imgs2) ? $imgs : (empty($imgs) ? $imgs2 : (\array_merge($imgs, $imgs2)));
        }
      }

      if (\is_array($imgs)) {
        foreach ($imgs as $key => $value) {
          if (!\array_key_exists('url', $value)) {
            unset($imgs[$key]);
          }
        }

        $imgs = \array_values($imgs);
      }

      return is_array($imgs) ? $imgs : [];
    }

    /*
     *
     */

    public static function thumb_querypost_functionpass(): array {
      $a = \json_decode(static::thumb_querypost_enabled() ? static::tratarAspasUTF8(html_entity_decode(static::getMeta('imagem_query_functionpass'))) : '[]', true);
      return is_array($a) ? $a : [];
    }

    /*
     *
     */

    public static function thumb_querypost_enabled(): bool {
      $r = static::getMeta('imagem_query_post');
      return (!empty($r) && ($r !== 'off'));
    }

    /*
     *
     */

    public static function thumb_querypost_showtitle() {
      $r = static::getMeta('imagem_query_showtitle');
      return (!empty($r) && ($r !== 'off'));
    }

    /*
     *
     */

    public static function thumb_querypost_showexcerpt() {
      $r = static::getMeta('imagem_query_showexcerpt');
      return (!empty($r) && ($r !== 'off'));
    }

    /*
     *
     */

    public static function thumb_querypost_merge() {
      $r = static::getMeta('imagem_query_post_merge_with_thumbs');
      return (!empty($r) && ($r !== 'off'));
    }

    /*
     *
     */

    public static function thumb_querypost_nolink() {
      return static::getMeta('imagem_query_nolink');
    }

    /*
     *
     */

    static function &getOptions() {
      if (empty(self::$optionsFields)) {
        self::$optionsFields[] = [
            'page_title' => 'STiny Theme',
            'menu_title' => 'STiny Theme',
            'capability' => 'administrator',
            'menu_slug' => 'stiny_theme_admin_page',
            'function' => [static::className(), 'stiny_theme_admin_page'],
            'parent_slugORicon_url' => 'themes.php',
            'forms' => []
        ];

        /*
         * GERAL
         */
        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => "Geral",
            'info' => "Informações diversas são necessárias, tais como a URL para o logotipo a ser exibido no topo da página, o link para o qual é redirecionado ao clicar sobre o logotipo superior, o texto de advertência exibido nos posts, dentre outros.<p><b>Importante:</b> Não informe o código UA do <a href='https://pt.wikipedia.org/wiki/Google_Analytics' target='_blank'>Google Analytics</a> se você estiver utilizando algum plugin para esta finalidade: pode gerar incompatibilidades.</p>",
            'campos' => [
                self::createCampoMeta('logotipoimage', 'image', 'Imagem de Logotipo Superior'),
                self::createCampoMeta('advertencia', 'textarea', 'Advertencia'),
                self::createCampoMeta('logolink', 'text', 'Logo Link', ['class' => 'link']),
                self::createCampoMeta('copy', 'textarea', 'Copyright'),
                self::createCampoMeta('ganalytics_ua', 'text', 'UA Google Analytics'),
                self::createCampoMeta('gads_ua', 'text', 'UA Google Adsense')/* AINDA NAO INCLUIDO NO HEADER */
            ]
        ];

        /*
         * GERAL
         */
        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => "Imagem Principal de Destaque",
            'info' => "Defina a configuração geral de como a imagem principal será exibida e, se habilirá a configuração individualizada para post.</p>",
            'campos' => [
                self::createCampoMeta('imagem_macro_por_post', 'checkbox', 'Desabilitar configuração por Post'),
                self::createCampoMeta('imagem_macro', 'radio', 'Destaque Interna', ['value' => 'normal']),
                self::createCampoMeta('imagem_macro', 'radio', 'Destaque Maximizada', ['value' => 'max']),
                self::createCampoMeta('imagem_macro', 'radio', 'Destaque Maximizada Cover Altura Fixa', ['value' => 'maxcover']),
                self::createCampoMeta('imagem_macro', 'radio', 'Destaque Maximizada Altura Fixa', ['value' => 'fitheight']),
                self::createCampoMeta('imagem_macro_cover_height', 'text', 'Altura Máxima Imagem'),
                self::createCampoMeta('imagem_macro_sob_menu', 'checkbox', 'Imagem Maximizada oveload'),
            ]
        ];

        /*
         * Open Graph
         */
        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => "Open Graph",
            'info' => "Este tema inclui metadados <b>Open Graph</b>, <b>schem.org</b> e <b>Twitter Card</b>, que são TAGs que permitem que as páginas e artigos sejam adequadamente exibidos, inclusive com imagem de destaque, em redes sociais e mecanismos de busca. Você pode desabilitar este recurso caso prefira um plugin que faça isso, caso contrário, recomendamos manter ativo.",
            'campos' => [
                self::createCampoMeta('disable_opengraph', 'checkbox', 'DESATIVAR Open Graph do Thema'),
                self::createCampoMeta('og_defaultimage', 'image', 'Imagem padrão'),
                self::createCampoMeta('og_text', 'textarea', 'Resumo Padrão')
            ]
        ];

        /*
         * AUTOR
         */

        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => 'Exibição de autor',
            'info' => "Informe aqui em quais tipos de página/post deseja <b>OCULTAR</b> informações do autor.",
            'name' => 'autor',
            'campos' => static::buildMetaFormFieldsPostType('autor', 'checkbox', ['attachment', 'revision', 'nav_menu_item'])
        ];

        /*
         * METAS
         */

        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => 'Exibição de Categorias e TAGs.',
            'info' => "Informe aqui em quais tipos de página/post deseja <b>OCULTAR</b> informações metas, tais como categorias.",
            'name' => 'metas',
            'campos' => static::buildMetaFormFieldsPostType('metas', 'checkbox', ['attachment', 'revision', 'nav_menu_item'])
        ];


        /*
         * SOCIAIS
         */

        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => 'Links de Redes Sociais',
            'info' => "Informe abaixo os links para as redes sociais que você deseja compartilhar.",
            'campos' => []
        ];


        foreach (self::getSociais() as $key => $value) {
          self::$optionsFields[0]['forms'][count(self::$optionsFields[0]['forms']) - 1]['campos'][] = self::createCampoMeta($value, 'text', \ucfirst($value), ['class' => "$value social"]);
        }

        /*
         * FAVICON
         */
        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => "Favicon",
            'info' => "<p>Se você possui todos os ícones com nome do arquivo no formato <i>180x180.png</i> (exceto o <i>.ico</i> e o <i>.webmanifest</i> que devem ter o nome <b>favicon.ico</b> e <b>site.webmanifest</b> respectivamente), então você pode especificar a <b>URL raiz para ícones</b>, local onde estes ícones estão. Mas use isto somente se você tiver todos os ícones! Se você não colocou os arquivo no formato acima, ou não deseja todos os tamanhos de icones, então você deve preencher os campos específicos para cada tamanho que deseja com a URL.<br />Para gerar os ícones e obter informações sobre as cores acesse <a href='https://realfavicongenerator.net' target='_blank'>https://realfavicongenerator.net</a>.<p><p>Lembre-se de editar o arquivo <b>browserconfig.xml</b> e <b>site.webmanifest</b>, editando a URL dos arquivo, do contrário NÃO funcionará.</p><p>É importante preencher as cores de qualquer forma.</p>",
            'campos' => [
                self::createCampoMeta('favicon_url_raiz', 'text', 'URL raiz dos ícones', ['class' => 'link']),
                self::createCampoMeta('favicon_icon_url', 'text', 'favicon.ico', ['class' => 'link']),
                self::createCampoMeta('webmanifest', 'text', 'site.webmanifest', ['class' => 'link']),
                self::createCampoMeta('browserconfig', 'text', 'browserconfig.xml', ['class' => 'link']),
                self::createCampoMeta('safari_pinned_tab', 'text', 'safari-pinned-tab.svg', ['class' => 'link']),
                self::createCampoMeta('msapplication_TileColor', 'color', 'msapplication-TileColor'),
                self::createCampoMeta('theme_color', 'color', 'theme-color'),
                self::createCampoMeta('mask_icon', 'color', 'mask-icon')
            ]
        ];

        $sizes = ["512x512", "256x256", "180x180", "144x144", "32x32", "16x16"];
        sort($sizes);

        foreach ($sizes as $key => $value) {
          self::$optionsFields[count(self::$optionsFields) - 1]['forms'][count(self::$optionsFields[0]['forms']) - 1]['campos'][] = self::createCampoMeta("ico_t$value", 'image', "$value.png");
        }

        /*
         * Manual da Marca IASD
         */
        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => "Barra da Marca IASD",
            'info' => "Se você deseja usar este tema para algo voltada à igreja adventista do Sétimo Dia, será necessário, para conformidade com a marca, ativar a barra lateral. <a href='https://downloads.adventistas.org/pt/comunicacao/logomarcas/nova-logo-e-manual-pratico-de-marca-da-iasd/' target='_blank'>Clique aqui</a> para mais informações.",
            'campos' => [
                self::createCampoMeta('iasd_bar', 'checkbox', 'Barra da marca IASD'),
                self::createCampoMeta('iasd_bar_color', 'color', 'Cor da Barra'),
                self::createCampoMeta('iasd_color', 'color', 'Cor do Logotipo'),
                self::createCampoMeta('iasd_link', 'text', 'Link do Logotipo IASD', ['class' => 'link'])
            ]
        ];

        self::$optionsFields[] = [
            'page_title' => 'STiny Models',
            'menu_title' => 'STiny Models',
            'capability' => 'administrator',
            'menu_slug' => 'stiny_theme_models_page',
            'function' => [static::className(), 'stiny_theme_models_page'],
            'parent_slugORicon_url' => 'themes.php',
            'forms' => []
        ];

        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => "About 1+3 Columns",
            'info' => "Informe aqui as informações que devem ser exibidas no about de 1+3 colunas.",
            'campos' => [
                self::createCampoMeta('about_13_show', 'checkbox', 'Exibir Barra About 1+3'),
                self::createCampoMeta('about_13_1', 'textarea', 'Coluna Principal'),
                self::createCampoMeta('about_13_2', 'textarea', 'Primeira Coluna'),
                self::createCampoMeta('about_13_3', 'textarea', 'Segunda Coluna'),
                self::createCampoMeta('about_13_4', 'textarea', 'Terceira Coluna'),
            ]
        ];

        self::$optionsFields[] = [
            'page_title' => 'STiny Rich Snippets',
            'menu_title' => 'STiny Rich Snippets',
            'capability' => 'administrator',
            'menu_slug' => 'stiny_theme_rich_snippets',
            'function' => [static::className(), 'stiny_theme_rich_snippets'],
            'parent_slugORicon_url' => 'themes.php',
            'forms' => []
        ];

        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => "Publicador",
            'info' => "Informe aqui quem é o publicador",
            'campos' => [
                self::createCampoMeta('rich_snippets_org_logo', 'image', 'Logotipo 600x60px'),
                self::createCampoMeta('rich_snippets_org_name', 'text', 'Nome')
            ]
        ];


        self::$optionsFields[count(self::$optionsFields) - 1]['forms'][] = [
            'title' => 'Modelo de Rich Padrão',
            'info' => "Informe o modelo padrão de richs para cada tipo de post",
            'name' => 'rich_defaults',
            'campos' => static::buildMetaFormFieldsPostType('rich_defaults', [
                self::createCampoMeta('rich_post_default', 'select', 'Padrão', ['options' => [
                        'Nenhum/Automático' => '',
                        'WebSite' => 'site',
                        'Artigo' => 'Article',
                        'Artigo de Notícia' => 'NewsArticle',
                        'Artigo de Blog' => 'BlogPosting',
            ]]),
                    ], ['attachment', 'revision', 'nav_menu_item'])
                /* static::buildMetaFormFieldsPostType('rich_defaults', [
                  self::createCampoMeta('rich_default', 'radio', 'Nenhum', ['value' => '']),
                  self::createCampoMeta('rich_default', 'radio', 'WebSite', ['value' => 'site']),
                  self::createCampoMeta('rich_default', 'radio', 'Artigo', ['value' => 'Article']),
                  self::createCampoMeta('rich_default', 'radio', 'Artigo de Notícia', ['value' => 'NewsArticle']),
                  self::createCampoMeta('rich_default', 'radio', 'Artigo de Blog', ['value' => 'BlogPosting'])
                  ], ['attachment', 'revision', 'nav_menu_item']) */
        ];
      }

      return self::$optionsFields;
    }

    public static function Publicador() {
      $v = trim(static::getVar('rich_snippets_org_name'));
      return (strlen($v) > 3) ? $v : $_SERVER['HTTP_HOST'];
    }

    static function showThemeOG() {
      return !esc_attr(\get_option(self::PREFIX . 'disable_opengraph'));
    }

    static function className() {
      return __CLASS__;
    }

    /*
     *
     */

    static function stiny_theme_admin_page($index = 0) {
      $mpte = (class_exists("jeancarloem\Wordpress\plugins\MPTEditor\MPTEditor"));
      $mpt = (class_exists("MultiPostThumbnails"));
      $og = ((is_callable('\get_series_order')) && (is_callable('\get_objects_in_term')));
      ?>
      <div class="wrap">
        <div class='requisits'>
          <h3>Requisitos</h3>
          <div class='<?php echo $mpt ? 'existe' : ''; ?>'><a href='https://br.wordpress.org/plugins/multiple-post-thumbnails/' target='_blank'>Multiple Post Thumbnails</a>.</div>
          <div class='<?php echo $mpte ? 'existe' : ''; ?>'><a href='https://br.wordpress.org/plugins/multiple-post-thumbnails/' target='_blank'>Multiple Post Thumbnails Editor</a>.</div>
          <div class='<?php echo $og ? 'existe' : ''; ?>'><a href='https://br.wordpress.org/plugins/organize-series/' target='_blank'>Organize Séries</a>.</div>
        </div>
      </div>
      <?php
      static::buildPageAdnBox(static::getOptions()[is_numeric($index) ? $index : 0]['forms']);
    }

    /*
     *
     */

    static function stiny_theme_models_page($index = 0) {
      static::stiny_theme_admin_page(1);
    }

    /*
     *
     */

    static function stiny_theme_rich_snippets($index = 0) {
      static::stiny_theme_admin_page(2);
    }

    static function get_wp_head() {

      function __get_head() {
        \ob_start();
        \wp_head();
        return \ob_get_clean();
      }

      if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return preg_replace('|[ ]*\r?\n[ ]*\t?[ ]*|i', "\n\t", preg_replace('|[ ]{2,20}|i', '', preg_replace("#'[ ]*//#i", "'https://", preg_replace('#"[ ]*//#i', '"https://', preg_replace('|http://|i', 'https://', __get_head())))));
      }

      return __get_head();
    }

    static function get_wp_content() {

      function __get_content() {
        \ob_start();
        \the_content('Continue...');
        return \ob_get_clean();
      }

      if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return preg_replace('|[ ]*\r?\n[ ]*\t?[ ]*|i', "\n\t", preg_replace('|[ ]{2,20}|i', '', preg_replace("#'[ ]*//#i", "'https://", preg_replace('#"[ ]*//#i', '"https://', preg_replace('|http://|i', 'https://', __get_content())))));
      }

      return __get_content();
    }

    /*
     *
     */

    static function getPostSerieId(int $post_id = null) {
      global $post;
      $r = \wp_get_post_series(empty($post_id) ? $post->ID : $post_id);

      if (count($r) === 1) {
        return $r[0];
      }

      return (empty($r)) ? false : $r;
    }

    /*
     * RETORNA UM VETOR COM TODAS OS POST DA SERIE
     */

    static function getSeriePosts(int $serie_id = null, int $post_id = null) {
      if ((!is_callable('\get_series_order')) || (!is_callable('\get_objects_in_term'))) {
        return null;
      }

      global $post;

      $post_id = empty($post_id) ? $post->ID : $post_id;
      $serie_id = empty($serie_id) ? static::getPostSerieId($post_id) : $serie_id;

      return \get_series_order(\get_objects_in_term($serie_id, 'series'), 0, $serie_id);
    }

    /*
     *
     */

    static function getNextPostOfSerieFromPost(int $post_id = null) {
      if ((!is_callable('\get_series_order')) || (!is_callable('\get_objects_in_term'))) {
        return null;
      }

      global $post;

      $post_id = empty($post_id) ? $post->ID : $post_id;
      $serie = static::getSeriePosts(null, $post_id);

      foreach ($serie as $key => $item) {
        if ($item['id'] === $post_id) {
          return ($key === count($serie) ) ? null : $serie[$key + 1]['id'];
        }
      }

      return null;
    }

    /*
     *
     */

    static function getPrevPostOfSerieFromPost(int $post_id = null) {
      if ((!is_callable('\get_series_order')) || (!is_callable('\get_objects_in_term'))) {
        return null;
      }

      global $post;

      $post_id = empty($post_id) ? $post->ID : $post_id;
      $serie = static::getSeriePosts(null, $post_id);

      foreach ($serie as $key => $item) {
        if ($item['id'] === $post_id) {
          return ($key === 0) ? null : $serie[$key - 1]['id'];
        }
      }

      return null;
    }

    static function showIasdBar() {
      return (STinyTheme::getVar('iasd_bar') || preg_match('#(.+\.)*(a7d|iasd|adventistas?)\..+#i', $_SERVER['HTTP_HOST']));
    }

    static function getWPMenuSemUL($nome, $outro = true) {
      $options = array(
          'echo' => false,
          'container' => false,
          'theme_location' => $nome,
      );
      $a = wp_nav_menu($options);
      $a = preg_replace(array(
          '#<ul[^>]*>#i',
          '#<div[^>]*>#i',
          '#</ul>#i',
          '#</div>#i'
              ), '', $a);
      $a = preg_replace('#\r?\n#', '', $a);
      $a = preg_replace('#"#', "'", $a);

      if ($outro) {
        $a = preg_replace('# class=\'#i', ' class=\'outro_menu ', $a);
      }

      return $a;
    }

    /*
     *
     */

    public static function thumb_personalizeEnabled() {
      return (!static::getVar('imagem_macro_por_post') && static::getMeta('imagem_macro_por_post'));
    }

    /*
     *
     */

    public static function thumb_imagem_data($key) {
      return static::thumb_personalizeEnabled() ? static::getMeta($key) : static::getVar($key);
    }

    /*
     *
     */

    public static function imagemMax($ignoreTipo = false) {
      $V = static::thumb_imagem_data('imagem_macro');

      if ($ignoreTipo) {
        return ($V === 'max');
      }

      return (($V === 'max') || ($V === 'maxcover') || ($V === 'fitheight') );
    }

    /*
     *
     */

    public static function imagemMaxCover() {
      return (static::thumb_imagem_data('imagem_macro') === 'maxcover');
    }

    /*
     *
     */

    public static function imagemNormal() {
      $V = static::thumb_imagem_data('imagem_macro');
      return (($V === '') || ($V === 'normal'));
    }

    /*
     *
     */

    public static function imagemFitHeight() {
      $V = static::thumb_imagem_data('imagem_macro');
      return ($V === 'fitheight');
    }

    /*
     *
     */

    public static function imagemFitHeightValue() {
      return static::thumb_imagem_data('imagem_macro_cover_height');
    }

    /*
     *
     */

    public static function imagemThumbOverload() {
      return static::thumb_imagem_data('imagem_macro_sob_menu');
    }

  }

}