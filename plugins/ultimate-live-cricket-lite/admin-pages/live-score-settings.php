<?php  
  if ( isset( $_POST['save_series_settings'] ) ) {

      update_option( 'col_per_row',    $_POST['col_per_row'] );
      update_option( 'base_color',     sanitize_text_field( $_POST['base_color'] ) );
      update_option( 'series-layout',  $_POST['series-layout'] );
      update_option( 'cric_api',       $_POST['cric_api'] );
      $update_message  = "Settings Updated";
  }
?>
<div class="wrap">
  <h2 class='opt-title'>

    <?php _e( 'Cricket Live Score Plugin Settings', 'wss-live-score'); ?>
  </h2>
  
  <?php
      if (isset( $update_message ) ){ 

        echo '<div id="setting-error-settings_updated" class="alert alert-success below-h2"><strong>'.$update_message.'</strong></div>';
      }
          $live_score = new LCW_Live_Score();   

      if ( isset ( $_GET['tab'] ) ) {

          $live_score->lcw_settings_tabs( $_GET['tab'] );

      }else{ 

        $live_score->lcw_settings_tabs( 'series' );
      }
      if ( isset ( $_GET['tab'] ) ) {

        $tab = $_GET['tab']; 
      }
      else {

        $tab = 'series';
      }
      
      // series Tab section
      if( $tab == 'series' ) {
  ?>
        <h3>Series Page settings</h3>
        <form action="" method="post">
          <table>
            
            <tr>
              <td width="400">
                <?php echo _e('How many columns in per row ','wss-live-score'); ?>
              </td>
              <td width="400">
                    <select name="col_per_row"  style="width: 300px"  class="select2">
                        <option value="6" <?php selected( get_option('col_per_row'),'6') ?>>2</option>
                        <option value="4" <?php selected( get_option('col_per_row'),'4') ?>>3</option>
                        <option value="3" <?php selected( get_option('col_per_row'),'3') ?>>4</option>
                    </select>
              </td>
            </tr>
            <tr>
              <td width="400">
                <?php echo _e('Series Layout','wss-live-score'); ?>
              </td>
              <td width="400">
                    <select name="series-layout"  style="width: 300px"  class="select2">
                        <option value="1" <?php selected( get_option('series-layout'),'1') ?>>Layout 1</option>
                        <option value="2" <?php selected( get_option('series-layout'),'2') ?>>Layout 2</option>
                        
                    </select>
              </td>
            </tr>
            <tr>
              <td width="400"><?php _e('Base Color','wss-live-score'); ?></td>
              <td>
                <input type="text" name="base_color" class="color-field" value="<?php echo get_option('base_color') ?>" style="width:300px;">
              </td>
            </tr>
            
            <tr>
              <td>
                  <input type="submit" name="save_series_settings" value="Save Settings" class="button-primary">
              </td>    
            </tr>
          </table>

        </form>
        
<?php } ?>
</div>