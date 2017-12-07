(function($) {
  var recentUrls, urlTest, whitelist, whitelisted;
  urlTest = /^(?:http|https|ftp):\/\/([^\/]+)/;
  recentUrls = {};
  whitelist = [/^.*\.navigationnorth\.com$/];
  whitelisted = function(hostname) {
    return _.any(whitelist, function(matcher) {
      return matcher.test(hostname);
    });
  };
  $(document).on('click', 'a[href]', function(e) {
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
              $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-header"></div><div class="modal-body"></div><div class="modal-footer"><button class="btn btn-primary btn-white" data-dismiss="modal" aria-hidden="true">No</button><button class="btn btn-primary btn-yellow" id="dataConfirmOK">Proceed</button></div></div>');
      }
      var $html = '<h4>You are about to leave the NMPED website.</h4>'
      $html += '<p>The link you clicked is NOT part of the New Mexico Public Education website.</p>'
      $html += '<p class="href">' + href + '</p>'
      $html += '<p>Would you like to proceed?</p>'
      $('#dataConfirmModal').find('.modal-body').html($html)
      $('#dataConfirmOK').attr('data-href', href)
      $('#dataConfirmModal').modal({show:true})
      return false;
    }
    return true;
  });
  $(document).on('click', '#dataConfirmModal #dataConfirmOK', function(e){
      var target = $(this).attr('data-href')
      recentUrls[target] = true;
      window.location = target
    });
})(jQuery);