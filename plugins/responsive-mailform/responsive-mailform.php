<?php

/*
Plugin Name: Responsive Mailform
Description: WordPress用のレスポンシブ メールフォームです。
Version: 1.4
Plugin URI: https://wordpress.org/plugins/responsive-mailform/
Author: FIRSTSTEP
Author URI: http://www.1-firststep.com/
License: GPLv2
Text Domain: responsive-mailform
Domain Path: /languages
*/


/*
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/




class Responsive_Mailform {
	
	public function __construct() {
		
		
		
		add_action( 'wp_enqueue_scripts', array( $this, 'mailform_style' ) );
		
		
		if( get_option( 'furigana_library' ) == '1' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'mailform_furigana_script' ) );
		}
		
		
		if( get_option( 'postal_library' ) == '1' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'mailform_postal_script' ) );
		}
		
		
		if( get_option( 'calendar_library' ) == '1' ){
			add_action( 'wp_enqueue_scripts', array( $this, 'mailform_calendar_script' ) );
		}
		
		
		add_action( 'wp_enqueue_scripts', array( $this, 'mailform_script' ) );
		
		
		
		
		
		
		register_activation_hook( __FILE__, array( $this, 'mailform_activate_setting' ) );
		
		
		register_deactivation_hook( __FILE__, array( $this, 'mailform_deactivate_setting' ) );
		
		
		if( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'mailform_menu' ) );
		}
		
		
	}
	
	
	
	
	public function mailform_style() {
		wp_enqueue_style(
			'Responsive_Mailform',
			plugins_url( 'css/responsive-mailform.css', __FILE__ ),
			array(),
			'1.0.0',
			'all'
		);
	}
	
	
	
	
	public function mailform_furigana_script() {
		wp_enqueue_script(
			'jquery.autoKana.js',
			plugins_url( 'js/jquery.autoKana.js', __FILE__ ),
			array( 'jquery' ),
			'1.0.0',
			true
		);
	}
	
	
	
	
	public function mailform_postal_script() {
		wp_enqueue_script(
			'ajaxzip3.js',
			plugins_url( 'js/ajaxzip3.js', __FILE__ ),
			array( 'jquery' ),
			'1.0.0',
			true
		);
	}
	
	
	
	
	public function mailform_calendar_script() {
	
		wp_enqueue_style(
			'jquery.datetimepicker.css',
			plugins_url( 'css/jquery.datetimepicker.css', __FILE__ ),
			array(),
			'1.0.0',
			'all'
		);
		
		wp_enqueue_script(
			'jquery.datetimepicker.js',
			plugins_url( 'js/jquery.datetimepicker.js', __FILE__ ),
			array( 'jquery' ),
			'1.0.0',
			true
		);
		
	}
	
	
	
	
	public function mailform_script() {
		wp_enqueue_script(
			'responsive-mailform.js',
			plugins_url( 'js/responsive-mailform.js', __FILE__ ),
			array( 'jquery' ),
			'1.0.0',
			true
		);
	}
	
	
	
	
	public function mailform_activate_setting() {
		$tmp_send_body = ''.PHP_EOL;
		$tmp_send_body .= 'メールフォームからお問い合わせがありました。'.PHP_EOL;
		$tmp_send_body .= 'お問い合わせの内容は以下の通りです。'.PHP_EOL;
		$tmp_send_body .= ''.PHP_EOL;
		$tmp_send_body .= '--------------------------------------------------------------------------'.PHP_EOL;
		$tmp_send_body .= ''.PHP_EOL;
		$tmp_send_body .= '送信時刻：%1$d年%2$d月%3$d日　%4$d時%5$d分%6$d秒'.PHP_EOL;
		$tmp_send_body .= '名前：%7$s　%8$s'.PHP_EOL;
		$tmp_send_body .= 'ふりがな：%9$s　%10$s'.PHP_EOL;
		$tmp_send_body .= 'メールアドレス：%11$s'.PHP_EOL;
		$tmp_send_body .= '性別：%12$s'.PHP_EOL;
		$tmp_send_body .= '郵便番号：%13$s'.PHP_EOL;
		$tmp_send_body .= '住所：%14$s　%15$s'.PHP_EOL;
		$tmp_send_body .= '電話番号：%16$s'.PHP_EOL;
		$tmp_send_body .= 'ご希望の日時：%17$s'.PHP_EOL;
		$tmp_send_body .= 'ご希望の商品：%18$s'.PHP_EOL;
		$tmp_send_body .= 'お問い合わせの種類：%19$s'.PHP_EOL;
		$tmp_send_body .= 'お問い合わせの内容：'.PHP_EOL;
		$tmp_send_body .= '%20$s'.PHP_EOL;
		$tmp_send_body .= ''.PHP_EOL;
		$tmp_send_body .= '--------------------------------------------------------------------------'.PHP_EOL;
		$tmp_send_body .= ''.PHP_EOL;
		$tmp_send_body .= '送信者のIPアドレス：%21$s'.PHP_EOL;
		$tmp_send_body .= '送信者のホスト名：%22$s'.PHP_EOL;
		$tmp_send_body .= '送信者のブラウザ：%23$s'.PHP_EOL;
		$tmp_send_body .= ''.PHP_EOL;
		$tmp_send_body .= '送信前の入力チェック：%24$s'.PHP_EOL;
		$tmp_send_body .= 'メールフォームのURL：%25$s'.PHP_EOL;
		$tmp_send_body .= 'メールフォームに付く前のURL：%26$s'.PHP_EOL;
		$tmp_send_body .= ''.PHP_EOL;
		
		
		$tmp_thanks_body = ''.PHP_EOL;
		$tmp_thanks_body .= 'この度はお問い合わせをいただき、ありがとうございました。'.PHP_EOL;
		$tmp_thanks_body .= ''.PHP_EOL;
		$tmp_thanks_body .= '折り返し担当者から返信が行きますので、しばらくお待ちください。'.PHP_EOL;
		$tmp_thanks_body .= '以下の内容でお問い合わせをお受けいたしました。'.PHP_EOL;
		$tmp_thanks_body .= ''.PHP_EOL;
		$tmp_thanks_body .= '--------------------------------------------------------------------------'.PHP_EOL;
		$tmp_thanks_body .= ''.PHP_EOL;
		$tmp_thanks_body .= '送信時刻：%1$d年%2$d月%3$d日　%4$d時%5$d分%6$d秒'.PHP_EOL;
		$tmp_thanks_body .= '名前：%7$s　%8$s'.PHP_EOL;
		$tmp_thanks_body .= 'ふりがな：%9$s　%10$s'.PHP_EOL;
		$tmp_thanks_body .= 'メールアドレス：%11$s'.PHP_EOL;
		$tmp_thanks_body .= '性別：%12$s'.PHP_EOL;
		$tmp_thanks_body .= '郵便番号：%13$s'.PHP_EOL;
		$tmp_thanks_body .= '住所：%14$s　%15$s'.PHP_EOL;
		$tmp_thanks_body .= '電話番号：%16$s'.PHP_EOL;
		$tmp_thanks_body .= 'ご希望の日時：%17$s'.PHP_EOL;
		$tmp_thanks_body .= 'ご希望の商品：%18$s'.PHP_EOL;
		$tmp_thanks_body .= 'お問い合わせの種類：%19$s'.PHP_EOL;
		$tmp_thanks_body .= 'お問い合わせの内容：'.PHP_EOL;
		$tmp_thanks_body .= '%20$s'.PHP_EOL;
		$tmp_thanks_body .= ''.PHP_EOL;
		$tmp_thanks_body .= '--------------------------------------------------------------------------'.PHP_EOL;
		$tmp_thanks_body .= ''.PHP_EOL;
		$tmp_thanks_body .= 'この度はお問い合わせを頂き、重ねてお礼申し上げます。'.PHP_EOL;
		$tmp_thanks_body .= '──────────────────────────────────────'.PHP_EOL;
		$tmp_thanks_body .= ''.PHP_EOL;
		$tmp_thanks_body .= '　　レスポンシブメールフォーム'.PHP_EOL;
		$tmp_thanks_body .= '　　〒***-**** ここに住所など'.PHP_EOL;
		$tmp_thanks_body .= '　　TEL : ***-***-****'.PHP_EOL;
		$tmp_thanks_body .= '　　Web Site URL : http://www.1-firststep.com/'.PHP_EOL;
		$tmp_thanks_body .= '　　Blog URL : http://www.firstsync.net/'.PHP_EOL;
		$tmp_thanks_body .= '──────────────────────────────────────'.PHP_EOL;
		$tmp_thanks_body .= ''.PHP_EOL;
		
		
		add_option( 'send_address' , 'aaa@example.com' );
		add_option( 'send_name' , 'レスポンシブメール　差出人' );
		add_option( 'thanks_page_url' , 'http://www.1-firststep.com/' );
		add_option( 'reply_mail' , '1' );
		add_option( 'send_body' , $tmp_send_body );
		add_option( 'thanks_body' , $tmp_thanks_body );
		add_option( 'spam_check' , '0' );
		add_option( 'domain_name' , '' );
		add_option( 'javascript_check' , '1' );
		add_option( 'furigana_library' , '1' );
		add_option( 'postal_library' , '1' );
		add_option( 'calendar_library' , '1' );
		add_option( 'min-time' , '10:00' );
		add_option( 'max-time' , '19:00' );
	}
	
	
	
	
	public function mailform_deactivate_setting() {
		delete_option( 'send_address' );
		delete_option( 'send_name' );
		delete_option( 'thanks_page_url' );
		delete_option( 'reply_mail' );
		delete_option( 'send_body' );
		delete_option( 'thanks_body' );
		delete_option( 'spam_check' );
		delete_option( 'domain_name' );
		delete_option( 'javascript_check' );
		delete_option( 'furigana_library' );
		delete_option( 'postal_library' );
		delete_option( 'calendar_library' );
		delete_option( 'min-time' );
		delete_option( 'max-time' );
	}
	
	
	
	
	public function mailform_menu() {
		
		add_options_page(
			'Responsive Mailform',
			'Responsive Mailform',
			'administrator',
			'responsive-mailform',
			array( $this, 'responsive_mailform' )
		);
		
		
		add_action( 'admin_enqueue_scripts', array( $this, 'mailform_admin_script' ) );
		
		
		add_action( 'admin_init', array( $this, 'mailform_register_setting' ) );
		
	}
	
	
	
	
	public function mailform_admin_script( $hook ) {
		if( strpos( $hook, 'responsive-mailform' ) != false ) {
			
			wp_enqueue_style(
				'jquery.datetimepicker.css',
				plugins_url( 'css/jquery.datetimepicker.css', __FILE__ ),
				array(),
				'1.0.0',
				'all'
			);
			
			wp_enqueue_script(
				'jquery.datetimepicker.js',
				plugins_url( 'js/jquery.datetimepicker.js', __FILE__ ),
				array( 'jquery' ),
				'1.0.0',
				true
			);
			
			wp_enqueue_script(
				'responsive-mailform-admin.js',
				plugins_url( 'js/responsive-mailform-admin.js', __FILE__ ),
				array( 'jquery' ),
				'1.0.0',
				true
			);
			
		}
	}
	
	
	
	
	public function mailform_register_setting() {
		register_setting( 'mailform-option-group', 'send_address' );
		register_setting( 'mailform-option-group', 'send_name' );
		register_setting( 'mailform-option-group', 'thanks_page_url' );
		register_setting( 'mailform-option-group', 'reply_mail' );
		register_setting( 'mailform-option-group', 'send_body' );
		register_setting( 'mailform-option-group', 'thanks_body' );
		register_setting( 'mailform-option-group', 'spam_check' );
		register_setting( 'mailform-option-group', 'domain_name' );
		register_setting( 'mailform-option-group', 'javascript_check' );
		register_setting( 'mailform-option-group', 'furigana_library' );
		register_setting( 'mailform-option-group', 'postal_library' );
		register_setting( 'mailform-option-group', 'calendar_library' );
		register_setting( 'mailform-option-group', 'min-time' );
		register_setting( 'mailform-option-group', 'max-time' );
	}
	
	
	
	
	public function responsive_mailform() {
?>
<div class="wrap">
	<h2>Responsive Mailform Setting</h2>
	<form action="options.php" method="post">
		
		<?php settings_fields( 'mailform-option-group' ); ?>
		<?php do_settings_sections( 'mailform-option-group' ); ?>
		
		<table class="form-table">
			<tr>
				<th>
					<span style="color : #ffffff; background : #ff3535; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">必須</span>
					自分のメールアドレス
				</th>
				<td><input type="text" name="send_address" value="<?php echo esc_attr( get_option( 'send_address' ) ); ?>" /></td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #ff3535; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">必須</span>
					メールの差出人名に表示される自分の名前
				</th>
				<td><input type="text" name="send_name" value="<?php echo esc_attr( get_option( 'send_name' ) ); ?>" /></td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #ff3535; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">必須</span>
					サンクスページのURL
				</th>
				<td>
					<input type="text" name="thanks_page_url" value="<?php echo esc_attr( get_option( 'thanks_page_url' ) ); ?>" />
					<p class="description">(http://からの絶対パス)</p>
				</td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					相手に自動返信メールを送るかどうか
				</th>
				<td>
					<input type="checkbox" name="reply_mail" value="1" <?php checked( get_option( 'reply_mail' ), 1 ); ?> />
				</td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					自分に届くメールの内容
				</th>
				<td><textarea name="send_body" cols="80" rows="20"><?php echo esc_textarea( get_option( 'send_body' ) ); ?></textarea></td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #ff3535; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">必須</span>
					相手に届く自動返信メールの内容
				</th>
				<td><textarea name="thanks_body" cols="80" rows="20"><?php echo esc_textarea( get_option( 'thanks_body' ) ); ?></textarea></td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					(スパム対策) ドメインチェック機能</th>
				<td>
					<input type="checkbox" name="spam_check" value="1" <?php checked( get_option( 'spam_check' ), 1 ); ?> />
					<p class="description">この設定をオンにする場合は以下の入力欄でドメインを設定してください。</p>
				</td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					(スパム対策) メールフォームを設置するサイトのドメイン
				</th>
				<td><input type="text" name="domain_name" value="<?php echo esc_attr( get_option( 'domain_name' ) ); ?>" /></td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					(スパム対策) 送信前の入力チェックが動作したときだけメールを受け付ける機能
				</th>
				<td>
					<input type="checkbox" name="javascript_check" value="1" <?php checked( get_option( 'javascript_check' ), 1 ); ?> />
				</td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					ふりがな自動入力機能を使うかどうか
				</th>
				<td>
					<input type="checkbox" name="furigana_library" value="1" <?php checked( get_option( 'furigana_library' ), 1 ); ?> />
				</td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					郵便番号から住所自動入力機能を使うかどうか
				</th>
				<td>
					<input type="checkbox" name="postal_library" value="1" <?php checked( get_option( 'postal_library' ), 1 ); ?> />
				</td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					日時選択のカレンダー補助機能を使うかどうか
				</th>
				<td>
					<input type="checkbox" name="calendar_library" value="1" <?php checked( get_option( 'calendar_library' ), 1 ); ?> />
					<p class="description">この設定をオンにする場合は以下の入力欄で選択できる時刻の範囲を設定してください。</p>
				</td>
			</tr>
			
			<tr>
				<th>
					<span style="color : #ffffff; background : #3535ff; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">任意</span>
					日時選択のカレンダーで選択できる時刻の範囲
				</th>
				<td>
					<input type="text" id="admin-day-1" name="min-time" value="<?php echo esc_attr( get_option( 'min-time' ) ); ?>" /> ～ <input type="text" id="admin-day-2" name="max-time" value="<?php echo esc_attr( get_option( 'max-time' ) ); ?>" />
					<p class="description">時刻の範囲を入力した後は一度設定を保存してから下にあるメールフォームのコードをコピーしてください。(設定を保存しないと時刻の範囲がコードに反映されません)</p>
				</td>
			</tr>
			
			<tr>
				<th><span style="color : #ffffff; background : #ff3535; display : inline-block; padding : 3px 5px; font-size : 85%; border-radius : 3px; margin-right : 5px;">必須</span>メールフォームのコード</th>
				<td>
					<p class="description">メールフォームを設置したい投稿ページや固定ページに以下のコードをすべてコピーして貼り付けてください。</p>
					<pre id="mailform-code" style="width : 85%; height : 450px; overflow : scroll; border : 1px solid #dddddd; background : #ffffff; padding : 10px; line-height : 1.6; word-wrap:break-word; white-space : pre-wrap;">
<code style="background : #ffffff;">&lt;form action="<?php echo esc_attr( plugins_url().'/responsive-mailform/mailform.php' ); ?>" method="post" id="mail_form"&gt;
	&lt;dl&gt;
		&lt;dt&gt;名前&lt;span&gt;Your Name&lt;/span&gt;&lt;/dt&gt;
		&lt;dd class="required"&gt;&lt;input type="text" id="name_1" name="name_1" value="" /&gt; &lt;input type="text" id="name_2" name="name_2" value="" /&gt;&lt;/dd&gt;
		
		&lt;dt&gt;ふりがな&lt;span&gt;Name Reading&lt;/span&gt;&lt;/dt&gt;
		&lt;dd&gt;&lt;input type="text" id="read_1" name="read_1" value="" /&gt; &lt;input type="text" id="read_2" name="read_2" value="" /&gt;&lt;/dd&gt;
		
		&lt;dt&gt;メールアドレス&lt;span&gt;Mail Address&lt;/span&gt;&lt;/dt&gt;
		&lt;dd class="required"&gt;&lt;input type="text" id="mail_address" name="mail_address" value="" /&gt;&lt;/dd&gt;
		
		&lt;dt&gt;メールアドレス(確認)&lt;span&gt;Mail Address Confirm&lt;/span&gt;&lt;/dt&gt;
		&lt;dd class="required"&gt;&lt;input type="text" id="mail_address_confirm" name="mail_address_confirm" value="" /&gt;&lt;/dd&gt;
		
		&lt;dt&gt;性別&lt;span&gt;Gender&lt;/span&gt;&lt;/dt&gt;
		&lt;dd&gt;
			&lt;ul&gt;
				&lt;li&gt;&lt;label&gt;&lt;input type="radio" id="gender_1" name="gender" value="男性" /&gt;男性&lt;/label&gt;&lt;/li&gt;
				&lt;li&gt;&lt;label&gt;&lt;input type="radio" id="gender_2" name="gender" value="女性" /&gt;女性&lt;/label&gt;&lt;/li&gt;
			&lt;/ul&gt;
		&lt;/dd&gt;
		
		&lt;dt&gt;郵便番号&lt;span&gt;Postal&lt;/span&gt;&lt;/dt&gt;
		&lt;dd&gt;&lt;input type="text" id="postal" name="postal" value="" onkeyup="AjaxZip3.zip2addr( this, '', 'address_1', 'address_1' );" /&gt;　&lt;a href="http://www.post.japanpost.jp/zipcode/" target="_blank"&gt;郵便番号検索&lt;/a&gt;&lt;/dd&gt;
		
		&lt;dt&gt;住所&lt;span&gt;Address&lt;/span&gt;&lt;/dt&gt;
		&lt;dd&gt;&lt;input type="text" id="address_1" name="address_1" value="" /&gt;&lt;input type="text" id="address_2" name="address_2" value="" /&gt;&lt;/dd&gt;
		
		&lt;dt&gt;電話番号&lt;span&gt;Phone Number&lt;/span&gt;&lt;/dt&gt;
		&lt;dd&gt;&lt;input type="text" id="phone" name="phone" value="" /&gt;&lt;/dd&gt;
		
		&lt;dt&gt;ご希望の日時&lt;span&gt;Schedule&lt;/span&gt;&lt;/dt&gt;
		&lt;dd&gt;&lt;input type="text" id="schedule" name="schedule" value="" data-min="<?php echo esc_attr( get_option( 'min-time' ) ); ?>" data-max="<?php echo esc_attr( get_option( 'max-time' ) ); ?>" readonly="readonly" /&gt;&lt;/dd&gt;
		
		&lt;dt&gt;ご希望の商品&lt;span&gt;Product&lt;/span&gt;&lt;/dt&gt;
		&lt;dd&gt;
			&lt;select id="product" name="product"&gt;
				&lt;option value=""&gt;選択してください&lt;/option&gt;
				&lt;option value="iPhone4"&gt;iPhone4&lt;/option&gt;
				&lt;option value="iPhone4s"&gt;iPhone4s&lt;/option&gt;
				&lt;option value="iPhone5"&gt;iPhone5&lt;/option&gt;
				&lt;option value="iPhone5s"&gt;iPhone5s&lt;/option&gt;
				&lt;option value="iPhone6"&gt;iPhone6&lt;/option&gt;
				&lt;option value="iPhone6 Plus"&gt;iPhone6 Plus&lt;/option&gt;
			&lt;/select&gt;
		&lt;/dd&gt;
		
		&lt;dt&gt;お問い合わせの種類&lt;span&gt;Inquiry Kind&lt;/span&gt;&lt;/dt&gt;
		&lt;dd class="required"&gt;
			&lt;ul&gt;
				&lt;li&gt;&lt;label&gt;&lt;input type="checkbox" id="kind_1" name="kind[]" value="WEBサイトについて" /&gt;WEBサイトについて&lt;/label&gt;&lt;/li&gt;
				&lt;li&gt;&lt;label&gt;&lt;input type="checkbox" id="kind_2" name="kind[]" value="商品について" /&gt;商品について&lt;/label&gt;&lt;/li&gt;
				&lt;li&gt;&lt;label&gt;&lt;input type="checkbox" id="kind_3" name="kind[]" value="キャンペーンについて" /&gt;キャンペーンについて&lt;/label&gt;&lt;/li&gt;
				&lt;li&gt;&lt;label&gt;&lt;input type="checkbox" id="kind_4" name="kind[]" value="採用について" /&gt;採用について&lt;/label&gt;&lt;/li&gt;
				&lt;li&gt;&lt;label&gt;&lt;input type="checkbox" id="kind_5" name="kind[]" value="ご意見・ご要望" /&gt;ご意見・ご要望&lt;/label&gt;&lt;/li&gt;
				&lt;li&gt;&lt;label&gt;&lt;input type="checkbox" id="kind_6" name="kind[]" value="交際希望" /&gt;交際希望&lt;/label&gt;&lt;/li&gt;
			&lt;/ul&gt;
		&lt;/dd&gt;
		
		&lt;dt&gt;お問い合わせの内容&lt;span&gt;Mail Contents&lt;/span&gt;&lt;/dt&gt;
		&lt;dd class="required"&gt;&lt;textarea id="mail_contents" name="mail_contents" cols="40" rows="5"&gt;&lt;/textarea&gt;&lt;/dd&gt;
		
		&lt;dt&gt;送信する&lt;span&gt;Send&lt;/span&gt;&lt;/dt&gt;
		&lt;dd class="required"&gt;&lt;input type="submit" id="mail_submit_button" name="mail_submit_button" value="送信する" /&gt;&lt;/dd&gt;
	&lt;/dl&gt;
&lt;/form&gt;</code>
					</pre>
				</td>
			</tr>
			
		</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
	}
	
}




new Responsive_Mailform();


?>