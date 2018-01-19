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
        
        $(document).on('focus','.ai1ec-tooltip-trigger',function(){
            $(this).tooltip();
        })
        $('.ai1ec-category-filter .ai1ec-dropdown-toggle').on('keydown',function(e){
            if (e.which=="13") {
                e.preventDefault();
                $('.ai1ec-category-filter .ai1ec-dropdown-menu').toggle();
            }
        });
        $('.ai1ec-calendar-view .ai1ec-views-dropdown .ai1ec-dropdown-toggle').on('keydown',function(e){
            if (e.which=="13") {
                e.preventDefault();
                $('.ai1ec-calendar-view .ai1ec-views-dropdown .ai1ec-dropdown-menu').toggle();
            }
        });
        $('.ai1ec-calendar-view  .ai1ec-agenda-view ul.ai1ec-date-events li.ai1ec-event').on('keydown',function(e){
            if (e.which=="13") {
                e.preventDefault();
                $(this).find('.ai1ec-event-summary').toggle();
            }
        });
        $('.ai1ec-calendar .ai1ec-subscribe-container  .ai1ec-subscribe-dropdown .ai1ec-dropdown-toggle').on('keydown',function(e){
            if (e.which=="13") {
                e.preventDefault();
                $('.ai1ec-calendar .ai1ec-subscribe-container  .ai1ec-subscribe-dropdown .ai1ec-dropdown-menu').toggle();
            }
        });
    });

    //Missing Visible Focus fixes for when focus on image - adding tabindex
    $(window).load(function () {
        $(".fusion-image-wrapper").attr("tabindex","0");
    });

})(jQuery);