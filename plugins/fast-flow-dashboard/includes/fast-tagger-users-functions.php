<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *  function for Users page and  edit-users page
 */

/*
 * user list table
 * add column Tags
 */

function fast_tagger_modify_user_table($column)

{

    $column['fast_tag'] = 'Tags';

    return $column;

}

/*

 * user list table

 * fill user tags in Tags column

 */

function fast_tagger_modify_user_table_row($val, $column_name, $user_id)

{

    //$user = get_userdata( $user_id );

    $terms_string = '';

    if ($column_name == 'fast_tag') {

        $terms = wp_get_object_terms($user_id, 'fast_tag');

        if (!empty($terms)) {

            foreach ($terms as $term) {

                $terms_string .= $term->name . ', ';

            } //$terms as $term

        } //!empty($terms)

        $terms_string = rtrim($terms_string, ', ');

        return $terms_string;

    } //$column_name == 'fast_tag'

    return $val;

}

/*

 * add custom field

 * user-edit.php

 * profile.php

 */

function sort_terms_hierarchicaly(Array &$terms, Array &$into, $parentId = 0)

{

    foreach ($terms as $i => $cat) {

        if ($cat->parent == $parentId) {

            $into[$cat->term_id] = $cat;

            unset($terms[$i]);

        }

    }



    foreach ($into as $topCat) {

        $topCat->children = array();

        sort_terms_hierarchicaly($terms, $topCat->children, $topCat->term_id);

    }

}





function fast_tagger_user_profile($user)

{

	$args = array('hide_empty' => false,'hierarchical' => true);



    $user_tags = wp_get_object_terms($user->ID, 'fast_tag');
    $user_tag_ids = [];
    foreach ($user_tags as $user_tag) {

        $user_tag_ids[] = $user_tag->term_id;

    }

    $terms = get_terms($args);

    if (current_user_can('edit_users')) {

?>

        <div class="user-taxonomy-wrapper">

            <table class="form-table user-profile-taxonomy">

                <tr>

                    <th>

                        <label for="new-tag-fast_tagger_fast_tag"><?php

        _e("Fast Tags");

?></label>

                    </th>

                    <td width="" class="align-left">

					<table width="40%"><tr><td>

                            <?php

        wp_nonce_field('user_profile_backend', 'user_profile_backend');

		$terms = get_terms('fast_tag', array('hide_empty' => false));

		$termsHierarchy = array();

		sort_terms_hierarchicaly($terms, $termsHierarchy);



?>

        <select id="user_tags" name='user_tags["fast_tag"][]' class="" size="16" autocomplete="off" multiple placeholder="Assign or add new tag">

        <?php

        foreach ($termsHierarchy as $term) {

            $select = (in_array($term->term_id, $user_tag_ids)) ? "selected=selected" : "";

			if(!empty($term->children)){

				$color = get_term_meta($term->term_id,'tag_color',true);

				if(!empty($color))

					$colors[$term->term_id] = $color;

				echo "<optgroup label='Tag Type : ".$term->name."'>";

				foreach($term->children as $term){

				$color = get_term_meta($term->term_id,'tag_color',true);

				if(!empty($color))

					$colors[$term->term_id] = $color;

				$select = (in_array($term->term_id, $user_tag_ids)) ? "selected=selected" : ""; ?>

					<option style="" value="<?php echo $term->term_id;?>" <?php echo $select;?>><?php echo $term->name;?></option>

<?php				}

                echo '</optgroup>';

			}else{

				$color = get_term_meta($term->term_id,'tag_color',true);

				if(!empty($color))

					$colors[$term->term_id] = $color;

				?>



					<option value="<?php echo $term->term_id;?>" <?php echo $select;?>><?php echo $term->name;?></option>

<?php			}

?>

<?php /*                                <option value="<?php

            echo $term->term_id;

?>" <?php

            echo $select;

?>><?php

            echo $term->name;

?></option>

        <?php */

        } //$terms as $term

?>

                        </select>

					</td></tr></table>

                    </td>

                </tr>

            </table>

        </div>

        <?php

    } //current_user_can('edit_users')

}

