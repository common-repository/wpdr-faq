<?php

class FAQ_tinyMCE
{

    public function wpfaq_addbuttons()
    {

        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;

        if (get_user_option('rich_editing') == 'true'):
            add_filter( 'mce_external_plugins', array( $this, 'add_wpfaq_tinymce_plugin' ));
            add_filter( 'mce_buttons', array( $this, 'register_wpfaq_button' ));
        endif;
    }

    public function register_wpfaq_button( $buttons )
    {
        array_push( $buttons, 'separator', 'wpfaq');
        return $buttons;
    }

    public function add_wpfaq_tinymce_plugin( $plugin_array )
    {
        $plugin_array['wpfaq'] = PLUGINURL . 'js/editor_plugin.js';
        return $plugin_array;
    }

}
$FAQ_TM = new FAQ_tinyMCE();
add_action('init', array( $FAQ_TM, 'wpfaq_addbuttons' ));

?>