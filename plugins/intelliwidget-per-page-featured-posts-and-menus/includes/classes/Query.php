<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-intelliwidget-query.php - IntelliWidget Query Class
 * based in part on code from Wordpress core post.php and query.php
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
class IntelliWidgetQuery {
    
    var $post;
    var $posts;
    var $post_count   = 0;
    var $in_the_loop  = FALSE;
    var $current_post = -1;
    var $postmeta;
    
    function iw() {
        return IntelliWidget::$instance;
    }
	/**
	 * Set up the next post and iterate current post index.
	 *
	 * @return next post.
	 */
	function next_post() {

		$this->current_post++;

		$this->post = $this->posts[ $this->current_post ];
		return $this->post;
	}
	/**
	 * Whether there are more posts available in the loop.
	 *
	 *
	 * @return bool True if posts are available, FALSE if end of loop.
	 */
	function have_posts() {
		if ( $this->current_post + 1 < $this->post_count ) {
			return TRUE;
		} elseif ( $this->current_post + 1 == $this->post_count && $this->post_count > 0 ) {
			// Do some cleaning up after the loop
			$this->rewind_posts();
		}

		$this->in_the_loop = FALSE;
		return FALSE;
	}

	/**
	 * Rewind the posts and reset post index.
	 *
	 */
	function rewind_posts() {
		$this->current_post = -1;
		if ( $this->post_count > 0 ) {
			$this->post = $this->posts[ 0 ];
		}
	}

	/**
	 * Sets up the current post.
	 *
	 * Retrieves the next post, sets up the post, sets the 'in the loop'
	 * property to TRUE.
	 *
	 * @uses $intelliwidget_post
	 */
	function the_post() {
		$this->in_the_loop = TRUE;

		if ( -1 == $this->current_post ){ // loop has just started
            // stub for future functionality
        }
		global $intelliwidget_post;
		$intelliwidget_post = $this->next_post();
	}
    
    /**
     * Intelliwidget has a lot of internal logic that can't be done efficiently using the standard
     * WP_Query parameters. This function dyanamically builds a custom query so that the majority of the 
     * post and postmeta data can be retrieved in two optimized db queries.
     */
    function iw_query( $instance = NULL ) {
        global $wpdb;
        if ( empty( $instance ) ) return '';
        $select = "
SELECT DISTINCT
    p1.ID
FROM {$wpdb->posts} p1
        ";
        $joins = array( "
LEFT JOIN {$wpdb->postmeta} pm1 ON pm1.post_id = p1.ID
    AND pm1.meta_key = 'intelliwidget_expire_date'
            ", "
LEFT JOIN {$wpdb->postmeta} pm2 ON pm2.post_id = p1.ID
    AND pm2.meta_key = 'intelliwidget_event_date'
            ", );
        if( !empty( $instance[ 'include_private' ] ) && current_user_can( 'read_private_posts' ) ):
            $clauses = array(
                "(p1.post_status = 'publish' OR p1.post_status = 'private' )",
                "(p1.post_password = '' OR p1.post_password IS NULL)",
            );
        else:
            $clauses = array(
                "(p1.post_status = 'publish')",
                "(p1.post_password = '' OR p1.post_password IS NULL)",
            );
        endif;
        // placeholders
        $prepargs   = array();
        // taxonomies
        $term_clauses = array();
        $taxonomies = preg_grep( '/post_format/', get_object_taxonomies( 
            ( array ) $instance[ 'post_types' ] ), 
                PREG_GREP_INVERT );
        // use subqueries for taxonomy AND search to accommodate child terms
        if ( !empty( $instance[ 'terms' ] ) && isset( $instance[ 'allterms' ] ) && $instance[ 'allterms' ] ):
            $ttids = $this->get_term_taxonomy_ids( $instance[ 'terms' ], $taxonomies, TRUE ); // true returns subset for each term
            foreach ( $ttids as $ttid_array ):
                $term_clauses[] = " ( p1.ID IN ( SELECT object_id FROM {$wpdb->term_relationships}
                WHERE term_taxonomy_id IN ( " . $this->prep_array( $ttid_array, $prepargs, 'd' ) . " ) ) ) ";
            endforeach;
        // constrain results to current queried taxonomy
        else:
            if ( !empty( $instance[ 'same_term' ] ) ):
                // get terms from wp_query object and add to terms array
                $t = get_queried_object();
                if ( isset( $t->term_taxonomy_id ) ):
                    $ttids = $this->get_term_taxonomy_ids( 
                        $t->term_taxonomy_id, 
                        $t->taxonomy 
                    );
                else:
                    $ttids = wp_get_post_terms( 
                        $post->ID, 
                        $taxonomies, 
                        array( 'fields' => 'tt_ids' ) 
                    );
                endif;
            /**
             * REMOVED: backward compatibility: support category term ids
            elseif ( isset( $instance[ 'category' ] ) && '' != $instance[ 'category' ] && -1 != $instance[ 'category' ] ):
                $ttids = $this->get_term_taxonomy_ids( 
                    $instance[ 'category' ], 
                    'category' 
                );
             */
            // otherwise match all child terms
            elseif ( !empty( $instance[ 'terms' ] ) ):
                $ttids = $this->get_term_taxonomy_ids( $instance[ 'terms' ], $taxonomies );
            endif;
            if ( !empty( $ttids ) ):
                $term_clauses[] = '( tx1.term_taxonomy_id IN ( ' . $this->prep_array( $ttids, $prepargs, 'd' ) . ' ) )';
                $joins[] = "LEFT JOIN {$wpdb->term_relationships} tx1 ON p1.ID = tx1.object_id ";
            endif;
        endif;

