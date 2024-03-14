<?php
function berocket_pagination() {
    global $wp_query, $wp_rewrite;
    
    if ( function_exists('wc_get_loop_prop') && ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) ) {
        return;
    }
    $BeRocket_Pagination = BeRocket_Pagination::getInstance();
    $options_global = $BeRocket_Pagination->get_option();
    $options = $options_global['general_settings'];

    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $url_parts    = explode( '?', $pagenum_link );

    if( function_exists('wc_get_loop_prop') ) {
        $total   = wc_get_loop_prop( 'total_pages' );
        $current = wc_get_loop_prop( 'current_page' );
    } else {
        $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
        $current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    }
    ?>
    <nav class="woocommerce-pagination berocket_pagination">
    <?php
    if( $total > 1 ) {

    $pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

    $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

    $args = apply_filters( 'woocommerce_pagination_args', array(
        'base'                  => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
        'format'                => $format, // ?page=%#% : %#% is replaced by the page number
        'total'                 => $total,
        'current'               => $current,
        'show_all'              => false,
        'prev_next'             => true,
        'prev_text'             => '«',
        'next_text'             => '»',
        'dots_prev_text'        => '…',
        'dots_next_text'        => '…',
        'first_page'            => '1',
        'last_page'             => '%LAST%',
        'current_page'          => '%PAGE%',
        'page'                  => '%PAGE%',
        'end_size'              => 2,
        'mid_size'              => 1,
        'type'                  => 'plain',
        'add_args'              => array(), // array of query args to add
        'add_fragment'          => '',
        'before_page_number'    => '',
        'after_page_number'     => ''
    ) );

    if( function_exists('wc_get_loop_prop') ) {
        if ( wc_get_loop_prop( 'is_shortcode' ) ) {
            $args['base']   = esc_url_raw( add_query_arg( 'product-page', '%#%', false ) );
            $args['format'] = '?product-page = %#%';
        } else {
            $args['base']   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
            $args['format'] = '';
        }
    }

    if ( ! is_array( $args['add_args'] ) ) {
        $args['add_args'] = array();
    }

    if ( isset( $url_parts[1] ) ) {
        $format = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
        $format_query = isset( $format[1] ) ? $format[1] : '';
        wp_parse_str( $format_query, $format_args );

        wp_parse_str( $url_parts[1], $url_query_args );

        foreach ( $format_args as $format_arg => $format_arg_value ) {
            unset( $url_query_args[ $format_arg ] );
        }

        $args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
    }

    $total = (int) $args['total'];
    if ( $total < 2 ) {
        return;
    }
    $current  = (int) $args['current'];
    $end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
    if ( $end_size < 0 ) {
        $end_size = 0;
    }
    $mid_size = (int) $args['mid_size'];
    if ( $mid_size < 0 ) {
        $mid_size = 0;
    }
    $add_args = $args['add_args'];
    $r = '';
    $page_links = array();
    $dots = false;
    $next_dots = false;

    $link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
    $link = str_replace( '%#%', $current - 1, $link );
    if ( $add_args )
        $link = add_query_arg( $add_args, $link );
    $link .= $args['add_fragment'];
    $page_before = '<li class="prev"><a class="prev page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a></li>';
    $page_before = apply_filters( 'berocket_pagination_previous', $page_before );

    $link = str_replace( '%_%', $args['format'], $args['base'] );
    $link = str_replace( '%#%', $current + 1, $link );
    if ( $add_args )
        $link = add_query_arg( $add_args, $link );
    $link .= $args['add_fragment'];

    $page_after = '<li class="next"><a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a></li>';
    $page_after = apply_filters( 'berocket_pagination_next', $page_after );

    $dots_prev_text = '<li class="dots"><span class="page-numbers dots">'.$args['dots_prev_text'].'</span></li>';
    $dots_prev_text = apply_filters( 'berocket_pagination_dots_previous', $dots_prev_text );
    $dots_next_text = '<li class="dots"><span class="page-numbers dots">'.$args['dots_next_text'].'</span></li>';
    $dots_next_text = apply_filters( 'berocket_pagination_dots_next', $dots_next_text );

    if ( $args['prev_next'] && $current && 1 < $current && $options['pos_next_prev'] == 'around_pagination' ) :
        $page_links[] = $page_before;
    endif;
    $start = true;
    $total_n = number_format_i18n( $total );
    for ( $n = 1; $n <= $total; $n++ ) :
        $current_n = number_format_i18n( $n );
        if ( $n == $current ) :
            $current_n = berocket_replace_data_pagination ( $args['current_page'], $current_n, $total_n );
            if ( $args['prev_next'] && $current && 1 < $current && $options['pos_next_prev'] == 'around_current' ) :
                $page_links[] = $page_before;
            endif;
            $page_links[] = "<li class='current'><span class='page-numbers current'>" . $args['before_page_number'] . $current_n . $args['after_page_number'] . "</span></li>";
            $dots = true;
            $next_dots = true;
            if ( $args['prev_next'] && $current && ( $current < $total || -1 == $total ) && $options['pos_next_prev'] == 'around_current' ) :
                $page_links[] = $page_after;
            endif;
        else :
            if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
                if ( $n == $total ) {
                    $current_n = berocket_replace_data_pagination ( $args['last_page'], $current_n, $total_n );
                } else if ( $n == 1 ) {
                    $current_n = berocket_replace_data_pagination ( $args['first_page'], $current_n, $total_n );
                } else {
                    $current_n = berocket_replace_data_pagination ( $args['page'], $current_n, $total_n );
                }
                $link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
                $link = str_replace( '%#%', $n, $link );
                if ( $add_args )
                    $link = add_query_arg( $add_args, $link );
                    $link .= $args['add_fragment'];

                    $page_links[] = "<li class='other'><a class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . $current_n . $args['after_page_number'] . "</a></li>";
                    $dots = true;
                elseif ( $dots && ! $args['show_all'] ) :
                if ( ! $start && $args['prev_next'] && $current && ( $current < $total || -1 == $total ) && $options['pos_next_prev'] == 'around_central' ) :
                    $page_links[] = $page_after;
                endif;
                if ( $options['use_dots'] ) {
                    if ( $next_dots ) {
                        $page_links[] = $dots_next_text;
                    } else {
                        $page_links[] = $dots_prev_text;
                    }
                }
                if ( $start && $args['prev_next'] && $current && 1 < $current && $options['pos_next_prev'] == 'around_central' ) :
                    $page_links[] = $page_before;
                endif;
                $dots = false;
                $start = false;
            endif;
        endif;
    endfor;
    if ( $args['prev_next'] && $current && ( $current < $total || -1 == $total ) && $options['pos_next_prev'] == 'around_pagination' ) :
        $page_links[] = $page_after;
    endif;
    $r .= "<ul class='page-numbers'>\n\t";
    $r .= join("\n\t", $page_links);
    $r .= "\n</ul>\n";

    do_action( 'berocket_ps_before_pagination' );
    echo $r;
    do_action( 'berocket_ps_after_pagination' );
    }
    ?>
    </nav>
    <?php
}
function berocket_replace_data_pagination ( $text, $page, $lastpage ) {
    $text = str_replace( '%PAGE%', $page, $text );
    $text = str_replace( '%LAST%', $lastpage, $text );
    return $text;
}
