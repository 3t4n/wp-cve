<?php
defined( 'ABSPATH' ) || exit;

XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'On', 'woo-thank-you-page-nextmove-lite' ) ) );
?>
    <div class="xlwcty_order_info">
		<?php
		echo $this->icon_html ? $this->icon_html : '';
		echo $this->data->heading ? '<div class="xlwcty_order_no">' . XLWCTY_Common::maype_parse_merge_tags( $this->data->heading ) . '</div>' : '';
		echo $this->data->heading2 ? '<div class="xlwcty_userN">' . XLWCTY_Common::maype_parse_merge_tags( $this->data->heading2 ) . '</div>' : '';
		?>
    </div>
<?php
