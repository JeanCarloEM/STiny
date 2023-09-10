<div class='autor'>
  <div class='avatar'>
    <?php echo \get_avatar(get_the_author_id(), '96'); ?>
  </div>

  <div class='conteudo'>
    <h4>
      <small><?php _e('Author', 'lightword'); ?></small> <a href="<?php the_author_url(); ?>"><?php the_author(); ?>
      </a>
    </h4>

    <span class='texto'>
      <?php the_author_description();
      if (!get_the_author_description()) _e('Autor sem descrição, por favor atualiza o perfil do usuário.', 'lightword'); ?>
    </span>
  </div>
</div>