<div class="cluevo-help-tab-container">
  <h2><?php esc_html_e("General Settings", "cluevo"); ?></h2>
  <p><?php esc_html_e("You can set various settings here.", "cluevo"); ?></p>
  <h3><?php esc_html_e("Titles / Levels", "cluevo"); ?></h3>
  <p><?php esc_html_e("You can set different titles for your levels. The amount of experience points needed for a levelup is calculated from the amount required for the initial level-up.", "cluevo"); ?></p>
  <div class="exp-table-container">
    <table>
      <tr>
        <th><?php esc_html_e("Level", "cluevo"); ?></th>
        <th><?php esc_html_e("Next Level", "cluevo"); ?></th>
      </tr>
    <?php
      $table = cluevo_get_exp_table();
      foreach ($table as $level => $exp) { ?>
        <tr>
          <td><?php echo esc_html($level); ?></td>
          <td><?php echo esc_html($exp); ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>
</div>
