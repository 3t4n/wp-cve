<?php
/**
 * Sheet Controller
 */

namespace FDSUS\Controller;

use FDSUS\Id;
use FDSUS\Lib\Dls\MetaBoxes\MetaBoxes;
use FDSUS\Lib\Dls\Notice;
use FDSUS\Model\MetaBoxes as MetaBoxesModel;
use FDSUS\Model\Settings;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\SignupCollection as SignupCollection;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Lib\Exception;
use WP_Post;
use WP_Query;

class Sheet extends PostTypeBase
{
    public $bodyClasses = array('');

    public function __construct()
    {
        $this->postType = SheetModel::POST_TYPE;

        add_action('init', array(&$this, 'addPostType'), 0);
        add_action('init', array(&$this, 'initMetaboxes'), 0);
        add_filter('dlsmb_update_post_metadata', array(&$this, 'process_tasks'), 10, 4);
        add_filter('posts_join', array(&$this, 'modifyCollectionJoin'), 10, 2);
        add_action('posts_where', array(&$this, 'modifyCollectionWhere'), 10, 2);
        add_filter('the_content', array(&$this, 'modifyTheContent'));
        add_action('gdlr_core_print_page_builder', array(&$this, 'goodlayersWorkaround'), 10, 0);
        add_action('wp', array(&$this, 'maybeAddSheetNotices'), 0);

        parent::__construct();
    }

