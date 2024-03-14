<?php
namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @var bool $compact - show compacted version or full
 */

?>
	<?php if( !$compact ):?>
	<p class="description">
		<?php _e('Import videos from Vimeo.', 'codeflavors-vimeo-video-post-lite');?><br />
		<?php _e('Enter your search criteria and submit. All found videos will be displayed and you can selectively import videos into WordPress.', 'codeflavors-vimeo-video-post-lite');?>
	</p>
	<?php endif;?>
	<form method="get" action="" id="cvm_load_feed_form">
		<?php 
			$current_mode = $this->mode;
			$modes = [
				'list' => __('List view', 'codeflavors-vimeo-video-post-lite'),
				'grid' => __('Grid view', 'codeflavors-vimeo-video-post-lite')
			];
		?>
		<input type="hidden" name="mode" value="<?php echo esc_attr( $current_mode ); ?>" />	
		<?php if( $compact ):?>
		<div class="view-switch">
			<?php 
			foreach ( $modes as $m => $title ) {
				$classes = [ 'view-' . $m ];
				if ( $current_mode === $m )
					$classes[] = 'current';
				printf(
						"<a href='%s' class='%s' id='view-switch-$m'><span class='screen-reader-text'>%s</span></a>\n",
						esc_url( add_query_arg( 'mode', $m ) ),
						implode( ' ', $classes ),
						$title
				);
			}
			?>
		</div>
		<?php endif;?>
		<input type="hidden" name="post_type" value="<?php echo $this->cpt->get_post_type();?>" />
		<input type="hidden" name="page" value="cvm_import" />
		<input type="hidden" name="cvm_source" value="vimeo" />
		<?php if( !$compact ):?>
		<table class="form-table">
			<tr class="cvm_feed">
				<th valign="top" scope="row">
		<?php endif;?>
					<label for="cvm_feed"><?php _e('Feed type', 'codeflavors-vimeo-video-post-lite');?> :</label>
		<?php if( !$compact ):?>
				</th>
				<td>
		<?php endif;?>		
					<?php
                        Helper_Admin::select_feed_source(
                            'cvm_feed',
                            isset( $_GET['cvm_feed'] ) ? $_GET['cvm_feed'] : false,
                            'cvm_feed'
                        );
					?>
			<?php if( !$compact ):?>		
					<span class="description"><?php _e('Select the type of feed you want to load.', 'codeflavors-vimeo-video-post-lite');?></span>
				</td>
			</tr>
			
			<tr class="cvm_album_user">
				<th valign="top" scope="row">
			<?php endif;?>
			
			<?php if( $compact ):?>
			<span class="cvm_album_user">
			<?php endif;?>
				
					<label for="cvm_album_user"><?php _e('User ID', 'codeflavors-vimeo-video-post-lite');?>:</label>
			<?php if( !$compact ):?>	
				</th>
				<td>
			<?php endif;?>	
					<input type="text" name="cvm_album_user" id="cvm_album_user" value="<?php echo isset( $_GET['cvm_album_user'] ) ? esc_attr( $_GET['cvm_album_user'] ) : '';?>" placeholder="<?php echo esc_attr( __('Album owner user ID','codeflavors-vimeo-video-post-lite') );?>" />
			
			<?php if( $compact ):?>
			</span>
			<?php endif;?>
			
			<?php if( !$compact ):?>	
				</td>
			</tr>
			
			<tr class="cvm_query">
				<th valign="top" scope="row">
			<?php endif;?>	
					<label for="cvm_query"><?php _e('Vimeo search query', 'codeflavors-vimeo-video-post-lite');?>:</label>
			<?php if( !$compact ):?>	
				</th>
				<td>
			<?php endif;?>	
					<input type="text" name="cvm_query" id="cvm_query" value="<?php echo  isset( $_GET['cvm_query'] ) ? esc_attr( $_GET['cvm_query'] ) : '';?>" />
			<?php if( !$compact ):?>		
					<span class="description"><?php _e('Enter search query, user ID, group ID, channel ID or album ID according to Feed Type selection.', 'codeflavors-vimeo-video-post-lite');?></span>
				</td>
			</tr>
			
			<tr class="cvm_order">
				<th valign="top" scope="row">
			<?php endif;?>
			
			<?php if( $compact ):?>
			<span class="cvm_order">
			<?php endif;?>
				
					<label for="cvm_order"><?php _e('Order by', 'codeflavors-vimeo-video-post-lite');?> :</label>
			<?php if( !$compact ):?>		
					</th>
				<td>
			<?php endif;?>	
                <?php
                    Helper_Admin::select_sort_order(
                        'cvm_order',
                        ( isset( $_GET['cvm_order'] ) ? $_GET['cvm_order'] : false ),
                        'cvm_order'
                    );
                ?>
			<?php if( $compact ):?>
			</span>
			<?php endif;?>		
					
			<?php if( !$compact ):?>							
				</td>
			</tr>

            <tr class="cvm_search_results">
                <th valign="top" scope="row">
			<?php endif;?>

            <?php if( $compact ):?>
                <span class="cvm_search_results">
			<?php endif;?>

                    <label for="cvm_search_results"><?php _e( 'Search results','codeflavors-vimeo-video-post-lite' );?> :</label>

	        <?php if( !$compact ):?>
                </th>
                <td>
		    <?php endif;?>

                    <input type="text" name="cvm_search_results" id="cvm_search_results" value="<?php echo  isset( $_GET['cvm_search_results'] ) ? esc_attr( $_GET['cvm_search_results'] ) : '';?>" placeholder="<?php _e('enter optional search query', 'codeflavors-vimeo-video-post-lite');?>" size="25">
	        <?php if( $compact ):?>
                </span>
	        <?php endif;?>

	        <?php if( !$compact ):?>
                </td>
            </tr>
            <?php endif;?>
                    <!--
					<tr>
						<td valign="top"><label for=""></label></td>
						<td></td>
					</tr>
					-->
		
		<?php if( !$compact ):?>					
		</table>
		<?php endif;?>
		<?php wp_nonce_field('cvm-video-import', 'cvm_search_nonce', false);?>
		<?php
			$type = $compact ? 'secondary' : 'primary'; 
			submit_button( __( 'Load feed', 'codeflavors-vimeo-video-post-lite' ), $type, 'submit', !isset( $compact ) );
		?>
	</form>