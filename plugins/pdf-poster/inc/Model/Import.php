<?php
namespace PDFPro\Model;
use PDFPro\Helper\Pipe;
use PDFPro\Helper\Functions;
class Import{

    public static function meta(){
        $query = new \WP_Query(array(
            'post_type' => 'pdfposter',
            'post_status' => 'any',
            'posts_per_page' => -1
        ));

        $output = [];
        while($query->have_posts()): $query->the_post();
            $id = \get_the_ID();
            
            $meta = [
                'source' => get_post_meta($id, 'meta-image', true),
                'height' => [
                    'height' => get_post_meta($id, 'pdfp_onei_pp_height', true) == '' ? 840 : get_post_meta($id, 'pdfp_onei_pp_height', true),
                    'unit' => 'px'
                ],
                'width' => [
                    'width' => get_post_meta($id, 'pdfp_onei_pp_width', true) == '' ? 100 : get_post_meta($id, 'pdfp_onei_pp_width', true),
                    'unit' => get_post_meta($id, 'pdfp_onei_pp_width', true) == '' ? '%' : 'px',
                ],
                'print' => get_post_meta($id, 'pdfp_onei_pp_print', true) == 'on' ? true : false,
                'showName' => get_post_meta($id, 'pdfp_onei_pp_pgname', true) == 'on' ? true : false
            ];

            if (\metadata_exists('post', $id, '_fpdf') == false) {
                \update_post_meta($id, '_fpdf', $meta);
            }
        endwhile;
    }

    public static function block(){

        if(Pipe::wasPipe()){
            $podcasts = self::createBlockFromCodestar();
        }else {
            $podcasts = self::createBlock();
        }
        $result = [];
        foreach($podcasts as $podcast){
            $content_post = get_post($podcast['ID']);
            $content = $content_post->post_content;
            if($content == ''){
                $result[$podcast['ID']] = wp_update_post($podcast);
            }else {
                $result[$podcast['ID']] = 'already imported before';
            }
        }
        return $result;
    }

    public static function createBlock(){
        $query = new \WP_Query(array(
            'post_type' => 'pdfposter',
            'post_status' => 'any',
            'posts_per_page' => -1
        ));

        $output = [];
        while($query->have_posts()): $query->the_post();
            $id = \get_the_ID();
            $output[] = [
                'ID' => $id,
                'post_content' => '<!-- wp:pdfp/pdfposter '.json_encode([
                    'file' => get_post_meta($id, 'meta-image', true),
                    'title' => \basename(get_post_meta($id, 'meta-image', true)),
                    'height' => get_post_meta($id, 'pdfp_onei_pp_height', true) == '' ? '1122px' : get_post_meta($id, 'pdfp_onei_pp_height', true).'px',
                    'width' => get_post_meta($id, 'pdfp_onei_pp_width', true) == '' ? '100%' : get_post_meta($id, 'pdfp_onei_pp_width', true).'px',
                    'print' => get_post_meta($id, 'pdfp_onei_pp_print', true) == 'on' ? true : false,
                    'show_filename' => get_post_meta($id, 'pdfp_onei_pp_pgname', true) == 'on' ? true : false
                ]).' /-->'
            ];
        endwhile;

        return $output;
    }

