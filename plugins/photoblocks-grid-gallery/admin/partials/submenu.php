<?php foreach ( $this->settings->fields[ $section ] as $group ) : ?>    
	<li><a href="#sub-<?php echo esc_attr( Photoblocks_Utils::slugify( $group['name'] ) ); ?>"><?php esc_html_e( $group['name'], 'photoblocks' ); ?></a></li>
<?php endforeach ?>