    /**
     * Add custom post type
     */
    public function addPostType()
    {
        $args = array(
            'labels' => $this->getPostTypeLabels(SheetModel::getName(true), SheetModel::getName()),
            'hierarchical'        => false,
            'supports'            => array(
                'title',
                'editor',
                'revisions',
                'author'
            ),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => SheetModel::getBaseSlug(),
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => array('slug' => SheetModel::getBaseSlug()),
            'capability_type'     => SheetModel::POST_TYPE,
            'capabilities'        => $this->getAddCapsArray(SheetModel::POST_TYPE),
            'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" style="isolation:isolate" viewBox="0 0 24 24" aria-hidden="true"><defs><clipPath id="a"><path d="M0 0H24V24H0z"/></clipPath></defs><g clip-path="url(#a)"><path fill="#a7aaad" d="M8.11 20c-.822-.222-2.083 1.278-3.069 2.056A11.1 11.1 0 0 0 9.315 24v-.056C9.205 22.5 9.918 20.5 8.11 20zM0 12.167c0 3.222 1.26 6.166 3.288 8.333 1.863-2.5 4-4.556 7.123-5.722 1.425-.5 2.685-1.945 3.671-3.278 1.589-2.278 2.904-4.778 4.329-7.167.931-.722 1.863.5 1.26 1.723-.493 1.277-.876 2.555-1.479 3.777-1.754 3.278-2.63 6.723-2.302 10.389.11 1.445.384 2.722-.109 3.5C20.548 22.111 24 17.556 24 12.167 24 5.444 18.63 0 12 0S0 5.444 0 12.167zm5.808-1c.11-1.778 1.48-3.056 3.124-2.945 1.589.111 2.958 1.611 2.849 3.222-.055 1.667-1.534 3-3.178 2.889-1.699-.055-2.85-1.444-2.795-3.166z"/></g></svg>')
        );
        register_post_type(SheetModel::POST_TYPE, $args);
    }

    /**
     * Init metaboxes
     *
     * @todo move non-sheet metabox inits into their proper controller
     */
    public function initMetaboxes()
    {
        $metaBoxesModel = new MetaBoxesModel;
        $metaBoxes = $metaBoxesModel->get();
        foreach ($metaBoxes as $metaBox) {
            new MetaBoxes($metaBox);
        }
    }

    /**
     * Override the saving of tasks as meta data and save as post types instead
     *
     * @param null|bool $check      Whether to allow updating metadata.
     * @param int       $sheetId    Sheet post ID.
     * @param string    $metaKey    Meta key.
     * @param mixed     $metaValue  Meta value. Should be array of task data
     *                              from sheet admin screen
     *
     * @return bool|null If not null, meta field will not be updated by default method
     * @since 2.1
     */
    public function process_tasks($check, $sheetId, $metaKey, $metaValue)
    {
        if ($metaKey !== Id::PREFIX . '_tasks' || !is_array($metaValue)) {
            return null;
        }

        $err = 0;
        $sheet = new SheetModel($sheetId);
        $prevTasks = $sheet->getTasks();
        $toProcess = array(
            'add' => array(),
            'update' => array(),
            'delete' => array(),
        );
        $submittedTaskIds = array();

        /**
         * Filter metaValue at the start of processing of the task save
         *
         * @param array      $metaValue
         * @param SheetModel $sheet
         * @param array      $prevTasks
         *
         * @return array
         * @since 2.2
         */
        $metaValue = apply_filters('fdsus_meta_value_before_process_task', $metaValue, $sheet, $prevTasks);

        // Queue for removal: tasks where the fields were emptied out
        $i = 0;

        foreach ($metaValue as $key => $data) {
            $submittedTaskIds[] = $data['id'];
            $data['sort'] = $i;
            if (empty($data['title'])) {
                if (!empty($data['id'])) {
                    $toProcess['delete'][] = $data['id']; // Delete if title is empty
                }
                continue;
            } elseif (empty($data['id'])) {
                $toProcess['add'][] = $data;
            } else {
                $toProcess['update'][] = $data;
                // Check if qty was reduced
                if ($data['task_row_type'] != 'header') {
                    $signupCollection = new SignupCollection();
                    $signups = $signupCollection->getByTask($data['id']);
                    $signupCount = count($signups);

                    if ($signupCount > $data['qty']) {
                        $err++;
                        if (!empty($err)) {
                            $this->addAdminNotice(
                                sprintf(
                                    /* translators: %1$s is replaced with task name, %2$d is replaced with the current number of signups on that task, %3$s is replaced with "person" or "people" depending on the number of signups  */
                                    esc_html__('The number of spots for task "%1$s" cannot be set below %2$d because it currently has %2$d %3$s signed up. Please clear some spots first before updating this task.', 'fdsus'),
                                    esc_html($data['title']),
                                    $signupCount,
                                    (($signupCount > 1) ? 'people' : 'person')
                                )
                            );
                        }
                    }
                }
            }
            $i++;
        }
        reset( $metaValue );

        // Queue for removal: tasks that are no longer in the list
        /** $prevTasks TaskModel[] */
        if (!empty($prevTasks) && is_array($prevTasks)) {
            foreach ($prevTasks as $task) {
                if (!in_array((string)$task->ID, $submittedTaskIds)) {
                    $toProcess['delete'][] = (string)$task->ID;
                    $signupCount = count($task->getSignups());
                    if ($signupCount > 0) {
                        $err++;
                        if (!empty($err)) {
                            $this->addAdminNotice(
                                sprintf(
                                    /* translators: %1$s is replaced with task name, %2$d is replaced with the current number of signups on that task, %3$s is replaced with "person" or "people" depending on the number of signups  */
                                    esc_html__('The task "%1$s" cannot be removed because it has %2$d %3$s signed up.  Please clear all spots first before removing this task.', 'fdsus'),
                                    $task->post_title,
                                    $signupCount,
                                    ($signupCount > 1) ? 'people' : 'person'
                                )
                            );
                        }
                    }
                }
            }
            reset($prevTasks);
        }

        // Error handling
        if (!empty($err)) {
            return false;
        }

        // Process Add
        foreach ($toProcess['add'] as $data) {
            try {
                // Disable/Re-enable filter to avoid infinite recursion
                remove_filter(current_filter(), array(&$this, __FUNCTION__));
                $taskModel = new TaskModel();
                $taskModel->add($data, $sheetId);
                add_filter(current_filter(), array(&$this, __FUNCTION__), 10, 4);
            } catch (Exception $e) {
                $this->addAdminNotice($e->getMessage());
            }
        }

        // Process Update
        foreach ($toProcess['update'] as $data) {
            // Disable/Re-enable filter to avoid infinite recursion
            remove_filter(current_filter(), array(&$this, __FUNCTION__));
            $taskModel = new TaskModel($data['id']);
            $result = $taskModel->update($data);
            add_filter(current_filter(), array(&$this, __FUNCTION__), 10, 4);

            if (is_wp_error($result)) {
                $this->addAdminNotice($result->get_error_message());
            }
        }

        // Process Delete
        foreach ($toProcess['delete'] as $data) {
            // Disable/Re-enable filter to avoid infinite recursion
            remove_filter(current_filter(), array(&$this, __FUNCTION__));
            $taskModel = new TaskModel();
            $result = $taskModel->delete($data);
            add_filter(current_filter(), array(&$this, __FUNCTION__), 10, 4);

            if (is_wp_error($result)) {
                $this->addAdminNotice($result->get_error_message());
            }
        }

        return true;
    }

    /**
     * Add admin notice
     *
     * Example notice array structure:
     *
     * $notices = array(
     *     'error'      => array(),
     *     'update'     => array(),
     *     'update-nag' => array()
     * );
     *
     * @param string|false $text
     *
     * @todo convert to use Notice class?
     */
    public function addAdminNotice($text)
    {
        if (!is_admin()) return false;

        $notices = get_transient(Id::PREFIX . '_admin_notices');
        if (empty($notices)) {
            $notices = array();
        }
        $notices['error'][] = esc_html($text);
        set_transient(Id::PREFIX . '_admin_notices', $notices, 60 * 60);
    }

    /**
     * Modify collection join
     *
     * @param string   $join    String containing joins
     * @param WP_Query $wpQuery Object
     *
     * @return string
     */
    public function modifyCollectionJoin($join, $wpQuery)
    {
        if (is_admin() || is_single()
            || empty($wpQuery->query['post_type'])
            || $wpQuery->query['post_type'] !== SheetModel::POST_TYPE
        ) {
            return $join;
        }

        global $wpdb;

        // Add in fields to ignore sheet dates in past if not using task date
        if (Id::isPro()) {
            $join .= " LEFT JOIN {$wpdb->postmeta} use_task_dates ON use_task_dates.post_id = {$wpdb->posts}.ID AND use_task_dates.meta_key = 'dlssus_use_task_dates'";
        }
        $join .= "LEFT JOIN {$wpdb->postmeta} sheet_date ON sheet_date.post_id = {$wpdb->posts}.ID AND sheet_date.meta_key = 'dlssus_date'";

        return $join;
    }

    /**
     * Modify collection where
     *
     * @param string   $where
     * @param WP_Query $wpQuery Object
     *
     * @return string
     */
    public function modifyCollectionWhere($where, $wpQuery)
    {
        if (is_admin() || is_single()
            || empty($wpQuery->query['post_type'])
            || $wpQuery->query['post_type'] !== SheetModel::POST_TYPE
        ) {
            return $where;
        }

        // Ignore sheet dates in past if not using task date
        $where .= "AND IF (
            sheet_date.meta_value IS NOT NULL
            AND sheet_date.meta_value <> ''
            AND sheet_date.meta_value < '" . current_time('Ymd') . "'
            " . (Id::isPro() ? "AND (use_task_dates.meta_value <> 'true' OR use_task_dates.meta_value IS NULL)" : '') . "
        , FALSE, TRUE)";

        return $where;
    }

    /**
     * Modify the content
     *
     * @param $content
     *
     * @return string
     */
    function modifyTheContent($content)
    {
        $before = '';
        $after = '';

        if (is_singular() && is_main_query() && get_post_type() === SheetModel::POST_TYPE) {
            // Before
            ob_start();
            fdsus_the_signup_form_response();
            if (dlssus_has_sheet_date() && empty($_GET['task_id'])): ?>
                <p class="dls-sus-sheet-date">
                    <?php esc_html_e('Date', 'fdsus'); ?>:
                    <?php echo dlssus_field('date'); ?>
                </p>
            <?php endif;
            $before = ob_get_clean();

            // After
            ob_start();
            dlssus_get_template_part('content');
            $after = ob_get_clean();

            // Hide normal sheet page content if displaying signup
            if (!empty($_GET['task_id'])) {
                $content = '';
            }
        }
        return $before . $content . $after;
    }

    /**
     * Workaround fix for Goodlayers themes that don't use the_content
     *
     * @return void
     */
    public function goodlayersWorkaround()
    {
        if (!is_singular() || !is_main_query() || get_post_type() !== SheetModel::POST_TYPE) {
            return;
        }

        $template = strtolower(get_option('template'));
        ?>
        <div class="<?php echo esc_attr($template) ?>-content-container <?php echo esc_attr($template) ?>-container">
            <div class="<?php echo esc_attr($template) ?>-content-area <?php echo esc_attr($template) ?>-item-pdlr <?php echo esc_attr($template) ?>-sidebar-style-none clearfix">
                <?php the_content(); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Maybe add sheet notices
     *
     * @return void
     * @global WP_Post $post
     */
    public function maybeAddSheetNotices()
    {
        // Skip if not a sheet post type and doesn't have a shortcode
        if ((get_post_type() != SheetModel::POST_TYPE || !is_singular() || !is_main_query())
            && !has_shortcode(get_the_content(), 'sign_up_sheet')
        ) {
            return;
        }

        // Successful Sign-up
        if (isset($_GET['action']) && $_GET['action'] === 'signup'
            && isset($_GET['status']) && $_GET['status'] === 'success'
            && !empty($_GET['tasks'])
        ) {
            // Check nonce
            if (empty($_GET['_susnonce']) || !wp_verify_nonce($_GET['_susnonce'], 'signup-success-' . $_GET['signups'] . '-tasks-' . $_GET['tasks'])) {
                return;
            }

            $signupIds = explode(',', $_GET['signups']);
            $taskIds = explode(',', $_GET['tasks']);
            foreach ($signupIds as $key => $signupId) {
                $signup = Settings::isReceiptEnabled() ? new SignupModel($signupId) : false;

                $task = new TaskModel($taskIds[$key]);
                if (!$task->isValid()) {
                    continue;
                }

                $this->addBodyClasses('dlssus_signup-success');

                $successMsg = sprintf(
                    /* translators: %s is replaced with the task title */
                    esc_html__('You have been signed up for %s!', 'fdsus'),
                    '<em>' . wp_kses_post($task->post_title) . '</em>'
                );

                /**
                 * Filters the Sign-up Form success notice content.
                 * May display as one or more on a page depending on how may tasks are signed up for
                 *
                 * @param string            $successMsg The content that will be displayed in the notice
                 * @param TaskModel         $task       Task object that was signed up for
                 * @param SignupModel|false $signup     Signup object (optional)
                 *
                 * @api
                 * @since 2.1.5.3
                 */
                $successMsg = apply_filters('fdsus_signup_success_message', $successMsg, $task, $signup);

                Notice::add(
                    'success',
                    $successMsg,
                    false,
                    Id::PREFIX . '-signup-success'
                );
            }
        }
    }
}