    public static function createBlockFromCodestar(){
        $query = new \WP_Query(array(
            'post_type' => 'pdfposter',
            'post_status' => 'any',
            'posts_per_page' => -1
        ));

        $output = [];
        while($query->have_posts()): $query->the_post();
            $id = \get_the_ID();
            $width = Functions::meta($id, 'width', ['width' => '100', 'unit' => '%']);
            $height = Functions::meta($id, 'height', ['height' => '1122', 'unit' => 'px']);

            $output[] = [
                'ID' => $id,
                'post_content' => '<!-- wp:pdfp/pdfposter '.json_encode([
                    'file' => Functions::meta($id, 'source', true),
                    'title' => \basename(Functions::meta($id, 'source', true)),
                    'height' => $height['height'].$height['unit'],
                    'width' => $width['width'].$width['unit'],
                    'print' => Functions::meta($id, 'print', 0) == '1' ? true : false,
                    'showName' => Functions::meta($id, 'show_filename', 0) == '1' ? true : false,
                    'onlyPDF' => Functions::meta($id, 'only_pdf', 0) == '1' ? true : false,
                    'defaultBrowser' => Functions::meta($id, 'default_browser', 0) == '1' ? true : false,
                    'downloadButton' => Functions::meta($id, 'show_download_btn', 0) == '1' ? true : false,
                    'downloadButtonText' => Functions::meta($id, 'download_btn_text', 'Download File'),
                    'fullscreenButton' => Functions::meta($id, 'view_fullscreen_btn', 0) == '1' ? true : false,
                    'fullscreenButtonText' => Functions::meta($id, 'fullscreen_btn_text', 'View Fullscreen'),
                    'newWindow' => Functions::meta($id, 'view_fullscreen_btn_target_blank', 0) == '1' ? true : false,
                    'protect' => Functions::meta($id, 'protect', 0) == '1' ? true : false,
                    'thumbMenu' => Functions::meta($id, 'thumbnail_toggle_menu', 0) == '1' ? true : false,
                    'alert' => Functions::meta($id, 'disable_alert', '0') == '1' ? false : true,
                    'initialPage' => Functions::meta($id, 'jump_to', 0),
                ]).' /-->'
            ];
        endwhile;

        return $output;
    }


    // eov_import_meta();
    public static function settings(){
        $old = get_option('pdfp_settings', false);
        if(!$old){
           return false;
        }

        $custom_css = get_option('pdfp_css');

        $thumbnail_toggle_menu = get_p_option($old, 'thumbnail', 'false') == 'false' ? '0' : '1';
        $protect = get_p_option($old, 'protection', 'false') == 'true' ? '1' : '0';
        $disable_alert = get_p_option($old, 'disable_alert', 'true') == 'true' ? '1' : '0';
        $view_fullscreen_btn = get_p_option($old, 'view_full', 'false') == 'true' ? '1' : '0';
        $fullscreen_btn_text = get_p_option($old, 'view_full_text','View Full Screen');
        $show_download_btn = get_p_option($old, 'download_top', 'false') == 'true' ? '1' : '0';
        $download_btn_text = get_p_option($old, 'download_text', 'Download File');
        $show_filename = get_p_option($old, 'file_name', 'false') == 'true' ? '1' : '0';
        $default_browser = get_p_option($old, 'default_browser', 'true') == 'true' ? '1' : '0';
        $print = get_p_option($old, 'print', 'true') == 'true' ? '1' : '0';
        $unit = 'px';
        $width = get_p_option($old, 'width', '0');
        $height = get_p_option($old, 'height', 1122);
        $jump_to = get_p_option($old, 'onei_pp_jump_to_page', 1);

        
        if($width == '0' || $width == ''){
            $unit = '%';
            $width = 100;
        }

        $newData = array(
            'width' => [
                'width' => $width,
                'unit' => $unit,
            ],
            'height' => [
                'height' => $height,
                'unit' => 'px',
            ],
            'print' => $print,
            'default_browser' => $default_browser,
            'show_filename' => $show_filename,
            'view_fullscreen_btn' => $view_fullscreen_btn,
            'fullscreen_btn_text' => $fullscreen_btn_text,
            'view_fullscreen_btn_target_blank' => get_p_option($old, 'fullscreen_blank', true),
            'show_download_btn' => $show_download_btn,
            'download_btn_text' => $download_btn_text,
            'jump_to' => $jump_to,
            'disable_alert' => $disable_alert,
            'protect' => $protect,
            'thumbnail_toggle_menu' => $thumbnail_toggle_menu,
            'custom_css' => get_p_option($custom_css, 'custom_css', '')
        );

        if(get_option('fpdf_option', false) === false){		
            return update_option('fpdf_option', $newData);
        }
        return false;
    }

