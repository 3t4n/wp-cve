<?php
use CatFolder_Document_Gallery\Helpers\Helper;

$data    = Helper::get_attachments( $attributes );
$columns = Helper::generate_columns( $attributes['displayColumns'] );

$libraryTitleTag    = $attributes['titleTag'];
$libraryTitle       = $attributes['title'];
$libraryIconAltText = $attributes['libraryIcon']['altText'];
$gridColumn         = $attributes['gridColumn'];

$is_display_title = $attributes['displayTitle'];
$is_display_icon  = $attributes['libraryIcon']['display'];

?>
<div id="cf-app" class="cf-app" data-json="<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>" data-columns="<?php echo esc_attr( wp_json_encode( $columns ) ); ?>">
	<div class="cf-main">
		<div class="cf-container">
			<?php if ( $is_display_icon || $is_display_title ) : ?>
				<<?php echo esc_html( $libraryTitleTag ); ?> class="cf-title">
					<img src="<?php echo esc_url( CATF_DG_IMAGES . 'icons/icon-folders.svg' ); ?>" alt=""<?php echo esc_attr( $libraryIconAltText ); ?>/>
					<span><?php echo esc_html( $libraryTitle ); ?></span>
				</<?php echo esc_html( $libraryTitleTag ); ?>>
			<?php endif; ?>

			<table class="cf-table" style="--grid-column:<?php echo esc_attr( $gridColumn ); ?>">
				<thead>
					<tr>
						<?php foreach ( $columns as $column ) { ?>
							<th class="<?php if ("Title" === $column['label']) echo esc_attr('cf-title-th') ?>">
								<span><?php echo esc_html( $column['label'] ); ?></span>
							</th>	
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $data['files'] as $file ) { ?>
						<tr><?php Helper::render_row( $columns, $file, $attributes ); ?></tr>
					<?php } ?> 
				</tbody>
			</table>
		</div>
	</div>
</div>
