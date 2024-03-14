<?php
/**
 * Pagination Styles
 *
 * @category PagStyles
 * @package Page_Links
 */

/**
 * PagStyles Funtions
 *
 * @category PagStyles_Functions
 * @package PagStyles
 */
class SH_PagStyles_Functions
{


    /**
     * PHP5 constructor method
     *
     * @return void
     */
    public function __construct()
    {
        add_filter('wp_link_pages_args', array($this, 'add_arg_values'), 11);
        add_filter('wp_link_pages_link', array($this, 'add_link_to_current'), 12, 2);
        add_filter('wp_head', array($this, 'add_pagination_styling'));
        add_filter('the_content', array($this, 'add_ajax_pagination'), 52);
        add_action( 'wp_ajax_plp_load_page', array($this, 'plp_load_next_page') );
        add_action( 'wp_ajax_nopriv_plp_load_page', array($this, 'plp_load_next_page') );
    }

    /**
     * Add pagination styles arguments
     *
     * @param array $link_pages_args
     * @return array
     */
    public function add_arg_values($link_pages_args)
    {
        
        global $sh_page_links, $auto_paged, $singlepage, $count, $sh_autopag_functions;

        $options     = $sh_page_links->get_options();
        $page_styles = $options['pagination_styles'];

        // Get plugin defaults
        $page_styles_defaults = SH_PageLinks_PagStyles_Bootstrap::get_default_options();
        $page_styles_args = wp_parse_args($page_styles, $page_styles_defaults);
        $page_styles_args = apply_filters('wp_link_page_styles_args', $page_styles_args);

        extract($page_styles_args, EXTR_SKIP);

        $new_args = empty($link_pages_args) ? $page_styles_args : $link_pages_args;
        if ($wrapper_tag) {
            $wrapper_class = str_replace(',',' ',$wrapper_class);
            //$wrapper_id = str_replace(',',' ',$wrapper_id);
            $wrapper_tag_id     = empty($wrapper_id)
                                    ? "" : " id=\"{$wrapper_id}\"";
            $wrapper_tag_class  = empty($wrapper_class)
                                    ? "" : " class=\"{$wrapper_class}\"";
            $new_args['before'] = "<{$wrapper_tag}{$wrapper_tag_id}{$wrapper_tag_class}>"
                                    . $page_styles_args['before'];
            $new_args['after']  = $page_styles_args['after'] . "</{$wrapper_tag}>";
        }

        $new_args['link_before'] = $page_styles_args['link_before'];
        if ($link_wrapper) {
            $wrapper_class = str_replace(',',' ',$wrapper_class);
            //$wrapper_id = str_replace(',',' ',$wrapper_id);
            $link_wrapper_class  = empty($link_wrapper_class)
                                    ? "" : " class=\"{$link_wrapper_class}\"";
            $link_wrapper_id  = empty($link_wrapper_id)
                                ? "" : " id=\"{$link_wrapper_id}\"";
            $new_args['link_before'] .= "<{$link_wrapper}{$link_wrapper_class}{$link_wrapper_id}>";
            $new_args['link_after']  = "</{$link_wrapper}>";
        }
        $new_args['link_after']  .= $page_styles_args['link_after'];

        if ($link_wrapper_outter) {
            $wrapper_class = str_replace(',',' ',$wrapper_class);
            $link_wrapper_class  = empty($link_wrapper_outter_class)
                                    ? "" : " class=\"{$link_wrapper_outter_class}\"";
            $new_args['link_before_outter'] = "<{$link_wrapper_outter}{$link_wrapper_class}>";
            $new_args['link_after_outter']  = "</{$link_wrapper_outter}>";
        }

        if ($sh_autopag_functions)
            $new_args['pagelink'] = $pagelink;
        else
            $new_args['pagelink'] = str_replace('%', '&&&', $pagelink);

        return $new_args;
    }



