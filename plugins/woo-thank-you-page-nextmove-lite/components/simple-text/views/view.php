<?php
defined( 'ABSPATH' ) || exit;

if ( '' !== $this->data->text || '' !== $this->data->heading ) {
	XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'On', 'woo-thank-you-page-nextmove-lite' ) ) );
	?>
    <div class="xlwcty_Box xlwcty_textBox xlwcty_textBoxSimpleText xlwcty_textBoxSimpleText_1">
		<?php
		echo $this->data->heading ? '<div class="xlwcty_title">' . XLWCTY_Common::maype_parse_merge_tags( $this->data->heading ) . '</div>' : '';
		echo $this->data->text ? '<div class="xlwcty_content">' . apply_filters( 'xlwcty_the_content', $this->data->text ) . '</div>' : '';
		?>
    </div>
	<?php
} else {
	XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'Data not set', 'woo-thank-you-page-nextmove-lite' ) ) );
}
