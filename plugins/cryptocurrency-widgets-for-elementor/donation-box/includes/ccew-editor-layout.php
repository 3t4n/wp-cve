<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Exit if accessed directly
?>
<#

var design_type = settings.ccewd_widget_type,
all_coin_wall_add = settings.ccewd_repeater_data,
title = (settings.ccewd_coins_title!=="") ? settings.ccewd_coins_title : 'Donate [coin-name] to this address',
description = (settings.ccewd_coins_description!=="") ? settings.ccewd_coins_description : 'Scan the QR code or copy the address below into your wallet to send some [coin-name]',
metamask_description = (settings.ccewd_metamask_title!=="") ? settings.ccewd_metamask_title : 'Donate ETH Via PAY With Metamask',
cdb_metamask_title = (settings.ccewd_metamask_description!=="") ? settings.ccewd_metamask_description : 'Donate With MetaMask',
metamask_price= (settings.ccewd_metamask_price!="") ? settings.ccewd_metamask_price : '0.005';
i = 0,
coin_links = "",
coin_tabs = "",
classic_list = "",
output = "",
random = Math.floor((Math.random() * 10000) + 1);
if (all_coin_wall_add!=="") {
	 _.each(all_coin_wall_add , function( address, id ) {

		var metamask_wall_add = (address.ccewd_coin_list == "metamask") ? address.ccewd_wallet_address : '';
		var active_tab="",coin_name="",logo_html="";
		if (i == 0) {
			active_tab = 'current';
		} else {
			active_tab = '';
		}
		if (address.ccewd_coin_list != 'metamask') {
			coin_name = address.ccewd_coin_list.replace('-', ' ');
		} else {
			coin_name = 'MetaMask';
		}
		
		var title_content = title.replace('[coin-name]', coin_name[0].toUpperCase() + coin_name.slice(1)),
		desc_content = description.replace('[coin-name]', coin_name[0].toUpperCase() + coin_name.slice(1)),
		coin_logo = settings.ccewd_url +'/donation-box/assets/logos/'+address.ccewd_coin_list+'.svg';
		logo_html += '<img src="'+coin_logo+ '"> ';
		logo_html += coin_name;
		coin_links += '<li class="ccewd-coins ' +active_tab+ '" id="' +address.ccewd_coin_list+ '" data-tab="' +address.ccewd_coin_list+ '-tab"> '+logo_html +'</li>';

		if (design_type == 'list') {
			if (address.ccewd_coin_list !== 'metamask' && (address.ccewd_wallet_address!=="")) {
				classic_list += '<li class="ccewd-classic-list">';
				classic_list += '<h2 class="ccewd-title">' +title_content+ '</h2>';
				classic_list += '<div class="ccewd_qr_code"><img src="https://chart.googleapis.com/chart?cht=qr&chl=' +address.ccewd_wallet_address + '&chs=260x260&chld=L|0" alt="Scan to Donate ' +coin_name+ ' to ' +address.ccewd_wallet_address+ '"/>';
				classic_list += '</div><div class="ccewd_classic_input_add"><input type="text" class="wallet-address-input"  id="' +address.ccewd_coin_list+'-classic-wallet-address-'+random+'" name="' +address.ccewd_coin_list+'-classic-wallet-address" value="' +address.ccewd_wallet_address+ '">';
				classic_list += '<button class="ccewd_btn" data-clipboard-target="#' +address.ccewd_coin_list+'-classic-wallet-address-'+random+'">COPY</button></div>';

				if (address.ccewd_wallet_address_meta!=="") {
					classic_list += '<div class="ccewd_tag"><span class="ccewd_tag_heading">Tag/Note:- </span>' +address.ccewd_wallet_address_meta+'</div>';
				}
				classic_list += '</li>';
			} else if (address.ccewd_coin_list == 'metamask' && address.ccewd_wallet_address!=="") {
				classic_list += '<li class="ccewd-classic-list"><h2 class="ccewd-title">' +cdb_metamask_title+'</h2>';
				classic_list += '<div class="tip-button" data-metamask-address="' +metamask_wall_add+'" data-metamask-amount="'+metamask_price +'"></div>';
				if (address.ccewd_wallet_address_meta!=="") {
					classic_list += '<div class="ccewd_tag"><span class="ccewd_tag_heading">Tag/Note:- </span>' +address.ccewd_wallet_address_meta+'</div>';
				}
				classic_list += '<div class="message"></div></li>';
			} else {
				if (address.ccewd_coin_list == 'select') {
					classic_list += '<div class="message">Please select coins</div>';

				} else {
					classic_list += '<div class="message">Please enter wallet address</div>';
				}

			}

		} else {
			coin_tabs += '<div class="ccewd-tabs-content ' +active_tab+'" id="' +address.ccewd_coin_list+'-tab">';

			if (address.ccewd_coin_list !== 'metamask' && (address.ccewd_wallet_address!=="")) {
				coin_tabs += '<div class="ccewd_qr_code"><img src="https://chart.googleapis.com/chart?cht=qr&chl=' +address.ccewd_wallet_address+ '&chs=260x260&chld=L|0" alt="Scan to Donate ' +coin_name+' to ' +address.ccewd_wallet_address+ '"/>';
				coin_tabs += '</div><div class="ccewd_input_add"><h2 class="ccewd-title">' +title_content+ '</h2> <p class="ccewd-desc">' +desc_content+ '</p>';
				if (address.ccewd_wallet_address_meta!=="") {
					coin_tabs += '<div class="ccewd_tag"><span class="ccewd_tag_heading">Tag/Note:- </span>' +address.ccewd_wallet_address_meta+'</div>';
				}
				coin_tabs += ' <input type="text" class="wallet-address-input"  id="' +address.ccewd_coin_list+'-wallet-address-'+random+'" name="' +address.ccewd_coin_list+'-wallet-address" value="' +address.ccewd_wallet_address+ '">';

				coin_tabs += '<button class="ccewd_btn" data-clipboard-target="#' +address.ccewd_coin_list+'-wallet-address-'+random+'">COPY</button></div>';
			} else if (address.ccewd_coin_list == 'metamask' && address.ccewd_wallet_address!=="") {			
				coin_tabs += '<div class="cdb-metamask-wrapper" ><h2 class="ccewd-title">' +cdb_metamask_title+'</h2><p class="ccewd-desc">' +metamask_description+'</p>';
				coin_tabs += '<div class="tip-button" data-metamask-address="' +metamask_wall_add+'" data-metamask-amount="'+metamask_price +'"></div>';
				if (address.ccewd_wallet_address_meta!=="") {
					coin_tabs += '<div class="ccewd_tag"><span class="ccewd_tag_heading">Tag/Note:- </span>' +address.ccewd_wallet_address_meta+'</div>';
				}
				coin_tabs += '<div class="message"></div></div>';
			} else {

				if (address.ccewd_coin_list == 'select') {
					coin_tabs += '<div class="message">Please select coins</div>';

				} else {
					coin_tabs += '<div class="message">Please enter wallet address</div>';
				}

			}
			coin_tabs += '</div>';
		}

		i++;

	})

	if (design_type == 'list') {
		output += '<div class="ccewd-classic-container">';
		output += '<ul class="ccewd-classic-list">';
		output += classic_list;
		output += '</ul></div>';

	} else {
		output += '<div class="ccewd-container" id="ccewd-random-' + random +'">';
		output += '<ul class="ccewd-tabs" id="ccewd-coin-list">' +coin_links +'</ul>';
		output += coin_tabs;
		output += '</div>';

	}
} else {
	output += '<h6>Please Add coin wallet address in plugin settings panel</h6>';
}

print(output);

#>
<?php
