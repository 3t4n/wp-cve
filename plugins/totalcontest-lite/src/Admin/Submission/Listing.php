<?php /** @noinspection SqlNoDataSourceInspection */

namespace TotalContest\Admin\Submission;


use TotalContest\Contest\Repository as ContestsRepository;
use TotalContest\Submission\Model;
use TotalContest\Submission\Repository as SubmissionsRepository;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Export\ColumnTypes\DateColumn;
use TotalContestVendors\TotalCore\Export\ColumnTypes\NumericColumn;
use TotalContestVendors\TotalCore\Export\ColumnTypes\TextColumn;
use TotalContestVendors\TotalCore\Export\Spreadsheet;
use TotalContestVendors\TotalCore\Export\Writers\CsvWriter;
use TotalContestVendors\TotalCore\Export\Writers\HTMLWriter;
use TotalContestVendors\TotalCore\Export\Writers\JsonWriter;
use TotalContestVendors\TotalCore\Helpers\Tracking;
use WP_Query;

/**
 * Class Listing
 * @package TotalContest\Admin\Submission
 */
class Listing
{
    public $contestId;
    public $contestRepository;
    public $submissionsRepository;
    public $contest;
    public $request;

    public function __construct(
        Request $request,
        ContestsRepository $contestRepository,
        SubmissionsRepository $submissionsRepository
    ) {
        global $screen;

        $this->request = $request;
        $this->contestRepository = $contestRepository;
        $this->submissionsRepository = $submissionsRepository;
        // Get current contest ID
        $this->contestId = $this->request->query('contest');
        $this->contest = $contestRepository->getById($this->contestId);

        // Setup hooks
        add_filter('parse_query', [$this, 'filter']);
        add_filter('admin_url', [$this, 'addNewUrl'], 10, 3);
        add_filter('restrict_manage_posts', [$this, 'managePosts']);
        add_filter('manage_contest_submission_posts_columns', [$this, 'columns']);
        add_action('manage_contest_submission_posts_custom_column', [$this, 'columnsContent'], 10, 2);
        add_filter('manage_edit-contest_submission_sortable_columns', [$this, 'columnsSortable'], 10, 1);
        add_filter('parent_file', [$this, 'parentMenu']);
        add_filter('submenu_file', [$this, 'subMenu']);
        add_filter('post_row_actions', [$this, 'actions'], 10, 2);
        add_filter('display_post_states', [$this, 'states'], 10, 2);
        add_filter('pre_get_posts', [$this, 'scope'], 10, 1);

        add_action('restrict_manage_posts', [$this, 'filterByContest']);
        add_action('admin_enqueue_scripts', [$this, 'assets']);
        add_action('admin_footer', [$this, 'templates']);


        if ($this->contestId):
            add_action('manage_posts_extra_tablenav', [$this, 'exportButtons']);
        endif;
    }

    public function filter($query)
    {
        if ($this->contestId):
            $query->query_vars['post_parent'] = $this->contestId;
        endif;
    }

    public function filterByContest()
    {
        global $post_type, $wpdb;

        if ($post_type === TC_SUBMISSION_CPT_NAME) {

            $query = $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type=%s AND post_status=%s ORDER BY 'post_date' DESC", TC_CONTEST_CPT_NAME, 'publish');

            $contests = $wpdb->get_results($query);
            $old = TotalContest('http.request')->query('contest', 0);
            ?>
            <select name="contest">
                <option value="0"><?php  esc_html_e('Select Contest', 'totalcontest') ?></option>
                <?php foreach ($contests as $contest): ?>
                    <option <?php if ($contest->ID == $old): ?>selected<?php endif; ?>
                            value="<?php echo esc_attr($contest->ID); ?>"><?php echo esc_html($contest->post_title) ?></option>
                <?php endforeach; ?>
            </select>
            <?php
        }
    }

    public function exportButtons($position)
    {
        if ($position === 'top'):
            require_once __DIR__ . '/Views/export.php';
        endif;
    }

    public function managePosts()
    {
        echo "<input type=\"hidden\" name=\"contest\" value=\"{$this->contestId}\">";
    }

