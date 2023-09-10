<?php

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;
use jeancarloem\Wordpress\plugins\MPTEditor as mpte;

global $first_post;

$imgs = jwpt\STinyTheme::thumbs_post_OR_QueryPost();

if (count($imgs) > 0) {  
  $cover = jwpt\STinyTheme::imagemMaxCover();
  $fitheight = jwpt\STinyTheme::imagemFitHeight();

  echo '<div class="thumbnail">';

  $functionpass = jwpt\STinyTheme::thumb_querypost_functionpass();
  $functionpass['linkmode'] = jwpt\STinyTheme::thumb_querypost_nolink();

  if ((count($imgs) > 1) && ($first_post)) {
    $html = shortcode_sldtx_htmlmodel(
            '',
            ($cover ? 'cover' : ''),
            ($cover || $fitheight) ? jwpt\STinyTheme::imagemFitHeightValue() : '',
            7000,
            '#444',
            $functionpass
    );

    foreach ($imgs as $key => $value) {      
      shortcode_sldtx_callableAdd($html, is_string($value) ? $value : (!empty(\trim(@$value[0])) ? $value[0] : $value["url"]), @$value['link'], @$value['title'] ?? '', @$value['content_html'] ?? '', '', empty(@$value['link']) ? 'div' : 'a', $functionpass);      
    }

    echo $html;
  } else {
    if (!empty(@$value['link'])){
      echo "<a href='{$value['link']}' target='_blank'>";
    }
    
    $imgs = is_string($imgs) ? $imgs : (is_array($imgs) ? (
            is_string($imgs[0]) ? $imgs[0] : (is_array($imgs[0]) ? ($imgs[0][0] ?? $imgs[0]['url']) : '' )
            ) : '' );

    if ($cover) {
      ?><div class='thumb cover' style="background-image: url('<?php echo $imgs; ?>') !important;"></div><?php
    } else if ($fitheight) {
      ?><div class='thumb fitheight' style="background-image: url('<?php echo $imgs; ?>') !important;"></div><?php
    } else {
      ?><img class='thumb' src="<?php echo $imgs; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /><?php
    }
    
    if (!empty(@$value['link'])){
      echo "</a>";
    }
  }
  ?>
  </div><?php
} else {
  echo "&nbsp;";
}
