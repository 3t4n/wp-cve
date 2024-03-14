<?php

class ConveyThisAdminNotices
{
    public $config;
    public $notice_spam = 0;
    public $notice_spam_max = 2;

    public function __construct( $config = array() )
    {
        add_action( 'admin_init', array( $this, 'mb_admin_notice_ignore' ) );
        add_action( 'admin_init', array( $this, 'mb_admin_notice_temp_ignore' ) );
        add_action('admin_notices', [$this, 'mb_display_admin_notices']);
    }

    public function mb_admin_notices()
    {
        $settings = get_option('mb_admin_notice');

        if( !isset($settings['disable_admin_notices']) || ( isset($settings['disable_admin_notices']) && $settings['disable_admin_notices'] == 0 ))
        {
            if( current_user_can('manage_options') )
            {
                return true;
            }
        }
        return false;
    }

    public function change_admin_notice_conveythis($admin_notices)
    {
        if (!$this->mb_admin_notices()) {
            return false;
        }

        foreach ($admin_notices as $slug => $admin_notice) {

            if ($this->mb_anti_notice_spam()) {
                return false;
            }

            if (isset($admin_notices[$slug]['pages']) && is_array($admin_notices[$slug]['pages'])) {
                if (!$this->mb_admin_notice_pages($admin_notices[$slug]['pages'])) {
                    return false;
                }
            }

            if (!$this->mb_required_fields($admin_notices[$slug]))
            {
                $current_date = current_time("m/d/Y");
                $start = date("m/d/Y");
                $date_array = explode('/', $start);
                $interval = ( isset($admin_notices[$slug]['int']) ? $admin_notices[$slug]['int'] : 0 );
                $date_array[1] += $interval;
                $start = date("m/d/Y", mktime(0, 0, 0, $date_array[0], $date_array[1], $date_array[2]));

                $admin_notices_option = get_option('mb_admin_notice', []);

                if (!array_key_exists($slug, $admin_notices_option)) {
                    $admin_notices_option[$slug]['start'] = $start;
                    $admin_notices_option[$slug]['int'] = $interval;
                    update_option('mb_admin_notice', $admin_notices_option);
                }

                $admin_display_check = ( isset($admin_notices_option[$slug]['dismissed']) ? $admin_notices_option[$slug]['dismissed'] : false );
                $admin_display_start = ( isset($admin_notices_option[$slug]['start']) ? $admin_notices_option[$slug]['start'] : $start );
                $admin_display_msg = ( isset($admin_notices[$slug]['msg']) ? $admin_notices[$slug]['msg'] : '' );
                $admin_display_link = ( isset($admin_notices[$slug]['link']) ? $admin_notices[$slug]['link'] : '' );

                if ($admin_display_check == 0 && strtotime($admin_display_start) <= strtotime($current_date)) {
                    if (strpos($slug, 'promo') === false) {
                        echo '<div class="update-nag mb-admin-notice notice notice-info is-dismissible" style="width:95%!important; display: flex;">
								<div style="max-width: 60px; margin-right: 20px;">
									<p><img src="'.plugins_url("app/widget/images/ceo.jpg",__FILE__).'" style="width:100%; border-radius: 50%;"></p>
								</div>
								<div>
									<strong><p style="font-size:14px !important">' . $admin_display_msg . '</p></strong>
									<strong><ul style="line-height: 2rem;">' . $admin_display_link . '</ul></strong>
								</div>

							</div>';
                    } else {
                        echo '<div class="admin-notice-promo">';
                        echo $admin_display_msg;
                        echo '<ul class="notice-body-promo blue">
									' . $admin_display_link . '
								</ul>';
                        echo '</div>';
                    }
                    $this->notice_spam += 1;
                }
            }
        }
    }

    public function mb_anti_notice_spam()
    {
        if ($this->notice_spam >= $this->notice_spam_max) {
            return true;
        }
        return false;
    }

