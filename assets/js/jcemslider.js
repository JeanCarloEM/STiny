(function (_, w, $) {
  _.jcemslider = function (els, tms) {
    if ((Array.isArray(els)) || (typeof els === 'object')) {
      for (var i = 0; i < els.length; i++) {
        if ((els[i].classList.contains('jcemslider')) && (els[i].hasAttribute('data-slider'))) {
          tms = (isFinite(tms) && !isNaN(tms) && tms > 100) ? tms : els[i].getAttribute('js');
          tms = (isFinite(tms) && !isNaN(tms) && tms > 100) ? tms : 5000;

          if (els[i].hasAttribute('data-jcemslider-started')) {
            return;
          }

          /* IMPEDE A REINICIALIZACAO MULTIPLA */
          els[i].setAttribute('data-jcemslider-started', 1);

          els[i].addEventListener('mouseover', function () {
            this.setAttribute('mouseobre', 1);
          });

          els[i].addEventListener('mouseout', function () {
            this.removeAttribute('mouseobre');
          });

          w.setInterval(function () {
            if (this.hasAttribute('stoplider')) {
              return;
            }

            var ips = $("[data-slider='" + this.getAttribute('data-slider') + "'] > input[type=radio][ndc]");

            if (!ips) {
              return;
            }

            for (var j = 0; j < ips.length; j++) {
              if (ips[j].checked && !this.hasAttribute('mouseobre')) {
                ips[j].checked = false;
                var number = $("[data-slider='" + this.getAttribute('data-slider') + "'] > input[type=radio][ndc]").length;
                number = ((Math.abs(ips[j].getAttribute('ndc')) + 1) >= number) ? 0 : Math.abs(ips[j].getAttribute('ndc')) + 1;

                var next = $("[data-slider='" + this.getAttribute('data-slider') + "'] > input[type=radio][ndc='" + number + "']")[0];

                if (next) {
                  next.checked = true;
                }

                break;
              }
            }
          }.bind(els[i]), tms);
        }
      }
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    w.setTimeout("jcemslider(document.querySelectorAll('div.jcemslider[data-slider][js]'));", 150);
  });
})(this, window, document.querySelectorAll.bind(document));