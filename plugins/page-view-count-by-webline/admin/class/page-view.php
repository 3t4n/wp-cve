<?php

class pageView {
    
    private $query = null, 
            $countQuery = null,
            $rawData= null, 
            $viewData = null, 
            $rawDetailData = null, 
            $sortFieldKey = '',
            $defaultSortField = 'sDisplayName', 
            $defaultSortOrder = 'ASC',
            $searchCondition = '',
            $limitClause = '',
            $perPage = 10,
            $totalPage = null,
            $totalResult = null, 
            $currentPage = 1,
            $sortField = null, 
            $sortOrder = null,
            $sortClasses = array(),
            $dateRange = '';
    
    public function __construct() {
        add_action( 'wp_ajax_load_views_data', array($this, 'loadDetailData') );
    }
    
    public function loadPageViewData() {
        global $adminAssets;
         
        $adminAssets->enqueueAssets();

        echo '<div class="wrap">';
        echo '<h1>'. __( 'Page View Insights' ) .'</h1>';

        $this->general_section_callback();
        $table = '';
        $table .= '<div class="page-view-insights-wrap">';
        $this->search()->sort()->getTotal()->limit()->build()->fetch();
        $table .= $this->loadDataTable();
        $table .= '</div>';
        echo $table;

        echo '</div>';

        add_filter( 'admin_footer_text', array( $this, 'pvbw_admin_footer' ), 1, 2 );
    }
    
    private function getTotal() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . VC_TABLENAME;
        
        $this->countQuery = 'SELECT COUNT(mainData.total) as total_result FROM (SELECT count(ph.nUserID) as total FROM '.$table_name.' AS ph  WHERE 1 '.$this->searchCondition.' GROUP BY ph.nUserID) AS mainData'; 
        
        $this->totalResult = $wpdb->get_var($this->countQuery);
        
