<?php
if ( ! defined('ABSPATH')) exit;  // if direct access



function job_bm_recursive_sanitize_arr($array) {

    if(is_array($array)){
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = job_bm_recursive_sanitize_arr($value);
            }
            else {
                $value = wp_kses_post( $value );
            }
        }
    }else {
        $value = wp_kses_post( $array );
    }


    return $array;
}



add_action('init', 'job_bm_jetpack_support');
function job_bm_jetpack_support() {
    add_post_type_support( 'job', 'publicize' );
    add_post_type_support( 'company', 'publicize' );
    add_post_type_support( 'location', 'publicize' );
    add_post_type_support( 'resume', 'publicize' );
}


/*
 * Restricted media file only current logged-in user
 *
 * */
add_filter( 'ajax_query_attachments_args', 'job_bm_show_current_user_attachments' );
if(!function_exists('job_bm_show_current_user_attachments')){
    function job_bm_show_current_user_attachments( $query ) {

        $job_bm_restrict_media_file = get_option('job_bm_restrict_media_file', 'yes');

        if($job_bm_restrict_media_file == 'yes'){
            $user_id = get_current_user_id();
            if ( $user_id ) {
                $query['author'] = $user_id;
            }
        }


        return $query;
    }
}















function job_bm_activation_update_email_templates(){

    $class_job_bm_emails = new class_job_bm_emails();
    $templates_data = $class_job_bm_emails->job_bm_email_templates_data();

    $job_bm_email_temp_data_update = get_option('job_bm_email_temp_data_update');

    if(!empty($job_bm_email_temp_data_update)){
        update_option('job_bm_email_templates_data', $templates_data);
        update_option('job_bm_email_temp_data_update', 'done');
    }
}

add_action('job_bm_activation', 'job_bm_activation_update_email_templates');





function job_ids_by_user($user_id=0){

    $user_id = !empty($user_id) ? $user_id : get_current_user_id();

    $job_ids = array();

    $wp_query = new WP_Query(
        array (
            'post_type' => 'job',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'author' => $user_id,
            'posts_per_page' => 5,
        )
    );


    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();
            $job_id = get_the_id();

            $job_ids[$job_id] = (int)$job_id;

        endwhile;

    endif;


    return $job_ids;
}















function job_bm_single_job_view_count(){

	if ( is_singular( 'job' )){
		$cookie_name = 'job_bm_view';
		$job_id = get_the_ID();
		$job_bm_view_count = (int) get_post_meta(get_the_ID(),'job_bm_view_count', true);
		update_post_meta(get_the_ID(), 'job_bm_view_count', ($job_bm_view_count+1));

/*
 *
 * 		if(isset($_COOKIE[$cookie_name.'_'.$job_id])){

		}
		else{
			// Update +1 view count
			setcookie( $cookie_name.'_'.$job_id, $job_id, time() + (86400 * 30)); // 86400 = 1 day
			update_post_meta(get_the_ID(), 'job_bm_view_count', ($job_bm_view_count+1));
		}
 *
 * */

	}

}

add_action('wp_head','job_bm_single_job_view_count');










	
	
	
	
	
function job_bm_ajax_post_id_serialize(){
	
		$attachment_id = sanitize_text_field($_POST['attachment_id']);

		echo serialize(array($attachment_id));
		die();
	}	
	
	
add_action('wp_ajax_job_bm_ajax_post_id_serialize', 'job_bm_ajax_post_id_serialize');
add_action('wp_ajax_nopriv_job_bm_ajax_post_id_serialize', 'job_bm_ajax_post_id_serialize');	
	
	


	
	
	
function job_bm_ajax_reset_email_templates_data(){
	
		$class_job_bm_emails = new class_job_bm_emails();
		$templates_data = $class_job_bm_emails->job_bm_email_templates_data();
	
		update_option('job_bm_email_templates_data', $templates_data);	
	
		die();
	}	
	
	
add_action('wp_ajax_job_bm_ajax_reset_email_templates_data', 'job_bm_ajax_reset_email_templates_data');
add_action('wp_ajax_nopriv_job_bm_ajax_reset_email_templates_data', 'job_bm_ajax_reset_email_templates_data');


	
	
	
	
	
add_filter( 'add_menu_classes', 'job_bm_show_pending_number');

