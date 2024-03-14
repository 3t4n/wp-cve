<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once(FAST_FLOW_DIR . '/includes/fast-tagger-users-functions.php');
require_once(FAST_FLOW_DIR . '/includes/fast-tagger-pages.php');
require_once(FAST_FLOW_DIR . '/includes/fast-tagger-taxonomy.php');



	add_action('admin_footer','admin_footer_script');

	function admin_footer_script(){

		if(is_admin() && isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'fast-flow-widgets'){ ?>

		<script>

			jQuery(document).ready(function($){

				$('.ff_from').datepicker({dateFormat: 'dd-mm-yy'});

				$('.ff_to').datepicker({dateFormat: 'dd-mm-yy'});

				$( document ).on( 'widget-added widget-updated', function(event, widget) {

                    if($(widget).attr('id').indexOf("fast_tagger")>0){

                        $(widget).find(".tags_field").selectize({ plugins: ['remove_button'],create: false});

                        widget.find('.ff_from').each(function () {

                            if ($(this).hasClass('hasDatepicker')) {
                            $(this).removeClass('hasDatepicker');
                            }
                            $(this).datepicker({dateFormat: 'dd-mm-yy'});
                        })

                        widget.find('.ff_to').each(function () {

                            if ($(this).hasClass('hasDatepicker')) {
                            $(this).removeClass('hasDatepicker');
                            }
                            $(this).datepicker({dateFormat: 'dd-mm-yy'});
                        })
                    }
                });


			});

		</script>

	<?php	}

	}

add_action('after_tag_applied_hook','ft_after_tag_applied',10,2);



	function ft_after_tag_applied($term_id,$user_id){

		global $wpdb;

		//echo $tag.'<br/>';

		$term = get_term($term_id, 'fast_tag', 'ARRAY_A');

		$info = get_userdata($user_id);



		$obj = $wpdb->get_results("SELECT settings_data FROM {$wpdb->prefix}fastflow_settings WHERE settings_for = 'Active Campaign'");
		$global_list = 0;
		if($obj){

			//settings coming from FF Plugins settings page

			$fmxtraoptions = unserialize($obj[0]->settings_data);

			$global_list = (int)$fmxtraoptions['listid'];

			$switch = $fmxtraoptions['switch'];

		}

		$ac_list_id = empty(get_term_meta($term_id,'tag_list',true))?$global_list:get_term_meta($term_id,'tag_list',true);

		$ac_tags = $term['name'];



		$contact = array(

		   "email"                 => $info->user_email,

		   "first_name"            => $info->first_name,

		   "last_name"             => $info->last_name,

		   "p[{$ac_list_id}]"      => $ac_list_id,

		   "tags"                  => $ac_tags,

		   "status[{$ac_list_id}]" => 1, // "Active" status

		);

        if(function_exists('is_fac_active') && is_fac_active()){

		  $ac = fast_AC_api_ready();

		  $contact_sync = $ac->api("contact/sync", $contact);
        }

	}

add_action('init', 'fast_tagger_create_link_terms');

function fast_tagger_create_link_terms()

{

    if (!empty($_GET['ftag']) && $_GET['ftag'] !== '') {

        $create_term_name = $_GET['ftag'];

        $parent_term_id   = get_option('fast_tag_link_type');

        if (!term_exists($create_term_name, 'fast_tag')) {

            $new_term = wp_insert_term($create_term_name, 'fast_tag', array(

                'parent' => $parent_term_id

            ));

        } //!term_exists($create_term_name, 'fast_tag')

        else {

            $new_term = get_term_by('name', $create_term_name, 'fast_tag', ARRAY_A);
			if(!$new_term){
				$new_term = get_term_by('slug', $create_term_name, 'fast_tag', ARRAY_A);
			}

        }

        if (!is_wp_error($new_term) && !empty($new_term) && !empty($new_term['term_id']) && $new_term['term_id'] !== '' && ((!empty($_GET['email']) && $_GET['email'] !== '') || is_user_logged_in())) {

            $create_term_email = empty($_GET['email']) ? '' : sanitize_email($_GET['email']);

            $user              = empty($create_term_email) ? wp_get_current_user() : get_user_by('email', $create_term_email);

            $base_term         = get_term_by('name', 'Link Tag', 'fast_tag', ARRAY_A);

            $term_ids          = array(

                $base_term['term_id'],

                $new_term['term_id']

            );

            wp_set_object_terms($user->ID, $term_ids, 'fast_tag', true);

			//var_dump($new_term);

			do_action('after_tag_applied_hook',$new_term['term_id'],$user->ID);

        } //!is_wp_error($new_term) && !empty($new_term) && !empty($new_term['term_id']) && $new_term['term_id'] !== '' && ((!empty($_GET['email']) && $_GET['email'] !== '') || is_user_logged_in())

    } //!empty($_GET['ftag']) && $_GET['ftag'] !== ''

}



