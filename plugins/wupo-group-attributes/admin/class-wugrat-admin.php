<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wugrat-admin-attributes-in-group.php';
use  Automattic\WooCommerce\Admin\Features\Navigation\Menu ;
use  Automattic\WooCommerce\Admin\Features\Navigation\Screen ;
use  Automattic\WooCommerce\Admin\Features\Features ;
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    wugrat
 * @subpackage wugrat/admin
 * @author     wupo
 */
class Wugrat_Admin
{
    private  $plugin_name ;
    private  $version ;
    private  $plugin_admin_attributes_in_group ;
    private  $taxonomy_group = 'wugrat_group' ;
    private  $option_key_group_order = 'wugrat_group_order' ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_admin_attributes_in_group = new Wugrat_Admin_Attributes_In_Group();
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    public function register_taxonomy_wugrat_group()
    {
        $labels = [
            'name'              => _x( 'Attribute Group', 'taxonomy general name' ),
            'singular_name'     => _x( 'Attribute Group', 'taxonomy singular name' ),
            'search_items'      => __( 'Search Attribute Group' ),
            'all_items'         => __( 'All Attribute Group' ),
            'parent_item'       => __( 'Parent Attribute Group' ),
            'parent_item_colon' => __( 'Parent Attribute Group:' ),
            'edit_item'         => __( 'Edit Attribute Group' ),
            'update_item'       => __( 'Update Attribute Group' ),
            'add_new_item'      => __( 'Add New Attribute Group' ),
            'new_item_name'     => __( 'New Attribute Group Name' ),
            'menu_name'         => __( 'Attribute Group' ),
            'not_found'         => __( 'No attribute groups found.' ),
            'back_to_items'     => __( 'Back to Attribute Groups' ),
        ];
        $args = [
            'hierarchical'       => false,
            'labels'             => $labels,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_admin_column'  => false,
            'show_in_nav_menus'  => false,
            'show_tagcloud'      => false,
            'publicly_queryable' => false,
            'query_var'          => true,
            'rewrite'            => [
            'slug' => $this->taxonomy_group,
        ],
            'meta_box_cb'        => false,
        ];
        register_taxonomy( $this->taxonomy_group, 'product', $args );
        register_taxonomy_for_object_type( $this->taxonomy_group, 'product' );
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_style()
    {
        $screen = get_current_screen();
        if ( $screen->id == 'edit-wugrat_group' || $screen->id == 'product_page_wugrat_attributes_in_group' ) {
            wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array() );
        }
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_script()
    {
        $screen = get_current_screen();
        
        if ( $screen->id == 'edit-wugrat_group' || $screen->id == 'product_page_wugrat_attributes_in_group' ) {
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script(
                'wugrat-admin-group-attributes-ordering',
                plugin_dir_url( __FILE__ ) . 'js/wugrat-admin-group-attributes-ordering.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            $group_term_id = ( isset( $_GET['term_id'] ) ? wc_clean( wp_unslash( $_GET['term_id'] ) ) : '' );
            $wugrat_admin_group_attributes_ordering_params = array(
                'screen_id'     => $screen->id,
                'group_term_id' => $group_term_id,
            );
            wp_localize_script( 'wugrat-admin-group-attributes-ordering', 'wugrat_admin_group_attributes_ordering_params', $wugrat_admin_group_attributes_ordering_params );
        } elseif ( $screen->id == 'product' ) {
            wp_enqueue_script(
                'wugrat-admin-add-group',
                plugin_dir_url( __FILE__ ) . 'js/wugrat-admin-add-group.js',
                array( 'jquery' ),
                $this->version,
                false
            );
        }
    
    }
    
    function enqueue_script_function()
    {
        $screen = get_current_screen();
        if ( $screen->id == 'woocommerce_page_wc-settings' && wugrat_fs()->is_free_plan() ) {
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $('#wc_wugrat_settings_tab_general_title_pro_version-description~table:nth-of-type(2) tr:nth-of-type(2),' +
                        '#wc_wugrat_settings_tab_general_title_pro_version-description~table:nth-of-type(2) tr:nth-of-type(3),' +
                        '#wc_wugrat_settings_tab_general_title_pro_version-description~table:nth-of-type(2) tr:nth-of-type(4),' +
                        '#wc_wugrat_settings_tab_general_title_pro_version-description~table:nth-of-type(2) tr:nth-of-type(5)').css({ opacity: '40%' });
                });
            </script>
			<?php 
        }
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    public function submenu_group_add()
    {
        add_submenu_page(
            null,
            'Attribute Group',
            'Attribute Group',
            'manage_product_terms',
            'wugrat_attributes_in_group',
            array( $this, 'output_attributes_in_group_page' )
        );
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function output_attributes_in_group_page()
    {
        $plugin_admin_attributes_in_group = $this->plugin_admin_attributes_in_group;
        $plugin_admin_attributes_in_group::output();
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    public function submenu_group_highlight_when_active( $parent_file )
    {
        $screen = get_current_screen();
        if ( $screen->id == 'product_page_wugrat_attributes_in_group' ) {
            ?>
            <script>
                jQuery(document).ready( function($) {
                    var reference = $("a[href=\'edit-tags.php?taxonomy=wugrat_group&post_type=product\']").parent();
                    reference.addClass('current');
                });
            </script>
			<?php 
        }
        return $parent_file;
    }
    
    function wugrat_settings_render_tabs()
    {
        // check user capabilities
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
        //Get the active tab from the $_GET param
        $default_tab = null;
        $tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab );
        if ( isset( $_POST['action'] ) and $_POST['action'] == 'save_settings' ) {
            woocommerce_update_options( $this->wc_settings_wugrat_tab_get_settings() );
        }
        ?>
		<div class="wrap">
			<div class="wrap fs-section fs-full-size-wrapper">
				<h2 class="nav-tab-wrapper" id="settings">
					<a href="?page=wugrat_settings&tab=settings" class="nav-tab fs-tab nav-tab-active home">Settings</a>
				</h2>
				<br><br><br>
			</div>

			<div class="tab-content">
				<?php 
        switch ( $tab ) {
            default:
                ?>
						<div class="wrap">
							<form action="?page=wugrat_settings&tab=settings" method="post">
								<input type="hidden" name="action" value="save_settings">
								<?php 
                woocommerce_admin_fields( $this->wc_settings_wugrat_tab_get_settings() );
                submit_button();
                ?>
							</form>
						</div>
						<?php 
                break;
        }
        ?>
			</div>
		</div>
		<?php 
    }
    
    function wugrat_add_submenu()
    {
        add_submenu_page(
            'options-general.php',
            'WUPO Group Attributes',
            'WUPO Group Attributes',
            'manage_options',
            'wugrat_settings',
            array( $this, 'wugrat_settings_render_tabs' )
        );
    }
    
    function wc_settings_wugrat_tab_save_initial()
    {
        // On initial load (settings page has never been saved yet from the admin page), this setting id is likely to be false.
        // Therefore the default value for the setting 'enable wugrat' which is true needs to be set
        $enable_wugrat = get_option( 'wc_wugrat_settings_tab_styling_text_color_odd_row' );
        
        if ( $enable_wugrat == false ) {
            update_option( 'wc_wugrat_settings_tab_general_enable_wugrat', 'yes' );
            update_option( 'wc_wugrat_settings_tab_styling_layout', '1' );
        }
    
    }
    
    function add_link_to_settings_on_plugin_page( $settings )
    {
        $setting_link = "<a href='" . get_admin_url( null, 'options-general.php?page=wugrat_settings' ) . "'>" . __( 'Settings', 'wupo-group-attributes' ) . "</a>";
        array_unshift( $settings, $setting_link );
        return $settings;
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function wc_settings_wugrat_tab_get_settings()
    {
        $settings = array(
            'pro_version'                            => array(
            'name' => __( 'Get The Pro Version', 'wupo-group-attributes' ),
            'type' => 'title',
            'desc' => 'Packed with many additional features:<br /><ol>' . '<li><b>Various Layouts</b><br />Choose between layout options for presenting the attribute groups or create your own template for complete freedom</li>' . '<li><b>Group sets</b><br />Assign attribute groups to a set for direct loading of all attributes when editing a product</li>' . '<li><b>More settings</b><br />Define if groups are shown above or below single attributes, or define a separate title for single attributes</li>' . '<li><b>Shortcodes</b><br />Add the attribute groups wherever you like on the website with shortcodes. Supported parameters include the layout option and product ids</li>' . '<li><b>Duplicate attributes</b><br />Create a copy/clone of an attribute with all terms with one click from the admin</li>' . '</ol><a href="https://wupoplugins.com/group-attributes-woocommerce/" target="_blank">Get more information</a><p>&nbsp;</p>',
            'id'   => 'wc_wugrat_settings_tab_general_title_pro_version',
        ),
            'general'                                => array(
            'name' => __( 'General', 'wupo-group-attributes' ),
            'type' => 'title',
            'id'   => 'wc_wugrat_settings_tab_general_title_section',
        ),
            'enable_wugrat'                          => array(
            'name'     => __( 'WUPO Group Attributes', 'wupo-group-attributes' ),
            'type'     => 'checkbox',
            'default'  => 'yes',
            'desc'     => __( 'Enable', 'wupo-group-attributes' ),
            'desc_tip' => __( 'Enable Plugin WUPO Group Attributes for WooCommerce', 'wupo-group-attributes' ),
            'id'       => 'wc_wugrat_settings_tab_general_enable_wugrat',
        ),
            'position_single_attributes'             => array(
            'name'    => __( 'Position Single Attributes (PRO)', 'wupo-group-attributes' ),
            'type'    => 'select',
            'options' => array(
            0 => __( 'Above Attribute Groups', 'wupo-group-attributes' ),
            1 => __( 'Below Attribute Groups', 'wupo-group-attributes' ),
        ),
            'default' => '1',
            'id'      => 'wc_wugrat_settings_tab_general_position_single_attributes',
        ),
            'single_attributes_label'                => array(
            'name'    => __( 'Title Single Attributes (PRO)', 'wupo-group-attributes' ),
            'type'    => 'text',
            'default' => __( 'More', 'wupo-group-attributes' ),
            'desc'    => __( 'The title for the section with all single attributes. If empty no title will be shown', 'wupo-group-attributes' ),
            'id'      => 'wc_wugrat_settings_tab_general_single_attributes_label',
        ),
            'position_dimension_attributes'          => array(
            'name'    => __( 'Position Dimension/Weight Attributes (PRO)', 'wupo-group-attributes' ),
            'type'    => 'select',
            'options' => array(
            0 => __( 'Separate Section, Above Attribute Groups Or Above Single Attributes', 'wupo-group-attributes' ),
            1 => __( 'Separate Section, Below Attribute Groups Or Below Single Attributes', 'wupo-group-attributes' ),
            2 => __( 'Within Single Attributes At The Top', 'wupo-group-attributes' ),
            3 => __( 'Within Single Attributes At The Bottom', 'wupo-group-attributes' ),
        ),
            'default' => '3',
            'id'      => 'wc_wugrat_settings_tab_general_position_dimension_attributes',
        ),
            'dimension_attributes_label'             => array(
            'name'    => __( 'Title Dimension/Weight Attributes (PRO)', 'wupo-group-attributes' ),
            'type'    => 'text',
            'default' => __( 'Dimension / Weight', 'wupo-group-attributes' ),
            'desc'    => __( 'The title for the section with the dimension/weight attributes. If empty no title will be shown. Depends on position setting for dimension/weight above', 'wupo-group-attributes' ),
            'id'      => 'wc_wugrat_settings_tab_general_dimension_attributes_label',
        ),
            'section_end1'                           => array(
            'type' => 'sectionend',
            'id'   => 'wc_wugrat_settings_tab_section_end1',
        ),
            'styling'                                => array(
            'name' => __( 'Styling', 'wupo-group-attributes' ),
            'type' => 'title',
            'id'   => 'wc_wugrat_settings_tab_general_section_title',
        ),
            'layout'                                 => array(
            'name'    => __( 'Layout', 'wupo-group-attributes' ),
            'type'    => 'select',
            'options' => array(
            1 => __( 'Layout 1', 'wupo-group-attributes' ),
        ),
            'default' => '0',
            'desc'    => __( 'See <a href="https://wupoplugins.com/group-attributes-woocommerce/" target="_blank">here</a> under section \'Various Layouts\' for examples. The free version only supports the option \'Layout 1\'', 'wupo-group-attributes' ),
            'id'      => 'wc_wugrat_settings_tab_styling_layout',
        ),
            'enable_customize_attribute_table_color' => array(
            'name'    => __( 'Customize Attribute Table Colors', 'wupo-group-attributes' ),
            'type'    => 'checkbox',
            'default' => 'no',
            'desc'    => __( 'Enable', 'wupo-group-attributes' ),
            'id'      => 'wc_wugrat_settings_tab_styling_enable_customize_attribute_table_color',
        ),
            'text_color_odd_row'                     => array(
            'name'    => __( 'Text Color Odd Row', 'wupo-group-attributes' ),
            'type'    => 'color',
            'default' => '#801e1e',
            'desc'    => 'Option \'Customize Attribute Table Colors\' must be enabled',
            'id'      => 'wc_wugrat_settings_tab_styling_text_color_odd_row',
        ),
            'background_color_odd_row'               => array(
            'name'    => __( 'Background Color Odd Row', 'wupo-group-attributes' ),
            'type'    => 'color',
            'default' => '#fbb6b6',
            'desc'    => 'Option \'Customize Attribute Table Colors\' must be enabled',
            'id'      => 'wc_wugrat_settings_tab_styling_background_color_odd_row',
        ),
            'text_color_even_row'                    => array(
            'name'    => __( 'Text Color Even Row', 'wupo-group-attributes' ),
            'type'    => 'color',
            'default' => '#097c9f',
            'desc'    => 'Option \'Customize Attribute Table Colors\' must be enabled',
            'id'      => 'wc_wugrat_settings_tab_styling_text_color_even_row',
        ),
            'background_color_even_row'              => array(
            'name'    => __( 'Background Color Even Row', 'wupo-group-attributes' ),
            'type'    => 'color',
            'default' => '#c0eafb',
            'desc'    => 'Option \'Customize Attribute Table Colors\' must be enabled',
            'id'      => 'wc_wugrat_settings_tab_styling_background_color_even_row',
        ),
            'section_end2'                           => array(
            'type' => 'sectionend',
            'id'   => 'wc_wugrat_settings_tab_section_end2',
        ),
        );
        return $settings;
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    public function group_customize_table()
    {
        add_filter(
            'manage_' . $this->taxonomy_group . '_custom_column',
            array( $this, 'group_table_row_values' ),
            15,
            3
        );
        add_filter( 'manage_edit-' . $this->taxonomy_group . '_columns', array( $this, 'group_table_add_columns' ) );
        add_filter(
            $this->taxonomy_group . '_row_actions',
            array( $this, 'group_table_row_actions' ),
            10,
            2
        );
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function group_table_row_values( $row, $column_name, $term_id )
    {
        global  $wpdb ;
        
        if ( 'attributes' === $column_name ) {
            //TODO: Refactor to method
            $query = $wpdb->prepare( "SELECT children FROM {$wpdb->term_taxonomy} WHERE term_id=%d", array( $term_id ) );
            $results = $wpdb->get_results( $query );
            $attribute_children = explode( ',', $results[0]->children, -1 );
            $attribute_taxonomies = array();
            foreach ( $attribute_children as $attribute_child ) {
                $query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name=%s", array( substr( $attribute_child, 3 ) ) );
                $results = $wpdb->get_results( $query );
                if ( !empty($results) ) {
                    $attribute_taxonomies[] = $results[0]->attribute_label;
                }
            }
            
            if ( $attribute_taxonomies ) {
                $attribute_list = implode( ', ', $attribute_taxonomies );
            } else {
                $attribute_list = '';
            }
            
            //return $attribute_list . '<br><a href="'.menu_page_url('atrm_attribute_group', false).'&post_type=product&term_id='.$term_id.'">Configure Attributes</a>';
            return $attribute_list . "<br><a href='edit.php?post_type=product&page=wugrat_attributes_in_group&term_id={$term_id}'>Configure Attributes</a>";
        } elseif ( 'handle' === $column_name ) {
            return "<input type='hidden' name='group_term_id' value='{$term_id}' />";
        }
    
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function group_table_add_columns( $original_columns )
    {
        unset( $original_columns['description'] );
        unset( $original_columns['posts'] );
        $new_columns['attributes'] = esc_html__( 'Attributes', '' );
        $new_columns['handle'] = '';
        $merged_columns = array_merge( $original_columns, $new_columns );
        return $merged_columns;
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function group_table_row_actions( $actions, $tag )
    {
        unset( $actions['view'] );
        unset( $actions['inline hide-if-no-js'] );
        return $actions;
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    public function group_table_sorting( $terms, $taxonomies, $args )
    {
        if ( $taxonomies[0] == $this->taxonomy_group ) {
            usort( $terms, array( $this, 'group_table_sorting_compare' ) );
        }
        return $terms;
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function group_table_sorting_compare( $a, $b )
    {
        $option_group_order = explode( ',', get_option( $this->option_key_group_order ), -1 );
        if ( $a->term_id == $b->term_id ) {
            return 0;
        }
        $cmpa = array_search( $a->term_id, $option_group_order );
        $cmpb = array_search( $b->term_id, $option_group_order );
        return ( $cmpa > $cmpb ? 1 : -1 );
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function group_create_term( $term_id )
    {
        $group_order = get_option( $this->option_key_group_order );
        $group_order = $term_id . ',' . $group_order;
        update_option( $this->option_key_group_order, $group_order );
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function group_delete_term( $term_id )
    {
        $group_order = get_option( $this->option_key_group_order );
        $group_order = str_replace( $term_id . ',', '', $group_order );
        update_option( $this->option_key_group_order, $group_order );
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function group_update_children_reference( $attribute_id, $attribute, $old_attribute_name )
    {
        global  $wpdb ;
        $new_attribute_name = $attribute['attribute_name'];
        $query = $wpdb->prepare( "UPDATE {$wpdb->term_taxonomy} SET children = REPLACE(children, %s, %s) WHERE taxonomy = %s", array( $old_attribute_name, $new_attribute_name, $this->taxonomy_group ) );
        $wpdb->get_results( $query );
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function product_edit_add_group_attribute_toolbar()
    {
        ?>
        <div class="toolbar" style="height: 30px;">
		<span class="expand-close">
            <select name="attribute_group" class="attribute_group">
                <option value=""><?php 
        esc_html_e( 'Attribute group', 'wupo-group-attributes' );
        ?></option>
                <?php 
        $attribute_group_terms = get_terms( array(
            'taxonomy'   => $this->taxonomy_group,
            'hide_empty' => false,
        ) );
        if ( !empty($attribute_group_terms) ) {
            foreach ( $attribute_group_terms as $attribute_group_term ) {
                $attribute_group_term_id = wc_sanitize_taxonomy_name( $attribute_group_term->term_id );
                $label = $attribute_group_term->name;
                $name = $attribute_group_term->slug;
                echo  '<option value="' . esc_attr( $attribute_group_term_id ) . '">' . esc_html( $label ) . ' (' . esc_html( $name ) . ')</option>' ;
            }
        }
        ?>
		    </select>
		    <button type="button" class="button add_attribute_group"><?php 
        esc_html_e( 'Add', 'woocommerce' );
        ?></button>

            &nbsp;&nbsp;&nbsp;&nbsp;
            <select name="attribute_group_set" class="attribute_group_set">
                <option value="">
                    <?php 
        $notice_pro_version = '';
        if ( wugrat_fs()->is_free_plan() ) {
            $notice_pro_version = ' (PRO version required)';
        }
        esc_html_e( 'Attribute group set' . $notice_pro_version, 'wupo-group-attributes' );
        ?></option>
                <?php 
        ?>
		    </select>
		    <button type="button" class="button add_attribute_group_set"><?php 
        esc_html_e( 'Add', 'woocommerce' );
        ?></button>
		</span>
        </div>

        <!--    Add attribute slug to attribute name in select -->
        <script>
            jQuery(document).ready(function ($) {
                $(".attribute_taxonomy option").each(function(index) {
                    if (index > 0) {
                        attribute_slug = $(this).val().substring(3);
                        $(this).html($(this).text() + ' (' + attribute_slug + ')');
                    }
                });
            });
        </script>
		<?php 
    }
    
    function product_edit_add_group_name_to_single_attribute_metabox( $attribute, $i )
    {
        $attribute_name = $attribute['name'];
        $attribute_name_in_brackets = '';
        $attribute_label = wc_attribute_label( $attribute_name );
        if ( $attribute_name !== '' ) {
            $attribute_name_in_brackets = "(" . substr( $attribute_name, 3 ) . ")";
        }
        
        if ( $attribute_name !== '' && substr( $attribute_name, 0, 3 ) === 'pa_' ) {
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $("div[data-taxonomy='<?php 
            echo  esc_attr( $attribute_name ) ;
            ?>'] strong.attribute_name").html(function (i, val) {
                        return '<?php 
            echo  esc_attr( "{$attribute_label} {$attribute_name_in_brackets}" ) ;
            ?>';
                    });
                });
            </script>
			<?php 
        }
    
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function ajax_add_attribute_group()
    {
        global  $wpdb ;
        ob_start();
        check_ajax_referer( 'add-attribute', 'security' );
        if ( !current_user_can( 'edit_products' ) || !isset( $_POST['group_term_id'], $_POST['i'] ) ) {
            wp_die( -1 );
        }
        $group_term_id = ( isset( $_POST['group_term_id'] ) && (int) $_POST['group_term_id'] ? (int) $_POST['group_term_id'] : null );
        $i = absint( $_POST['i'] );
        //TODO: Refactor to method
        $query = $wpdb->prepare( "SELECT children FROM {$wpdb->term_taxonomy} WHERE term_id=%d", array( $group_term_id ) );
        $results = $wpdb->get_results( $query );
        $attributes_of_group = explode( ',', $results[0]->children, -1 );
        wupo_log( 'attributes of group', $attributes_of_group );
        // Parse data array from metaboxes from the frontend
        parse_str( wp_unslash( $_POST['data'] ), $data );
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        
        if ( !empty($data) ) {
            $data = array_values( $data['attribute_names'] );
            $result = array_intersect( $data, $attributes_of_group );
            foreach ( $result as $item ) {
                $key = array_search( $item, $attributes_of_group );
                unset( $attributes_of_group[$key] );
            }
        }
        
        wupo_log( 'attributes to add', $attributes_of_group );
        foreach ( $attributes_of_group as $attribute_of_group ) {
            $metabox_class = array();
            $attribute = new WC_Product_Attribute();
            $attribute->set_id( wc_attribute_taxonomy_id_by_name( sanitize_text_field( wp_unslash( $attribute_of_group ) ) ) );
            $attribute->set_name( sanitize_text_field( wp_unslash( $attribute_of_group ) ) );
            $attribute->set_visible( apply_filters( 'woocommerce_attribute_default_visibility', 1 ) );
            $attribute->set_variation( apply_filters( 'woocommerce_attribute_default_is_variation', 0 ) );
            
            if ( $attribute->is_taxonomy() ) {
                $metabox_class[] = 'taxonomy';
                $metabox_class[] = $attribute->get_name();
            }
            
            include WP_PLUGIN_DIR . '/woocommerce/includes/admin/meta-boxes/views/html-product-attribute.php';
            $i++;
        }
        wp_die();
    }
    
    /**
     * -
     *
     * @since    1.0.0
     */
    function ajax_group_attributes_ordering()
    {
        global  $wpdb ;
        $screen_id = ( isset( $_POST['screen_id'] ) ? sanitize_text_field( wp_unslash( $_POST['screen_id'] ) ) : null );
        $group_term_id = ( isset( $_POST['group_term_id'] ) && (int) $_POST['group_term_id'] ? (int) $_POST['group_term_id'] : null );
        $attribute_name = ( isset( $_POST['attribute_name'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute_name'] ) ) : null );
        $prev_attribute_name = ( isset( $_POST['prev_attribute_name'] ) ? sanitize_text_field( wp_unslash( $_POST['prev_attribute_name'] ) ) : null );
        $next_attribute_name = ( isset( $_POST['next_attribute_name'] ) ? sanitize_text_field( wp_unslash( $_POST['next_attribute_name'] ) ) : null );
        
        if ( $screen_id == 'edit-wugrat_group' ) {
            $group_order = get_option( $this->option_key_group_order );
            $group_order = str_replace( $attribute_name . ',', '', $group_order );
            
            if ( empty($next_attribute_name) ) {
                // Moved attribute to the last position
                $group_order .= $attribute_name . ',';
            } else {
                // Moved attribute to any position except last one
                $pos = strpos( $group_order, $next_attribute_name );
                $group_order = substr_replace(
                    $group_order,
                    $attribute_name . ',',
                    $pos,
                    0
                );
            }
            
            update_option( $this->option_key_group_order, $group_order );
        } elseif ( $screen_id == 'product_page_wugrat_attributes_in_group' ) {
            $attribute_name = 'pa_' . $attribute_name;
            //$prev_attribute_name = 'pa_' . $prev_attribute_name;
            if ( !empty($next_attribute_name) ) {
                $next_attribute_name = 'pa_' . $next_attribute_name;
            }
            //TODO: refactor to method
            $query = $wpdb->prepare( "SELECT children FROM {$wpdb->term_taxonomy} WHERE term_id=%d", array( $group_term_id ) );
            $results = $wpdb->get_results( $query );
            $attributes_in_group = $results[0]->children;
            $attributes_in_group = str_replace( $attribute_name . ',', '', $attributes_in_group );
            
            if ( empty($next_attribute_name) ) {
                // Moved attribute to the last position
                $attributes_in_group .= $attribute_name . ',';
            } else {
                // Moved attribute to any position except last one
                $pos = strpos( $attributes_in_group, $next_attribute_name );
                $attributes_in_group = substr_replace(
                    $attributes_in_group,
                    $attribute_name . ',',
                    $pos,
                    0
                );
            }
            
            $query = $wpdb->prepare( "UPDATE {$wpdb->term_taxonomy} SET children = %s WHERE term_id = %d", array( $attributes_in_group, $group_term_id ) );
            $wpdb->get_results( $query );
        }
    
    }

}