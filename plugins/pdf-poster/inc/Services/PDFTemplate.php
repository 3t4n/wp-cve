<?php
namespace PDFPro\Services;
use PDFPro\Helper\DefaultArgs;
use PDFPro\Helper\Functions;
use PDFPro\Services\AnalogSystem;

class PDFTemplate{
    public static $uniqid = null;
    protected static $styles = [];
    protected static $mediaQuery = [];
    protected static $data = null;

    public static function html($data){
        self::createId();

        if($data['template']['adobeEmbedder']){
            wp_enqueue_script('adobe-viewer');
        }

        self::enqueueEssentialAssets();
        
        $iid = self::$uniqid;
        self::$data = $data;
        self::$data['template'] = self::finalizeData($data['template']);
        $t = self::$data['template'];

        $pdf_path = $data['template']['file'];
        if($t['protect']){
            $pdf_path = $t['file'];
        }
        ob_start();

		?>
		<style>
            <?php echo esc_html(self::renderStyle($data['template'])); ?>
            <?php echo esc_html(Functions::isset($data['additional'], 'CSS')); ?>
            <?php echo esc_html(Functions::isset($data['template'], 'CSS')); ?>
        </style>
        <?php 
            if($t['file']){ 
                if($data['template']['adobeEmbedder']){
                    if($data['infos']['adobeOptions']['embedMode'] === 'LIGHT_BOX'){
                        ?>
                           <div data-align="<?php echo esc_attr($data['template']['align']) ?>"> 
                            <button
                                data-id="<?php echo esc_attr($iid) ?>" 
                                data-options='<?php echo esc_attr(esc_attr(wp_json_encode($data['infos']['adobeOptions']))) ?>' 
                                data-href='<?php echo esc_attr(Functions::scramble('encode', $data['template']['file'])) ?>' 
                                data-protect="1"
                                class='<?php echo esc_attr($data['template']['uniqueId']) ?>btn pdfp-adobe-viewer'>
                                    <?php echo esc_html($data['template']['popupBtnText']) ?>
                            </button>
                            </div>
                        <div id='<?php echo esc_attr($iid) ?>'>
                        </div>
                        <?php
                    }else {
                        echo "<div id='$iid' data-align='". esc_attr($data['template']['align'])."'><a data-protect='".$t['protect']."' class='pdfp-adobe-viewer' data-options='".esc_attr(wp_json_encode($data['infos']['adobeOptions']))."' href='".$pdf_path."'></a></div>";
                    }
                }else if(strpos($data['template']['file'], 'dropbox.com')){
                    wp_enqueue_script('dropbox-picker');
                    self::$data['template']['file'] = $data['template']['file'];
                    self::useDropbox();
                }else if(strpos($data['template']['file'], '.google.com/')) {
                    self::$data['template']['file'] = $data['template']['file'];
                    self::useGoogleDrive();
                }else {
                    self::useLibrary();
                }
                
            } else { 
                echo "<h3>Oops! You forgot to select a pdf file. </h3>";
            }

        return ob_get_clean();
    }

    public static function enqueueEssentialAssets(){
        wp_enqueue_style('pdfp-public');
        wp_enqueue_script('pdfp-public');
    }

