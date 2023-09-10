<?php

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;

global $dontShowSectionInnerWrapper, $dontShowMasterDivColunaBarra;

if (!$dontShowSectionInnerWrapper) {
  ?>
  
  <?php
}
if (!$dontShowMasterDivColunaBarra) {
  ?>
  </div>
<?php } ?>

<footer>
  <?php
  if (jwpt\STinyTheme::isWidgetActive(0) || jwpt\STinyTheme::isWidgetActive(1) || jwpt\STinyTheme::isWidgetActive(2)) {
    ?>
    <div class="wrapper colunabarra colunas4"><?php
      echo jwpt\STinyTheme::getFooterWidget(0);
      ?>
    </div>
    <?php
  }
  if (jwpt\STinyTheme::isWidgetActive(1)) {
    ?>
    <div class="inteiro p1">
      <div class="wrapper colunabarra"><?php
        echo jwpt\STinyTheme::getFooterWidget(1);
        ?>
      </div>
    </div>
    <?php
  }
  if (jwpt\STinyTheme::isWidgetActive(2)) {
    ?>
    <div class="inteiro p2">
      <div class="wrapper colunabarra"><?php
        echo jwpt\STinyTheme::getFooterWidget(2);
        ?>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="inteiro last">
    <div class="wrapper colunabarra colunas2">
      <div>
        <?php
        do_action('break_credits');
        echo jwpt\STinyTheme::getVar('copy') ?? '';
        ?>
      </div>
      <div style='white-space: nowrap;'><small>powered&nbsp;by&nbsp;<a href="https://jeancarloem.com" target="_blank" class="nosimbol" style='display: inline-block;'>STiny&nbsp;|&nbsp;jcem</a></small></div>
    </div>
  </div>
  <?php
  if (\has_nav_menu('bottom')) {
    ?>
    <div class="inteiro pnultimo">
      <div class="wrapper colunabarra"><?php
        \wp_nav_menu(array('theme_location' => 'bottom'));
        ?>
      </div>
    </div>
  <?php } ?>
</footer>

<?php
wp_footer();
?>
</section>
</body>
</html>