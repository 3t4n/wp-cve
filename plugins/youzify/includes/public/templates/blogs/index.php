<?php
/**
 * BuddyPress - Blogs
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

/**
 * Fires at the top of the blogs directory template file.
 *
 * @since 2.3.0
 */
do_action( 'bp_before_directory_blogs_page' ); ?>

<div id="youzify">

<div id="<?php echo apply_filters( 'youzify_members_template_id', 'youzify-bp' ); ?>" class="youzify <?php echo youzify_blogs_directory_class() ?>">

	<?php do_action( 'youzify_before_directory_sites_main_content' ); ?>

	<main class="youzify-page-main-content">

		<div id="youzify-blogs-directory">

	<?php

	/**
	 * Fires before the display of the blogs.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_before_directory_blogs' ); ?>

	<?php

	/**
	 * Fires before the display of the blogs listing content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_blogs_content' ); ?>

	<div class="youzify-mobile-nav">
		<div id="directory-show-menu" class="youzify-mobile-nav-item"><div class="youzify-mobile-nav-container"><i class="fas fa-bars"></i><a><?php _e( 'Menu', 'youzify' ); ?></a></div></div>
		<div id="directory-show-search" class="youzify-mobile-nav-item"><div class="youzify-mobile-nav-container"><i class="fas fa-search"></i><a><?php _e( 'Search', 'youzify' ); ?></a></div></div>
		<div id="directory-show-filter" class="youzify-mobile-nav-item"><div class="youzify-mobile-nav-container"><i class="fas fa-sliders-h"></i><a><?php _e( 'Filter', 'youzify' ); ?></a></div></div>
	</div>

	<?php

	/**
	 * Fires before the display of the blogs list tabs.
	 *
	 * @since 2.3.0
	 */
	do_action( 'bp_before_directory_blogs_tabs' ); ?>

	<form action="" method="post" id="blogs-directory-form" class="dir-form">

		<div class="youzify-directory-filter">

		<div class="item-list-tabs" aria-label="<?php esc_attr_e( 'Sites directory main navigation', 'buddypress' ); ?>" role="navigation">
			<ul>
				<li class="selected" id="blogs-all">
					<a href="<?php bp_root_url(); ?>/<?php bp_blogs_root_slug(); ?>">
						<?php
						/* translators: %s: all blogs count */
						printf( __( 'All Sites %s', 'buddypress' ), '<span>' . bp_get_total_blog_count() . '</span>' ); ?>
					</a>
				</li>

				<?php if ( is_user_logged_in() && bp_get_total_blog_count_for_user( bp_loggedin_user_id() ) ) : ?>

					<li id="blogs-personal">
						<a href="<?php echo bp_loggedin_user_domain() . bp_get_blogs_slug(); ?>">
							<?php
							/* translators: %s: current user blogs count */
							printf( __( 'My Sites %s', 'buddypress' ), '<span>' . bp_get_total_blog_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?>
						</a>
					</li>

				<?php endif; ?>

				<?php

				/**
				 * Fires inside the unordered list displaying blog types.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_blogs_directory_blog_types' ); ?>

			</ul>
		</div><!-- .item-list-tabs -->

		<div class="item-list-tabs" id="subnav" aria-label="<?php esc_attr_e( 'Sites directory secondary navigation', 'buddypress' ); ?>" role="navigation">
			<ul>

				<?php

				/**
				 * Fires inside the unordered list displaying blog sub-types.
				 *
				 * @since 1.5.0
				 */
				do_action( 'bp_blogs_directory_blog_sub_types' ); ?>

				<li id="blogs-order-select" class="last filter">

					<label for="blogs-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
					<select id="blogs-order-by">
						<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
						<option value="newest"><?php _e( 'Newest', 'buddypress' ); ?></option>
						<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>

						<?php

						/**
						 * Fires inside the select input listing blogs orderby options.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_blogs_directory_order_options' ); ?>

					</select>
				</li>

				<?php if ( apply_filters( 'youzify_display_blogs_directory_search_bar', true ) ) : ?>
				<li id="youzify-directory-search-box">
					<?php /* Backward compatibility for inline search form. Use template part instead. */ ?>
					<?php if ( has_filter( 'bp_directory_blogs_search_form' ) ) : ?>

						<div id="blog-dir-search" class="dir-search" role="search">
							<?php bp_directory_blogs_search_form(); ?>
						</div><!-- #blog-dir-search -->

					<?php else : ?>

						<?php bp_get_template_part( 'common/search/dir-search-form' ); ?>

					<?php endif; ?>
				</li>
				<?php endif; ?>

			</ul>
		</div>
		</div><!-- .youzify-directory-filter -->

		<h2 class="bp-screen-reader-text"><?php
			/* translators: accessibility text */
			_e( 'Sites directory', 'buddypress' );
		?></h2>

		<div id="blogs-dir-list" class="blogs dir-list">

			<?php bp_get_template_part( 'blogs/blogs-loop' ); ?>

		</div><!-- #blogs-dir-list -->

		<?php

		/**
		 * Fires inside and displays the blogs content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_directory_blogs_content' ); ?>

		<?php wp_nonce_field( 'directory_blogs', '_wpnonce-blogs-filter' ); ?>

		<?php

		/**
		 * Fires after the display of the blogs listing content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_directory_blogs_content' ); ?>

	</form><!-- #blogs-directory-form -->

	<?php

	/**
	 * Fires at the bottom of the blogs directory template file.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_after_directory_blogs' ); ?>

	</div>

</main>

</div>

</div>

<?php

/**
 * Fires at the bottom of the blogs directory template file.
 *
 * @since 2.3.0
 */
do_action( 'bp_after_directory_blogs_page' );
