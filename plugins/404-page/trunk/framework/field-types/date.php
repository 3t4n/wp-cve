<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db

$option_values = array(
	'01'=>__('01-Jan','seedprod'),
	'02'=>__('02-Feb','seedprod'),
	'03'=>__('03-Mar','seedprod'),
	'04'=>__('04-Apr','seedprod'),
	'05'=>__('05-May','seedprod'),
	'06'=>__('06-Jun','seedprod'),
	'07'=>__('07-Jul','seedprod'),
	'08'=>__('08-Aug','seedprod'),
	'09'=>__('09-Sep','seedprod'),
	'10'=>__('10-Oct','seedprod'),
	'11'=>__('11-Nov','seedprod'),
	'12'=>__('12-Dec','seedprod'),
	);


echo "<select id='mm' name='{$setting_id}[$id][month]'>";
foreach ( $option_values as $k => $v ) {
    echo "<option value='$k' " . selected( $options[ $id ]['month'], $k, false ) . ">$v</option>";
}
echo "</select>";

echo "<input id='jj' class='small-text' name='{$setting_id}[$id][day]' placeholder='".__('day','seedprod')."' type='text' value='" . esc_attr( $options[ $id ]['day'] ) . "' />";

echo ',';
echo "<input id='aa' class='small-text' name='{$setting_id}[$id][year]' placeholder='".__('year','seedprod')."'  type='text' value='" . esc_attr( $options[ $id ]['year'] ) . "' /><br>";
