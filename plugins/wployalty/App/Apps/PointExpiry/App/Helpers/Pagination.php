<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */


namespace Wlpe\App\Helpers;

use Wlr\App\Helpers\Input;

defined('ABSPATH') or die();

class Pagination
{
    protected $baseURL = '';
    protected $totalRows = '';
    protected $perPage = 10;
    protected $numLinks = 2;
    protected $currentPage = 0;
    protected $firstLink = 'First';
    protected $nextLink = '&raquo;';
    protected $prevLink = '&laquo;';
    protected $lastLink = 'Last';
    protected $fullTagOpen = '<ul class="pagination">';
    protected $fullTagClose = '</ul>';
    protected $firstTagOpen = '<li>';
    protected $firstTagClose = '</li>';
    protected $lastTagOpen = '<li>';
    protected $lastTagClose = '</li>';
    protected $curTagOpen = '<li class="wlpe-current-page"><a  href="#">';
    protected $curTagClose = '</a></li>';
    protected $nextTagOpen = '<li>';
    protected $nextTagClose = '</li>';
    protected $prevTagOpen = '<li>';
    protected $prevTagClose = '</li>';
    protected $numTagOpen = '<li>';
    protected $numTagClose = '</li>';
    protected $showCount = true;
    protected $currentOffset = 0;
    protected $queryStringSegment = 'page_number';

    /**
     * Pagination constructor.
     * @param array $params
     * @since 1.0.0
     */
    function __construct($params = array())
    {
        if (count($params) > 0) {
            $this->initialize($params);
        }
    }

    /**
     * initialize
     * @param array $params
     * @since 1.0.0
     */
    function initialize($params = array())
    {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }
    }

    /**
     * Generate the pagination links
     * @param array $pagination_args
     * @return string
     * @since 1.0.0
     */
    function createLinks($pagination_args = array())
    {
        // If total number of rows is zero, do not need to continue
        if ($this->totalRows == 0 or $this->perPage == 0) {
            return '';
        }
        // Calculate the total number of pages
        $numPages = ceil($this->totalRows / $this->perPage);
        // Is there only one page? will not need to continue
        if ($numPages == 1) {
            if ($this->showCount) {
                $info = 'Showing : ' . $this->totalRows;
                $info = ' <div class="dataTables_info">' . esc_html($info) . '</div>';
                return $info;
            } else {
                return '';
            }
        }
        $query_string_sep_data = (isset($pagination_args['page_number_name']) && !empty($pagination_args['page_number_name'])) ? $pagination_args['page_number_name'] . '=' : 'page_number=';
        // Determine query string
        $query_string_sep = '';
        $input = new Input();
        $get_page_id = $input->post_get('page_id', '');
        if (isset($get_page_id) && !empty($get_page_id)) {
            $query_string_sep .= '?page_id=' . $get_page_id;
        }
        $get_loyalty = $input->post_get('loyalty', '');
        if (isset($get_loyalty) && !empty($get_loyalty)) {
            $query_string_sep .= '&loyalty=';
        }
        if (empty($query_string_sep)) {
            $query_string_sep .= (strpos($this->baseURL, '?') === FALSE) ? '?' . $query_string_sep_data : '&amp;' . $query_string_sep_data;
        } else {
            $query_string_sep .= '&amp;' . $query_string_sep_data;
        }
        // Determine query string
        //$query_string_sep = (strpos($this->baseURL, '?') === FALSE) ? '?page_number=' : '&amp;page_number=';
        $this->baseURL = $this->baseURL . $query_string_sep;
        // Determine the current page
        $this->currentPage = $input->post_get($this->queryStringSegment, 0);
        if (!is_numeric($this->currentPage) || $this->currentPage == 0) {
            $this->currentPage = 1;
        }
        // Links content string variable
        $output = '';
        $this->numLinks = (int)$this->numLinks;
        // Is the page number beyond the result range? the last page will show
        if ($this->currentPage > $this->totalRows) {
            $this->currentPage = $numPages;
        }
        $uriPageNum = $this->currentPage;
        // Calculate the start and end numbers.
        $start = (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
        $end = (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;
        // Render the "First" link
        if ($this->currentPage > $this->numLinks) {
            //$query_string_sep .= 1;
            //$firstPageURL = str_replace($query_string_sep, '', $this->baseURL);
            $output .= $this->firstTagOpen . '<a href="' . $this->baseURL . '1">' . $this->firstLink . '</a>' . $this->firstTagClose;
        }
        // Render the "previous" link
        if ($this->currentPage != 1) {
            $i = ($uriPageNum - 1);
            if ($i == 0) $i = '';
            $output .= $this->prevTagOpen . '<a href="' . $this->baseURL . $i . '">' . $this->prevLink . '</a>' . $this->prevTagClose;
        }
        // Write the digit links
        for ($loop = $start - 1; $loop <= $end; $loop++) {
            $i = $loop;
            if ($i >= 1) {
                if ($this->currentPage == $loop) {
                    $output .= $this->curTagOpen . $loop . $this->curTagClose;
                } else {
                    $output .= $this->numTagOpen . '<a href="' . $this->baseURL . $i . '">' . $loop . '</a>' . $this->numTagClose;
                }
            }
        }
        // Render the "next" link
        if ($this->currentPage < $numPages) {
            $i = ($this->currentPage + 1);
            $output .= $this->nextTagOpen . '<a href="' . $this->baseURL . $i . '">' . $this->nextLink . '</a>' . $this->nextTagClose;
        }
        // Render the "Last" link
        if (($this->currentPage + $this->numLinks) < $numPages) {
            $i = $numPages;
            $output .= $this->lastTagOpen . '<a href="' . $this->baseURL . $i . '">' . $this->lastLink . '</a>' . $this->lastTagClose;
        }
        // Remove double slashes
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->fullTagOpen . $output . $this->fullTagClose;
        // Showing links notification
        if ($this->showCount) {
            $currentOffset = ($this->currentPage > 1) ? ($this->currentPage - 1) * $this->perPage : $this->currentPage;
            $info = 'Showing ' . $currentOffset . ' to ';
            if (($currentOffset + $this->perPage) <= $this->totalRows)
                $info .= $this->currentPage * $this->perPage;
            else
                $info .= $this->totalRows;
            $info .= ' of ' . $this->totalRows;
            $info = ' <div class="dataTables_info">' . $info . '</div>';
            $output .= $info;
        }
        return $output;
    }
}