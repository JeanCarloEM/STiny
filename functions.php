<?php

/*
  Theme Name: STiny
  Theme URI: https://lab.jeancarloem.com/wordpress/STiny
  Author: Jean Carlo de Elias Moreira
  Author URI: https://jeancarloem.com
  Description: Um tema clean e minimalista personalizável com alguns recursos avançados.
  Version: 1
  License: GNU General Public License
 */

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;

if (\class_exists('jeancarloem\Wordpress\Admins\PagesConstruct') !== true) {
  $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'jcadmins' . DIRECTORY_SEPARATOR . 'jcadmins.php';

  if (file_exists($path)) {
    require_once $path;
  }
}

if (\class_exists('jeancarloem\Wordpress\Admins\PagesConstruct') !== true) {
  require_once __DIR__ . DIRECTORY_SEPARATOR . '.nojcadmins.php';
} else {
  require_once __DIR__ . DIRECTORY_SEPARATOR . '.' . \basename(__FILE__);
}
