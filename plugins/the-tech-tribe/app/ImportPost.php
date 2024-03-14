<?php
namespace TheTribalPlugin;

use DateInterval;

class ImportPost
{
    /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct(){}

    public function import($args = [])
    {
        require_once ABSPATH . 'wp-admin/includes/post.php';

        $statusVerbage = \TheTribalPlugin\StatusVerbage::get_instance()->get('import');

        $ret = [
            'success' => false,
            'msg-header' => $statusVerbage['error']['header'],
            'msg' => $statusVerbage['error']['msg'],
            'code' => 'error',
            'summary' => []
        ];
        
        tttResetDownloadStatusStartEnd();
        
        tttStartImport();
        
        tttCustomLogs("start import ");

        $countSuccess = 0;

        $currentUserId = WPOptions::get_instance()->defaultAuthor();
        
        //insert the post
        $post = new Post;
        $getPost = $post->get($args);
        
        tttCustomLogs('getPost');
        tttCustomLogs($getPost);
        tttCustomLogs($ret);
        tttCustomLogs('getPost');

        if( 
            $getPost->status == 200 
            && isset($getPost->data['success'])
            && isset($getPost->data['posts'])
            && isset($getPost->data['posts']['total_post'])
            && isset($getPost->data['code'])
            && $getPost->data['code'] != 'error'
            && $getPost->data['posts']['total_post'] >= 1
            && $getPost->data['success'] == true
        ) {
            tttCustomLogs("start post data import ");
            $timezone_offset = get_option( 'gmt_offset' );
            
            $dataPost = $getPost->data['posts']['posts'];
            $blogUrl = $getPost->data['posts']['current_post_url'];
            $blogName = $getPost->data['posts']['current_blog_name'];
            
            $publishPostSetting = WPOptions::get_instance()->publishPosts();
            
            $postStatus = 'draft';
            
            $postData = [];
            $post_id = 0;
            
            tttCustomLogs("post data import ");
            tttCustomLogs($dataPost);

            foreach($dataPost as $post) {
                $postContent = '';
                if( ! post_exists($post['title']) ) {
                    //insert post
                    $post_date = date_i18n( 'Y-m-d H:i:s' );
                    $post_date_schedule = date_i18n( 'Y-m-d H:i:s', strtotime($post['meta']['date_schedule'] . ' 12:00:00'));
                    if( $post['meta']['date_schedule'] != '' ) {
                        $post_date_schedule = date_i18n('Y-m-d H:i:s', strtotime($post['meta']['date_schedule'] . ' 12:00:00'));
                        if( $post['meta']['date_schedule'] > $post_date) {
                            $postStatus = 'draft';
                            //$postStatus = 'future';
                        }
                    }

                    if( $publishPostSetting == 'auto' ) {
                        $postStatus = 'publish';
                    }
                    
                    $postData = [
                        'post_title'        => $post['title'],
                        'post_status'       => $postStatus,
                        'post_date'         => $post_date_schedule, 
                        'post_date_gmt'     => $post_date_schedule, 
                        'post_author'       => $currentUserId,
                    ];
                    
                    $post_id = wp_insert_post($postData);
                    //insert post

                    if($post_id) {

                        $ret['summary']['post'][$post_id]['id'] = $post_id;
                        $ret['summary']['post'][$post_id]['title'] = $post['title'];
                        
                        $countSuccess++;
                        
                        //insert category
                        $taxonomy = 'category';
                        $postCategory = $post['categories'];
                        if(!empty($postCategory)) {
                            $arrInsertTaxonomy = [
                                'categories'    => $postCategory,
                                'post_id'       => $post_id
                            ];
                            $category_id = $this->insertTaxonomy($arrInsertTaxonomy);
                            if($category_id != 0 || is_array($category_id)){
                                wp_set_object_terms( $post_id, $category_id, $taxonomy );
                            }
                        }
                        //insert category
                        
                        $postImages = isset($post['meta']['images']['contents']) ? $post['meta']['images']['contents'] : [];
                        $searchImageToReplaces = [];
                        $actualImageToReplaces = [];
                        //download images, more of inline image in the content
                        if(is_array($postImages) && count($postImages) >= 1) {
                            foreach($postImages as $postImage) {
                                $downloadImages = \TheTribalPlugin\DownloadImage::get_instance()->download([
                                	'file_url' => $postImage,
                                	'parent_post_id' => $post_id
                                ]);
                                $wp_get_attachment_url = wp_get_attachment_url($downloadImages);
                                $searchImageToReplaces[] = $postImage;
                                $actualImageToReplaces[] = $wp_get_attachment_url;
                                $ret['summary']['post'][$post_id]['attach_image'][$downloadImages]['id'] = $downloadImages;
                                $ret['summary']['post'][$post_id]['attach_image'][$downloadImages]['old_file_name'] = $postImage;
                                $ret['summary']['post'][$post_id]['attach_image'][$downloadImages]['file_name'] = $wp_get_attachment_url;
                            }
                        }
                        //download images, more of inline image in the content

                        //download images, set as featured image
                        $postFeaturedImage = isset($post['meta']['images']['featured']['image']) ? $post['meta']['images']['featured']['image'] : '';
                        if(!empty($postFeaturedImage)) {
                            $featuredAttachmentId = \TheTribalPlugin\DownloadImage::get_instance()->download([
                            	'file_url' => $postFeaturedImage,
                            	'parent_post_id' => $post_id,
                                'alt_text' => $post['meta']['images']['featured']['alt_text']
                            ]);
                            $ret['summary']['post'][$post_id]['featured_image'][$featuredAttachmentId]['id'] = $featuredAttachmentId;
                            $ret['summary']['post'][$post_id]['featured_image'][$featuredAttachmentId]['file_name'] = wp_get_attachment_url($featuredAttachmentId);
                            set_post_thumbnail( $post_id, $featuredAttachmentId );
                        }
                        //download images, set as featured image

                        //update the post
                        //$postContent = str_replace($blogUrl, site_url(), $post['content']);
                        $postContent = str_replace($searchImageToReplaces, $actualImageToReplaces, $post['content']);
                        $postContent .= '<p>This Article has been Republished with Permission from <a rel="canonical" href="'.$post['get_the_permalink'].'" title="'.$post['title'].'" target="_blank">'.$blogName.'.</a></p>';

                        //remove extra carraige returns
                        $postContent = str_replace(["\n", "\r"], '', $postContent);
                        $postContent = nl2br($postContent);

                        $argUpdate = [
                            'ID'                => $post_id,
                            'post_modified'     => date_i18n( 'Y-m-d H:i' ),
                            'post_modified_gmt' => date_i18n( 'Y-m-d H:i' ),
                            'post_content'      => $postContent
                        ];
                        wp_update_post( $argUpdate );
                        //update the post
                    }
                } else {
                    //exists
                    $ret['summary']['exists']['post'][]['title'] = $post['title'];
                }
            }
        }
        else{
            $ret['success'] = $getPost->data['success'];
            $ret['msg-header'] = $getPost->data['headers'];
            $ret['msg'] = $getPost->data['msg'];
            $ret['code'] = $getPost->data['code'];
            $ret['post_count_imported'] = $countSuccess;
        }

        if( $countSuccess > 0 ) {
            $ret['success'] = true;
            $ret['msg-header'] = $statusVerbage['success']['header'];
            $ret['msg'] = $statusVerbage['success']['msg'];
            $ret['code'] = 'success';
            $ret['post_count_imported'] = $countSuccess;
            
            tttCustomLogs("post imported : " . $countSuccess);
            tttLastDownload();
        }

        //invalid api key, means wrong
        if(isset($ret['msg']) && $ret['msg'] == 'api error'){
            $apiVerbage = tttGetAPIVerbage();
            $ret['msg-header'] = $apiVerbage['error']['header'];
            $ret['msg'] = $apiVerbage['error']['msg'];
            
            tttCustomLogs("post imported auth error ");

            tttSetKeyActive(0);
        }

        if(isset($ret['msg']) && $ret['msg'] == 'domain already used'){
            //echo '4';
            $domainVerbage = tttGetDomainVerbage();
            $ret['msg-header'] = $domainVerbage['error']['header'];
            $ret['msg'] = $domainVerbage['error']['msg'];
            
            tttCustomLogs("post imported domain error ");

            tttSetKeyActive(0);
        }

        if(!empty($ret['summary']['exists']['post']) && count($ret['summary']['exists']['post']) > 0) {
            $ret['success'] = true;
            $ret['msg-header'] = $statusVerbage['nothing']['header'];
            $ret['msg'] = $statusVerbage['nothing']['msg'];
            $ret['code'] = $getPost->data['code'];
            $ret['post_count_imported'] = $countSuccess;

            tttCustomLogs("nothing to import ");
        }
        
        tttLastChecked();
        
        tttLastCheckedStatus($ret['code'], $ret['msg']);
        
        tttCustomLogs("return import api code" . $ret['code']);
        tttCustomLogs("return import api msg" . $ret['msg']);

        tttEndImport();

        tttLogReturn($ret);
        
        tttCustomLogs("end post import ");

        return rest_ensure_response($ret);
    }

    /**
     * to insert category
	 *	- check if category exists
	 *	- if not, create
	 *	- if yes, get the ID
	 *	- insert it to post
     */
    private function insertTaxonomy( $args = [] )
    {
        $categoryFromPress = $args['categories'] ?? [];
        $taxonomy = $args['taxonomy'] ?? 'category';
        $postId = $args['post_id'] ?? false;
        $term_id = [];
        if(!empty($categoryFromPress) && $postId){
            foreach($categoryFromPress as $category) {
                $termName = $category;
                $category = get_term_by('name', $termName, $taxonomy);
                if(!$category){
                    //create the term/category
                	$retCat = wp_insert_term($termName, $taxonomy);
                    $term_id[] = $retCat['term_id'];
                }else{
                    //get the term/category
                    $term_id[] = $category->term_id;
                }
            }
       }

       return $term_id;
    }
    
}