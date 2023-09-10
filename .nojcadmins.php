<?php

namespace jeancarloem\Wordpress;

use jeancarloem\Wordpress as jwp;

$este = basename(__DIR__);

$page = <<<EOF
<style>
  div.nojcadmins div.close,
  div.nojcadmins input[type='checkbox']{
    position: absolute;
    right: .1em;
    top: .1em;
    background: red;
    height: 2em;
    width: 2em;
    border: none;
    border-radius: .5em;
    cursor: pointer;
  }

  div.nojcadmins input[type='checkbox']{
    z-index: 3;
    opacity: 0;
  }

  div.nojcadmins div.close{
    zindex: 1;
    color: #fff;
    text-align: center;
    line-height: 2em;
  }

  div.nojcadmins input[type='checkbox']:checked,
  div.nojcadmins input[type='checkbox']:checked + div.close,
  div.nojcadmins input[type='checkbox']:checked + div.close + div{
    display: none;
  }
</style>
<div class='nojcadmins' style='position: fixed;left: 1em;top: 10%;right: 1em;z-index:100000;'>
  <input type='checkbox' />
  <div class='close'>X</div>
  <div style='border: .2em solid red;background: #f9f9f9;padding: 1.5em;font-size:1.3em;box-shadow: 0 0 1em #999;border-radius: .3em;'>
    <strong>{$este}</strong> necessita para funcionar corretamente do plugin '<strong>JCAdmins</strong>' que está ausente ou não ativo.
  </div>
</div>
EOF;

if (!\is_admin() && ( $GLOBALS['pagenow'] !== 'wp-login.php' )) {
  die(<<<EOF
<!DOCTYPE>
 <html>
<body>
  {$page}
</body>
EOF
  );
}

echo $page;
