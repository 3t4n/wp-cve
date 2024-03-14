<?php
class EPS_Super_Search_Widget extends WP_Widget {

    /** constructor */
    function __construct() {
        $widget_ops = array('classname' => 'EPS_Super_Search_Widget', 'description' => 'Dynamic search box for custom post types.' );
        $this->WP_Widget('eps_super_search', 'Super Search', $widget_ops);
        add_filter('template_include',  array( $this, 'template_redirect')); 
        add_filter('pre_get_posts',     array( $this, 'search_filter'));
    }

    
    /**
     * 
     * UPDATE
     * 
     * Updates the widget.
     * 
     */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
        
        if( !isset($new_instance['user_selectable'])  ) $instance['user_selectable'] = false;

        foreach( $new_instance as $k => $v ) {
            if( $k == 'in_post_type') {
                $instance[$k] = implode(',', $v);
            } else {
                $instance[$k] = sanitize_text_field($new_instance[$k]);
            }
        }
        return $instance;
    }




    /**
     * 
     * FORM
     * 
     * The Widget Input HTML
     * 
     */
    function form($instance) {
        foreach( $instance as $key => $value )
        	esc_attr($instance[$key]);

        $post_types = $this->get_post_types();
        extract( $instance );
        $in_post_type = ( isset($in_post_type) ) ? explode(',', $in_post_type ) : array();
        require( EPS_SS_PATH . 'templates/admin.php');
    }


   
    function get_post_types()
    {
        $post_types = get_post_types( array(
            'publicly_queryable' => true
        ), 'objects' );

        $post_types = apply_filters('eps-super-search-post-types', $post_types );

        return $post_types;
    }

    /**
     * 
     * WIDGET
     * 
     * The widget Output HTML.
     * 
     */
    function widget($args, $instance) {
        extract( $instance );
        extract( $args );

        $post_types = $this->get_post_types();


        echo $before_widget;
        require( EPS_SS_PATH . '/templates/widget.php');
        echo $after_widget;
        
        add_action('wp_footer', array($this, 'scripts') );
    }

    /**
     * 
     * SCRIPTS
     * 
     * Outputs the necessary scripts for the Post Type selector select to work.
     * 
     */
    function scripts() {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $('#super_searchform select#post-type-selector').live('change', function(e) {
                    e.preventDefault();
                    if( $('#super_searchform input#s').val() != '' && $('#super_searchform select#post-type-selector').val() != '') {
                        $('#super_searchform').submit();
                    } else {
                        $('#super_searchform input#s').focus();
                    }
                })
            });
        </script>
        <?php
    }
    
    
    /**
     * 
     * SEARCH FILTER
     * 
     * Adds a 'post_type' filter to the search query, based on the inputs.
     * 
     * 
     */
    function search_filter($query) {
        if( !isset($_GET['post_type']) || !$query->is_search ) return $query;

        $data = filter_input_array(INPUT_GET, array(
            'post_type'      => FILTER_SANITIZE_STRING
        ));

        if ( empty($data['post_type']) ) { $data['post_type'] = 'any'; }

        $query->set('post_type', explode(',', $data['post_type']));
        return $query;
    }
    
    /**
     * 
     * TEMPLATE REDIRECT 
     * 
     * Redirects to user inputted search template, or falls back if it's not found.
     * Allows you to set a specific template to show your results.
     * 
     */
    function template_redirect( $template ) {
        global $wp_query;   
        if( !$wp_query->is_search || !isset($_GET['archive_template']) ) return $template;

        if( isset($_GET['tag-search']) )
        {
            $template = locate_template( array(
                locate_template( $_GET['archive_template'] ),
                'search.php',
                'archive.php',
                'index.php'
            ));
        }
        return $template;

    }

  
    
   
} 

add_action('widgets_init', create_function('', 'return register_widget("EPS_Super_Search_Widget");'));
?>