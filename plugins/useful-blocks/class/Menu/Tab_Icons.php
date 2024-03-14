<?php
namespace Ponhiro_Blocks\Menu;
use \Ponhiro_Blocks\Admin_Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Icons {

	/**
	 * カラーパレットの設定
	 */
	public static function iconbox( $page_name ) {
		$section_name = 'pb_section_color_set';

		// セクションの追加
		add_settings_section(
			$section_name,
			__( 'Icon image for "Icon box" block', 'useful-blocks' ),
			'',
			$page_name
		);

		add_settings_field(
			'pb_icon_set', //フィールドID。何にも使わない
			'', // th
			['\Ponhiro_Blocks\Menu\Tab_Icons', 'callback_for_icons'],
			$page_name,
			$section_name,
			[
				'keys' => [
					'01',
					'02',
					'03',
					'04',
				]
			]
		);
	}


	/**
	 * カラーパレット設定用の専用コールバック
	 */
	public static function callback_for_icons( $args ) {

		$keys = $args['keys'];

		// 使用するデータベース
		$db = \Ponhiro_Blocks::DB_NAME['settings'];

		foreach ($keys as $num) :
			$key = 'iconbox_img_'. $num;
			$src = \Ponhiro_Blocks::get_settings( $key );
			?>
				<div class="__field_title">
					画像<?=$num?>
				</div>
				<div class="__field -media -pb-icon">
					<div class="__items">
						<div class="__item">
							<?php Admin_Menu::mediabtn( $key, $src, $db ); ?>
						</div>
					</div>
				</div>
			<?php
		endforeach;
	}

}

