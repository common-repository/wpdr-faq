<?php
class ScreenMeta
{
    private $options = array();
    public $js;

    public function add_scripts()
    {
        wp_enqueue_script( 'screen-meta', PLUGINURL . 'js/screen-meta.js' );
        wp_enqueue_style( 'screen-meta', PLUGINURL . 'css/screen-meta.css' );
    }

    public function build_tabs()
    {
        global $current_screen;
        $this->options = apply_filters( 'extend_screen_meta', $this->options );
        if (!empty( $this->options )):
            $this->js = '<script type="text/javascript">var links = jQuery("#screen-meta-links"), container = jQuery("#screen-meta");';
            foreach ($this->options as $k => $option):
                $show = false;
                if (is_array($option['screen'])):
                    if (in_array($current_screen->id, $option['screen'])):
                        $show = true;
                    endif;
                else:
                    if ($option['screen'] == $current_screen->id):
                        $show = true;
                    endif;
                endif;

                if ($show):
                    $this->js .= '
                        links.append(\'<div class="hide-if-no-js screen-meta-custom-toggle contextual-custom-link-wrap" id="contextual-' . $option['id'] . '-link-wrap"><a class="show-settings" id="contextual-' . $option['id'] . '-link" href="#contextual-' . $option['id'] . '">' . $option['title'] . '</a></div>\');
						container.prepend(\'<div class="hidden screen-custom-wrap" id="contextual-' . $option['id'] . '-wrap"><img class="loader" src="' . get_bloginfo('wpurl') . '/wp-admin/images/wpspin_light.gif" alt="" /></div>\');';
                endif;
            endforeach;
            $this->js .= '</script>';
        endif;
    }

    public function show_options()
    {
        $this->build_tabs();
        echo $this->js;
    }

}

$ScreenMeta = new ScreenMeta;
add_action( 'admin_footer', array( $ScreenMeta, 'show_options' ));
add_action( 'admin_head', array( $ScreenMeta, 'add_scripts' ));

?>