add_action('FM_after_member_registered', 'fast_tagger_create_subscriber_terms', 20, 3);

function fast_tagger_create_subscriber_terms($fm_member_id, $prodid = 0, $userid = 0)

{
   	/*Add tags (Product tags) to user */
		$prodid_arr =  $tags = [];
		$bundle_prods = fm_get_bundle_products_id($prodid);

		if($bundle_prods !== false) {
			$prodid_arr = $bundle_prods;
		}
		$prodid_arr[] = $prodid;

		foreach($prodid_arr as $prod_id) {

			$option_name ='pro_tags_'.$prod_id;

			$option_value = get_option( $option_name , true);

			if(is_array($option_value) && !empty($option_value)){

				$tags[] = array_map('intval',$option_value);

			}

		}
		if($tags){
			$tags = call_user_func_array('array_merge', $tags);
			wp_set_object_terms($userid, $tags, 'fast_tag', true);
			do_action('FM_after_tag_applied_hook',$tags, $userid);

			if ( is_plugin_active( 'fast-activecampaign/fast-activecampaign.php' ) ) {
				if(fast_AC_api_ready()){
					add_tagged_users_to_active_campaign_list($userid, $tags);
				}
			}
		}
}


function fast_tagger_add_members_terms ( $txn_idx_id, $prodid = 0, $userid = 0 ) {

	if ( empty($prodid) || empty($userid) ) {
		return;
	}

	$prodid_arr = array();

	$bundle_prods = fm_get_bundle_products_id($prodid);

	if($bundle_prods !== false) {
		$prodid_arr = $bundle_prods;
	}
	$prodid_arr[] = $prodid;

	foreach($prodid_arr as $prod_id) {

		/*Add tags (Product tags) to user */
		$option_name ='pro_tags_'.$prod_id;
		$option_value = get_option( $option_name , true);

		if(is_array($option_value) && !empty($option_value)){

			$tags = array_map('intval',$option_value);

			wp_set_object_terms($userid, $tags, 'fast_tag', true);

			do_action('FM_after_transaction_tag_applied_hook',$tags, $userid);

			add_tagged_users_to_active_campaign_list($userid, $tags);

		}
	}
}
add_action( 'FM_after_transaction_recorded', 'fast_tagger_add_members_terms', 20, 3 );


function fast_tagger_add_members_refunded_terms ( $txn_idx_id) {

	if ( empty($txn_idx_id) ) {
		return;
	}
	global $wpdb;
	$transactionRec = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpbn_transactions WHERE txn_id=%s", $txn_idx_id ) );
	$prodid_arr = array();

	$bundle_prods = fm_get_bundle_products_id($transactionRec->prodid);

	if($bundle_prods !== false) {
		$prodid_arr = $bundle_prods;
	}
	$prodid_arr[] = $transactionRec->prodid;

	foreach($prodid_arr as $prod_id) {

		/*Add tags (Refunded Product tags) to user */
		$option_name ='pro_refunded_tags_'.$prod_id;
		$option_value = get_option( $option_name , true);

		if(is_array($option_value) && !empty($option_value)){

			$tags = array_map('intval',$option_value);

			wp_set_object_terms($transactionRec->userid, $tags, 'fast_tag', true);

		}
	}
}

add_action( 'FM_after_transaction_refunded', 'fast_tagger_add_members_refunded_terms', 20, 1 );