if(!function_exists('job_bm_show_pending_number')){
    function job_bm_show_pending_number( $menu ) {
        $type = "job";
        $status = "pending";
        $num_posts = wp_count_posts( $type, 'readable' );
        $pending_count = 0;
        if ( !empty($num_posts->$status) )
            $pending_count = $num_posts->$status;

        // build string to match in $menu array
        if ($type == 'post') {
            $menu_str = 'edit.php';
            // support custom post types
        } else {
            $menu_str = 'edit.php?post_type=' . $type;
        }

        // loop through $menu items, find match, add indicator
        foreach( $menu as $menu_key => $menu_data ) {
            if( $menu_str != $menu_data[2] )
                continue;
            $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
        }
        return $menu;
    }

}

	
	
	
	


	
	

	
	
	
	
	
	function job_bm_ajax_delete_job_by_id() {
		
		$job_bm_can_user_delete_jobs = get_option('job_bm_can_user_delete_jobs');
		
		$html = '';
		
		
		if($job_bm_can_user_delete_jobs=='no'){
			
			$html.= '<i class="fa fa-exclamation-circle"></i> '.__('You are not authorized to delete this job.','job-board-manager');

			}
		else{
			
			$job_id = (int)sanitize_text_field($_POST['job_id']);
	
			$current_user_id = get_current_user_id();
			
			$post_data = get_post($job_id, ARRAY_A);
	
			$author_id = $post_data['post_author'];		
		
			if( $current_user_id == $author_id ){
				
				if(wp_trash_post($job_id)){
					$html.=	'<i class="fa fa-check"></i> '.__('Job Deleted.','job-board-manager');
					$response['is_deleted'] = 'yes';

					do_action('job_bm_job_trash', $job_id);

					}
				else{
					$html.=	'<i class="fa fa-exclamation-circle"></i> '.__('Something going wrong.','job-board-manager');
					$response['is_deleted'] = 'no';
					}
				}
				
			else{
				
				$html.=	'<i class="fa fa-exclamation-circle"></i> '.__('You are not authorized to delete this job.','job-board-manager');
				$response['is_deleted'] = 'no';
				}
			}

		$response['html'] = $html;

		echo json_encode($response);

		//echo $html;
		
		die();
		}

	add_action('wp_ajax_job_bm_ajax_delete_job_by_id', 'job_bm_ajax_delete_job_by_id');
	//add_action('wp_ajax_nopriv_job_bm_ajax_delete_job_by_id', 'job_bm_ajax_delete_job_by_id');

	

	
	
//add_action( 'init', 'job_bm_revoke_admin_access' );


function job_bm_revoke_admin_access() {
	
	if(is_user_logged_in()){
		
		$job_bm_hide_admin_bar_role = get_option('job_bm_hide_admin_bar_role');
		
		
		
		if(empty($job_bm_hide_admin_bar_role)){
			
			$job_bm_hide_admin_bar_role = array('none');
			}
		
		$current_user = wp_get_current_user();
		$user_role = $current_user->roles;
		
		
		$user_role = $user_role[0];
		//var_dump($user_role);
		//var_dump($job_bm_hide_admin_bar_role);
		
		if(in_array($user_role,$job_bm_hide_admin_bar_role) ){
				header( home_url() ); 
			}
		
		}

	
}
	
	
	
	
//add_action('after_setup_theme', 'job_bm_remove_admin_bar');

function job_bm_remove_admin_bar() {
	
	if(is_user_logged_in()){
		
		$job_bm_hide_admin_bar_role = get_option('job_bm_hide_admin_bar_role');
		
		if(empty($job_bm_hide_admin_bar_role)){
			
			$job_bm_hide_admin_bar_role = array('none');
			}
		
		
		$current_user = wp_get_current_user();
		$user_role = $current_user->roles;
		$user_role = $user_role[0];
		//var_dump($user_role);
		//var_dump($job_bm_hide_admin_bar_role);
		
		if(in_array($user_role,$job_bm_hide_admin_bar_role)){
			show_admin_bar(false);
	
			}
		
		}


}
	






function job_bm_list_user_roles(){

	global $wp_roles;
	$all_roles = $wp_roles->roles;

	foreach($all_roles as $role_key=>$role_data){
		
		$all_roles[$role_key] = $role_data['name'];
		}
		
	return array_merge( array('none'=>__('None','job-board-manager')),$all_roles);
	
	}


	

	
	
	
	
	function job_bm_page_list_id(){

			$wp_query = new WP_Query(
				array (
					'post_type' => 'page',
					'posts_per_page' => -1,
					) );
					
			$pages_ids = array();

            $pages_ids[''] = __('None','job-board-manager');

			if ( $wp_query->have_posts() ) :
			
	
			while ( $wp_query->have_posts() ) : $wp_query->the_post();
			
			$pages_ids[get_the_ID()] = get_the_title();
			
			
			endwhile;
			wp_reset_query();
			endif;
			
			
			return $pages_ids;
		
		}
	
	
	




	function job_bm_get_date(){	
	
			$gmt_offset = get_option('gmt_offset');
			$wpls_datetime = date('Y-m-d', strtotime('+'.$gmt_offset.' hour'));
			
			return $wpls_datetime;
		
		}