    public function mb_admin_notice_ignore()
    {
        if (isset($_GET['mb_admin_notice_ignore'])) {
            $admin_notices_option = get_option('mb_admin_notice', array());
            $admin_notices_option[$_GET['mb_admin_notice_ignore']]['dismissed'] = 1;
            update_option('mb_admin_notice', $admin_notices_option);
            $query_str = remove_query_arg('mb_admin_notice_ignore');
            wp_redirect($query_str);
            exit;
        }
    }

    public function mb_admin_notice_temp_ignore()
    {

        if (isset($_GET['mb_admin_notice_temp_ignore'])) {
            $admin_notices_option = get_option('mb_admin_notice', array());
            $current_date = current_time("m/d/Y");
            $date_array = explode('/', $current_date);
            $interval = (isset($_GET['mb_int']) ? filter_var($_GET['mb_int'], FILTER_SANITIZE_NUMBER_INT) : 7);

            $date_array[1] += $interval;
            $new_start = date("m/d/Y", mktime(0, 0, 0, $date_array[0], $date_array[1], $date_array[2]));

            $admin_notices_option[$_GET['mb_admin_notice_temp_ignore']]['start'] = $new_start;
            $admin_notices_option[$_GET['mb_admin_notice_temp_ignore']]['dismissed'] = 0;
            update_option('mb_admin_notice', $admin_notices_option);
            $query_str = remove_query_arg(array('mb_admin_notice_temp_ignore', 'mb_int'));
            wp_redirect($query_str);
            exit;
        }
    }

    public function mb_admin_notice_pages( $pages )
    {
        foreach ($pages as $key => $page) {
            if (is_array($page)) {
                if (isset($_GET['page']) && $_GET['page'] == $page[0] && isset($_GET['tab']) && $_GET['tab'] == $page[1]) {
                    return true;
                }
            } else {
                if ($page == 'all') {
                    return true;
                }
                if (get_current_screen()->id === $page) {
                    return true;
                }
                if (isset($_GET['page']) && $_GET['page'] == $page) {
                    return true;
                }
            }
            return false;
        }
    }

    public function mb_required_fields( $fields )
    {
        if (!isset($fields['msg']) || ( isset($fields['msg']) && empty($fields['msg']) )) {
            return true;
        }
        if (!isset($fields['title']) || ( isset($fields['title']) && empty($fields['title']) )) {
            return true;
        }
        return false;
    }

    public function mb_display_admin_notices()
    {
        $two_week_review_ignore = add_query_arg(['mb_admin_notice_ignore' => 'conveythis_two_week_review']);
        $two_week_review_temp = add_query_arg(['mb_admin_notice_temp_ignore' => 'conveythis_two_week_review', 'int' => 7]);

        $notices['conveythis_two_week_review'] = array(
            'title' => 'Leave A ConveyThis Review?',
            'msg' => 'Hello,<br><br>my name is Alex Buran. I\'m the founder of ConveyThis Translate plugin.<br>If you like this plugin, please write a few words about it at wordpress.org or twitter. Your opinion will help other people.<br><br>Thank you!',
            'link' => '<span class="conveythis-admin-notice"><a href="https://wordpress.org/support/plugin/conveythis-translate/reviews/?filter=5#postform" target="_blank" class="button button-primary conveythis-admin-notice-link">' . 'Rate plugin' . '</a></span>
					<span class="conveythis-admin-notice" style="margin-left: 20px;"><a href="' . $two_week_review_temp . '" class="conveythis-admin-notice-link">' . 'Remind me later' . '</a></span>
					<span class="conveythis-admin-notice" style="margin-left: 20px;"><a href="' . $two_week_review_ignore . '" class="conveythis-admin-notice-link">' . 'Don\'t show anymore' . '</a></span>',
            'later_link' => $two_week_review_temp,
            'int' => 7
        );

        $this->change_admin_notice_conveythis( $notices );
    }
}