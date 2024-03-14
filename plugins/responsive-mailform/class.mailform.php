<?php

class Mailform {
	
	private $send_address = '';
	private $send_name = '';
	private $thanks_page_url = '';
	private $spam_check = '';
	private $domain_name = '';
	private $javascript_check = '';
	
	
	private $referer = '';
	private $addr = '';
	private $host = '';
	private $agent = '';
	
	
	private $name_1 = '';
	private $name_2 = '';
	private $read_1 = '';
	private $read_2 = '';
	private $mail_address = '';
	private $mail_address_confirm = '';
	private $mail_address_empty = false;
	private $gender = '';
	private $postal = '';
	private $address_1 = '';
	private $address_2 = '';
	private $phone = '';
	private $schedule = '';
	private $product = '';
	private $kind = array();
	private $kind_separated = '';
	private $mail_contents = '';
	
	private $javascript_action = false;
	private $javascript_comment = '送信前の入力チェックは動作しませんでした。';
	private $now_url = '';
	private $before_url = '';
	
	
	private $year = '';
	private $month = '';
	private $day = '';
	private $hour = '';
	private $minute = '';
	private $second = '';
	
	
	private $reply_mail = '';
	
	
	private $send_body = '';
	private $thanks_body = '';
	private $send_subject = '';
	private $additional_headers = '';
	private $my_result = false;
	
	private $thanks_subject = '';
	private $thanks_additional_headers = '';
	private $you_result = false;
	
	
	
	
	public function __construct() {
		
		
		require_once( dirname( __FILE__ ) . '../../../../wp-load.php' );
		
		
		$this->send_address = get_option( 'send_address' );
		$this->send_name = get_option( 'send_name' );
		$this->thanks_page_url = get_option( 'thanks_page_url' );
		$this->spam_check = get_option( 'spam_check' );
		$this->domain_name = get_option( 'domain_name' );
		$this->javascript_check = get_option( 'javascript_check' );
		
		
		
		if( isset( $_SERVER['HTTP_REFERER'] ) ){
			$this->referer = $_SERVER['HTTP_REFERER'];
		}
		
		
		if( isset( $_SERVER['REMOTE_ADDR'] ) ){
			$this->addr = $_SERVER['REMOTE_ADDR'];
		}
		
		
		if( isset( $_SERVER['REMOTE_HOST'] ) ){
			$this->host = $_SERVER['REMOTE_HOST'];
		}else{
			$this->host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		}
		
		
		if( isset( $_SERVER['HTTP_USER_AGENT'] ) ){
			$this->agent = $_SERVER['HTTP_USER_AGENT'];
		}
		
		
		if( $this->spam_check == '1' && !empty( $this->domain_name ) ){
			if( strpos( $this->referer, $this->domain_name ) === false ) {
				echo '<p>不正な操作が行われたようです。</p>';
				exit;
			}
		}
		
		
		
		
		
		if( !( empty( $_POST['name_1'] ) ) ) {
			$this->name_1 = $this->sanitize_post( $_POST['name_1'] );
			$this->name_1 = mb_convert_kana( $this->name_1, 'KVa' );
		}
		
		if( !( empty( $_POST['name_2'] ) ) ) {
			$this->name_2 = $this->sanitize_post( $_POST['name_2'] );
			$this->name_2 = mb_convert_kana( $this->name_2, 'KVa' );
		}
		
		
		if( !( empty( $_POST['read_1'] ) ) ) {
			$this->read_1 = $this->sanitize_post( $_POST['read_1'] );
			$this->read_1 = mb_convert_kana( $this->read_1, 'KVa' );
		}
		
		if( !( empty( $_POST['read_2'] ) ) ) {
			$this->read_2 = $this->sanitize_post( $_POST['read_2'] );
			$this->read_2 = mb_convert_kana( $this->read_2, 'KVa' );
		}
		
		
		if( !( empty( $_POST['mail_address'] ) ) ) {
			$this->mail_address = $this->sanitize_post( $_POST['mail_address'] );
		}
		
		if( !( empty( $_POST['mail_address_confirm'] ) ) ) {
			$this->mail_address_confirm = $this->sanitize_post( $_POST['mail_address_confirm'] );
		}
		
		if( !( empty( $_POST['mail_address'] ) ) && !( empty( $_POST['mail_address_confirm'] ) ) ) {
			if( !( $this->mail_address === $this->mail_address_confirm ) ) {
				echo '<p>メールアドレスが一致しませんでした。</p>';
				exit;
			}
			
			if( !( preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $this->mail_address ) ) ) {
				echo '<p>正しくないメールアドレスです。</p>';
				exit;
			}
		}else{
			$this->mail_address_empty = true;
		}
		
		
		if( !( empty( $_POST['gender'] ) ) ) {
			$this->gender = $this->sanitize_post( $_POST['gender'] );
		}
		
		
		if( !( empty( $_POST['postal'] ) ) ) {
			$this->postal = $this->sanitize_post( $_POST['postal'] );
			$this->postal = mb_convert_kana( $this->postal, 'a' );
			$this->postal = str_replace(array(' ','-'), '', $this->postal);
		}
		
		
		if( !( empty( $_POST['address_1'] ) ) ) {
			$this->address_1 = $this->sanitize_post( $_POST['address_1'] );
			$this->address_1 = mb_convert_kana( $this->address_1, 'KVa' );
		}
		
