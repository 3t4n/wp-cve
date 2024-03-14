<?php
if (class_exists('RabbitLoader_21_Tab_Help')) {
    #it seems we have a conflict
    return;
}

class RabbitLoader_21_Tab_Help
{

    public static function init()
    {
        add_settings_section(
            'rabbitloader_section_help',
            ' ',
            '',
            'rabbitloader-help'
        );
    }

    public static function echoMainContent()
    {

        do_settings_sections('rabbitloader-help');
?>
        <div class="" style="max-width: 1160px; margin:40px auto;">
            <div class="row mb-4">
                <div class="col">
                    <div class="bg-white rounded p-4">
                        <div class="row">

                            <div class="col-sm-12 col-md-8 text-secondary">
                                <h5 class="mt-0"><?php RL21UtilWP::_e('Having trouble?'); ?></h5>
                                <span><?php RL21UtilWP::_e('Facing issue with RabbitLoader plugin? Browse our knowledge base for common issues or reach out to our support team at support@rabbitloader.com'); ?></span>

                                <div class="mt-5">
                                    <a target="_blank" class="rl-btn rl-btn-primary mb-1 mb-sm-0" href="https://rabbitloader.com/kb/"><?php RL21UtilWP::_e('Browse Knowledge Base'); ?></a>
                                    <a target="_blank" class="rl-btn rl-btn-outline-primary" href="mailto:support@rabbitloader.com"><?php RL21UtilWP::_e('Contact Support'); ?></a>
                                </div>

                            </div>
                            <div class="col-sm-12 col-md-4 text-center">
                                <img src="<?php echo RABBITLOADER_PLUG_URL; ?>/assets/error.png" class="img-fluid" style="max-height:170px;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="rl_crash_course_videos"></div>
            <?php self::kbContainer(); ?>
        </div>
<?php
    }

    private static function &remoteCategories()
    {
        $args = array('method' => 'GET', 'timeout' => 30);
        $res = wp_remote_request('https://rabbitloader.com/kb/wp-json/wp/v2/categories', $args);
        if (!is_wp_error($res) && ($res['response']['code'] == 200 || $res['response']['code'] == 201)) {
            $catData = json_decode($res['body'], true);
            $posts = self::remotePosts();
            $data = array();
            if (!empty($catData)) {
                foreach ($catData as $cat) {
                    $list = array();
                    if (!empty($posts)) {
                        foreach ($posts as $post) {
                            if ($post['categories'][0] == $cat['id']) {
                                $list[] = array(
                                    'title' => $post['title']['rendered'],
                                    'link' => $post['link']
                                );
                            }
                        }
                    }
                    $data[] = array(
                        'id' => $cat['id'],
                        'category_name' => $cat['name'],
                        'posts' => $list
                    );
                }
            }
        } else {
            $data = "Something went wrong, Please try again later.";
        }
        return $data;
    }

    private static function &remotePosts()
    {
        $args = array(
            'method' => 'GET',
            'timeout' => 30
        );
        $res = wp_remote_request("https://rabbitloader.com/kb/wp-json/wp/v2/posts?per_page=100", $args);
        //Check for success
        if (!is_wp_error($res) && ($res['response']['code'] == 200 || $res['response']['code'] == 201)) {
            $posts = json_decode($res['body'], true);
            return $posts;
        }
    }

    private static function kbContainer()
    {
        $posts = get_transient('rabbitloader_knowlegebase_data');
        if ($posts) {
            self::renderCategory($posts);
        } else {
            $expiryInterval = 7 * 24 * 60 * 60;
            $data = self::remoteCategories();
            if (is_array($data) && !empty($data)) {
                set_transient('rabbitloader_knowlegebase_data', $data, $expiryInterval);
                self::renderCategory($data);
            } else {
                self::renderCategory($data);
            }
        }
    }

    private static function renderCategory($posts)
    {
        if (is_array($posts)) {
            foreach ($posts as $post) {
                $posts = '<ul class="my-0" style="list-style:square;">';
                foreach ($post['posts'] as $data) {
                    $posts .= '<li><a class="text-secondary" href="' . $data['link'] . '" target="_blank" title="Read more" style="text-decoration:none;">' . $data['title']  . '</a></li>';
                }
                $posts .= '</ul>';
                self::getKBBox($post['category_name'] . ' related articles', $posts);
            }
        } else {
            echo '<div class="alert alert-danger text-center" role="alert"> Sorry, Cloud not load knowledge base data. Please try again later.</div>';
        }
    }

    private static function getKBBox($cat_name, $content)
    {
        echo '
        <div class="row mb-4">
            <div class="col">
                <div class="bg-white rounded p-4">
                    <div class="row">
                        <div class="col-sm-12 col-md-8 text-secondary">
                            <h5 class="mb-3">', RL21UtilWP::_e($cat_name), '</h5>
                            ', RL21UtilWP::_e($content), '
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
}
?>