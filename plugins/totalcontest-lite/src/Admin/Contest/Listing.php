<?php

namespace TotalContest\Admin\Contest;

use TotalContest\Contracts\Log\Repository as LogRepository;
use TotalContest\Contracts\Submission\Repository as SubmissionsRepository;
use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Listing
 * @package TotalContest\Admin\Contest
 */
class Listing
{
    /**
     * @var LogRepository $logRepository
     */
    protected $logRepository;
    /**
     * @var SubmissionsRepository $submissionsRepository
     */
    protected $submissionsRepository;

    /**
     * Listing constructor.
     *
     * @param LogRepository $logRepository
     * @param SubmissionsRepository $submissionsRepository
     */
    public function __construct(LogRepository $logRepository, SubmissionsRepository $submissionsRepository)
    {
        $this->logRepository = $logRepository;
        $this->submissionsRepository = $submissionsRepository;

        // Columns
        add_filter('manage_contest_posts_columns', [$this, 'columns']);

        // Columns contest
        add_action('manage_contest_posts_custom_column', [$this, 'columnsContent'], 10, 2);

        // Actions
        add_filter('post_row_actions', [$this, 'actions'], 10, 2);


        // Assets
        add_action('admin_enqueue_scripts', [$this, 'assets']);

        // Scope
        add_filter('pre_get_posts', [$this, 'scope']);

    }

    public function assets()
    {
        wp_enqueue_style('totalcontest-admin-contests-listing');
    }

    /**
     * Columns.
     *
     * @param $originalColumns
     *
     * @return array
     */
    public function columns($originalColumns)
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'title' => esc_html__('Title'),
            'submissions' => esc_html__('Submissions', 'totalcontest'),
            'votes' => esc_html__('Votes', 'totalcontest'),
            'date' => esc_html__('Date'),
        ];

        if (!current_user_can('edit_contest_submissions')):
            unset($columns['submissions']);
        endif;

        /**
         * Filters the list of columns in contests listing.
         *
         * @param array $columns Array of columns.
         * @param array $originalColumns Array of original columns.
         *
         * @return array
         * @since 2.0.0
         */
        return apply_filters('totalcontest/filters/admin/contest/listing/columns', $columns, $originalColumns);
    }

    /**
     * Columns content.
     *
     * @param $column
     * @param $id
     */
    public function columnsContent($column, $id)
    {
        // Votes column
        add_filter('totalcontest/filters/admin/contest/listing/columns-content/votes', function ($content, $id) {
            return number_format_i18n($this->logRepository->count(['conditions' => ['contest_id' => $id, 'action' => 'vote', 'status' => 'accepted']]));
        }, 10, 2);

        // Submissions column
        add_filter('totalcontest/filters/admin/contest/listing/columns-content/submissions', function ($content, $id) {
            $submissionPostType = TC_SUBMISSION_CPT_NAME;
            $formattedNumber = number_format_i18n($this->submissionsRepository->count(['contest' => $id]));
            $link = admin_url("edit.php?post_type={$submissionPostType}&contest={$id}");

            return sprintf('<a href="%s">%s</a>', esc_attr($link), $formattedNumber);
        }, 10, 2);

        /**
         * Filters the content of a column in contests listing.
         *
         * @param array $content Column content.
         * @param array $id Contest post ID.
         *
         * @return string
         * @since 2.0.0
         */
        echo apply_filters("totalcontest/filters/admin/contest/listing/columns-content/{$column}", null, $id);
    }

    /**
     * Inline actions.
     *
     * @param $actions
     * @param $post
     *
     * @return array
     */
    public function actions($actions, $post)
    {
        $contestPostType = TC_CONTEST_CPT_NAME;
        $submissionPostType = TC_SUBMISSION_CPT_NAME;

        if (current_user_can('edit_contest_submissions')):
            $actions['submissions'] = sprintf('<a href="%s">%s</a>', esc_attr(admin_url("edit.php?post_type={$submissionPostType}&contest={$post->ID}")),
                esc_html(esc_html__('Submissions', 'totalcontest')));
        endif;

        if (current_user_can('manage_options')):
            $url = admin_url("admin.php?action=reset_contest&post_type={$contestPostType}&contest={$post->ID}");
            $url = add_query_arg('_wpnonce', wp_create_nonce('reset_contest'), $url);
	        $confirm          = sprintf( "return prompt('%s') === 'confirm';", esc_js( esc_html__( 'Are you sure? This will remove submissions, votes and log from contest. Please type "confirm" to continue', 'totalcontest' ) ) );
            $actions['reset'] = sprintf('<a href="%s" style="color: #a00" onclick="%s">%s</a>', esc_attr($url), $confirm, esc_html(esc_html__('Reset contest', 'totalcontest')));
        endif;

        if (current_user_can('manage_options')):
            $actions['log'] = sprintf('<a href="%s">%s</a>', esc_attr(admin_url("edit.php?post_type={$contestPostType}&page=log&contest={$post->ID}")),
                esc_html(esc_html__('Log', 'totalcontest')));
        endif;

        /**
         * Filters the list of available actions in contests listing (under each contest).
         *
         * @param array $actions Array of actions [id => url].
         * @param \WP_Post $post Contest post.
         *
         * @return array
         * @since 2.0.0
         */
        return apply_filters('totalcontest/filters/admin/contest/listing/actions', $actions, $post);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scope($query)
    {
        if (!current_user_can('edit_others_contests')):
            $query->set('author', get_current_user_id());
        endif;

        return $query;
    }
}