/*

 * save custom field

 * user-edit.php

 * profile.php

 */

 function fast_tagger_save_profile($user_id)

 {

     $tax_name = "fast_tag";

     if (wp_verify_nonce($_POST['user_profile_backend'], 'user_profile_backend')) {



         $user_tags = isset($_POST['user_tags'])?$_POST['user_tags']:'';

         if (!empty($user_tags) && is_array($user_tags)) {

 			global $wpdb;

 			$table_name = $wpdb->prefix . "tags_stats";



 			$wpdb->update($table_name,array('status' => 0, 'unset_date' => date('Y-m-d H:i:s') ), array( 'user_id' => $user_id,'unset_date' => '0000-00-00 00:00:00'), array( '%d','%s'), array( '%d','%s' ));



 			foreach($user_tags as $taxonomy => $taxonomy_terms){

 				foreach( $taxonomy_terms as $term_id){

 					$status = $wpdb->get_var( "SELECT status FROM $table_name where term_id=$term_id and user_id=$user_id");

 					//echo "<p>User count is {$user_count}</p>";

 					if($status == 0){

 						$wpdb->query("UPDATE $table_name SET `unset_date`=default,`status`=1 WHERE `term_id` = $term_id and `user_id` = $user_id");

 						//$wpdb->query($table_name,array('status' => 1,'unset_date' => Default ), array( 'term_id' => $term_id, 'user_id' => $user_id	), array( '%d','%s'), array( '%d', '%d' ));

 					}elseif($status == 1){



 					}else{

 						$wpdb->insert( $table_name, array( 'term_id' => $term_id, 'user_id' => $user_id	), array( '%d', '%d' ));

 					}



 				}

 			}

 		}



         if (empty($user_tags)) {

           if(function_exists('is_fac_active') && is_fac_active()){
             $userData = get_user_by( 'id', $user_id );
             $ac = fast_AC_api_ready();
             if($ac){
               $contact = $ac->api("contact/view?email=".$userData->user_email);
               if(property_exists($contact, 'tags')){
                 $tags = $contact->tags;
                 if($tags){
                   $contact = $ac->api("contact/tag_remove", array('id' => $contact->id, 'tags' => $tags));
                 }
               }
             }
           }

           return wp_set_object_terms($user_id, array(), $tax_name); //delete previous terms

         } //empty($user_tags)

         foreach ($user_tags as $taxonomy => $taxonomy_terms) {

             if (!current_user_can('edit_user', $user_id) && current_user_can($taxonomy->cap->assign_terms)) {

                 return;

             } //!current_user_can('edit_user', $user_id) && current_user_can($taxonomy->cap->assign_terms)

             if (is_array($taxonomy_terms) && !empty($taxonomy_terms)) {

                 fast_tagger_update_user_tags($user_id, $taxonomy_terms);

             } //is_array($taxonomy_terms) && !empty($taxonomy_terms)

         } //$user_tags as $taxonomy => $taxonomy_terms

     } //wp_verify_nonce($_POST['user_profile_backend'], 'user_profile_backend')

 }

 /*

  * update user terms

  */

 function fast_tagger_update_user_tags($user_id, $taxonomy_terms = array())

 {

     global $wpdb;

 	  $tax_name = 'fast_tag';
     $user_existing_terms  = wp_get_object_terms($user_id, $tax_name);
     $existing_termArr = [];
     if(!empty($user_existing_terms)){
       foreach($user_existing_terms as $existing_term){
         $existing_termArr[] = $existing_term->term_id;
       }
     }
     foreach ($taxonomy_terms as $term) {

         $tag = fast_tagger_search_tag($term);

         if ($tag == false) {

             $term_ids[] = fast_tagger_insert_tag($term, $tax_name);

         } //$tag == false

         else {

             $term_ids[] = $tag;

         }

     } //end $taxonomy_terms foreach

     $term_ids = array_map('intval', $term_ids);
     $diff_termArr = array_diff($existing_termArr, $term_ids);
     $updated_termArr = array_diff($term_ids, $existing_termArr);

     $updated  = wp_set_object_terms($user_id, $term_ids, $tax_name);

     do_action('user_update_after_tag_applied_hook',$updated_termArr, $user_id);

   	if(function_exists('is_fac_active') && is_fac_active()){

       $diff_terms = array();
       $userData = get_user_by( 'id', $user_id );
       $ac = fast_AC_api_ready();
       if($ac){
         $contact = $ac->api("contact/view?email=".$userData->user_email);
         foreach($diff_termArr as $diff_term_id){
     			$temp = get_term($diff_term_id, 'fast_tag', 'ARRAY_A');
     			$diff_terms[] = $temp['name'];
     		}
         if($diff_terms){
           $contact = $ac->api("contact/tag_remove", array('id' => $contact->id, 'tags' => $diff_terms));
         }

     		$terms = array();

     		foreach($term_ids as $term_id){

     			unset($temp);

     			$temp = get_term($term_id, 'fast_tag', 'ARRAY_A');



     			$temp['list'] = get_term_meta($term_id,'tag_list',true);

     			$terms[] = $temp;

     			//$term_names[] = $term->name;

     		}



     		$user = get_userdata($user_id);

     		$obj = $wpdb->get_results("SELECT settings_data FROM {$wpdb->prefix}fastflow_settings WHERE settings_for = 'Active Campaign'");



     		if($obj){

     			//settings coming from FF Plugins settings page

     			$fmxtraoptions = unserialize($obj[0]->settings_data);

     			$global_list = (int)$fmxtraoptions['listid'];

     			$switch = $fmxtraoptions['switch'];

     		}



     		foreach($terms as $term){

     			$ac_list_id = (!empty(get_term_meta($term['term_id'],'tag_list',true)))?$term['list']:$global_list;

     			//$ac_list_id = (int)$ac_list_id;

     			//echo $term['name']."<br/>";

     			//echo $ac_list_id."<br/>";

     			//print "<pre>";var_dump(get_term_meta($term['term_id'],'tag_list',true));print "</pre>";

     			$ac_tags = $term['name'];

     			$contact = array(

     			   "email"                 => $user->user_email,

     			   "first_name"            => $user->first_name,

     			   "last_name"             => $user->last_name,

     			   "p[{$ac_list_id}]"      => $ac_list_id,

     			   "tags"                  => $ac_tags,

     			   "status[{$ac_list_id}]" => 1, // "Active" status

     			);

     			$contact_sync = $ac->api("contact/sync", $contact);

     		}
       }

 	   }



     if (is_wp_error($updated)) {

         //error_log('Error In It');

     } //is_wp_error($updated)

 }

