<?php
$plugins = SOSPO_Admin_Helper::get_plugins_with_status();

$groups = get_posts( [
	'post_type'   => 'plgnoptmzr_group',
	'numberposts' => - 1,
] );

if( $groups ){
    
    usort( $groups, "SOSPO_Admin_Helper::sort__by_post_title" );
}

$categories = get_categories( [
	'taxonomy'   => 'plgnoptmzr_categories',
	'type'       => 'plgnoptmzr_filter',
	'hide_empty' => 0,
] );

// sospo_mu_plugin()->write_log( $categories, "page-add-filters-categories" );

// defaults
$page_title         = "Create a new Filter";
$post_title       = "";
$filter_type        = "_endpoint";
$plugins_to_block   = [];
$groups_to_block    = [];
$post_categories  = [];
$endpoints          = [];
$is_premium         = false;
$block_editing      = false;
$viewing_dictionary = false;

/**
 * Post is false if $_GET['filter_id'] doesn't exist, otherwise returns post object
 * @var post object
 */
$post = ! empty( $_GET["filter_id"] )  ? get_post( intval( $_GET["filter_id"] ) )  : false;

$filter_type = '_endpoint';

if( $post ){
    
    $page_title = "Editing filter: " . $post->post_title;

    $post_title       = $post->post_title;

    // Get Filter Meta
    $filter_type        = get_post_meta( $post->ID, "filter_type", true );
    $plugins_to_block   = get_post_meta( $post->ID, "plugins_to_block", true );
    $groups_to_block    = get_post_meta( $post->ID, "groups_used", true );
    $post_categories    = get_post_meta( $post->ID, "categories", true );
    $frontend           = get_post_meta( $post->ID, "frontend", true);
    $endpoints          = SOSPO_Admin_Helper::get_filter_endpoints( $post->ID );

    /**
     * Returns false if not a premium filter
     * @var boolean
     */
    $is_premium         = get_post_meta( $post->ID, 'premium_filter', true ) === "true";
    
    $block_editing      = ( $is_premium && ! sospo_mu_plugin()->has_agent );
    
    $plugins_to_block   = ! empty( $plugins_to_block  ) ? array_keys( $plugins_to_block  ) : [];
    $groups_to_block    = ! empty( $groups_to_block   ) ? array_keys( $groups_to_block   ) : [];
    $post_categories    = ! empty( $post_categories )   ? array_keys( $post_categories )   : [];
    
    // sospo_mu_plugin()->write_log( $groups_to_block, "sospo_mu_plugin()-page-filters-edit-groups_to_block" );
    
} 

// Reassign defaults
elseif( ! empty( $_GET["work_title"] ) && ! empty( $_GET["work_link"] ) ){
    
    $post_title = sanitize_text_field( $_GET["work_title"] );
    $endpoints    = [ esc_url( $_GET["work_link"] ) ];
    
}

$title_class = 'col-9';

if($post){
if( sospo_mu_plugin()->has_agent ){
    
    $title_class = 'col-6';
    
    $belongs_to  =  $post ? get_post_meta( $post->ID, "belongsTo", true ) : '';
    
    $belongs_to_core_selected = $belongs_to === "_core" ? ' selected="selected"' : '';
        
    $plugin_select_options = '';
    

    // This constructs a BelongsTo dropdown
    foreach( get_plugins() as $plugin_id => $plugin ){
        
        $selected = ! $belongs_to || $belongs_to !== $plugin_id ? '' : ' selected="selected"';
        
        $plugin_select_options .= '<option value="' . $plugin_id . '"' . $selected . ' data-belongs_to="'.get_post_meta( $post->ID, "belongsTo", true ).'" data-plugins="'.$plugin_id.'" data-postid="'.$post->ID.'">';
        $plugin_select_options .=      $plugin["Name"];
        $plugin_select_options .= '</option>';
        $plugin_select_options .= PHP_EOL;
    }
    

    // RETRIEVE DICTIONARY SERVER COPY OF A FILTER
    // ---------------------------------------------------

    if( ! empty( $_GET["dictionary_id"] ) ){
        
        $dictionary_id = sanitize_text_field( $_GET["dictionary_id"] );
        
        $page_title = "Dictionary filter: " . $dictionary_id;
        
        $args = [
            "query" => [
                '_id' =>  $dictionary_id,
            ],
            /*
              TODO: Possible upgrade

              "exclude_fields" => [
                  "plugins",
                  "proposed",
              ],
              
            */
        ];
        
        $filter = sospo_dictionary()->get( $args, true );
        
        if( ! empty( $filter ) ){
            
            $viewing_dictionary = true;
            
            $dictionary_filter = $filter[0];
            
            // write_log( $filter, "sospo_mu_plugin()-page-filters-agent-dictionary_filter" );
            
            $page_title = "Dictionary filter: " . $dictionary_id;
            
            $post_title       = $dictionary_filter->title;
            $filter_type        = "_endpoint";
            $groups_to_block    = [];
            $post_categories  = $dictionary_filter->categories;
            $endpoints          = [ $dictionary_filter->endpoint ];
            $belongs_to         = $dictionary_filter->belongsTo;
            
            $plugins_proposed   = $dictionary_filter->proposed;
            $plugins_approved   = $dictionary_filter->plugins;
            $plugins_added      = array_diff( $plugins_proposed, $plugins_approved );
            $plugins_removed    = array_diff( $plugins_approved, $plugins_proposed );
            
        }
       
    }
}
} else {
   $belongs_to_core_selected = '';
}


