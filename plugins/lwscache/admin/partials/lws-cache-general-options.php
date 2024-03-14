<?php
/**
 * Display general options of the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      2.0.0
 *
 * @package    lwscache
 * @subpackage lwscache/admin/partials
 */

global $lws_cache_admin, $nginx_purger;
$error_log_filesize = false;
$nginx_settings = get_site_option('rt_wp_lws_cache_options', $lws_cache_admin->lws_cache_default_settings());

if ((! is_numeric($nginx_settings['log_filesize'])) || (empty($nginx_settings['log_filesize']))) {
	$error_log_filesize = __('Log file size must be a number.', 'lwscache');
	unset($nginx_settings['log_filesize']);
}

if ($nginx_settings['enable_map']) {
	$lws_cache_admin->update_map();
}

if (isset($_POST['change_cache_fastest_cache'])){
    $array = (explode('/', ABSPATH));
    $path = implode('/', array($array[0], $array[1], $array[2]));        
    $api_key = file_get_contents($path . '/tmp/fc_token_api');
    wp_remote_post(
        "http://localhost:6084/api/domains/" . $_SERVER['HTTP_HOST'],
        array(
        'method'      => 'PUT',
        'headers'     => array('Authorization' => 'Bearer ' . $api_key, 'Content-Type' => "application/x-www-form-urlencoded" ),
        'body'		  => array(
            'template' => 'dev',
            ),
        )
    );
    ?>
    <script>
        window.location.reload();
    </script>
    <?php
}

$purge = $nginx_settings['enable_purge'];
?>

<div class="lwscache_purge_container">
	<div class="lwscache_autopurge_element">
		<div class="lwscache_autopurge_element_left">
			<div class="lwscache_autopurge_header">
				<h2 class="lwscache_autopurge_title"><?php esc_html_e('Automatic Purge', 'lwscache'); ?></h2>
				<div class="lwscache_recommanded">
					<span><?php esc_html_e('recommended', 'lwscache'); ?></span>
				</div>
			</div>
			<div class="lwscache_autopurge_content">
				<?php esc_html_e("Smart purge based on your website's activity (modifications, comments, etc...) for an efficient cache management.", 'lwscache'); ?>
			</div>
		</div>
		<div class="lwscache_autopurge_element_right">
			<label class="label_checkbox_button">
                <label class="rounded_checkbox">
                    <input class="checkbox_input" id="autopurge_check" name="autopurge_check" type="checkbox" <?php echo $purge ? esc_html("checked") : esc_html(''); ?>>
                    <span class="checkbox_button"></span>
                </label>
            </label>
		</div>
	</div>

	<div class="lwscache_autopurge_element">
		<div class="lwscache_autopurge_element_left">
			<div class="lwscache_autopurge_header">
				<h2 class="lwscache_autopurge_title"><?php esc_html_e('Manual Purge', 'lwscache'); ?></h2>
			</div>
			<div class="lwscache_autopurge_content">
				<?php esc_html_e('Purge your website\'s cache on demand. Perfect to update content without automatic purge.', 'lwscache'); ?>
			</div>
		</div>
		<div class="lwscache_autopurge_element_right">
			<?php
				$purge_url  = add_query_arg(
					array(
						'lws_cache_action' => 'purge',
						'lws_cache_urls'   => 'all'
					)
				);
				$nonced_url = wp_nonce_url($purge_url, 'lws_cache-purge_all');				
			?>
			<a type="button" class="lwscache_lb_a" name="manualpurge_check" id="manualpurge_check" href="<?php echo esc_url($nonced_url); ?>">
				<img src="<?php echo esc_url(plugins_url('icons/supprimer.svg', __DIR__))?>" alt="Poubelle Logo" width="20px" height="20px">
				<span><?php esc_html_e("Purge all cache", 'lwscache'); ?></span>
			</a>
		</div>
	</div>

	<?php /*
	<div class="lwscache_autopurge_element">
		<div class="lwscache_autopurge_element_left">
			<div class="lwscache_autopurge_header">
				<h2 class="lwscache_autopurge_title"><?php esc_html_e('Exclude URLs from caching', 'lwscache'); ?></h2>
			</div>
			<div class="lwscache_autopurge_content">
				<span><?php esc_html_e("Option to exclude URLs from caching.", 'lwscache'); ?></span>
				<span>
					<span id="setted_exclusions" class="lwscache_setted_exclusions">X</span>
					<?php esc_html_e("exclusions setted.", 'lwscache'); ?>
				</span>
			</div>
		</div>
		<div class="lwscache_autopurge_element_right">
			<button type="button" class="lwscache_b_button" name="exclude_purge" id="exclude_purge" data-toggle="modal" data-target="#lwscache_exclude_urls">
				<span><?php esc_html_e("Modify", 'lwscache'); ?></span>
			</button>
		</div>
	</div>
	*/ ?>
