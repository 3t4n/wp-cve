<?php

namespace ZPOS\Model;

use ZPOS\Plugin;
use const ZPOS\PLUGIN_VERSION;
use const ZPOS\TEXTDOMAIN;

class VatControl
{
	public static function enqueue_assets(): void
	{
		wp_enqueue_style(
			'zpos_tax_vat_control',
			Plugin::getUrl('assets/woocommerce/style/vat-control.min.css'),
			[],
			PLUGIN_VERSION
		);
		wp_enqueue_script(
			'zpos_tax_vat_control',
			Plugin::getUrl('assets/woocommerce/script/vat-control.min.js'),
			['jquery', 'select2'],
			PLUGIN_VERSION,
			true
		);
		wp_localize_script('zpos_tax_vat_control', 'zpos_tax_vat_control', [
			'types' => array_merge(
				['' => ['title' => '', 'code' => __('Select...', TEXTDOMAIN), 'iso' => '']],
				self::get_types()
			),
			'flagsPath' => self::get_flags_path(),
		]);
	}

	public static function enqueue_admin_assets(): void
	{
		self::enqueue_assets();
		wp_enqueue_style(
			'zpos_tax_vat_admin',
			Plugin::getUrl('assets/woocommerce/style/vat-admin.min.css'),
			[],
			PLUGIN_VERSION
		);
		wp_enqueue_script(
			'zpos_tax_vat_admin',
			Plugin::getUrl('assets/woocommerce/script/vat-admin.min.js'),
			['jquery'],
			PLUGIN_VERSION,
			true
		);
	}

	public static function get_flags_path(): string
	{
		return Plugin::getUrl('assets/woocommerce/image/flags/', true);
	}

