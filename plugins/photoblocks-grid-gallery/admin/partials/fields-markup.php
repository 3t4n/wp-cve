<?php foreach ( $this->settings->fields[ $section ] as $group ) : ?>
<div class="pb-section-group" id="sub-<?php echo esc_attr( Photoblocks_Utils::slugify( $group['name'] ) ); ?>">
	<h3><?php echo wp_kses_post( __( $group['name'], 'photoblocks' ) ); ?></h3>
	<ul class="photoblocks-expandable js-group-<?php echo esc_attr( Photoblocks_Utils::slugify( $group['name'] ) ); ?> settings-panel">
		<?php foreach ( $group['fields'] as $k => $field ) : ?>
			<?php if ( $field['render'] ) : ?>
		<li class="field field-code-<?php echo esc_attr( $field['code'] ); ?>" data-code="<?php echo esc_attr( $field['code'] ); ?>" data-show-if="<?php echo esc_attr( $field['show_if'] ); ?>">
				<?php include 'field-type/' . $field['type'] . '.php'; ?>
		</li>
		<?php endif ?>
		<?php endforeach ?>
	</ul>
</div>
<?php endforeach ?>
