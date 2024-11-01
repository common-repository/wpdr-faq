<?php
class FAQ
{

    private $skins = array( 'blue', 'cyan', 'green', 'orange', 'red', 'purple', 'black', 'white' );

    public function __construct()
    {
    }

    /**
     * Register "faq" as a new post type
     * @return void
     */

    public function register_faq()
    {
        $labels = array(
            'name' => _x('Entries', 'post type general name'),
            'singular_name' => _x('Entry', 'post type singular name'),
            'add_new' => _x('New entry', 'product'),
            'add_new_item' => __('Add entry'),
            'edit_item' => __('Edit entry'),
            'new_item' => __('New entry'),
            'view_item' => __('View entry'),
            'search_items' => __('Search entries'),
            'not_found' => __('No entries found'),
            'not_found_in_trash' => __('No entries found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => 'FAQ',
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => PLUGINURL . 'images/icon-faq.png',
            'supports' => array('title', 'editor', 'faq_groups'),
        );
        register_post_type('faq', $args);
    }

    /**
     * Register a custom term for grouping the FAQ entries
     * @return void
     */

    public function register_groups()
    {
        $labels = array(
            'name' => _x('Groups', 'taxonomy general name'),
            'singular_name' => _x('Group', 'taxonomy singular name'),
            'search_items' => __('Search Groups'),
            'all_items' => __('All Groups'),
            'parent_item' => __('Parent Group'),
            'parent_item_colon' => __('Parent Group:'),
            'edit_item' => __('Edit Group'),
            'update_item' => __('Update Group'),
            'add_new_item' => __('Add New Group'),
            'new_item_name' => __('New Group Name'),
            'menu_name' => __('FAQ Groups')
        );

        register_taxonomy('faq_groups', array('faq'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => false,
        ));
    }

    /**
     * Check if the current page/post contain the FAQ tag
     * @return bool|int
     */

    private function _is_faq()
    {
        global $post;
        if($post->post_content):
            $is = strpos($post->post_content, '[wpfaq]');
        endif;

        if($is === false)
            return false;
        else
            return true;
    }

    /**
     * Add the front end styles
     * @return void
     */

    public function add_faq_styles()
    {
        if($this->_is_faq()):
            wp_enqueue_style('faq.global', PLUGINURL . 'css/global.css');
        endif;
    }

    /**
     * Add the backend styles
     * @return void
     */

    public function add_backend_styles()
    {
        wp_enqueue_style('faq.picker', PLUGINURL . 'css/backend.css');
    }

    /**
     * Add the backend scripts
     * @return void
     */

    public function add_backend_scripts()
    {
        wp_enqueue_script('faq.backend', PLUGINURL . 'js/backend.js', array( 'jquery', 'quicktags', 'jquery-ui-core', 'jquery-ui-sortable' ));
    }

    /**
     * Add the front end scripts
     * @return void
     */

    public function add_faq_scripts()
    {
        if($this->_is_faq()):
            wp_enqueue_script('wpfaq', PLUGINURL . 'js/faq.js', array( 'jquery' ));
        endif;
    }

    public function get_terms()
    {
        return get_terms( 'faq_groups', array( 'hide_empty' => true ));
    }


    public function get_questions( $term_slug )
    {
        $response = array();
        $questions = new WP_Query( array(
            'post_type' => 'faq',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'paged' => 1,
            'tax_query' => array(
		        array(
			        'taxonomy' => 'faq_groups',
			        'field' => 'slug',
			        'terms' => $term_slug
                )
            )
        ));
        if($questions->have_posts()):
            $i = 1;
            while($questions->have_posts()): $questions->the_post(); global $post;
                ($i == $questions->post_count) ? $class = ' last' : $class = '';
                $response[$i-1]['ID'] = $post->ID;
                $response[$i-1]['post_name'] = $post->post_name;
                $response[$i-1]['post_title'] = $post->post_title;
                $response[$i-1]['post_content'] = wpautop( $post->post_content );
                $response[$i-1]['class'] = $class;
                $i++;
            endwhile;
            wp_reset_postdata();
        endif;
        return $response;
    }

    public function get_default_skin()
    {
        $default = get_option('_faq_default_skin');
        if( !in_array( $default, $this->skins )) $default = 'blue';
        return $default;
    }

    /**
     * Screen Meta
     * @param array $array
     * @return array
     */

    public function add_faq_picker_tab( $array ){
		$array[] = array(
			'id' => 'faq_skin_picker',
			'title' => 'FAQ Color Schemes',
			'screen' => array( 'edit-faq', 'faq' ),
		);
		return $array;
	}

    public function faq_skins_picker(){
        $default = $this->get_default_skin();
		$html = '<h5>Change the FAQ Skin</h5>';
        $html .= '<form method="post" action="">';
            $html .= wp_nonce_field( PLUGINPATH , 'faq_update_nonce', true, false );
            $html .= '<ul id="faq-picker">';
                foreach( $this->skins as $skin ):
                    if( $default == $skin ) $current = ' current'; else $current = '';
                    $html .= '<li class="' . $skin . '-skin' . $current . '">';
                        $html .= '<div class="skin-button"></div>';
                    $html .= '</li>';
                endforeach;
            $html .= '</ul>';
            $html .= '<div class="clearer"></div>';
            $html .= '<input type="button" class="button-secondary" id="update-faq-default-skin" value="Update Skin" />';
            $html .= '<a href="http://wordpressdoneright.com" target="_blank" class="color-picker-ad">WP-FAQ Plugin brought to you by WordpressDoneRight</a>';
            $html .= '<img src="' . get_bloginfo('wpurl') . '/wp-admin/images/wpspin_light.gif" alt="" id="update-faq-loader" />';
        $html .= '</form>';
        $html .= '<div class="clearer"></div>';
		echo json_encode( array( 'status' => 'success', 'html' => $html ));
		die();
	}

    public function update_default_skin()
    {
        if ( empty($_POST) || !wp_verify_nonce( $_POST['wp_nonce'], PLUGINPATH )):
            die( json_encode( array( 'status' => 'error', 'message' => 'Invalid Nonce.' )));
        else:
            $skin = explode( '-', $_POST['skin'] );
            if( in_array( $skin[0], $this->skins )):
                update_option( '_faq_default_skin', $skin[0] );
            else:
                die( json_encode( array( 'status' => 'error', 'message' => 'Invalid Color Scheme!' )));
            endif;
            die( json_encode( array( 'status' => 'success' )));
        endif;
        die();
    }

    public function add_dashboard_widget() {
        add_meta_box( 'wpfaq_widget', 'Premium Wordpress Support', array( $this, 'wpfaq_widget' ), 'dashboard', 'side', 'high' );
    }

    public function wpfaq_widget()
    {
        echo '
            <div class="wpfaq_widget">
                <a href="http://wordpressdoneright.com" target="_blank" class="left"><img src="' . PLUGINURL . 'images/dashboard-left.png" alt="Premium Wordpress Support" /></a>
                <a href="http://wordpressdoneright.com" target="_blank" class="right"><img src="' . PLUGINURL . 'images/dashboard-right.png" alt="Premium Wordpress Support" /></a>
                <div class="clearer"></div>
            </div>';
    }


}

$FAQ = new FAQ;
add_action( 'init', array( $FAQ, 'register_faq' ));
add_action( 'init', array( $FAQ, 'register_groups' ));

add_action( 'wp_print_styles', array( $FAQ, 'add_faq_styles' ));
add_action( 'wp_enqueue_scripts', array( $FAQ, 'add_faq_scripts' ));

// Backend Stuff
add_action( 'admin_head', array( $FAQ, 'add_backend_styles' ));
add_action( 'admin_head', array( $FAQ, 'add_backend_scripts' ));

// Extend Screen Meta
add_filter( 'extend_screen_meta',array( $FAQ, 'add_faq_picker_tab'));
add_action( 'wp_ajax_get-meta-faq_skin_picker', array( $FAQ, 'faq_skins_picker'));
add_action( 'wp_ajax_update_default_skin', array( $FAQ, 'update_default_skin' ));

add_action('wp_dashboard_setup', array( $FAQ, 'add_dashboard_widget' ));

?>