?>



<style>
   .additional_endpoint_wrapper:before{
   content: "<?php echo home_url() ?>";
   }
</style>
<div class="sos-wrap">
   <?php @SOSPO_Admin_Helper::content_part__header( $page_title, "filters" ); ?>


   <div id="edit_filter" class="sos-content">
     <?php if( $post ) : ?>
      <div class="test-filter-container"><a target="_blank" href="<?php echo site_url() . trim($endpoints[0],'*'); ?>" class="po_blue_button">Test this filter</a></div>
     <?php endif;?>
      <?php if( $block_editing ){ ?>
      <div id="forbid_premium_edit">Premium filters can not be edited</div>
      <?php } else { ?>
      <div class="row content-new-element">
         <div class="col-12">
            <div class="content-filter">
               <div class="row filter_title">
                  <input type="hidden" name="SOSPO_filter_data[ID]" value="<?php echo $post ? $post->ID : "" ?>"/>
                  <div class="<?php echo $title_class ?>">
                     <div class="header">Title</div>
                     <div>
                        <div class="content enter-data">
                           <span><input class="content-text" id="set_title" type="text" name="SOSPO_filter_data[title]" value="<?php echo $post_title ?>" placeholder="The title of this filter"></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-3">
                     <div class="header">Type</div>
                     <div>
                        <div class="content enter-data">
                           <span>
                              <select name="SOSPO_filter_data[type]" id="set_filter_type" data-selected="<?php echo $post_type; ?>" style="display: block;">
                                 <optgroup label="Default:">
                                    <option value="_endpoint" <?php echo $filter_type == '_endpoint' ? 'selected="selected"': ''; ?>>Endpoint(s)</option>
                                 </optgroup>
                                 <optgroup label="Edit page of a Post Type:" id="select_post_types">
                                    <?php

                                        $post_types = [];
                                        
                                        $post_types_raw = get_post_types( [], "objects" );
                                        
                                        // Check for all filter posts that are assigned to a post type
                                        global $wpdb;

                                        $post_types_filters = wp_list_pluck( $post_types_raw, 'name' );
                                        $post_types_filters = implode("','", $post_types_filters);
                                        $post_types_filters = $wpdb->get_results("SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` = 'filter_type' AND `meta_value` IN ('{$post_types_filters}')");
                                        $post_types_filters = wp_list_pluck( $post_types_filters, 'meta_value' );

                                        foreach( $post_types_raw as $pstype ){

                                            //if( in_array($pstype->name, $post_types_filters) && $pstype->name != $filter_type ) continue;

                                            $post_types[ $pstype->name ] = $pstype->labels->singular_name . " (" . $pstype->name . ")";
                                        }

                                        natsort( $post_types );

                                        foreach( $post_types as $key => $pstype ){

                                            echo '<option value="'.$key.'" 
                                             '.( $filter_type == $key ? 'selected="selected"': '' ).' 
                                             '.( in_array($key, $post_types_filters) && $key != $filter_type ? 'disabled' : '').'>
                                             '.$pstype.'</option>';
                                        }
                                        
                                    ?>
                                 </optgroup>
                              </select>
                           </span>
                        </div>
                     </div>
                     <div id="post_type_options" style="margin-top:5px; <?php echo $filter_type != '_endpoint' ? '' : 'display: none;'; ?> ">
                        <input type="checkbox" name="SOSPO_filter_data[frontend]" value="yes" id="frontend_check" <?php echo $frontend == 'true' ? 'checked="checked"' : ''; ?>> Use only for customer facing pages
                     </div>
                  </div>
                  <?php if( sospo_mu_plugin()->has_agent ){ ?>
                  <div class="col-3">
                     <div class="header">Belongs to:</div>
                     <div>
                        <div class="content enter-data">
                           <span>
                              <select name="SOSPO_filter_data[belongs_to]" id="set_belongs_to" data-selected="<?php echo $belongs_to; ?>">
                                 <optgroup label="Default:">
                                    <option value="_core"<?php echo $belongs_to_core_selected; ?>>Core</option>
                                 </optgroup>
                                 <optgroup label="Plugin:">
                                    <?php echo $plugin_select_options; ?>
                                 </optgroup>
                              </select>
                           </span>
                        </div>
                     </div>
                  </div>
                  <?php } ?>
               </div>
               <div class="row select_trigger" id="endpoints_wrapper" <?php echo $filter_type == '_endpoint' ? 'style="display: block;"' : ''; ?>>
                  <div class="">
                     <div class="header">Endpoints</div>
                  </div>
                  <div class="additional_endpoint_wrapper">
                     <input id="first_endpoint" type="text" name="SOSPO_filter_data[endpoints][]" placeholder="Put your URL here" value="<?php echo ! empty( $endpoints ) ? $endpoints[0] : "" ?>"/>
                     <div id="add_endpoint" class="circle_button add_something">+</div>
                  </div>
                  <?php if( is_array($endpoints) ) for( $i = 1; $i < count( $endpoints ); $i++ ){ ?>
                  <div class="additional_endpoint_wrapper">
                     <input class="additional_endpoint" type="text" name="SOSPO_filter_data[endpoints][]" placeholder="Put your URL here" value="<?php echo $endpoints[ $i ] ?>"/>
                     <div class="remove_additional_endpoint circle_button remove_something">-</div>
                  </div>
                  <?php } ?>
               </div>
               <div class="row block-plugin-wrapper">
                  <div class="col-12">
                     <?php if( ! $viewing_dictionary ){ ?>
                     <div class="header">
                        <div class="title">Plugins <span class="disabled">- <?php echo count( $plugins["all"] ); ?></span></div>
                        <span class="count-plugin">( Active: <?php echo count( $plugins["active"] ); ?>   |   Inactive: <?php echo count( $plugins["inactive"] ); ?> )</span>
                        <span class="all-check toggle_plugins">Disable All</span>
                     </div>
                     <div class="header attribute-plugin">Active plugins</div>
                     <?php SOSPO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["active"],   "inactive" => [],                   "blocked" => $plugins_to_block ] ); ?>
                     <div class="header attribute-plugin" style="margin-top: 10px;">Inactive plugins</div>
                     <?php SOSPO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["inactive"], "inactive" => $plugins["inactive"], "blocked" => $plugins_to_block ] ); ?>
                     <?php } else { ?>
                     <div class="header">
                        <div class="title">Plugins</div>
                        <span class="count-plugin"></span>
                     </div>
                     <div class="header attribute-plugin dictionary_view">
                        <span class="name">Proposed plugins:</span>
                        <span class="number"><?php echo count( $plugins_proposed ); ?></span>
                     </div>
                     <ul class="dictionary_view_plugins_list">
                        <?php
                           if( ! empty( $plugins_proposed ) ){
                               
                               natsort( $plugins_proposed );
                               
                               foreach( $plugins_proposed as $plugin_id ){
                                   
                                   echo '<li>' . $plugin_id . '</li>';
                               }
                               
                           } else {
                               echo '<li>Empty list</li>';
                           }
                           ?>
                     </ul>
                     <div class="header attribute-plugin dictionary_view" style="margin-top: 10px;">
                        <span class="name">Already approved plugins:</span>
                        <span class="number"><?php echo count( $plugins_approved ); ?></span>
                     </div>
                     <ul class="dictionary_view_plugins_list">
                        <?php
                           if( ! empty( $plugins_approved ) ){
                               
                               natsort( $plugins_approved );
                               
                               foreach( $plugins_approved as $plugin_id ){
                                   
                                   echo '<li>' . $plugin_id . '</li>';
                               }
                               
                           } else {
                               echo '<li>Empty list</li>';
                           }
                           ?>
                     </ul>
                     <div class="header attribute-plugin dictionary_view" style="margin-top: 10px;">
                        <span class="name">Removed plugins:</span>
                        <span class="number"><?php echo count( $plugins_removed ); ?></span>
                     </div>
                     <ul class="dictionary_view_plugins_list">
                        <?php
                           if( ! empty( $plugins_removed ) ){
                               
                               natsort( $plugins_removed );
                               
                               foreach( $plugins_removed as $plugin_id ){
                                   
                                   echo '<li>' . $plugin_id . '</li>';
                               }
                               
                           } else {
                               echo '<li>Empty list</li>';
                           }
                           ?>
                     </ul>
                     <div class="header attribute-plugin dictionary_view" style="margin-top: 10px;">
                        <span class="name">Added plugins:</span>
                        <span class="number"><?php echo count( $plugins_added ); ?></span>
                     </div>
                     <ul class="dictionary_view_plugins_list">
                        <?php
                           if( ! empty( $plugins_added ) ){
                               
                               natsort( $plugins_added );
                               
                               foreach( $plugins_added as $plugin_id ){
                                   
                                   echo '<li>' . $plugin_id . '</li>';
                               }
                               
                           } else {
                               echo '<li>Empty list</li>';
                           }
                           ?>
                     </ul>
                     <?php } ?>
                  </div>
               </div>
               <?php if( ! $viewing_dictionary ){ ?>
               <div class="row block-group-plugin-wrapper">
                  <div class="col-12">
                     <div class="header">
                        <div class="title">Blocked Plugin Groups <span class="disabled">- <?php echo count( $groups ); ?></span>
                        </div>
                        <span class="all-check toggle_groups">Disable All</span>
                     </div>
                     <div class="special_grid_list">
                        <?php
                           if ( $groups ){
                            foreach ( $groups as $group ){
                                                           $block_plugins_in_group = get_post_meta( $group->ID, 'group_plugins', true );
                                                           $selected = in_array( $group->ID, $groups_to_block );
                                                           $blocked  = $selected ? " blocked" : "";
                                                           $checked  = $selected ? ' checked="checked"' : '';
                              ?>
                        <div class="single_group content<?php echo $blocked ?>" data-plugins="<?php echo htmlspecialchars(json_encode($block_plugins_in_group)) ?>">
                           <input class="noeyes" type="checkbox" name="SOSPO_filter_data[groups][<?php echo $group->ID ?>]" value="<?php echo $group->post_title ?>"<?php echo $checked ?>/>
                           <span><?php echo $group->post_title; ?></span>
                        </div>
                        <?php
                           }
                           }
                           ?>
                     </div>
                  </div>
               </div>
               <?php } ?>
               <div class="row category-wrapper">
                  <div class="col-12">
                     <div class="header">
                        <div class="title">Categories</div>
                     </div>
                     <div class="special_grid_list">
                        <?php
                           if ( ! $viewing_dictionary && $categories ){
                            foreach ( $categories as $cat ){
                                                           $selected = is_array( $post_categories ) ? in_array( $cat->term_id, $post_categories ) : false;
                                                           $checked  = $selected ? ' checked="checked"' : '';
                              ?>
                        <div class="single_category content<?php echo $selected ? " blocked" : "" ?>">
                           <input class="noeyes" type="checkbox" name="SOSPO_filter_data[categories][<?php echo $cat->term_id ?>]" value="<?php echo $cat->cat_name ?>"<?php echo $checked ?>/>
                           <span value="<?php echo $cat->term_id; ?>"><?php echo $cat->cat_name; ?></span>
                        </div>
                        <?php
                           }
                           }
                                                  
                           if ( $viewing_dictionary && $post_categories ){
                           foreach ( $post_categories as $category_name ){
                                                          $selected = true;
                                                          $checked  = $selected ? ' checked="checked"' : '';
                            ?>
                        <div class="single_category content blocked dictionary_view">
                           <input class="noeyes" type="checkbox" name="SOSPO_filter_data[categories][<?php echo $category_name ?>]" value="<?php echo $category_name ?>" checked="checked"/>
                           <span value="<?php echo $category_name; ?>"><?php echo $category_name; ?></span>
                        </div>
                        <?php
                           }
                           }
                           ?>
                        <?php if( ! $viewing_dictionary ){ ?>
                        <div class="content before_add" id="add_category">
                           <span class="circle_button add_something before_add">+</span><span class="before_add"> Create New</span>
                           <input class="during_add" type="text" name="new_category_name" value="" placeholder="Category Name"/>
                           <span class="circle_button remove_something during_add cancel">-</span>
                           <span class="circle_button add_something during_add ok">&#10003;</span>
                        </div>
                        <?php } ?>
                     </div>
                  </div>
               </div>
            </div>
            <?php if( ! $viewing_dictionary ){ ?>
            <div class="row">
               <button id="save_filter" class="po_green_button">Save Filter</button>
            </div>
            <?php } ?>
         </div>
      </div>
      <?php } ?>
   </div>
</div>

