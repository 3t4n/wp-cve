<?php
function GSWPS_visual_form() {
    // Require admin rights
    if (!current_user_can('manage_options'))
        return false;
    $gswps_opt = array();
    if (isset($_POST['btnAdds'])) {
        // Sidebar options screen
        $gswps_opt['gswps_op_posts'] = ( isset( $_POST['gswps_posts'] ) && $_POST['gswps_posts'] != '' ) ? sanitize_text_field(intval($_POST['gswps_posts'] )) : '';
        $gswps_opt['gswps_op_odr'] = ( isset($_POST['gswps_order']) && $_POST['gswps_order'] != '' ) ? sanitize_text_field($_POST['gswps_order']) : '';
        $gswps_opt['gswps_op_theme'] = ( isset($_POST['gswps_theme']) && $_POST['gswps_theme'] != '' ) ? sanitize_text_field( $_POST['gswps_theme'] ) : '';
        $gswps_opt['gswps_op_cols'] = ( isset($_POST['gswps_cols']) && $_POST['gswps_cols'] != '' ) ? sanitize_text_field( $_POST['gswps_cols'] ) : '';
        $gswps_opt['gswps_op_autop'] = ( isset($_POST['gswps_autop']) && $_POST['gswps_autop'] != '' ) ? sanitize_text_field( $_POST['gswps_autop'] ) : ''; 
        $gswps_opt['gswps_op_pause'] = ( isset($_POST['gswps_pause']) && $_POST['gswps_pause'] != '' ) ? sanitize_text_field( $_POST['gswps_pause'] ) : ''; 
        $gswps_opt['gswps_op_loop'] = ( isset($_POST['gswps_inf_loop']) && $_POST['gswps_inf_loop'] != '' ) ? sanitize_text_field( $_POST['gswps_inf_loop'] ): '';
        $gswps_opt['gswps_op_speed'] = ( isset( $_POST['gswps_speed'] ) && $_POST['gswps_speed'] != '' ) ? sanitize_text_field( intval ( $_POST['gswps_speed'] )) : '';
        $gswps_opt['gswps_op_timeout'] = ( isset(  $_POST['gswps_timeout'] ) && $_POST['gswps_timeout'] != '' ) ? sanitize_text_field( intval ( $_POST['gswps_timeout'] )) : '';
        $gswps_opt['gswps_op_nav_speed'] = ( isset( $_POST['gswps_nav_speed'] ) && $_POST['gswps_nav_speed'] != '' ) ? sanitize_text_field( intval ( $_POST['gswps_nav_speed'] ) ): '';
        $gswps_opt['gswps_op_nav'] = ( isset($_POST['gswps_nav']) && $_POST['gswps_nav'] != '' ) ? sanitize_text_field( $_POST['gswps_nav'] ): '';
        $gswps_opt['gswps_op_dots_nav'] = ( isset($_POST['gswps_dots_nav']) && $_POST['gswps_dots_nav'] != '' ) ? sanitize_text_field( $_POST['gswps_dots_nav'] ): '';
        $gswps_opt['gswps_op_prod_tit_limit'] = ( isset($_POST['gswps_prod_tit_limit']) && $_POST['gswps_prod_tit_limit'] != '' ) ? sanitize_text_field( $_POST['gswps_prod_tit_limit'] ) : '';    
    }
    
    // Get all existing UPS options
    $existing_options = get_option('gswps_options');
    // Merge $gswps_opt into $existing_options to retain options from all other screens/tabs
    if ($existing_options) {
        $gswps_opt = array_merge($existing_options, $gswps_opt);
    }
    update_option('gswps_options', $gswps_opt);

    $options = get_option('gswps_options');
    ?>
    <div class="modal fade" id="gswps_visual_modal" tabindex="-1" role="dialog" aria-labelledby="gswpsModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: auto; max-height: 100%;">
          <div class="modal-header" style="background: #5cb85c; color: #fff; text-align: center;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="gswpsModalLabel">GS WooCommerce Products Slider</h4>
          </div>
          <div class="modal-body" style="height: 470px; overflow-y: scroll;">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">General</a></li>
                    <li><a href="#tab2" data-toggle="tab">More Options</a></li>
                </ul>
                <div class="tab-content">
                    <form role="form" name="frmvisual" method="post">
                        <div class="tab-pane active" id="tab1">
                            <div class="form-group">
                                <label for="gswps_posts">Number of Products : </label>
                                <input type="number" class="form-control" id="gswps_posts" name="gswps_posts" value="<?php echo ( isset($options['gswps_op_posts']) && trim($options['gswps_op_posts'] != '') ) ? $options['ups_op_columns'] : __('10', 'gswps'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="gswps_order">Display Order : </label>
                                <select class="form-control" id="gswps_order" name="gswps_order">
                                    <option value="DESC" <?php if (!isset($options['gswps_op_odr']) || $options['gswps_op_odr'] == 'DESC') echo ' selected'; ?>>Descending</option>
                                    <option value="ASC" <?php if (isset($options['gswps_op_odr']) && $options['gswps_op_odr'] == 'ASC') echo ' selected'; ?>>Ascending</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gswps_theme">Theme : </label>
                                <select class="form-control" id="gswps_theme" name="gswps_theme">
                                    <option value="gs-effect-1" <?php if (!isset($options['gswps_op_theme']) || $options['gswps_op_theme'] == 'gs-effect-1') echo ' selected'; ?>>Effect 1</option>
                                    <option value="gs-effect-2" <?php if (isset($options['gswps_op_theme']) && $options['gswps_op_theme'] == 'gs-effect-2') echo ' selected'; ?>>Effect 2</option>
                                    <option value="gs-effect-3" <?php if (isset($options['gswps_op_theme']) && $options['gswps_op_theme'] == 'gs-effect-3') echo ' selected'; ?>>Effect 3</option>
                                    <option value="gs-effect-4" <?php if (isset($options['gswps_op_theme']) && $options['gswps_op_theme'] == 'gs-effect-4') echo ' selected'; ?>>Effect 4</option>
                                    <option value="gs-effect-5" <?php if (isset($options['gswps_op_theme']) && $options['gswps_op_theme'] == 'gs-effect-5') echo ' selected'; ?>>Effect 5</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gswps_cols">Columns : </label>
                                <select class="form-control" id="gswps_cols" name="gswps_cols">
                                    <option value="4" <?php if (!isset($options['gswps_op_cols']) || $options['gswps_op_cols'] == '4') echo ' selected'; ?>>4 Columns</option>
                                    <option value="1" <?php if (isset($options['gswps_op_cols']) && $options['gswps_op_cols'] == '1') echo ' selected'; ?>>Single Column</option>
                                    <option value="2" <?php if (isset($options['gswps_op_cols']) && $options['gswps_op_cols'] == '2') echo ' selected'; ?>>2 Columns</option>
                                    <option value="3" <?php if (isset($options['gswps_op_cols']) && $options['gswps_op_cols'] == '3') echo ' selected'; ?>>3 Columns</option>
                                    <option value="4" <?php if (isset($options['gswps_op_cols']) && $options['gswps_op_cols'] == '4') echo ' selected'; ?>>4 Columns</option>
                                    <option value="5" <?php if (isset($options['gswps_op_cols']) && $options['gswps_op_cols'] == '5') echo ' selected'; ?>>5 Columns</option>
                                    <option value="6" <?php if (isset($options['gswps_op_cols']) && $options['gswps_op_cols'] == '6') echo ' selected'; ?>>6 Columns</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gswps_autop">Autoplay : </label>
                                <select class="form-control" id="gswps_autop" name="gswps_autop">
                                    <option value="true" <?php if (!isset($options['gswps_op_autop']) || $options['gswps_op_autop'] == 'true') echo ' selected'; ?>>True</option>
                                    <option value="false" <?php if (isset($options['gswps_op_autop']) && $options['gswps_op_autop'] == 'false') echo ' selected'; ?>>False</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gswps_pause">Pause : </label>
                                <select class="form-control" id="gswps_pause" name="gswps_pause">
                                    <option value="true" <?php if (!isset($options['gswps_op_pause']) || $options['gswps_op_pause'] == 'true') echo ' selected'; ?>>True</option>
                                    <option value="false" <?php if (isset($options['gswps_op_pause']) && $options['gswps_op_pause'] == 'false') echo ' selected'; ?>>False</option>
                                </select>
                            </div>
                        </div> <!-- end if tab1 -->

                        <div class="tab-pane" id="tab2">
                            <div class="form-group">
                                <label for="gswps_inf_loop">Inifnity Loop : </label>
                                <select class="form-control" id="gswps_inf_loop" name="gswps_inf_loop">
                                    <option value="true" <?php if (!isset($options['gswps_op_loop']) || $options['gswps_op_loop'] == 'true') echo ' selected'; ?>>True</option>
                                    <option value="false" <?php if (isset($options['gswps_op_loop']) && $options['gswps_op_loop'] == 'false') echo ' selected'; ?>>False</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gswps_speed">Autoplay Speed : </label>
                                <input type="number" class="form-control" id="gswps_speed" name="gswps_speed" value="<?php echo ( isset($options['gswps_op_speed']) && trim($options['gswps_op_speed'] != '') ) ? $options['ups_op_columns'] : __('1000', 'gswps'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="gswps_timeout">Autoplay Timeout : </label>
                                <input type="number" class="form-control" id="gswps_timeout" name="gswps_timeout" value="<?php echo ( isset($options['gswps_op_timeout']) && trim($options['gswps_op_timeout'] != '') ) ? $options['ups_op_columns'] : __('2500', 'gswps'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="gswps_nav_speed">Navigation speed : </label>
                                <input type="number" class="form-control" id="gswps_nav_speed" name="gswps_nav_speed" value="<?php echo ( isset($options['gswps_op_nav_speed']) && trim($options['gswps_op_nav_speed'] != '') ) ? $options['ups_op_columns'] : __('1000', 'gswps'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="gswps_nav">Navigation : </label>
                                <select class="form-control" id="gswps_nav" name="gswps_nav">
                                    <option value="initial" <?php if (!isset($options['gswps_op_nav']) || $options['gswps_op_nav'] == 'initial') echo ' selected'; ?>>Display</option>
                                    <option value="none" <?php if (isset($options['gswps_op_nav']) && $options['gswps_op_nav'] == 'none') echo ' selected'; ?>>Hide</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gswps_dots_nav">Dots navigation : </label>
                                <select class="form-control" id="gswps_dots_nav" name="gswps_dots_nav">
                                    <option value="true" <?php if (!isset($options['gswps_op_dots_nav']) || $options['gswps_op_dots_nav'] == 'true') echo ' selected'; ?>>True</option>
                                    <option value="false" <?php if (isset($options['gswps_op_dots_nav']) && $options['gswps_op_dots_nav'] == 'false') echo ' selected'; ?>>False</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gswps_prod_tit_limit">Product Title characters limit : </label>
                                <input type="number" class="form-control" id="gswps_prod_tit_limit" name="gswps_prod_tit_limit" value="<?php echo ( isset($options['gswps_op_prod_tit_limit']) && trim($options['gswps_op_prod_tit_limit'] != '') ) ? $options['ups_op_columns'] : __('15', 'gswps'); ?>">
                            </div>
                        </div> <!-- end if tab2 -->
                    </form>
                </div> <!-- tab-content -->
            </div> <!-- tabbable -->
          </div> <!-- end modal-body -->
        <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
            <input type="button" class="btn btn-success" id="gswps_add_code" value="Add Shortcode" name="btnAdds">
        </div>
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->
<?php
}