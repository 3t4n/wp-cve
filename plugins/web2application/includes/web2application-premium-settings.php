<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<?php
/* if ( ! defined( 'ABSPATH' ) ) exit;
define('WP_DEBUG', false); */
// Init Options Global
global $w2a_options;


	
// create regular HTML Object
if (isset($_POST['submit'])) {
	if(wp_verify_nonce($_REQUEST['w2a_premium_submit_post'], 'w2a_premium')){
		
		//sanitize input fields
		$postData = $_POST['data']; //sanitize_text_field($_POST['data']);
		
		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_premium_settings_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);
		
		/*$options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);*/
		
		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'web2application');
		echo '</div>';
	}
}

// get appId to check api key validity
$url = 'https://www.web2application.com/w2a/api-process/get_app_id.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']).'&version=new';
$appId = file_get_contents($url);

// check
if ($appId == "") {
	// init curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$appId = curl_exec($ch);
	curl_close($ch);
}


// check
$disabled = ($appId == 'Wrong API. Please Check Your API Key' || trim($w2a_options['w2a_api_key']) == "") ? true : false;

// check
if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) {
	// get app premium settings
	$url = 'https://www.web2application.com/w2a/api-process/get_premium_settings.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']);
	$settings = file_get_contents($url);
	//$row = json_decode($settings);
	
	// check
	if ($settings == "") {
		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$settings = curl_exec($ch);
		curl_close($ch);
	}
	
	// decode
	$row = json_decode($settings);
}

?>

<style type="text/css">
.form-control {
    width: 400px;
}
</style>

<script>
$(document).ready(function() {
    // push buttons
    $('input[type="checkbox"]').click(function() {
        var inputValue = $(this).attr("value");
        $("." + inputValue).toggle();
    });

    // push sounds
    $('#push-sound').on('change', function() {
        var sound = $(this).val();
        change('https://web2application.com/w2a/user/push_sounds/' + sound.replace(".wav", ".mp3"));
    });
});

function change(sourceUrl) {
    var audio = document.getElementById("player");
    var source = document.getElementById("mp3_src");

    audio.pause();

    if (sourceUrl) {
        source.src = sourceUrl;
        audio.load();
        audio.play();
    }
}
</script>

