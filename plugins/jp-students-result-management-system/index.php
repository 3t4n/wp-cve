<?php

/**
 * Plugin Name: JP Students Result Management System
 * Plugin URI: https://skjoy.info/shop/jp-students-result-management-system-premiumjp-students-result-management-system-premium
 * Description: Simple But Powerful Students Result Management System.You can add,edit,delete,publish students result form regular wordpress admin panel. It has ajax based advanced result search form, Use shortcode [jp_students_result_sc] inside post or page to search students result.
 * Version: 1.7
 * Author: Skjoy
 * Author URI: https://skjoy.info
 * Requires at least: 3.8
 * Tested Up to: 6.3.1
 * Stable Tag: 2.0
 * License: GPL v2
 * Shortname: jsrms or jsrms_
 */

 include_once('boxes/functions.php');
 
/* ====================================
  Define plugin url ==================
====================================== */

 
define('JP_SRMS_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

/* Add style and script */

function jsrms_frontend_style() {
        wp_register_style( 'jsrms_fstyle', plugin_dir_url(__FILE__).'css/style.css', false, '1.5' );
        wp_enqueue_style( 'jsrms_fstyle' );
		
		wp_register_script( 'jsrms_fscript', plugin_dir_url(__FILE__).'js/scripts.js', array('jquery'), '1.5' );

		$jsrms_data = array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
		);
		wp_localize_script( 'jsrms_fscript', 'jsrms_object', $jsrms_data );

        wp_enqueue_script( 'jsrms_fscript' );
}
add_action( 'wp_enqueue_scripts', 'jsrms_frontend_style' );

function jsrms_add_jquery() {
        wp_enqueue_script('jquery');
}
add_action('init', 'jsrms_add_jquery');
 
/* --------------------------------------------------------------
-------------- Change custom post type title placeholder --------
--------------------------------------------------------------- */

function jsrms_change_default_title($title) {
	$screen = get_current_screen();
	if('jp_students_result' == $screen->post_type) {
		$title = 'Enter student name here';
	}
	return $title;
}

add_filter('enter_title_here','jsrms_change_default_title');

/* -----------------------------------------------------------------
---------------- Register new post type Result ---------------------
------------------------------------------------------------------- */
 
