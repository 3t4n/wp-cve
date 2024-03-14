<?php

namespace WP_Reactions\Lite;

class Main extends PluginExtension
{

    private $DB;

    function __construct()
    {
        global $wpdb;
        $this->DB = $wpdb;

        register_activation_hook(WPRA_LITE_PLUGIN_PATH . 'wp-reactions-lite.php', array($this, "activation"));

        add_filter('the_content', array($this, 'content_adder'));
        add_action('init', array($this, 'wp_init'));
        add_filter('plugin_action_links_' . WPRA_LITE_PLUGIN_BASENAME, array($this, 'action_links'));

        add_action('plugins_loaded', function () {
            load_plugin_textdomain(
                'wpreactions-lite',
                false,
                dirname(WPRA_LITE_PLUGIN_BASENAME) . '/languages/'
            );
        });

        // init necessary plugin components
        Config::init();
        Update::init();
        Ajax::init();
        AdminPages::init();
        Metaboxes::render();
    }

    // run necessary actions on plugin activation
    function activation()
    {
        if (class_exists('WPRA\App')) {
            deactivate_plugins('wp-reactions-lite/wp-reactions-lite.php');
            $message = '<p style="color:red;margin-bottom: 10px;font-size: 16px;">' . __('Sorry. You have WP Reactions Pro plugin activated. Please disable it first and activate Lite plugin', 'wpreactions-lite') . '</p>';
            $message .= '<a href="' . admin_url('plugins.php') . '">' . __('Back to Plugins', 'wpreactions-lite') . '</a>';
            wp_die($message);
        }
        Activation::start();
    }

    function wp_init()
    {
        $this->loadPluginAssets();
    }

    function loadPluginAssets()
    {
        $hooks = [
            'toplevel_page_wpra-dashboard',
            'wp-reactions_page_wpra-global-options',
            'wp-reactions_page_wpra-support',
            'wp-reactions_page_wpra-pro',
        ];

        $this->enqueueMedia($hooks);

        // add plugin styles
        $this->addAdminAsset('style', 'wpra_admin_bootstrap_css', Helper::getAsset('vendor/bootstrap/css/bootstrap.min.css'), $hooks);
        $this->addAdminAsset('style', 'wpra_admin_css', Helper::getAsset('css/admin.css', true), $hooks);
        $this->addAdminAsset('style', 'wpra_front_css', Helper::getAsset('css/front.css'), $hooks);
        $this->addAdminAsset('style', 'wpra_g_fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700');
        $this->addAdminAsset('style', 'wpra_common_css', Helper::getAsset('css/common.css'));
        $this->addAdminAsset('style', 'wpra_post_css', Helper::getAsset('css/post.css'));
        $this->addAdminAsset('style', 'wpra_minicolor_css', Helper::getAsset('vendor/minicolor/jquery.minicolors.css'), $hooks);

        // add plugin scripts

        $this->addAdminAsset(
            'script',
            'wpra_minicolor_js',
            Helper::getAsset('vendor/minicolor/jquery.minicolors.min.js'),
            $hooks,
            ['jquery']
        );
        $this->addAdminAsset(
            'script',
            'wpra_front_js',
            Helper::getAsset('js/front.js', true),
            $hooks,
            ['jquery']
        );
        $this->addAdminAsset(
            'script',
            'jquery.ui.touch-punch.min',
            Helper::getAsset('vendor/jquery.ui.touch-punch.min.js'),
            $hooks
        );

        $this->addAdminAsset(
            'script',
            'wpra_lottie',
            Helper::getAsset('vendor/lottie/lottie.min.js'),
            $hooks,
            ['jquery']
        );

        $this->addAdminAsset(
            'script',
            'wpra_post_js',
            Helper::getAsset('js/post.js'),
            ['post-new.php', 'post.php']
        );

        $locals = [
            'object' => 'wpra',
            'vars' => [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'msg_options_updated' => __('Options updated successfully', 'wpreactions-lite'),
                'msg_options_updating' => __('Updating options...', 'wpreactions-lite'),
                'msg_getting_preview' => __('Getting preview...', 'wpreactions-lite'),
                'msg_resetting_options' => __('Resetting to factory settings...', 'wpreactions-lite'),
                'msg_reset_done' => __('Factory settings have been successfully updated...', 'wpreactions-lite'),
                'msg_reset_confirm' => __('Are you sure you want to reset to our factory settings?', 'wpreactions-lite'),
                'default_options' => Config::$default_options,
                'current_options' => Config::$current_options,
                'global_lp' => Helper::getAdminPage('global'),
                'global_prev_step' => __('Prev', 'wpreactions-lite'),
                'global_next_step' => __('Next', 'wpreactions-lite'),
                'global_go_back' => __('Go Back', 'wpreactions-lite'),
                'global_start_over' => __('Start Over', 'wpreactions-lite'),
                'emojis_path' => WPRA_LITE_PLUGIN_URL . 'assets/emojis/',
                'version' => WPRA_LITE_VERSION,
            ]
        ];

        $this->addAdminAsset(
            'script',
            'wpra_admin_js',
            Helper::getAsset('js/admin.js', true),
            $hooks,
            ['jquery', 'wpra_front_js'],
            $locals
        );

        // add plugin frontend styles
        $this->addFrontAsset('style', 'wpra_front_css', Helper::getAsset('css/front.css', true));
        $this->addFrontAsset('style', 'wpra_common_css', Helper::getAsset('css/common.css', true));

        $vars = [
            'object' => 'wpra',
            'vars' => [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'emojis_path' => WPRA_LITE_PLUGIN_URL . 'assets/emojis/',
                'version' => WPRA_LITE_OPTIONS,
                'social_platforms' => Config::SOCIAL_PLATFORMS,
            ]
        ];
        $this->addFrontAsset('script', 'wpra_front_js', Helper::getAsset('js/front.js'), ['jquery'], $vars);
        $this->addFrontAsset('script', 'wpra_lottie', Helper::getAsset('vendor/lottie/lottie.min.js'));

        $this->flushAssets();
    }