</div>

<?php /*
<div class="modal fade" id="lwscache_exclude_urls" tabindex='-1' role='dialog' aria-hidden='true'>
    <div class="modal-dialog">
        <div class="modal-content">
            <h2 class="lwscache_exclude_title"><?php echo esc_html_e('Exclude URLs from the cache', 'lwscache'); ?></h2>
            <form method="POST" id="lwscache_form_exclude_urls">                
            </form>
			<div class="lwscache_modal_buttons">
				<button type="button" class="lwscache_closebutton" data-dismiss="modal"><?php echo esc_html_e('Close', 'lwscache'); ?></button>
				<button type="button" id="lwscache_submit_excluded_form" class="lwscache_validatebutton">
					<img src="<?php echo esc_url(plugins_url('icons/enregistrer.svg', __DIR__))?>" alt="Logo Disquette" width="20px" height="20px">
					<?php echo esc_html_e('Save', 'lwscache'); ?>
				</button>
			</div>
        </div>
    </div>
</div>
*/ ?>

<script>
	if (localStorage.getItem('lwscache_manualpurge_done') !== null) {
		localStorage.removeItem('lwscache_manualpurge_done');
		callPopup('success', "Purge du LWSCache effectuée");
	}

	document.getElementById('autopurge_check').addEventListener('change', function(){
		let state_purge = this.checked;
		let ajaxRequest = jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			timeout: 120000,
			context: document.body,            
			data: { 
				state: state_purge,
				_ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lwscache_change_autopurge_nonce')); ?>',
				action: "change_autopurge"
			},
			success: function(data) { 
				console.log(data);                                          
				if (data === null || typeof data != 'string'){
					return 0;
				}

				try{
					var returnData = JSON.parse(data);
				} catch (e){
					console.log(e);
					return 0;
				}
			
				switch (returnData['code']){
					case 'SUCCESS':
						if (returnData['data'] == "true") {
							callPopup('success', "Purge automatique du LWSCache activée");
						} else {
							callPopup('success', "Purge automatique du LWSCache désactivée");
						}
						break;                                
					default:
						break;
				}
			},
			error: function(error) {
				console.log(error);
			}
		});
	});

	document.getElementById('manualpurge_check').addEventListener('click', function(){
		localStorage.setItem('lwscache_manualpurge_done', 'true');
	});

	<?php /*
	document.getElementById('exclude_purge').addEventListener('click', function() {
		let form = document.getElementById('lwscache_form_exclude_urls');
		let ajaxRequest = jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			timeout: 120000,
			context: document.body,            
			data: { 
				_ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lwscache_get_excluded_nonce')); ?>',
				action: "lwscache_get_excluded_url"
			},
			success: function(data) { 
				if (data === null || typeof data != 'string'){
					return 0;
				}

				try{
					var returnData = JSON.parse(data);
				} catch (e){
					console.log(e);
					return 0;
				}
			
				switch (returnData['code']){
					case 'SUCCESS':
						let urls = returnData['data'];
						let domain = returnData['domain'];
						form.innerHTML = '';
						if (!urls.length) {
							form.innerHTML = `
								<div class="lwscache_exclude_element">
									<div class="lwscache_exclude_url">
										` + domain + `/
									</div>
									<input type="text" class="lwscache_exclude_input" name="lwscache_exclude_url" value="">
									<div class="lwscache_exclude_action_buttons">
										<div class="lwscache_exclude_action_button red" name="lwscache_less_urls">-</div>
										<div class="lwscache_exclude_action_button green" name="lwscache_more_urls">+</div>
									</div>
								</div>
							`;
						} else {
							for (var i in urls) {
								form.insertAdjacentHTML('beforeend', `
									<div class="lwscache_exclude_element">
										<div class="lwscache_exclude_url">
											` + domain + `/
										</div>
										<input type="text" class="lwscache_exclude_input" name="lwscache_exclude_url" value="` + urls[i] + `">
										<div class="lwscache_exclude_action_buttons">
											<div class="lwscache_exclude_action_button red" name="lwscache_less_urls">-</div>
											<div class="lwscache_exclude_action_button green" name="lwscache_more_urls">+</div>
										</div>
									</div>
								`);
							}
						}

						document.addEventListener( "click", function(event){
							var element = event.target;
							if (element.getAttribute('name') == "lwscache_less_urls") {
								let amount_element = document.getElementsByName("lwscache_exclude_url").length;
								if (amount_element > 1) {
									let element_remove = element.parentNode.parentNode;
									element_remove.remove();
								}
							}

							if (element.getAttribute('name') == "lwscache_more_urls") {
								let amount_element = document.getElementsByName("lwscache_exclude_url").length;
								let element_create = element.parentNode.parentNode;

								let new_element = document.createElement("div");
								new_element.insertAdjacentHTML("afterbegin", `
									<div class="lwscache_exclude_url">
										` + domain + `/
									</div>
									<input type="text" class="lwscache_exclude_input" name="lwscache_exclude_url" value="">
									<div class="lwscache_exclude_action_buttons">
										<div class="lwscache_exclude_action_button red" name="lwscache_less_urls">-</div>
										<div class="lwscache_exclude_action_button green" name="lwscache_more_urls">+</div>
									</div>
								`);
								new_element.classList.add('lwscache_exclude_element');

								element_create.after(new_element);
							}
						});

						document.getElementById('lwscache_submit_excluded_form').addEventListener('click', function() {
							let form = document.getElementById('lwscache_form_exclude_urls');
							if (form !== null) {
								form.dispatchEvent(new Event('submit'));
							}
						})
						document.getElementById('lwscache_form_exclude_urls').addEventListener('submit', function(event) {
							event.preventDefault();
							let formData = jQuery(this).serializeArray();
							let ajaxRequest = jQuery.ajax({
								url: ajaxurl,
								type: "POST",
								timeout: 120000,
								context: document.body,            
								data: { 
									data: formData,
									_ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lwscache_save_excluded_nonce')); ?>',
									action: "lwscache_save_excluded_url"
								},
								success: function(data) { 
									if (data === null || typeof data != 'string'){
										return 0;
									}

									try{
										var returnData = JSON.parse(data);
									} catch (e){
										console.log(e);
										return 0;
									}

									jQuery(document.getElementById('lwscache_exclude_urls')).modal('hide');
									switch (returnData['code']){
										case 'SUCCESS':
											callPopup('success', "Les URLs ont bien été sauvegardées");
											break;
										case 'FAILED':
											callPopup('error', "Les URLs n'ont pas pu être sauvegardées");
											break;
										case 'NO_DATA':
											callPopup('error', "Les URLs n'ont pas pu être sauvegardées car aucune donnée n'a été trouvée");
											break;
										default:
										callPopup('error', "Les URLs n'ont pas pu être sauvegardées car une erreur est survenue");
											break;
									}
								},
								error: function(error) {
									console.log(error);
								}
							});
						})
						break;
					default:
						break;
				}
			},
			error: function(error) {
				console.log(error);
			}
		});
	}); */ ?>
</script>