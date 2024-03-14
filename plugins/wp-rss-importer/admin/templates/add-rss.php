<div id="logics-wrap" class="wrap logics-add-stores">
	<h2>RSS Feed Syndication List</h2>
    <?php settings_errors(); ?>
    
    <?php //echo $this->create_menu(); ?>
    
    <form method="post" action="" accept-charset="utf-8">
        <input type="hidden" name="logics_actions" value="add_new_rss" />
        <?php wp_nonce_field( 'logics_add_new_rss' ); ?>
        <div class="logics-add-rss">
            <div class="metabox-holder">
                <div class="postbox">
                    <h3><span><?php _e( 'RSS details', 'logics' ); ?></span></h3>
                    <div class="inside">
                        <p>
                            <label for="logics-rss-title"><?php _e( 'Title:', 'logics' ); ?></label>
                            <input id="logics-rss-title" name="logics[title]" type="text" class="textinput <?php if ( isset( $_POST['logics'] ) && empty( $_POST['logics']['title'] ) ) { echo 'logics-error'; } ?>" value="<?php if ( !empty( $_POST['logics']['title'] ) ) { echo esc_attr( stripslashes( $_POST['logics']['title'] ) );  } ?>" />
                        </p>
						
						<p>
                            <label for="logics-rss-url"><?php _e( 'Feed URL', 'logics' ); ?></label>
                            <input id="logics-rss-url" name="logics[url]" type="text" class="textinput <?php if ( isset( $_POST['logics'] ) && empty( $_POST['logics']['url'] ) ) { echo 'logics-error'; } ?>" value="<?php if ( !empty( $_POST['logics']['url'] ) ) { echo esc_attr( stripslashes( $_POST['logics']['url'] ) );  } ?>" />
                        </p>
                        
						<p>
                            <label for="logics-rss-title"><?php _e( 'Custom Post Type:', 'logics' ); ?></label>
							<?php 
							$args = array('public'   => true);
							$post_types = get_post_types($args);
							echo '<select id="logics-rss-pid" name="logics[pid]" class="textinput" onChange=populate_Select(this.options[this.selectedIndex].value)>';
							foreach($post_types as $pt) {
								if(!in_array($pt,array('page','attachment'))) {
									echo '<option value="'.$pt.'">'.$pt.'</option>';
								}
							}
							echo '</select>'; ?>
                            
                        </p>
						
						<p>
                            <label for="logics-rss-title"><?php _e( 'Taxonomy:', 'logics' ); ?></label>
                            <?php 
							$taxonomy_names = get_object_taxonomies( 'post' );
							echo '<select id="logics-rss-taxid" name="logics[taxid]" class="textinput" onChange=populate_Terms(this.options[this.selectedIndex].value)>';
							foreach($taxonomy_names as $tx) {
								echo '<option value="'.$tx.'">'.$tx.'</option>';
							}
							echo '</select>';
							?>
                        </p>
						
						<p>
                            <label for="logics-rss-title"><?php _e( 'Taxonomy Items:', 'logics' ); ?></label>
                            <div id="logics-rss-taxitem">
							<?php 
							$terms = get_terms(array('category'), array('hide_empty' => false));
							foreach($terms as $tm) {
								echo '<input type="checkbox" name="logics[taxitem][]" id="logics-rss-taxitem-'.$tm->term_id.'" value="'.$tm->term_id.'" >'.$tm->name.' ';
							}
							?></div>
                        </p>
                     </div>
                </div>
            </div> 
        
            <div id="wpsl-gmap-wrap"></div>
        </div>
          
        <div class="metabox-holder">            
            <p><input id="wpsl-add-store" type="submit" name="wpsl-add-store" class="button-primary" value="<?php _e( 'Add RSS', 'logics' ); ?>" /></p>
         </div>
    </form>

</div>