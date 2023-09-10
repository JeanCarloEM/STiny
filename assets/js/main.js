(function ($, w) {
  w.rand = function (max) {
    var r = Math.floor((1 + Math.random()) * 0x10000);
    return ((isFinite(max) && !isNaN(max)) && max > 0) ? r % (max + 1) : r;
  };

  w.guid = function () {
    function s4() {
      return  w.rand()
              .toString(16)
              .substring(1);
    }
    return String.fromCharCode((w.rand(1) === 1) ? 65 : 97) + w.rand(26) + s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
  };

  Node.prototype.getId = function () {
    var id = $(this).attr('id');

    if (!id) {
      id = w.guid();
      $(this).attr('id', id);
    }

    return id;
  };

  w.downmenu = function () {
    w.downmenuFEITO = false;

    var iconHeader = $("section.body > header");
    var header = $("section.body > nav.second");
    var bar = $("section.body > nav.second ul");

    if (bar.length > 0) {
      bar.addClass("forcaroculto");

      w.setTimeout(function () {
        var resopen = header.hasClass('open');

        header.removeClass('estouro');
        header.removeClass('open');

        if (bar[0].scrollWidth > bar.width()) {
          iconHeader.addClass('estourosecond');
          header.addClass('estouro');

          if (resopen) {
            header.addClass('open');
          } else {
            w.fecharMenu($('body').attr('menuopen'));
          }
        } else {
          iconHeader.removeClass('estourosecond');
          header.removeClass('estouro');

          w.fecharMenu($('body').attr('menuopen'));
        }

        w.setTimeout(function () {
          $("section.body > header.first ul").removeClass('forcaroculto');
        }, 500);
      }, 1);
    }
  };

  w.masterheader = function () {
    w.masterheaderFEITO = false;

    var header = $("section.body > header.first");
    var bar = $("section.body > header.first ul");

    if (bar.length > 0) {
      bar.addClass("forcaroculto");

      w.setTimeout(function () {
        var resopen = bar.hasClass('open');

        bar.removeClass('estouro');
        bar.removeClass('open');

        if (bar[0].scrollWidth > bar.width()) {
          bar.addClass('estouro');
          header.addClass('estouroico');

          if (resopen) {
            bar.addClass('open');
          } else {
            w.fecharMenu($('body').attr('menuopen'));
          }
        } else {
          header.removeClass('estouroico');
          bar.removeClass('estouro');

          w.fecharMenu($('body').attr('menuopen'));
        }

        w.setTimeout(function () {
          $("section.body > header.first ul").removeClass('forcaroculto');
        }, 500);
      }, 1);
    }
  };

  w.primarymenu = function () {
    w.primarymenuFEITO = false;

    var secundario = $("section.body > nav.second ul");
    var bar = $("section.body > div.wrapper header.third nav.navmenu ul");

    if (bar.length > 0) {
      bar.addClass("forcaroculto");

      w.setTimeout(function () {
        bar.removeClass('estouro');

        if (bar[0].scrollWidth > bar.width()) {
          bar.addClass('estouro');
          secundario.addClass('show_outro_menu');
        } else {
          bar.removeClass('estouro');
          secundario.removeClass('show_outro_menu');
        }

        w.setTimeout(function () {
          $("section.body > div.wrapper header.third nav.navmenu ul").removeClass('forcaroculto');
        }, 500);
      }, 1);
    }
  };

  w.exibirEscuro = function (exibir) {
    var escuro = $(".body > .escuro");

    if (escuro.length <= 0) {
      escuro = $($(".body").prepend('<div class="escuro ocultar"></div>'));
      var escuro = $(".body > .escuro");

      $(document).on('click', function () {
        if ($('body')[0].hasAttribute('menuopen')) {
          w.toogleMenu($('body').attr('menuopen'));
        }
      });
    }

    if (exibir === true) {
      escuro.css('display', 'block');
      escuro.removeClass('ocultar');
      escuro.addClass('exibir');
    } else {
      escuro.addClass('ocultar');
      escuro.removeClass('exibir');

      w.setTimeout(function () {
        $(".body > .escuro").css('display', 'none');
      }, 400);
    }
  };

  w.fecharMenu = function (menu) {
    var e = $('#' + menu);
    e.removeClass('open');

    w.exibirEscuro();

    $("[menuid=" + menu + "]").each(function (i, item) {
      $(item).removeClass('open');
    });
  };

  w.abrirMenu = function (menu) {
    var e = $('#' + menu);
    e.addClass('open');

    e.css('display', 'block !important');

    w.exibirEscuro(true);

    $("[menuid=" + menu + "]").each(function (i, item) {
      $(item).addClass('open');
    });

    w.setTimeout(function () {
      $('body').attr('menuopen', menu);
    }, 10);
  };

  w.toogleMenu = function (id) {
    var e = $("#" + id);

    if (!e.hasClass('open')) {
      w.setTimeout(function () {
        w.abrirMenu(id);
      }, 350);
    } else {
      w.fecharMenu(id);
      $('body').removeAttr('menuopen');
    }
  };

  w.menuHandle = function (dest) {
    var menuid = $(this).attr('menuid');

    if (!menuid && dest) {
      menuid = $(dest)[0].getId();
      $(this).attr('menuid', menuid);
    }

    if (menuid) {
      w.toogleMenu(menuid);
    }
  };

  $(document).ready(function ($) {
    w.masterheaderFEITO = false;
    w.downmenuFEITO = false;
    w.primarymenuFEITO = false;

    $(w).resize(function () {
      if (!w.masterheaderFEITO) {
        w.masterheaderFEITO = true;
        w.setTimeout("window.masterheader();", 1000);
      }

      if (!w.primarymenuFEITO) {
        w.primarymenuFEITO = true;
        w.setTimeout("window.primarymenu();", 1000);
      }

      if (!w.downmenuFEITO) {
        w.downmenuFEITO = true;
        w.setTimeout("window.downmenu();", 1000);
      }
    });

    $("section.body > header div.ico.menu").on('click', function () {
      w.menuHandle.bind(this)("section.body > nav.second");
    });

    $("section.body > header div.ico.follow").on('click', function () {
      w.menuHandle.bind(this)("section.body > header.first ul");
    });

    $("article .content svg").on('click', function () {
      $(this).toggleClass('maximixed');
    });
    $("article .content img").on('click', function () {
      $(this).toggleClass('maximixed');
    });

    $("section.body > nav .lupa").on('click', function () {
      $("section.body > nav.search").toggleClass('open');
    });

    $(w).scroll(function () {
      if ($(w).scrollTop() >= ($("header.first.fixed").height() * 0.1)) {
        $("header.first").addClass('sombra');
      } else {
        $("header.first").removeClass('sombra');
      }

      if ($(w).scrollTop() >= ($("section.body").height() * 0.35)) {
        $("header.first div.topo").css('display', 'block');
      } else {
        $("header.first div.topo").css('display', 'none');
      }
    });

    w.setTimeout(function () {
      $('#carregandoPagina').hide();
      $('body > .pagebody').show(500);
      $(w).resize();

      if ($('#wpadminbar').length > 0) {
        $(['ul', 'nav', 'header', 'section']).each(function (i, item) {
          $(item).addClass('wpadminbar');
        });
      }

      if ($('.body.imagem_macro.thumb_overload').length > 0) {
        $('.body > header').addClass('thumb_overload');
        $('.body > nav').addClass('thumb_overload');
      }

      if ($('.body.imagem_macro').length > 0) {
        $('.body > header').addClass('imagem_macro');
        $('.body > nav').addClass('imagem_macro');
      }
      
      if ($('.body.iasd').length > 0) {
        $('.body > header').addClass('iasdbar');
        $('.body > nav').addClass('iasdbar');
        $('.body > * ul.social').addClass('iasdbar');
      }      

      w.setTimeout('jQuery(window).resize();', 550);
    }, 1000);
  });
})(jQuery, window);