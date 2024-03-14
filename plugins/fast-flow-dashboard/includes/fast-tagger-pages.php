<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *  All menu pages functions
 */

 /*
  * get tag parent name
  */
function get_tag_parent_name($tag_ID){
	if(term_exists(intval($tag_ID))){
		$tag = get_term(intval($tag_ID), 'fast_tag', 'OBJECT');
		if($tag->parent != 0 ):
			$parent = get_term($tag->parent, 'fast_tag', 'OBJECT');
			$parent_name = $parent->name;
		else:
			$parent_name = $tag->name;
		endif;
		return $parent_name;
	}
}
function fast_tags_list() {

    global $tag_del_msg;
    $tag_del_msg='';


	//Delete Tag
	if( isset($_REQUEST['action']) && $_REQUEST['action']=="delete" && !empty( $_REQUEST['tag_ID'] )){
		wp_delete_term( $_REQUEST['tag_ID'], 'fast_tag' );

		$notice = "<div class='updated notice notice-success' style=' display:block; margin-left:0; '>";
		$notice.= "<p>The selected tag has been <strong>deleted</strong>.</p>";
		$notice.= "<div>";

	}

    if( !class_exists('FT_Tags_List_Table') ){
        require_once( FAST_FLOW_DIR . '/includes/lib/class-ft-tags-list-table.php' );
    }

    if( class_exists('FT_Tags_List_Table') ){

        $ft_list_table = new FT_Tags_List_Table();
        $notice = $ft_list_table->process_bulk_action();
        $tags = array();
	    //add quick tag and filter
		if( isset($_POST['tag_add_or_filter']) && wp_verify_nonce( $_POST['tag_add_or_filter'], 'tag_add_or_filter'  ) && $_POST['action']== "do_something"){

			if( isset($_POST["add"]) && $_POST["add"] == "Quick Add Tag" ){
				$term_name = $_POST['tag_name'];
				$parent = (isset($_POST['parent_tag']) && $_POST['parent_tag'] !="" && is_array($_POST['parent_tag']) )? $_POST['parent_tag'][0]:0;

				if(!term_exists( $term_name, 'fast_tag' ))
					$term = wp_insert_term( $term_name, 'fast_tag',array('parent' => $parent));

					if(isset($term) && is_wp_error($term)){
						$notice = print_r("<div class='updated notice error' style='display:block; margin-left:0;'>
						<p>A term with the name already <strong>exists</strong>.</p>
						</div>",true);
					}else{
						$notice = print_r("<div class='updated notice notice-success' style='display:block; margin-left:0;'>
						<p>New tag has been <strong>created</strong>.</p>
						</div>",true);
					}

				/*Display all tags*/
				$tags = get_terms( array('taxonomy' => 'fast_tag', 'hide_empty' => false )  );
				foreach ( $tags as $obj ) :
					$terms[] =  array('ID'=> $obj->term_id,'tag'=> $obj->name,'type'=> get_tag_parent_name($obj->term_id),'users'  => $obj->count);
				endforeach;
				$ft_list_table->set_prepare_items_data($terms);
				$ft_list_table->prepare_items();

			}elseif( isset($_POST["filter"]) && $_POST["filter"] == "Filter" ){

				$name_like = isset($_POST['tag_name']) ? $_POST['tag_name'] : NULL;
				$term_filter = isset($_POST['parent_tag']) ? $_POST['parent_tag'] : array();
				if(NULL != $name_like)
					$tags = get_terms(array('taxonomy'=>'fast_tag','fields'=>'ids','name__like'=>$name_like,'hide_empty'=>false));

				foreach($term_filter as $term){
					$parent = array(intval($term));
					$child = get_term_children(intval($term),'fast_tag');
					//$total = array_merge($parent,$child);
					$tags = array_merge($tags,array_merge($parent,$child));
				}
				//remove duplicate
				$tags = array_unique($tags);
				//type cast to int
				$tags = array_map('intval', $tags );

				$terms = array();
				foreach ( $tags as $tag ) :
					$obj = get_term(intval($tag), 'fast_tag','OBJECT');
					$terms[] =  array('ID'=> $obj->term_id,'tag'=> $obj->name,'type'=> get_tag_parent_name($obj->term_id),'users'  => $obj->count);
				endforeach;

				$ft_list_table->set_prepare_items_data($terms);
				$ft_list_table->prepare_items();

			}
		}else{
			$tags = get_terms( array('taxonomy' => 'fast_tag', 'hide_empty' => false )  );
			$terms = [];
			foreach ( $tags as $obj ) :
				$terms[] =  array('ID'=> $obj->term_id,'tag'=> $obj->name,'type'=> get_tag_parent_name($obj->term_id),'users'  => $obj->count);
			endforeach;
			$ft_list_table->set_prepare_items_data($terms);
			$ft_list_table->prepare_items();
		}



    ?>
            <div class='wrap'>
                    <h2>All Fast Tags<!--<a href='edit-tags.php?taxonomy=fast_tag' class='button add-new-h2' >Add New Tag</a>--></h2>

                    <?php echo $notice; ?>
					<div style='padding: 10px 30px 10px 0;'>
                    <table width="70%">
                    <tr><td width="10%">
                        <form id="fast-tagger-quick-tags" method="post">
							<input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
                            <input type="hidden" name="action" value="do_something" />
							<?php wp_nonce_field( 'tag_add_or_filter', 'tag_add_or_filter' ); ?>
                            <!--<input type="text" id="tag_name" name="tag_name" value="" />-->

							<label class="add_tag" for="add_tag"><strong><?php _e( 'Tag Name: ' ); ?></strong></label>
						</td><td width="30%">
							<select id="tag_name" name="tag_name" placeholder="Add New Or Search">
							<option value=""></option>
                            <?php $terms = get_terms( array( 'taxonomy' => 'fast_tag', 'hide_empty' => false ) );
                            if ( ! empty( $terms ) && ! is_wp_error( $terms ) && is_array($terms) ){
                                foreach ( $terms as $term ) { ?>
                                    <option value="<?php echo $term->name; ?>"><?php echo $term->name; ?></option>
                            <?php
                                }
                            }
							?>
							</select>

						</td><td width="30%">

							<select id="parent_tag" name="parent_tag[]" multiple>
							<option value="" >--Select Type--</option>
                            <?php $types = get_terms( 'fast_tag', array( 'hide_empty' => false, 'parent' => 0 ) );
                            if ( ! empty( $types ) && ! is_wp_error( $types ) && is_array($types) ){

                                foreach ( $types as $type ) {
								$select = (isset($_POST['parent_tag']) && in_array($type->term_id,$_POST['parent_tag']))?"selected='selected'":'';
								?>
                                    <option <?php echo $select;?> value="<?php echo $type->term_id; ?>" ><?php echo $type->name; ?></option>
                            <?php
                                }
                            }
							?>
							</select>

						</td><td width="15%">
                            <input class="button-secondary" type="submit" name="add"   value="Quick Add Tag" />
						</td><td width="15%">
                            <input class="button-secondary" type="submit" name="filter"   value="Filter" />
						</td><td>
                        </form>
						</table>
                    </div>

			<div class="tablenav top">
				<div class="alignleft actions">
					<form id="tag-filter" method="get">
    <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
					<?php  $ft_list_table->display(); ?>
				</form>
				</div>
				<br class="clear">
            </div>
		</div><!--#wrap-->
    <?php
    }
}
function does_user_exist( $user_id = '' ) {
	if ( $user_id instanceof WP_User ) {
		$user_id = $user_id->ID;
	}
	return (bool) get_user_by( 'id', $user_id );
}

function fast_tagged_users() {

    if( !class_exists('FT_Users_List_Table') ){
        require_once( FAST_FLOW_DIR . '/includes/lib/class-ft-users-list-table.php' );
    }

    if( class_exists('FT_Users_List_Table') ){
        $users = array();
        $user_ids = array();
        $terms = get_terms( array( 'taxonomy'=>'fast_tag','hide_empty' => false )  );

	$default = FALSE;
	if( isset($_POST['tag_or_type_filter']) && wp_verify_nonce( $_POST['tag_or_type_filter'], 'tag_or_type_filter'  ) && $_POST['action']== "tag_or_type_filter"){


			$tags = isset($_POST['fast_tag_term'])?$_POST['fast_tag_term']:array();
			$types = isset($_POST['fast_tag_type'])?$_POST['fast_tag_type']:array();
			$term_ids = array_unique(array_merge($tags,$types));
			$term_ids = array_map('intval', $term_ids );
			$user_ids = array_unique(get_objects_in_term($term_ids, 'fast_tag' ));

			if(isset($_POST['filter-button']) && !isset($_POST['fast_tag_term']) && !isset($_POST['fast_tag_type'])){
				$default = TRUE;
				$notice = print_r("<div class='updated notice error' style='display:block; margin-left:0;'>
				<p>No Parameters set. Please set parameters to filter records.</p>
				</div>",true);
			}



	}else{
		$default = TRUE;
	}

		if($default == TRUE){


			if(isset($_REQUEST['fast_tag_term']) && !empty($_REQUEST['fast_tag_term'])){
				if(is_array($_REQUEST['fast_tag_term'])){
					$term_ids = array_map('intval', $_REQUEST['fast_tag_term'] );
				}else{
					$term_ids = array($_REQUEST['fast_tag_term']);
					$term_ids = array_map('intval', $term_ids );
				}

			}else{
				$term_ids = get_terms( array( 'taxonomy'=>'fast_tag','hide_empty' => false,'fields'=>'ids' ));
			}
			$user_ids = array_unique(get_objects_in_term($term_ids, 'fast_tag' ));
		}
			foreach($user_ids as $user_id){
				if(does_user_exist($user_id)){
					$user = get_user_by('id', $user_id);

						array_push( $users, array(
							'ID'        => $user->ID,
							'username'    => $user->user_login,
							'name'     => trim( get_user_meta( $user_id, 'first_name', true ) . ' ' . get_user_meta( $user_id, 'last_name', true ) ),
							'email'  => $user->user_email
						) );
				}

			}


        $ft_list_table = new FT_Users_List_Table();
		$ft_list_table->set_prepare_items_data($users);
        $ft_list_table->prepare_items();
        //echo "<pre>" . print_r( $users, true ) . "</pre>";
        ?>
            <div class='wrap'>
                    <h2>Fast Tagged Users</h2>
					<?php if( isset($notice) && !empty($notice) ) { echo $notice; } ?>
					<div style='padding: 10px 30px 10px 0;'>
                    <table width="70%">
                    <tr><td width="30%">
                    <form id="fast-tagged-users" method="post">
                        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                        <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
                        <input type="hidden" name="action" value="tag_or_type_filter" />
						<?php wp_nonce_field( 'tag_or_type_filter', 'tag_or_type_filter' ); ?>

                        <select id="fast_tag_term" name="fast_tag_term[]" multiple>
                            <option value="" >--Select Tag--</option>
                            <?php
                            $selected_term = "";
                            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                                foreach ( $terms as $term ) {
																	if(is_array($_REQUEST['fast_tag_term'])){
																		if( !empty($_REQUEST['fast_tag_term']) && in_array($term->term_id,$_REQUEST['fast_tag_term'] )) {
                                        $selected_term = "selected='selected'";
                                    } else {
                                        $selected_term = "";
                                    }
																	}else{
																		if( !empty($_REQUEST['fast_tag_term']) && $term->term_id == $_REQUEST['fast_tag_term'] ) {
                                        $selected_term = "selected='selected'";
                                    } else {
                                        $selected_term = "";
                                    }
																	}

                                    ?>
                                    <option value="<?php echo $term->term_id; ?>" <?php echo $selected_term; ?>><?php echo $term->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
						</td><td width="10%">
                        &nbsp;&nbsp;&nbsp;--or--&nbsp;&nbsp;&nbsp;
						</td><td width="30%">
                        <select id="fast_tag_type" name="fast_tag_type[]" multiple>
                            <option value="" >--Select Type--</option>
                            <?php
                            $types = get_terms( array( 'taxonomy' =>'fast_tag', 'hide_empty' => false, 'parent' => 0 ) );
                            $selected_type = "";
                            if ( ! empty( $types ) && ! is_wp_error( $types ) ){
                                foreach ( $types as $type ) {
																	if(is_array($_REQUEST['fast_tag_type'])){
																		if( !empty($_REQUEST['fast_tag_type']) && in_array($term->term_id,$_REQUEST['fast_tag_type'] )) {
                                        $selected_term = "selected='selected'";
                                    } else {
                                        $selected_term = "";
                                    }
																	}else{
																		if( !empty($_REQUEST['fast_tag_type']) && $term->term_id == $_REQUEST['fast_tag_type'] ) {
                                        $selected_type = "selected='selected'";
                                    } else {
                                        $selected_type = "";
                                    }
																	}

                                    ?>
                                    <option value="<?php echo $type->term_id; ?>" <?php echo $selected_type; ?>><?php echo $type->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
						</td><td width="30%">
                        <input class="button-secondary" type="submit" name="filter-button"   value="Filter Users" />
                        <!-- Now we can render the completed list table -->

                    </form>
					</td></tr>
					</table>
				<?php $ft_list_table->display() ?>
            </div>
			</div><!--#wrap-->
    <?php
    }
}
