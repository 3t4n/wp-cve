<?php

namespace WPListUrls\ListUrls;

use WPListUrls\League\Csv\Writer;
use SplTempFileObject;
use WP_Query;

class ListUrls{

    public function __construct(){
        add_action( 'admin_init', array( $this, 'export_action' ) );
    }

    /**
     * Export action which call the CSV download
     *
     * @param  
     * @return Header CSV force download
     */
    public function export_action()
    {
        if ( ! isset( $_POST['wp_list_urls_download'] ) ) {
            return;
        }

        // security check
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'wp_list_urls_download_action' ) ) {
            var_dump( $_POST['_wpnonce']);
            wp_die( __( 'Action failed. Please refresh the page and retry.', 'wp-listurls' ) );
        }

        $this->toCsv($_POST);
        exit;

    }

    /**
     * Produce the CSV
     *
     * @param  
     * @return bool
     */
    protected function toCsv($request)
    {
        $types = $this->getPostTypes();

        $taxs = $this->getTaxonomies();

        global $post;

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        
        $firstRow = ['Page Title','URL','Wordpress Id','Post Type', 'Post Status'];

        $csv->insertOne($firstRow);

        $rows = [];

        foreach ($types as $type){
            $args = array(
              'post_type' => $type->name,
              'posts_per_page' => -1
            );

            if(!isset($request['include_draft']))
            {
                $args['post_status'] = array('publish', 'future', 'private');
            }

            $query = new WP_Query( $args );
            if ( $query->have_posts() ) {

                $archiveLink = get_post_type_archive_link($type->name);

                if($archiveLink){                    
                    $rows[] = ['Archive Link',$archiveLink,'NA',$type->labels->name];                
                }

                while ( $query->have_posts() ) {

                    $query->the_post();
                    $title = get_the_title();
                    $url = get_the_permalink($post->ID);
                    $id = $post->ID;
                    $status = get_post_status($post->ID);
                    $rows[] = [$title,$url,$id,$type->labels->name, $status];

                }
            }
        }

        if($taxs){
            $rows[] = ['','','',''];

            $rows[] = ['Term Name','URL','Term Id','Taxonomy'];

            foreach ($taxs as $tax){

                $terms = get_terms( 
                    $tax->name, 
                    array(
                        'hide_empty' => true,
                    ) 
                );

                foreach ($terms as $term) {

                    $termUrl = get_term_link ($term);
                    $rows[] = [$term->name,$termUrl,$term->term_id,$tax->labels->name];

                }
            }
        }    
        
        $csv->insertAll($rows);

        $csv->output($this->getFileName());

        return true;
    }

    /**
     * Get all the post types available
     *
     * @param  
     * @return object
     */
    protected function getPostTypes()
    {
        $args = array(
           'public'   => true
        );

        return get_post_types($args, 'objects'); 
    }


    /**
     * Get all the taxonimies available
     *
     * @param  
     * @return object
     */
    protected function getTaxonomies()
    {
        $args = array(
           'public'   => true
        );

        return get_taxonomies($args, 'objects');
    }

    /**
     * 
     *
     * @param  
     * @return 
     */
    protected function getFileName()
    {
        $siteName = get_bloginfo('name');

        if(empty($siteName))
        {
            return 'urlslist.csv';
        }        

        $siteName = preg_replace('/[^A-Za-z0-9\-\']/', '', $siteName);

        $siteName = str_replace(" ","-", $siteName);

        return $siteName.'-urlslist.csv';

    }

}