function fast_tagger_add_members_cancelled_terms ( $userid = 0, $prodid = 0 ) {

	if ( empty($prodid) || empty($userid) ) {
		return;
	}

	$prodid_arr = array();

	$bundle_prods = fm_get_bundle_products_id($prodid);

	if($bundle_prods !== false) {
		$prodid_arr = $bundle_prods;
	}
	$prodid_arr[] = $prodid;

	foreach($prodid_arr as $prod_id) {

		/*Add tags (Cancelled Product tags) to user */
		$option_name ='pro_cancelled_tags_'.$prod_id;
		$option_value = get_option( $option_name , true);

		if(is_array($option_value) && !empty($option_value)){

			$tags = array_map('intval',$option_value);

			wp_set_object_terms($userid, $tags, 'fast_tag', true);

		}
	}
}

add_action( 'FM_after_subscription_cancelled', 'fast_tagger_add_members_cancelled_terms', 20, 2 );


function fast_tagger_enqueue_scripts()

{

	if(is_admin()){

        wp_enqueue_style( 'wp-color-picker');

        wp_enqueue_script( 'wp-color-picker');

	}

    wp_enqueue_style('ut-style', FAST_FLOW_URL . 'assets/css/style.css');

		wp_register_script('fast_tagger_js', FAST_FLOW_URL . 'assets/js/fast_tagger_script.js',array('selectize','wp-color-picker'),'1.0', false);

		if(is_admin()){
    	wp_enqueue_script('fast_tagger_js');
		}

    //filter for search tags

    wp_register_style('selectize', FAST_FLOW_URL . 'assets/css/selectize.default.css', array(), '1.0', false);

    wp_register_script('selectize', FAST_FLOW_URL . 'assets/js/selectize.js', array(

        'jquery'

    ), '1.0', true);

    if (wp_style_is('selectize', 'registered') && wp_script_is('selectize', 'registered')) {

        wp_enqueue_style('selectize');

        wp_enqueue_script('selectize');

    } //wp_style_is('selectize', 'registered') && wp_script_is('selectize', 'registered')

}

add_action('admin_enqueue_scripts', 'fast_tagger_enqueue_scripts', 5, 0);

add_action('wp_enqueue_scripts', 'fast_tagger_enqueue_scripts', 5, 0);

/**
 * Admin ajax URL
 */

function fast_tagger_admin_ajax()

{

?>

       <script type="text/javascript">

               var ajaxurl = <?php

    echo json_encode(admin_url("admin-ajax.php"));

?>;

       </script><?php

}
add_action('wp_head', 'fast_tagger_admin_ajax');

function fast_tagger_ajax_url()

{

?>

    <script type="text/javascript">

            $fast_tagger_ajax_url =

            <?php

    echo json_encode(admin_url('admin-ajax.php'));

?>

    </script><?php

}

add_action('in_admin_footer', 'fast_tagger_ajax_url');

add_action('wp_footer', 'fast_tagger_ajax_url');


// Register Custom Taxonomy

function custom_taxonomy() {



	$labels = array(

		'name'                       => _x( 'Tags', 'Taxonomy General Name', 'text_domain' ),

		'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'text_domain' ),

		'menu_name'                  => __( 'Taxonomy', 'text_domain' ),

		'all_items'                  => __( 'All Items', 'text_domain' ),

		'parent_item'                => __( 'Parent Item', 'text_domain' ),

		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),

		'new_item_name'              => __( 'New Item Name', 'text_domain' ),

		'add_new_item'               => __( 'Add New Item', 'text_domain' ),

		'edit_item'                  => __( 'Edit Item', 'text_domain' ),

		'update_item'                => __( 'Update Item', 'text_domain' ),

		'view_item'                  => __( 'View Item', 'text_domain' ),

		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),

		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),

		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),

		'popular_items'              => __( 'Popular Items', 'text_domain' ),

		'search_items'               => __( 'Search Items', 'text_domain' ),

		'not_found'                  => __( 'Not Found', 'text_domain' ),

		'no_terms'                   => __( 'No items', 'text_domain' ),

		'items_list'                 => __( 'Items list', 'text_domain' ),

		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),

	);

	$args = array(

		'labels'                     => $labels,

		'hierarchical'               => true,

		'public'                     => true,

		'show_ui'                    => true,

		'show_admin_column'          => true,

		'show_in_nav_menus'          => true,

		'show_tagcloud'              => true,

	);

	register_taxonomy( 'for_tag', array( 'post' ), $args );



}

