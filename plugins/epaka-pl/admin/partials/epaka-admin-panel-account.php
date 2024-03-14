<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<button id="saveProfile" class="button-primary woocommerce-save-button">Zapisz</button>
<div class="flex-row-wrap profile-forms">
        <form method="POST">
            <table class="form-table">
                <tbody>
                    <h3>Dane do faktury</h3>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[firstName]">Imie:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[firstName]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->name) ? $profile->name->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[lastName]">Nazwisko:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[lastName]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->lastName) ? $profile->lastName->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[company]">Nazwa firmy:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[company]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->company) ? $profile->company->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[tin]">NIP:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[tin]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->tin) ? $profile->tin->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[street]">Ulica:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[street]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->street) ? $profile->street->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[houseNumber]">Numer domu:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[houseNumber]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->houseNumber) ? $profile->houseNumber->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[flatNumber]">Numer mieszkania:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[flatNumber]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->flatNumber) ? $profile->flatNumber->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[postCode]">Kod pocztowy:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[postCode]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->postCode) ? $profile->postCode->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[city]">Miasto:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[city]" <?php echo (!empty($profile->paymentType) ? (($profile->paymentType->__toString() == "abonamentowy")?"disabled":"") : '');?> value="<?php echo (!empty($profile->city) ? $profile->city->__toString() : '');?>"/>
                        </td>
                    </tr>

                    <tr valign="top">
                        <td class="forminp" colspan="2">
                            <div id="invoceCheckboxs">
                                <label class="checkbox-container flex-row-nowrap margin-bottom-5px" id="UzytkownikBezFaktury">
                                    <input type="checkbox" class="epakainvoicecheckbox" <?php echo (!empty($profile->invoices) ? (($profile->invoices->__toString() == "0") ? "checked" : "") : '') ?> name="profile[invoices]" value="0" class="form-radio mr-3" id="UzytkownikBezFakturyCheckbox">
                                    <div style="margin-top: -3px;">Nie chce otrzymywać faktur</div>
                                </label>
                                <label class="checkbox-container flex-row-nowrap margin-bottom-5px" id="UzytkownikFakturaPoPlatnosci">
                                    <input type="checkbox" class="epakainvoicecheckbox" <?php echo (!empty($profile->invoices) ? (($profile->invoices->__toString() == "1") ? "checked" : "") : '') ?> name="profile[invoices]" value="1" id="UzytkownikFakturaPoPlatnosciCheckbox">
                                    <div style="margin-top: -3px;">Chce otrzymywać fakturę po każdej płatności</div>
                                </label>
                                <label class="checkbox-container flex-row-nowrap margin-bottom-5px" id="UzytkownikFakturaZbiorcza">
                                    <input type="checkbox" class="epakainvoicecheckbox" <?php echo (!empty($profile->invoices) ? (($profile->invoices->__toString() == "2") ? "checked" : "") : '') ?> name="profile[invoices]" value="2" id="UzytkownikFakturaZbiorczaCheckbox">
                                    <div style="margin-top: -3px;">Chce otrzymywać fakturę zbiorczą na koniec miesiąca</div>
                                </label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

        <form method="POST" class="margin-left-5px">
            <table class="form-table">
                <tbody>
                    <h3>Adres nadań</h3>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderName]">Imie:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderName]" value="<?php echo (!empty($profile->senderName) ? $profile->senderName->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderLastName]">Nazwisko:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderLastName]" value="<?php echo (!empty($profile->senderLastName) ? $profile->senderLastName->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderCompany]">Nazwa firmy:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderCompany]" value="<?php echo (!empty($profile->senderCompany) ? $profile->senderCompany->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderStreet]">Ulica:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderStreet]" value="<?php echo (!empty($profile->senderStreet) ? $profile->senderStreet->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderHouseNumber]">Numer domu:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderHouseNumber]" value="<?php echo (!empty($profile->senderHouseNumber) ? $profile->senderHouseNumber->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderFlatNumber]">Numer mieszkania:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderFlatNumber]" value="<?php echo (!empty($profile->senderFlatNumber) ? $profile->senderFlatNumber->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderPostCode]">Kod pocztowy:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderPostCode]" value="<?php echo (!empty($profile->senderPostCode) ? $profile->senderPostCode->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderCity]">Miasto:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderCity]" value="<?php echo (!empty($profile->senderCity) ? $profile->senderCity->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[senderPhone]">Numer telefon:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[senderPhone]" value="<?php echo (!empty($profile->senderPhone) ? $profile->senderPhone->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[defaultPaczkomatDescription]">Domyślny punkt nadania dla usługi "InPost Paczkomat"</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="hidden" name="profile[defaultPaczkomat]" value="<?php echo (!empty($profile->defaultPaczkomat) ? $profile->defaultPaczkomat->__toString() : '');?>"/>
                            <input type="text" class="woocommerce-input-wrapper showMapOnClick marquee"
                             data-map-source-url="<?php echo $saveProfileCouriers[12]['courierMapSourceUrl']?>"
                             data-map-source-name="<?php echo $saveProfileCouriers[12]['courierMapSourceName']?>"
                             data-map-source-id="<?php echo $saveProfileCouriers[12]['courierMapSourceId']?>"
                             name="profile[defaultPaczkomatDescription]" value="<?php echo (!empty($profile->defaultPaczkomatDescription) ? $profile->defaultPaczkomatDescription->__toString() : '');?>"/>
                            <i class="fa fa-times pointClear" style="display: none;"></i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[defaultPaczkomatDescription]">Domyślny punkt nadania dla usługi "Paczka w Ruchu"</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="hidden" name="profile[defaultPunktRuchu]" value="<?php echo (!empty($profile->defaultPunktRuchu) ? $profile->defaultPunktRuchu->__toString() : '');?>"/>
                            <input type="text" class="woocommerce-input-wrapper showMapOnClick marquee"
                             data-map-source-url="<?php echo $saveProfileCouriers[11]['courierMapSourceUrl']?>"
                             data-map-source-name="<?php echo $saveProfileCouriers[11]['courierMapSourceName']?>"
                             data-map-source-id="<?php echo $saveProfileCouriers[11]['courierMapSourceId']?>"
                             name="profile[defaultPunktRuchuDescription]" value="<?php echo (!empty($profile->defaultPunktRuchuDescription) ? $profile->defaultPunktRuchuDescription->__toString() : '');?>"/>
                            <i class="fa fa-times pointClear" style="display: none;"></i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[defaultPaczkomatDescription]">Domyślny punkt nadania dla usługi "Paczka 48"</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="hidden" name="profile[defaultPunktPaczka48]" value="<?php echo (!empty($profile->defaultPunktPaczka48) ? $profile->defaultPunktPaczka48->__toString() : '');?>"/>
                            <input type="text" class="woocommerce-input-wrapper showMapOnClick marquee" 
                             data-map-source-url="<?php echo $saveProfileCouriers[17]['courierMapSourceUrl']?>"
                             data-map-source-name="<?php echo $saveProfileCouriers[17]['courierMapSourceName']?>"
                             data-map-source-id="<?php echo $saveProfileCouriers[17]['courierMapSourceId']?>"
                             name="profile[defaultPunktPaczka48Description]" value="<?php echo (!empty($profile->defaultPunktPaczka48Description) ? $profile->defaultPunktPaczka48Description->__toString() : '');?>"/>
                            <i class="fa fa-times pointClear" style="display: none;"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

        <form method="POST" class="margin-left-5px">
            <table class="form-table">
                <tbody>
                    <h3>Dodatkowe dane</h3>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[bankAccount]">Numer konta bankowego:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" style="min-width: 240px;" name="profile[bankAccount]" value="<?php echo (!empty($profile->bankAccount) ? $profile->bankAccount->__toString() : '');?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="profile[phone]">Numer telefonu:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="profile[phone]" value="<?php echo (!empty($profile->phone) ? $profile->phone->__toString() : '');?>"/>
                        </td>
                    </tr>
                </tbody>
            </table>

        </form>
    </div>