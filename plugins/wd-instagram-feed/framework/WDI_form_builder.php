<?php
class WDI_form_builder  {

public function __construct() {
}

/*
 * $elements arguments
 * name
 * id // default is WDI_.$element_name
 * type // text, number...
 * attr // custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * input_size
 * label // array('text','place')
 * defaults // array of default vlaues
 * CONST // variable to store data in array
 */
  public function input($element,$feed_row=''){
      $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET') . ']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      $type = isset($element['input_type']) ? $element['input_type'] : 'text';

      $input_size= isset($element['input_size']) ? $element['input_size'] : '20';
      $label = isset($element['label']) ? $element['label'] : '';
      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }
      ?>
      <div class="wdwt_param" id="WDI_wrap_<?php echo esc_attr($element['name']); ?>">
        <div class="block">
          <div class="optioninput">
              <?php 
                if($label!='' && $label['place']=='before'){
                  ?>
                    <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                  <?php
                }
              ?>
              <input type="<?php echo esc_attr($type); ?>" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"
              <?php
              foreach ($element['attr'] as $attr) {
                echo esc_attr($attr['name']) . '="' . esc_attr($attr['value']) . '" ';
              } ?>
              size="<?php echo esc_attr($input_size); ?>">
              <?php 
                if($label!='' && $label['place']=='after'){
                  ?>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                    <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
                  <?php
                }
              ?>
          </div>
        </div>
      </div> 
      <?php
  }

/*
 * $elements arguments
 * name
 * id // default is WDI_.$element_name
 * type // multiple
 * attr // custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * label // array('text','place')
 * valid_options // array('option_value1'=>'option_name1','option_value2'=>'option_name2');
 * width
 * selected // one of valid options
 * defaults // array of default vlaues
 * CONST // variable to store data in array
 */
  public function select($element,$feed_row=''){
      $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET') . ']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      $type = isset($element['type']) ? $element['type'] : '';
      $label = isset($element['label']) ? $element['label'] : '';
      $width = isset($element['width']) ? ($element['width']) : '';
      $options = isset($element['valid_options']) ? $element['valid_options'] : ''; 

      $hide_ids = isset($element['hide_ids']) ? $element['hide_ids'] : '';
      $switched = (isset($element['switched']) && $element['switched'] === 'off') ? 'disabled' : '';
      $disabled_text = (isset($element['disabled']) && isset($element['disabled']['text'])) ? $element['disabled']['text'] : '';
      $disabled_options = isset($element['disabled_options']) ? $element['disabled_options'] : array();
      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $opt_value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }
      $attrs = ' ';
      foreach ($element['attr'] as $attr) {
        $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
      }
      $attr = $attrs;
     ?>
      <div class="wdwt_param" id="WDI_wrap_<?php echo esc_attr($element['name']); ?>">
      <div class="block">   
        <div class="optioninput"> 
        <?php 
          if($label!='' && $label['place']=='before'){
            ?>
              <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
              <?php echo isset($label['br']) ? '<br/>' : ''?>
            <?php
          }
          ?>
          <select <?php
          // All arguments in the $attr are esc.
          /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
          echo $attr; ?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" <?php echo esc_attr($switched); ?> <?php echo esc_attr($type); ?> style="<?php if($width!='') echo 'width:' .esc_attr($width) . ';';?> resize:vertical;">
          <?php foreach($options as $key => $value){ ?>
            <option <?php echo (in_array($key, $disabled_options)) ? 'disabled' : '' ?> value="<?php echo esc_attr($key) ?>" <?php if($key==$opt_value){echo 'selected';}?>>
              <?php echo esc_html($value); ?>
            </option>
          <?php } ?>
          </select>
          <?php
          if($label!='' && $label['place']=='after'){
            ?>
            <?php echo isset($label['br'])? '<br/>' : ''?>
              <label class="<?php echo isset($label['class']) ? esc_attr($label['class']) : '';?>" for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
            <?php
            }
            if($disabled_text != ''){ ?>
              <span class="wdi_pro_only"><?php echo esc_html($disabled_text); ?></span>
            <?php
            }
           ?>
        </div>
      </div>
    </div>
    <?php
        if($hide_ids != ''){
      ?>
      <style>
      .<?php echo esc_attr($id).'_hide_ids_hidden';?>{
        display:none !important;
      }
      </style>
      <script>
      jQuery(document).ready(function(){
        var <?php echo esc_attr($id).'_hide_ids';?> = <?php echo str_replace('&quot;', '"', esc_attr( wp_json_encode($hide_ids)));?>;
        jQuery("#WDI_wrap_<?php echo esc_attr($element['name']);?> select").on('change',function(){
          jQuery('.<?php echo esc_attr($id).'_hide_ids_hidden';?>').each(function(){
            jQuery(this).removeClass('<?php echo esc_attr($id).'_hide_ids_hidden';?>');
          });
          var selected = jQuery(this).val();
          for (var opt in <?php echo esc_attr($id).'_hide_ids'?>){
            if(opt == selected){
              var ids = <?php echo esc_attr($id).'_hide_ids'?>[opt].split(',');
              for (var i in ids){
                jQuery('#WDI_wrap_'+ids[i]).parent().parent().addClass("<?php echo esc_attr($id).'_hide_ids_hidden';?>");
              }
            }
          }
        });
        jQuery("#WDI_wrap_<?php echo esc_attr($element['name']);?> select").each(function(){
          var currentOption = "<?php echo esc_attr($opt_value)?>";
          if(jQuery(this).val() == currentOption){
            jQuery(this).trigger('change');
          }
        });
      });
      </script>
    <?php }
  }