    public static function finalizeData($t){
        if($t['lastVersion']){
            $t['file'] = $t['file'].'?'.time();
        }
        
        if($t['protect']){
            // if(true){
            $t['file'] = Functions::scramble('encode', $t['file']);
            $t['print'] = 'false';
            $t['download'] = 'false';
            $viewer_base_url = PDFPRO_PLUGIN_DIR."pdfjs-new/web/pviewer.html";
        }else{
            $viewer_base_url = PDFPRO_PLUGIN_DIR."pdfjs-new/web/viewer.html";
            if($t['downloadButton']){
                $t['download'] = 'vera';
            }
        }

        $hr = $t['hrscroll'] == '1' ? 'vera' : '';

        $zoomLevel = "&z=auto";
        if($t['zoomLevel'] !== 'auto'){
            $zoomLevel = "&z=". (int) $t['zoomLevel']/100;
        }

        $t['final_url'] = $viewer_base_url."?file=".$t['file']."&nobaki=".$t['download'].$zoomLevel."&stdono=".$t['print']."&onlypdf=".$t['onlyPDF']."&raw=".$t['raw']."&fullscreen=".$t['fullscreenButton']."&sidebarOpen=".$t['sidebarOpen']."&side=".$t['thumbMenu']."&open=false&hrscroll=".$hr."#page=".$t['initialPage'];

        if($t['defaultBrowser'] && Functions::getBrowser() == 'Edge'){
            $t['final_url'] = "//docs.google.com/gview?embedded=true&url=".$t['file'];
        }
        if($t['defaultBrowser'] && Functions::getBrowser() == 'Edge' && $t['protect']){
            $t['final_url'] = "https://bplugins.com/".$t['file']."?google=//docs.google.com/gview?embedded=true&url=";
        }

        if($t['protect']){
            $t['demo_url'] = $viewer_base_url."?file=".PDFPRO_PLUGIN_DIR.'img/loading.pdf';
        }else {
            $t['demo_url'] = $t['final_url'];
        }

        return $t;
    }

    public static function renderStyle($t){
        $id = self::$uniqid;
        self::addStyle("#$id .title", ['font-size' => $t['titleFontSize']]);
        self::addStyle("#$id iframe", ['height' => $t['height']]);
        self::addStyle("#$id", ['width' => $t['width']]);
        if($t['raw']){
            self::addStyle("#$id iframe", ['border' => '2px solid #d7d7d7']);
        }
        if($t['adobeEmbedder'] && $t['embedMode'] != 'LIGHT_BOX'){
            self::addStyle("#$id", ['height' => $t['height']]);
        }

        $output = '';
        foreach(self::$styles as $selector => $style){
            $new = '';
            foreach($style as $property => $value){
                if($value == ''){
                    $new .= $property;
                }else {
                    $new .= " $property: $value;";
                }
            }
            $output .= "$selector { $new }";
        }
        
        foreach(self::$mediaQuery as $query => $styles){
            $output .= $query."{";
            foreach($styles as $selector => $style){
                $new = '';
                foreach($style as $property => $value){
                    if($value == ''){
                        $new .= $property;
                    }else {
                        $new .= " $property: $value;";
                    }
                }
                $output .= "$selector { $new }";
            }
            $output .= "}";
        }

        return $output;
    }

    public static function addStyle($selector, $styles, $mediaQuery = false){
        if($mediaQuery){
            if(array_key_exists($mediaQuery, self::$mediaQuery)){
                if(array_key_exists($selector, self::$mediaQuery[$mediaQuery])){
                    self::$mediaQuery[$mediaQuery][$selector] = wp_parse_args(self::$mediaQuery[$mediaQuery][$selector], $styles);
                }else {
                    self::$mediaQuery[$mediaQuery] = wp_parse_args(self::$mediaQuery[$mediaQuery], [$selector => $styles]);
                }
             }else {
                 self::$mediaQuery[$mediaQuery] = [$selector => $styles];
             }
        }else {
            if(array_key_exists($selector, self::$styles)){
                self::$styles[$selector] = wp_parse_args(self::$styles[$selector], $styles);
             }else {
                 self::$styles[$selector] = $styles;
             }
        }
        
    }


    public static function createId(){
        self::$uniqid = "pdfp".uniqid();
    }

    public static function splice($string){
        if(strlen($string) < 45){
            return $string;
        }
        return substr($string, 0, 40)."...";
    }

    public static function useDropbox(){
        $t = self::$data['template'];
        ?>
        <div class="<?php echo esc_attr(Functions::isset(self::$data['additional'], 'Class')) ?>">
            <a href="<?php echo esc_url(self::$data['template']['file']) ;?>" class="dropbox-embed" data-height="<?php echo esc_attr($t['height']) ;?>" data-width="<?php echo esc_attr($t['width']) ; ?>">
            </a>
        </div>
        <?php
    }

