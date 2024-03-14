<div class="wrap wphr wphr-company-single">
    <h2><?php _e( 'Company Details', 'wphr' ); ?></h2>

    <div class="metabox-holder company-accounts">
        <?php
        $company = new \WPHR\HR_MANAGER\Company();

        $company->get_locations();
        ?>
        <div class="postbox account">
            <div class="inside clearfix">
                <div class="logo-area">
                    <?php echo $company->get_logo(); ?>
                </div><!-- .logo-area -->

                <div class="content-area">
                    <h2><?php echo $company->name; ?> <a href="<?php echo $company->get_edit_url(); ?>"><?php _e( 'Edit', 'wphr' ); ?></a></h2>

                    <address class="address">
                        <?php echo $company->get_formatted_address(); ?>
                    </address>
                </div><!-- .content-area -->
            </div><!-- .inside -->
        </div><!-- .account -->

        <div class="company-location-wrap">
            <h2>
                <?php _e( 'Locations', 'wphr' ); ?>

                <a href="#" id="wphr-company-new-location" class="add-new-h2 wphr-add-new-location" data-title="<?php _e( 'New Location', 'wphr' ); ?>" data-id="<?php echo $company->id; ?>"><?php _e( 'Create New Location', 'wphr' ); ?></a>
            </h2>

            <div id="company-locations">
                <div id="company-locations-inside">
                <?php
                $locations = $company->get_locations();
                $country = \WPHR\HR_MANAGER\Countries::instance();

                if ( $locations ) {
                    foreach ($locations as $num => $location) {
                        ?>
                        <div class="company-location postbox">
                            <h3 class="hndle"><?php echo wp_kses_post( $location['name'] ); ?></h3>

                            <div class="inside">
                                <address class="address">
                                    <?php
                                    echo $country->get_formatted_address( array(
                                        'address_1' => $location['address_1'],
                                        'address_2' => $location['address_2'],
                                        'city'      => $location['city'],
                                        'state'     => $location['state'],
                                        'postcode'  => $location['zip'],
                                        'country'   => $location['country']
                                    ) );
                                    ?>
                                </address>
                            </div><!-- .inside -->

                            <div class="actions">
                                <a href="#" class="edit-location" data-data='<?php echo json_encode( $location ); ?>'><span class="dashicons dashicons-edit"></span></a>
                                <a href="#" class="remove-location" data-id="<?php echo $location['id']; ?>"><span class="dashicons dashicons-trash"></span></a>
                            </div>
                        </div>

                        <?php
                    }
                } else {
                    _e( 'No extra locations found!', 'wphr' );
                }
                ?>
                </div><!-- #company-locations-inside -->
            </div><!-- #company-locations -->
        </div><!-- .company-location-wrap -->
    </div><!-- .metabox-holder -->

</div>
