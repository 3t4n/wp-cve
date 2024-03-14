<?php

class ViewSubmissions_cf7b {

  public function display( $data = array() ) {
    if( !CF7B_PRO ) {
      CF7B_Library::buy_pro_banner();
    }
    ?>
    <div class="wrap">
      <h2 class="wdi_page_title">Submissions</h2>
      <?php
      $this->list_view( $data );
      ?>
    </div>
    <?php
  }

  public function list_view( $datas = array() ) {
    wp_enqueue_style('cf7b_submissions');
    wp_enqueue_script('cf7b_submissions');

    $search = CF7B_Library::get('s');
    ?>
    <form id="posts-filter" method="get" action="?page=submissions_cf7b">

      <p class="search-box">
        <label class="screen-reader-text" for="post-search-input">Search Views:</label>
        <input type="hidden" name="page" value="submissions_cf7b">
        <input type="search" id="post-search-input" name="s" value="<?php echo esc_html($search) ?>">
        <input type="submit" id="search-submit" class="button" value="Search Views">
      </p>

      <div class="tablenav top">

        <div class="alignright actions">
          <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($datas) ?> item</span>
          <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed striped table-view-list posts">
          <thead>
            <tr>
              <td id="cb" class="manage-column column-cb check-column">
                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                <input id="cb-select-all-1" type="checkbox">
              </td>
              <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                <?php
                $order = (CF7B_Library::get('order', '', 'sanitize_text_field') == 'asc') ? 'desc' : 'asc';
                ?>
                <a href="?page=submissions_cf7b&orderby=title&order=<?php echo esc_url($order); ?>">
                <span>Title</span>
                  <span class="sorting-indicator"></span>
                </a>
              </th>
              <th scope="col" id="form_id" class="manage-column column-form_id">Form ID</th>
              <th scope="col" id="count" class="manage-column column-count">Count</th>
            </tr>
          </thead>

          <tbody id="the-list">
          <?php foreach ( $datas as $data)  { ?>
            <tr id="post-268" class="iedit author-self level-0 post-268 type-cf7-views status-publish hentry">
              <th scope="row" class="check-column">
                <label class="screen-reader-text" for="cb-select-268">Select <?php echo esc_html($data['title']); ?></label>
                <input id="cb-select-268" type="checkbox" name="post[]" value="<?php echo esc_html($data['form_id']); ?>">
                <div class="locked-indicator">
                  <span class="locked-indicator-icon" aria-hidden="true"></span>
                  <span class="screen-reader-text">“<?php echo esc_html($data['title']); ?>” is locked</span>
                </div>
              </th>
              <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                <div class="locked-info">
                  <span class="locked-avatar"></span>
                  <span class="locked-text"></span>
                </div>
                <strong><a class="row-title" href="?page=submissions_cf7b&task=view&id=<?php echo intval($data['form_id']); ?>" aria-label="“<?php echo esc_html($data['title']); ?>” (View)"><?php echo esc_html($data['title']); ?></a></strong>

                <div class="row-actions">
                <?php
                if( CF7B_PRO ) { ?>
                  <span class="view"><a href="?page=submissions_cf7b&task=view&id=<?php echo intval($data['form_id']); ?>"
                                        rel="bookmark"
                                        aria-label="View “<?php echo esc_html($data['title']); ?>”">View</a> | </span>
                  <span class="trash"><a
                      href="?page=submissions_cf7b&task=remove_submissions&id=<?php echo intval($data['form_id']); ?>"
                      class="submitdelete"
                      aria-label="Empty “<?php echo esc_html($data['title']); ?>” submissions">Empty</a></span>
                  <?php
                } else {
                ?>
                  <span class="view">View | </span>
                  <span class="trash">Empty</span>
                  <a href="<?php echo CF7B_UPGRADE_PRO_URL ?>" class="cf7b-upgrade-mini-button" target="_blank">Upgrade Pro</a>
                <?php
                }
                ?>
                </div>
              </td>
              <td class="shortcode column-form_id" data-colname="form_id"><?php echo intval($data['form_id']); ?></td>
              <td class="count column-count" data-colname="Count"><?php echo intval($data['count']); ?></td>
            </tr>
          <?php } ?>
          </tbody>

          <tfoot>
          <tr>
            <td class="manage-column column-cb check-column">
              <label class="screen-reader-text" for="cb-select-all-2">Select All</label>
              <input id="cb-select-all-2" type="checkbox">
            </td>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
              <?php
              $order = (CF7B_Library::get('order', '', 'sanitize_text_field') == 'asc') ? 'desc' : 'asc';
              ?>
              <a href="?page=submissions_cf7b&orderby=title&order=<?php echo esc_url($order); ?>">
                <span>Title</span>
                <span class="sorting-indicator"></span>
              </a>
            </th>
            <th scope="col" id="form_id" class="manage-column column-form_id">Form ID</th>
            <th scope="col" id="count" class="manage-column column-count">Count</th>
          </tr>
          </tfoot>
        </table>
    </form>
    <?php
  }

  public function view( $params ) {
    wp_enqueue_style('cf7b_submissions');
    wp_enqueue_script('cf7b_submissions');

    $form_id = CF7B_Library::get('id',0);
    ?>
    <div class="wrap">
      <h2 class="wdi_page_title">Submissions for <?php echo esc_html($params['title']); ?> form</h2>

      <div class="cf7b-subm-row cf7b-subm-row-title">
        <div class="cf7b-subm-title cf7b-subm-header">
          <div class="cf7b-form-id">ID</div>
          <div class="cf7b-form-created">Date</div>
          <div class="cf7b-form-ip_address">IP address</div>
        </div>
      </div>

      <?php

      foreach ( $params['submissions'] as $subm ) {

        ?>
        <div class="cf7b-subm-row">
          <div class="cf7b-subm-title">
            <div class="cf7b-form-id"><?php echo intval($subm['id']); ?></div>
            <div class="cf7b-form-created"><?php echo esc_html($subm['created']); ?></div>
            <div class="cf7b-form-ip_address"><?php echo esc_html($subm['ip_address']); ?></div>
            <a href="?page=submissions_cf7b&task=remove_submission&id=<?php echo intval($subm['id']); ?>&form_id=<?php echo intval($form_id); ?>" class="cf7b-subm-delete">
              <span class="dashicons dashicons-trash"></span>
            </a>
            <span class="dashicons dashicons-arrow-down-alt2"></span>
          </div>
          <div class="cf7b-subm-content cf7b-hidden">
            <?php
            $fields = json_decode($subm['fields'],1);
            $tr_fields = '';
            $tr_values = '';
            foreach ( $fields as $key => $field ) {
              $tr_fields .= '<th>'.$key.'</th>';

              if( is_array($field) ) { ?>
                  <?php
                  $tr_values .= '<td>';
                  foreach ( $field as $f ) {
                    $tr_values .= $f.', ';
                  }
                  $tr_values =rtrim($tr_values, ', ');
                  $tr_values .= '</td>';
              } else {
                  $tr_values .= '<td>'.$field.'</td>';
              }
            }
            ?>
            <table class="cf7b-subm-fields">
              <tr><?php echo $tr_fields; ?></tr>
              <tr><?php echo $tr_values; ?></tr>
            </table>
            <p><b>User Agent :</b> <?php echo esc_html($subm['user_agent']); ?></p>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
  }
}