/*
 * $elements arguments
 * name
 * id // default is WDI_.$element_name
 * type // multiple
 * attr  //custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * label  // array('text','place')
 * valid_options // array('option_value1'=>'option_name1','option_value2'=>'option_name2');
 * width
 * selected // one of valid options
 * defaults // array of default vlaues
 * CONST // variable to store data in array
 */
  public function selectgroup( $element, $feed_row='' ) {
      $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET') . ']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      $type = isset($element['type']) ? $element['type'] : '';
      $label = isset($element['label']) ? $element['label'] : '';
      $width = isset($element['width']) ? ($element['width']) : '';
      $options = isset($element['valid_options']) ? $element['valid_options'] : '';
      $hide_ids = isset($element['hide_ids']) ? $element['hide_ids'] : '';
      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $opt_value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }
      $attrs = ' ';
      foreach ($element['attr'] as $attr) {
        $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
      }
      $attr = $attrs;
     ?>
      <div class="wdwt_param" id="WDI_wrap_<?php echo esc_attr($element['name']); ?>">
      <div class="block">
        <div class="optioninput">
        <?php if ( !empty($label['place']) && $label['place'] == 'before' ) { ?>
          <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
          <?php echo isset($label['br']) ? '<br/>' : ''; ?>
        <?php } ?>
        <select <?php
        // All arguments in the $attr are esc.
        /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
        echo $attr; ?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" <?php echo esc_attr($type); ?> style="<?php if($width!='') echo 'width:' .esc_attr($width) . ';';?> resize:vertical;">
           <option value=""><?php _e('Select', 'wd-instagram-feed'); ?></option>
          <?php foreach($options as $label => $values) { ?>
             <optgroup label="<?php echo esc_attr(ucfirst($label)); ?>">
             <?php foreach ( $values as $key => $value ) {
               $selected = ( $key == $opt_value ) ? 'selected' : '';
               $data_type = 'data-type="' . esc_attr($label) . '"';
               $data_id = !empty($value['id']) ? 'data-id="' . esc_attr($value['id']) . '"': '';
               ?>
                <option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected); ?> <?php echo esc_attr($data_id); echo esc_attr($data_type);?>>
                  <?php echo esc_html($value['name']); ?>
                </option>
              <?php } ?>
              </optgroup>
          <?php } ?>
          </select>
          <?php
          if ( !empty($label['place']) && $label['place'] == 'after' ) { ?>
            <?php echo isset($label['br'])? '<br/>' : ''?>
              <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
            <?php
            }
           ?>
         </div>
      </div>
    </div>
      <?php if($hide_ids != ''){ ?>
      <style>
      .<?php echo esc_attr($id).'_hide_ids_hidden';?>{
        display: none !important;
      }
      </style>
      <script>
      jQuery(document).ready(function(){
        var <?php echo esc_attr($id) . '_hide_ids'; ?> = <?php echo str_replace('&quot;','"', esc_attr(wp_json_encode($hide_ids))); ?>;
        var hide_ids = <?php echo str_replace('&quot;', '"', esc_attr(wp_json_encode($hide_ids))); ?>;
        var hide_class = '<?php echo esc_attr($id).'_hide_ids_hidden'; ?>';

        function iterator(show) {
          for ( var i=0; i < hide_ids.length; i++ ) {
            var el = jQuery('#WDI_' + hide_ids[i]).closest('.wdi_element');
            if ( hide_ids[i] == 'hashtag_top_recent' ) {
              el = jQuery('#WDI_wrap_' + hide_ids[i]).closest('.wdi_element');
            }

            if( el.length == 0 ) {
              continue;
            }
            (show === true) ? el.removeClass(hide_class) : el.addClass(hide_class);
          }
        }

        function wdi_show_personal_block() {
           iterator(false);
        }

        function wdi_show_business_block() {
          iterator(true);
          jQuery('.wdi_element_name_hashtag_top_recent').hide();
          if ( jQuery('#wdi_feed_users_ajax .wdi_user').length > 0 ) {
            jQuery('.wdi_element_name_hashtag_top_recent').show();
          }
        }

        jQuery(document).on('change', '#WDI_wrap_<?php echo esc_attr($element['name']);?> select', function() {
          var element = jQuery(this).find('option:selected');
          var type = element.data('type');
          if ( typeof type != 'undefined' ) {
            switch (type) {
              case 'personal': {
                wdi_show_personal_block();
                break
              }
              default: {
                wdi_show_business_block();
                break
              }
            }
          }
          else {
            iterator(false);
          }
          var selected = jQuery(this).val();
          for ( var opt in <?php echo esc_attr($id).'_hide_ids'?> ) {
            if(opt == selected){
              var ids = <?php echo esc_attr($id).'_hide_ids'?>[opt].split(',');
              for (var i in ids){
                jQuery('#WDI_wrap_'+ids[i]).parent().parent().addClass("<?php echo esc_attr($id).'_hide_ids_hidden';?>");
              }
            }
          }
        });

        jQuery("#WDI_wrap_<?php echo esc_attr($element['name']);?> select").each(function(){
          var currentOption = "<?php echo esc_attr($opt_value)?>";
          if(jQuery(this).val() == currentOption){
            jQuery(this).trigger('change');
          }
        });
      });
      </script>
    <?php }
  }

