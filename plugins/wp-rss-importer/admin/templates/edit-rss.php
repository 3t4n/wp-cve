<?php
$rss_id = absint( $_GET['rss_id'] );
$thumb = '';

if ( $rss_id ) {
    $rss_details = $this->get_rss_data( $rss_id );
	//print_r($rss_details);
}
?>
<div id="logics-wrap" class="wrap logics-add-rss">
    <h2 class="logics-edit-header"><?php _e( 'Edit ', 'logics' ); if ( isset( $_POST['logics'] ) && empty( $_POST['logics']['title'] ) ) { echo esc_attr( stripslashes( $_POST['logics']['title'] ) ); } else { echo esc_attr( stripslashes( $rss_details['title'] ) ); } ?></h2>
    <?php settings_errors(); ?>
    
    <form method="post" action="" accept-charset="utf-8">
        <input type="hidden" name="logics_actions" value="update_rss" />
        <?php wp_nonce_field( 'logics_update_rss' ); ?>
        <div class="logics-add-rss">
            <div class="metabox-holder">
                <div class="postbox">
                    <h3><span><?php _e( 'RSS details', 'logics' ); ?></span></h3>
					<h3 style="background: #CCC; text-align: center;">Cron Job URL: <?php echo admin_url( 'admin-ajax.php?action=runcron&id=' . $rss_id );  ?></h3>
                    <div class="inside">
                        <p>
                            <label for="logics-rss-title"><?php _e( 'Title:', 'logics' ); ?></label>
                            <input id="logics-rss-name" name="logics[title]" type="text" class="textinput <?php if ( isset( $_POST['logics'] ) && empty( $_POST['logics']['title'] ) ) { echo 'logics-error'; } ?>" value="<?php if ( isset( $_POST['logics'] ) && empty( $_POST['logics']['title'] ) ) { echo esc_attr( stripslashes( $_POST['logics']['title'] ) ); } else { echo esc_attr( stripslashes( $rss_details['title'] ) ); } ?>" />
							
                        </p>
						
						<p>
                            <label for="logics-rss-url"><?php _e( 'Feed URL:', 'logics' ); ?></label>
                            <input id="logics-rss-url" name="logics[url]" type="text" class="textinput <?php if ( isset( $_POST['logics'] ) && empty( $_POST['logics']['url'] ) ) { echo 'logics-error'; } ?>" value="<?php if ( isset( $_POST['logics'] ) && empty( $_POST['logics']['url'] ) ) { echo esc_attr( stripslashes( $_POST['logics']['url'] ) ); } else { echo esc_attr( stripslashes( $rss_details['url'] ) ); } ?>" />
							
                        </p>
                        
						<p>
                            <label for="logics-rss-title"><?php _e( 'Custom Post Type:', 'logics' ); ?></label>
							<?php 
							$args = array('public'   => true);
							$post_types = get_post_types($args);
							echo '<select id="logics-rss-pid" name="logics[pid]" class="textinput" onChange=populate_Select(this.options[this.selectedIndex].value)>';
							foreach($post_types as $pt) {
								if(!in_array($pt,array('page','attachment'))) {
									echo '<option value="'.$pt.'"';
									if($pt == $rss_details['pid']) {
										echo ' selected';
									}
									echo ' >'.$pt.'</option>';
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
								echo '<option value="'.$tx.'"';
									if($tx == $rss_details['taxid']) {
										echo ' selected';
									}
								echo ' >'.$tx.'</option>';
							}
							echo '</select>';
							?>
                        </p>
						<p>
                            <label for="logics-rss-taxitem"><?php _e( 'Taxonomy Item:', 'logics' ); ?></label>
                            <div id="logics-rss-taxitem">
							<?php 
							$rt = explode(',',$rss_details['taxitem']);
							$terms = get_terms(array('category'), array('hide_empty' => false));
							foreach($terms as $tm) {
								echo '<input type="checkbox" name="logics[taxitem][]" id="logics-rss-taxitem-'.$tm->term_id.'" value="'.$tm->term_id.'"';
								if(in_array($tm->term_id,$rt)) {
									echo ' checked ';
								}
								echo '>'.$tm->name.' ';
							}
							?>
							</div>
                        </p>
                     </div>
                </div>
            </div> 
        
            <div id="logics-gmap-wrap"></div>
        </div>
          
        <div class="metabox-holder">
              
            <p>
                <input id="logics-update-rss" type="submit" name="logics-update-rss" class="button-primary" value="<?php _e( 'Update Rss', 'logics' ); ?>" />
            </p>
         </div>
    </form>

</div>