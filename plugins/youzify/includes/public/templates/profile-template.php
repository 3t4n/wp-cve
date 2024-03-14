<?php
/**
 * Template Name: Youzify Profile Template
 */
?>



<div id="youzify">

<?php do_action( 'youzify_profile_before_profile' ); ?>

<div id="<?php echo apply_filters( 'youzify_profile_template_id', 'youzify-bp' ); ?>" class="youzify noLightbox youzify-page youzify-profile <?php echo youzify_get_profile_class(); ?>">

	<?php do_action( 'youzify_profile_before_content' ); ?>

	<div class="youzify-content">

		<?php do_action( 'youzify_profile_before_header' ); ?>

		<header id="youzify-profile-header" class="<?php echo youzify_headers()->get_class( 'user' ); ?>" <?php echo youzify_widgets()->get_loading_effect( youzify_option( 'youzify_hdr_load_effect', 'fadeIn' ) ); ?>><?php do_action( 'youzify_profile_header' ); ?></header>

				<?php do_action( 'youzify_profile_navbar' ); ?>

				<main class="youzify-page-main-content">

					<?php

					/**
					 * Fires before the display of member home content.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_before_member_home_content' ); ?>

					<?php do_action( 'youzify_profile_main_content' ); ?>

					<?php

						/**
						 * Fires after the display of member home content.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_after_member_home_content' );

					?>

				</main>

		<?php do_action( 'youzify_profile_sidebar' ); ?>

	</div>

	<?php do_action( 'youzify_profile_after_content' ); ?>

</div>

<?php do_action( 'youzify_profile_after_profile' ); ?>

</div>