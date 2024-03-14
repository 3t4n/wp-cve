<?php
/**
 * BuddyPress Activity templates
 */

/**
 * Fires before the activity directory listing.
 *
 * @since 1.5.0
 */

// Get Activity Layout
$layout = youzify_get_activity_layout();

do_action( 'bp_before_directory_activity' ); ?>

<div id="youzify">

<div id="<?php echo apply_filters( 'youzify_activity_template_id', 'youzify-bp' ); ?>" class="youzify youzify-page noLightbox <?php echo youzify_get_activity_page_class(); ?>">

	<div class="youzify-content">

		<main class="youzify-page-main-content">

			<?php

			/**
			 * Fires before the activity directory display content.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_directory_activity_content' ); ?>

			<?php if ( 'on' == youzify_option( 'youzify_enable_activity_directory_filter_bar', 'on' ) && apply_filters( 'youzify_enable_activity_directory_filter_bar', true ) ) : ?>

			<div class="youzify-mobile-nav">
				<div class="youzify-mobile-nav-item youzify-show-activity-menu"><div class="youzify-mobile-nav-container"><i class="fas fa-bars"></i><a><?php _e( 'Menu', 'youzify' ); ?></a></div></div>
				<div class="youzify-mobile-nav-item youzify-show-activity-search"><div class="youzify-mobile-nav-container"><i class="fas fa-search"></i><a><?php _e( 'Search', 'youzify' ); ?></a></div></div>
				<div class="youzify-mobile-nav-item youzify-show-activity-filter"><div class="youzify-mobile-nav-container"><i class="fas fa-sliders-h"></i><a><?php _e( 'Filter', 'youzify' ); ?></a></div></div>
			</div>

			<div id="youzify-wall-nav">
				<div class="item-list-tabs activity-type-tabs" aria-label="<?php esc_attr_e( 'Sitewide activities navigation', 'youzify' ); ?>" role="navigation">
					<ul>
						<?php

						/**
						 * Fires before the listing of activity type tabs.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_before_activity_type_tab_all' ); ?>

						<li id="activity-all" class="selected"><a href="<?php bp_activity_directory_permalink(); ?>"><?php printf( __( 'All Members %s', 'youzify' ), '<span>' . bp_get_total_member_count() . '</span>' ); ?></a></li>

						<?php if ( is_user_logged_in() ) : ?>

							<?php

							/**
							 * Fires before the listing of friends activity type tab.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_before_activity_type_tab_friends' ); ?>

							<?php if ( bp_is_active( 'friends' ) ) : ?>

								<?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

									<li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>"><?php printf( __( 'My Friends %s', 'youzify' ), '<span>' . bp_get_total_friend_count( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

								<?php endif; ?>

							<?php endif; ?>

							<?php

							/**
							 * Fires before the listing of groups activity type tab.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_before_activity_type_tab_groups' ); ?>

							<?php if ( bp_is_active( 'groups' ) ) : ?>

								<?php if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>

									<li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>"><?php printf( __( 'My Groups %s', 'youzify' ), '<span>' . bp_get_total_group_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

								<?php endif; ?>

							<?php endif; ?>

							<?php

							/**
							 * Fires before the listing of favorites activity type tab.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_before_activity_type_tab_favorites' ); ?>

							<?php if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) : ?>

								<li id="activity-favorites"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/'; ?>"><?php printf( __( 'My Favorites %s', 'youzify' ), '<span>' . bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

							<?php endif; ?>

							<?php if ( bp_activity_do_mentions() ) : ?>

								<?php

								/**
								 * Fires before the listing of mentions activity type tab.
								 *
								 * @since 1.2.0
								 */
								do_action( 'bp_before_activity_type_tab_mentions' ); ?>

								<li id="activity-mentions"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>"><?php _e( 'Mentions', 'youzify' ); ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><span><?php printf( _nx( '%s new', '%s new', bp_get_total_mention_count_for_user( bp_loggedin_user_id() ), 'Number of new activity mentions', 'youzify' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ); ?></span></strong><?php endif; ?></a></li>

							<?php endif; ?>

						<?php endif; ?>

						<?php

						/**
						 * Fires after the listing of activity type tabs.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_activity_type_tabs' ); ?>
					</ul>
				</div><!-- .item-list-tabs -->

				<div class="item-list-tabs activity-type-tabs-subnav no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Activity secondary navigation', 'youzify' ); ?>" role="navigation">
					<ul>
						<?php

						/**
						 * Fires before the display of the activity syndication options.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_activity_syndication_options' ); ?>

						<li id="activity-filter-select" class="last">
							<div class="youzify-activity-show-filter"><i class="fas fa-sliders-h"></i></div>
							<div class="youzify-dropdown-area">
							<label for="activity-filter-by"><?php _e( 'Filter Activities By :', 'youzify' ); ?></label>
							<select id="activity-filter-by" class="youzify-bar-select">
								<option value="-1"><?php _e( '&mdash; Everything &mdash;', 'youzify' ); ?></option>

								<?php bp_activity_show_filters(); ?>

								<?php

								/**
								 * Fires inside the select input for activity filter by options.
								 *
								 * @since 1.2.0
								 */
								do_action( 'bp_activity_filter_options' ); ?>

							</select>
							</div>
						</li>
						<li class="youzify-activity-show-search">

							<div class="youzify-activity-show-search-form"><i class="fas fa-search"></i></div>
							<div class="youzify-dropdown-area">
								<div class="youzify-activity-search">
									<form action="<?php echo bp_get_activity_directory_permalink(); ?>">
									<input type="text" name="s" placeholder="<?php _e( 'Search Activities ...', 'youzify' ); ?>">
									<button><i class="fas fa-search"></i></button>
									</form>
								</div>
							</div>
						</li>
					</ul>
				</div><!-- .item-list-tabs -->

		</div>

		<?php endif; ?><!-- Enable/Disable Global Activity Filter bar. -->


		<div class="<?php echo $layout; ?>">

		<div class="youzify-main-column">

			<div class="youzify-column-content">

				<?php if ( is_user_logged_in() ) : ?>

					<?php bp_get_template_part( 'activity/post-form' ); ?>

				<?php endif; ?>

				<div id="template-notices" role="alert" aria-atomic="true">
					<?php

					/**
					 * Fires towards the top of template pages for notice display.
					 *
					 * @since 1.0.0
					 */
					do_action( 'template_notices' ); ?>

				</div>

				<?php

				/**
				 * Fires before the display of the activity list.
				 *
				 * @since 1.5.0
				 */
				do_action( 'bp_before_directory_activity_list' ); ?>

				<div class="activity" aria-live="polite" aria-atomic="true" aria-relevant="all">

					<?php bp_get_template_part( 'activity/activity-loop' ); ?>

				</div><!-- .activity -->

				<?php

				/**
				 * Fires after the display of the activity list.
				 *
				 * @since 1.5.0
				 */
				do_action( 'bp_after_directory_activity_list' ); ?>

				<?php

				/**
				 * Fires inside and displays the activity directory display content.
				 */
				do_action( 'bp_directory_activity_content' ); ?>
			</div>
		</div>

		<?php do_action( 'youzify_activity_stream_sidebar', $layout ); ?>


		</div>
	<?php

	/**
	 * Fires after the activity directory display content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_directory_activity_content' ); ?>

	<?php

	/**
	 * Fires after the activity directory listing.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_after_directory_activity' ); ?>

	</main>

	</div><!-- .youzify-content -->

</div>
</div>
