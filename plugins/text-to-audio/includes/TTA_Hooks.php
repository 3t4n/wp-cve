<?php
namespace TTA;

/**
 * Fired during plugin activation
 *
 * @link       http://azizulhasan.com
 * @since      1.0.0
 *
 * @package    TTA
 * @subpackage TTA/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TTA
 * @subpackage TTA/includes
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 */
class TTA_Hooks {

    public function __construct() {
        // TODO it should work with new functionality 
        add_action('add_meta_boxes', array($this, 'add_custom_meta_box'));

        // Update hook
        // add_action('upgrader_process_complete', 'update_settings_data', 10, 2);

    }


    /**
     * Upgrader process complete.
     *
     * @see \WP_Upgrader::run() (wp-admin/includes/class-wp-upgrader.php)
     * @param \WP_Upgrader $upgrader_object
     * @param array $hook_extra
     * @see https://wordpress.stackexchange.com/questions/144870/wordpress-update-plugin-hook-action-since-3-9
     */
    function update_settings_data(\WP_Upgrader $upgrader_object, $hook_extra){
        // get current plugin version. ( https://wordpress.stackexchange.com/a/18270/41315 )
        if(!function_exists('get_plugin_data')){
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        // https://developer.wordpress.org/reference/functions/get_plugin_data/
        $plugin_data = get_plugin_data(TEXT_TO_AUDIO_ROOT_FILE);
        $plugin_version = ($plugin_data['Version'] ?? 'unknown.version');
        unset($plugin_data);

        if (
            is_array($hook_extra) && 
            array_key_exists('action', $hook_extra) && 
            $hook_extra['action'] == 'update'
        ) {
            if (
                array_key_exists('type', $hook_extra) && 
                $hook_extra['type'] == 'plugin'
            ) {
                // if updated the plugins.
                $this_plugin = plugin_basename(TEXT_TO_AUDIO_ROOT_FILE);
                $this_plugin_updated = false;
                if (array_key_exists('plugins', $hook_extra)) {
                    // if bulk plugin update (in update page)
                    foreach ($hook_extra['plugins'] as $each_plugin) {
                        if ($each_plugin === $this_plugin) {
                            $this_plugin_updated = true;
                            break;
                        }
                    }// endforeach;
                    unset($each_plugin);
                } elseif (array_key_exists('plugin', $hook_extra)) {
                    // if normal plugin update or via auto update.
                    if ($this_plugin === $hook_extra['plugin']) {
                        $this_plugin_updated = true;
                    }
                }
                if ($this_plugin_updated === true) {
                    // if this plugin is just updated.
                    // do your task here.
                    // DON'T process anything from new version of code here, because it will work on old version of the plugin.
                    // please read again!! the code run here is not new (just updated) version but the version before that.

                    // 
                    
                    $settings =  (array) get_option( 'tta_settings_data' , [] );
                    $data = (object) array_merge( $settings,  array(
                        'tta__settings_enable_button_add'=> true,
                        "tta__settings_allow_listening_for_post_types" => ['post'],
                        "tta__settings_display_btn_icon" => '',
                    ));

                    update_option( 'tta_settings_data', $data );
                }
            } elseif (
                array_key_exists('type', $hook_extra) && 
                $hook_extra['type'] == 'theme'
            ) {
                // if updated the themes.
                // same as plugin, the bulk theme update will be set the name in $hook_extra['themes'] as 'theme1', 'theme2'.
                // normal update or via auto update will be set the name in $hook_extra['theme'] as 'theme1'.
            }
        }// endif; $hook_extra
    }

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
            // do something here
    }
    /**
     * Register MetaBox to add PDF Download Button
     */
    public function add_custom_meta_box() {
        $plugin_name = 'Text To Speech TTS';
        if(\is_pro_active()) {
            $plugin_name = 'Text To Speech Pro';
        }
        add_meta_box(
                'wps22-meta-box',
                $plugin_name,
                array(
                    $this,
                    'tta_meta_box',
                ),
                get_current_screen()->post_type,
                'side',
                'high',
                null
            );

    }

    /**
     * Add meta box for record, re-record, listen content with loud.
     */
    public function tta_meta_box() {

        // $listening = (array) get_option('tta_listening_settings');
        // $listening = json_encode($listening);
        $customize = (array) get_option('tta_customize_settings');
        // $button_text_arr =  apply_filters( 'tta__button_text_arr', get_option( 'tta__button_text_arr') );
        
        // Button style.
        if (isset($customize) && count($customize)) {
            $btn_style = 'background-color:#184c53;color:#fff;border:0;';
        }
        $short_code = '[tta_listen_btn]';
        if (isset($customize['tta_play_btn_shortcode']) && '' != $customize['tta_play_btn_shortcode']) {
            $short_code = $customize['tta_play_btn_shortcode'];
        }
        \do_action('tts_before_metabox_content');
        ?>
        <div class="tta_metabox">
            
            <input
                type="text"
                name="tta_play_btn_shortcode"
                id="tta_play_btn_shortcode"
                value="<?php echo esc_attr($short_code) ?>"
                title="Short code"
            />

            <!-- Copy Button -->
            <button type="button" style='<?php echo esc_attr($btn_style); ?>;cursor: copy;margin-top:10px;padding:6px;' onclick="copyshortcode()">
            <span class="dashicons dashicons-admin-page"></span>
            </button>

            <script>
                const unsecuredCopyToClipboard = (text) => {
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    textArea.setSelectionRange(0, 99999);
                    try {
                        document.execCommand('copy')
                        alert('Copied')
                    }
                    catch (err) {
                        console.error('Unable to copy to clipboard', err)
                    }

                    document.body.removeChild(textArea)
                    };
            /**
             * Copy short Code
             */
            function copyshortcode () {
                /* Get the text field */
                var copyText = document.getElementById("tta_play_btn_shortcode");

                /* Select the text field */
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                if (window.isSecureContext && navigator.clipboard) {
                    /* Copy the text inside the text field */
                    navigator.clipboard
                    .writeText(copyText.value)
                    .then(() => {
                        alert('Copied')
                    })
                    .catch((e) => {
                        alert("Something went wrong! " + e);
                        // toast('Something went wrong! ');
                    });
                } else {
                    unsecuredCopyToClipboard(copyText.value);
                }
            };
            </script>
        </div>
        <?php
        \do_action('tts_after_metabox_content');
}

}
new TTA_Hooks();