<?php

// for admin sessions only

if ( !is_admin() ) return;

// class

class ram108_sape_admin extends ram108_sape_plugin {

	function _init(){

		$this->_admin_message();

		add_filter( 'plugin_action_links_' . plugin_basename( _RAM108_SAPE ), array( $this, '_admin_link' ) );
		add_action( 'admin_init', array( $this, '_admin_init' ) );
		add_action( 'admin_menu', array( $this, '_admin_menu' ) );
	}

	function _admin_page() {
		?>
		<div class="wrap">

			<?php screen_icon(); ?><h2>[ram108] SAPE Links</h2>

			<div style="width: 65%; float: left;">

				<form method="post" action="options.php">

					<?php settings_fields( $this->id ); ?><?php do_settings_sections( $this->id ); ?>

					<input type="hidden" name="<?php echo $this->id?>[ver]" value="<?php echo $this->settings->ver?>" />

					<table class="form-table">

						<tr valign="top"><th scope="row">Идентификатор _SAPE_USER</th><td>
							<input class="regular-text" type="text" name="<?php echo $this->id?>[user]" value="<?php echo $this->settings->user; ?>" />
							<br/><small><a href="http://www.ram108.ru/post/78" title="Перейти на страницу помощи" target="_blank">где взять идентификатор</a></small>
						</td></tr>

						<tr valign="top"><th scope="row">Контекстные ссылки</th><td>
							<fieldset>
							<label>
								<input type="checkbox" name="<?php echo $this->id?>[context]" value="1"<?php checked( $this->settings->context );?> />
								<span title="Размещает контекстные ссылки в тексте сайта.">
									Ссылки в тексте записей и страниц сайта
								</span>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="<?php echo $this->id?>[context_except]" value="1"<?php checked( $this->settings->context_except );?> />
								<span title="Цитаты выводятся на страницах архивов, которые обновляются во время новой публикации. Размещение ссылок на страницах с часто меняющимся содержимом приводит к ошибкам SAPE.">
									Ссылки в цитате <i>(не рекомендуется)</i>
								</span>
							</label>
							</fieldset>
						</td></tr>

						<tr valign="top"><th scope="row">Дополнительные опции</th><td>
							<fieldset>
							<label>
								<input type="checkbox" name="<?php echo $this->id?>[debug]" value="1"<?php checked( $this->settings->debug );?> />
								<span title="Выводит отладочный текст в местах, где должны отображаться ссылки. Смотрите HTML-код страницы.">
									Включить режим отладки вывода ссылок
								</span>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="<?php echo $this->id?>[disable_texturize]" value="1"<?php checked( $this->settings->disable_texturize );?> />
								<span title="Фильтр wptexturize обрабатывает исходный текст сайта, добавляя спецсимволы. В результате некоторые контекстные ссылки могут выпасть в ERROR. Более подробно читайте в FAQ.">
									Отключить wptexturize <i>(не рекомендуется)</i>
								</span>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="<?php echo $this->id?>[disable_css]" value="1"<?php checked( $this->settings->disable_css );?> />
								<span title="Плагин оформляет ссылки таким образом, чтобы визуально они воспринимались как простой текст. Вы можете отключить встроенные стили плагина.">
									Не подключать встроенные CSS стили
								</span>
							</label>
							</fieldset>
						</td></tr>

						<tr valign="top"><th scope="row">Виджет</th><td>
							<a href="<?php echo admin_url('widgets.php');?>">Используйте виджет [ram108] SAPE Links</a> для размещения ссылок в области виджетов сайта.
						</td></tr>

						<tr valign="top"><th scope="row">Управление ссылками</th><td>
							<a href="http://goo.gl/2Iok0K" target="_blank">Перейти в панель управления</a> sape для веб-мастеров.
						</td></tr>

					</table>

					<?php submit_button(); ?>

				</form>

			</div>

			<?php $this->_widget_area(); ?>

		</div>
		<?php
	}

	function _widget_area(){
		?>
		<div style="width: 30%; float: right">

			<h3>Использование плагина</h3>
			<p><a href="http://www.ram108.ru/post/78" target="_blank">Посетите страницу плагина</a> для информации по активации, настройке и использованию плагина.</p>

			<h3>Ждем ваших отзывов</h3>
			<p><a href="http://wordpress.org/support/view/plugin-reviews/ram108-sape" target="_blank">Оцените плагин каталоге Wordpress</a> или напишите обзор на своем сайте.</p>

			<p>Рекомендую VPS хостинг от российской <a href="http://goo.gl/VqSMdg" target="_blank">компании iHor</a> или <a href="http://goo.gl/BYr5qG" target="_blank">DigitalOcean</a>.<p>

		</div>
		<?php
	}

	function _news_widget(){
		?>
		<style type="text/css">
			.news_widget a{
				font-size: 100%;
				line-height: 1.2;
				font-family: inherit;
			}
		</style>
		<h3>Новости плагина</h3>
		<div class="news_widget">
		<?php
			wp_widget_rss_output( array(
				'link' => 'http://www.ram108.ru',
				'url' => 'http://www.ram108.ru/plugins/ram108-sape/feed/',
				'title' => 'Plugin News',
				'items' => 4,
				'show_summary' => 0,
				'show_author' => 0,
				'show_date' => 0
			) );
		?>
		</div>
		<?php
	}

	// ADMIN MESSAGE

	function _admin_message(){

		global $sape;

		add_action( 'admin_notices', array( $this, '_admin_notice') );

		if ( !$this->settings->user ) {
			$this->_error( '<div class="updated"><p><b>[ram108] SAPE Links</b>: Необходима активация плагина. Посетите <a href="'.admin_url('options-general.php?page='.$this->id).'">страницу настроек</a>.</p></div>' );
			return;
		}

		if ( !file_exists( $file = realpath( $_SERVER['DOCUMENT_ROOT'] ).'/'.$this->settings->user.'/sape.php' ) ) {
			$this->_error( '<div class="error"><p><b>[ram108] SAPE Links</b>: Файл не найден <b>'.$file.'</b>. Посетите <a href="'.admin_url('options-general.php?page='.$this->id).'">страницу настроек</a>.</p></div>' );
			return;
		}

		if ( @$sape->_version && $sape->_version < '1.2.7' ) {
			$this->_error( '<div class="updated"><p><b>[ram108] SAPE Links</b>: Необходимо обновить файл <b>'.$file.'</b> до последней версии. <a href="http://www.sape.ru/get_user_files.php">Скачать сейчас</a>.</p></div>' );
			return;
		}

		if ( $this->settings->debug ) {
			$this->_error( '<div class="updated"><p><b>[ram108] SAPE Links</b>: Включен режим отладки вывода ссылок. Посетите <a href="'.admin_url('options-general.php?page='.$this->id).'">страницу настроек</a>.</p></div>' );
			return;
		}
	}

	// OTHER

	function _admin_notice() {
		if ( $this->error ) foreach( $this->error as $message ) echo $message;
	}

	function _admin_link( $links ){
		return array_merge( array('<a href="'.admin_url('options-general.php?page='.$this->id).'">Настройки</a>'), $links );
	}

	function _admin_init(){
		register_setting( $this->id, $this->id );
	}

	function _admin_menu(){
		add_options_page('Настройки [ram108] SAPE Links', '[ram108] SAPE Links', 'manage_options', $this->id, array( $this, '_admin_page' ) );
	}
}

// init class

$ram108_sape_admin = new ram108_sape_admin;
