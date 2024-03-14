<?php

defined( 'ABSPATH' ) || exit;

class Wugrat_Admin_Attributes_In_Group {

    /**
     *
     */
    public static function output() {
        $result = '';
        $action = '';

        // Action to perform: add, edit, delete or none.
        if ( ! empty( $_POST['add_new_attribute'] ) ) { // WPCS: CSRF ok.
            $action = 'add';
        } elseif ( ! empty( $_GET['delete'] ) ) {
            $action = 'delete';
        }

        switch ( $action ) {
            case 'add':
                $result = self::process_add_attribute();
                break;
            case 'delete':
                $result = self::process_delete_attribute();
                break;
        }

        if ( is_wp_error( $result ) ) {
            echo '<div id="woocommerce_errors" class="error"><p>' . wp_kses_post( $result->get_error_message() ) . '</p></div>';
        }

        // Show admin interface.
        self::add_attribute();
    }

    /**
     * Add an attribute.
     *
     * @return bool|WP_Error
     */
    private static function process_add_attribute() {
        global $wpdb;

		check_admin_referer( 'woocommerce-add-new_attribute' );
		$attribute_name = isset( $_POST['attribute_name'] ) ? wc_clean( wp_unslash( $_POST['attribute_name'] ) ) : '';
		$term_id = isset( $_GET['term_id'] ) && (int) $_GET['term_id'] ? (int) $_GET['term_id'] : null;

	    $query = $wpdb->prepare("UPDATE $wpdb->term_taxonomy SET children = CONCAT(COALESCE(children,''), %s) WHERE term_id = %d", array("pa_".$attribute_name.",", $term_id));
        $wpdb->get_results($query);

        return true;
    }

    /**
     * Delete an attribute.
     *
     * @return bool
     */
    private static function process_delete_attribute() {
        global $wpdb;

		$attribute_name = isset( $_GET['delete'] ) ? wc_clean( wp_unslash( $_GET['delete'] ) ) : '';
		$term_id = isset( $_GET['term_id'] ) && (int) $_GET['term_id'] ? (int) $_GET['term_id'] : null;
		check_admin_referer( 'woocommerce-delete-attribute_' . $attribute_name );

	    $query = $wpdb->prepare("UPDATE $wpdb->term_taxonomy SET children = REPLACE(children, %s, '') WHERE term_id = %d", array("pa_".$attribute_name.",", $term_id));
	    $wpdb->get_results($query);

	    return true;
    }

