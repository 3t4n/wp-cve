<?php
/**
 * [sign_up_sheet] Shortcode Controller
 */

namespace FDSUS\Controller\Scode;

use FDSUS\Id;
use FDSUS\Model\Settings;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\SheetCollection;
use FDSUS\Controller\Base;
use FDSUS\Lib\Dls\Notice;

class SignUpSheet extends Base
{
    /** @var int  */
    public $shortcodeCount = 0;

    public function __construct()
    {
        parent::__construct();
        add_shortcode('sign_up_sheet', array(&$this, 'shortcode'));
    }

    /**
     * Enqueue plugin css and js files
     */
    function addCssAndJsToSignUp()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style(Id::PREFIX . '-style');
        if (Settings::isEmailValidationEnabled()) {
            wp_enqueue_script(Id::PREFIX . '-mailcheck');
        }
        wp_enqueue_script('dlssus-js');
    }

    /**
     * Main shortcode
     *
     * @param array $atts attributes from shortcode call
     *
     * @return string shortcode output
     */
    public function shortcode($atts)
    {
        /**
         * Filter sign_up_sheet shortcode attributes
         *
         * @param array $atts
         *
         * @return array
         * @since 2.2
         */
        $atts = apply_filters('fdsus_scode_sign_up_sheet_attributes', $atts);

        /**
         * @var int|bool   $id
         * @var string     $list_title
         * @deprecated int|string $category_id (as of v2.1 use category_slug instead)
         * @var int|string $category_slug
         * @var string     $list_title_is_category
         */
        extract(
            shortcode_atts(
                array(
                    'id'                     => false,
                    'list_title'             => esc_html__('Current Sign-up Sheets', 'fdsus'),
                    'category_id'            => false, // Pro only - deprecated as of v2.1
                    'category_slug'          => false, // Pro only
                    'list_title_is_category' => false, // Pro only
                ), $atts
            )
        );

        // Prevent shortcode from working if migration didn't finish
        $dbVersion = get_option('dls_sus_db_version');
        if (strpos($dbVersion, '1.0') === 0 || strpos($dbVersion, '2.0') === 0) {
            Notice::add('info', esc_html__('No sheet found, please contact the webmaster.', 'fdsus'), true);
        }

        $this->addCssAndJsToSignUp();

        $args = array(
            'show_backlink' => false,
            'list_title'    => $list_title,
            'sheets'        => array(),
            'above_title'   => '',
        );

        ob_start();

        $forceOneSheet = false;
        if (!empty($_GET['sheet_id'])) {
            $id = $_GET['sheet_id']; // ID overrides shortcode id if defined
        }
        if (!empty($_GET['sheet_id']) || !empty($_GET['task_id'])) {
            $args['show_backlink'] = true;
            $forceOneSheet = true;
        }
        $this->shortcodeCount++;

        // Get sheet id from task id
        if (!empty($_GET['task_id'])) {
            $firstTaskId = (is_array($_GET['task_id'])) ? current($_GET['task_id']) : $_GET['task_id'];
            $task = new TaskModel($firstTaskId);
            $id = $task->post_parent;
        }

        // Display individual sheet
        if ($id !== false || $forceOneSheet !== false) {

            if ($forceOneSheet && $this->shortcodeCount > 1) return null; // Do not process multiple short codes on one page

            global $post;
            $originalPost = $post;
            $sheet = new SheetModel($id, true);
            if (empty($sheet) || (is_a($sheet, 'FDSUS\Model\Sheet') && !$sheet->isVisibleOnFrontend())) {
                $this->locateTemplate('fdsus/sheet-none.php', true, false);
                return ob_get_clean();
            }
            $post = is_a($sheet, 'FDSUS\Model\Sheet') ? $sheet->getData() : $sheet;
            setup_postdata($post);

            $this->locateTemplate('fdsus/sheet.php', true, false, $args);

            $post = $originalPost;
            setup_postdata($post);
            return ob_get_clean();
        }

        /** @var SheetModel[]|false $sheets */
        $sheets = false;

        /**
         * Filter for sheet collection
         *
         * @param SheetCollection $sheetCollection
         * @param array           $atts shortcode attributes
         *
         * @return SheetModel[]
         * @since 2.2
         */
        $sheets = apply_filters('fdsus_scode_sign_up_sheet_collection', $sheets, $atts);

        // Display all active if not already set
        if ($sheets === false) {
            $sheetCollection = new SheetCollection();
            $sheets = $sheetCollection->get();
        }

        if (isset($sheetCollection) && !is_a($sheetCollection, 'SheetCollection')) {
            $sheets = $sheetCollection->posts;
        }

        if (!empty($sheets)) {
            $args['sheets'] = $sheets;
        }
        ?>

        <?php
        // Add content above title (like removal message)
        $args['above_title'] = apply_filters(Id::PREFIX . '_above_listtitle', '');

        /**
         * Filter template arguments for sheet listing
         *
         * @param array           $args template arguments
         * @param SheetModel[]    $sheets
         * @param array           $atts shortcode attributes
         *
         * @return array
         * @since 2.2
         */
        $args = apply_filters('fdsus_scode_sign_up_sheet_template_args', $args, $sheets, $atts);

        $this->locateTemplate('fdsus/sheet-list.php', true, false, $args);

        return ob_get_clean();
    }

}