/*
 * $elements arguments
 * name
 * id // default is WDI_.$element_name
 * attr // custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * label // array('text','place')
 * valid_options // array('option_value1'=>'option_name1','option_value2'=>'option_name2');
 * width
 * selected // one of valid options
 * defaults // array of default vlaues
 * CONST // variable to store data in array
 */
  public function radio($element,$feed_row=''){
	    $option_name = isset($element['name']) ? $element['name'] : 'NOT_SET';
      $name = $element['CONST'] . '[' . (isset($element['name']) ? $element['name'] : 'NOT_SET') . ']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      $label = isset($element['label']) ? $element['label'] : '';
      $width = isset($element['width']) ? ($element['width']) : '';
      $options = isset($element['valid_options']) ? $element['valid_options'] : ''; 
      $break = isset($element['break']) ? '<br/>' : ''; 
      $hide_ids = isset($element['hide_ids']) ? $element['hide_ids'] : '';
      $show_ids = isset($element['show_ids']) ? $element['show_ids'] : '';
      $disabled_options = isset($element['disabled_options']) ? $element['disabled_options'] : array();
      $attrs = ' ';
      foreach ($element['attr'] as $attr) {
        $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
      }
      $attr = $attrs;
      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $opt_value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }
      ?>
        <div class="wdwt_param" id="WDI_wrap_<?php echo esc_attr($element['name']);?>">
        <div class="block">
        <div class="optioninput">
        <?php
          if($label!='' && $label['place']=='before'){
            ?>
              <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
              <?php echo isset($label['br']) ? '<br/>' : ''?>
            <?php
          }
        foreach ( $options as $key => $option ) {
          $disable = '';
          $disable_text = '';
          foreach ($disabled_options as $disabled_option => $disable_lable) {
            if($disabled_option == $key){
              $disable = 'disabled';
              $disable_text = $disable_lable;
            }
          }
        ?>
          <input <?php echo esc_attr($disable);?> style="margin:2px;" type="radio" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($key); ?>" <?php checked($key,$opt_value); ?> <?php echo esc_attr($attr); ?> id="<?php echo esc_attr($option_name) . "_" . esc_attr($key);?>"/> <label for="<?php echo esc_attr($option_name) . "_" . esc_attr($key);?>"><?php echo esc_html($option); ?></label>
          <?php if($disable_text != '') : ?>
            <?php if(isset($disabled_options['br'])) : ?>
              <br>
            <?php endif; ?>
            <span class="wdi_pro_only"><?php echo esc_html($disable_text); ?></span>
          <?php endif;?>

          <?php echo esc_attr($break);?>
        <?php
      }
      if($label!='' && $label['place']=='after'){
                  ?>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                    <label class="<?php echo isset($label['class']) ? esc_attr($label['class']) : '';?>" for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
                  <?php
                }
      echo '</div></div></div>';

      if($hide_ids != ''){
      ?>
      <style>
      .<?php echo esc_attr($id).'_hide_ids_hidden';?>{
        display:none !important;
      }
      </style>
      <script>
      jQuery(document).ready(function(){
        var <?php echo esc_attr($id).'_hide_ids';?> = <?php echo str_replace('&quot;','"', esc_attr(wp_json_encode($hide_ids)));?>;
        jQuery("#WDI_wrap_<?php echo esc_attr($element['name']);?> input").on('click',function(){
          jQuery('.<?php echo esc_attr($id).'_hide_ids_hidden';?>').each(function(){
            jQuery(this).removeClass('<?php echo esc_attr($id).'_hide_ids_hidden';?>');
          });
          var selected = jQuery(this).val();
          for (var opt in <?php echo esc_attr($id).'_hide_ids'?>){
            if(opt == selected){
              var ids = <?php echo esc_attr($id).'_hide_ids'?>[opt].split(',');
              for ( var i in ids ) {
                jQuery('#WDI_wrap_'+ids[i]).parent().parent().addClass("<?php echo esc_attr($id).'_hide_ids_hidden';?>");
              }
            }
            
          }
        });
        jQuery("#WDI_wrap_<?php echo esc_attr($element['name']);?> input").each(function(){
          var currentOption = "<?php echo esc_attr($opt_value)?>";
          if(jQuery(this).val() == currentOption){
            jQuery(this).trigger('click');
          }
        });
      });
      </script>
    <?php }
    if($show_ids != ''){
      ?>
      <style>
    .<?php echo esc_attr($id).'_show_ids_show';?>{
      display:block !important;
    }
      </style>
      <script>
      jQuery(document).ready(function(){
        var <?php echo esc_attr($id).'_show_ids';?> = <?php echo str_replace('&quot;','"', esc_attr(wp_json_encode($show_ids)));?>;
        jQuery("#WDI_wrap_<?php echo esc_attr($element['name']);?> input").on('click',function(){
          jQuery('.<?php echo esc_attr($id).'_show_ids_show';?>').each(function(){
            jQuery(this).removeClass('<?php echo esc_attr($id).'_show_ids_show';?>');
          });
          var selected = jQuery(this).val();
          for (var opt in <?php echo esc_attr($id.'_show_ids') ?>){
            if(opt == selected){
              var ids = <?php echo esc_attr($id.'_show_ids')?>[opt].split(',');
              for (var i in ids){
                jQuery('#WDI_wrap_'+ids[i]).parent().parent().addClass("<?php echo esc_attr($id.'_show_ids_show');?>");
              }
            }
            
          }
        });
        jQuery("#WDI_wrap_<?php echo esc_attr($element['name']);?> input").each(function(){
          var currentOption = "<?php echo esc_attr($opt_value)?>";
          if(jQuery(this).val() == currentOption){
            jQuery(this).trigger('click');
          }
        });
      });
      </script>
    <?php }

  }