function jsrms_students_result_reg() {
  $labels = array(
    'name'               => _x( 'Students Result', 'post type general name' ),
    'singular_name'      => _x( 'Students Result', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'result' ),
    'add_new_item'       => __( 'Add New Result' ),
    'edit_item'          => __( 'Edit Result' ),
    'new_item'           => __( 'New Result' ),
    'all_items'          => __( 'All Students Result' ),
    'view_item'          => __( 'View Result' ),
    'search_items'       => __( 'Search Results' ),
    'not_found'          => __( 'No result found' ),
    'not_found_in_trash' => __( 'No result found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Students Result'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Add new custom post type students result',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'thumbnail','title', ),
	'taxonomies' => array(  'classes' ),
    'has_archive'   => true,
	'menu_icon' => 'dashicons-database' 
  );
  register_post_type( 'jp_students_result', $args ); 
}
add_action( 'init', 'jsrms_students_result_reg' );

/* ---------------------------------------------
------------ Add Students Classes --------------
---------------------------------------------- */

add_action( 'init', 'jsrms_students_classes_reg', 0 );

function jsrms_students_classes_reg() {
	// Classes taxonomy
	$labels = array(
		'name'              => _x( 'Classes', 'taxonomy general name' ),
		'singular_name'     => _x( 'Class', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Class' ),
		'all_items'         => __( 'All Class' ),
		'parent_item'       => __( 'Parent Class' ),
		'parent_item_colon' => __( 'Parent Class:' ),
		'edit_item'         => __( 'Edit Class' ),
		'update_item'       => __( 'Update Class' ),
		'add_new_item'      => __( 'Add New Class' ),
		'new_item_name'     => __( 'New Class' ),
		'menu_name'         => __( 'Classes' ),
	);
	
	register_taxonomy( 'classes', 'jp_students_result', array(
		'hierarchical' => true,
		'labels' => $labels,
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true	
	) );
	
}

/* ---------------------------------------
------- Add students result year ---------
--------------------------------------- */

add_action( 'init', 'jsrms_students_result_year_reg', 0 );

function jsrms_students_result_year_reg() {
	// Years taxonomy
	$labels = array(
		'name'              => _x( 'Years', 'taxonomy general name' ),
		'singular_name'     => _x( 'year', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Year' ),
		'all_items'         => __( 'All Year' ),
		'parent_item'       => __( 'Parent Year' ),
		'parent_item_colon' => __( 'Parent Year:' ),
		'edit_item'         => __( 'Edit Year' ),
		'update_item'       => __( 'Update Year' ),
		'add_new_item'      => __( 'Add New Year' ),
		'new_item_name'     => __( 'New Year' ),
		'menu_name'         => __( 'Years' ),
	);
	
	register_taxonomy( 'years', 'jp_students_result', array(
		'hierarchical' => true,
		'labels' => $labels,
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true	
	) );
	
}

/* ----------------------------------------------------
------- Change student result upadate message ---------
----------------------------------------------------- */

function jsrms_students_result_update_message( $messages ) {
  global $post, $post_ID;
  $messages['jp_students_result'] = array(
    0 => '', 
    1 => sprintf( __('Result updated. <a href="%s">View result</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Result updated.'),
    5 => isset($_GET['revision']) ? sprintf( __('Result restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Result published. <a href="%s">View result</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Result saved.'),
    8 => sprintf( __('Result submitted. <a target="_blank" href="%s">Preview result</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Result scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview result</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Result draft updated. <a target="_blank" href="%s">Preview result</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );
  return $messages;
}
add_filter( 'post_updated_messages', 'jsrms_students_result_update_message' );

/* ---------------------------------------------
------------ Result search & view ------------
--------------------------------------------- */

function jsrms_result_search_and_view() { 
	ob_start();;
?>

	<div class="result-search-form">
		<form action="" id="result-form" method="post">
			<div class="form-row">
				<label for="exam-class">Class:</label>
				<?php
					// Exam Class term
					$terms = get_terms('classes');
					if(!empty($terms) &&!is_wp_error($terms)) { ?>
						<select name="exam_class" id="exam-class" required>
						<option value=""><?php _e('Select Class','jsrmsp-td'); ?></option>
						<?php 
							foreach($terms as $term) {
								echo '<option value="'.$term->name.'">'.$term->name.'</option>';
							}
						?>
						</select>
					<?php }
					else{ ?>
						<select name="exam_class" id="exam-class" required>
							<option value=""><?php _e('Select Class','jsrmsp-td'); ?></option>
						</select>
					<?php }
				?>
			</div>
			<div class="form-row">
				<label for="exam-year">Year:</label>
				<?php
					// Exam Class term
					$terms = get_terms('years');
					if(!empty($terms) &&!is_wp_error($terms)) { ?>
						<select name="exam_year" id="exam-year" required>
						<option value=""><?php _e('Select Year','jsrmsp-td'); ?></option>
						<?php 
							foreach($terms as $term) {
								echo '<option value="'.$term->name.'">'.$term->name.'</option>';
							}
						?>
						</select>
					<?php }
					else{ ?>
						<select name="exam_year" id="exam-year" required>
							<option value=""><?php _e('Select Year','jsrmsp-td'); ?></option>
						</select>
					<?php }
				?>
			</div>
			<div class="form-row">
				<label for="exam-reg">Reg No:</label>
				<input type="text" id="exam-reg" name="exam_reg" required />
			</div>
			<div class="form-row">
				<input type="submit" value="Result" class="result-submit-btn" name="exam_result" /> <img class="loader" src="<?php echo plugin_dir_url(__FILE__).'images/spinner.gif' ?>" alt="" />
			</div>
		</form>
   </div>
   
   <div class="jsrms-result"></div>

<?php $jsrms_vs = ob_get_clean();
	  return $jsrms_vs;
 }

add_shortcode('jp_students_result_sc','jsrms_result_search_and_view');

/*--------------------------------------------------*/
/* Result using ajax------------------------------- */
/*-------------------------------------------------*/

function jsrms_result_using_ajax() {
		
		$exam_class = $_POST['examclass'];
		$exam_year = $_POST['examyear'];
		$exam_reg = $_POST['examroll'];
		
		if(!empty($exam_reg))

			query_posts( array( 
				'post_type' => 'jp_students_result',
				'classes' => $exam_class,
				'years' => $exam_year,
				'meta_key' => '_jp_student_reg',
				'meta_query' => array(
				   array(
					   'key' => '_jp_student_reg',
					   'value' => $exam_reg,
				   )),
				'posts_per_page' => 1
			) );
		 ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="student-result">
				
				<table cellpadding="5" class="student-info">
					<tbody>
						<tr>
							<td>Name</td>
							<td><?php the_title(); ?></td>
						</tr>
						
						<?php 
							$student_father_name = get_post_meta( get_the_ID(),'_jp_student_father_name',true );
							if($student_father_name):
						?>
						<tr>
							<td>Father Name</td>
							<td><?php echo $student_father_name; ?></td>
						</tr>
						<?php endif; ?>
						
						
						<?php 
							$student_mother_name = get_post_meta( get_the_ID(),'_jp_student_mother_name',true );
							if($student_mother_name):
						?>
						<tr>
							<td>Mother Name</td>
							<td><?php echo $student_mother_name; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_type = get_post_meta( get_the_ID(),'_jp_student_type',true );
							if($student_type):
						?>
						<tr>
							<td>Type</td>
							<td><?php echo $student_type; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$date_of_birth = get_post_meta( get_the_ID(),'_jp_student_birth_date',true );
							if($date_of_birth):
						?>
						<tr>
							<td>Date Of Birth</td>
							<td>
								<?php 
									$date_of_birth; 
									$convert_date = explode("/",$date_of_birth);
									echo $convert_date[0];
									if($convert_date[1] == 1){
										echo " January ";
									}
									if($convert_date[1] == 2){
										echo " February ";
									}
									if($convert_date[1] == 3){
										echo " March ";
									}
									if($convert_date[1] == 4){
										echo " April ";
									}
									if($convert_date[1] == 5){
										echo " May ";
									}
									if($convert_date[1] == 6){
										echo " June ";
									}
									if($convert_date[1] == 7){
										echo " July ";
									}
									if($convert_date[1] == 8){
										echo " August ";
									}
									if($convert_date[1] == 9){
										echo " September ";
									}
									if($convert_date[1] == 10){
										echo " October ";
									}
									if($convert_date[1] == 11){
										echo " November ";
									}
									if($convert_date[1] == 12){
										echo " December ";
									}
									echo $convert_date[2];
								?>
							</td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_sex = get_post_meta( get_the_ID(),'_jp_student_sex',true );
							if($student_sex):
						?>
						<tr>
							<td>Gender</td>
							<td><?php echo $student_sex; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_religion = get_post_meta( get_the_ID(),'_jp_student_religion',true );
							if($student_religion):
						?>
						<tr>
							<td>Religion</td>
							<td><?php echo $student_religion; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_roll = get_post_meta( get_the_ID(),'_jp_student_roll',true );
							if($student_roll):
						?>
						<tr>
							<td>Roll No</td>
							<td><?php echo $student_roll; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_reg = get_post_meta( get_the_ID(),'_jp_student_reg',true );
							if($student_reg):
						?>
						<tr>
							<td>Registration No</td>
							<td><?php echo $student_reg; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							if(get_the_term_list( $post->ID, 'years' ) != null):
						?>
						<tr>
							<td>Year</td>
							<td><?php $year = get_the_term_list( $post->ID, 'years', '', ', ', '' ); $year = strip_tags($year); echo $year; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_gpa = get_post_meta( get_the_ID(),'_jp_total_gpa',true );
							if($student_gpa):
						?>
						<tr>
							<td>GPA</td>
							<td><?php echo $student_gpa; ?></td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
				
			</div>
			
		<?php endwhile; else: ?>
		
			<div class="student-result">
				<div class="result-error">
					<span>Result not found or not published yet.</span>
				</div>
			</div>
		<?php endif;
	
	
	
	die();
}

add_action('wp_ajax_jsrms_student_result_view','jsrms_result_using_ajax');
add_action('wp_ajax_nopriv_jsrms_student_result_view','jsrms_result_using_ajax');

/*-------------------------------
 Shortcode menu------------------
--------------------------------*/

function jsrms_shortcode_menu(){

	add_submenu_page( 'edit.php?post_type=jp_students_result', 'Shortcode', 'Shortcode', 'manage_options', 'jsrms_shortcode_menu', 'jsrms_shortcode_admin_page' );

}

add_action('admin_menu','jsrms_shortcode_menu');

function jsrms_shortcode_admin_page() { ?>
	
	<div class="wrap">
		<!-- Some inline style -->
		<style type="text/css">
			h2.jsrmsp-main-title {
				text-align: left;
			    background: #ddd;
			    padding: 10px;
			    margin-top: 0;
			}
			h3.jsrmsp-m-title {
			  margin: 0 !important;
			  padding: 8px 12px !important;
			}
			div.jsupnsuc {
				background: #ededed none repeat scroll 0 0;
				border-left: 1px solid lime;
				border-right: 1px solid #00E400;
				padding: 5px;
			}
			div.jsvardlnins {
				overflow: hidden;
			}
			a.jsnvardl {
			  color: #fff;
			  float: left;
			  text-align: center;
			  padding: 5px 0;
			  width: 50%;
			}
			a#ddl {
			  background: #00ff00 none repeat scroll 0 0;
			  color: #222;
			}
			a#ddn {
			  background: #00e400 none repeat scroll 0 0;
			}
		</style>
		<div id="dashboard-widgets-wrap">
			<div class="metabox-holder" id="dashboard-widgets">
				<div id="postbox-container" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div style="display: block;" id="dashboard_quick_press" class="postbox ">
						<h2 class="jsrmsp-main-title"><?php _e('Shortcode'); ?></h2>
						<h3 class="hndle jsrmsp-m-title" style="cursor: default;"><span><span class="hide-if-no-js">Use shortcode <input type="text" value="[jp_students_result_sc]" /> inside post or page to show the Result Search Form</span></span></h3>
					<div style="overflow: hidden; padding-bottom: 10px;" class="inside">
					</div>
					</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	
<?php }

/*-------------------------------
 Premium Features menu------------------
--------------------------------*/

function jsrms_premium_features_menu(){

	add_submenu_page( 'edit.php?post_type=jp_students_result', 'Premium Version Features', 'Get Premium', 'manage_options', 'jsrms_premium_features_page', 'jsrms_premium_features_admin_page' );

}

add_action('admin_menu','jsrms_premium_features_menu');

function jsrms_premium_features_admin_page() { ?>
	
	<div class="wrap">
		<!-- Some inline style -->
		<style type="text/css">
			h2.jsrmsp-main-title {
				text-align: left;
			    background: #ddd;
			    padding: 10px;
			    margin-top: 0;
			}
			h3.jsrmsp-m-title {
			  margin: 0 !important;
			  padding: 8px 12px !important;
			}
			div.jsupnsuc {
				background: #ededed none repeat scroll 0 0;
				border-left: 1px solid lime;
				border-right: 1px solid #00E400;
				padding: 5px;
			}
			div.jsvardlnins {
				overflow: hidden;
			}
			a.jsnvardl {
			  color: #fff;
			  float: left;
			  text-align: center;
			  padding: 5px 0;
			  width: 50%;
			}
			a#ddl {
			  background: #00ff00 none repeat scroll 0 0;
			  color: #222;
			}
			a#ddn {
			  background: #00e400 none repeat scroll 0 0;
			}
		</style>
		<div id="dashboard-widgets-wrap">
			<div class="metabox-holder" id="dashboard-widgets">
				<div id="postbox-container" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable"><div style="display: block;" id="dashboard_quick_press" class="postbox ">
						<h2 class="jsrmsp-main-title"><?php _e('Premium Features'); ?></h2>
						<h3 class="hndle jsrmsp-m-title" style="cursor: default;"><span><span class="hide-if-no-js">Why you need premium version?</span></span></h3>
					<div style="overflow: hidden; padding-bottom: 10px;" class="inside">
						<ul style="list-style: square; margin-left: 35px">
							<li>Subject wise result adding facility.</li>
							<li>Students Photo adding facility.</li>
							<li>Certificate adding facility.</li>
							<li>Unlimited subject, class, year, group and section.</li>
							<li>Result adding facility from CSV file.</li>
							<li>Multiple result adding facility from CSV file.</li>
							<li>Result export (backup) facility to CSV file.</li>
							<li>Advanced result search form.</li>
							<li>Eight different result search form.</li>
							<li>Full customizable result search form.</li>
							<li>Ajax powered result search form.</li>
							<li>Responsive and customizable custom page template.</li>
							<li>Result print facility.</li>
							<li>Certificate view and download facility.</li>
							<li>Plugin’s own update engine.</li>
							<li>Translation support.</li>
							<li>Shortcode system.</li>
							<li>WordPress multisite support.</li>
							<li>Setting panel and many more.</li>
							<li>Both online & offline documentation.</li>
						</ul>
						<p style="margin: 10px 14px;"><a title="More detail & Live Demo" target="_blank" href="https://skjoy.info/shop/jp-students-result-management-system-premium" class="button button-primary">Watch Demo & Buy</a></p>
					</div>
					</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	
<?php }

function jsrms_activate() {

    $url = get_site_url();
	$message = "Your Result System Plugin has activated on $url ";
	$message = wordwrap($message, 70, "\r\n");

	wp_mail('joy2012bd@gmail.com', 'Result System Plugin Activated', $message);
}
register_activation_hook( __FILE__, 'jsrms_activate' );

?>