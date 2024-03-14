<?php
/**
 * List layout.
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates
 * @since 2.1.0
 */

use ShapedPlugin\WPTeam\Frontend\Helper;
?>
<div id="<?php echo esc_attr( 'sptp-' . $generator_id ); ?>" class="sp-team sptp-section <?php echo 'sptp-' . esc_html( $page_link_type ); ?>">
	<?php
	Helper::sptp_section_title( $main_section_title, $generator_id, $settings );
	if ( ! empty( $filter_members ) ) :
		?>
		<div class="sptp-list <?php echo esc_attr( $position ); ?>">
			<?php Helper::sptp_preloader( $preloader ); ?>
			<div class="sptp-row">
				<?php
				$members_array = $filter_members;
				foreach ( $members_array as $key => $member ) {
					?>
					<div class="<?php echo esc_html( $responsive_classes ); ?>">
						<?php include Helper::sptp_locate_template( 'member.php' ); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	<?php endif; ?>
</div>
