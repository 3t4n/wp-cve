<?php namespace AjaxPagination\Frontend;

use Premmerce\SDK\V2\FileManager\FileManager;
use AjaxPagination\Admin\Settings;

/**
 * Class Frontend
 *
 * @package AjaxPagination\Frontend
 */
class Frontend
{


    private $options;
    /**
     * @var FileManager
     */
    private $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->options = get_option(Settings::OPTIONS);
        $this->registerActions();
    }

    public function registerActions()
    {

        if ($this->options['postsSelector'] && $this->options['navigationSelector']) {
            add_action('wp_head', array($this, 'addPaginationStyles'));
            add_action('wp_footer', array($this, 'addPaginationScript'), 100);
        }

    }


    public function showStylesCustom() {
        ?>
        <style>
            .wpap-loadmore-wrapper .wpap-loadmore-button{
                position: relative;
            }

            .wpap-loadmore-wrapper .wpap-loadmore-button .customize-partial-edit-shortcut{
                position: initial;

            }
        </style>
        <?php
    }

    public function addPaginationStyles()
    {
        if(is_customize_preview()){
            echo $this->showStylesCustom();
        }
        $bgUrl = $this->fileManager->locateAsset('frontend/img/loader.gif');
        $spinner = get_theme_mod('wpap_spinner_image', $bgUrl);
        $enable_loading = get_theme_mod('wpap_loading_effects_enable', 1);
        $spinner_size = get_theme_mod('wpap_spinner_size', 60);
        $opacity = get_theme_mod('wpap_opacity', 0.4);
        $color = get_theme_mod('wpap_background_color', 'black');

        $button_background = get_theme_mod('wpap_button_background_color', '#ffffff');
        $button_textcolor = get_theme_mod('wpap_button_text_color', '');
        $button_width = get_theme_mod('wpap_button_width', 150);
        $button_height = get_theme_mod('wpap_button_height', 50);
        $border_radius = get_theme_mod('wpap_border_radius', 0);
        $button_bold = get_theme_mod('wpap_button_bold_text', 0);
        $button_shadows = get_theme_mod('wpap_button_shadows', 1);

        switch ($color) {
            case 'black':
                $color_css = "background-color: rgba(0,0,0,{$opacity});";
                break;
            case 'white':
                $color_css = "background-color: rgba(255,255,255,{$opacity});";
                break;
            case 'none':
                $color_css = "background-color: none;";
                break;
        }

        $bold = '';
        if($button_bold){
            $bold = 'font-weight: bold;';
        }

        $shadows = '';
        if($button_shadows){
            $shadows = 'box-shadow: 1px 1px 10px 0 #b4b4b4;';
        }

        $color = '';
        if($button_textcolor != ''){
            $color = "color: $button_textcolor;";
        }

        echo "<style>";

        echo "
        .wpap-loadmore-wrapper{
            display: flex;
            align-items: center;
            justify-content: center;  
            margin: 20px 0; 
        }   
        .wpap-loadmore-wrapper .wpap-loadmore-button{
            background-color: $button_background;
            color: $button_textcolor;
            width: {$button_width}px;
            height: {$button_height}px;
            text-align: center;
            cursor: pointer;
            border-radius: {$border_radius}px;
            display: flex;
            align-items: center;
            justify-content: center;
            $bold;
            $shadows
            $color
        }
        .wpap-visibility-hidden{
           display: none;
        }";

        if ($this->options['paginationType'] == 'infinite-scroll') {
            echo "
        .wpap-loadmore-wrapper .wpap-loadmore-button{
           visibility: hidden;
        }";

        }

        if ($enable_loading) {
            echo "
            .wp-ajax-pagination-loading{
                position:fixed;
                display: block;
                top:0;
                left: 0;
                width: 100%;
                height: 100%;
                $color_css
                z-index:10000;
                background-image: url('{$spinner}');
                background-position: 50% 50%;
                background-size: {$spinner_size }px;
                background-repeat: no-repeat; 
            }
            ";
        }

        echo "</style>";

    }

    public function addPaginationScript()
    {

        $button_text = get_theme_mod('wpap_button_text', 'Load more');

        $pagingUrl = '';
        if($this->options['pagingUrl']){
            $pagingUrl = "window.history.pushState('', 'Title', link);";
        }

        if ($this->options['paginationType'] == 'ajax') {
            echo "<script type='text/javascript'>
          function makeHttpObject() {
            try {return new XMLHttpRequest();}
            catch (error) {}
            try {return new ActiveXObject(\"Msxml2.XMLHTTP\");}
            catch (error) {}
            try {return new ActiveXObject(\"Microsoft.XMLHTTP\");}
            catch (error) {}
            
            throw new Error(\"Could not create HTTP request object.\");
           }";

            echo "jQuery(document).ready(function($){";

            $i = 0;
            foreach ($this->options['postsSelector'] as $postsSelector) {
                echo "

                    if($(\"{$postsSelector}\").length != 0){
                    
                        $(document).on('click', \"{$this->options['navigationSelector'][$i]} a\", function (event) {
                            event.preventDefault();
                              
                            var link = $(this).attr('href');
                            
                            $('html, body').animate({
                                scrollTop: ($(\"{$postsSelector}\").offset().top - 200)
                            }, 200);    
                                                                                                   
                             $pagingUrl                    
                            $('body').append('<div class=\"wp-ajax-pagination-loading\"></div>');
                
                            var request = makeHttpObject();
                            request.open(\"POST\", link , true);
                            request.send(null);
                            request.onreadystatechange = function() {
                
                                if (request.readyState == 4){
                                         
                                    var htmlDoc = $( request.responseText );
                                    var html = htmlDoc.find('{$postsSelector}').html();
                                    var htmlNav = htmlDoc.find('{$this->options['navigationSelector'][$i]}').html();
                
                                    $(\"{$postsSelector}\").html(html);
                                    $(\"{$this->options['navigationSelector'][$i]}\").html(htmlNav);
                                    $('.wp-ajax-pagination-loading').remove();
                                    
                                    {$this->options['jsCode']}
                                }
                
                            };
                                 
                        });
                     }";
                $i++;
            }

            echo '});';
            echo '</script>';
        }


        if ($this->options['paginationType'] == 'loadmore-ajax') {

            global $wp_query;
            $link = html_entity_decode(get_pagenum_link());

            $max_pages = $wp_query->max_num_pages;
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            echo "<script type='text/javascript'>
            function makeHttpObject() {
                try {return new XMLHttpRequest();}
                catch (error) {}
                try {return new ActiveXObject(\"Msxml2.XMLHTTP\");}
                catch (error) {}
                try {return new ActiveXObject(\"Microsoft.XMLHTTP\");}
                catch (error) {}
            
                throw new Error(\"Could not create HTTP request object.\");
           }";

            echo "jQuery(document).ready(function($){";

            $i = 0;

            if ($paged != $max_pages) {

                foreach ($this->options['postsSelector'] as $postsSelector) {

                    echo "
        
        
                    if($(\"{$postsSelector}\").length != 0){
                    $(\"{$this->options['navigationSelector'][$i]}\").before(\"<div class='wpap-loadmore-wrapper'><span class='wpap-loadmore-button loadmore-button-{$i} wpap_button_text' data-pages='$max_pages' data-page='$paged' data-link='$link'>$button_text</span></div>\");
                    }
                    if($('.loadmore-button-{$i}').length != 0){
                      
                        var pageNext = $('.loadmore-button-{$i}').data('page');
                      
                        var pages = $('.loadmore-button-{$i}').data('pages');
                        
                         if(pageNext < pages){
                         pageNext++;
                         }
                    
                        $(document).on('click', \".loadmore-button-{$i}\", function (event) {
                            event.preventDefault();
                            
                            var link = $(this).data('link');   
                            var arr = link.split('?',2);
                            if(arr.length == 1){
                            link = link +'page/'+pageNext+'/'; 
                            }
                            if(arr.length == 2){
                            link = arr[0] +'page/'+pageNext+'/' +'?' + arr[1]; 
                            }
                                                                                                 
                            $pagingUrl                          
                            $('body').append('<div class=\"wp-ajax-pagination-loading\"></div>');
                
                            var request = makeHttpObject();
                
                            request.open(\"POST\", link , true);
                            request.send(null);
                            request.onreadystatechange = function() {
                
                                if (request.readyState == 4){
                                         
                                    var htmlDoc = $( request.responseText );
                                    var html = htmlDoc.find('{$postsSelector}').html();
                                    var htmlNav = htmlDoc.find('{$this->options['navigationSelector'][$i]}').html();
                
                                    $(\"{$postsSelector}\").children().last().after(html);
                                    $(\"{$this->options['navigationSelector'][$i]}\").html(htmlNav);
                                    $('.wp-ajax-pagination-loading').remove();
                                    
                                   
                                     if(pageNext == pages){
                                        console.log(pageNext);
                                       $('.loadmore-button-{$i}').remove();
                                     }else{
                                      pageNext++;
                                     }
                                                                                                                              
                                    {$this->options['jsCode']}
                                }
                            };                  
                        });                           
                   }";
                    $i++;
                }

            }
            echo '});';
            echo '</script>';
        }

        if ($this->options['paginationType'] == 'loadmore') {

            global $wp_query;
            $link = html_entity_decode(get_pagenum_link());
            $max_pages = $wp_query->max_num_pages;
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            echo "<script type='text/javascript'>
            function makeHttpObject() {
                try {return new XMLHttpRequest();}
                catch (error) {}
                try {return new ActiveXObject(\"Msxml2.XMLHTTP\");}
                catch (error) {}
                try {return new ActiveXObject(\"Microsoft.XMLHTTP\");}
                catch (error) {}
            
                throw new Error(\"Could not create HTTP request object.\");
           }";

            echo "jQuery(document).ready(function($){";

            $i = 0;

            if ($paged != $max_pages) {

                foreach ($this->options['postsSelector'] as $postsSelector) {

                    echo "
        
        
                    if($(\"{$postsSelector}\").length != 0){
                    $(\"{$this->options['navigationSelector'][$i]}\").before(\"<div class='wpap-loadmore-wrapper'><span class='wpap-loadmore-button loadmore-button-{$i} wpap_button_text' data-pages='$max_pages' data-page='$paged' data-link='$link'>$button_text</span></div>\");
                    $(\"{$this->options['navigationSelector'][$i]}\").addClass('wpap-visibility-hidden');
                    }
                    if($('.loadmore-button-{$i}').length != 0){
                      
                        var pageNext = $('.loadmore-button-{$i}').data('page');
                      
                        var pages = $('.loadmore-button-{$i}').data('pages');
                        
                         if(pageNext < pages){
                         pageNext++;
                         }
                    
                        $(document).on('click', \".loadmore-button-{$i}\", function (event) {
                            event.preventDefault();
                               
                             var link = $(this).data('link');   
                            var arr = link.split('?',2);
                            if(arr.length == 1){
                            link = link +'page/'+pageNext+'/'; 
                            }
                            if(arr.length == 2){
                            link = arr[0] +'page/'+pageNext+'/' +'?' + arr[1]; 
                            }                                                                          
                            $pagingUrl                          
                            $('body').append('<div class=\"wp-ajax-pagination-loading\"></div>');
                
                            var request = makeHttpObject();
                
                            request.open(\"POST\", link , true);
                            request.send(null);
                            request.onreadystatechange = function() {
                
                                if (request.readyState == 4){
                                         
                                    var htmlDoc = $( request.responseText );
                                    var html = htmlDoc.find('{$postsSelector}').html();
                                    var htmlNav = htmlDoc.find('{$this->options['navigationSelector'][$i]}').html();
                
                                    $(\"{$postsSelector}\").children().last().after(html);
                                    
                                    $('.wp-ajax-pagination-loading').remove();
                                    
                                   
                                     if(pageNext == pages){
                                        console.log(pageNext);
                                       $('.loadmore-button-{$i}').remove();
                                     }else{
                                      pageNext++;
                                     }
                                                                                                                              
                                    {$this->options['jsCode']}
                                }
                            };                  
                        });                           
                   }";
                    $i++;
                }

            }
            echo '});';
            echo '</script>';
        }


        if ($this->options['paginationType'] == 'infinite-scroll') {

            global $wp_query;
            $link = html_entity_decode(get_pagenum_link());
            $max_pages = $wp_query->max_num_pages;
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            echo "<script type='text/javascript'>
            function makeHttpObject() {
                try {return new XMLHttpRequest();}
                catch (error) {}
                try {return new ActiveXObject(\"Msxml2.XMLHTTP\");}
                catch (error) {}
                try {return new ActiveXObject(\"Microsoft.XMLHTTP\");}
                catch (error) {}
            
                throw new Error(\"Could not create HTTP request object.\");
           }";

            echo "jQuery(document).ready(function($){";

            $i = 0;

            if ($paged != $max_pages) {

                foreach ($this->options['postsSelector'] as $postsSelector) {

                    echo "
        
        
                    if($(\"{$postsSelector}\").length != 0){
                    $(\"{$this->options['navigationSelector'][$i]}\").before(\"<div class='wpap-loadmore-wrapper'><span class='wpap-loadmore-button loadmore-button-{$i} wpap_button_text' data-pages='$max_pages' data-page='$paged' data-link='$link'>$button_text</span></div>\");
                    $(\"{$this->options['navigationSelector'][$i]}\").addClass('wpap-visibility-hidden');
                    }
                    var button = $('.loadmore-button-{$i}');
                    
                    if(button.length != 0){
                                      
                        var pageNext = button.data('page');
                      
                        var pages = button.data('pages');
                        
                        var isLoading = false;
                        var endLoading = false;
                        
                         if(pageNext < pages){
                         pageNext++;
                         }
                                              
                         $(window).scroll(function(){
                         
                            if( $(document).scrollTop() + $(window).height()  > button.offset().top && button.offset().top > $(document).scrollTop() && !isLoading && !endLoading){
                                    
                                 var link = button.data('link');   
                            var arr = link.split('?',2);
                            if(arr.length == 1){
                            link = link +'page/'+pageNext+'/'; 
                            }
                            if(arr.length == 2){
                            link = arr[0] +'page/'+pageNext+'/' +'?' + arr[1]; 
                            }                                                                          
                                $pagingUrl  
                                $('body').append('<div class=\"wp-ajax-pagination-loading\"></div>');
                                isLoading = true;                       

                                var request = makeHttpObject();
                    
                                request.open(\"POST\", link , true);
                                request.send(null);
                                request.onreadystatechange = function() {
                    
                                    if (request.readyState == 4){
                                             
                                        var htmlDoc = $( request.responseText );
                                        var html = htmlDoc.find('{$postsSelector}').html();
                                        var htmlNav = htmlDoc.find('{$this->options['navigationSelector'][$i]}').html();
                    
                                        $(\"{$postsSelector}\").children().last().after(html);
                                        
                                        $('.wp-ajax-pagination-loading').remove();
                                        isLoading = false;
                                                                         
                                         if(pageNext == pages){
                                           button.remove();
                                           endLoading = true;
                                         }else{
                                          pageNext++;
                                         }
                                                                                                                                  
                                        {$this->options['jsCode']}
                                    }
                                }; 
                                 
                            }
                        });
                            
                   }";
                    $i++;
                }

            }
            echo '});';
            echo '</script>';
        }


    }

}