		if( !( empty( $_POST['address_2']) ) ) {
			$this->address_2 = $this->sanitize_post( $_POST['address_2'] );
			$this->address_2 = mb_convert_kana( $this->address_2, 'KVa' );
		}
		
		
		if( !( empty( $_POST['phone'] ) ) ) {
			$this->phone = $this->sanitize_post( $_POST['phone'] );
			$this->phone = mb_convert_kana( $this->phone, 'a' );
		}
		
		
		if( !( empty( $_POST['schedule'] ) ) ) {
			$this->schedule = $this->sanitize_post( $_POST['schedule'] );
			$this->schedule = mb_convert_kana( $this->schedule, 'as' );
		}
		
		
		if( !( empty( $_POST['product'] ) ) ) {
			$this->product = $this->sanitize_post( $_POST['product'] );
		}
		
		
		if( !( empty( $_POST['kind'] ) ) ) {
			foreach( $_POST['kind'] as $key => $value ) {
				$this->kind[] = $this->sanitize_post( $_POST['kind'][$key] );
			}
			$this->kind_separated = implode( '、', $this->kind );
		}
		
		
		if( !( empty( $_POST['mail_contents'] ) ) ) {
			$this->mail_contents = htmlspecialchars( $_POST['mail_contents'] );
			$this->mail_contents = mb_convert_kana( $this->mail_contents, 'KVa' );
		}
		
		
		if( !( empty( $_POST['javascript_action'] ) ) ) {
			$this->javascript_action = true;
			$this->javascript_comment = '送信前の入力チェックは正常に動作しました。';
		}
		
		
		if( !( empty( $_POST['now_url'] ) ) ) {
			$this->now_url = $this->sanitize_post( $_POST['now_url'] );
			$this->now_url = mb_convert_kana( $this->now_url, 'as' );
		}
		
		
		if( !( empty( $_POST['before_url'] ) ) ) {
			$this->before_url = $this->sanitize_post( $_POST['before_url'] );
			$this->before_url = mb_convert_kana( $this->before_url, 'as' );
		}
		
		
		
		
		
		if( $this->javascript_check == '1' && $this->javascript_action === false ) {
			echo '<p>不正な操作が行われたようです。</p>';
			exit;
		}
		
		
		
		
		
		$this->year = date( 'Y' );
		$this->month = date( 'm' );
		$this->day = date( 'd' );
		$this->hour = date( 'H' );
		$this->minute = date( 'i' );
		$this->second = date( 's' );
		
		$this->reply_mail = get_option( 'reply_mail' );
		
		
		$this->send_body = sprintf(
			get_option( 'send_body' ),
			$this->year,
			$this->month,
			$this->day,
			$this->hour,
			$this->minute,
			$this->second,
			$this->name_1,
			$this->name_2,
			$this->read_1,
			$this->read_2,
			$this->mail_address,
			$this->gender,
			$this->postal,
			$this->address_1,
			$this->address_2,
			$this->phone,
			$this->schedule,
			$this->product,
			$this->kind_separated,
			$this->mail_contents,
			$this->addr,
			$this->host,
			$this->agent,
			$this->javascript_comment,
			$this->now_url,
			$this->before_url
		);
		
		
		$this->thanks_body = sprintf(
			get_option( 'thanks_body' ),
			$this->year,
			$this->month,
			$this->day,
			$this->hour,
			$this->minute,
			$this->second,
			$this->name_1,
			$this->name_2,
			$this->read_1,
			$this->read_2,
			$this->mail_address,
			$this->gender,
			$this->postal,
			$this->address_1,
			$this->address_2,
			$this->phone,
			$this->schedule,
			$this->product,
			$this->kind_separated,
			$this->mail_contents
		);
		
		
		$this->send_subject = 'メールフォームからお問い合わせがありました。';
		
		
		if( $this->mail_address_empty === false ) {
			$this->additional_headers = "From:".$this->mail_address;
		}else{
			$this->additional_headers = "From:".$this->send_address;
		}
		
		
		$this->my_result = mb_send_mail( $this->send_address, $this->send_subject, $this->send_body, $this->additional_headers );
		
		
		
		
		
		if( $this->reply_mail == '1' ) {
			
			$this->thanks_subject = 'お問い合わせありがとうございました。';
			$this->send_name = mb_encode_mimeheader( $this->send_name, 'ISO-2022-JP' );
			$this->thanks_additional_headers = "From:".$this->send_name." <".$this->send_address.">";
			
			
			if( $this->mail_address_empty === false ){
				$this->you_result = mb_send_mail( $this->mail_address, $this->thanks_subject, $this->thanks_body, $this->thanks_additional_headers );
			}else{
				$this->you_result = true;
			}
			
		}
		
		
		
		
		
		switch( $this->reply_mail ) {
			case 0:
				if( $this->my_result ) {
					header( 'Location: '.$this->thanks_page_url );
				}else{
					echo '<p>エラーが起きました。<br />ご迷惑をおかけして大変申し訳ありません。</p>';
					exit;
				}
				break;
			
			case 1:
				if( $this->my_result && $this->you_result ) {
					header( 'Location: '.$this->thanks_page_url );
				}else{
					echo '<p>エラーが起きました。<br />ご迷惑をおかけして大変申し訳ありません。</p>';
					exit;
				}
			 break;
		}
		
	}
	
	
	
	
	public function sanitize_post( $p ) {
		$p = htmlspecialchars( $p, ENT_QUOTES, 'UTF-8' );
		$p = esc_sql( $p );
		return $p;
	}
	
}


?>
