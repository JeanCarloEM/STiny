<?php

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;
use jeancarloem\Wordpress\plugins\MPTEditor as mpte;

global $first_post;

if (class_exists("jeancarloem\Wordpress\plugins\MPTEditor\MPTEditor")) {
  $imgs = mpte\MPTEditor::getThumbs();
} else if (\has_post_thumbnail()) {
  $imgs = [\wp_get_attachment_image_src(\get_post_thumbnail_id($post_id), 'single-post-thumbnail')[0]];
}

$cover = jwpt\STinyTheme::imagemMaxCover();
$fitheight = jwpt\STinyTheme::imagemFitHeight();

if (count($imgs) >= 1) {
  echo '<div class="thumbnail">';

  if ((count($imgs) > 1) && ($first_post)) {
    ?>
    <div class="slideshow thumb swipe">
      <ul class='rslides'>
        <?php
        foreach ($imgs as $key => $img_url) {
          echo "<li" . (($cover || $fitheight) ? " style=\"background-image: url('$img_url') no-repeat !important;\"" : '') . ">" . ((!$cover && !$fitheight) ? "<img src='$img_url' style='background-image: url(" . '"' . $img_url . '"' . ");' />" : '') . "</li>";
        }
        ?>
      </ul>
    </div>
    <?php
  } else {
    if ($cover) {
      ?><div class='thumb cover' style="background-image: url('<?php echo $imgs[0]; ?>') !important;"></div><?php
    } else if ($fitheight) {
      ?><div class='thumb fitheight' style="background-image: url('<?php echo $imgs[0]; ?>') !important;"></div><?php
    } else {
      ?><img class='thumb' src="<?php echo $imgs[0]; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /><?php
    }
  }
  ?>
  </div><?php
} else {
  echo "&nbsp;";
}
?>