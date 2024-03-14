<div class="wrap">
<h2>DL Яндекс Вебмастер</h2>

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$webmaster_token	= get_option('dl_yandex_webmaster_token');
$webmaster_user_id	= get_option('dl_yandex_webmaster_user_id');
$webmaster_host_id	= get_option('dl_yandex_webmaster_host_id');

$webmaster_token = 'Authorization: OAuth ' . $webmaster_token;
$response = wp_remote_get( 'https://api.webmaster.yandex.net/v3/user/' . $webmaster_user_id . '/hosts/' . $webmaster_host_id . '/summary/', 
	array(
		'method'	=> 'GET',
		'headers'	=> $webmaster_token,
	)
);


if($response[response][code] == 404) {
  
  echo '<h3>Данные появятся в ближайшее время</h3>';
  
} else {
  
	$response = wp_remote_retrieve_body( $response );
	$response = json_decode($response, true);

?>


<table class="form-table">
	<tr>
		<th scope="row">Cтатистика сайта</th>
		<td><p><b><? echo $response[tic]; ?></b> - тИЦ сайта</p>
			<p><b><? echo $response[downloaded_pages_count]; ?></b> - Количество страниц, загруженных роботом Яндекса.</p>
			<p><b><? echo $response[excluded_pages_count]; ?></b> - Количество исключенных страниц.</p>
			<p><b><? echo $response[searchable_pages_count]; ?></b> - Количество страниц в поиске.</p>
		</td> 
	</tr>
	<tr>
		<th scope="row">Количество найденных на сайте проблем.</th>
		<td><p><b><? echo $response[site_problems][POSSIBLE_PROBLEM]; ?></b> - Возможные проблемы</p>
			<p><b><? echo $response[site_problems][RECOMMENDATION]; ?></b> - Рекомендации</p>
		</td>
	</tr>
</table>
  
<? } ?>  
  
</div>