<h2 class="title"><?php _e('General Settings','conf-scheduler')?></h2>
<table class="form-table">
  <tbody>
    <tr>
      <th scope="row"><?php _e('Time Format','conf-scheduler')?></th>
      <td>
        <fieldset>
          <legend class="screen-reader-text"><span><?php _e('Time Format','conf-scheduler')?></span></legend>
          <label for="time_format">
          <?php echo sprintf(
              __('Set the time format in the %s.','conf-scheduler'),
              '<a href="'.admin_url('options-general.php').'">'.__('General Settings', 'conf-scheduler').'</a>'
            );?></label>
        </fieldset>
      </td>
    </tr>
  </tbody>
</table>

<h2 class="title"><?php _e('Style Settings','conf-scheduler');?></h2>
<p><?php echo sprintf(
  __('Basic Conference Scheduler style settings can be set in the %s.','conf-scheduler'),
  '<a href="'.
  esc_url(
    add_query_arg(
      array('autofocus[section]' => 'conf_scheduler'),
      admin_url( 'customize.php' )
    )
  ).
  '">'.
  __('Conference section of the Customizer', 'conf-scheduler').'</a>'
);?></p>
<p style="margin-bottom:40px;"><?php sprintf(
    __('For advanced customization, use %s or edit your theme CSS. The layout of the workshop block can also be %s.','conf-scheduler'),
    '<a href="'.esc_url( add_query_arg( array('autofocus[section]' => 'custom_css'), admin_url( 'customize.php' ) ) ).'">'.__('Custom CSS rules', 'conf-scheduler').'</a>',
    '<a href="https://conferencescheduler.com/documentation/#customization">'.__('fully customized using a theme template', 'conf-scheduler').'</a>'
  );?>
  </p>
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><?php _e('Filter by Multiple','conf-scheduler')?></th>
        <td>
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Filter by Multiple','conf-scheduler')?></span></legend>
          	<label for="filter_multiple"><input name="filter_multiple" id="filter_multiple" type="checkbox" value="1"<?php if(get_option('conf_scheduler_filter_multiple', false)) echo' checked';?>/>
          	<?php _e('Allow users to filter displayed workshops by multiple keywords/themes.','conf-scheduler')?></label>
            <p class="description"><?php _e('Workshops with ALL selected filter values with be displayed.','conf-scheduler')?></p>
          </fieldset>
        </td>
      </tr>
    </tbody>
  </table>

  <h2 class="title"><?php _e('Remove All Data','conf-scheduler')?></h2>
  <p><?php _e('Use these buttons to permenantly remove all data from the database.<br/><strong>Caution</strong> - this action cannot be undone.');?></p>
  <script>
    jQuery( document ).ready( function( $ ) {
      conf_scheduler_admin.cs_nonce = '<?php echo wp_create_nonce( 'conf-scheduler-delete-data' );?>';
    });
  </script>
  <table class="remove_data">
    <?php
      $buttons = apply_filters('conf_scheduler_delete_data_buttons', array(
        'delete_workshops' => __('Delete all workshops','conf-scheduler'),
        'delete_sessions' => __('Delete all sessions','conf-scheduler'),
        'delete_themes' => __('Delete all themes','conf-scheduler'),
        'delete_keywords' => __('Delete all keywords','conf-scheduler'),
      ));
      $i = 0;
      foreach ($buttons as $action => $text) {
        if ($i % 2 == 0) echo '<tr>';
        echo '<td><label for="'.$action.'"><button class="button button-primary" data-action="'.$action.'" type="button"><span class="dashicons dashicons-trash"></span> '.$text.'</button></label></td>';
        if ($i % 2 == 1) echo '</tr>';
        $i++;
      }
      if ($i % 2 == 1) echo '<td></td></tr>';
      ?>
  </table>

<?php do_action('conf_scheduler_options', $options); ?>
