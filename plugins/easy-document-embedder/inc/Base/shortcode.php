<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Base;

require_once \dirname(__FILE__,2) . '/Base/class-basecontroller.php';
use EDE\Inc\Base\BaseController;

class Shortcode extends BaseController
{
    public function ede_register()
    {
        add_shortcode( 'EDE', array($this,'edeShortcode') );
    }

    public function edeShortcode($atts)
    {
        
        \extract(shortcode_atts( [
            'id' => '1'
        ], $atts));

        $args = [
            'post_type' => array('ede_embedder'),
            'post_status'   =>  array('publish'),
            'order' =>  'DESC',
            'orderby'   =>  'date',
            'p' =>  $id,
        ];

        $q = new \WP_Query($args);
        \ob_start();
        if ($q->have_posts()) :
            while($q->have_posts()) : $q->the_post();

                $sourceType = get_post_meta( get_the_ID(), 'ede_source_type', true );
                $type = get_post_meta( get_the_ID(), 'ede_select_type', true );
                $ede_width = get_post_meta( get_the_ID(), 'ede_width', true );
                $ede_height = get_post_meta( get_the_ID(), 'ede_height', true );
                $download_btn = get_post_meta( get_the_ID(), 'ede_download_enable', true );
                $download_btn_class = get_post_meta( get_the_ID(), 'ede_download_btn_class', true );
                
                $ede_width = ($ede_width !== "") ? $ede_width : "100%";
                $ede_height = ($ede_height !== "") ? $ede_height : "600px";
                $iframeStyle = 'width:'.$ede_width.';'.'height:'.$ede_height.';';
                if ($sourceType === "ML") {
                    $url = get_post_meta( get_the_ID(), 'ede_upload_file_url', true );
                    $base_url = '//docs.google.com/gview?embedded=true&url=';
                    if ($download_btn === "1") {
                        echo '<a href="'.$url.'" download  target="_blank"><button class="'.$download_btn_class.'">Download</button></a>';
                    }

                    echo '<iframe id="s_pdf_frame" src="'.$base_url . $url.'" style="float:left; padding:10px; '.$iframeStyle.'" frameborder="0"></iframe>';
                } else if($sourceType === "EL") {
                    if ($download_btn === "1") {
                        $url = get_post_meta( get_the_ID(), 'ede_external_file_url', true );
                        $base_url = '//docs.google.com/gview?embedded=true&url=';
                        echo '<a href="'.$url.'" download  target="_blank"><button class="'.$download_btn_class.'">Download</button></a>';
                    }
                    
                    echo '<iframe id="s_pdf_frame" src="'.$base_url . $url.'" style="float:left; padding:10px;'.$iframeStyle.'" frameborder="0"></iframe>';
                } else if( $sourceType === "GDL") {
                    $url = get_post_meta( get_the_ID(), 'ede_gdocs_file_url', true );
                    $base_url = '//docs.google.com/viewer?srcid=';
                    $parseUrl = parse_url($url);
                    $new_path = $parseUrl["path"];
                    $new_path = \explode("/",$new_path);
                    
                    $last_path = $new_path[4];
                    $gdocsID = $new_path[3];
                    if ($type === "xlsx" && $download_btn === "1") {
                        $last_path = "export?format";
                        $gid = $parseUrl["fragment"];
                        $gdocsDownloadLink ="//docs.google.com/spreadsheets/d/$gdocsID/$last_path=$type&$gid";
                        echo '<a href="'.$gdocsDownloadLink.'" download target="_blank"><button class="'.$download_btn_class.'">Download</button></a>';
                    } else if (($type === "doc" || $type === "docx") && $download_btn === "1") {
                        $last_path = "export?format";
                        $gdocsDownloadLink = "//docs.google.com/document/d/$gdocsID/$last_path=$type";
                        echo '<a href="'.$gdocsDownloadLink.'" download target="_blank"><button class="'.$download_btn_class.'">Download</button></a>';
                    } else if (($type === "ppt" || $type === "pptx")  && $download_btn === "1") {
                        $last_path = "export";
                        $gdocsDownloadLink = "//docs.google.com/presentation/d/$gdocsID/$last_path/$type";
                        echo '<a href="'.$gdocsDownloadLink.'" download target="_blank"><button class="'.$download_btn_class.'">Download</button></a>';
                    }
                    echo '<iframe id="s_pdf_frame" src="'.$base_url . $gdocsID.'&pid=explorer&efh=false&a=v&chrome=false&embedded=true" style="float:left; padding:10px;'.$iframeStyle.'" frameborder="0"></iframe>';
                }
                
                
            endwhile;
        endif;
        $output = \ob_get_contents();
        \ob_end_clean();
        return $output;
        // Restore original Post Data
    }

    
}