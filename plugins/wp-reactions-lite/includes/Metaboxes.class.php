<?php

namespace WP_Reactions\Lite;

use WP_Reactions\Lite\FieldManager\Switcher;
use WP_Reactions\Lite\FieldManager\Text;

class Metaboxes
{

    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add']);
        add_action('save_post', [$this, 'save_metaboxdata']);
    }

    public static function render()
    {
        return new self();
    }

    function add()
    {
        $screens = ['post', 'page'];
        foreach ($screens as $screen) {
            add_meta_box(
                'wpra_options',
                __('WP Reactions', 'wpreactions-lite'),
                [$this, 'view'],
                $screen
            );
        }
    }

    function isToggleOn($post_id)
    {
        $post_allow = get_post_meta($post_id, '_wpra_show_emojis', true);
        $screen = Config::$current_options['display_where'];
        $current_screen = get_post_type($post_id);
        if (!empty($post_allow) and $post_allow == 'false') {
            return 'false';
        } else if (!empty($post_allow) and $post_allow == 'true') {
            return 'true';
        } else if ($screen == 'both' or $screen == $current_screen) {
            return 'true';
        }
        return 'false';
    }

    function view($post)
    {
        global $wpra_lite, $wpdb;
        if ( Config::$current_options['activation'] == 'false') {
            echo '<p>';
            _e('Please activate plugin globally in WP Reactions > Dashboard to see page/post related options', 'wpreactions-lite');
            echo '</p>';
            return;
        }

        $fake_counts = $wpra_lite->getFakeCounts($post->ID);
        $active_emojis = $wpra_lite->getActiveEmojis();
        ?>

        <button class="wpra-restart-guides">
            <i class="dashicons dashicons-flag"></i> <?php _e('Show Tips', 'wpreactions-lite'); ?>
        </button>
        <div class="wpra-activate-emojis-wrap">
            <?php
            Helper::guide(__('Activate/Deactivate Emoji\'s', 'wpreactions-lite'), 'post-activate-emoji');
            (new Switcher())
                ->setId('wpra_show_emojis')
                ->setLabel(__('Activate/Deactivate Emoji Reactions on this page', 'wpreactions-lite'))
                ->setValue($this->isToggleOn($post->ID))
                ->setChecked('true')
                ->setUnchecked('false')
                ->addClasses('title-inline m-3')
                ->build();
            ?>
        </div>
        <p style="text-align: center;font-size: 24px">
            <?php _e('Set your user reaction counts to the desired number', 'wpreactions-lite'); ?>
        </p>
        <div class="wpra-fake-counts">
            <?php
            Helper::guide('Setting User Counts', 'post-fake-counts');
            foreach ($active_emojis as $emoji_id) {
                ?>
                <div class="col-md-2">
                    <div class="wpra-fake-count-emojis">
                        <img src="<?php echo Helper::getAsset('emojis/svg/' . $emoji_id . '.svg'); ?>">
                    </div>
                    <?php
                    (new Text())
                        ->setType('number')
                        ->setId('wpra_count_' . $emoji_id)
                        ->setValue(isset($fake_counts[ $emoji_id ]) ? $fake_counts[$emoji_id] : 0)
                        ->build();
                    ?>
                </div>
                <?php
            }
            ?>
        </div>

        <?php
        $tbl = Config::$tbl_reacted_users;
	    $reacts_db = $wpdb->get_results(
		    "select emoji_id, count(*) as count from $tbl where bind_id = '$post->ID' group by emoji_id",
		    ARRAY_A
	    );

	    $stats = array_column($reacts_db, 'count', 'emoji_id');

        echo '<h2 class="wpra-inside-metabox-header"><span>Statistics</span></h2>';
        echo '<div class="wpra-stats-wrap">';
        Helper::guide('Statistics', 'post-reaction-stats');
        $i = 0;
        foreach ($active_emojis as $emoji_id) {
	        $count      = isset($stats[$emoji_id]) ? $stats[$emoji_id] : 0;
	        $total      = array_sum( $stats );
	        $percentage = $total == 0 ? 0 : round( $count * 100 / $total, 1 );
	        Helper::getTemplate(
		        'view/admin/metaboxes/stat-single-emoji',
		        [
			        'count'      => $count,
			        'percentage' => $percentage
		        ]
	        );
            $i++;
        }
        echo '</div>';
    }

    function save_metaboxdata($post_id)
    {
        if (array_key_exists('wpra_show_emojis', $_POST)) {
            update_post_meta(
                $post_id,
                '_wpra_show_emojis',
                $_POST['wpra_show_emojis']
            );

	        $counts = [];

	        foreach (Config::$current_options['emojis'] as $emoji_id) {
		        if (array_key_exists('wpra_count_' . $emoji_id, $_POST)) {
			        $counts[$emoji_id] = $_POST['wpra_count_' . $emoji_id];
		        }
	        }

	        update_post_meta(
		        $post_id,
		        '_wpra_start_counts',
		        $counts
	        );
        }
    }
}