<div class="wrap">

    <h2><?php _e('Web2Application Premium Setting Page', 'web2application'); ?></h2>

    <form method="post">
		<div class="my-section">
        <h3><?php _e('App Settings', 'web2application'); ?></h3>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label><?php _e('Allow Landscape','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_allow_landscape1"><input type="radio" id="w2a_allow_landscape1" name="data[allow_landscape]" value="1" <?php echo ($row->allow_landscape == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?>/><?php _e('Yes', 'web2application'); ?></label></td>
                                <td><label for="w2a_allow_landscape0"><input type="radio" id="w2a_allow_landscape0" name="data[allow_landscape]" value="0" <?php echo ($row->allow_landscape == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?>/><?php _e('No', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Allow Zoom In/Out','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_allow_zoom1"><input type="radio" id="w2a_allow_zoom1" name="data[allow_zoom]" value="1" <?php echo ($row->allow_zoom == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
                                <td><label for="w2a_allow_zoom0"><input type="radio" id="w2a_allow_zoom0" name="data[allow_zoom]" value="0" <?php echo ($row->allow_zoom == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Support Deep Link','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_support_deep_link1"><input type="radio" id="w2a_support_deep_link1" name="data[support_deep_link]" value="1" <?php echo ($row->support_deep_link == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
                                <td><label for="w2a_support_deep_link0"><input type="radio" id="w2a_support_deep_link0" name="data[support_deep_link]" value="0" <?php echo ($row->support_deep_link == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table><br>
		</div>
		
		<div class="my-section" style="margin-top:20px;">
        <h3><?php _e('Push Settings', 'web2application'); ?></h3>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label><?php _e('Push Special Sound','web2application'); ?></label></th>
                    <td valign="top">
						<select name="data[push_sound]" class="form-control" id="push-sound">
							<option value="default" <?php echo ($row->push_sound == "default") ? "selected" : ""; ?>>Default</option>
							<option value="alarm.wav" <?php echo ($row->push_sound == "alarm.wav") ? "selected" : ""; ?>>Alarm</option>
							<option value="announce_tones.wav" <?php echo ($row->push_sound == "announce_tones.wav") ? "selected" : ""; ?>>Announce Tone</option>
							<option value="arcade_retro_game_over.wav" <?php echo ($row->push_sound == "arcade_retro_game_over.wav") ? "selected" : ""; ?>>Arcade Retro Game Over</option>
							<option value="bells.wav" <?php echo ($row->push_sound == "bells.wav") ? "selected" : ""; ?>>Bells</option>
							<option value="car_double_horn.wav" <?php echo ($row->push_sound == "car_double_horn.wav") ? "selected" : ""; ?>>Car Double Horn</option>
							<option value="cartoon_insect_long_buzz.wav" <?php echo ($row->push_sound == "cartoon_insect_long_buzz.wav") ? "selected" : ""; ?>>Cartoon Insect Long Buzz</option>
							<option value="cartoon_toy_whistle.wav" <?php echo ($row->push_sound == "cartoon_toy_whistle.wav") ? "selected" : ""; ?>>Cartoon Toy Whistle</option>
							<option value="church_bell_loop.wav" <?php echo ($row->push_sound == "church_bell_loop.wav") ? "selected" : ""; ?>>Church Bell Loop</option>
							<option value="classic_alarm.wav" <?php echo ($row->push_sound == "classic_alarm.wav") ? "selected" : ""; ?>>Classic Alarm</option>
							<option value="clinking_coins.wav" <?php echo ($row->push_sound == "clinking_coins.wav") ? "selected" : ""; ?>>Clinking Coins</option>
							<option value="clock_countdown_bleeps.wav" <?php echo ($row->push_sound == "clock_countdown_bleeps.wav") ? "selected" : ""; ?>>Clock Countdown Bleeps</option>
							<option value="coin_win.wav" <?php echo ($row->push_sound == "coin_win.wav") ? "selected" : ""; ?>>Coin Win</option>
							<option value="crowd_laugh.wav" <?php echo ($row->push_sound == "crowd_laugh.wav") ? "selected" : ""; ?>>Crowd Laugh</option>
							<option value="digital_cartoon_falling.wav" <?php echo ($row->push_sound == "digital_cartoon_falling.wav") ? "selected" : ""; ?>>Digital Cartoon Falling</option>
							<option value="dog_barking.wav" <?php echo ($row->push_sound == "dog_barking.wav") ? "selected" : ""; ?>>Dog Barking</option>
							<option value="fantasy_game_success.wav" <?php echo ($row->push_sound == "fantasy_game_success.wav") ? "selected" : ""; ?>>Fantasy Game Success</option>
							<option value="fast_rocket_whoosh.wav" <?php echo ($row->push_sound == "fast_rocket_whoosh.wav") ? "selected" : ""; ?>>Fast Rocket Whoosh</option>
							<option value="flute.wav" <?php echo ($row->push_sound == "flute.wav") ? "selected" : ""; ?>>Flute</option>
							<option value="game_success.wav" <?php echo ($row->push_sound == "game_success.wav") ? "selected" : ""; ?>>Game Success</option>
							<option value="gold_coin_price.wav" <?php echo ($row->push_sound == "gold_coin_price.wav") ? "selected" : ""; ?>>Gold Coin Price</option>
							<option value="horror_bell.wav" <?php echo ($row->push_sound == "horror_bell.wav") ? "selected" : ""; ?>>Horror Bell</option>
							<option value="human_whistle.wav" <?php echo ($row->push_sound == "human_whistle.wav") ? "selected" : ""; ?>>Human Whistle</option>
							<option value="kitty_meow.wav" <?php echo ($row->push_sound == "kitty_meow.wav") ? "selected" : ""; ?>>Kitty Meow</option>
							<option value="little_birds_singing.wav" <?php echo ($row->push_sound == "little_birds_singing.wav") ? "selected" : ""; ?>>Little Birds Singing</option>
							<option value="marimba.wav" <?php echo ($row->push_sound == "marimba.wav") ? "selected" : ""; ?>>Marimba</option>
							<option value="one_man_clapping.wav" <?php echo ($row->push_sound == "one_man_clapping.wav") ? "selected" : ""; ?>>One Man Clapping</option>
							<option value="page_back_chime.wav" <?php echo ($row->push_sound == "page_back_chime.wav") ? "selected" : ""; ?>>Page Back Chime</option>
							<option value="page_forward_chime.wav" <?php echo ($row->push_sound == "page_forward_chime.wav") ? "selected" : ""; ?>>Page Forward Chime</option>
							<option value="police_whistle.wav" <?php echo ($row->push_sound == "police_whistle.wav") ? "selected" : ""; ?>>Police Whistle</option>
							<option value="quick_chime.wav" <?php echo ($row->push_sound == "quick_chime.wav") ? "selected" : ""; ?>>Wuick Chime</option>
							<option value="telephone_ring.wav" <?php echo ($row->push_sound == "telephone_ring.wav") ? "selected" : ""; ?>>Telephone Ring</option>
							<option value="truck_horn.wav" <?php echo ($row->push_sound == "truck_horn.wav") ? "selected" : ""; ?>>Truck Horn</option>
							<option value="vintage_telephone_ring.wav" <?php echo ($row->push_sound == "vintage_telephone_ring.wav") ? "selected" : ""; ?>>Vintage Telephone Ring</option>
							<option value="whistler_bird.wav" <?php echo ($row->push_sound == "whistler_bird.wav") ? "selected" : ""; ?>>Whistler Bird</option>
						</select><br><br>
						<audio id="player" controls="controls">
							<source id="mp3_src" src="<?php echo 'https://web2application.com/w2a/user/push_sounds/'.str_replace(".wav", ".mp3", $row->push_sound); ?>" type="audio/mp3" />Your browser does not support the audio element.
						</audio>
                    </td>
                </tr>
			</tbody>
		</table><br>
		</div>
		
		<div class="my-section" style="margin-top:20px;">
        <h3><?php _e('Advanced Settings', 'web2application'); ?></h3>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label><?php _e('Remember Password and Form Data in App','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_remember_password1"><input type="radio" id="w2a_remember_password1" name="data[remember_password]" value="1" <?php echo ($row->remember_password == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
                                <td><label for="w2a_remember_password0"><input type="radio" id="w2a_remember_password0" name="data[remember_password]" value="0" <?php echo ($row->remember_password == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Open External Link in Browser','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_open_to_browser0"><input type="radio" id="w2a_open_to_browser0" name="data[open_to_browser]" value="0" <?php echo ($row->open_to_browser == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
                                <td><label for="w2a_open_to_browser1"><input type="radio" id="w2a_open_to_browser1" name="data[open_to_browser]" value="1" <?php echo ($row->open_to_browser == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Open _blank tag in browser', 'web2application'); ?></label></td>
                                <td><label for="w2a_open_to_browser2"><input type="radio" id="w2a_open_to_browser2" name="data[open_to_browser]" value="2" <?php echo ($row->open_to_browser == 2) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Open all external link in browser', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Disable No Internet Connection Screen','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_disable_no_internet1"><input type="radio" id="w2a_disable_no_internet1" name="data[disable_no_internet]" value="1" <?php echo ($row->disable_no_internet == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
                                <td><label for="w2a_disable_no_internet0"><input type="radio" id="w2a_disable_no_internet0" name="data[disable_no_internet]" value="0" <?php echo ($row->disable_no_internet == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Disable Cached in WebView','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_disable_webview_cache1"><input type="radio" id="w2a_disable_webview_cache1" name="data[disable_webview_cache]" value="1" <?php echo ($row->disable_webview_cache == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
                                <td><label for="w2a_disable_webview_cache0"><input type="radio" id="w2a_disable_webview_cache0" name="data[disable_webview_cache]" value="0" <?php echo ($row->disable_webview_cache == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Support Progress Bar','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_allow_progress_bar0"><input type="radio" id="w2a_allow_progress_bar0" name="data[allow_progress_bar]" value="0" <?php echo ($row->allow_progress_bar == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Dont show progress bar', 'web2application'); ?></label></td>
                                <td><label for="w2a_allow_progress_bar1"><input type="radio" id="w2a_allow_progress_bar1" name="data[allow_progress_bar]" value="1" <?php echo ($row->allow_progress_bar == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Line progress bar' ,'web2application'); ?></label></td>
                                <td><label for="w2a_allow_progress_bar2"><input type="radio" id="w2a_allow_progress_bar2" name="data[allow_progress_bar]" value="0" <?php echo ($row->allow_progress_bar == 2) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Round progress bar', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Disable Pull to Refresh','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_disable_pull_to_refresh1"><input type="radio" id="w2a_disable_pull_to_refresh1" name="data[disable_pull_to_refresh]" value="1" <?php echo ($row->disable_pull_to_refresh == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes' ,'web2application'); ?></label></td>
                                <td><label for="w2a_disable_pull_to_refresh0"><input type="radio" id="w2a_disable_pull_to_refresh0" name="data[disable_pull_to_refresh]" value="0" <?php echo ($row->disable_pull_to_refresh == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
		</div>
        <?php wp_nonce_field('w2a_premium', 'w2a_premium_submit_post'); ?>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'web2application'); ?>"/></p>

    </form>

</div>

<?php ?>