/*
 * $elements arguments
 * name
 * id // default is WDI_.$element_name
 * attr // custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * label // array('text','place')
 * width
 * selected // one of valid options
 * defaults // array of default vlaues
 * CONST // variable to store data in array
 */
  public function checkbox($element,$feed_row=''){
      $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET').']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      $label = isset($element['label']) ? $element['label'] : '';
      $width = isset($element['width']) ? ($element['width']) : ''; 
      $break = isset($element['break']) ? '<br/>' : ''; 
      $disable = (isset($element['switched']) && $element['switched']=='off') ? 'disabled' : '' ;

      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $opt_value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }

      $hide_ids = isset($element['hide_ids']) ? $element['hide_ids'] : '';
      ?>  
      <div class="wdwt_param" id="WDI_wrap_<?php echo esc_attr($element['name']);?>">
        <div class="block margin">
        <div class="optioninput checkbox">
         <?php
          if($label!='' && $label['place']=='before'){
            ?>
              <label class="<?php echo isset($label['class']) ? esc_attr($label['class']) : '' ?>" for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
              <?php echo isset($label['br'])? '<br/>' : ''?>
            <?php
          }
        ?>
        <input
         <?php
          foreach ($element['attr'] as $attr) {
           echo  esc_attr($attr['name']) . '="' . esc_attr($attr['value']) . '" ';
          }
        ?>
        <?php echo esc_attr($disable); ?> type="checkbox" class="checkbox" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id) ?>" <?php checked(1,$opt_value)?>  value="1">
        <?php
        if($label!='' && $label['place']=='after'){
                ?>
                  <?php echo isset($label['br'])? '<br/>' : ''?>
                  <label class="<?php echo isset($label['class']) ? esc_attr($label['class']) : '' ?>" for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label['text']); ?></label>
                <?php
              }
        ?>
        </div>
       </div>
      </div>
      <style>
      <?php if(!empty($hide_ids)){
        echo '.' . esc_attr($id) . '_hide_ids_hidden{display:none !important;}';
       }
      ?>
      </style>
      <script>
      jQuery(document).ready(function(){
          var el = jQuery('#<?php echo ''.esc_attr($id)?>');
          var hide_ids = <?php echo str_replace('&quot;','"', esc_attr(wp_json_encode($hide_ids))); ?>;
          var hide_class = '<?php echo esc_attr($id).'_hide_ids_hidden'; ?>';

          if(el.prop('checked') != true){
             el.after('<input type=\"hidden\" name=\"' + jQuery("#<?php echo ''.esc_attr($id)?>").attr("name") + '\" value="0">');
          }

          el.on('click',function(){

            if (jQuery(this).prop("checked") != true) {
                jQuery(this).after("<input type=\"hidden\" name=\"" + jQuery(this).attr("name") + "\" value=0>");
                if(Array.isArray(hide_ids) && hide_ids.length > 0){
                    iterator(false);
                }
            } else {
              jQuery(this).next().remove();
              if(Array.isArray(hide_ids) && hide_ids.length > 0){
                iterator(true);
              }
            }
          });

          if(Array.isArray(hide_ids) && hide_ids.length > 0){
              if(el.prop('checked') != true){
                iterator(false);
              }else{
                iterator(true);
              }
          }

          function iterator(show) {
            for (var i=0;i<hide_ids.length;i++){
              var el = jQuery("#WDI_"+hide_ids[i]).closest('.wdi_element');
              if(el.length == 0){
                  continue;
              }
              (show === true) ? el.removeClass(hide_class) : el.addClass(hide_class);
            }
          }
      });
      </script>
    <?php
  }


  public function how_to_publish($element,$feed_row=array()){
      $feed_id = (isset($feed_row['id'])) ? $feed_row['id'] : 0;
    ?>
          <div class="wdi_howto_container">
            <div class="wdi_howto_content">
                <div class="wdi_howto_wrapper">
                    <h2>Shortcode</h2>
                    <h4>Copy and paste this shortcode into your posts or pages:</h4>
                    <input type="text" class="wdi_howto_shortcode" value='[wdi_feed id="<?php echo esc_attr($feed_id); ?>"]' onclick="wdi_select_focus_element(this)" size="11" readonly="readonly" />
              </div>
            </div>
            <div class="wdi_howto_content">
            <div class="wdi_howto_wrapper">
              <h2>Page or Post editor</h2>
              <h4>Insert it into an existing post with the button:</h4>
              <img src="<?php echo esc_url(WDI_URL).'/images/how_to_publish_shortcode.png'; ?>">
              </div>
            </div>
            <div class="wdi_howto_content">
            <div class="wdi_howto_wrapper">
              <h2>PHP code</h2>
              <h4>Copy and paste the PHP code into your template file:</h4>
              <input type="text" class="wdi_howto_phpcode" value="&#60;?php echo wdi_feed(array('id'=>'<?php echo esc_attr($feed_id); ?>')); ?&#62;" onclick="wdi_select_focus_element(this)" size="17" readonly="readonly" />
              </div>
            </div>
            <div class="wdi_howto_content">
            <div class="wdi_howto_wrapper">
              <h2>Widget</h2>
              <h4>Add Instagram Feed Widget to your site:</h4>
              <img src="<?php echo esc_url(WDI_URL).'/images/how_to_publish_widget.png'; ?>">
              </div>
            </div>
            </div>
            <script>function wdi_select_focus_element(obj) {obj.focus();obj.select();}</script>
    <?php
  }

   /**
  * Displays a single color control
  * $elements arguments
  * name
  * CONST // variable to store data in array
  */
   public function color($element,$feed_row=''){
    $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET').']';
    $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
    $defaults = $element['defaults'];
    
    $attrs = ' ';
    foreach ($element['attr'] as $attr) {
      $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
      if($attr['name'] === 'tab'){
        $tab = $attr['value'];
      }
      if($attr['name'] === 'section'){
        $section = $attr['value'];
      }
    }
    $attr = $attrs;
    $current_settings = isset($feed_row) ? $feed_row : '';
    $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
    if($current_settings !=''){
     if($current_settings[$element['name']] != '') {
        $opt_value = $current_settings[$element['name']];
      }
    }
     ?>
     <div class="wdwt_param" id="WDI_wrap_<?php echo esc_attr($element['name']);?>">
       <div class='wdwt_float' >
         <div>
            <input type="text" class='color_input' id="<?php echo esc_attr($id) ?>" <?php echo esc_attr($attr);?> name="<?php echo esc_attr($name); ?>"   value="<?php echo esc_attr($opt_value); ?>" data-default-color="<?php echo esc_attr($defaults[$element['name']]); ?>" style="background-color:<?php echo esc_attr($opt_value); ?>;">
         </div>
       </div>
     </div>
     <script  type="text/javascript">
     jQuery(document).ready(function() {
       jQuery('.color_input').wpColorPicker();
       jQuery('#WDI_wrap_<?php echo esc_attr($element['name']);?> .wp-picker-container').attr('tab','<?php echo esc_attr($tab);?>');
       jQuery('#WDI_wrap_<?php echo esc_attr($element['name']);?> .wp-picker-container').attr('section','<?php echo esc_attr($section);?>');
     });
     </script>
     <?php
   }
}