    public static function useGoogleDrive(){
        $t = self::$data['template'];
        ?>
        <style>
            <?php echo esc_attr(self::$uniqid); ?>{
                margin: 0 auto;

            }
        </style>
        <div id="<?php echo esc_attr(self::$uniqid); ?>" class="pdfp_wrapper <?php echo esc_attr(Functions::isset(self::$data['additional'], 'Class')) ?>" data-infos="<?php echo esc_attr(wp_json_encode(self::$data['infos'])) ?>">
            <div class="cta_wrapper">
                <?php if($t['showName']): ?>
                    <p class="title"><?php echo esc_html($t['title'] ? $t['title'] : basename(Functions::scramble('decode', $t['file'])));?></p>
                <?php endif;?>

                <?php if($t['downloadButton'] && !$t['protect']): ?>
                    <a class="pdfp_download" download href="<?php echo $t['file']; ?>"><button class="pdfp_download_btn" style="margin-right:15px;"><?php echo esc_html($t['downloadButtonText']); ?></button></a>
                <?php endif; ?>

                <?php if($t['fullscreenButton'] && !$t['protect']): ?>
	                <a class="pdfp_fullscreen" target="<?php echo $t['newWindow'] ? '_blank' : '_self' ?>" href="<?php echo esc_url($t['final_url']); ?>"><button><?php echo esc_html($t['fullscreenButtonText']) ?></button></a>
                <?php endif; 
                ?>

            </div>
        <div class="pdf">
            <iframe id="<?php echo esc_attr("frame-".self::$uniqid); ?>" src="<?php echo esc_url(self::$data['template']['file']); ?>&new" style="margin:0 auto;<?Php echo 'width:' . esc_attr($t['width']) . ';height:' . esc_attr($t['height']); ?>" frameborder="0">
            </iframe>
        <?php if($t['protect']){ ?>
                <div class="popout-disabled"></div>
        <?php } ?>
        </div>
                </div>
        <?php
    }

    public static function useLibrary(){
        $t = self::$data['template'];

        ?>
        <div id="<?php echo esc_attr(self::$uniqid); ?>" class="pdfp_wrapper <?php echo esc_attr(Functions::isset(self::$data['additional'], 'Class')) ?>" data-infos="<?php echo esc_attr(wp_json_encode(self::$data['infos'])) ?>">
            <div class="cta_wrapper">
                <?php if($t['showName']): ?>
                    <p class="title"><?php echo esc_html($t['title'] ? $t['title'] : basename($t['protect'] ? Functions::scramble('decode', $t['file']) : $t['file']));?></p>
                <?php endif;?>

                <?php if($t['downloadButton'] && !$t['protect']): ?>
                    <a class="pdfp_download" download href="<?php echo esc_url($t['file']); ?>"><button class="pdfp_download_btn" style="margin-right:15px;"><?php echo esc_html($t['downloadButtonText']); ?></button></a>
                <?php endif; ?>

                <?php if($t['fullscreenButton'] && !$t['protect']): ?>
	                <a class="pdfp_fullscreen" target="<?php echo $t['newWindow'] ? '_blank' : '_self' ?>" href="<?php echo esc_url($t['final_url']); ?>"><button><?php echo esc_html($t['fullscreenButtonText']) ?></button></a>
                <?php endif; ?>

            </div>
            <div class="iframe_wrapper" >
                <span class="close">&times;</span>
                <iframe title="<?php echo esc_attr(basename($t['final_url'])) ?>" id="<?php echo esc_attr("frame-".self::$uniqid); ?>" class="pdfp_iframe" width="<?php echo esc_attr($t['width']); ?>" height="<?php echo esc_attr($t['height']);?>" data-source="<?php echo esc_url($t['final_url']);?>&new" src="<?php echo esc_url($t['demo_url']);?>"></iframe>
            </div>
	    </div>
        <?php
    }

}