jQuery(document).ready(function(a){
    jQuery(".side-nav-left .side-nav li").each(function(){
        jQuery(this).find("> .children").length&&(jQuery(".rtl").length?jQuery(this).prepend('<span class="arrow"></span>'):jQuery(this).append('<span class="arrow"></span>'))
    }),
    jQuery(".side-nav-right .side-nav li").each(function(){
        jQuery(this).find("> .children").length&&(jQuery("body.rtl").length?jQuery(this).append('<span class="arrow"><i class="fa fa-caret-right" aria-hidden="true"></i></span>'):jQuery(this).prepend('<span class="arrow"><i class="fa fa-caret-right" aria-hidden="true"></i></span>'))
    }),
    jQuery(".side-nav .current_page_item").each(function(){
        jQuery(this).find("> .children").length&&jQuery(this).find("> .children").show("slow")
        if (jQuery(this).find("> .children").is(':visible')) {
            jQuery(this).find('> span.arrow').html('<i class="fa fa-caret-down" aria-hidden="true"></i>')
        }
    }),
    jQuery(".side-nav .current_page_item").each(function(){
        jQuery(this).parent().hasClass("side-nav")&&jQuery(this).find("> .children").show("slow")
        jQuery(this).parent().hasClass("children")&&jQuery(this).parents("ul").show("slow")
        if (jQuery(this).parent().hasClass("children")) {
            jQuery(this).find('> span.arrow').html('<i class="fa fa-caret-down" aria-hidden="true"></i>')
        }
    }),
    jQuery(".side-nav .current_page_ancestor, .side-nav .current_page_parent").each(function(){
        jQuery(this).find('> span.arrow').html('<i class="fa fa-caret-down" aria-hidden="true"></i>')
    })
}),
jQuery(window).load(function(){
    "Hover"===avadaSideNavVars.sidenav_behavior?
        jQuery(".side-nav li span.arrow").on("click",function(a){
            if (jQuery(this).parent(".page_item_has_children").length&&(jQuery(this).parent().find("> .children").length&&!jQuery(this).parent().find("> .children").is(":visible"))) {
                    jQuery(this).html('<i class="fa fa-caret-down" aria-hidden="true"></i>')
                    jQuery(this).parent().find("> .children").stop(!0,!0).slideDown("slow")
                }else {
                    jQuery(this).html('<i class="fa fa-caret-right" aria-hidden="true"></i>')
                    jQuery(this).parent().find("> .children").stop(!0,!0).slideUp("slow")
                }
               jQuery(this).parent(".page_item_has_children.current_page_item").length
               return!1
            }):
            jQuery(".side-nav li").hoverIntent({
                over:function(){
                    jQuery(this).find("> .children").length&&jQuery(this).find("> .children").stop(!0,!0).slideDown("slow")},
                out:function(){
                    0===jQuery(this).find(".current_page_item").length&&!1===jQuery(this).hasClass("current_page_item")&&jQuery(this).find(".children").stop(!0,!0).slideUp("slow")
            },
            timeout:500
    })
});