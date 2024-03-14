<?php
namespace YTP\Block;

use YTP\Model\Presets;

class Video {

    public function register(){
        add_action('init', [$this, 'init']);
    }

    public function init(){
        register_block_type( YTP_DIR_PATH . '/blocks/video', [
            'editor_style'  => 'ytp-blocks',
            'render_callback' => [$this, 'render'],
        ]);

        wp_localize_script( 'yt-player-video-editor-script', 'ytpPlayer',[
            'ajaxURL' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce( 'wp_ajax' ),
            'is_premium' => (boolean) ytp_fs()->can_use_premium_code(),
        ]);
        
    }

    public function render($attrs){
        $presetModel = new Presets();
        extract($attrs);
        $preset = $presetModel->getPreset($presetID);
        extract($this->dataParser($preset));

        if(!$source){
            return "Source Not Found";
        }

        wp_enqueue_style('ytp-public');
        wp_enqueue_script('ytp-public');

        ob_start();

        $classes = "ytPlayer ytWrapper align ";
        $classes .= isset($hideYoutubeUI) ? ($hideYoutubeUI ? ' hideYoutubeUI' : '') : '';
        $classes .= isset($hideControlsWhenPause) ? ($hideControlsWhenPause ? ' hideControlsWhenPause' : '') : '';

        ?>
        <div id=<?php echo esc_attr($uniqueId) ?> class="ytPlayer align" data-attributes="<?php echo esc_attr(wp_json_encode($attrs)); ?>"  data-preset="<?php echo esc_attr(wp_json_encode($preset ?? [])) ?>">
            <style><?php echo esc_html($CSS); ?></style>
            <div class="<?php echo esc_attr($classes) ?>">
                <!-- <div class="plyr__poster" style="background-image: url(<?php //echo esc_url($thumbnail) ?>);"></div> -->
            
                <div class="plyr__video-embed" id="player">
                    <iframe src="<?php echo esc_url($source); ?>?controls=0&amp;origin=<?php //echo esc_url(site_url()) ?>&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency ></iframe>
                </div>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }


   function dataParser($data){
        $tempData = $data ?? [];
        if (is_array($tempData)) {
          foreach($tempData as $key => $value){
            if(is_array($tempData[$key])){
                $tempData[$key] = $this->dataParser($tempData[$key]);
            }else {
                $tempData[$key] = $this->typeChecker($tempData[$key]);
            }
          }
        }
        return $tempData;
    }
      
    function typeChecker($value) {
        if ($value === "true") {
            return true;
        }
        if ($value === "false") {
            return false;
        }
        return $value;
    }
}