function job_bm_get_terms($taxonomy){


    if(!isset($taxonomy)){
			$taxonomy = 'job_category';
			}
		
		
		$args=array(
			
		  'orderby' => 'id',
		  'taxonomy' => $taxonomy,
		  'hide_empty' => false,
		  'parent'  => 0,
		  );
		
		$categories = get_categories($args);

			
		$html = '';
		
		if(!empty($categories)){
			
			foreach($categories as $category){
				
					$name = $category->name;
					$cat_ID = $category->cat_ID;	
				
					$terms[$cat_ID] = 	$name;	
					
					$args_child=array(
						
					  'orderby' => 'id',
					  'taxonomy' => $taxonomy,
					  'hide_empty' => false,
					  'parent'  => $cat_ID,
					  );
					
					$categories_child = get_categories($args_child);
					
					if(!empty($categories_child))
					foreach($categories_child as $category_child){
						
						$name_child = $category_child->name;
						$cat_ID_child = $category_child->cat_ID;	
						
						$terms[$cat_ID_child] = $name_child;
						
						}
					
					
					
					//$html.= '<li cat-id="'.$cat_ID.'"><i class="fa fa-check"></i> '.$name;
					//$html.= '</li>';
	
				}
			
			
			}
		else{
			$terms = array();
			}
		
		
		return $terms;

	}



function job_bm_get_terms_hierarchical($taxonomy){

		
		//$cat_id = (int)sanitize_text_field($_POST['cat_id']);
		if(!isset($taxonomy)){
			$taxonomy = 'job_category';
			}
		
		
		$args=array(
			
		  'orderby' => 'id',
		  'taxonomy' => $taxonomy,
		  'hide_empty' => false,
		  'parent'  => 0,
		  );
		
		$categories = get_categories($args);

			
		$html = '';
		
		if(!empty($categories)){
			
			foreach($categories as $category){
				
					$name = $category->name;
					$cat_ID = $category->cat_ID;	
				
					$terms[$cat_ID] = 	$name;	
					
					$args_child=array(
						
					  'orderby' => 'id',
					  'taxonomy' => $taxonomy,
					  'hide_empty' => false,
					  'parent'  => $cat_ID,
					  );
					
					$categories_child = get_categories($args_child);
					
					if(!empty($categories_child))
					foreach($categories_child as $category_child){
						
						$name_child = $category_child->name;
						$cat_ID_child = $category_child->cat_ID;	
						
						$terms[$cat_ID_child] = $name_child;
						
						}
					
					
					
					//$html.= '<li cat-id="'.$cat_ID.'"><i class="fa fa-check"></i> '.$name;
					//$html.= '</li>';
	
				}
			
			
			}
		else{
			$terms = array();
			}
		
		
		return $terms;

	}













function job_bm_get_child_cats(){

		$html = '';
		$cat_id = (int)sanitize_text_field($_POST['cat_id']);
		
		$taxonomy = 'job_category';
		
		$args=array(
		  'orderby' => 'name',
		  'order' => 'ASC',
		  'taxonomy' => $taxonomy,
		  'hide_empty' => false,
		  'child_of'  => $cat_id,
		  );
		
		$categories = get_categories($args);

		//var_dump();
		
		
		if(!empty($categories)){
			
			foreach($categories as $category){
				
				$name = $category->name;
				$cat_ID = $category->cat_ID;	
		
					$html.= '<li cat-id="'.$cat_ID.'"><i class="fa fa-check"></i> '.$name.'</li>';
	
				}
			
			}
		
		

		
		
		echo $html;
		
		
		die();
		
	}





add_action('wp_ajax_job_bm_get_child_cats', 'job_bm_get_child_cats');
add_action('wp_ajax_nopriv_job_bm_get_child_cats', 'job_bm_get_child_cats');





function job_bm_country_list(){

    $country_list = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island & Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic Of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre And Miquelon',
        'VC' => 'Saint Vincent And Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis And Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    );

    return apply_filters('job_bm_country_list', $country_list);


}