        return $this;
    }
    
    private function fetch () {
        global $wpdb;
        $this->viewData = $wpdb->get_results($this->query, OBJECT);
        return $this;
    }
    
    private function limit () {
        
        
        $this->totalPage = ceil($this->totalResult/$this->perPage);
        
        $this->currentPage = isset($_POST['pagenum'])?$_POST['pagenum']:'1'; 

        if($this->currentPage > $this->totalPage) {
            $this->currentPage = $this->totalPage;
            
            if($this->currentPage == 0) {
                $this->currentPage = 1;
            }
        }
        
        $upperLimit = $this->perPage*$this->currentPage;
        
        $lowerLimit = $upperLimit-$this->perPage;
        
        $this->limitClause = ' LIMIT '.$lowerLimit.', '.$this->perPage;
        
        return $this;
    }
    
    private function getSortField($fieldName = '') {
        
        if($fieldName == '') {
            $this->sortFieldKey = '';
            return $this->defaultSortField;
        } else {
            $sortFieldMap = array(
                    'username' => 'sDisplayName',
                    'postviewcount' => 'nPostView',
                    'pageviewcount' => 'nPageView',
                    'datevisited' => 'dDateAdded'
            );
            
            if(array_key_exists($fieldName, $sortFieldMap)) {
                $this->sortFieldKey = $fieldName;
                return $sortFieldMap[$fieldName];
            }  else {
                $this->sortFieldKey = $fieldName;
                return $this->defaultSortField;
            }
        }
    }
    
    private function getSortOrder($order = '') {
        $orders = array('ASC', 'DESC');
        
        if($order == '' || !in_array(strtoupper($order), $orders)) {
            return $this->defaultSortOrder;
        } else {
            return strtoupper($order);
        }
        
    }
    
    
    private function generateSortClasses() {
        
        $defaultClass = 'sortable desc';
        $this->sortClasses = array(
                'sDisplayName' => $defaultClass,
                'nPostView' => $defaultClass,
                'nPageView' => $defaultClass,
                'dDateAdded' => $defaultClass
        );  
        
        $this->sortClasses[$this->sortField] = 'sorted '.strtolower($this->sortOrder);        
    }
    
    private function sort() {
        
        $this->sortField = (isset($_POST['sort_field'])) ? $this->getSortField($_POST['sort_field']) : $this->defaultSortField;
        $this->sortOrder = (isset($_POST['sort_order'])) ? $this->getSortOrder($_POST['sort_order']) : $this->defaultSortOrder;
        
        
        $this->generateSortClasses();
        
        return $this;
    }
    
    
    private function search() {
        
        $this->dateRange = !empty( $_POST['date_range'] ) ? $_POST['date_range'] : '';
        
        $dates = explode('-', $this->dateRange);
        
        $startDate = null;
        $endDate = null;
        
        if(isset($dates[0]) && trim($dates[0]) != '') {
            $startDate = date_format(date_create_from_format('j/n/Y' ,trim($dates[0])), 'Y-m-d');
        }
        
        if(isset($dates[1]) && trim($dates[1]) != '') {
            $endDate = date_format(date_create_from_format('j/n/Y' ,trim($dates[1])), 'Y-m-d');
        }
        
        //$conditionArr[] = $this->searchCondition;
        $conditionArr = array();
        
        if($startDate == null && $endDate == null) {
            
        } else if($startDate != null && $endDate == null) {
            $conditionArr[] = " DATE(dDateAdded) = '".$startDate."'";
        } else if($startDate == null && $endDate != null) {
            $conditionArr[] = " DATE(dDateAdded) = '".$endDate."'";
        } else if($startDate != null && $endDate != null) {
            $conditionArr[] = " dDateAdded >= '".$startDate."' AND dDateAdded <= '".$endDate."'";
        }

        if(count($conditionArr) > 0){
            $this->searchCondition = implode(' AND ', $conditionArr);
        }
        
        if($this->searchCondition != '') {
            $this->searchCondition = ' AND '.$this->searchCondition;
        }
        
        return $this;
    }
    
    private function build() {
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . VC_TABLENAME;
        
        $this->query = 'SELECT ph.nUserID, u.display_name as sDisplayName, (SELECT COUNT(nHistoryID) FROM '.$table_name.' AS sph WHERE sph.nUserID = ph.nUserID AND sph.sPostType = "page" '.$this->searchCondition.') AS nPageView, (SELECT COUNT(nHistoryID) FROM '.$table_name.' AS sph WHERE sph.nUserID = ph.nUserID AND sph.sPostType = "post" '.$this->searchCondition.') AS nPostView, MAX(ph.dDateAdded) as dDateAdded FROM '.$table_name.' AS ph LEFT JOIN '. $wpdb->users .' AS u ON ph.nUserID=u.ID WHERE 1 '.$this->searchCondition.' GROUP BY ph.nUserID ORDER BY '. $this->sortField .' '. $this->sortOrder. $this->limitClause;
        
        return $this;
    }
    
    private function loadPostType() {
        //$postTypes = get_post_types(array('publicly_queryable'=>true),'objects');
        
        $str = '';
        
        $postType = array('post'=>'Post','page'=>'Page');
        
        $str .= '<select style="width:200px;">';
            $str .= '<option value="0">Select Page Type</option>';

            foreach($postType as $key => $value) {
                $str .= '<option value="'.$key.'">'.$value.'</option>';
            }
            
        $str .= '</select>';
        
        return $str;
        
    }
    
    private function loadSearchForm() {
        
        $str = '';
        
        $str .= '<table class="wp-list-table widefat fixed posts view-search-table" id="view-search-table">';
            /* $str .= '<tr>';
                $str .= '<td><h3>Filter Data&nbsp;Data</h3></td>';
            $str .= '</tr>'; */
            
            $str .= '<tr>';
                $str .= '<td>';
                    $str .= '<div class="wrap">';
                        $str .= '<form method="post" id="view-search-form">';
                            $str .= '<table class="form-table">';
                                $str .= '<tr>';
                                
                                    $str .= '<td align="left" id="date_range_td">';
                                        $str .= '<input name="date_range" type="text" id="date_range" value="'.$this->dateRange.'"  placeholder="Select Date Range" class="regular-text" autocomplete="off">';
                                        $str .= '<input name="sort_order" type="hidden" id="sort_order" value="'.$this->sortOrder.'">';
                                        $str .= '<input name="sort_field" type="hidden" id="sort_field" value="'.$this->sortFieldKey.'">&nbsp;&nbsp;';
                                        $str .= '<input name="pagenum" type="hidden" id="pagenum" value="'.$this->currentPage.'">&nbsp;&nbsp;';
                                        $str .= '<input type="submit" class="button button-primary" value="Filter Data" />&nbsp;&nbsp;';
                                        $str .= '<input type="button" class="button" value="Reset Filter" id="reset_filter" />';
                                    $str .= '</td>';

                                $str .= '</tr>';
                            $str .= '</table>';
                        $str .= '</form>';
                    $str .= '</div>';
                $str .= '</td>';
            $str .= '</tr>';
            
            
        $str .= '</table>';
        
        return $str;
    }
    
    private function tableHead() {
        
        $str = '';
        
        $str .= '<thead>';
            $str .= '<tr class="head">';
                $str .= '<th width="20%" class="'.$this->sortClasses['sDisplayName'].'"><a href="javascript:void(0);" class="sortdata" data-field="username"><span>User</span><span class="sorting-indicator"></span></a></th>';
                $str .= '<th width="15%" align="left">Role</th>';
                $str .= '<th width="15%" class="'.$this->sortClasses['nPostView'].'"><a href="javascript:void(0);" class="sortdata" data-field="postviewcount"><span>Post View Count</span><span class="sorting-indicator"></span></a></th>';
                $str .= '<th width="15%" class="'.$this->sortClasses['nPageView'].'"><a href="javascript:void(0);" class="sortdata" data-field="pageviewcount"><span>Page View Count</span><span class="sorting-indicator"></span></a></th>';
                $str .= '<th width="10%">Post Count</th>';
                $str .= '<th width="25%" class="'.$this->sortClasses['dDateAdded'].'"><a href="javascript:void(0);" class="sortdata" data-field="datevisited"><span>Last Visited on</span><span class="sorting-indicator"></span></a></th>';
            $str .= '</tr>';
        $str .= '</thead>';
        
        return $str;
        
    }
    
    private function loadMainPagination() {
        
        $str = '';
        
        $firstPageLink .= '<a class="page_link first-page '.(($this->currentPage==1)?'disabled':'').'" title="Go to the first page" data-page="1" href="javascript:void(0);">&laquo;</a>';
        
        $prevPageLink = '<a class="page_link prev-page '.(($this->currentPage==1)?'disabled':'').'" title="Go to the previous page" href="javascript:void(0);" data-page="'.(($this->currentPage==1)?'1':$this->currentPage-1).'">&lsaquo;</a>';
        
        $nextPageLink = '<a class="page_link next-page '.(($this->currentPage==$this->totalPage)?'disabled':'').'" title="Go to the next page" href="javascript:void(0);" data-page="'.(($this->currentPage==$this->totalPage)?$this->totalPage:$this->currentPage+1).'">&rsaquo;</a>';
        
        $lastPageLink = '<a class="page_link last-page '.(($this->currentPage==$this->totalPage)?'disabled':'').'" title="Go to the last page" data-page="'.$this->totalPage.'" href="javascript:void(0);">&raquo;</a>';
        
        
        $str .= '<tr class="detail_data" id="main_pagination">';
            $str .= '<td colspan="6" width="100%">
                        <div class="tablenav tablenav-pages">
                            <span class="displaying-num">'.$this->totalResult.' Entries</span>
                            <span class="pagination-links">
                                '.$firstPageLink.$prevPageLink.'            
                                
                                <span class="paging-input">
                                    Page <span class="current-page">'.$this->currentPage.'</span> of <span class="total-pages">'.$this->totalPage.'</span>
                                </span>
                                
                                '.$nextPageLink.$lastPageLink.'
                            </span>
                        </div>
                    </td>';
        $str .= '</tr>';
        
        return $str;
        
    }
    
    private function loadDataTable() {
        $str = '';
        
        
        
        
        $str .= $this->loadSearchForm();
        
        
        $str .= '<table class="wp-list-table widefat fixed posts view-count-table" id="view-count-table">';
        
            $str .= $this->tableHead();    
            /* pr($this->viewData); */
            
            
            if(count($this->viewData) > 0) {
                foreach ($this->viewData as $key => $value) {
                
                    if($value->nUserID == '0') {
                        $value->sDisplayName = 'Anonymous';
                        $value->role = ' N/A ';
                        $value->post_count = ' N/A ';
                    } else {
                        $userObj = new WP_User( $value->nUserID );
                
                        $role = array();
                
                        if ( !empty( $userObj->roles ) && is_array( $userObj->roles ) ) {
                            foreach ( $userObj->roles as $user_role ) {
                                $role[] = ucfirst($user_role);
                            }
                        } else {
                            $role [] = ' - ';
                        }
                
                        $value->role = implode(', ', $role);
                
                        $value->post_count = count_user_posts( $value->nUserID);
                
                    }
                
                
                    $str .= '<tr>';
                    $str .= '<td class="user_name">';
                    if($value->nUserID > 0) {
                        $str .= '<a href="'.home_url().'/wp-admin/user-edit.php?user_id='.$value->nUserID.'">' . $value->sDisplayName . '</a>';
                    } else {
                        $str .= $value->sDisplayName;
                    }
                
                    $str .= '</td>';
                    $str .= '<td>'.$value->role.'</td>';
                    $str .= '<td><a href="javascript:void(0);" class="load_detail_data" data-id="'.$value->nUserID.'" data-type="post" id="post_view_count_'.$value->nUserID.'">'. (($value->nPostView == '') ? '0' : $value->nPostView).'</a></td>';
                    $str .= '<td><a href="javascript:void(0);" class="load_detail_data" data-id="'.$value->nUserID.'" data-type="page" id="page_view_count_'.$value->nUserID.'">'.(($value->nPageView == '') ? '0' : $value->nPageView).'</a></td>';
                
                    $str .= '<td>'.$value->post_count.'</td>';
                    $str .= '<td>'.date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime($value->dDateAdded), true).'</td>';
                    $str .= '</tr>';
                
                    $str .= '<tr class="detail_data" id="post_view_tr_'.$value->nUserID.'" style="display:none;">';
                    $str .= '<td colspan="6" class="detail_data_holder" width="100%" id="post_view_td_'.$value->nUserID.'" align="center"></td>';
                    $str .= '</tr>';
                
                    $str .= '<tr class="detail_data" id="page_view_tr_'.$value->nUserID.'" style="display:none;">';
                    $str .= '<td colspan="6" class="detail_data_holder" width="100%" id="page_view_td_'.$value->nUserID.'" align="center"></td>';
                    $str .= '</tr>';
                
                    $str .= '<tr class="detail_data" id="loading_tr_'.$value->nUserID.'" style="display:none;">';
                    $str .= '<td colspan="6" width="100%" align="center">...Loading...</td>';
                    $str .= '</tr>';
                
                
                }
            } else {
                $str .= '<tr><td align="center" colspan="6">No Data Found</td></tr>';
            }
            
            if($this->totalPage > 1) {
                $str .= $this->loadMainPagination();
            }
            
            
            
        $str .= '</table>';
        
        
        return $str;
    }
    
    private function setDetailDataOrder() {
        $fieldMapping = array(
                'title' => 'p.post_title',
                'views' => 'viewcount',
                'visit' => 'dDateAdded'
        );
        
        $orderMapping = array( 'ASC', 'DESC' );
        
        $sort['fieldkey'] = 'title';
        $sort['field'] = $fieldMapping[$sort['fieldkey']];
        
        $sort['order'] = 'asc';
        
        if(isset($_POST['sort_field']) && array_key_exists($_POST['sort_field'], $fieldMapping)) {
            
            $sort['fieldkey'] = $_POST['sort_field'];
            
            $sort['field'] = $fieldMapping[$sort['fieldkey']];
        }
        
        if(isset($_POST['sort_order']) && in_array(strtoupper($_POST['sort_order']), $orderMapping)) {
            $sort['order'] = strtoupper($_POST['sort_order']);
        }
        
        return $sort;
    }
    
    private function sortClass(&$sort = array()) {
        
        $defaultClass = 'sortable desc';
        $classArr = array(
                'title' => $defaultClass,
                'views' => $defaultClass,
                'visit' => $defaultClass
        );
        
        $classArr[$sort['fieldkey']] = 'sorted '.$sort['order'];
        
        return $classArr;
    }
    
    public function loadDetailData() {
        global $wpdb;

        $sort = $this->setDetailDataOrder();
        
        $classes = $this->sortClass($sort);
        
        $table_name = $wpdb->prefix . VC_TABLENAME;
        
        $this->search();
        
        $query = 'SELECT COUNT(ph.nPostID) AS viewcount, ph.nPostID, p.post_title, MAX(ph.dDateAdded) as dDateAdded FROM '.$table_name.' AS ph LEFT JOIN '.$wpdb->posts.' as p ON ph.nPostID = p.ID WHERE nUserID = "'.sanitize_text_field($_POST['id']).'" AND sPostType="'.sanitize_text_field($_POST['type']).'" '.$this->searchCondition.' GROUP BY ph.nPostID ORDER BY '.$sort['field'].' '.$sort['order'] ;
        
        $this->rawDetailData = $wpdb->get_results($query, OBJECT);
        
        $innerTable = '';
        
        $innerTable .= '<table class="wp-list-table widefat fixed posts detail-view-count-table" id="detail-view-count-table">';
        
        if(count($this->rawDetailData) > 0) {
        
            $innerTable .= '<thead>';
                $innerTable .= '<tr class="head" data-id="'.$_POST['id'].'" data-type="'.$_POST['type'].'">';
                    $innerTable .= '<td width="50%"><a href="javascript:void(0);" class="sort-detail-data '.$classes['title'].'" data-field="title">Post</a></th>';
                    $innerTable .= '<td align="center" width="10%" align="center"><a href="javascript:void(0);" class="sort-detail-data '.$classes['views'].'" data-field="views">Total View(s)</a></th>';
                    $innerTable .= '<td align="center" width="25%" align="center"><a href="javascript:void(0);" class="sort-detail-data '.$classes['visit'].'" data-field="visit">Last Visit On</a></th>';
                    $innerTable .= '<td width="30%"> &nbsp; </th>';
                $innerTable .= '</tr>';
            $innerTable .= '</thead>';

            foreach($this->rawDetailData as $key=>$value) {
                $innerTable .= '<thead>';
                   $innerTable .= '<tr>';
                        $innerTable .= '<td><a href="'.get_permalink($value->nPostID).'" target="_blank">'.$value->post_title.'</a></td>';
                        $innerTable .= '<td align="center">'.$value->viewcount.'</td>';
                        $innerTable .= '<td align="center">'.date_i18n( get_option('date_format') . ' ' . get_option('time_format'), strtotime( $value->dDateAdded ) ).'</td>';
                        $innerTable .= '<td> &nbsp; </td>';
                    $innerTable .= '</tr>';
                $innerTable .= '</thead>';
            } 
            
        } else {
            $innerTable .= '<tr>';
                $innerTable .= '<td align="center">No View Data Found</td>';
            $innerTable .= '</tr>';
        }
            
        echo $innerTable .='</table>'; die;
        
    }

    /**
	 * General section callback function.
	 *
	 * @since    1.0.0
	 */
	public function general_section_callback() {
		?>
		<div class="pvbw-plugin-cta-wrap">
			<h2 class="head">Thank you for downloading our plugin - Page View Count by Webline.</h2>
			<h1 class="head">We're here to help !</h1>
			<p>Our plugin comes with free, basic support for all users. We also provide plugin customization in case you want to customize our plugin to suit your needs.</p>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Page%View%20Count%20By%20Webline&utm_campaign=Free%20Support" target="_blank" class="button">Need help?</a>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Page%View%20Count%20By%20Webline&utm_campaign=Plugin%20Customization" target="_blank" class="button">Want to customize plugin?</a>
		</div>
		<div class="pvbw-plugin-cta-upgrade">
			<p class="note">Want to hire Wordpress Developer to finish your wordpress website quicker or need any help in maintenance and upgrades?</p>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Page%View%20Count%20By%20Webline&utm_campaign=Hire%20WP%20Developer" target="_blank" class="button button-primary">Hire now</a>
		</div>
		<?php
	}

    // Callback section on Admin Footer
    public function pvbw_admin_footer($footer_text)
    {
        $url = 'https://wordpress.org/support/plugin/page-view-count-by-webline/reviews/?filter=5#new-post';
        $wpdev_url = 'https://www.weblineindia.com/wordpress-development.html?utm_source=WP-Plugin&utm_medium=Page%View%20Count%20By%20Webline&utm_campaign=Footer%20CTA';
        $text = sprintf(
            wp_kses(
                'Please rate our plugin %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you from the <a href="%4$s" target="_blank" rel="noopener noreferrer">WordPress development</a> team at WeblineIndia.',
                array(
                    'a' => array(
                        'href' => array(),
                        'target' => array(),
                        'rel' => array(),
                    ),
                )
            ),
            '<strong>"Page View Count by Webline"</strong>',
            $url,
            $url,
            $wpdev_url
        );
        return $text;
    }
}

$pageView = new pageView();