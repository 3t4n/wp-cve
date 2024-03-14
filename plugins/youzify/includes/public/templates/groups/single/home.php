<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

<?php do_action( 'youzify_group_before_group' ); ?>
<div id="youzify">

<div id="<?php echo apply_filters( 'youzify_group_template_id', 'youzify-bp' ); ?>" class="youzify <?php echo youzify_group_page_class(); ?>">

	<?php do_action( 'youzify_group_before_content' ); ?>

	<div class="youzify-content">

		<header id="youzify-group-header" class="<?php echo youzify_headers()->get_class( 'group' ); ?>">

			<?php do_action( 'youzify_group_header' ); ?>

		</header>

		<div class="youzify-group-content">

			<div class="youzify-inner-content">

				<?php do_action( 'youzify_group_navbar' ); ?>

				<main class="youzify-page-main-content">

					<?php do_action( 'youzify_group_main_content' ); ?>

				</main>

			</div>

		</div>

	</div>

	<?php do_action( 'youzify_group_after_content' ); ?>

</div>

</div>

<?php do_action( 'youzify_group_after_group' ); ?>

<?php endwhile; endif; ?>