    /**
     * Add Attribute admin panel.
     *
     * Shows the interface for adding new attributes.
     */
    public static function add_attribute() {
        global $wpdb;

        ?>
        <div class="wrap woocommerce">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <br class="clear" />
            <div id="col-container">
                <div id="col-right">
                    <div class="col-wrap">
                        <table class="wp-list-table widefat fixed striped tags ui-sortable" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col"><?php esc_html_e( 'Name', 'woocommerce' ); ?></th>
                                <th scope="col"><?php esc_html_e( 'Slug', 'woocommerce' ); ?></th>
                                <?php if ( wc_has_custom_attribute_types() ) : ?>
                                    <th scope="col"><?php esc_html_e( 'Type', 'woocommerce' ); ?></th>
                                <?php endif; ?>
                                <th scope="col"><?php esc_html_e( 'Order by', 'woocommerce' ); ?></th>
                                <th scope="col"><?php esc_html_e( 'Terms', 'woocommerce' ); ?></th>
                                <th scope="col" id="handle" class="column-handle"></th>
                            </tr>
                            </thead>
                            <tbody id="the-list" data-wp-lists="list:tag">
							<?php
							$term_id = isset( $_GET['term_id'] ) && (int) $_GET['term_id'] ? (int) $_GET['term_id'] : null;

							//TODO: Refactor to method
							$query = $wpdb->prepare("SELECT children FROM $wpdb->term_taxonomy WHERE term_id=%d", array($term_id));
							$results = $wpdb->get_results($query);

							$attribute_children = explode(',', $results[0]->children);

                            $attribute_taxonomies = array();
                            foreach ($attribute_children as $attribute_child) {
	                            $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name=%s", array(substr($attribute_child, 3)));
	                            $results = $wpdb->get_results($query);

                                if (!empty($results)) {
                                    $attribute_taxonomies[] = $results[0];
                                }
                            }

							if ( $attribute_taxonomies ) :
								foreach ( $attribute_taxonomies as $tax ) :
									$term_id = isset( $_GET['term_id'] ) && (int) $_GET['term_id'] ? (int) $_GET['term_id'] : null;
									?>
                                    <tr>
                                        <td>
                                            <strong><a href="edit-tags.php?taxonomy=<?php echo esc_attr( wc_attribute_taxonomy_name( $tax->attribute_name ) ); ?>&amp;post_type=product"><?php echo esc_html( $tax->attribute_label ); ?></a></strong>

                                            <div class="row-actions"><span class="edit"><a href="<?php echo esc_url( add_query_arg( 'edit', $tax->attribute_id, 'edit.php?post_type=product&amp;page=product_attributes' ) ); ?>"><?php esc_html_e( 'Edit', 'woocommerce' ); ?></a> | </span><span class="delete"><a class="delete" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'delete', $tax->attribute_name, 'edit.php?post_type=product&amp;page=wugrat_attributes_in_group&amp;term_id='.$term_id ), 'woocommerce-delete-attribute_' . $tax->attribute_name ) ); ?>"><?php esc_html_e( 'Delete', 'woocommerce' ); ?></a></span></div>
                                        </td>
                                        <td><?php echo esc_html( $tax->attribute_name ); ?></td>
                                        <?php if ( wc_has_custom_attribute_types() ) : ?>
                                            <td><?php echo esc_html( wc_get_attribute_type_label( $tax->attribute_type ) ); ?> <?php echo $tax->attribute_public ? esc_html__( '(Public)', 'woocommerce' ) : ''; ?></td>
                                        <?php endif; ?>
                                        <td>
                                            <?php
                                            switch ( $tax->attribute_orderby ) {
                                                case 'name':
                                                    esc_html_e( 'Name', 'woocommerce' );
                                                    break;
                                                case 'name_num':
                                                    esc_html_e( 'Name (numeric)', 'woocommerce' );
                                                    break;
                                                case 'id':
                                                    esc_html_e( 'Term ID', 'woocommerce' );
                                                    break;
                                                default:
                                                    esc_html_e( 'Custom ordering', 'woocommerce' );
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td class="attribute-terms">
                                            <?php
                                            $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name );

                                            if ( taxonomy_exists( $taxonomy ) ) {
                                                $terms = get_terms( array(
                                                    'taxonomy' => $taxonomy,
                                                    'hide_empty' => false,
                                                ));
                                                $terms_string = implode( ', ', wp_list_pluck( $terms, 'name' ) );
                                                if ( $terms_string ) {
                                                    echo esc_html( $terms_string );
                                                } else {
                                                    echo '<span class="na">&ndash;</span>';
                                                }
                                            } else {
                                                echo '<span class="na">&ndash;</span>';
                                            }
                                            ?>
                                            <br /><a href="edit-tags.php?taxonomy=<?php echo esc_attr( wc_attribute_taxonomy_name( $tax->attribute_name ) ); ?>&amp;post_type=product" class="configure-terms"><?php esc_html_e( 'Configure terms', 'woocommerce' ); ?></a>
                                        </td>
                                        <td class="handle column-handle ui-sortable-handle" data-colname style="display: table-cell;">
                                            <input type="hidden" name="attribute_name" value="<?php echo esc_attr($tax->attribute_name); ?>" />
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                            else :
                                ?>
                                <tr>
                                    <td colspan="6"><?php esc_html_e( 'No attributes currently added to this group.', 'woocommerce' ); ?></td>
                                </tr>
                            <?php
                            endif;
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="col-left">
                    <div class="col-wrap">
                        <div class="form-wrap">
							<?php
							$term_id = isset( $_GET['term_id'] ) && (int) $_GET['term_id'] ? (int) $_GET['term_id'] : null;

							$term_name = get_term_field('name', $term_id);

                            ?>
                            <h2><?php esc_html_e( 'Add attribute to the group '.$term_name, 'woocommerce' ); ?></h2>
                            <p><?php esc_html_e('All these attributes will be shown as one group. Adjust the order with the handle on the right side of the table. This order will be used to display the attributes on the frontend.', 'wupo-group-attributes' ); ?></p>
                            <p><?php echo __("<b>ATTENTION:</b> When using the same attribute in multiple groups,
                                        it is strongly advised to use only one of these groups for a product afterwards.<br />
                                        For example an attribute 'Resolution' with terms like Full-HD or 4K could be part of a group 'Screen', 
                                        but also of another group 'Camera'. When both groups are used for a product like a mobile phone 
                                        it is not possible to assign to this attribute different values for each of the two groups. This results in arbitrary
                                        and wrong rendering. This is a limitation from Wordpress/WooCommerce.<br />
                                        To circumvent the issue this plugin has a feature to duplicate existing attributes. On the attributes page
                                        you will find the Duplicate action when hovering over an attribute name on the right-side table.", 'wupo-group-attributes' ); ?>
                            <form action="edit.php?post_type=product&amp;page=wugrat_attributes_in_group&amp;term_id=<?php echo $term_id = isset( $_GET['term_id'] ) && (int) $_GET['term_id'] ? (int) $_GET['term_id'] : null; ?>" method="post">
								<?php do_action( 'woocommerce_before_add_attribute_fields' ); ?>

                                <div class="form-field">
                                    <label for="add_attribute_to_group"><?php esc_html_e( 'Select attribute', 'woocommerce' ); ?></label>
                                    <select name="attribute_name" id="attribute_name">
                                        <?php

                                        // Get all attributes
                                        $attribute_taxonomies = wc_get_attribute_taxonomies();

										// Get already existing attributes in the group
										$term_id = isset( $_GET['term_id'] ) && (int) $_GET['term_id'] ? (int) $_GET['term_id'] : null;

                                        $query = $wpdb->prepare("SELECT children FROM $wpdb->term_taxonomy WHERE term_id=%d", array($term_id));
                                        $results = $wpdb->get_results($query);
										$group_children = explode(',', $results[0]->children, -1);

                                        foreach ($attribute_taxonomies as $key=>$attribute_taxonomy) {
                                            if (in_array("pa_{$attribute_taxonomy->attribute_name}", $group_children))  {
                                                // Already exists in group set
                                                echo "<option value='".$attribute_taxonomy->attribute_name."' disabled='disabled'>".esc_html( $attribute_taxonomy->attribute_label )." (".esc_html($attribute_taxonomy->attribute_name).")</option>";
                                            } else {
                                                echo "<option value='".$attribute_taxonomy->attribute_name."'>".esc_html( $attribute_taxonomy->attribute_label )." (".esc_html($attribute_taxonomy->attribute_name).")</option>";
                                            }
                                        }

                                        ?>
                                    </select>
                                    </p>
                                </div>

                                <p class="submit"><button type="submit" name="add_new_attribute" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Add attribute', 'woocommerce' ); ?>"><?php esc_html_e( 'Add attribute', 'woocommerce' ); ?></button></p>
                                <?php wp_nonce_field( 'woocommerce-add-new_attribute' ); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                /* <![CDATA[ */

                jQuery( 'a.delete' ).click( function() {
                    if ( window.confirm( '<?php esc_html_e( 'Are you sure you want to delete this attribute?', 'woocommerce' ); ?>' ) ) {
                        return true;
                    }
                    return false;
                });

                /* ]]> */
            </script>
        </div>
        <?php
    }
}