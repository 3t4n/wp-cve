<p class="pb-settings-label">
	<?php echo wp_kses_post( _e( $field['name'], 'photoblocks' ) ); ?>
	Useful classes to add your own style:<br>
	<style>
		.pb-css-help {
			font-size:11px;
			background: #fff;
			width: 100%;
			border:1px dashed #f0f0f0;
		}
		.pb-css-help td,
		.pb-css-help th {
			border-bottom: 1px solid #f0f0f0;
			padding: 4px 6px;
		}
	</style>
	<table class="pb-css-help">
		<tr>
			<th>CSS Selector</td>
			<th>Description</td>
		</tr>
		<tr>
			<td class="pb-class">.photoblocks-gallery</td>
			<td class="pb-help">Gallery</td>
		</tr>
		<tr>
			<td class="pb-class">.photoblocks-gallery .pb-blocks</td>
			<td class="pb-help">Blocks container</td>
		</tr>
		<tr>
			<td class="pb-class">.photoblocks-gallery .pb-block</td>
			<td class="pb-help">Block</td>
		</tr>
		<tr>
			<td class="pb-class">.photoblocks-gallery .pb-block .pb-image</td>
			<td class="pb-help">Image</td>
		</tr>
		<tr>
			<td class="pb-class">.photoblocks-gallery .pb-block .pb-overlay</td>
			<td class="pb-help">Overlay with captions and social icons</td>
		</tr>
		<tr>
			<td class="pb-class">.photoblocks-gallery .pb-block .pb-overlay .pb-title</td>
			<td class="pb-help">Title</td>
		</tr>
		<tr>
			<td class="pb-class">.photoblocks-gallery .pb-block .pb-overlay .pb-description</td>
			<td class="pb-help">Description</td>
		</tr>
	</table>
</p>
