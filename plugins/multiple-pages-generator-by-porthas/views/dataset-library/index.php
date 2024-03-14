<?php

if (!defined('ABSPATH')) {
    exit;
}

class MPG_DatasetLibraryView
{
    public static function render($datasets_list)
    {

        add_action('admin_head', ['Helper', 'mpg_header_code_container']);

?>

        <div class="dataset-library">
            <div class="main-page-container">
			<?php
			global $wpdb;
			$projects       = $wpdb->get_results( "SELECT id, name FROM {$wpdb->prefix}" . MPG_Constant::MPG_PROJECTS_TABLE );
			$projects_count = count( $projects );
			$is_pro         = mpg_app()->is_premium();
			if ( ! $is_pro && $projects_count > 0 ) :
				?>
				<div class="mpg-notice mpg-notice-top">
						<?php
						echo wp_kses(
							// translators: %1$s to upgrade URL, %2$s to button label.
							sprintf( __( 'Project limit reached! Create unlimited projects, by <a href="%1$s" target="_blank">%2$s</a>', 'mpg' ), esc_url( mpg_app()->get_upgrade_url( 'setupProject' ) ), __( 'upgrading to MPG Pro', 'mpg' ) ),
							array(
								'a' => array(
									'href'   => true,
									'class'  => true,
									'target' => true,
								),
							)
						);
						?>
				</div>
			<?php endif; ?>
                <div class="main-inner-content shadowed">
                    <div class="top-content">
                        <form name="filterform" onsubmit="return false;">
                            <div class="left-block">
                                <h1><?php _e('MPG Setup', 'mpg'); ?></h1>
                                <span id="mpg_result_count"></span>
                            </div>
                            <div class="right-block">
                                <input type="search" name="filterinput" id="filterinput" placeholder="<?php _e('Search for template', 'mpg'); ?>" />
                                <input type="button" class="btn btn-secondary" name="clear" value="<?php _e('Reset', 'mpg'); ?>" id="clearfilter" />
                            </div>
                        </form>
                    </div>

                    <div class="middle-content-container">
                    	<div class="mpg-project-list-wrap">
                    		<?php
                    		if ( ! $is_pro && $projects_count > 0 ) :
                    			?>
                    			<div class="overlay">
                    				<div class="mpg-notice mpg-inner-notice">
                    					<?php
                    					echo wp_kses(
											// translators: %1$s to upgrade URL, %2$s to button label.
                    						sprintf( __( 'Unlock all templates and create unlimited projects, by <a href="%1$s" target="_blank">%2$s</a>', 'mpg' ), esc_url( mpg_app()->get_upgrade_url( 'setupProject' ) ), __( 'upgrading to MPG Pro', 'mpg' ) ),
                    						array(
                    							'a' => array(
                    								'href'   => true,
                    								'class'  => true,
                    								'target' => true,
                    							),
                    						)
                    					);
                    					?>
                    				</div>
                    			</div>
                    		<?php endif; ?>
                    		<ul id="dataset_list">
                    			<?php
                            	// Replace headers row to "From scratch"
                    			$datasets_list[0] = array(1 => __('From scratch', 'mpg'), 2 => 'fa fa-file');

                    			foreach ($datasets_list as $index => $dataset) {
                                // Избавляемся от пустых рядов
                    				if (isset($dataset[0]) && !$dataset[0]) {
                    					continue;
                    				} ?>

                    				<li>

                    					<a <?php if (!$is_pro && $projects_count > 0) {
                    						echo 'class="disable-tile"';
                    					} ?> <?php
                    					if (!$is_pro & $projects_count > 0) {
                    						$link = '#';
                    					} else {
                    						if ($index === 0) {
                    							$link = add_query_arg(
                    								array(
                    									'page' => 'mpg-project-builder',
                    									'action' => 'from_scratch',
                    								),
                    								admin_url( 'admin.php' )
                    							);
                    						} else {
                    							$link = add_query_arg( 'page', 'mpg-deploy-dataset', admin_url( 'admin.php' ) );
                    						}
                    					} ?> <?php
                    					if (isset($dataset[0])) {

                    						echo  'data-dataset-id=' .  (int) $dataset[0];
                    					} else {
                    						'data-dataset-id=""';
                    					} ?> href="<?php echo esc_url($link); ?>">

                    					<?php if (!$is_pro & $projects_count > 0) { ?>
                    						<div class="pro-field">Pro</div>
                    					<?php } ?>

                    					<i class="<?php echo esc_textarea($dataset[2]); ?>"></i>
                    					<span><?php echo esc_textarea($dataset[1]); ?></span>
                    					<div class="dataset-filesize">
                    						<?php if (isset($dataset[13])) {
                    							echo esc_textarea($dataset[13]);
                    						} ?></div>
                    					</a>

                    				</li>
                    			<?php } ?>
                    		</ul>
                    	</div>
                    </div>

                    <div class="load-more-container">
                        <a href="#" class="load-more hide"><?php _e('Load more', 'mpg'); ?></a>
                    </div>
                </div>

                <div class="sidebar-container">
                    <?php require_once('sidebar.php'); ?>
                </div>
            </div>
        </div>

<?php }
}
