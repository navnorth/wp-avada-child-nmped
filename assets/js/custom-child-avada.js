(function($) {
    //replace footer widget titles h4(heading tag) to p
    $(document).ready(function(){
        $("p.widget-title").each(function() {
            $(this).prop("style")["font-size"] && $(this).attr("data-inline-fontsize", !0), $(this).prop("style")["font-size"] && $(this).attr("data-inline-lineheight", !0), $(this).attr("data-fontsize", parseInt($(this).css("font-size"))), $(this).attr("data-lineheight", parseInt($(this).css("line-height")));
        });
        $('.fusion-main-menu .fusion-main-menu-search a').on('keydown', function(e){
            if (e.which=="13") {
                e.preventDefault();
                $('.fusion-main-menu .fusion-main-menu-search a').trigger('click');
            }
        });
    });

    //Missing Visible Focus fixes for when focus on image - adding tabindex
    $(window).load(function () {
        $(".fusion-image-wrapper").attr("tabindex","0");
    });


})(jQuery);