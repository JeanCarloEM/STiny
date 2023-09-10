<?php

/*
  Template Name: Loose
 */

namespace jeancarloem\Wordpress\Temas\STiny;

use jeancarloem\Wordpress\Temas\STiny as jwpt;
use jeancarloem\Wordpress\Admins as wpa;

global $dontShowSectionInnerWrapper, $dontShowMasterDivColunaBarra;

/* IMPEDE QUE O HEADER CRIA A COLUNABARRA PRINCIPAL  */
$dontShowSectionInnerWrapper = true;
$dontShowMasterDivColunaBarra = true;

# ONTEM O CABECALHO
require_once "header.php";

if (\have_posts()) {
  \the_post();
  \the_content();
}

# INCLUI O RODAPE
require_once "footer.php";

