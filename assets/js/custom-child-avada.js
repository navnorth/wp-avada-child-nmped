(function($) {
    //replace footer widget titles h4(heading tag) to p
    $(document).ready(function(){
        $("p.widget-title").each(function() {
            $(this).prop("style")["font-size"] && $(this).attr("data-inline-fontsize", !0), $(this).prop("style")["font-size"] && $(this).attr("data-inline-lineheight", !0), $(this).attr("data-fontsize", parseInt($(this).css("font-size"))), $(this).attr("data-lineheight", parseInt($(this).css("line-height")));
        });

        //replace side nav h4(heading tag for title) to h2
        $("h2.widget-title").each(function() {
            if(false != $(this).attr("data-fontsize")){
                $(this).css("font-size",$(this).attr("data-fontsize")+"px");
            }
        });

        $('.fusion-main-menu .fusion-main-menu-search a').on('keydown', function(e){
            if (e.which=="13") {
                e.preventDefault();
                $('.fusion-main-menu .fusion-main-menu-search a').trigger('click');
            }
        });
        
        if ($('.fusion-blog-archive .fusion-posts-container .fusion-flexslider .flex-direction-nav').length) {
            $('.fusion-blog-archive .fusion-posts-container .fusion-flexslider .flex-direction-nav').empty();
        }
        
        if ($('.page.has-sidebar #main #content').height() < $('.page.has-sidebar #main #sidebar').height()) {
            
            var contentHeight = $('.page.has-sidebar #main #content').height()
            var sideHeight = $('.page.has-sidebar #main #sidebar').height()
            var heightDiff = sideHeight - contentHeight
            var paddHeight = 80;
            
            if (heightDiff>60) {
                
                $('.page.has-sidebar #main').css({ 'padding-bottom': '0' })
                $('.page.has-sidebar #main #content .last-updated-date').css({ 'margin-bottom':'30px' });
                
            } else {
                var paddBottom = paddHeight-heightDiff
                var marBottom = paddBottom/2
                
                $('.page.has-sidebar #main').css({ 'padding-bottom': paddBottom + 'px' })
                $('.page.has-sidebar #main #content .last-updated-date').css({ 'margin-bottom': marBottom + 'px' });
                
            }
        }
    });

    //Missing Visible Focus fixes for when focus on image - adding tabindex
    $(window).load(function () {
        $(".fusion-image-wrapper").attr("tabindex","0");
    });

})(jQuery);