        // include specific posts along with any term matches
        if ( empty( $instance[ 'page' ] ) ):
            if ( count( $term_clauses ) ):
                $clauses[] = implode( ' AND ', $term_clauses );
            endif;
        else:
            $page_clause = '(p1.ID IN ('. $this->prep_array( $instance[ 'page' ], $prepargs, 'd' ) . ') )';
            if ( count( $term_clauses ) ):
                $clauses[] = '( ( ' . implode( ' AND ', $term_clauses ) . ' ) OR ' . $page_clause . ' )';
            else:
                $clauses[] = $page_clause;
            endif;
        endif;
        
        // post types
        if ( empty( $instance[ 'post_types' ] ) )
            $instance[ 'post_types' ] = 'none';
        $clauses[] = '(p1.post_type IN ('. $this->prep_array( $instance[ 'post_types' ], $prepargs ) . ') )';
        // time-based clauses //
        $time_adj = gmdate( 'Y-m-d H:i', current_time( 'timestamp' ) );
        //$time_adj = '[current_time]'; // do not use for now (2.3.4)

        // skip all expired posts
        // postmeta intelliwidget_expire_date date format 
        // MUST be YYYY-MM-DD HH:MM for this to work correctly!
        if ( $instance[ 'skip_expired' ] ):
            $clauses[] = "(pm1.meta_value IS NULL OR (pm1.meta_value IS NOT NULL AND CAST( pm1.meta_value AS CHAR ) > '" . $time_adj . "'))";
        endif;
        // show posts that have not started yet only
        // postmeta intelliwidget_event_date date format 
        // MUST be YYYY-MM-DD HH:MM for this to work correctly!
        if ( $instance[ 'future_only' ] ):
            $clauses[] = "(pm2.meta_value IS NOT NULL AND CAST( pm2.meta_value AS CHAR ) > '" . $time_adj . "')";
        endif;
        // skip posts that have not started yet
        // postmeta intelliwidget_event_date date format 
        // MUST be YYYY-MM-DD HH:MM for this to work correctly!
        if ( $instance[ 'active_only' ] ):
            $clauses[] = "(pm2.meta_value IS NULL OR (pm2.meta_value IS NOT NULL AND CAST( pm2.meta_value AS CHAR ) < '" . $time_adj . "'))";
        endif;