    public static function createBlockFromOld(){
        
        $docs = new \WP_Query([
            'post_type' => 'pdfposter',
            'post_status' => 'any',
            'posts_per_page' => -1
        ]);
        $output = [];
        while ($docs->have_posts()): $docs->the_post();
            $id = get_the_ID();
            $fpdf = get_post_meta($id, '_fpdfe', true);
            if($fpdf){
                return false;
            }
            
            $view_fullscreen_btn = fpdf_get_old_meta($id, 'view_full', [
                'pdfp_vfs_button_target' => 'on', 
                'pdfp_view_full_text' => 'View Fullscreen', 
                'enabled' => 'on'
            ]);

            $show_download_btn = fpdf_get_old_meta($id, 'download_button_text_con', ['pdfp_download_text' => 'Downlaod File', 'enabled' => 'on']);
            $width = get_post_meta($id, 'pdfp_onei_pp_width', true);

            if(!isset($view_fullscreen_btn['pdfp_vfs_button_target'])){
                $view_fullscreen_btn['pdfp_vfs_button_target'] = 'off';
            }
            if(!isset($view_fullscreen_btn['enabled'])){
                $view_fullscreen_btn['enabled'] = 'off';
            }
            if(!isset($show_download_btn['enabled'])){
                $show_download_btn['enabled'] = 'off';
            }

            $unit = 'px';
            if($width == '0' || $width == ''){
                $unit = '%';
                $width = 100;
            }

            $output[] = [
                'ID' => $id,
                'post_content' => '<!-- wp:pdfp/pdfposter '.json_encode([
                    'file' => get_post_meta($id, 'meta-image', true),
                    'title' => \basename(get_post_meta($id, 'meta-image', true)),
                    'height' => get_post_meta($id, 'pdfp_onei_pp_height', true) == '' ? '1122px' : get_post_meta($id, 'pdfp_onei_pp_height', true).'px',
                    'width' => get_post_meta($id, 'pdfp_onei_pp_width', true) == '' ? '100%' : get_post_meta($id, 'pdfp_onei_pp_width', true).'px',
                    'print' => get_post_meta($id, 'pdfp_onei_pp_print', true) === 'on' ? true : false,
                    'showName' => get_post_meta($id, 'pdfp_onei_pp_pgname', true) === 'on' ? true : false,
                    'onlyPDF' => false,
                    'defaultBrowser' => get_post_meta($id, 'pdfp_onei_pp_enable_default_viewer', true) === 'on' ? true : false,
                    'downloadButton' => $show_download_btn['enabled'] === 'on' ? true : false,
                    'downloadButtonText' => $show_download_btn['pdfp_download_text'],
                    'fullscreenButton' => $view_fullscreen_btn['enabled'] === 'off' ? false : true,
                    'fullscreenButtonText' => $view_fullscreen_btn['pdfp_view_full_text'],
                    'newWindow' => $view_fullscreen_btn['pdfp_vfs_button_target'] === 'on' ? true : false,
                    'protect' => get_post_meta($id, 'pdfp_onei_pp_right_click', true) === 'on' ? true : false,
                    'thumbMenu' => get_post_meta($id, 'pdfp_onei_pp_side', true) === 'on' ? true : false,
                    'alert' => get_post_meta($id, 'pdfp_onei_pp_disable_alert', true) === 'on' ? false : true,
                    'initialPage' =>  get_post_meta($id, 'pdfp_onei_pp_jump_to_page', true)
                ]).' /-->'
            ];
        
        endwhile;

        return $output;
    }
}

function fpdf_get_old_meta($id, $key, $default = null, $true = false){
	$meta = get_post_meta($id, $key, true);
    if($meta != ''){
        if($true === true){
            if($meta === '1'){
                return true;
            }else if($meta === '0'){
                return false;
            }
        }else {
            return $meta;
        }
        
    }else {
        return $default;
    }
}