add_action( 'init', 'custom_taxonomy', 0 );



add_filter('fm_prod_third_party_int', 'fast_tagger_third_party_int_html', 14, 1);



function fast_tagger_third_party_int_html($content){

	$terms = get_terms(array('taxonomy'=>'fast_tag','hide_empty'=>false));

	$product_id = isset($_REQUEST['prodid'])?$_REQUEST['prodid']:'';

	$option_name = 'pro_tags_'.$product_id;

	$product_tags = get_option($option_name);

	$refunded_option_name = 'pro_refunded_tags_'.$product_id;

	$product_refunded_tags = get_option($refunded_option_name);

	$cancelled_option_name = 'pro_cancelled_tags_'.$product_id;

	$product_cancelled_tags = get_option($cancelled_option_name);

	$select = '';
	$refunded_select = '';
	$cancelled_select = '';

	$options = '';
	$refunded_options = '';
	$cancelled_options = '';

	if(is_array($terms) && !empty($terms)){

		foreach($terms as $term){

			if(is_array($product_tags)){

				$select = in_array($term->term_id,$product_tags)?'selected="selected"':'';

			}

				$options.= "<option ".$select." value='".$term->term_id."'>".$term->name."</option>";

			if(is_array($product_refunded_tags)){

				$refunded_select = in_array($term->term_id,$product_refunded_tags)?'selected="selected"':'';

			}

				$refunded_options.= "<option ".$refunded_select." value='".$term->term_id."'>".$term->name."</option>";

			if(is_array($product_cancelled_tags)){

				$cancelled_select = in_array($term->term_id,$product_cancelled_tags)?'selected="selected"':'';

			}

				$cancelled_options.= "<option ".$cancelled_select." value='".$term->term_id."'>".$term->name."</option>";

		}

	}else{

		$msg = _ft("There is no tags available. Please add new tags.","fast-tagger");

	}



			$content.= "<h2>"._ft(' Fast Tags Integration','fast-tagger')."</h2>

				<div><p>

				"._ft('You are ready to integrate with Fast Tags','fast-tagger')."<br />

				"._ft('When you add tags these tags will be available for users. you can tag user by editing his profile.')."<br />

				<table cellpadding=10 cellspacing=0 width='70%'>

					<tr><td width='20%'>"._ft('Add Tags','fast-tagger').":</td>

						<td width='80%'>";

				if(isset($msg)){

					$content.="<p style='color:red;'>".$msg."</p>";

				}

				$content.= "<select id='add_tags' name='add_tags[]' placeholder='Select tags for users' multiple>

						".$options."

						</select>

					    <input type='hidden' name='product_id' value='".$_REQUEST['prodid']."' />

					</td></tr>

					<tr>

					<td width='20%'>"._ft('Add Refunded Tags','fast-tagger').":</td>

						<td width='80%'>";



						$content.= "<select id='add_refunded_tags' name='add_refunded_tags[]' placeholder='Select tags for users' multiple>

						".$refunded_options."

						</select>

					</td>

					</tr>
					<tr>

					<td width='20%'>"._ft('Add Cancelled Tags','fast-tagger').":</td>

						<td width='80%'>";



						$content.= "<select id='add_cancelled_tags' name='add_cancelled_tags[]' placeholder='Select tags for users' multiple>

						".$cancelled_options."

						</select>

					</td>

					</tr>

				</table>

				</p></div>";

		return $content;

}



