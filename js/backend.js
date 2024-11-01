jQuery(document).ready(function(){

    jQuery( '#faq-picker li' ).live( 'click', function(){
        jQuery( '#faq-picker' ).find( '.current' ).removeClass( 'current' );
        jQuery( this ).addClass( 'current' );
        return false;
    });

    jQuery( '#update-faq-default-skin' ).live( 'click', function(){
        var loader = jQuery( '#update-faq-loader' );
        loader.show();
        var value = jQuery( '#faq-picker .current' ).attr( 'class' ).split( ' ' )[0];
        jQuery.ajax({ type: 'POST', url: ajaxurl, dataType: 'json', data:'action=update_default_skin&wp_nonce=' + jQuery('#faq_update_nonce').val() + '&skin=' + value, success: function( response ){
            switch( response.status ){
                case 'success': break;
                case 'error':
                    alert( response.message );
                break;
                default:
                    alert( 'Unknown Error!' );
                break;
            }
            loader.hide();
        }});
    });

    edButtons[edButtons.length] = new edButton( 'wpfaq_q', 'faq', '[wpfaq]', '', '' );

    jQuery( "#the-list" ).sortable();

});