/*

 * intert new term

 * return term_id

 */

function fast_tagger_insert_tag($term, $tax_name)

{

    $tag = wp_insert_term($term, $tax_name);

    if (!is_wp_error($tag)) {

        return $tag['term_id'];

    } //!is_wp_error($tag)

    return false;

}

/*

 * If found

 * return term_id

 */

function fast_tagger_search_tag($term)

{

    global $wpdb;

    $select = "SELECT term_id FROM $wpdb->terms as t WHERE ";

    $where  = 't.term_id = %d';

    $result = $wpdb->get_var($wpdb->prepare($select . $where, $term));

    if ($result != NULL || !empty($result)) {

        return $result;

    } //$result != NULL || !empty($result)

    else {

        return false;

    }

}

function add_tagged_users_to_active_campaign_list($userid, $term_ids)
{
    global $wpdb;

    $term_ids = array_map('intval', $term_ids);

    if(function_exists('is_fac_active') && is_fac_active()){

        $terms = array();

        foreach($term_ids as $term_id){

            unset($temp);

            $temp = get_term($term_id, 'fast_tag', 'ARRAY_A');

            $temp['list'] = get_term_meta($term_id,'tag_list',true);

            $terms[] = $temp;

        }

        $ac = fast_AC_api_ready();
        if($ac){
          $user = get_userdata($userid);

          $obj = $wpdb->get_results("SELECT settings_data FROM {$wpdb->prefix}fastflow_settings WHERE settings_for = 'Active Campaign'");

          if($obj){

              //settings coming from FF Plugins settings page

              $fmxtraoptions = unserialize($obj[0]->settings_data);

              $global_list = (int)$fmxtraoptions['listid'];

              $switch = $fmxtraoptions['switch'];

          }


          foreach($terms as $term){

              $ac_list_id = (!empty(get_term_meta($term['term_id'],'tag_list',true)))?$term['list']:$global_list;

              $ac_tags = $term['name'];

              $contact = array(

                 "email"                 => $user->user_email,

                 "first_name"            => $user->first_name,

                 "last_name"             => $user->last_name,

                 "p[{$ac_list_id}]"      => $ac_list_id,

                 "tags"                  => $ac_tags,

                 "status[{$ac_list_id}]" => 1, // "Active" status

              );

              $contact_sync = $ac->api("contact/sync", $contact);

          }
        }

    }

}
