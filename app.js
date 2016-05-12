var isMobile = {
  Android: function() {
    return navigator.userAgent.match(/Android/i);
  },
  BlackBerry: function() {
    return navigator.userAgent.match(/BlackBerry/i);
  },
  iOS: function() {
    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
  },
  Opera: function() {
    return navigator.userAgent.match(/Opera Mini/i);
  },
  Windows: function() {
    return navigator.userAgent.match(/IEMobile/i);
  },
  any: function() {
    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
  }
};

var app = function() {
  var androidUrl = 'https://play.google.com/store/apps/details?id=it.cerra.dabellowdg';
  var iosUrl = 'https://itunes.apple.com/it/app/dabello-wdg/id1096205210?l=it';

  return {
    init: function() {
      if(isMobile.iOS()) {
        window.location.href = iosUrl;

        return;
      }

      if(isMobile.Android()) {
        window.location.href = androidUrl;

        return;
      }

      setTimeout(function(){
        document.getElementById('wrapper').style.display = "block";
      }, 1000);
    }
  };
}();

app.init();
