<?php
defined( 'ABSPATH' ) || exit;

/* @var $wqm_n string */
/* @var $wqm_nickname string */
/* @var $wqm_photo int */
/* @var $wqm_tel array */
/* @var $wqm_email array */
/* @var $wqm_adr array */
/* @var $wqm_bday string */
/* @var $wqm_title string */
/* @var $wqm_org string */
/* @var $wqm_url array */
/* @var $wqm_class string */
/* @var $wqm_note string */

$wqm_photo_path_url = '';
if ( ! empty( $wqm_photo ) ) {
	$wqm_photo_path_url = wp_get_attachment_image_url( $wqm_photo, array( 100, 100 ) );
}

?>

<table class="form-table" role="presentation">
    <tbody>
    <tr class="field-photo">
        <th><label for="field-photo"><?php _e( 'Photo', 'wp-qrcode-me-v-card' ) ?></label></th>
        <td>
            <input id="field-photo" name="wqm_photo" type="hidden" value="<?php echo $wqm_photo; ?>">
            <img src="<?php echo $wqm_photo_path_url; ?>" id="wqm-photosrc" alt="photo"/>
            <button type="button" id="wqm_photo_path_upload" class="button button-primary button-large"
                    style="<?php echo ! empty( $wqm_photo ) ? 'display:none;' : '' ?>">
				<?php _e( 'Select photo', 'wp-qrcode-me-v-card' ) ?></button>
            <button type="button" id="wqm_photo_path_delete" class="button button-add-media button-large"
                    style="<?php echo ! empty( $wqm_photo ) ? '' : 'display:none;' ?>">
				<?php _e( 'Delete photo', 'wp-qrcode-me-v-card' ) ?></button>
            <span class="description"><?php _e( 'Card photo.', 'wp-qrcode-me-v-card' ) ?></span>
        </td>

    </tr>
    <tr class="field-n">
        <th><label for="field-n"><?php _e( 'Name', 'wp-qrcode-me-v-card' ) ?></label></th>
        <td>
            <div id="n-collapse">
                <input type="text" name="wqm_n[s]" id="field-n" class="regular-text" placeholder="Web Marshal" value="<?php echo $wqm_n; ?>">
                <span id="n-expand-btn" class="expand-blue">&nbsp;(<?php _e( 'expand', 'wp-qrcode-me-v-card' ) ?>)</span>
            </div>
            <div id="n-expand">
                <div>
                    <label for="wqm_n[0]">
                        <span><?php _e( 'Family Name', 'wp-qrcode-me-v-card' ) ?>:</span>
                        <input type="text" name="wqm_n[0]" class="regular-text wqm-n-part" placeholder="Stevenson" value="">
                    </label>
                    <label for="wqm_n[1]">
                        <span><?php _e( 'Given Name', 'wp-qrcode-me-v-card' ) ?>:</span>
                        <input type="text" name="wqm_n[1]" class="regular-text wqm-n-part" placeholder="John" value="">
                    </label>
                    <label for="wqm_n[2]">
                        <span><?php _e( 'Additional Names', 'wp-qrcode-me-v-card' ) ?>:</span>
                        <input type="text" name="wqm_n[2]" class="regular-text wqm-n-part" placeholder="Philip" value="">
                    </label>
                    <label for="wqm_n[3]">
                        <span><?php _e( 'Honorific Prefixes', 'wp-qrcode-me-v-card' ) ?>:</span>
                        <input type="text" name="wqm_n[3]" class="regular-text wqm-n-part" placeholder="Mr." value="">
                    </label>
                    <label for="wqm_n[4]">
                        <span><?php _e( 'Honorific Suffixes', 'wp-qrcode-me-v-card' ) ?>:</span>
                        <input type="text" name="wqm_n[4]" class="regular-text wqm-n-part" placeholder="Esq." value="">
                    </label>
                </div>
                <span id="n-collapse-btn" class="expand-blue">&nbsp;(<?php _e( 'collapse', 'wp-qrcode-me-v-card' ) ?>)</span>
            </div>
            <span class="description"><?php _e( 'A structured representation of the name of the person. When a field is divided by a SEMICOLON (;), the structured type value corresponds, in sequence, to the Family Name;Given Name;Additional Names;Honorific Prefixes;Honorific Suffixes.', 'wp-qrcode-me-v-card' ) ?></span>
        </td>
    </tr>
    <tr class="field-nickname">
        <th><label for="field-nickname"><?php _e( 'Nickname', 'wp-qrcode-me-v-card' ) ?></label></th>
        <td>
            <input type="text" name="wqm_nickname" id="field-nickname" class="regular-text" placeholder="WM_the_best"
                   value="<?php echo $wqm_nickname; ?>">
            <span class="description"><?php _e( 'Familiar name for the object represented by this MeCard/vCard. A few nicknames may be COMMA (,) separated', 'wp-qrcode-me-v-card' ) ?></span>
        </td>
    </tr>
    <tr class="field-tel">
		<?php $wqm_tel = is_array( $wqm_tel ) ? $wqm_tel : [ $wqm_tel ]; ?>
        <th>
            <div class="tel-label">
                <label for="field-tel"><?php _e( 'Tel', 'wp-qrcode-me-v-card' ) ?></label>
                <span id="tel-add" class="green-btn" data-len="<?php echo( $wqm_tel ? count( $wqm_tel ) : 1 ) ?>"><span class="dashicons dashicons-plus"></span></span>
            </div>
        </th>
        <td>
            <div id="tel-wrapper">
				<?php for ( $i = 0; $i < ( $wqm_tel ? count( $wqm_tel ) : 1 ); $i ++ ): ?>
                    <div class="row">
                        <div class="tel-elements tel-elements-<?php echo $i; ?><?php echo empty( $wqm_tel[ $i ]['type'] ) ? ' tel-no-type' : '' ?>">
                            <div class="tel-content-wrap">
                                <label for="wqm_tel[<?php echo $i; ?>][content]">
                                    <span class="wqm-tel-label"><?php _e( 'Phone number', 'wp-qrcode-me-v-card' ) ?></span>
                                    <input type="text" name="wqm_tel[<?php echo $i; ?>][content]" class="regular-text wqm-tel" placeholder="+7(978) 571-91-44" value="<?php echo $wqm_tel[ $i ]['content'] ?? ''; ?>">
                                    <span class="tel-expand-btn expand-blue" data-id="<?php echo $i; ?>">&nbsp;(<?php _e( 'expand', 'wp-qrcode-me-v-card' ) ?>)</span>
                                    <span class="tel-collapse-btn expand-blue" data-id="<?php echo $i; ?>">&nbsp;(<?php _e( 'collapse', 'wp-qrcode-me-v-card' ) ?>)</span>
                                </label>
                            </div>
                            <div class="tel-type-wrap">
                                <label for="wqm_tel[<?php echo $i; ?>][type][]">
                                    <span class="wqm-tel-label"><?php _e( 'Phone type(s)', 'wp-qrcode-me-v-card' ) ?></span>
                                    <select name="wqm_tel[<?php echo $i; ?>][type][]" class="wqm-tel" multiple="multiple" data-placeholder="<?php _e( 'Select an option(s)', 'wp-qrcode-me-v-card' ) ?>">
                                        <option value="home" <?php echo wqm_selected( 'home', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'residence', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="msg" <?php echo wqm_selected( 'msg', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'voice messaging support', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="work" <?php echo wqm_selected( 'work', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'place of work', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="pref" <?php echo wqm_selected( 'pref', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'preferred-use', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="voice" <?php echo wqm_selected( 'voice', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'voice', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="fax" <?php echo wqm_selected( 'fax', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'facsimile', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="cell" <?php echo wqm_selected( 'cell', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'cellular', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="video" <?php echo wqm_selected( 'video', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'video conferencing', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="pager" <?php echo wqm_selected( 'pager', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'paging device', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="bbs" <?php echo wqm_selected( 'bbs', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'bulletin board system', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="modem" <?php echo wqm_selected( 'modem', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'MODEM connected', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="car" <?php echo wqm_selected( 'car', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'car-phone', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="isdn" <?php echo wqm_selected( 'isdn', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'ISDN service', 'wp-qrcode-me-v-card' ) ?></option>
                                        <option value="pcs" <?php echo wqm_selected( 'pcs', $wqm_tel[ $i ]['type'] ?? [] ) ?>><?php _e( 'personal communication services', 'wp-qrcode-me-v-card' ) ?></option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="remtel red-btn"><span class="dashicons dashicons-no"></span></div>
                    </div>
				<?php endfor; ?>
            </div>
            <span class="description"><?php _e( 'The canonical number string for a telephone number for telephony communication.', 'wp-qrcode-me-v-card' ) ?></span>

            <div id="tel-template">
                <div class="row">
                    <div class="tel-elements tel-no-type">
                        <div class="tel-content-wrap"><label>
                                <span class="wqm-tel-label"><?php _e( 'Phone number', 'wp-qrcode-me-v-card' ) ?></span>
                                <input type="text" class="regular-text wqm-tel" placeholder="+7(978) 571-91-44">
                                <span class="tel-expand-btn expand-blue">&nbsp;(<?php _e( 'expand', 'wp-qrcode-me-v-card' ) ?>)</span>
                                <span class="tel-collapse-btn expand-blue">&nbsp;(<?php _e( 'collapse', 'wp-qrcode-me-v-card' ) ?>)</span>
                            </label>
                        </div>
                        <div class="tel-type-wrap">
                            <label>
                                <span class="wqm-tel-label"><?php _e( 'Phone type(s)', 'wp-qrcode-me-v-card' ) ?></span>
                                <select class="wqm-tel" multiple="multiple" data-placeholder="<?php _e( 'Select an option(s)', 'wp-qrcode-me-v-card' ) ?>">
                                    <option value="home"><?php _e( 'residence', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="msg"><?php _e( 'voice messaging support', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="work"><?php _e( 'place of work', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="pref"><?php _e( 'preferred-use', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="voice"><?php _e( 'voice', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="fax"><?php _e( 'facsimile', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="cell"><?php _e( 'cellular', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="video"><?php _e( 'video conferencing', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="pager"><?php _e( 'paging device', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="bbs"><?php _e( 'bulletin board system', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="modem"><?php _e( 'MODEM connected', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="car"><?php _e( 'car-phone', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="isdn"><?php _e( 'ISDN service', 'wp-qrcode-me-v-card' ) ?></option>
                                    <option value="pcs"><?php _e( 'personal communication services', 'wp-qrcode-me-v-card' ) ?></option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="remtel red-btn"><span class="dashicons dashicons-no"></span></div>
                </div>
            </div>
        </td>
    </tr>
    <tr class="field-email">
		<?php $wqm_email = is_array( $wqm_email ) ? $wqm_email : [ $wqm_email ]; ?>
        <th>
            <div class="mail-label">
                <label for="field-email"><?php _e( 'Email', 'wp-qrcode-me-v-card' ) ?></label>
                <span id="mail-add" class="green-btn" data-len="<?php echo( $wqm_email ? count( $wqm_email ) : 1 ) ?>"><span class="dashicons dashicons-plus"></span></span>
            </div>
        </th>
        <td>
            <div id="mail-wrapper">
				<?php for ( $i = 0; $i < ( $wqm_email ? count( $wqm_email ) : 1 ); $i ++ ): ?>
                    <div class="row">
                        <div class="mail-elements">
                            <input type="text" name="wqm_email[<?php echo $i; ?>]" class="regular-text" placeholder="web.marshal.ru@gmail.com" value="<?php echo $wqm_email[ $i ]; ?>">
                        </div>
                        <div class="remmail red-btn"><span class="dashicons dashicons-no"></span></div>
                    </div>
				<?php endfor; ?>
            </div>
            <span class="description"><?php _e( 'The address for electronic mail communication.', 'wp-qrcode-me-v-card' ) ?><?php _e( 'First field marks as preferred email', 'wp-qrcode-me-v-card' ) ?></span>

            <div id="mail-template">
                <div class="row">
                    <div class="mail-elements">
                        <input type="text" class="regular-text" placeholder="web.marshal.ru@gmail.com">
                    </div>
                    <div class="remmail red-btn"><span class="dashicons dashicons-no"></span></div>
                </div>
            </div>
        </td>
    </tr>
    <tr class="field-bday">
        <th><label for="field-bday"><?php _e( 'Birthday', 'wp-qrcode-me-v-card' ) ?></label></th>
        <td>
            <input type="text" name="wqm_bday" id="field-bday" class="regular-text" placeholder="2018-11-21"
                   value="<?php echo $wqm_bday; ?>">
            <span class="description"><?php _e( '8 digits for date of birth: year (4 digits), month (2 digits) and day (2 digits), in order.', 'wp-qrcode-me-v-card' ) ?></span>
        </td>
    </tr>
    <tr class="field-adr">
		<?php $wqm_adr = is_array( $wqm_adr ) ? $wqm_adr : [ $wqm_adr ]; ?>
        <th>
            <div class="adr-label">
                <label for="field-adr"><?php _e( 'Address', 'wp-qrcode-me-v-card' ) ?></label>
                <span id="adr-add" class="green-btn" data-len="<?php echo( $wqm_adr ? count( $wqm_adr ) : 1 ) ?>"><span class="dashicons dashicons-plus"></span></span>
            </div>
        </th>
        <td>
            <div id="adr-wrapper">
				<?php for ( $i = 0; $i < ( $wqm_adr ? count( $wqm_adr ) : 1 ); $i ++ ): ?>
                    <div class="row">
                        <div class="adr-elements">
                            <div class="adr-collapse adr-collapse-<?php echo $i; ?>">
                                <input type="text" name="wqm_adr[<?php echo $i; ?>][s]" class="regular-text" placeholder="132, My Street, Kingston, New York 12401 United States" value="<?php echo $wqm_adr[ $i ]; ?>">
                                <span class="adr-expand-btn expand-blue" data-id="<?php echo $i; ?>">&nbsp;(<?php _e( 'expand', 'wp-qrcode-me-v-card' ) ?>)</span>
                            </div>
                            <div class="adr-expand adr-expand-<?php echo $i; ?>">
                                <div>
                                    <label for="wqm_adr[<?php echo $i; ?>][0]">
                                        <span><?php _e( 'The post office box', 'wp-qrcode-me-v-card' ) ?>:</span>
                                        <input type="text" name="wqm_adr[<?php echo $i; ?>][0]" class="regular-text wqm-adr-part" placeholder="132" value="">
                                    </label>
                                    <label for="wqm_adr[<?php echo $i; ?>][1]">
                                        <span><?php _e( 'The extended address', 'wp-qrcode-me-v-card' ) ?>:</span>
                                        <input type="text" name="wqm_adr[<?php echo $i; ?>][1]" class="regular-text wqm-adr-part" placeholder="" value="">
                                    </label>
                                    <label for="wqm_adr[<?php echo $i; ?>][2]">
                                        <span><?php _e( 'The street address', 'wp-qrcode-me-v-card' ) ?>:</span>
                                        <input type="text" name="wqm_adr[<?php echo $i; ?>][2]" class="regular-text wqm-adr-part" placeholder="My Street" value="">
                                    </label>
                                    <label for="wqm_adr[<?php echo $i; ?>][3]">
                                        <span><?php _e( 'The locality', 'wp-qrcode-me-v-card' ) ?>:</span>
                                        <input type="text" name="wqm_adr[<?php echo $i; ?>][3]" class="regular-text wqm-adr-part" placeholder="Kingston" value="">
                                    </label>
                                    <label for="wqm_adr[<?php echo $i; ?>][4]">
                                        <span><?php _e( 'The region', 'wp-qrcode-me-v-card' ) ?>:</span>
                                        <input type="text" name="wqm_adr[<?php echo $i; ?>][4]" class="regular-text wqm-adr-part" placeholder="New York" value="">
                                    </label>
                                    <label for="wqm_adr[<?php echo $i; ?>][5]">
                                        <span><?php _e( 'The postal code', 'wp-qrcode-me-v-card' ) ?>:</span>
                                        <input type="text" name="wqm_adr[<?php echo $i; ?>][5]" class="regular-text wqm-adr-part" placeholder="12401" value="">
                                    </label>
                                    <label for="wqm_adr[<?php echo $i; ?>][6]">
                                        <span><?php _e( 'The country name', 'wp-qrcode-me-v-card' ) ?>:</span>
                                        <input type="text" name="wqm_adr[<?php echo $i; ?>][6]" class="regular-text wqm-adr-part" placeholder="United States" value="">
                                    </label>
                                </div>
                                <span class="adr-collapse-btn expand-blue" data-id="<?php echo $i; ?>">&nbsp;(<?php _e( 'collapse', 'wp-qrcode-me-v-card' ) ?>)</span>
                            </div>
                        </div>
                        <div class="remadr red-btn"><span class="dashicons dashicons-no"></span></div>
                    </div>
				<?php endfor; ?>
            </div>
            <span class="description"><?php _e( 'The physical delivery address. When a field is divided by a SEMICOLON (;), the structured type value corresponds, in sequence, to the post office box; the extended address; the street address; the locality (e.g., city); the region (e.g., state or province); the postal code; the country name.', 'wp-qrcode-me-v-card' ) ?></span>

            <div id="adr-template">
                <div class="row">
                    <div class="adr-elements">
                        <div class="adr-collapse">
                            <input type="text" class="regular-text" placeholder="132, My Street, Kingston, New York 12401 United States" value="">
                            <span class="adr-expand-btn expand-blue">&nbsp;(<?php _e( 'expand', 'wp-qrcode-me-v-card' ) ?>)</span>
                        </div>
                        <div class="adr-expand">
                            <div>
                                <label>
                                    <span><?php _e( 'The post office box', 'wp-qrcode-me-v-card' ) ?>:</span>
                                    <input type="text" class="regular-text wqm-adr-part" placeholder="132" value="">
                                </label>
                                <label>
                                    <span><?php _e( 'The extended address', 'wp-qrcode-me-v-card' ) ?>:</span>
                                    <input type="text" class="regular-text wqm-adr-part" placeholder="" value="">
                                </label>
                                <label>
                                    <span><?php _e( 'The street address', 'wp-qrcode-me-v-card' ) ?>:</span>
                                    <input type="text" class="regular-text wqm-adr-part" placeholder="My Street" value="">
                                </label>
                                <label>
                                    <span><?php _e( 'The locality', 'wp-qrcode-me-v-card' ) ?>:</span>
                                    <input type="text" class="regular-text wqm-adr-part" placeholder="Kingston" value="">
                                </label>
                                <label>
                                    <span><?php _e( 'The region', 'wp-qrcode-me-v-card' ) ?>:</span>
                                    <input type="text" class="regular-text wqm-adr-part" placeholder="New York" value="">
                                </label>
                                <label>
                                    <span><?php _e( 'The postal code', 'wp-qrcode-me-v-card' ) ?>:</span>
                                    <input type="text" class="regular-text wqm-adr-part" placeholder="12401" value="">
                                </label>
                                <label>
                                    <span><?php _e( 'The country name', 'wp-qrcode-me-v-card' ) ?>:</span>
                                    <input type="text" class="regular-text wqm-adr-part" placeholder="United States" value="">
                                </label>
                            </div>
                            <span class="adr-collapse-btn expand-blue">&nbsp;(<?php _e( 'collapse', 'wp-qrcode-me-v-card' ) ?>)</span>
                        </div>
                    </div>
                    <div class="remadr red-btn"><span class="dashicons dashicons-no"></span></div>
                </div>
            </div>
        </td>
    </tr>
    <tr class="field-title">
        <th><label for="field-title"><?php _e( 'Title', 'wp-qrcode-me-v-card' ) ?></label></th>
        <td>
            <input type="text" name="wqm_title" id="field-title" class="regular-text" placeholder="Web Marshal"
                   value="<?php echo $wqm_title; ?>">
            <span class="description"><?php _e( 'Position held in organization.', 'wp-qrcode-me-v-card' ) ?></span>
        </td>
    </tr>
    <tr class="field-org">
        <th><label for="field-org"><?php _e( 'Organization', 'wp-qrcode-me-v-card' ) ?></label></th>
        <td>
            <input type="text" name="wqm_org" id="field-org" class="regular-text" placeholder="Web Marshal"
                   value="<?php echo $wqm_org; ?>">
            <span class="description"><?php _e( 'Organization name.', 'wp-qrcode-me-v-card' ) ?></span>
        </td>
    </tr>
    <tr class="field-url">
		<?php $wqm_url = is_array( $wqm_url ) ? $wqm_url : [ $wqm_url ]; ?>
        <th>
            <div class="url-label">
                <label for="field-url"><?php _e( 'Url', 'wp-qrcode-me-v-card' ) ?></label>
                <span id="url-add" class="green-btn" data-len="<?php echo( $wqm_url ? count( $wqm_url ) : 1 ) ?>"><span class="dashicons dashicons-plus"></span></span>
            </div>
        </th>
        <td>
            <div id="url-wrapper">
				<?php for ( $i = 0; $i < ( $wqm_url ? count( $wqm_url ) : 1 ); $i ++ ): ?>
                    <div class="row">
                        <div class="url-elements">
                            <input type="text" name="wqm_url[<?php echo $i; ?>]" class="regular-text" placeholder=https://web-marshal.ru value="<?php echo $wqm_url[ $i ]; ?>">
                        </div>
                        <div class="remurl red-btn"><span class="dashicons dashicons-no"></span></div>
                    </div>
				<?php endfor; ?>
            </div>
            <span class="description"><?php _e( 'A URL pointing to a website that represents the person in some way.', 'wp-qrcode-me-v-card' ) ?><?php _e( 'First field marks as preferred url', 'wp-qrcode-me-v-card' ) ?></span>

            <div id="url-template">
                <div class="row">
                    <div class="url-elements">
                        <input type="text" class="regular-text" placeholder="https://web-marshal.ru">
                    </div>
                    <div class="remurl red-btn"><span class="dashicons dashicons-no"></span></div>
                </div>
            </div>
        </td>
    </tr>
    <tr class="field-class">
        <th><label for="field-class"><?php _e( 'Class', 'wp-qrcode-me-v-card' ) ?></label></th>
        <td>
            <select name="wqm_class" id="field-class">
                <option value="" <?php echo selected( '', $wqm_class ) ?>><?php _e( 'Skip', 'wp-qrcode-me-v-card' ) ?></option>
                <option value="PUBLIC" <?php echo selected( 'PUBLIC', $wqm_class ) ?>><?php _e( 'Public', 'wp-qrcode-me-v-card' ) ?></option>
                <option value="PRIVATE" <?php echo selected( 'PRIVATE', $wqm_class ) ?>><?php _e( 'Private', 'wp-qrcode-me-v-card' ) ?></option>
                <option value="CONFIDENTIAL" <?php echo selected( 'CONFIDENTIAL', $wqm_class ) ?>><?php _e( 'Confidential', 'wp-qrcode-me-v-card' ) ?></option>
            </select>
            <span class="description"><?php _e( 'Select card class', 'wp-qrcode-me-v-card' ) ?></span>
        </td>
    </tr>
    <tr class="field-note">
        <th><label for="field-note"><?php _e( 'Note', 'wp-qrcode-me-v-card' ) ?></label></th>
        <td>
            <input type="text" name="wqm_note" id="field-note" class="regular-text"
                   value="<?php echo $wqm_note; ?>">
            <span class="description"><?php _e( 'Specifies supplemental information to be set as memo in the phonebook.', 'wp-qrcode-me-v-card' ) ?></span>
        </td>
    </tr>
    </tbody>
</table>
<script>
    jQuery(document).ready(function () {
        jQuery('#wqm_photo_path_delete').click(function () {
            jQuery('#field-photo').val('');
            jQuery('#wqm-photosrc').prop('src', '');
            jQuery('#wqm_photo_path_upload').show();
            jQuery('#wqm_photo_path_delete').hide();
        });

        jQuery('#wqm_photo_path_upload').click(function () {
            var frame = new wp.media.view.MediaFrame.Select({
                title: '<?php _e( 'Select photo', 'wp-qrcode-me-v-card' ) ?>',
                multiple: false,
                library: {
                    order: 'ASC',
                    orderby: 'title',
                    type: 'image',
                    search: null,
                    uploadedTo: null,
                },
                button: {
                    text: '<?php _e( 'Select photo', 'wp-qrcode-me-v-card' ) ?>',
                },
            });
            // Open the modal.
            frame.open();
            frame.on('select', function () {
                var mediaFrameProps = frame.state().get('selection').first().toJSON();
                jQuery('#field-photo').val(mediaFrameProps.id);
                jQuery('#wqm-photosrc').prop('src', mediaFrameProps.url);
                jQuery('#wqm_photo_path_upload').hide();
                jQuery('#wqm_photo_path_delete').show();
                return false;
            });
        }); // End on click

        jQuery('#tel-wrapper select.wqm-tel').select2();

        jQuery('#tel-add').click(function (e) {
            var len = jQuery(this).data('len');
            len++;
            jQuery(this).data('len', len);

            var templ = jQuery('#tel-template > .row').clone();

            var cont = 'wqm_tel[' + len + '][content]';
            templ.find('.tel-content-wrap label').attr('for', cont);
            templ.find('.tel-content-wrap input').attr('name', cont);

            var type = 'wqm_tel[' + len + '][type][]';
            templ.find('.tel-type-wrap label').attr('for', type);
            templ.find('.tel-type-wrap select').attr('name', type);
            templ.find('.tel-elements').addClass('tel-elements-' + len);
            templ.find('.tel-expand-btn').attr('data-id', len);
            templ.find('.tel-collapse-btn').attr('data-id', len);

            jQuery('#tel-wrapper').append(templ);
            jQuery('#tel-wrapper select.wqm-tel').select2();
        });

        jQuery('#mail-add').click(function (e) {
            var len = jQuery(this).data('len');
            len++;
            jQuery(this).data('len', len);
            var templ = jQuery('#mail-template > .row').clone();

            var cont = 'wqm_email[' + len + ']';
            templ.find('.mail-elements input').attr('name', cont);

            jQuery('#mail-wrapper').append(templ);
        });

        jQuery('#url-add').click(function (e) {
            var len = jQuery(this).data('len');
            len++;
            jQuery(this).data('len', len);
            var templ = jQuery('#url-template > .row').clone();

            var cont = 'wqm_url[' + len + ']';
            templ.find('.url-elements input').attr('name', cont);

            jQuery('#url-wrapper').append(templ);
        });

        jQuery('#adr-add').click(function (e) {
            var len = jQuery(this).data('len');
            len++;
            jQuery(this).data('len', len);
            var templ = jQuery('#adr-template > .row').clone();

            var cont = 'wqm_adr[' + len + '][s]';
            templ.find('.adr-elements .adr-collapse input').attr('name', cont);
            templ.find('.adr-expand-btn').attr('data-id', len);
            templ.find('.adr-collapse-btn').attr('data-id', len);
            templ.find('.adr-collapse').addClass('adr-collapse-' + len);
            templ.find('.adr-expand').addClass('adr-expand-' + len);

            templ.find('.adr-expand label').each(function (i, e) {
                var name = 'wqm_adr[' + len + '][' + i + ']';
                jQuery(e).attr('for', name);
                jQuery(e).find('input').attr('name', name);
            });

            jQuery('#adr-wrapper').append(templ);
        });

        jQuery('#n-expand-btn').click(function () {
            var n = jQuery('input[name="wqm_n[s]"]').val().split(';');
            for (var i in n) {
                jQuery('input[name="wqm_n[' + i + ']"]').val(n[i].trim());
            }
            jQuery('input[name="wqm_n[s]"]').val('');
            jQuery('#n-expand').css('display', 'flex');
            jQuery('#n-collapse').hide();
        });

        jQuery('#n-collapse-btn').click(function () {
            var n = [];
            jQuery('.wqm-n-part').each(function (i, e) {
                var v = jQuery(e).val();
                if (v.length) n.push(v);
                jQuery(e).val('');
            });

            n = n.join(';');
            jQuery('input[name="wqm_n[s]"]').val(n);
            jQuery('#n-expand').hide();
            jQuery('#n-collapse').show();
        });

        jQuery('body').on('click', '.remtel, .remmail, .remurl, .remadr', function () {
            jQuery(this).parent().remove('div.row');
        }).on('click', '.tel-expand-btn', function () {
            var id = jQuery(this).data('id');
            jQuery('.tel-elements-' + id).removeClass('tel-no-type');
            jQuery('#tel-wrapper select.wqm-tel').select2();
        }).on('click', '.tel-collapse-btn', function () {
            var id = jQuery(this).data('id');
            jQuery('.tel-elements-' + id).addClass('tel-no-type');
            jQuery('select[name="wqm_tel[' + id + '][type][]"').val('').trigger('change'); //clean type field
        }).on('click', '.adr-expand-btn', function () {
            var id = jQuery(this).data('id'),
                collapsedEl = 'input[name="wqm_adr[' + id + '][s]"]',
                n = jQuery(collapsedEl).val().split(';');
            for (var i in n) {
                jQuery('input[name="wqm_adr[' + id + '][' + i + ']"]').val(n[i].trim());
            }
            jQuery(collapsedEl).val('');
            jQuery('.adr-expand-' + id).css('display', 'flex');
            jQuery('.adr-collapse-' + id).hide();
        }).on('click', '.adr-collapse-btn', function () {
            var id = jQuery(this).data('id'),
                n = [];
            jQuery('.adr-expand-' + id + ' .wqm-adr-part').each(function (i, e) {
                var v = jQuery(e).val();
                if (v.length) n.push(v);
                jQuery(e).val('');
            });

            n = n.join(';');
            jQuery('input[name="wqm_adr[' + id + '][s]"]').val(n);
            jQuery('.adr-expand-' + id).hide();
            jQuery('.adr-collapse-' + id).show();
        });
    });
</script>
