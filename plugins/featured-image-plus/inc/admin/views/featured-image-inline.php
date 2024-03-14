<?php
/**
 * [Short Description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

?>
<!-- <fieldset class="inline-edit-col-left">&nbsp;</fieldset> -->
<fieldset class="inline-edit-col-right">
	<div class="fip-admin">
		<div class="fip-loading-bar"></div>
		<div class="fip-inline-container">
			<span class="fip-title">
				<?php echo esc_html__( 'Featured Image', 'featured-image-plus' ); ?>
			</span>
			<input 
				id="fip-url"
				name="fip-url" 
				type="text"
				class="url" 
				value="<?php echo ( 'quick' === $type ) ? esc_html__( 'No image selected.', 'featured-image-plus' ) : esc_html__( 'Multiple images selected.', 'featured-image-plus' ); ?>" 
				placeholder="<?php echo ( 'quick' === $type ) ? esc_html__( 'No image selected.', 'featured-image-plus' ) : esc_html__( 'Multiple images selected.', 'featured-image-plus' ); ?>" 
				readonly 
			/>

			<button 
				name="<?php echo ( 'quick' === $type ) ? 'fip-button-upload-image' : 'fip-button-upload-bulk-image'; ?>" 
				class="button button-upload" 
			/>
				<?php echo esc_html__( 'Browse...', 'featured-image-plus' ); ?>
			</button>

			<input
				id="fip-thumbnail-id" 
				name="_thumbnail_id" 
				type="hidden" 
				class="id" 
				value="" 
			/>

			<?php if ( 'quick' === $type ) : ?>
				<img id="fip-preview" class="fip-preview" src="" alt="" />
				<button name="fip-button-remove" class="button button-remove">
					<?php echo esc_html__( 'Remove Featured Image', 'featured-image-plus' ); ?>
				</button>
			<?php else : ?>
				<img id="fip-preview" class="fip-preview" src="" alt="" />
				<button name="fip-button-remove-bulk" class="button button-remove-bulk">
					<?php echo esc_html__( 'Remove ALL Featured Images', 'featured-image-plus' ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>
</fieldset>
