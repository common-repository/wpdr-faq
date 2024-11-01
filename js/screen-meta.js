var screen_meta = {

    links: '', hidden: false,

    build_links: function()
    {
        links = {};
		jQuery( '#screen-meta-links' ).find( '.screen-meta-custom-toggle' ).each(function(){
            var val = jQuery( this ).find( 'a' ).attr( 'href' ).replace( '#', '' ) + '-wrap';
            var id = jQuery( this ).attr( 'id' );
			links[id] = val;
		});
		return links;
    },

    toggleEvent: function( e ){
        var panel;
		e.preventDefault();
		// Check to see if we found a panel.
		if(!screen_meta.links[this.id]) return;
        panel = jQuery( '#' + screen_meta.links[this.id]);
	    if( panel.is( ':visible' ))
            screen_meta.close( panel, jQuery( this ));
        else
            screen_meta.open( panel, jQuery( this ));
    },

    open: function( panel, link ) {
        jQuery( '.screen-meta-custom-toggle' ).not(link).css( 'visibility', 'hidden' );
		jQuery( '.screen-meta-toggle' ).css( 'visibility' ,'hidden' );
        jQuery( '#screen-meta' ).show();
		panel.slideDown( 'fast', function(){
		    link.addClass( 'screen-meta-active' );
            if(!panel.find( '.ajax-content' ).size() > 0) screen_meta.get(panel);
        });
    },

    close: function( panel, link ) {
        panel.slideUp( 'fast', function(){
            link.removeClass( 'screen-meta-active' );
			jQuery( '.screen-meta-custom-toggle' ).css( 'visibility', '' );
			jQuery( '.screen-meta-toggle' ).css( 'visibility', '' );
            jQuery( '#screen-meta' ).hide();
        });
    },

    get: function( panel ){
        var id = panel.attr( 'id' ).split( '-' )[1];
        jQuery.ajax({ type: 'POST', url: ajaxurl, dataType: 'json', data:'action=get-meta-' + id, success: function( result ){
            screen_meta.append( panel, result );
        }});
    },

    append:function( panel, result ){
        var html;
		if( 'success' == result.status ){
            html = result.html;
        } else {
            html = 'No Content.';
        }
		panel.find( '.loader' ).fadeOut( function(){
		    panel.append( '<div class="ajax-content"></div>' );
			panel.find( '.ajax-content' ).hide().append( html );
			panel.find( '.ajax-content' ).slideDown();
        });
    },

    init: function()
    {
        screen_meta.links = screen_meta.build_links();
        jQuery( '.screen-meta-custom-toggle' ).click( screen_meta.toggleEvent );
        jQuery( '.screen-meta-toggle' ).bind( 'click', function(){
            if(!screen_meta.hidden) {
                jQuery( '.screen-meta-custom-toggle' ).hide();
                screen_meta.hidden = true;
            } else {
                jQuery( '.screen-meta-custom-toggle' ).show();
                screen_meta.hidden = false;
            }
        });
    }

}

jQuery(document).ready(function(){
	screen_meta.init();
});