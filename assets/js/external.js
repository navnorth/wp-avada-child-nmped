(function($) {
  var recentUrls, urlTest, whitelist, whitelisted;
  urlTest = /^(?:http|https|ftp):\/\/([^\/]+)/;
  recentUrls = {};
  /* basic format for domains in the whitelist: /^domainname\.com$/  -- no quotes around each item in the array */
  whitelist = [
                /^plus\.google\.com$/,
                /^ped\.state\.nm\.us$/,
                /^aae\.ped\.state\.nm\.us$/,
                /^gradcohort\.ped\.state\.nm\.us$/,
                /^soap\.ped\.state\.nm\.us$/,
                /^webed\.ped\.state\.nm\.us$/,
                /^newmexicocommoncore\.org$/,
                /^apps\.ped\.state\.nm\.us$/,
                /^families\.ped\.state\.nm\.us$/,
                /^www\.dvr\.state\.nm\.us$/
              ];
  whitelisted = function(hostname) {
    return _.any(whitelist, function(matcher) {
      return matcher.test(hostname);
    });
  };
  $(document).on('click', 'a[href],area[href]', function(e) {
    var $this, checkMatches, checkOverride, href, matches, target;
    $this = $(this);
    href = $this.attr('href');
    target = $this.attr('target');
    matches = href.match(urlTest);
    checkMatches = matches && matches[1] !== window.location.host && matches[1] !== window.location.hostname;
    checkOverride = $this.hasClass('-external-no-check') || recentUrls[href] || whitelisted(this.hostname);

    if (checkMatches && !checkOverride) {
      e.preventDefault();

      if (!$('#dataConfirmModal').length) {
              $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-header"></div><div class="modal-body"></div><div class="modal-footer fusion-button-wrapper"><button class="btn btn-primary btn-white fusion-button button-flat fusion-button-round button-large button-default button-1" data-dismiss="modal" tabindex="1" aria-hidden="true">No</button><button class="btn btn-primary btn-yellow fusion-button button-flat fusion-button-round button-large button-default button-1" tabindex="2" id="dataConfirmOK">Proceed</button></div></div>');
      }

      var $html = '<h2>You are about to leave the NMPED website.</h2>'
      $html += '<p>The link you clicked is NOT part of the New Mexico Public Education website.</p>'
      $html += '<p class="href">' + href + '</p>'
      $html += '<p>Would you like to proceed?</p>'
      $('#dataConfirmModal').find('.modal-body').html($html)
      $('#dataConfirmOK').attr('data-href', href)
      $('#dataConfirmModal').modal({show:true})
      $('#dataConfirmOK').focus()
      return false;
    }
    return true;
  });
  $(document).on('click', '#dataConfirmModal #dataConfirmOK', function(e){
     $('#dataConfirmModal').modal('hide')
      var target = $(this).attr('data-href')
      /*recentUrls[target] = true;*/
      window.open(target,'_blank');
    });
  $(document).on('keydown', '#dataConfirmModal #dataConfirmOK', function(e){
    if (e.which==13) {
      e.preventDefault();
      $(this).trigger('click');
    }
  });
})(jQuery);