        $items = intval( $instance[ 'items' ] );
        $limit = '';
        if ( !empty( $items ) && empty( $instance[ 'daily' ] ) ): 
            $limit = ' LIMIT %d';
            $prepargs[] = $items;
        endif;
        $query = $select . implode( ' ', $joins ) . ' WHERE ' . implode( "\n AND ", $clauses ) . $this->orderby( $instance ) . $limit;
        return $wpdb->prepare( $query, $prepargs );
    }
    
    function orderby( $instance ) {
        $order = $instance[ 'sortorder' ] == 'ASC' ? 'ASC' : 'DESC';
        if ( empty( $instance[ 'daily' ] ) ):
            switch ( $instance[ 'sortby' ] ):
                case 'event_date':
                    $orderby = 'pm2.meta_value ' . $order;
                    break;
                case 'rand':
                    $orderby = 'RAND()';
                    break;
                case 'menu_order':
                    $orderby = 'p1.menu_order ' . $order;
                    break;
                case 'date':
                    $orderby = 'p1.post_date ' . $order;
                    break;
                case 'title':
                default:
                    $orderby = 'p1.post_title ' . $order;
                    break;
            endswitch;
        else:
            $orderby = 'p1.ID ';
        endif;
        return ' ORDER BY ' . $orderby;
    }
    
    function get_posts( $instance ) {
        // $time_adj = gmdate( 'Y-m-d H:i', current_time( 'timestamp' ) ); // do not use for now (2.3.4)
        
        global $wpdb;
        //if ( ! ( $query = $instance[ 'querystr' ] ) ) // do not use for now (2.3.4)
            $query = $this->iw_query( $instance );
        // $query = str_replace( '[current_time]', $time_adj, $query ); // do not use for now (2.3.4)
        //echo '<!-- ' . $query . LF . print_r( $instance, TRUE ) . LF . ' -->' . LF;
        $res = $wpdb->get_col( $query, 0 );
        $count = count( $res );
        if ( $count ):
            if (!empty( $instance[ 'daily' ] ) ):
                $doy = gmdate( 'z', current_time( 'timestamp' ) );
                $index = intval( $doy ) % $count;
                $res = array_slice( $res, $index, 1 );
            endif;
            $clauses    = array();
            $prepargs   = array();

            // now flesh out objects
            $select = "
SELECT DISTINCT
    p1.ID,
    p1.post_content, 
    p1.post_excerpt,
    p1.post_type,
    COALESCE(NULLIF(TRIM(p1.post_title), ''), " 
    . $this->prep_array( __( 'Untitled', 'intelliwidget' ), $prepargs ) . ") AS post_title,
    p1.post_date AS post_date,
    p1.post_author,
    'raw' AS filter,
    pm1.meta_value AS expire_date, 
    pm2.meta_value AS event_date, 
    pm3.meta_value AS link_classes,
    pm4.meta_value AS alt_title,
    pm5.meta_value AS link_target,
    pm6.meta_value AS external_url,
    pm7.meta_value AS thumbnail_id
FROM {$wpdb->posts} p1
";
            $joins = array( "
LEFT JOIN {$wpdb->postmeta} pm1 ON pm1.post_id = p1.ID
    AND pm1.meta_key = 'intelliwidget_expire_date'
            ", "
LEFT JOIN {$wpdb->postmeta} pm2 ON pm2.post_id = p1.ID
    AND pm2.meta_key = 'intelliwidget_event_date'
            ", "
LEFT JOIN {$wpdb->postmeta} pm3 ON pm3.post_id = p1.ID
    AND pm3.meta_key = 'intelliwidget_link_classes'
            ", "
LEFT JOIN {$wpdb->postmeta} pm4 ON pm4.post_id = p1.ID
    AND pm4.meta_key = 'intelliwidget_alt_title'
            ", "
LEFT JOIN {$wpdb->postmeta} pm5 ON pm5.post_id = p1.ID
    AND pm5.meta_key = 'intelliwidget_link_target'
            ", "
LEFT JOIN {$wpdb->postmeta} pm6 ON pm6.post_id = p1.ID
    AND pm6.meta_key = 'intelliwidget_external_url'
            ", "
LEFT JOIN {$wpdb->postmeta} pm7 ON pm7.post_id = p1.ID
    AND pm7.meta_key = '_thumbnail_id'
            " );
            $clauses[] = '(p1.ID IN ('. implode(',', $res ) . ') )';
            /* Remove current page from list of pages if set */
            if ( is_singular() && $instance[ 'skip_post' ] ):
                // 2.3.7.4: using get_queried_object instead of global $post in case secondary query was not reset
                $qo = get_queried_object();
                $clauses[] = "(p1.ID != {$qo->ID})";
            endif;
            $query = $select . implode( ' ', $joins ) . ' WHERE ' . implode( "\n AND ", $clauses ) . $this->orderby( $instance );
            $res      = $wpdb->get_results( $wpdb->prepare( $query, $prepargs ), OBJECT );
        endif;
        $this->posts = $res;
        $this->post_count = count( $res );
    }

    function prep_array( $value, &$args, $type = 's' ) {
        $values = is_array( $value ) ? $value : explode( ',', $value );
        $placeholders = array();
        foreach( $values as $val ):
            $placeholders[] = ( 'd' == $type ? '%d' : '%s' );
            $args[] = 'w' == $type ? '%' . trim( $val ) . '%' : trim( $val );
        endforeach;
        return implode( ',', $placeholders );
    }
    
    /**
     * post_list_query
     * 
     * lightweight post query for use in menus.
     * Merges current selection ( page array ) with first 200 
     * results from text search ( pagesearch ).
     */
    function post_list_query( $instance ) {
        global $wpdb;
        $limit  = INTELLIWIDGET_MAX_MENU_POSTS;
        $args   = array();
        $clause = array();
        $selected = count( $instance[ 'page' ] ) ? 
            " ( SELECT ID IN ( " . $this->prep_array( $instance[ 'page' ], $args, 'd' ) . " ) ) AS selected " :
            " 1 AS selected ";
        
        $query = "
        SELECT
        ";
        if ( $instance[ 'profiles_only' ] ):
            $query .= " pm.meta_id as has_profile,
        ";
        endif; 
        $query .= "
            ID,
            post_title,
            post_type,
            post_parent,
            " . $selected . "
        FROM {$wpdb->posts}
        ";
        if ( $instance[ 'profiles_only' ] ):
            $query .= " JOIN {$wpdb->postmeta} pm ON pm.meta_key = '_intelliwidget_map' and pm.post_id = ID 
        ";
            $limit = 0;
        endif;
        $query .= " WHERE post_type IN (" . $this->prep_array( $instance[ 'post_types' ], $args ) . ")
            AND (post_status = 'publish' " . ( current_user_can( 'read_private_posts' ) ? " OR post_status = 'private'" : '' ) . ")
            AND (post_password = '' OR post_password IS NULL)
        ";
        // return currently selected posts in addition to text matches
        if ( !empty( $instance[ 'pagesearch' ] ) ):
            $clause[] = " ( post_title LIKE " . $this->prep_array( $instance[ 'pagesearch' ], $args, 'w' ) . " )
        ";
        else:
            $clause[] = " ( 1=1 ) ";
        endif;
        if ( count( $instance[ 'page' ] ) )
            $clause[] = " ( ID IN ( " . $this->prep_array( $instance[ 'page' ], $args, 'd' ) . " ) )
        ";
        if ( count( $clause ) )
            $query .= ' AND ( ' . implode( " OR ", $clause ) . " )
        "; 
        $query .= " ORDER BY selected DESC, post_type, post_title
        ";
        if ( $limit ): 
            $query .= " LIMIT " . $limit;
        endif;
        return $wpdb->get_results( $wpdb->prepare( $query, $args ), OBJECT );
    }
    
    /**
     * terms_query
     *
     * Returns the most frequently used ( relevant ) term object from a list of term ids
     */
    function terms_query( $ttids = array() ) {
        global $wpdb;
        $args = array();
        $query = "
        SELECT t.*, tt.* 
        FROM $wpdb->terms AS t 
            INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id 
        WHERE term_taxonomy_id IN (" . $this->prep_array( $ttids, $args, 'd' ) . ")
        ORDER BY tt.count DESC
        LIMIT 1
        ";
        return $wpdb->get_row( $wpdb->prepare( $query, $args ), OBJECT );
    }
    
    function get_term_taxonomy_ids( $ttids, $taxonomies, $and = FALSE ) {
        $actual_ttids = array();
        $terms = array();
        foreach ( ( array ) $taxonomies as $taxonomy )
            $terms = $this->iw()->_get_term_hierarchy( $taxonomy ) + $terms;
        foreach ( ( array ) $ttids as $ttid ):
            if ( '' === $ttid ) continue;
            $child_ttids = $this->iw()->get_term_children( $ttid, $terms );
            if ( $and ):
                // return array of arrays for AND
                $child_ttids[] = $ttid;
                $actual_ttids[] = $child_ttids;
            else:
                // return single array for OR
                $actual_ttids = array_merge( $actual_ttids, $child_ttids );
                $actual_ttids[] = $ttid;
            endif;
        endforeach;
        return $actual_ttids;
    }
}