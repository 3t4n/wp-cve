<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$webmaster_token	= get_option('dl_yandex_webmaster_token');
$webmaster_user_id	= get_option('dl_yandex_webmaster_user_id');
$webmaster_host_id	= get_option('dl_yandex_webmaster_host_id');
?>

<div class="wrap">
<h2>DL Яндекс Вебмастер</h2>

<form method="post" action="options.php">

<?php 

settings_fields( 'dl-yandex-webmaster-settings-group' );
	
settings_errors();

if ($webmaster_token == '') { ?>

	<h3>Установка и настройка </h3>
	<ol>
		<li>Разрешить доступ к своим данным и получить токен - <a target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&amp;client_id=cc5d85b0441e42428741fe4ee89ff69c" class="button" style="margin-top:-5px">Получить токен</a></li>
		<li><input type="text" name="dl_yandex_webmaster_token" value=""  required> - Token доступа</li>
	</ol>
		
	<p class="submit">
		<input type="submit" class="button-primary" value="Сохранить и продолжить" />
	</p>

<?php } elseif ($webmaster_host_id == '') { 


$webmaster_token_aut = 'Authorization: OAuth ' . $webmaster_token;


$response_user_id = wp_remote_get( 'https://api.webmaster.yandex.net/v3/user/', 
	array(
		'method'	=> 'GET',
		'headers'	=> $webmaster_token_aut,
	)
);

$response_user_id = wp_remote_retrieve_body( $response_user_id );
$response_user_id = json_decode($response_user_id, true);
$webmaster_user_id = $response_user_id['user_id'];


$response_hosts_id = wp_remote_get( 'https://api.webmaster.yandex.net/v3/user/' . $webmaster_user_id . '/hosts/', 
	array(
		'method'	=> 'GET',
		'headers'	=> $webmaster_token_aut,
	)
);

$response_hosts_id = wp_remote_retrieve_body( $response_hosts_id );
$response_hosts_id = json_decode($response_hosts_id, true);

$response_hosts_id = $response_hosts_id[hosts];


echo '<p>Выбрать сайт для работы: <select name="dl_yandex_webmaster_host_id">';

foreach($response_hosts_id as $key => $value) {
	echo '<option value="' . $response_hosts_id[$key]['host_id'] . '">' . $response_hosts_id[$key]['unicode_host_url'] . '</li>';	
}
echo '</select></p>';

?>

<input type="hidden" name="dl_yandex_webmaster_token" value="<?php echo $webmaster_token; ?>">
<input type="hidden" name="dl_yandex_webmaster_user_id" value="<?php echo $webmaster_user_id; ?>">

<p class="submit">
	<input type="submit" class="button-primary" value="Завершить настройку" />
</p>
<? } ?>

</form>

</div>