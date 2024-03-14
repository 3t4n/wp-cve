<div class="wrap">
	<div class="icon32" id="icon-options-general">
		<br>
	</div>
	<h2>RSS Antenna 設定</h2>
	<div class="updated" style="border: 2px solid #1E8CBE !important;border-radius: 3px;">
		<p class='updated'>複数のRSS一覧を表示できる<strong><a href="http://residentbird.main.jp/bizplugin/plugins/rss-antenna-site/" target="_blank">RSS Antenna Siteはこちら</a></strong>です</p>
    </div>
	<h3>ショートコード</h3>
	<p>以下のコードをコピーして、Rss Antennaを表示する固定ページや投稿の本文内に貼り付けてください。</p>
	<p>
		<input type="text" value=<?php echo $shortcode;?> readonly></input>
	</p>
	<form action="options.php" method="post">
		<?php settings_fields( $option_name ); ?>
		<?php do_settings_sections( $file ); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary"
				value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
	</form>
</div>