    function isEmojisShown()
    {
        global $post;

        $screens = ['page', 'post'];
        $current_screen = get_post_type($post);

        if (!is_singular($screens)) {
            return false;
        }

        if ( Config::$current_options['activation'] == 'false') {
            return false;
        }

        $allow_emojis = get_post_meta($post->ID, '_wpra_show_emojis', true);
        $user_screen = Config::$current_options['display_where'];

        if (!empty($allow_emojis) and $allow_emojis == 'true') {
            return true;
        }

        if (!empty($allow_emojis) and $allow_emojis == 'false') {
            return false;
        }

        if (($user_screen == 'both' or $user_screen == $current_screen)) {
            return true;
        }

        return false;
    }

    function content_adder($content)
    {
        if (!$this->isEmojisShown()) {
            return $content;
        }

        $result = '';

        $reactions = Shortcode::build(Config::$current_options);
	    $reactions = str_replace(["\r", "\n", "\r\n"], '', $reactions);

	    $before = '';
        $after = '';

        if ( Config::$current_options['content_position'] == 'before') {
            $before = $reactions;
        } else if ( Config::$current_options['content_position'] == 'after') {
            $after = $reactions;
        } else {
            $before = $reactions;
            $after = $reactions;
        }

        $result .= $before . $content . $after;
        return $result;
    }

    function getActiveEmojis()
    {
        return array_diff(Config::$current_options['emojis'], [-1]);
    }

	function getFakeCounts( $bind_id ) {
		$fake_counts = get_post_meta( $bind_id, '_wpra_start_counts', true );
		if ( is_array( $fake_counts ) and ! empty( $fake_counts ) ) {
			return array_map( 'intval', $fake_counts );
		}

		return [];
	}

	function getCountsTotal( $bind_id ) {
        $fake_counts = $this->getFakeCounts( $bind_id );
        $emojis = Config::$current_options['emojis'];
        $tbl = Config::$tbl_reacted_users;

		$emojis_in = '(' . implode(',' , $emojis) . ')';

		$db_counts = $this->DB->get_results(
			"select emoji_id, count(*) as count from $tbl where bind_id = '$bind_id' and emoji_id in $emojis_in group by emoji_id",
			ARRAY_A
		);

		$db_counts = array_column($db_counts, 'count', 'emoji_id');

		$result = [];
		foreach ($emojis as $emoji_id) {
			$fake_count = isset($fake_counts[$emoji_id]) ? intval($fake_counts[$emoji_id]) : 0;
			$db_count = isset($db_counts[$emoji_id]) ? intval($db_counts[$emoji_id]) : 0;
			$result[$emoji_id] = $fake_count + $db_count;
		}

		return $result;
	}

    function make_doc_links()
    {
        ob_start();
        foreach (Config::DOCS as $doc) { ?>
            <li>
                <a target="_blank" href="<?php echo $doc['url']; ?>"><?php echo $doc['name']; ?></a>
                <span class="dashicons dashicons-external"></span>
            </li>
            <?php
        }
        $out = '<ul>' . ob_get_clean() . '</ul>';
        echo $out;
    }

    function action_links($links)
    {
        $links[] = '<a href="' . Helper::getAdminPage('global') . '">' . __('Settings', 'wpreactions-lite') . '</a>';
        $links[] = '<a href="' . Helper::getAdminPage('support') . '">' . __('Support', 'wpreactions-lite') . '</a>';
        return $links;
    }

} // end of WP Emoji class