function ft_tag_add_meta_field($term) {

	// this will add the custom meta field to the add new term page

	$t_id = $term->term_id;

	?>

	<div class="form-field">

		<label for="color-picker"><?php _e( 'Select Tag Color', 'fast-tagger' ); ?></label>

		<input type="text" class="color-field" name="color" id="color-field" value="<?php echo get_term_meta( $t_id, 'tag_color', true );?>">

	</div>

	<?php if(function_exists('is_fac_active') && is_fac_active()){

			$ac = fast_AC_api_ready();

           	$all_lists = $ac->api( 'list/list', array( 'ids' => 'all' ) );

			$list_arr = json_decode( json_encode( $all_lists ), true );



			$options = '<option value="">Select Tag List</option>';

			foreach($list_arr as $list){

				if(is_array($list)){

					$selected = (get_term_meta( $t_id, 'tag_list', true ) == $list["id"])?"selected='selected'":"";

					$options .= '<option '.$selected.' value="'.$list["id"].'" >'.$list["name"].'</option>';

				}

			}

	?>

	<div class="form-field">

		<label for="color-picker"><?php _e( 'Select Tag Active Campaign List', 'fast-tagger' ); ?></label>

		<select class="" name="list" id="list-field">

		<?php echo $options;?>

		</select>

	</div>

	<?php } ?>

<?php

}

// Edit term page

function ft_tag_edit_meta_field($term) {



	// put the term ID into a variable

	$t_id = $term->term_id;



	// retrieve the existing value(s) for this meta field.

	 ?>

	<tr class="form-field">

	<th scope="row" valign="top"><label for="color-picker"><?php _e( 'Select Tag Color', 'fast-tagger' ); ?></label></th>

		<td>

			<input type="text" class="color-field" name="color" id="color-field" value="<?php echo get_term_meta( $t_id, 'tag_color', true ); ?>">

		</td>

	</tr>

	<?php if(function_exists('is_fac_active') && is_fac_active()){

			$ac = fast_AC_api_ready();

			$all_lists = $ac->api( 'list/list', array( 'ids' => 'all' ) );

			$list_arr = json_decode( json_encode( $all_lists ), true );



			$options = '<option value="">Select Tag List</option>';

			foreach($list_arr as $list){

				if(is_array($list)){

					$selected = (get_term_meta( $t_id, 'tag_list', true ) == $list["id"])?"selected='selected'":"";

					$options .= '<option '.$selected.' value="'.$list["id"].'" >'.$list["name"].'</option>';

				}

			}



	?>

	<tr class="form-field">

	<th scope="row" valign="top"><label for="color-picker"><?php _e( 'Select Active Campaign List', 'fast-tagger' ); ?></label></th>

		<td>

			<select class="" name="list" id="list-field">

			<?php echo $options;?>

			</select>

		</td>

	</tr>

	<?php } ?>

<?php

}

add_action( 'fast_tag_add_form_fields', 'ft_tag_add_meta_field', 10, 2 );

add_action( 'fast_tag_edit_form_fields', 'ft_tag_edit_meta_field', 10, 2 );



function save_tag_meta_field( $term_id ) {



	//error_log("<pre>".print_r($_POST)."<br/>");

	if ( isset( $_POST['color'] ) ) {

		$previous = get_term_meta($term_id,'tag_color',true);

		update_term_meta($term_id,'tag_color',wp_filter_nohtml_kses($_POST['color']),$previous);

	}

	if ( isset( $_POST['list'] ) ) {

		$previous = get_term_meta($term_id,'tag_list',true);

		update_term_meta($term_id,'tag_list',$_POST['list'],$previous);

	}

}

add_action( 'edited_fast_tag', 'save_tag_meta_field', 10, 2 );

add_action( 'create_fast_tag', 'save_tag_meta_field', 10, 2 );



add_action('admin_footer','fast_tagger_assign_color',12,1);

function fast_tagger_assign_color($term){



	$terms = get_terms(array('taxonomy'=>'fast_tag','hide_empty' => false,'fields'=>'ids','meta_key'=>'tag_color'));

	foreach($terms as $term_id){ ?>

		<script type="text/javascript">

			jQuery(document).ready(function($){

				$(".selectize-input").find('[data-value="<?php echo $term_id;?>"]').css({"background":"<?php echo get_term_meta($term_id, 'tag_color', true);?>"});

			});

		</script>

<?php

	}



	$screen = get_current_screen();

	//print "<pre>";print_r($screen);exit;

	if($screen->id == "edit-fast_tag")

		echo '<style>.term-description-wrap {display:none !important;}</style>';



}
