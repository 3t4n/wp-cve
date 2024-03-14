<?php if( isset( $_GET['section'] ) && ! empty( $_GET['section'] ) ) {
    $section = sanitize_text_field( $_GET['section'] );
    if( in_array( $section, array_keys( $options['form_list'] ) ) ) {
        $this->get_form_settings_html( $section );
    } else{
        $back_url = remove_query_arg( 'section' ) ;?>
        <div class="ep-forms-tab-content">
            <h2>
                <?php esc_html_e( 'Manage Form Settings', 'eventprime-event-calendar-management' );?>
            </h2>
            <p>
                <a href="<?php echo esc_url( $back_url );?>">
                    <- <?php esc_html_e( 'Back', 'eventprime-event-calendar-management' );?>
                </a>
            </p>
            <p class="ep-settings-error">
                <?php esc_html_e( 'Wrong form key. Please go back and try again.', 'eventprime-event-calendar-management' );?>
            </p>
        </div><?php
    }
} else{?>
    <div class="ep-forms-tab-content">
        <h2><?php esc_html_e( 'Manage Form Settings', 'eventprime-event-calendar-management' );?></h2>
        <input type="hidden" name="em_setting_type" value="form_settings">
    </div>
    <div class="ep-settings-form-list">
        <table class="ep-setting-table-main">
            <tbody>
                <tr>
                    <td class="ep-setting-table-wrap" colspan="2">
                        <table class="ep-setting-table ep-setting-table-wide ep-from-manage-setting" cellspacing="0" id="ep_form-manage-setting">
                            <thead>
                                <tr>
                                    <th>
                                        <?php esc_html_e( 'Title', 'eventprime-event-calendar-management' );?>
                                    </th>
                                    <th>
                                        <?php esc_html_e( 'Description', 'eventprime-event-calendar-management' );?>
                                    </th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $options['form_list'] as $key => $form ){ ?>
                                    <tr>
                                        <td><?php echo $form['title'];?></td>
                                        <td><?php echo $form['description'];?></td>
                                        <td>
                                            <?php $tab_url = esc_url( add_query_arg( array( 'tab' => 'forms', 'section' => $key ) ) );?>
                                            <a href="<?php echo $tab_url; ?>" class="button alignright">
                                                <?php esc_html_e( 'Manage', 'eventprime-event-calendar-management' ); ?>
                                            </a>
                                        </td>
                                    </tr><?php
                                }?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table> 
    </div><?php
}?>