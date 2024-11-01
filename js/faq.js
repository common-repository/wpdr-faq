jQuery(document).ready(function(){

    jQuery('#faq-container .toggle').hover(function(){
        jQuery(this).addClass('hover');
    }, function(){
        jQuery(this).removeClass('hover');
    });

    jQuery('#faq-container .toggle').mousedown(function(){
        jQuery(this).addClass('focus');
    }).mouseup(function(){
        jQuery(this).removeClass('focus');
    }).mouseout(function(){
        jQuery(this).removeClass('focus');
    });

    jQuery('#faq-container .toggle').click(function(){
        var parent = jQuery(this).parent();
        var content = parent.find('.quest-content');
        if(parent.hasClass('collapsed')){
            parent.removeClass('collapsed').addClass('expanded');
            content.slideDown('normal');
        } else {
            parent.removeClass('expanded').addClass('collapsed');
            content.slideUp('normal');
        }
        return false;
    });

    jQuery('#faq-container .faq-nav a').click(function() {
        if(jQuery(this).hasClass('current')) return false;
        var current = jQuery(this).attr('class').split(' ')[0];
        switch (current) {
            case 'all':
                jQuery('.faq-questions').slideUp('normal', function(){
                    jQuery('.faq-questions .topics').show();
                    jQuery('.faq-questions').slideDown('normal');
                });
            break;
            default:
                jQuery('.faq-questions').slideUp('normal', function(){
                    jQuery('.faq-questions .topics').hide();
                    jQuery('#faq-topics-' + current).show();
                    jQuery('.faq-questions').slideDown('normal');
                });
                break;
        }
        jQuery('#faq-container .faq-nav .current').removeClass('current');
        jQuery(this).addClass('current');
        return false;
    });


});