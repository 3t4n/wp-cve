<?php

class AdminUi {
	var $option_name;
	var $file_path;

	public function __construct( $file ){
		$this->option_name = BC::OPTIONS;
		$this->file_path = $file;
		$this->setUi();
	}

	public function setUi(){
		register_setting($this->option_name, $this->option_name, array ( &$this, 'validate' ));
		add_settings_section('fixed_holiday', '定休日', array(&$this,'text_fixed_holiday'), $this->file_path);
		add_settings_field('id_holiday_title', '定休日の説明', array(&$this,'setting_holiday_title'), $this->file_path, 'fixed_holiday');
		add_settings_field('id_chk_sun', '日曜日', array(&$this,'setting_chk_sun'), $this->file_path, 'fixed_holiday');
		add_settings_field('id_chk_mon', '月曜日', array(&$this,'setting_chk_mon'), $this->file_path, 'fixed_holiday');
		add_settings_field('id_chk_tue', '火曜日', array(&$this,'setting_chk_tue'), $this->file_path, 'fixed_holiday');
		add_settings_field('id_chk_wed', '水曜日', array(&$this,'setting_chk_wed'), $this->file_path, 'fixed_holiday');
		add_settings_field('id_chk_thu', '木曜日', array(&$this,'setting_chk_thu'), $this->file_path, 'fixed_holiday');
		add_settings_field('id_chk_fri', '金曜日', array(&$this,'setting_chk_fri'), $this->file_path, 'fixed_holiday');
		add_settings_field('id_chk_sat', '土曜日', array(&$this,'setting_chk_sat'), $this->file_path, 'fixed_holiday');
		add_settings_field('id_chk_holiday', '祝日を定休日にする', array(&$this,'setting_chk_holiday'), $this->file_path, 'fixed_holiday');

		add_settings_section('temp_holiday', '臨時休営業日', array(&$this,'text_temp_holiday'), $this->file_path);
		add_settings_field('id_temp_holidays', '臨時休業日', array(&$this,'setting_temp_holidays'), $this->file_path, 'temp_holiday');
		add_settings_field('id_temp_weekdays', '臨時営業日', array(&$this,'setting_temp_weekdays'), $this->file_path, 'temp_holiday');

		add_settings_section('eventday', 'イベント', array(&$this,'text_eventday'), $this->file_path);
		add_settings_field('id_eventday_title', 'イベントの説明', array(&$this,'setting_eventday_title'), $this->file_path, 'eventday');
		add_settings_field('id_eventday_url', 'イベントのurl', array(&$this,'setting_eventday_url'), $this->file_path, 'eventday');
		add_settings_field('id_eventdays', 'イベント日', array(&$this,'setting_eventdays'), $this->file_path, 'eventday');
		add_settings_section('monthlimit', '月送り制限', array(&$this,'text_monthlimit'), $this->file_path);
		add_settings_field('id_monthlimit', '月送り制限設定', array(&$this,'setting_monthlimit'), $this->file_path, 'monthlimit');
		add_settings_field('id_nextmonthlimit', '次の月', array(&$this,'setting_nextmonthlimit'), $this->file_path, 'monthlimit');
		add_settings_field('id_prevmonthlimit', '前の月', array(&$this,'setting_prevmonthlimit'), $this->file_path, 'monthlimit');
	}

	function validate($input) {
		$input["holiday_title"] = esc_html($input["holiday_title"]);
		$input["eventday_title"] = esc_html($input["eventday_title"]);
		$input["eventday_url"] = esc_url($input["eventday_url"]);
		return $input; // return validated input
	}

	function  text_fixed_holiday() {
		echo '<p>定休日として設定する曜日をチェックします。「祝日を定休日にする」には祝日ファイルの登録が必要です</p>';
	}

	function  text_temp_holiday() {
		echo '<p>臨時営業日・休業日を設定します。<br>YYYY-MM-DD (例 2001-01-01)の形式で登録します。複数登録する場合は改行してください。登録できる件数の上限はありません。</p>';
	}

