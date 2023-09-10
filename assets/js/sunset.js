(function ($, _, w) {
  w.nextDay = function (x) {
    var now = new Date();
    now.setDate(now.getDate() + ((x % 7) + (6 - now.getDay())) % 7);
    return now;
  };

  w.getLocation = function (func) {
    if (!_.navigator.geolocation) {
      $('body')[0].setAttribute('no-geolocation', 1);
      return false;
    }

    if (($('body')[0].hasAttribute('latitude')) && ($('body')[0].hasAttribute('longitude'))) {
      return [
        $('body')[0].getAttribute('latitude'),
        $('body')[0].getAttribute('longitude')
      ];
    }

    _.navigator.geolocation.getCurrentPosition(function (position) {
      $('body')[0].setAttribute('latitude', position.coords.latitude);
      $('body')[0].setAttribute('longitude', position.coords.longitude);

      if (typeof func === 'function') {
        func.bind(this)(w.getLocation(null));
      }
    });

    return true;
  };

  w.getSunsetTimes = function (lat, lng, ano, mes, dia) {
    var obj = this;
    _.jQuery.getJSON.bind(this)("https://api.sunrise-sunset.org/json?lat=" + lat + "&lng=" + lng + "&date=" + ano + "-" + mes + "-" + dia, function (data) {
      var x = ((new Date(mes + "/" + dia + "/" + ano + ' ' + data.results.sunset + ' UTC')).toLocaleTimeString() + " ").match(/((0|1)?\d):(\d{2}):(\d{2})(.+)/i);

      if (x[5].trim().toLowerCase() === 'pm') {
        x[1] = parseInt(x[1]) + 12;
      }

      /* CONVERTENDO PARA O HORARIO LOCAL */
      data.results.sunset = x[1] + ":" + x[3];

      if (obj instanceof _.Node) {
        if (obj.tagName === 'input') {
          obj.setAttribute('value', data.results.sunset);
        } else {
          obj.innerHTML = data.results.sunset;
        }
      } else

      if (Array.isArray(obj)) {
        obj.push(data.results.sunset);
      } else

      if (typeof obj === 'object') {
        obj.value = data.results.sunset;
      }
    });
  };

  document.addEventListener("DOMContentLoaded", function () {
    var els = $(".sunset[data-day]");

    for (var i = 0; i < els.length; i++) {
      item = els[i];
      var diaSemana = item.getAttribute('data-day');

      if (isFinite(diaSemana) && (!isNaN(diaSemana))) {
        diaSemana = w.nextDay(diaSemana);

        if ((item.hasAttribute('lat')) && (item.hasAttribute('lng'))) {
          w.getSunsetTimes.bind(item)(item.getAttribute('lat'), item.getAttribute('lng'), diaSemana.getFullYear(), diaSemana.getMonth(), diaSemana.getDate());
        } else {
          w.getLocation.bind(item)(function (resp) {
            w.getSunsetTimes.bind(item)(resp[0], resp[1], diaSemana.getFullYear(), diaSemana.getMonth(), diaSemana.getDate());
          });
        }
      }
    }
  });

})(document.querySelectorAll.bind(document), this, window);