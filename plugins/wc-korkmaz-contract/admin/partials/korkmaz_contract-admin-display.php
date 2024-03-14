<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://yemlihakorkmaz.com
 * @since      1.0.0
 *
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/admin/partials
 */

if ( !current_user_can ( 'manage_options' ) ) {
	return;
}
?>
<div class="wrap">

    <h1 class="wp-heading-inline"><?php
		echo __ (
			'Satış Sözleşmesi Ayarları',
			'korkmaz_contract'
		); ?></h1>


    <hr>
    <h2><?php
		echo __ (
			'Firma Bilgileri',
			'korkmaz_contract'
		); ?></h2>


    <form action="options.php" method="post">
		<?php
		wp_nonce_field ( 'update-options' ) ?>

        <table class="form-table">
            <tbody>

            <tr>
                <th scope="row">
                    <label for="firmaadi"><?php
						echo __ (
							'Firma Adı',
							'korkmaz_contract'
						); ?></label>
                </th>
                <td>
                    <input name="firmaadi" type="text" id="firmaadi" value="<?php
					echo get_option ( 'firmaadi' ); ?>" class="regular-text">
                </td>
            </tr>


            <tr>
                <th scope="row">
                    <label for="firmaadresi"><?php
						echo __ (
							'Firma Adresi',
							'korkmaz_contract'
						); ?></label>
                </th>
                <td>
                    <input name="firmaadresi" type="text" id="firmaadresi" value="<?php
					echo get_option ( 'firmaadresi' ); ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="firmatelno"><?php
						echo __ (
							'Firma Telefon Numarası',
							'korkmaz_contract'
						); ?></label>
                </th>
                <td>
                    <input name="firmatelno" type="text" id="firmatelno" value="<?php
					echo get_option ( 'firmatelno' ); ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="firmaverdaire"><?php
						echo __ (
							'Firma Vergi Dairesi',
							'korkmaz_contract'
						); ?></label>
                </th>
                <td>
                    <input name="firmaverdaire" type="text" id="firmaverdaire" value="<?php
					echo get_option ( 'firmaverdaire' ); ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="firmaverno"><?php
						echo __ (
							'Firma Vergi Numarası',
							'korkmaz_contract'
						); ?></label>
                </th>
                <td>
                    <input name="firmaverno" type="text" id="firmaverno" value="<?php
					echo get_option ( 'firmaverno' ); ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th>
                    <h2>
						<?php
						echo __ (
							'Link İsimleri',
							'korkmaz_contract'
						); ?>
                    </h2>
                </th>

            </tr>

            <tr>
                <th scope="row">
                    <label for="birinci_sozlesme_link_ismi"><?php
						echo __ (
							'İlk Sözleşme Link İsmi',
							'korkmaz_contract'
						); ?></label>
                </th>
                <td>
                    <input name="birinci_sozlesme_link_ismi" type="text" id="birinci_sozlesme_link_ismi" value="<?php
					echo get_option ( 'birinci_sozlesme_link_ismi' ); ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ikinci_sozlesme_link_ismi"><?php
						echo __ (
							'İkinci Sözleşme Link İsmi',
							'korkmaz_contract'
						); ?></label>
                </th>
                <td>
                    <input name="ikinci_sozlesme_link_ismi" type="text" id="ikinci_sozlesme_link_ismi" value="<?php
					echo get_option ( 'ikinci_sozlesme_link_ismi' ); ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th>
                    <h2>
						<?php
						echo __ (
							'Görünürlük',
							'korkmaz_contract'
						); ?>
                    </h2>
                </th>

            </tr>


            <tr valign="top">
                <th scope="row" class="titledesc"><?php
					echo __ ( 'Ödeme Sayfası','korkmaz_contract' ); ?></th>
                <td>
                    <label for="sozlesme_ozellik_3">

                        <input type='checkbox' name='sozlesme_ozellik_3' value='1' <?php
						if ( 1 == get_option ( 'sozlesme_ozellik_3' ) ) echo 'checked="checked"'; ?> />
						<?php
						echo __ ( 'Ödeme Sayfasında sözleşmeleri göster','korkmaz_contract' ); ?>
                    </label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc"><?php
					echo __ ( 'Siparişler Sayfası','korkmaz_contract' ); ?></th>
                <td>
                    <label for="sozlesme_ozellik_1">

                        <input type='checkbox' name='sozlesme_ozellik_1' value='1' <?php
						if ( 1 == get_option ( 'sozlesme_ozellik_1' ) ) echo 'checked="checked"'; ?> />
						<?php
						echo __ ( 'Siparişler bölümünde sözleşmeleri göster','korkmaz_contract' ); ?>
                    </label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc"><?php
					echo __ ( 'Teşekkür Sayfası','korkmaz_contract' ); ?></th>
                <td>
                    <label for="sozlesme_ozellik_2">

                        <input type='checkbox' name='sozlesme_ozellik_2' value='1' <?php
						if ( 1 == get_option ( 'sozlesme_ozellik_2' ) ) echo 'checked="checked"'; ?> />
						<?php
						echo __ ( 'Teşekkürler sayfasında sözleşmeleri göster','korkmaz_contract' ); ?>
                    </label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc"><?php
					echo __ ( 'Sipariş Detayları','korkmaz_contract' ); ?></th>
                <td>
                    <label for="sozlesme_ozellik_5">

                        <input type='checkbox' name='sozlesme_ozellik_5' value='1' <?php
						if ( 1 == get_option ( 'sozlesme_ozellik_5' ) ) echo 'checked="checked"'; ?> />
						<?php
						echo __ ( 'Sipariş detayları sayfasında göster','korkmaz_contract' ); ?>
                    </label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc"><?php
					echo __ ( 'Tc/Vergi No','korkmaz_contract' ); ?></th>
                <td>
                    <label for="sozlesme_ozellik_6">

                        <input type='checkbox' name='sozlesme_ozellik_6' value='1' <?php
						if ( 1 == get_option ( 'sozlesme_ozellik_6' ) ) echo 'checked="checked"'; ?> />
						<?php
						echo __ ( 'Tc/Vergi no sistemi aktif','korkmaz_contract' ); ?>
                    </label>
                </td>
            </tr>


            <tr valign="top">
                <th scope="row" class="titledesc"><?php
					echo __ ( 'Mail Gönder','korkmaz_contract' ); ?></th>
                <td>
                    <label for="sozlesme_ozellik_4">

                        <input type='checkbox' name='sozlesme_ozellik_4' value='1' <?php
						if ( 1 == get_option ( 'sozlesme_ozellik_4' ) ) echo 'checked="checked"'; ?> />
						<?php
						echo __ ( 'Sözleşmeleri Mail Olarak Gönder','korkmaz_contract' ); ?>
                    </label>
                </td>
            </tr>
			
			<tr valign="top">
				<th scope="row" class="titledesc"><?php echo __('Hangi Sipariş Durumunda Mail Gönderilsin ?', 'korkmaz_contract'); ?></th>
				<td>


					<select name="sozlesme_mail_durumu" id="sozlesme_mail_durumu">
						<option value="new_order" <?php selected(get_option('sozlesme_mail_durumu'), 'new_order'); ?>><?php echo __('Yeni Sipariş', 'korkmaz_contract'); ?></option>
						<option value="customer_processing_order" <?php selected(get_option('sozlesme_mail_durumu'), 'customer_processing_order'); ?>><?php echo __('Hazırlanıyor', 'korkmaz_contract'); ?></option>
						<option value="customer_on_hold_order" <?php selected(get_option('sozlesme_mail_durumu'), 'customer_on_hold_order'); ?>><?php echo __('Bekletiliyor', 'korkmaz_contract'); ?></option>
						<option value="customer_completed_order" <?php selected(get_option('sozlesme_mail_durumu'), 'customer_completed_order'); ?>><?php echo __('Tamamlandı', 'korkmaz_contract'); ?></option>
					</select>
				</td>
			</tr>
			
			
			
			</tbody>

        </table>

        <input type="hidden" name="action" value="update"/>

        <input type="hidden" name="page_options" value="firmaverdaire,firmatelno,firmaadresi,firmaadi,firmaverno,sozlesme_ozellik_1,
               sozlesme_ozellik_2,sozlesme_ozellik_3,sozlesme_ozellik_4,birinci_sozlesme_link_ismi,ikinci_sozlesme_link_ismi,
               sozlesme_ozellik_4,sozlesme_ozellik_5,sozlesme_ozellik_6,sozlesme_mail_durumu"/>
        <p><?php
			submit_button (); ?></p>
    </form>


</div>