	function  text_eventday() {
		echo '<p>イベントの説明、url、日にちを登録します。<br>イベント日は、YYYY-MM-DD (例 2001-01-01)の形式で登録します。複数登録する場合は改行してください。登録できる件数の上限はありません。</p>';
	}

	function  text_monthlimit() {
// 		echo '<p>月送りの制限を選択してください</p>';
	}


	function setting_chk_sun() {
		$this->setting_chk( "sun" );
	}

	function setting_chk_mon() {
		$this->setting_chk( "mon" );
	}

	function setting_chk_tue() {
		$this->setting_chk( "tue" );
	}

	function setting_chk_wed() {
		$this->setting_chk( "wed" );
	}

	function setting_chk_thu() {
		$this->setting_chk( "thu" );
	}

	function setting_chk_fri() {
		$this->setting_chk( "fri" );
	}

	function setting_chk_sat() {
		$this->setting_chk( "sat" );
	}

	function setting_chk_holiday() {
		$this->setting_chk( "holiday" );
	}

	function setting_chk( $id ) {
		$options = get_option($this->option_name);
		$checked = (isset($options[$id]) && $options[$id]) ? $checked = ' checked="checked" ': "";
		$name = $this->option_name. "[$id]";

		echo "<input ".$checked." id='id_".$id."' name='".$name."' type='checkbox' />";
	}

	function setting_inputtext( $name, $size) {
		$options = get_option($this->option_name);
		$value = esc_html( $options[$name] );
		echo "<input id='{$name}' name='bizcalendar_options[{$name}]' size='{$size}' type='text' value='{$value}' />";
	}

	function setting_holiday_title() {
		$this->setting_inputtext("holiday_title", 40);
	}

	function setting_eventday_title() {
		$this->setting_inputtext("eventday_title", 40);
	}

	function setting_eventday_url() {
		$this->setting_inputtext("eventday_url", 60);
	}

	function setting_textarea( $name ) {
		$options = get_option($this->option_name);
		$value = esc_html( $options[ $name ] );
		echo "<textarea id='{$name}' name='bizcalendar_options[{$name}]' rows='7' cols='15'>{$value}</textarea>";
	}

	function setting_temp_holidays() {
		$this->setting_textarea("temp_holidays");
	}

	function setting_temp_weekdays() {
		$this->setting_textarea("temp_weekdays");
	}

	function setting_eventdays() {
		$this->setting_textarea("eventdays");
	}

	function setting_monthlimit() {
		$options = get_option($this->option_name);
		$items = array("制限なし", "年内", "年度内", "指定");
		foreach($items as $item) {
			$checked = ($options['month_limit']==$item) ? ' checked="checked" ' : '';
			echo "<label><input {$checked} value='$item' name='bizcalendar_options[month_limit]' type='radio' /> $item</label><br />";
		}
	}

	function setting_nextmonthlimit() {
		$options = get_option($this->option_name);
		$items = array("0", "1", "2", "3","4", "5", "6", "7", "8", "9", "10", "11", "12");
		echo "<select id='nextmonthlimit' name='bizcalendar_options[nextmonthlimit]'>";
		foreach($items as $item) {
			$selected = ($options['nextmonthlimit'] == $item) ? 'selected="selected"' : '';
			echo "<option value='$item' $selected>$item</option>";
		}
		echo "</select>";
		echo "ヶ月先まで";
	}

	function setting_prevmonthlimit() {
		$options = get_option($this->option_name);
		$items = array("0", "1", "2", "3","4", "5", "6", "7", "8", "9", "10", "11", "12");
		echo "<select id='prevmonthlimit' name='bizcalendar_options[prevmonthlimit]'>";
		foreach($items as $item) {
			$selected = ($options['prevmonthlimit'] == $item) ? 'selected="selected"' : '';
			echo "<option value='$item' $selected>$item</option>";
		}
		echo "</select>";
		echo "ヶ月前まで";
	}

}
