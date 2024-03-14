<?php
namespace YTP\Block;

use YTP\Model\Presets;

class Timeline {

    public function register(){
        add_action('init', [$this, 'init']);
    }

    public function init(){
        register_block_type( YTP_DIR_PATH . '/blocks/timeline', [
            'editor_style'  => 'ytp-blocks',
            'render_callback' => [$this, 'render'],
        ]);

        wp_localize_script( 'yt-player-video-editor-script', 'ytpPlayer',[
            'ajaxURL' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce( 'wp_ajax' )
        ]);
        
    }

    public function render($attrs){
        $presetModel = new Presets();
        extract($attrs);

        wp_enqueue_style('ytp-public');
        wp_enqueue_script('ytp-public');

        ob_start(); ?>

        <div class="timelineBlock" data-attributes="<?php echo esc_attr(wp_json_encode($attrs)) ?>">timelineBlock</div>


        <?php return ob_get_clean();
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