	public static function render(
		string $type_key,
		?string $type,
		string $id_key,
		?string $id,
		bool $is_required = false
	): void {
		?>
		<span class="zpos-vat-control">
			<?php if (!$is_required) { ?>
					<input class="zpos-vat-control__clear" type="hidden" name="<?php echo esc_attr(
     	$type_key
     ); ?>_clear" value="0">
			<?php } ?>
			<span class="zpos-vat-control__col zpos-vat-control__col_type">
					<select
							class="zpos-vat-control__type"
							name="<?php echo esc_attr($type_key); ?>"
							data-vat-type-value="<?php echo esc_attr($type); ?>"
					></select>
			</span>
			<span class="zpos-vat-control__col">
					<input
							class="zpos-vat-control__input"
							type="text"
							name="<?php echo esc_attr($id_key); ?>"
							value="<?php echo esc_attr($id); ?>"
					>
			</span>
	</span>
	<?php
	}

	public static function get_label(): string
	{
		return __('Tax/VAT ID Type', TEXTDOMAIN);
	}

	public static function get_types(): array
	{
		return [
			'au_1' => ['title' => __('Australia', 'woocommerce'), 'code' => 'AU ABN', 'iso' => 'au'],
			'au_2' => ['title' => __('Australia', 'woocommerce'), 'code' => 'AU ARN', 'iso' => 'au'],
			'at_1' => ['title' => __('Austria', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'at'],
			'be_1' => ['title' => __('Belgium', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'be'],
			'br_1' => ['title' => __('Brazil', 'woocommerce'), 'code' => 'BR CNPJ', 'iso' => 'br'],
			'br_2' => ['title' => __('Brazil', 'woocommerce'), 'code' => 'BR CPF', 'iso' => 'br'],
			'bg_1' => ['title' => __('Bulgaria', 'woocommerce'), 'code' => 'BG UIC', 'iso' => 'bg'],
			'bg_2' => ['title' => __('Bulgaria', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'bg'],
			'ca_1' => ['title' => __('Canada', 'woocommerce'), 'code' => 'CA BN', 'iso' => 'ca'],
			'ca_2' => ['title' => __('Canada', 'woocommerce'), 'code' => 'CA GST HST', 'iso' => 'ca'],
			'ca_3' => ['title' => __('Canada', 'woocommerce'), 'code' => 'CA PST BC', 'iso' => 'ca'],
			'ca_4' => ['title' => __('Canada', 'woocommerce'), 'code' => 'CA PST MB', 'iso' => 'ca'],
			'ca_5' => ['title' => __('Canada', 'woocommerce'), 'code' => 'CA PST SK', 'iso' => 'ca'],
			'ca_6' => ['title' => __('Canada', 'woocommerce'), 'code' => 'CA QST', 'iso' => 'ca'],
			'cl_1' => ['title' => __('Chile', 'woocommerce'), 'code' => 'CL TIN', 'iso' => 'cl'],
			'hr_1' => ['title' => __('Croatia', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'hr'],
			'cy_1' => ['title' => __('Cyprus', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'cy'],
			'cz_1' => ['title' => __('Czech Republic', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'cz'],
			'dk_1' => ['title' => __('Denmark', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'dk'],
			'eg_1' => ['title' => __('Egypt', 'woocommerce'), 'code' => 'EG TIN', 'iso' => 'eg'],
			'ee_1' => ['title' => __('Estonia', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'ee'],
			'eu_1' => ['title' => __('EU', 'woocommerce'), 'code' => 'EU OSS VAT', 'iso' => 'eu'],
			'fi_1' => ['title' => __('Finland', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'fi'],
			'fr_1' => ['title' => __('France', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'fr'],
			'ge_1' => ['title' => __('Georgia', 'woocommerce'), 'code' => 'GE VAT', 'iso' => 'ge'],
			'de_1' => ['title' => __('Germany', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'de'],
			'gr_1' => ['title' => __('Greece', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'gr'],
			'hk_1' => ['title' => __('Hong Kong', 'woocommerce'), 'code' => 'HK BR', 'iso' => 'hk'],
			'hu_1' => ['title' => __('Hungary', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'hu'],
			'hu_2' => ['title' => __('Hungary', 'woocommerce'), 'code' => 'HU TIN', 'iso' => 'hu'],
			'is_1' => ['title' => __('Iceland', 'woocommerce'), 'code' => 'IS VAT', 'iso' => 'is'],
			'in_1' => ['title' => __('India', 'woocommerce'), 'code' => 'IN GST', 'iso' => 'in'],
			'id_1' => ['title' => __('Indonesia', 'woocommerce'), 'code' => 'ID NPWP', 'iso' => 'in'],
			'ie_1' => ['title' => __('Ireland', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'ie'],
			'il_1' => ['title' => __('Israel', 'woocommerce'), 'code' => 'IL VAT', 'iso' => 'il'],
			'it_1' => ['title' => __('Italy', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'it'],
			'jp_1' => ['title' => __('Japan', 'woocommerce'), 'code' => 'JP CN', 'iso' => 'jp'],
			'jp_2' => ['title' => __('Japan', 'woocommerce'), 'code' => 'JP RN', 'iso' => 'jp'],
			'jp_3' => ['title' => __('Japan', 'woocommerce'), 'code' => 'JP TRN', 'iso' => 'jp'],
			'ke_1' => ['title' => __('Kenya', 'woocommerce'), 'code' => 'KE PIN', 'iso' => 'ke'],
			'lv_1' => ['title' => __('Latvia', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'lv'],
			'li_1' => ['title' => __('Liechtenstein', 'woocommerce'), 'code' => 'LI UID', 'iso' => 'li'],
			'lt_1' => ['title' => __('Lithuania', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'lt'],
			'lu_1' => ['title' => __('Luxembourg', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'lu'],
			'my_1' => ['title' => __('Malaysia', 'woocommerce'), 'code' => 'MY FRP', 'iso' => 'my'],
			'my_2' => ['title' => __('Malaysia', 'woocommerce'), 'code' => 'MY ITN', 'iso' => 'my'],
			'my_3' => ['title' => __('Malaysia', 'woocommerce'), 'code' => 'MY SST', 'iso' => 'my'],
			'mt_1' => ['title' => __('Malta', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'mt'],
			'mx_1' => ['title' => __('Mexico', 'woocommerce'), 'code' => 'MX RFC', 'iso' => 'mx'],
			'nl_1' => ['title' => __('Netherlands', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'nl'],
			'nz_1' => ['title' => __('New Zealand', 'woocommerce'), 'code' => 'NZ GST', 'iso' => 'nz'],
			'no_1' => ['title' => __('Norway', 'woocommerce'), 'code' => 'NO VAT', 'iso' => 'no'],
			'ph_1' => ['title' => __('Philippines', 'woocommerce'), 'code' => 'PH TIN', 'iso' => 'ph'],
			'pl_1' => ['title' => __('Poland', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'pl'],
			'pt_1' => ['title' => __('Portugal', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'pt'],
			'ro_1' => ['title' => __('Romania', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'ro'],
			'ru_1' => ['title' => __('Russia', 'woocommerce'), 'code' => 'RU INN', 'iso' => 'ru'],
			'ru_2' => ['title' => __('Russia', 'woocommerce'), 'code' => 'RU KPP', 'iso' => 'ru'],
			'sa_1' => ['title' => __('Saudi Arabia', 'woocommerce'), 'code' => 'SA VAT', 'iso' => 'sa'],
			'sg_1' => ['title' => __('Singapore', 'woocommerce'), 'code' => 'SG GST', 'iso' => 'sg'],
			'sg_2' => ['title' => __('Singapore', 'woocommerce'), 'code' => 'SG UEN', 'iso' => 'sg'],
			'sk_1' => ['title' => __('Slovakia', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'sk'],
			'si_1' => ['title' => __('Slovenia', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'si'],
			'si_2' => ['title' => __('Slovenia', 'woocommerce'), 'code' => 'SI TIN', 'iso' => 'si'],
			'za_1' => ['title' => __('South Africa', 'woocommerce'), 'code' => 'ZA VAT', 'iso' => 'za'],
			'kr_1' => ['title' => __('South Korea', 'woocommerce'), 'code' => 'KR BRN', 'iso' => 'kr'],
			'es_1' => ['title' => __('Spain', 'woocommerce'), 'code' => 'ES CIF', 'iso' => 'es'],
			'es_2' => ['title' => __('Spain', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'es'],
			'se_1' => ['title' => __('Sweden', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'se'],
			'ch_1' => ['title' => __('Switzerland', 'woocommerce'), 'code' => 'CH VAT', 'iso' => 'ch'],
			'tw_1' => ['title' => __('Taiwan', 'woocommerce'), 'code' => 'TW VAT', 'iso' => 'tw'],
			'th_1' => ['title' => __('Thailand', 'woocommerce'), 'code' => 'TH VAT', 'iso' => 'th'],
			'tr_1' => ['title' => __('Turkey', 'woocommerce'), 'code' => 'TR TIN', 'iso' => 'tr'],
			'ua_1' => ['title' => __('Ukraine', 'woocommerce'), 'code' => 'UA VAT', 'iso' => 'ua'],
			'ae_1' => [
				'title' => __('United Arab Emirates', 'woocommerce'),
				'code' => 'AE TRN',
				'iso' => 'ae',
			],
			'gb_1' => ['title' => __('United Kingdom', 'woocommerce'), 'code' => 'EU VAT', 'iso' => 'gb'],
			'gb_2' => ['title' => __('United Kingdom', 'woocommerce'), 'code' => 'GB VAT', 'iso' => 'gb'],
			'us_1' => ['title' => __('United States', 'woocommerce'), 'code' => 'US EIN', 'iso' => 'us'],
		];
	}
}
