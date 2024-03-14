<?php
/**
 * Plugin Name: Wetterwarner
 * Plugin URI: https://it93.de/projekte/wetterwarner/
 * Description: Zeigt amtliche Wetterwarnungen in einem Widget an
 * Version: 2.7.2
 * Author: Tim Knigge
 * Author URI: https://www.linkedin.com/in/tim-knigge-a1238912b/
 * Text Domain: wetterwarner
 * Domain Path: /lang
 */
 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( ! defined( 'WW_PLUGIN_VER' ) ) {
	define( 'WW_PLUGIN_VER', '2.7' );
}
require_once dirname(__FILE__) . '/wetterwarner-settings.php';
require_once dirname(__FILE__) . '/wetterwarner-functions.php';

if(!class_exists('Wetterwarner_Widget')) {
	
    class Wetterwarner_Widget extends WP_Widget {
		public function __construct(){
			parent::__construct(
				'Wetterwarner_Widget',
				'Wetterwarner',
				array(
					'description' => __('Displays official weather warnings and, if desired, a weather map in a widget.', 'wetterwarner'),
					'customize_selective_refresh' => true,
				)
			);
			add_action( 'wp_enqueue_scripts', 'enqueueStyleAndScripts' );
		}

		/* Update Funktion der Einstellungen in die WP Datenbank */
		public function update($new_instance, $old_instance){
			$instance = array();
			/* Textboxen */
			$instance['title'] = isset($new_instance['title']) ? sanitize_title($new_instance['title'], 'Wetterwarnungen', 'save') : '';
			$instance['ww_widget_titel'] = isset($new_instance['ww_widget_titel']) ? sanitize_text_field($new_instance['ww_widget_titel'], 'Wetterwarnungen', 'save') : '';
			$instance['ww_einleitungstext'] = isset($new_instance['ww_einleitungstext']) ? sanitize_text_field($new_instance['ww_einleitungstext']) : '';
			$instance['ww_hinweistext'] = isset($new_instance['ww_hinweistext']) ? sanitize_text_field($new_instance['ww_hinweistext']) : '';
			$instance['ww_text_feed'] = isset($new_instance['ww_text_feed']) ? sanitize_text_field($new_instance['ww_text_feed']) : '';
			$instance['ww_feed_id'] = isset($new_instance['ww_feed_id']) ? sanitize_key(strtolower($new_instance['ww_feed_id'])) : '';
			$instance['ww_kartengroesse'] = isset($new_instance['ww_kartengroesse']) && is_numeric($new_instance['ww_kartengroesse']) ? (int)$new_instance['ww_kartengroesse'] : (isset($old_instance['ww_kartengroesse']) ? (int)$old_instance['ww_kartengroesse'] : 0);
			$instance['ww_max_meldungen'] = isset($new_instance['ww_max_meldungen']) && is_numeric($new_instance['ww_max_meldungen']) ? (int)$new_instance['ww_max_meldungen'] : (isset($old_instance['ww_max_meldungen']) ? (int)$old_instance['ww_max_meldungen'] : 0);
			
			$map_mapping = wetterwarner_get_map_mapping();

			if (isset($map_mapping[$new_instance['ww_kartenbundesland']])) {
				$mapfilename = $map_mapping[$new_instance['ww_kartenbundesland']];
			} else {
				$mapfilename = "warning_map.webp";
			}
			$widget_id = $this->id;
			$instance['ww_kartenbundeslandURL'] = (string) $mapfilename;
			$instance['ww_kartenbundesland'] = (string) strip_tags($new_instance['ww_kartenbundesland']);
			
			/* Checkboxes */
			$fields = [
			'ww_immer_zeigen',
			'ww_feed_zeigen',
			'ww_gueltigkeit_zeigen',
			'ww_quelle_zeigen',
			'ww_tooltip_zeigen',
			'ww_icons_zeigen',
			'ww_hintergrundfarbe',
			'ww_meldungen_verlinken',
			'ww_stby_icon',
			'ww_doppelte_ausblenden',
			];


			foreach ($fields as $field) {
				if (isset($new_instance[$field])) {
					$instance[$field] = $new_instance[$field] ? 1 : 0;
				}
			}
			
			/* Cache leeren und Daten erneut abrufen */
			wetterwarner_cache_refresh();
			if($instance['ww_feed_id'] == 100)
				$feed_url = 'https://api.it93.de/wetterwarner/100.rss';
			else
				$feed_url = 'https://wettwarn.de/rss/' . $instance['ww_feed_id'] . '.rss';
				
			wetterwarner_get_file($feed_url);
			wetterwarner_get_file('https://api.it93.de/wetterwarner/worker/files/' . $mapfilename);
			
			return $instance;
		}
		/* Aufbau Formular Widget Einstellungen / Default Werte	*/
        public function form($instance) {
			try{
				$instance = wp_parse_args((array) $instance, array(
					'ww_widget_titel' => __('Weather alerts', 'wetterwarner'),
					'ww_text_feed' => __('Weather alerts %region%', 'wetterwarner'),
					'ww_max_meldungen' => '3',
					'ww_feed_id' => 'HAN',
					'ww_einleitungstext' => __('Weather alerts for %region%', 'wetterwarner'),
					'ww_hinweistext' => __('No weather alerts for %region%', 'wetterwarner'),
					'ww_kartengroesse' => '65',
					'ww_kartenbundesland' => 'Niedersachsen',
					'ww_kartenbundeslandURL' => 'warning_map_nib.webp'
				));
				?>
				<p style="border-bottom: 1px solid #DFDFDF;"><strong><?php echo __('Widget Title','wetterwarner'); ?></strong></p>
				<p>
					<input id="<?php echo $this->get_field_id('ww_widget_titel'); ?>" name="<?php echo $this->get_field_name('ww_widget_titel'); ?>" type="text" value="<?php echo $instance['ww_widget_titel']; ?>" size="18"/>
				</p>
					<p style="border-bottom: 1px solid #DFDFDF;"><strong>Feed ID</strong></p>
					<input id="<?php echo $this->get_field_id('ww_feed_id'); ?>" name="<?php echo $this->get_field_name('ww_feed_id'); ?>" type="text" maxlength="3" size="3" value="<?php echo $instance['ww_feed_id']; ?>" />
					<br><p><?php echo __('Get feed ID from https://wettwarn.de/warnregion!','wetterwarner'); ?></p>
				<p style="border-bottom: 1px solid #DFDFDF;"><strong><?php echo __('Options','wetterwarner'); ?></strong></p>
				<table>
					<tr><td><?php echo __('Introduction text','wetterwarner'); ?></td><td><input id="<?php echo $this->get_field_id('ww_einleitungstext'); ?>" name="<?php echo $this->get_field_name('ww_einleitungstext'); ?>" type="text" value="<?php echo $instance['ww_einleitungstext']; ?>" size="20"/></td></tr>
					<tr><td><?php echo __('Information text','wetterwarner'); ?></td><td><input id="<?php echo $this->get_field_id('ww_hinweistext'); ?>" name="<?php echo $this->get_field_name('ww_hinweistext'); ?>" type="text" value="<?php echo $instance['ww_hinweistext']; ?>" size="20"/></td></tr>
					<tr><td><?php echo __('Show Feed-Link','wetterwarner'); ?></td><td><input id="<?php echo $this->get_field_id('ww_feed_zeigen'); ?>" name="<?php echo $this->get_field_name('ww_feed_zeigen'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_feed_zeigen'], true); ?>/></td></tr>
					<tr><td><?php echo __('Feed text','wetterwarner'); ?></td><td><input id="<?php echo $this->get_field_id('ww_text_feed'); ?>" name="<?php echo $this->get_field_name('ww_text_feed'); ?>" type="text" value="<?php echo $instance['ww_text_feed']; ?>" size="20" /></td></tr>
					<tr><td><?php echo __('Max messages','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_max_meldungen'); ?>" name="<?php echo $this->get_field_name('ww_max_meldungen'); ?>" maxlength="2" size="3" type="text" value="<?php echo $instance['ww_max_meldungen']; ?>" /></td></tr>
					<tr><td><?php echo __('Size of map','wetterwarner'); ?></td><td><input id="<?php echo $this->get_field_id('ww_kartengroesse'); ?>" name="<?php echo $this->get_field_name('ww_kartengroesse'); ?>" type="text" maxlength="3" size="3" value="<?php echo $instance['ww_kartengroesse']; ?>" /> <?php echo __('0 = map invisible','wetterwarner'); ?></td></tr>
					<tr><td><?php echo __('Federal state of map','wetterwarner'); ?></td><td>
					<select id="<?php echo $this->get_field_id('ww_kartenbundesland'); ?>" name="<?php echo $this->get_field_name('ww_kartenbundesland'); ?>" value="<?php echo $instance['ww_kartenbundesland']; ?>" > 
					<?php 
						$ww_Bundesländer = ["Deutschland","Baden-Württemberg","Bayern","Berlin","Brandenburg","Bremen","Hamburg","Hessen","Mecklenburg-Vorpommern","Niedersachsen","Nordrhein-Westfalen","Rheinland-Pfalz","Saarland","Sachsen","Sachsen-Anhalt","Schleswig-Holstein","Thüringen"];
						foreach($ww_Bundesländer as $ww_Bundesland)
						{
							echo '<option';
							if ($instance['ww_kartenbundesland'] == $ww_Bundesland)
								echo ' selected';
							echo '>' . $ww_Bundesland . '</option>';
						}
					?>
					</select>
					</td></tr>
					<tr><td><?php echo __('Show validity','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_gueltigkeit_zeigen'); ?>" name="<?php echo $this->get_field_name('ww_gueltigkeit_zeigen'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_gueltigkeit_zeigen'], true); ?>/></td></tr>
					<tr><td><?php echo __('Show source','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_quelle_zeigen'); ?>" name="<?php echo $this->get_field_name('ww_quelle_zeigen'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_quelle_zeigen'], true); ?>/></td></tr>
					<tr><td><?php echo __('Show always','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_immer_zeigen'); ?>" name="<?php echo $this->get_field_name('ww_immer_zeigen'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_immer_zeigen'], true); ?>/></td></tr>
					<tr><td><?php echo __('Create tooltip','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_tooltip_zeigen'); ?>" name="<?php echo $this->get_field_name('ww_tooltip_zeigen'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_tooltip_zeigen'], true); ?>/></td></tr>
					<tr><td><?php echo __('Show Icons','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_icons_zeigen'); ?>" name="<?php echo $this->get_field_name('ww_icons_zeigen'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_icons_zeigen'], true); ?>/></td></tr>
					<tr><td><?php echo __('Show StandBy icon','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_stby_icon'); ?>" name="<?php echo $this->get_field_name('ww_stby_icon'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_stby_icon'], true); ?>/></td></tr>
					<tr><td><?php echo __('Show background color','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_hintergrundfarbe'); ?>" name="<?php echo $this->get_field_name('ww_hintergrundfarbe'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_hintergrundfarbe'], true); ?>/></td></tr>
					<tr><td><?php echo __('Link messages','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_meldungen_verlinken'); ?>" name="<?php echo $this->get_field_name('ww_meldungen_verlinken'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_meldungen_verlinken'], true); ?>/></td></tr>
					<tr><td><?php echo __('Hide dublicates','wetterwarner'); ?><br></td><td><input id="<?php echo $this->get_field_id('ww_doppelte_ausblenden'); ?>" name="<?php echo $this->get_field_name('ww_doppelte_ausblenden'); ?>" type="checkbox" value="1" <?php checked(1, $instance['ww_doppelte_ausblenden'], true); ?>/></td></tr>
				</table>
				<p style="border-bottom: 1px solid #DFDFDF;"></p>
				<?php 
			}
			catch( Exception $e ) {
				echo '<p style="color:red; font-weight:bold">' . __('Sorry, something went wrong.', 'wetterwarner') .'</p>',  $e->getMessage(), "\n";
				echo '<br><br>';
			}
        }
			public function widget($args, $instance) {
				try {
					$options = get_option('wetterwarner_settings');
					extract($args);
					$feed = plugin_dir_url(__FILE__) . "tmp/{$instance['ww_feed_id']}.rss";
					$xml_data = wetterwarner_xml($feed);
					$feed = wetterwarner_meldungen($xml_data, $instance);
					$parameter = wetterwarner_parameter($xml_data, $instance);
					$tooltip_texte = [];

					$output = $args['before_widget'];

					if ($feed[0]['title'] != 'Keine Warnungen') {
						$output .= $args['before_title'] . $parameter->widget_title . $args['after_title'];

						if (isset($parameter->einleitung) && !empty($parameter->einleitung)) {
							$output .= '<span class="ww_einleitung">' . $parameter->einleitung . '</span><br>';
						}

						$output .= '<ul class="ww_wetterwarnungen">';
						$i = $instance['ww_max_meldungen'];
						$cwarnungen = array();
						$info_url = "";
						foreach ($feed as $value) {
							if ($i-- == 0) {
								break;
							}
							
							$shorttitle = (strpos($value['title'], 'VORABINFORMATION')) ? explode("VORABINFORMATION", $value['title'])[1] : trim(explode(":", $value['title'])[1]);
							$vorabinformation = strpos($value['title'], 'VORABINFORMATION');

							if (in_array($shorttitle, $cwarnungen) && $instance['ww_doppelte_ausblenden']) {
								$tooltip = explode("Quelle:", $value['description']);
								$tooltip_texte[$shorttitle] = $tooltip_texte[$shorttitle] . '------------------<br>' . str_replace(array("\r", "\n"), "", $tooltip[0]);
							} else {
								$tooltip = explode("Quelle:", $value['description']);
								$tooltip_texte[$shorttitle] = str_replace(array("\r", "\n"), "", $tooltip[0]);
								$i++;
								array_push($cwarnungen, $shorttitle);
							}
							if(isset($instance['ww_hintergrundfarbe']))
								$hintergrund = wetterwarner_meldung_hintergrund($value, $options);
							$item = '<li class="ww_wetterwarnung"';
							if (isset($hintergrund)) {
								$item .= $hintergrund;
							}
							$item .= '>';
							if ($instance['ww_tooltip_zeigen']) {
								$tooltip_code = wetterwarner_tooltip($tooltip_texte[$shorttitle]);
							}
							$info_url = $value['link'];
							if (isset($instance['ww_meldungen_verlinken']) && $instance['ww_meldungen_verlinken'] && isset($tooltip_code)) {
								$output .= "<a href=\"$info_url\" target=\"_blank\"$tooltip_code>$item";
							} elseif (isset($instance['ww_meldungen_verlinken']) && $instance['ww_meldungen_verlinken']) {
								$output .= "<a href=\"$info_url\" target=\"_blank\">$item";
							} elseif (isset($tooltip_code)) {
								$output .= "<a $tooltip_code>$item";
							} else {
								$output .= $item;
							}

							if (isset($instance['ww_icons_zeigen']) && $instance['ww_icons_zeigen']) {
								$output .= "<i class=\"" . wetterwarner_icons($shorttitle) . "\"></i> ";
							}

							$output .= $shorttitle;

							if (isset($tooltip_code) || isset($info_url)) {
								$output .= '</a>';
							}

							if (isset($vorabinformation) && $vorabinformation) {
								if (isset($instance['ww_icons_zeigen']) && $instance['ww_icons_zeigen']) {
									$output .= "<br><span class=\"ww_Info\"><i class=\"fa fa-info\"></i> " . __('prior information', 'wetterwarner')  . "</span>";
								} else {
									$output .= "<br><span class=\"ww_Info\">" . __('prior information', 'wetterwarner') . "</span>";
								}
							}

							if (isset($instance['ww_gueltigkeit_zeigen'])) {
								$output .= wetterwarner_gueltigkeit($value, $parameter);
							}

							if (isset($instance['ww_quelle_zeigen'])) {
								$output .= wetterwarner_quelle($value);
							}

							$output .= '</li>';
						}

						$output .= '</ul>';
					} else {
						if (isset($instance['ww_immer_zeigen'])) {
							$output .= $args['before_title'] . $parameter->widget_title . $args['after_title'];

							if (isset($instance['ww_hinweistext'])) {
								$hinweis = (strpos($instance['ww_hinweistext'], '%region%')) ? str_replace("%region%", $parameter->region, $instance['ww_hinweistext']) : $instance['ww_hinweistext'];
							}

							if (isset($hinweis)) {
								$output .= '<span class="ww_hinweis">';

								if (isset($instance['ww_icons_zeigen']) and $instance['ww_icons_zeigen'] and (isset($instance['ww_stby_icon'])) and $instance['ww_stby_icon']) {
									$output .=  "<i class=\"wi wi-horizon-alt\" style=\"text-align:center;font-size:30pt;display: inline-block; width: 100%;\"></i><br>";
								}

								$output .= $hinweis . '<br>';
								$output .= '</span>';
							}
						}
					}

					if (isset($instance['ww_immer_zeigen']) or $feed[0]['title'] != 'Keine Warnungen') {
						if ($instance['ww_kartengroesse'] > 0) {
							$output .= wetterwarner_wetterkarte($instance, $args, $parameter->region);
						}

						if (isset($instance['ww_feed_zeigen'])) {
							$output .= wetterwarner_feed_link($instance, $parameter);
						}
					}

					if (isset($output)) {
						$output .= $args['after_widget'];
						echo $output;
					}
				} catch (Exception $e) {
					$title = isset($title) ? $title : "Wetterwarner";
					$output = $args['before_widget'] . $before_title . $title . $after_title;
					$output .= '<p style="color:red; font-weight:bold">' . __('Sorry, something went wrong.', 'wetterwarner') . '</p>' . $e->getMessage() . "\n";
					$output .= $args['after_widget'];
					echo $output;
				}
			}
		}
    }
	/* Widget  registrieren*/
	add_action('widgets_init', 'wetterwarner_init_widget');
	add_action('plugin_action_links_' . plugin_basename( __FILE__ ), 'wetterwarner_action_links' );
	add_action('plugins_loaded', 'wetterwarner_load_textdomain');
	add_action('upgrader_process_complete', 'wetterwarner_upgrade_completed', 10, 2);
	add_action('wetterwarner_data_update', 'wetterwarner_data_update');
	add_filter('debug_information', 'wetterwarner_debug_info' );
	add_filter('site_status_tests', 'wetterwarner_add_konfig_check' );
	add_filter('cron_schedules', 'wetterwarner_cron_schedule');
	register_activation_hook(__FILE__, 'wetterwarner_activation');
	register_deactivation_hook(__FILE__, 'wetterwarner_deactivation');

?>