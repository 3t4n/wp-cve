<?php
/**
 * Admin Actions.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Action links class.
 */
class REVIVESO_Actions
{
	use REVIVESO_Ajax;
    use REVIVESO_Hooker;
    use REVIVESO_HelperFunctions;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->ajax( 'process_get_taxonomies', 'get_taxonomies_list' );
	}

	/**
	 * Get Taxonomies list based on user search.
	 */
	public function get_taxonomies_list() {

		check_admin_referer( 'wp_reviveso_admin' );

		if( ! current_user_can( 'edit_posts' ) ){

			wp_send_json_error( esc_html__( 'Current user is not allowed to view the taxonomies list.', 'revive-so' ) );
		}

		$search_term = ! empty( $_POST['searchTerm'] ) ? sanitize_text_field( wp_unslash( $_POST['searchTerm'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $filter_data['results'] = array();           
        $all_data_count = 0;

		$taxonomies = $this->get_taxonomies( array(
			'public'   => true,
			'_builtin' => true,
		) );
		$taxonomies = apply_filters( 'reviveso_taxonomies_list', $taxonomies );
      
        if ( ! empty( $taxonomies ) && is_array( $taxonomies ) && ! empty( $search_term ) ) {
            foreach ( $taxonomies as $categories_data ) {
                $child_count      = 0;
                $child_term_array = array(); 

                if ( ! empty( $categories_data['categories'] ) && is_array( $categories_data['categories'] ) ) {
                    foreach ( $categories_data['categories'] as $key_child => $child_cat_data ) {
                        $term_data      = explode( ":", $child_cat_data );
                        $available_term = trim( $term_data[1] );
                        
                        if ( stripos( $available_term, $search_term ) !== false ) {
                            $child_term_array[ $child_count ] = array(
                                'id'   => $key_child,
                                'text' => $child_cat_data,
							);
                            ++$child_count;
                        }     
                    }
                }
                
                if ( ! empty( $child_term_array ) && is_array( $child_term_array ) ) {
                    $filter_data['results'][ $all_data_count ] = array(
                        'text'     => $categories_data['label'],
                        'children' => $child_term_array,
                       
					);
                    ++$all_data_count;
                }           
            }
        }

        wp_send_json( $filter_data );
	}
}