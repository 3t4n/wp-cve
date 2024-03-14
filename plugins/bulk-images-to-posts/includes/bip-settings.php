<?php

function bip_settings_page() { ?>
	
<div class="grid">
	<div class="whole unit">

	<h2><?php _e('Bulk Images to Posts - Settings','bulk-images-to-posts'); ?></h2>

	</div>
</div>
<div id="poststuff" class="grid">
<div class="half unit">
    <div class="grid">
        <div class="two-thirds unit">
			<form method="post" action="options.php" id="bip-settings-form">
			    <?php settings_fields( 'bip-settings-group' ); ?>
			    <?php do_settings_sections( 'bip-settings-group' ); ?>
			    <input type="hidden" name="bip_updated" value="true">
			<div class="postbox">
			    <div title="Click to toggle" class="handlediv"><br></div>
			    <h3 class="hndle"><span><?php _e('Post Status','bulk-images-to-posts'); ?></span></h3>
			        <div class="inside">
			                <select id="bip-post-status" name="bip_post_status">
			                    <option style="font-weight:bold;" value="<?php echo get_option('bip_post_status','publish'); ?>">
			                        <?php echo get_option('bip_post_status','publish'); ?>
			                    </option>
			                    <option value="publish">
			                        publish
			                    </option>
			                    <option value="draft">
			                        draft
			                    </option>
			                </select>
			    </div>
			</div>
			<div class="postbox">
			    <div title="Click to toggle" class="handlediv"><br></div>
			    <h3 class="hndle"><span><?php _e('Post Title','bulk-images-to-posts'); ?></span></h3>
			        <div class="inside">
			        		<b><?php _e('By default the image filename is used.','bulk-images-to-posts'); ?></b>
			        		<p><?php _e('Instead this can be set to the image metadata title, this is useful for photographers','bulk-images-to-posts'); ?></p>
<?php $useTitle = get_option( 'bip_image_title' ); ?>
<p> <label for'bip_image_title'><input type='checkbox' name='bip_image_title' value='1' <?php if ( 1 == $useTitle ) echo 'checked="checked"'; ?> />
    <?php _e('Use image metadata title.','bulk-images-to-posts'); ?></label>
  </p>

			    </div>
			</div>
			<div class="postbox">
			    <div title="Click to toggle" class="handlediv"><br></div>
			    <h3 class="hndle"><span><?php _e('Post Content','bulk-images-to-posts'); ?></span></h3>
			        <div class="inside">
<?php $includeImageContent = get_option( 'bip_image_content' ); ?>
   <p>  
   	<label for'bip_image_content'><input type='checkbox' name='bip_image_content' value='1' <?php if ( 1 == $includeImageContent ) echo 'checked="checked"'; ?> />
   <?php _e('Include the image in the body of the post','bulk-images-to-posts'); ?>
</label>
  </p>
<p>
  <?php 
  $imageSizes = get_intermediate_image_sizes(); ?>
  <label for'bip_image_content_size'><?php _e('Image size','bulk-images-to-posts'); ?></label>
<select name='bip_image_content_size'>
				                    <option style="font-weight:bold;" value="<?php echo get_option('bip_image_content_size','large'); ?>">
			                        <?php echo get_option('bip_image_content_size','large'); ?>
			                    </option>
  <?php 
  foreach ($imageSizes as $imageSize => $imageSizeName): ?>
    <option value="<?php echo $imageSizeName ?>"><?php echo $imageSizeName; ?></option>
  <?php endforeach; ?>
</select>
</p>
					</div>
			</div>		   
			<div class="postbox">
			    <div title="Click to toggle" class="handlediv"><br></div>
			    <h3 class="hndle"><span><?php _e('Post Type','bulk-images-to-posts'); ?></span></h3>
			        <div class="inside">
			                <select id="bip-post-type" name="bip_post_type">
			                    <option style="font-weight:bold;" value="<?php echo get_option('bip_post_type','post'); ?>">
			                        <?php echo get_option('bip_post_type','post'); ?>
			                    </option>
			                    <option value="post">
			                        post
			                    </option>
			                    <option value="page">
			                        page
			                    </option>
			                    <?php $args = array( 'public'   => true, '_builtin' => false );
			
			                    $output = 'names'; // names or objects, note names is the default
			                    $operator = 'and'; // 'and' or 'or'
			
			                    $post_types = get_post_types( $args, $output, $operator ); 
			
			                    foreach ( $post_types  as $post_type ) { ?>
			                        <option value="<?php echo $post_type ?>">
			                           <?php echo $post_type ?>
			                        </option>
			                    <?php } ?>
			                </select>
			    </div>
			</div>
			<div class="postbox">
			    <div title="Click to toggle" class="handlediv"><br></div>
			    <h3 class="hndle"><span><?php _e('Taxonomies','bulk-images-to-posts'); ?></span></h3>
			        <div class="inside">
				                <?php
				                $taxList = get_option( 'bip_taxonomy' );
								$args = array(
								  'public'   => true,			  
								);
								$output = 'names'; // or objects
								$operator = 'and'; // 'and' or 'or'
								$taxonomies = get_taxonomies( $args, $output, $operator ); 
								if ( $taxonomies ) {
								  foreach ( $taxonomies  as $taxonomy ) { ?>	
								                
								  <?php if(!empty($taxList)) { $checked = checked( in_array( $taxonomy, $taxList ), true, false ); } ?>		 
								 <label><input type="checkbox" <?php if(!empty($taxList)) { echo $checked; } ?> name="bip_taxonomy[]" value="<?php echo $taxonomy; ?>"><?php echo $taxonomy; ?></label><br/>
								 <?php  }
								}
								?>	                
			    </div>
			</div>
			<?php submit_button(); ?>
			</form>
        </div>
    </div>
</div>
</div>

<?php }