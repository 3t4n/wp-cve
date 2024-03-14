<?php
/**
 * Admin Page: Edit Sign-up
 */

namespace FDSUS\Controller\Admin;

use FDSUS\Id;
use FDSUS\Lib\Dls\Notice;
use FDSUS\Model\Data;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\States as StatesModel;
use FDSUS\Model\SignupFormInitialValues;
use FDSUS\Model\Settings;
use WP_Screen;
use WP_User;
use FDSUS\Lib\Exception;

class EditSignupPage extends PageBase
{
    /** @var string */
    protected $menuSlug = 'fdsus-edit-signup';

    public function __construct()
    {
        $this->data = new Data();
        add_action('admin_menu', array(&$this, 'menu'));
        add_action('current_screen', array(&$this, 'maybeProcessEditSignup'));
        add_action('current_screen', array(&$this, 'maybeProcessAddSignup'));
        add_action('current_screen', array(&$this, 'maybeDisplayNotice'));
        add_action('fdsus_signup_form_last_fields', array(&$this, 'addFieldsToForm'), 10, 2);

        parent::__construct();
    }

    /**
     * Menu
     */
    public function menu()
    {
        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);

        add_submenu_page(
            '',
            esc_html__('Edit Sign-up', 'fdsus'),
            '',
            $caps['edit_posts'],
            $this->menuSlug,
            array(&$this, 'page')
        );
    }

    /**
     * Page
     */
    public function page()
    {
        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);
        if (!current_user_can($caps['edit_posts'])) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.'));
        }

        $signup = null;

        if (!empty($_GET['signup'])) {
            $signup = new SignupModel($_GET['signup']);
            if (!$signup->isValid()) {
                wp_die(__('Sign-up invalid', 'fdssus'));
            }
        }

        $task = new TaskModel(!empty($_GET['task']) ? $_GET['task'] : $signup->post_parent);
        if (!$task->isValid()) {
            wp_die(__('Task invalid', 'fdssus'));
        }

        $sheet = new SheetModel($task->post_parent);
        if (!$sheet->isValid()) {
            wp_die(__('Sheet invalid', 'fdssus'));
        }
        ?>

        <div class="wrap dls_sus">
            <h1 class="wp-heading-inline">
                <?php echo $_GET['action'] === 'add'
                    ? esc_html__('Add Sign-up', 'fdsus')
                    : esc_html__('Edit Sign-up', 'fdsus');
                ?>
            </h1>

        <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content" style="position: relative;">

            <?php
            $initial = new SignupFormInitialValues($sheet, $task, $signup, $_POST);
            $initialArray = $initial->get();
            $initialArray['user_id'] = isset($signup->dlssus_user_id) ? $signup->dlssus_user_id : '';
            $states = new StatesModel;
            $args = array(
                'sheet'              => $sheet,
                'task_id'            => $task->ID,
                'signup_titles_str'  => '',
                'initial'            => $initialArray,
                'multi_tag'          => '',
                'states'             => $states->get(),
                'submit_button_text' => __('Submit', 'fdsus'),
                'go_back_url'        => '',
            );

            $located = Id::getPluginPath() . 'theme-files' . DIRECTORY_SEPARATOR . 'fdsus' . DIRECTORY_SEPARATOR . 'sign-up-form.php';
            load_template($located, true, $args);
            ?>
        </div>
            <div id="postbox-container-1" class="postbox-container">
                <div class="fdsus-edit-quick-info" role="group"
                     aria-label="<?php esc_attr_e('Sheet Quick Info', 'fdsus') ?>">
                    <span class="quick-info-item quick-info-id"><strong><?php esc_html_e(
                                'Sheet ID', 'fdsus'
                            ) ?>: </strong> <code><?php echo $sheet->ID ?></code></span>
                    <?php do_action('fdsus_edit_sheet_quick_info', $sheet->getData()); ?>
                </div>

                <div class="postbox ">
                    <div class="postbox-header"><h2>Sheet and Task Info</h2></div>
                    <div class="inside">
                        <dl>
                            <dt><?php _e('Sheet', 'fdsus'); ?>:</dt>
                            <dd><?php echo wp_kses_post($sheet->post_title); ?></dd>

                            <dt><?php esc_html_e('Date', 'fdsus'); ?>:</dt>
                            <dd>
                                <?php echo(empty($sheet->dlssus_date)
                                    ? esc_html__('N/A', 'fdsus')
                                    : date(get_option('date_format'), strtotime($sheet->dlssus_date))
                                ); ?>
                            </dd>

                            <dt><?php _e('Task', 'fdsus'); ?>:</dt>
                            <dd><?php esc_html_e($task->post_title); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        </div>

        </div><!-- .wrap -->
        <?php
    }

    /**
     * Maybe process display notice
     *
     * @param WP_Screen $currentScreen Current WP_Screen object.
     *
     * @return void
     */
    public function maybeDisplayNotice($currentScreen)
    {
        if (empty($_GET['notice']) || !$this->isManageSignupsScreen($currentScreen)) {
            return;
        }

        Notice::instance();

        switch($_GET['notice']) {
            case 'edited':
                Notice::add('success', esc_html__('Sign-up updated.', 'fdsus'));
                break;
            case 'added':
                Notice::add('success', esc_html__('Sign-up added.', 'fdsus'));
                break;
        }

    }

    /**
     * Maybe process edit sign-up
     *
     * @param WP_Screen $currentScreen Current WP_Screen object
     *
     * @return void
     */
    public function maybeProcessEditSignup($currentScreen)
    {
        if (empty($_GET['action']) || $_GET['action'] !== 'edit' || !$this->isCurrentScreen($currentScreen)) {
            return;
        }

        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);
        if (!current_user_can($caps['edit_posts'])) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'fdsus'));
        }

        if (empty($_GET['signup'])) {
            wp_die(esc_html__('Sign-up ID missing', 'fdsus'));
        }

        if (!empty($_POST)) {
            if (
                !isset($_POST['signup_nonce'])
                || !wp_verify_nonce($_POST['signup_nonce'], 'fdsus_signup_submit')
            ) {
                wp_die(esc_html__('Sign-up nonce not valid', 'fdsus'));
            }

            Notice::instance();

            // Update signup
            $signup = new SignupModel($_GET['signup']);

            if (!$signup->isValid()) {
                Notice::add('error', esc_html__('Sign-up not found.', 'fdss'));
                return;
            }

            try {
                $signup->update(0, $_POST, true);

                $task = new TaskModel($signup->post_parent);
                $sheet = new SheetModel($task->post_parent);

                // Error Handling
                if (is_array($missingFieldNames = SignupModel::validateRequiredFields($_POST, $sheet))) {
                    throw new Exception(
                        sprintf(
                        /* translators: %s is replaced with a comma separated list of all missing required fields */
                            esc_html__('Please complete the following required fields: %s', 'fdsus'),
                            implode(', ', $missingFieldNames)
                        )
                    );
                }

                wp_redirect(add_query_arg(
                    array('notice' => 'edited'),
                    Settings::getManageSignupsPageUrl($_GET['sheet'])
                ));
            } catch (Exception $e) {
                Notice::add('error', esc_html($e->getMessage()));
            }
        }
    }

    /**
     * Maybe process add sign-up
     *
     * @param WP_Screen $currentScreen Current WP_Screen object
     *
     * @return void
     */
    public function maybeProcessAddSignup($currentScreen)
    {
        if (empty($_GET['action']) || $_GET['action'] !== 'add' || !$this->isCurrentScreen($currentScreen)) {
            return;
        }

        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);
        if (!current_user_can($caps['edit_posts'])) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'fdsus'));
        }

        Notice::instance();

        if (empty($_GET['task'])) {
            wp_die(esc_html__('Task-up ID missing', 'fdsus'));
        }

        if (!empty($_POST)) {
            if (
                !isset($_POST['signup_nonce'])
                || !wp_verify_nonce($_POST['signup_nonce'], 'fdsus_signup_submit')
            ) {
                wp_die(esc_html__('Sign-up nonce not valid', 'fdsus'));
            }

            // Add signup
            $signup = new SignupModel();

            try {
                $signup->add($_POST, $_GET['task'], true);

                $task = new TaskModel($_GET['task']);
                $sheet = new SheetModel($task->post_parent);

                // Error Handling
                if (is_array($missingFieldNames = SignupModel::validateRequiredFields($_POST, $sheet))) {
                    throw new Exception(
                        sprintf(
                        /* translators: %s is replaced with a comma separated list of all missing required fields */
                            esc_html__('Please complete the following required fields: %s', 'fdsus'),
                            implode(', ', $missingFieldNames)
                        )
                    );
                }

                wp_redirect(
                    add_query_arg(
                        array('notice' => 'added'),
                        Settings::getManageSignupsPageUrl($_GET['sheet'])
                    )
                );
            } catch (Exception $e) {
                Notice::add('error', esc_html($e->getMessage()));
            }
        }
    }

    /**
     * Action run to add additional fields to the sign-up form
     *
     * @param SheetModel $sheet
     * @param array      $args
     */
    public function addFieldsToForm($sheet, $args)
    {
        if (!is_admin()) {
            return;
        }

        /** @var WP_User[] $users */
        $users = get_users();
        ?>
        <p class="fdsus-user">
            <label for="signup_user_id" class="signup_user_id">
                <?php esc_html_e('Linked User', 'fdsus'); ?>
            </label>
            <select id="signup_user_id" class="signup_user_id" name="signup_user_id">
                <option value=""></option>
                <?php
                foreach ($users as $user) {
                    $selected = ($args['initial']['user_id'] == $user->ID) ? ' selected="selected"' : null;
                    echo sprintf('<option value="%s"%s>%s</option>', $user->ID, $selected, $user->user_login . ' (' . $user->display_name . ')');
                }
                ?>
            </select>
        </p>
        <?php
    }

    /**
     * Is the screen the manage signups screen
     *
     * @param WP_Screen $currentScreen Current WP_Screen object
     *
     * @return bool
     */
    protected function isManageSignupsScreen($currentScreen)
    {
        return $currentScreen->id === SheetModel::POST_TYPE . '_page_fdsus-manage';
    }
}
