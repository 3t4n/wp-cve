<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Exit if accessed directly

$design_type = $settings['ccewd_widget_type'];

$all_coin_wall_add    = $settings['ccewd_repeater_data'];
$title                = ! empty( $settings['ccewd_coins_title'] ) ? $settings['ccewd_coins_title'] : 'Donate [coin-name] to this address';
$description          = ! empty( $settings['ccewd_coins_description'] ) ? $settings['ccewd_coins_description'] : 'Scan the QR code or copy the address below into your wallet to send some [coin-name]';
$metamask_description = ! empty( $settings['ccewd_metamask_title'] ) ? $settings['ccewd_metamask_title'] : 'Donate ETH Via PAY With Metamask';
$cdb_metamask_title   = ! empty( $settings['ccewd_metamask_description'] ) ? $settings['ccewd_metamask_description'] : 'Donate With MetaMask';
$metamask_price= !empty($settings['ccewd_metamask_price']) ? $settings['ccewd_metamask_price'] : '0.005';

$i                    = 0;
$coin_links           = '';
$coin_tabs            = '';
$classic_list         = '';
$output               = '';
$random               = rand();
if ( ! empty( $all_coin_wall_add ) && is_array( $all_coin_wall_add ) && array_filter( $all_coin_wall_add ) ) {
	foreach ( $all_coin_wall_add as $id => $address ) {


			$metamask_wall_add = ( $address['ccewd_coin_list'] == 'metamask' ) ? $address['ccewd_wallet_address'] : '';

		if ( $i == 0 ) {
			$active_tab = 'current';
		} else {
			$active_tab = '';
		}
		if ( $address['ccewd_coin_list'] != 'metamask' ) {
			$coin_name = ucfirst( str_replace( '-', ' ', $address['ccewd_coin_list'] ) );
		} else {
			$coin_name = 'MetaMask';
		}

			$title_content = str_replace( '[coin-name]', $coin_name, $title );
			$desc_content  = str_replace( '[coin-name]', $coin_name, $description );
			$coin_logo     = CCEW_URL . '/donation-box/assets/logos/' . $address['ccewd_coin_list'] . '.svg';
			$logo_html     = '<img src="' . esc_url( $coin_logo ) . '"> ';
			$logo_html    .= $coin_name;
			$coin_links   .= '<li class="ccewd-coins ' . esc_attr( $active_tab ) . '" id="' . esc_attr( $address['ccewd_coin_list'] ) . '" data-tab="' . esc_attr( $address['ccewd_coin_list'] ) . '-tab">' . $logo_html . '</li>';

		if ( $design_type == 'list' ) {
			if ( $address['ccewd_coin_list'] !== 'metamask' && ! empty( $address['ccewd_wallet_address'] ) ) {
				$classic_list             .= '<li class="ccewd-classic-list">';
				$classic_list             .= '<h2 class="ccewd-title">' . esc_html( $title_content ) . '</h2>';
				$classic_list             .= '<div class="ccewd_qr_code"><img src="https://chart.googleapis.com/chart?cht=qr&chl=' . $address['ccewd_wallet_address'] . '&chs=260x260&chld=L|0" alt="Scan to Donate ' . esc_attr( $coin_name ) . ' to ' . esc_attr( $address['ccewd_wallet_address'] ) . '"/>';
				$classic_list             .= '</div><div class="ccewd_classic_input_add">
                            <input type="text" class="wallet-address-input"  id="' . esc_attr( $address['ccewd_coin_list'] ) . '-classic-wallet-address-' . esc_attr( $random ) . '" name="' . esc_attr( $address['ccewd_coin_list'] ) . '-classic-wallet-address" value="' . esc_attr( $address['ccewd_wallet_address'] ) . '" readonly >';
							$classic_list .= '<button class="ccewd_btn" data-clipboard-target="#' . esc_attr( $address['ccewd_coin_list'] ) . '-classic-wallet-address-' . esc_attr( $random ) . '">
                            ' . __( 'COPY', 'ccewd' ) . '</button>
                                </div>';

				if ( isset( $address['ccewd_wallet_address_meta'] ) && ! empty( $address['ccewd_wallet_address_meta'] ) ) {
					$classic_list .= '<div class="ccewd_tag"><span class="ccewd_tag_heading">' . __( 'Tag/Note:-', 'ccewd' ) . ' </span>' . esc_html( $address['ccewd_wallet_address_meta'] ) . '</div>';
				}
				$classic_list .= '</li>';
			} elseif ( $address['ccewd_coin_list'] == 'metamask' && ! empty( $address['ccewd_wallet_address'] ) ) {
				$classic_list .= '<li class="ccewd-classic-list">
                            <h2 class="ccewd-title">' . esc_html( $cdb_metamask_title ) . '</h2>';
				$classic_list .= '<div class="tip-button" data-metamask-address="' . esc_attr( $metamask_wall_add ) . '" data-metamask-amount="' . esc_attr( $metamask_price ) . '"></div>';
				if ( isset( $address['ccewd_wallet_address_meta'] ) && ! empty( $address['ccewd_wallet_address_meta'] ) ) {
					$classic_list .= '<div class="ccewd_tag"><span class="ccewd_tag_heading">' . __( 'Tag/Note:-', 'ccewd' ) . ' </span>' . esc_html( $address['ccewd_wallet_address_meta'] ) . '</div>';
				}
				$classic_list .= '<div class="message"></div></li>';
			} else {
				if ( $address['ccewd_coin_list'] == 'select' ) {
					$classic_list .= '<div class="message">Please select coins</div>';

				} else {
					$classic_list .= '<div class="message">Please enter wallet address</div>';
				}
			}
		} else {
			$coin_tabs .= '<div class="ccewd-tabs-content ' . esc_attr( $active_tab ) . '" id="' . esc_attr( $address['ccewd_coin_list'] ) . '-tab">';

			if ( $address['ccewd_coin_list'] != 'metamask' && ! empty( $address['ccewd_wallet_address'] ) ) {
				$coin_tabs .= '<div class="ccewd_qr_code"><img src="https://chart.googleapis.com/chart?cht=qr&chl=' . $address['ccewd_wallet_address'] . '&chs=260x260&chld=L|0" alt="Scan to Donate ' . esc_attr( $coin_name ) . ' to ' . esc_attr( $address['ccewd_wallet_address'] ) . '"/>';
				$coin_tabs .= '</div><div class="ccewd_input_add">
                            <h2 class="ccewd-title">' . esc_html( $title_content ) . '</h2>
                            <p class="ccewd-desc">' . esc_html( $desc_content ) . '</p>';
				if ( isset( $address['ccewd_wallet_address_meta'] ) && ! empty( $address['ccewd_wallet_address_meta'] ) ) {
					$coin_tabs .= '<div class="ccewd_tag"><span class="ccewd_tag_heading">' . __( 'Tag/Note:-', 'ccewd' ) . ' </span>' . esc_html( $address['ccewd_wallet_address_meta'] ) . '</div>';
				}
				$coin_tabs .= ' <input type="text" class="wallet-address-input"  id="' . esc_attr( $address['ccewd_coin_list'] ) . '-wallet-address-' . esc_attr( $random ) . '" name="' . esc_attr( $address['ccewd_coin_list'] ) . '-wallet-address" value="' . esc_attr( $address['ccewd_wallet_address'] ) . '" readonly>';

				$coin_tabs .= '
                            <button class="ccewd_btn" data-clipboard-target="#' . esc_attr( $address['ccewd_coin_list'] ) . '-wallet-address-' . esc_attr( $random ) . '">
                            ' . __( 'COPY', 'ccewd' ) . '</button></div>';
			} elseif ( $address['ccewd_coin_list'] == 'metamask' && ! empty( $address['ccewd_wallet_address'] ) ) {

				$coin_tabs .= '<div class="cdb-metamask-wrapper" >
                            <h2 class="ccewd-title">' . esc_html( $cdb_metamask_title ) . '</h2>
                            <p class="ccewd-desc">' . esc_html( $metamask_description ) . '</p>';
				$coin_tabs .= '<div class="tip-button" data-metamask-address="' . esc_attr( $metamask_wall_add ) . '" data-metamask-amount="' . esc_attr( $metamask_price ) . '"></div>';
				if ( isset( $address['ccewd_wallet_address_meta'] ) && ! empty( $address['ccewd_wallet_address_meta'] ) ) {
					$coin_tabs .= '<div class="ccewd_tag"><span class="ccewd_tag_heading">' . __( 'Tag/Note:-', 'ccewd' ) . ' </span>' . esc_html( $address['ccewd_wallet_address_meta'] ) . '</div>';
				}
				$coin_tabs .= '<div class="message"></div></div>';
			} else {

				if ( $address['ccewd_coin_list'] == 'select' ) {
					  $coin_tabs .= '<div class="message">Please select coins</div>';

				} else {
											$coin_tabs .= '<div class="message">Please enter wallet address</div>';
				}
			}
			$coin_tabs .= '</div>';
		}

			$i++;


	}

	if ( $design_type == 'list' ) {
			$output .= '<div class="ccewd-classic-container">';
			$output .= '<ul class="ccewd-classic-list">';
			$output .= $classic_list;
			$output .= '</ul></div>';

	} else {
		$output .= '<div class="ccewd-container" id="ccewd-random-' . esc_attr( $random ) . '">';
		$output .= '<ul class="ccewd-tabs" id="ccewd-coin-list">' . $coin_links . '</ul>';
		$output .= $coin_tabs;
		$output .= '</div>';

	}
} else {
	$output .= '<h6>' . esc_html__( 'Please Add coin wallet address in plugin settings panel', 'ccewd' ) . '</h6>';
}

echo  $output;
