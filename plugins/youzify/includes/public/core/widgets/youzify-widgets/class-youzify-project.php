<?php

class Youzify_Profile_Project_Widget {

    /**
     * Profile Content.
     */
    function widget() {

        // Get User ID
        $user_id = bp_displayed_user_id();

        // Get Project Decription
        $project_description = get_the_author_meta( 'youzify_wg_project_desc', $user_id );

        if ( empty( $project_description ) ) {
            return;
        }

        // Get Project Title.
        $project_title = get_the_author_meta( 'youzify_wg_project_title', $user_id );

        if ( ! $project_title ) {
            return false;
        }

        // Get Project Meta.
        $project_tags        = get_the_author_meta( 'youzify_wg_project_tags', $user_id );
        $project_link        = get_the_author_meta( 'youzify_wg_project_link', $user_id );
        $project_categories  = get_the_author_meta( 'youzify_wg_project_categories', $user_id );
        $project_thumbnail   = get_the_author_meta( 'youzify_wg_project_thumbnail', $user_id );

    	// Show / Hide Project Elements
    	$display_icons = youzify_option( 'youzify_display_prjct_meta_icons', 'on' );

    	?>

    	<div class="youzify-project-content">
    		<?php
                youzify_get_post_thumbnail(
                    array(
                        'widget'  => 'project',
                        'attachment_id' => $project_thumbnail,
                        'element' => 'profile-project-widget',
                        'size'  => 'medium'
                    )
                );
            ?>
    		<div class="youzify-project-container">
    			<div class="youzify-project-inner-content">
    				<div class="youzify-project-head">

                        <a class="youzify-project-type"><?php esc_attr_e( get_the_author_meta( 'youzify_wg_project_type', $user_id ) ); ?></a>

                        <?php if ( $project_title ) : ?>
    					   <h2 class="youzify-project-title"><?php echo $project_title; ?></h2>
                        <?php endif; ?>

    					<?php if ( 'on' == youzify_option( 'youzify_display_prjct_meta', 'on' ) ) : ?>
    					<div class="youzify-project-meta">
    						<ul>
                                <?php if ( $project_categories ) : ?>
        							<li class="youzify-project-categories">
            							<?php if ( 'on' == $display_icons ) : ?>
                                            <i class="fas fa-tags"></i>
                                        <?php endif ?>
                                        <?php echo implode( ', ', $project_categories ); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ( $project_link ) : ?>
                                    <li class="youzify-project-link">
            							<?php if ( 'on' == $display_icons ) : ?>
                                            <i class="fas fa-link"></i>
                                        <?php endif; ?>
                                        <a href="<?php echo esc_url( $project_link ) ;?>"><?php echo youzify_esc_url( $project_link ); ?></a>

                                    </li>
                                <?php endif; ?>
    						</ul>
    					</div>
    					<?php endif; ?>
    				</div>

    				<div class="youzify-project-text"><?php echo wpautop( wp_kses_post( html_entity_decode( $project_description ) ) ); ?></div>

    				<?php if ( 'on' == youzify_option( 'youzify_display_prjct_tags', 'on' ) && ! empty( $project_tags ) ) : ?>
        				<div class="youzify-project-tags">
        					<?php youzify_get_project_tags( $project_tags ); ?>
        				</div>
    				<?php endif; ?>

    			</div>
    		</div>
    	</div>

    	<?php
    }

}