    /**
     * Add pagination styles arguments
     *
     * @param array $link_pages_args
     * @return array
     */
    public function add_link_to_current($link,$i)
    {

        $link = str_replace(array('&&&title&&&', '&&&page&&&'), array(get_the_title(), $i), $link);
        if (!strpos($link, "<a ")){
            $link = _wp_link_page( $i ) . $link . '</a>';
        }
        return $link;
    }
    
    /**
     * Add pagination spacing.
     *
     * @return void
     */
    public function add_pagination_styling()
    {
    ?>
    <style type="text/css">
        #post-pagination a {
            margin: 0 3px;
        }
    </style>
    <?php
    }
    
    /**
     * Add pagination by Ajax
     *
     * @return void
     */
    public function add_ajax_pagination($content) {

        if (!is_singular())
            return $content;

        global $sh_page_links, $post, $sh_autopag_functions;
        $options = $sh_page_links->get_options();

        $post_type = get_post_type($post);
        $enabled = unserialize($options['single_view']['enabled_posts']);
        if (!in_array($post_type, $enabled))
            return $content;
        
        if ($options['pagination_styles']['use_ajax']==1) {

            $id = $options['pagination_styles']['wrapper_id'];
            $page = (is_single() || is_page()) ? get_query_var('page') : 1;
            if ($page==0)
                $page=1;

            $add_content = '
            <input type="hidden" id="plp_ajax_prev_page" value="'. $page .'" />
            <input type="hidden" id="plp_ajax_current_page" value="'. $page .'" />
            <span id="plp_ajax_loading"></span>
            <script type="text/javascript">
            jQuery(document).ready( function($){

                $("#plp_ajax_loading").fadeTo(0,0);
                $("#'. $id .'").hide(0);
                $(window).scroll( function(e) {
                    var total_h = $(window).height();
                    var page_h = $(document).height();
                    var current_h = $(window).scrollTop();
                    var content_h =  $("#plp_wrapper_default_content").height();
                    var content_top = $("#plp_wrapper_default_content").position();
                    var content_end = content_h + content_top.top;

                    //Load when current position is bigger than the almost end of post
                    //Load when the page is smaller than window
                    //Load when the scroll is at end of window
                    if ( ( current_h > content_end - 100) || ( page_h < total_h ) || (current_h+total_h >= page_h)) {
                        
                        var next_page = $("#plp_ajax_current_page").val();
                        if (next_page != "hold") {
                            $("#plp_ajax_current_page").val("hold");
                            $("#plp_ajax_loading").fadeTo(300, 1, function() {
                                var data = {
                                    action: "plp_load_page",
                                    pagination: next_page,
                                    type: "vertical",
                                    current_post: '. $post->ID .'
                                };

                                $.post("'. admin_url( 'admin-ajax.php' ) .'", data, function(response) {
                                    $("#plp_ajax_loading").fadeTo(300, 0, function() {
                                        if (response != 0) {
                                            $("#plp_wrapper_default_content").append(response);
                                            $("#plp_ajax_prev_page").val(parseInt($("#plp_ajax_prev_page").val())+1);
                                            $("#plp_ajax_current_page").val($("#plp_ajax_prev_page").val());
                                        }
                                    });
                                });
                            });
                        }
                    } 
                });
            });
            </script>
            <style>
            #plp_ajax_loading {
                display: block;
                width: 16px;
                height: 16px;
                margin: 20px auto;
                background: url('. plugins_url( 'images/loading.gif' , __FILE__ ) .') no-repeat top left;
            }
            </style>';

            return '<div id="plp_wrapper_default_content">'. $content . '</div>' . $add_content;

        } elseif ($options['pagination_styles']['use_ajax']==2) {

            $id = $options['pagination_styles']['wrapper_id'];
            $page = (is_single() || is_page()) ? get_query_var('page') : 1;
            if ($page==0)
                $page=1;

            $script = '
            jQuery(document).ready( function($){

                function plp_bind_links() {
                    $("#plp_ajax_loading").fadeTo(0,0);
                    $("#'. $id .' a").click( function(e) {

                        if ($(this).attr("data-ajax") == "0")
                            return;
                        
                        e.preventDefault();
                        var pagination = $(this).attr("href");

                        $("#plp_wrapper_default_content").fadeTo(500, 0.3, function() {

                            var data = {
                                action: "plp_load_page",
                                pagination: pagination,
                                type: "horizontal",
                                current_post: '. $post->ID .'
                            };

                            $.post("'. admin_url( 'admin-ajax.php' ) .'", data, function(response) {
                                plp_process_response(response);
                            });
                        });
                    });
                }

                plp_bind_links();';

            $append_script = '
                    function plp_process_response(response) {
                        if (response != 0) {
                            $("#plp_wrapper_default_content").html(response);
                        }
                        $("#plp_wrapper_default_content").fadeTo(500, 1);
                    }';

            if ($sh_autopag_functions) {

                $script .= '$("#post-pagination").insertAfter($("#plp_wrapper_default_content"));';

                global $sh_scrolling_functions;
                if ($sh_scrolling_functions) {
                    $append_script = '
                    function plp_process_response(response) {
                        if (response != 0) {
                            $("#plp_wrapper_default_content").html(response);
                            $("#plp_inital_pagination").html($("#plp_new_pagination").html());
                            $("#plp_new_pagination").remove();
                        }
                        $("#plp_wrapper_default_content").fadeTo(500, 1);
                        plp_bind_links();
                    }';
                }

            }

            $script .= $append_script . '
            });';

            $add_content = '
            <span id="plp_ajax_loading"></span>
            <script type="text/javascript">'. $script .'</script>
            <style>
            #plp_ajax_loading {
                display: block;
                width: 16px;
                height: 16px;
                margin: 20px auto;
                background: url('. plugins_url( 'images/loading.gif' , __FILE__ ) .') no-repeat top left;
            }
            </style>';

            return '<div id="plp_wrapper_default_content">'. $content . '</div>' . $add_content;
        }

        return $content;
    }
    
    /**
     * Return next page for ajax
     *
     * @return void
     */
    public function plp_load_next_page() {

        global $sh_page_links, $sh_autopag_functions, $current_page;
        $options = $sh_page_links->get_options();
        $auto_options = $options['auto_pagination'];
        $current_post = get_post((int)$_POST['current_post']);

        if ($_POST['type']=="vertical") {
            $current_page = (int)$_POST['pagination'];
        } else {
            global $wp_rewrite;
            $url = $_POST['pagination'];
            $rewrite = $wp_rewrite->wp_rewrite_rules();

            if ( empty($rewrite) ) {
                //No pretty permalink
                preg_match('#[?&](page)=(\d+)#', $url, $values);
                $current_page = absint($values[2]);
            } else {
                $start = strpos($url, $current_post->post_name) + 1 + strlen($current_post->post_name);
                $end = strpos($url, '/', $start);
                $current_page = intval(substr($url, $start, $end-$start));
            }
            if ( !$current_page )
                $current_page = 1;
            $current_page = $current_page-1;
        }

        $content = apply_filters( 'the_content', $current_post->post_content );

        if ($sh_autopag_functions) {
            
            global $post;
            
            $post = $current_post;
            $sh_autopag_functions->remove_nextpage();
            setup_postdata( $post );

            the_content();

            if ($_POST['type']!="vertical") {
                global $sh_scrolling_functions;
                if ($sh_scrolling_functions) {
                    global $pages_count;
                    $page_style_args = $options['pagination_styles'];
                    echo $sh_scrolling_functions->generate_scrolling_pagination("", $current_page+1, $pages_count, $page_style_args, "plp_new_pagination");
                }    
            }
            

        } else {
            //No auto pagination. Just Single pagination.
            setup_postdata( $current_post );
            global $pages;
            if (isset($pages[$current_page]))
                echo apply_filters('the_content', $pages[$current_page]);
            else
                echo '0';
        }

        die();
        
    }
}