    public function addNewUrl($url, $path, $blogId)
    {
        if ($path === 'post-new.php?post_type=contest_submission'):
            return "post-new.php?post_type=contest_submission&contest={$this->contestId}";
        endif;

        return $url;
    }

    public function parentMenu()
    {
        return 'edit.php?post_type=' . TC_CONTEST_CPT_NAME;
    }

    public function subMenu()
    {
        return 'edit.php?post_type=' . TC_SUBMISSION_CPT_NAME;
    }

    public function columns($columns)
    {
        return [
            'inline-preview' => esc_html__('Preview', 'totalcontest'),
            'cb' => '<input type="checkbox" />',
            'title' => esc_html__('Title', 'totalcontest'),
            'taxonomy-' . TC_SUBMISSION_CATEGORY_TAX_NAME => esc_html__('Category', 'totalcontest'),
            'votes' => esc_html__('Votes', 'totalcontest'),
            'views' => esc_html__('Views', 'totalcontest'),
            'author' => esc_html__('Author', 'totalcontest'),
            'date' => esc_html__('Date', 'totalcontest'),
        ];
    }

    public function columnsContent($column, $id)
    {
        /**
         * @var Model
         */
        $submission = $this->submissionsRepository->getById($id);

        if (!$submission || !$submission->getContest()):
            return null;
        endif;

        $submission = $submission->toArray() + ['fields' => $submission->getVisibleFields()];

        foreach ($submission['fields'] as $fieldIndex => $field):
            if (is_array($field)):
                if (isset($field['file']) && is_array($field['file'])) {
                    $field['file'] = implode(', ', (array)$field['file']);
                }
                $submission['fields'][$fieldIndex] = implode(', ', $field);
            endif;
        endforeach;

        $submission['preview'] = do_shortcode($submission['preview']);

        if ($column === 'inline-preview'):
            printf('<script type="text/javascript">TotalContest.submissions[%d] = %s</script>', $id,
                json_encode($submission, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            printf('<button class="button" type="button"><span class="dashicons dashicons-visibility"></span></button>');
        endif;

        if ($column === 'votes'):
            echo esc_html($submission['votes']['count']);
        endif;

        if ($column === 'views'):
            echo esc_html(get_post_meta($submission['id'], '_tc_views', true));
        endif;
    }

    public function columnsSortable($columns)
    {
        $columns['id'] = 'id';
        $columns['votes'] = 'votes';
        $columns['views'] = 'views';

        return $columns;
    }

    public function assets()
    {
        wp_enqueue_script('totalcontest-admin-submissions-listing');
        wp_enqueue_style('totalcontest-admin-submissions-listing');
    }

    public function actions($actions, $post)
    {
        $contestPostType = TC_CONTEST_CPT_NAME;
        if ($post->post_status != 'publish' && current_user_can('publish_contest_submissions', $post->ID, $post->post_parent)):
            $actions = [
                    'confirm' => sprintf('<a href="%s" target="_blank">%s</a>',
                        esc_attr(wp_nonce_url(admin_url("admin-ajax.php?action=totalcontest_contests_approve_submission&submission={$post->ID}"), 'totalcontest')),
                        esc_html(esc_html__('Approve', 'totalcontest')))
                ] + $actions;
        endif;

        $actions['contest'] = sprintf('<a href="%s">%s</a>',
            esc_attr(admin_url("post.php?post={$post->post_parent}&action=edit")),
            esc_html(esc_html__('Contest', 'totalcontest')));

        if (current_user_can('manage_options')):
            $actions['log'] = sprintf('<a href="%s">%s</a>',
                esc_attr(admin_url("edit.php?post_type={$contestPostType}&page=log&contest={$post->post_parent}&submission={$post->ID}")),
                esc_html(esc_html__('Log', 'totalcontest')));
        endif;

        return $actions;
    }

    public function states($states, $post)
    {
        $submission = $this->submissionsRepository->getById($post);

        if (!$submission):
            return null;
        endif;

        $states[] = $submission->getVotesWithLabel();

        if ($this->contest && $this->contest->isRateVoting()):
            $states[] = $submission->getRateWithLabel();
        endif;

        return $states;
    }

    public function templates()
    {
        include __DIR__ . '/Views/templates.php';
    }

    public function download()
    {
        if (!$this->contest):
            exit;
        endif;

        $submissions = TotalContest( 'submissions.repository' )->get([
	        'contest'        => $this->contestId,
	        'perPage'        => -1,
	        'page'           => 1,
            'status' => 'any',
        ]);

        $export = new Spreadsheet();

        $export->addColumn(new TextColumn(esc_html__('ID', 'totalcontest')));
        $export->addColumn(new TextColumn(esc_html__('Title', 'totalcontest')));
        $export->addColumn(new DateColumn(esc_html__('Date', 'totalcontest')));
        $export->addColumn(new TextColumn(esc_html__('Status', 'totalcontest')));
        $export->addColumn(new TextColumn(esc_html__('User', 'totalcontest')));
        $export->addColumn(new TextColumn(esc_html__('Category', 'totalcontest')));
        $export->addColumn(new NumericColumn(esc_html__('Views', 'totalcontest')));
        $export->addColumn(new NumericColumn(esc_html__('Votes', 'totalcontest')));
        $export->addColumn(new NumericColumn(esc_html__('Rate', 'totalcontest')));

        $fields = (array)$this->contest->getFormFieldsDefinitions();

        foreach ($fields as $field):
	        if($field['name'] === 'category'){
		        continue;
	        }

	        $export->addColumn(new TextColumn(esc_html__('Form: ',
                    'totalcontest') . (empty($field['label']) ? $field['name'] : $field['label'])));
        endforeach;

        foreach ($submissions as $submission):
            $row = [
                $submission->getId(),
                $submission->getTitle(),
                $submission->getDate()->format(''),
                $submission->isApproved() ? esc_html__('Approved', 'totalcontest') : esc_html__('In Review', 'totalcontest'),
                $submission->getAuthor()->display_name,
                $submission->getCategoryName(),
                $submission->getViews(),
                $submission->getVotes(),
                $submission->getRate(),
            ];

            foreach ($fields as $field):
                if($field['name'] === 'category'){
                    continue;
                }

                $row[] = $submission->getField($field['name'], esc_html__('N/A', 'totalcontest'));
            endforeach;

            $export->addRow($row);
        endforeach;

        $format = $this->request->request('export', 'html');

        
            $writer = new HTMLWriter();
        
        

        $writer->includeColumnHeaders = true;

        $filename = apply_filters('totalcontest/filters/admin/submission/listing/export/filename', 'totalcontest-submissions-export-' . date('Y-m-d H:i:s'), $this);
        $export->download($writer, $filename);

        exit;
    }

    /**
     * @param WP_Query $query
     *
     * @return mixed
     */
    public function scope($query)
    {
        if (current_user_can('edit_others_contest_submissions')):

            if ($query->get('post_type') === TC_SUBMISSION_CPT_NAME):

                $subquery = new WP_Query([
                    'post_type' => TC_CONTEST_CPT_NAME,
                    'fields' => 'ids',
                    'nopaging' => true,
                    'author' => get_current_user_id(),
                    'suppress_filters' => true,
                    'no_found_rows' => true
                ]);

                $orderBy = $query->get('orderby');

                if ($orderBy == 'votes') {
                    $query->set('meta_key', '_tc_votes');
                    $query->set('orderby', 'meta_value_num');
                }

                if ($orderBy == 'views') {
                    $query->set('meta_key', '_tc_views');
                    $query->set('orderby', 'meta_value_num');
                }

                $contestFilter = TotalContest('http.request')->query('contest', 0);

                if (!empty($contestFilter)) {
                    $query->set('post_parent', (int)$contestFilter);
                }


                $ids = $subquery->get_posts();

                if (! empty($ids)):
                    $query->set('post_parent__in', (array) $ids);
                endif;

            endif;

        endif;

        return $query;
    }
}
