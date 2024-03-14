<div class="wrap wphr wphr-company-single">
    <h2><?php _e( 'Company', 'wphr' ); ?></h2>

    <?php if ( isset( $_GET['msg'] ) && sanitize_text_field( $_GET['msg'] ) == 'updated' ) { ?>
        <div class="updated">
            <p><?php _e( 'Company information has been updated!', 'wphr' ); ?></p>
        </div>
    <?php } else if ( isset( $_GET['msg'] ) && sanitize_text_field($_GET['msg']) == 'error' ) { ?>
        <?php
            if ( ! empty( $_GET['error-company'] ) ) {
                $errors[] = __( 'Company name is required', 'wphr' );
            }

            if ( ! empty( $_GET['error-country'] ) ) {
                $errors[] = __( 'Country is required', 'wphr' );
            }

            foreach ( $errors as $error ) {
                printf( '<div class="error"><p>%s</p></div>', $error );
            }
        ?>
    <?php } ?>

    <?php $country = \WPHR\HR_MANAGER\Countries::instance(); ?>

    <form action="" method="post" id="wphr-new-company">
        <div class="wphr-single-container">
            <div class="wphr-area-left">
                <div id="titlediv" style="margin-bottom: 20px;">
                    <div id="titlewrap">
                        <label class="screen-reader-text" id="title-prompt-text" for="title"><?php _e( 'Enter company name here', 'wphr' ); ?></label>
                        <input type="text" name="name" size="30" value="<?php echo esc_attr( $company->name ); ?>" id="title" autocomplete="off">
                    </div>
                </div>

                <div class="postbox company-postbox">
                    <h3 class="hndle"><span><?php _e( 'Company Information', 'wphr' ); ?></span></h3>
                    <div class="inside">

                        <table class="form-table">
                            <tr>
                                <td><label for="address_1"><?php _e( 'Address Line 1 ', 'wphr' ); ?></label></td>
                                <td>
                                    <?php wphr_html_form_input(array(
                                        'name'  => 'address[address_1]',
                                        'value' => $company->address['address_1'],
                                        'class' => 'regular-text'
                                    )); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="address_2"><?php _e( 'Address Line 2 ', 'wphr' ); ?></label></td>
                                <td>
                                    <?php wphr_html_form_input(array(
                                        'name'  => 'address[address_2]',
                                        'value' => $company->address['address_2'],
                                        'class' => 'regular-text'
                                    )); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="city"><?php _e( 'City', 'wphr' ); ?></label></td>
                                <td>
                                    <?php wphr_html_form_input(array(
                                        'name'  => 'address[city]',
                                        'value' => $company->address['city'],
                                        'class' => 'regular-text'
                                    )); ?>
                                </td>
                            </tr>

                             <tr>
                                <td><label for="wphr-country"><?php _e( 'Country', 'wphr' ); ?></label> <span class="required">*</span></td>
                                <td>
                                    <select name="address[country]" id="wphr-country" data-parent="table" class="wphr-country-select select2" required="required">
                                        <?php echo $country->country_dropdown( $company->address['country'] ); ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="state"><?php _e( 'Province / State', 'wphr' ); ?></label></td>
                                <td>
                                    <select name="address[state]" id="wphr-state" class="wphr-state-select">
                                        <?php
                                        if ( $company->address['country'] ) {
                                            $states = $country->get_states( $company->address['country'] );
                                            echo wphr_html_generate_dropdown( $states, $company->address['state'] );
                                        } else {
                                            ?>
                                            <option value="-1"><?php _e( '- Select -', 'wphr' ); ?></option>
                                        <?php } ?>

                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="zip"><?php _e( 'Postal / Zip Code', 'wphr' ); ?></label></td>
                                <td>
                                    <?php wphr_html_form_input(array(
                                        'name'  => 'address[zip]',
                                        'value' => ( isset( $company->address['zip'] ) ? $company->address['zip'] : '' ),
                                        'class' => 'regular-text'
                                    )); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="phone"><?php _e( 'Phone', 'wphr' ); ?></label></td>
                                <td>
                                    <?php wphr_html_form_input(array(
                                        'name'  => 'phone',
                                        'value' => $company->phone,
                                        'class' => 'regular-text'
                                    )); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="fax"><?php _e( 'Fax', 'wphr' ); ?></label></td>
                                <td>
                                    <?php wphr_html_form_input(array(
                                        'name'  => 'fax',
                                        'value' => $company->fax,
                                        'class' => 'regular-text'
                                    )); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="mobile"><?php _e( 'Mobile', 'wphr' ); ?></label></td>
                                <td>
                                    <?php wphr_html_form_input(array(
                                        'name'  => 'mobile',
                                        'value' => $company->mobile,
                                        'class' => 'regular-text'
                                    )); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="website"><?php _e( 'Website', 'wphr' ); ?></label></td>
                                <td>
                                    <?php wphr_html_form_input(array(
                                        'name'  => 'website',
                                        'type'  => 'url',
                                        'value' => $company->website,
                                        'class' => 'regular-text'
                                    )); ?>
                                </td>
                            </tr>
                        </table>
						<?php do_action('wphr_company_fields'); ?>
                    </div><!-- .inside -->
                </div><!-- .postbox -->
            </div><!-- .wphr-area-left -->

            <div class="wphr-area-right">
                <div class="postbox company-logo" id="postimagediv">
                    <h3 class="hndle"><span><?php _e( 'Company Logo', 'wphr' ); ?></span></h3>
                    <div class="inside">

                        <?php echo $company->get_logo(); ?>

                        <?php if ( $company->has_logo() ) { ?>

                            <p class="hide-if-no-js">
                                <input type="hidden" name="company_logo_id" value="<?php echo $company->logo; ?>">
                                <a href="#" class="remove-logo"><?php _e( 'Remove company logo', 'wphr' ); ?></a>
                            </p>

                        <?php } else { ?>

                            <p class="hide-if-no-js">
                                <a href="<?php echo get_upload_iframe_src('image' ); ?>" id="set-company-thumbnail" class="thickbox"><?php _e( 'Upload company logo', 'wphr' ); ?></a>
                            </p>

                        <?php } ?>
                    </div>
                </div><!-- .postbox -->

                <div class="postbox company-postbox">
                    <h3 class="hndle"><span><?php _e( 'Actions', 'wphr' ); ?></span></h3>
                    <div class="inside">
                        <div class="submitbox" id="submitbox">
                            <div id="major-publishing-actions">

                                <div id="publishing-action">

                                    <?php wp_nonce_field( 'wphr-new-company' ); ?>
                                    <input type="hidden" name="wphr-action" value="create_new_company">
                                    <input type="hidden" name="company_id" value="<?php echo $company->id; ?>">
                                    <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php echo __( 'Update Company', 'wphr' ); ?>">
                                </div>

                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- .postbox -->
            </div><!-- .leads-right -->
        </div><!-- .wphr-single-container -->
    </form>
</div>
