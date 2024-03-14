<?php

function tsseph_get_settings($tsseph_options, $tsseph_bonus_options) {
    ?>
    <div class="form-content">
        <div class="postbox">
            <h3><?php _e('Nastavenie API', 'spirit-eph'); ?></h3>
            <div class="inside">
                <p><?php printf( wp_kses( __( 'Údaje pre API nájdete v <a href="%s" target="_blank" > Slovenská Pošta - Nastavenia</a>.', 'spirit-eph' ), array('a'=>array('href'=>array(), 'target' =>array() ))), esc_url("https://mojezasielky.posta.sk/#settings")); ?></p>
                <table class="form-table" style="max-width:500px;">
                        <tr valign="top">
                            <th><label for="tsseph_options[UserId]"><?php _e('User ID', 'spirit-eph'); ?>:</label></th>
                            <td><input type="text" name="tsseph_options[UserId]" value="<?php esc_attr_e($tsseph_options['UserId']); ?>" size="64" class="regular-text code"></td>
                        </tr>
                        <tr valign="top">
                            <th><label for="tsseph_options[ApiKey]"><?php _e('API Key', 'spirit-eph'); ?>:</label></th>
                            <td><input type="text" name="tsseph_options[ApiKey]" value="<?php esc_attr_e($tsseph_options['ApiKey']); ?>" size="64" class="regular-text code"></td>
                        </tr>                                
                    </table>   
            </div>
        </div> 
        <div class="postbox">
            <h3><?php _e('Nastavenie pluginu', 'spirit-eph'); ?></h3>
            <div class="inside">	
                <table class="form-table" style="max-width:650px;">
                    <tr>
                        <th><label for="tsseph_options[SendTrackingNo]"><?php _e('Vloženie pod. čísla do emailu', 'mpod-eph'); ?>:</label></th>
                        <td>
                            <input type="checkbox" value="1" name="tsseph_options[SendTrackingNo]" <?php checked($tsseph_options['SendTrackingNo'],"1",true); ?>>
                            <div class="tooltip">
                                <span class="dashicons dashicons-info"></span>
                                <span class="tooltiptext">
                                    <?php _e('Po odoslaní objednávky do EPH a vygenerovaní adresných štítkov, získa plugin podacie číslo, vďaka ktorému je možné sledovať objednávku. Toto nastavenie pridáva získané podacie číslo do objednávkového emailu - stav Vybavená.', 'spirit-eph'); ?>
                                </span>
                            </div>
                        </td>
                    </tr>							
                    <tr>
                        <th><label for="tsseph_options[PaymentType]"><?php _e('Použiť dobierku pre platbu', 'mpod-eph'); ?>:</label></th>
                        <td>
                        <?PHP 
                                if (!isset($tsseph_options['PaymentType'])) {
                                    $tsseph_options['PaymentType'] = ['cod'];
                                } 

                                $PaymentGateways = new WC_Payment_Gateways();
                            ?>
                            <select class="wc-product-search" name="tsseph_options[PaymentType][]" multiple="multiple" style="width: 150px;">
                            <?PHP
                                foreach($PaymentGateways->payment_gateways() as $PaymentGateway) {

                                    if ($PaymentGateway->enabled == "yes") {
                                        $selected = in_array( $PaymentGateway->id, $tsseph_options['PaymentType'] ) ? ' selected="selected" ' : '';

                                        echo "<option value=\"" . $PaymentGateway->id . "\"" . $selected . ">" . $PaymentGateway->method_title . "</option>";                                                							
                                    }
                                }
                                ?>					
                            </select>
                            <div class="tooltip">
                                <span class="dashicons dashicons-info"></span>
                                <span class="tooltiptext">
                                    <?php _e('Dobierka bude započítaná v objednávke iba pre uvedené platobné metódy.', 'spirit-eph'); ?>
                                </span>
                            </div>                                 
                        </td>
                    </tr>		
                </table>   
            </div>
        </div> 

        <div class="postbox">
            <h3><?php _e('Základné nastavenia', 'spirit-eph'); ?></h3>
            <div class="inside">
                <p><?php _e('Vyplňte prosím všetky povinné údaje.', 'spirit-eph'); ?></p>	

                <table class="form-table" style="max-width:650px;">
                    <tr>
                        <th><label for="tsseph_options[ZmluvnyVztahEnabled]"><?php _e('Zmluvný vzťah', 'spirit-eph'); ?>:</label></th>
                        <td><input type="checkbox" value="1" name="tsseph_options[ZmluvnyVztahEnabled]" id="tsseph_ZmluvnyVztahEnabled" <?php checked($tsseph_options['ZmluvnyVztahEnabled'],"1",true); ?>>Mám so Slovenskou poštou zmluvný vzťah.</td>
                    </tr>	
                </table>            

                <h3>Adresa odosielateľa</h3>	
                <table class="form-table" style="max-width:650px;">
                    <tr>
                        <th><label for="tsseph_options[OdosielatelID]"><?php _e('ID Odosieľatela', 'spirit-eph'); ?>:</label></th>
                        <td><input name="tsseph_options[OdosielatelID]" type="text" value="<?php esc_attr_e($tsseph_options['OdosielatelID']); ?>">
                            <div class="tooltip">
                                <span class="dashicons dashicons-info"></span>
                                <span class="tooltiptext">
                                    <?php _e('Identifikácia podávateľa na základe prideleného kódu po registrácii v EKP na stránke.', 'spirit-eph'); ?>
                                </span>
                            </div>
                        </td>
                    </tr>		
                    <tr>
                        <th><label for="tsseph_options[Meno]"><?php _e('Meno', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                        <td><input name="tsseph_options[Meno]" type="text" value="<?php esc_attr_e($tsseph_options['Meno']); ?>">
                            <div class="tooltip">
                                <span class="dashicons dashicons-info"></span>
                                <span class="tooltiptext">
                                    <?php _e('Titul, krstné meno a priezvisko.', 'spirit-eph'); ?>
                                </span>
                            </div>				
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[Organizacia]"><?php _e('Organizacia', 'spirit-eph'); ?>:</label></th>
                        <td><input name="tsseph_options[Organizacia]" type="text" value="<?php esc_attr_e($tsseph_options['Organizacia']); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[Ulica]"><?php _e('Ulica', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                        <td><input name="tsseph_options[Ulica]" type="text" value="<?php esc_attr_e($tsseph_options['Ulica']); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[Mesto]"><?php _e('Mesto', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                        <td><input name="tsseph_options[Mesto]" type="text" value="<?php esc_attr_e($tsseph_options['Mesto']); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[PSC]"><?php _e('PSČ', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                        <td><input name="tsseph_options[PSC]" type="text" value="<?php esc_attr_e($tsseph_options['PSC']); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[Krajina]"><?php _e('Krajina', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                        <td><input name="tsseph_options[Krajina]" type="text" value="<?php esc_attr_e($tsseph_options['Krajina']); ?>"></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input type="checkbox" value="1" name="tsseph_options[RovnakaNavratova]" id="tsseph_RovnakaNavratova" <?php checked($tsseph_options['RovnakaNavratova'],"1",true); ?>>Návratová adresa je tá istá.</td>
                    </tr>
                    </table>

                    <!-- Return address block -->
                    <div class="tsseph_return_address" style="<?php echo ($tsseph_options['RovnakaNavratova'] == 1 ? 'display:none;' : ''); ?>">
                        <h3>Návratová adresa</h3>
                        <table class="form-table" style="max-width:650px;">
                            <tr>
                                <th><label for="tsseph_options[SMeno]"><?php _e('Meno', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                                <td><input name="tsseph_options[SMeno]" type="text" value="<?php esc_attr_e($tsseph_options['SMeno']); ?>">
                                    <div class="tooltip">
                                        <span class="dashicons dashicons-info"></span>
                                        <span class="tooltiptext">
                                            <?php _e('Titul, krstné meno a priezvisko.', 'spirit-eph'); ?>
                                        </span>
                                    </div>				
                                </td>
                            </tr>									
                            <tr>
                                <th><label for="tsseph_options[SOrganizacia]"><?php _e('Organizacia', 'spirit-eph'); ?>:</label></th>
                                <td><input name="tsseph_options[SOrganizacia]" type="text" value="<?php esc_attr_e($tsseph_options['SOrganizacia']); ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="tsseph_options[SUlica]"><?php _e('Ulica', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                                <td><input name="tsseph_options[SUlica]" type="text" value="<?php esc_attr_e($tsseph_options['SUlica']); ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="tsseph_options[SMesto]"><?php _e('Mesto', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                                <td><input name="tsseph_options[SMesto]" type="text" value="<?php esc_attr_e($tsseph_options['SMesto']); ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="tsseph_options[SPSC]"><?php _e('PSČ', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                                <td><input name="tsseph_options[SPSC]" type="text" value="<?php esc_attr_e($tsseph_options['SPSC']); ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="tsseph_options[SKrajina]"><?php _e('Krajina', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                                <td><input name="tsseph_options[SKrajina]" type="text" value="<?php esc_attr_e($tsseph_options['SKrajina']); ?>"></td>
                            </tr>							
                        </table>
                    </div>

                    <h3>Ostatné informácie</h3>
                    <table class="form-table" style="max-width:650px;">   					
                    <tr>
                        <th><label for="tsseph_options[Telefon]"><?php _e('Telefón', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                        <td>
                            <input name="tsseph_options[Telefon]" type="text" value="<?php esc_attr_e($tsseph_options['Telefon']); ?>">
                            <div class="tooltip"><span class="dashicons dashicons-info"></span>
                                <span class="tooltiptext">
                                    <?php _e('Číslo na mobilný telefón v tvare 0987654321.', 'spirit-eph'); ?><br>
                                    <?php _e('Zasielanie notifikácií sa poskytuje iba na slovenské telefónne čísla.', 'spirit-eph'); ?>
                                </span>
                            </div>						
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[Email]"><?php _e('Email', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                        <td><input name="tsseph_options[Email]" type="text" value="<?php esc_attr_e($tsseph_options['Email']); ?>"></td>
                    </tr>	
                    <tr>
                        <th><label for="tsseph_options[CisloUctu]"><?php _e('Číslo účtu', 'spirit-eph'); ?>:</label><span class="asterisk">*</span></th>
                        <td><input name="tsseph_options[CisloUctu]" type="text" value="<?php esc_attr_e($tsseph_options['CisloUctu']); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[TypEPH]"><?php _e('Typ EPH', 'spirit-eph'); ?>:</label></th>
                        <td>
                            <select name="tsseph_options[TypEPH]">
                                <option value="1"><?php _e('EPH', 'spirit-eph'); ?></option>
                                <option value="2"><?php _e('Potvrdenie o prijatých zásielkach', 'spirit-eph'); ?></option>
                            </select>	
                            <div class="tooltip">
                                <span class="dashicons dashicons-info"></span>
                                <span class="tooltiptext">
                                    <?php _e('Pri podaji zásielok sa vždy vypĺňa hodnota 1 (EPH).', 'spirit-eph'); ?>   				
                                </span>
                            </div>						
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[SposobSpracovania]"><?php _e('Spôsob spracovania', 'spirit-eph'); ?>:</label></th>
                        <td>
                            <?PHP 
                                $SposobSpracovania = [0 => '',
                                                1 => __('Podaj na poštách hromadného podaja', 'spirit-eph'),
                                                2 => __('Podaj na oblastnom uzle expresných služieb', 'spirit-eph'),
                                                3 => __('Podaj na pošte', 'spirit-eph'),
                                                4 => __('Podaj na medzinárodnej pošte Bratislava 090', 'spirit-eph')];
                            ?>
                            <select name="tsseph_options[SposobSpracovania]">
                                <?PHP
                                    foreach ($SposobSpracovania as  $key => $value) {
                                        echo "<option value=\"" . $key . "\"" . selected($tsseph_options['SposobSpracovania'], $key) . ">" . $value . "</option>";
                                    }
                                ?>					
                            </select>
                            <div class="tooltip"><span class="dashicons dashicons-info"></span>
                            <span class="tooltiptext">
                            <?php _e('Parameter nie je povinný, ak vám nebola stanovená jeho hodnota, neuvádzajte ho prosím.','spirit-eph')?>
                            </span></div>                                  					
                        </td>
                    </tr>                        
                    <tr>
                        <th><label for="tsseph_options[SposobUhrady]"><?php _e('Spôsob úhrady', 'spirit-eph'); ?>:</label></th>
                        <td>
                            <?PHP 
                                $SposobUhrady = [1 => __('Poštovné úverované', 'spirit-eph'),
                                                2 => __('Výplatný stroj', 'spirit-eph'),
                                                3 => __('Platené prevodom', 'spirit-eph'),
                                                4 => __('Poštové známky', 'spirit-eph'),
                                                5 => __('Platené v hotovosti', 'spirit-eph'),
                                                7 => __('Vec poštovej služby', 'spirit-eph'),
                                                8 => __('Faktúra', 'spirit-eph'),
                                                9 => __('Online (platba kartou)', 'spirit-eph')];
                            ?>
                            <select name="tsseph_options[SposobUhrady]">
                                <?PHP
                                    foreach ($SposobUhrady as  $key => $value) {
                                        echo "<option value=\"" . $key . "\"" . selected($tsseph_options['SposobUhrady'], $key) . ">" . $value . "</option>";
                                    }
                                ?>					
                            </select> 
                            <div class="tsseph_warning">Pri možnosti "Online (platba kartou)" a "Faktúra" sa posiela do pošty celková váha objednávky. <strong>Uistite sa, že máte nastavenú váhu pre všetky produkty, alebo nastavte váhu priamo v objednávke.</strong></div>                             					
                            <div class="tsseph_warning_faktura">Pri možnosti "Faktúra" <strong>uveďte rozsah podacích čísel a aktuálne podacie číslo.</strong></div>    
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[Trieda]"><?php _e('Trieda', 'spirit-eph'); ?>:</label></th>
                        <td>
                            <?PHP 
                                $Trieda = [1 => __('1 - Prvá trieda', 'spirit-eph'),
                                        2 => __('2 - Druhá trieda', 'spirit-eph')];
                            ?>
                            <select name="tsseph_options[Trieda]">
                                <?PHP
                                    foreach ($Trieda as  $key => $value) {
                                        echo "<option value=\"" . $key . "\"" . selected($tsseph_options['Trieda'], $key)  . ">" . $value . "</option>";
                                    }
                                ?>					
                            </select>					
                        </td>
                    </tr>
                    <?php if (isset($tsseph_bonus_options[1470]) && $tsseph_bonus_options[1470]['Enabled'] ) { ?>
                    <tr>
                        <th><label for="tsseph_options[UloznaLehota]"><?php _e('Úložná lehota', 'spirit-eph'); ?>:</label></th>
                        <td><input name="tsseph_options[UloznaLehota]" min="0" max="100" type="number" value="<?php echo (empty($tsseph_options['UloznaLehota']) ? 0 : absint($tsseph_options['UloznaLehota']) ); ?>">
                            <div class="tooltip"><span class="dashicons dashicons-info"></span>
                                <span class="tooltiptext">
                                <?php _e('Určuje počet dní koľko má zasielka ostať na pošte.','spirit-eph')?>
                                </span>
                            </div>  
                        </td>
                    </tr>
                    <?php } ?>													
                </table>
            </div>
        </div>

        <div class="postbox">
            <h3 class="hndle"><?php _e( 'Predvolený druh zásielky', 'spirit-eph' ); ?></h3>
            <div class="inside">
                <p><?php _e('Hodnota vyjadruje predvolený druh zasielky v tabuľke objednávok.','spirit-eph'); ?></p>
                <table class="form-table" style="max-width:800px;">
                    <?php

                        $ShippingZones = WC_Shipping_Zones::get_zones();

                        foreach($ShippingZones as $ShippingZone) {
                            foreach($ShippingZone['shipping_methods'] as $ShippingMethod) {

                            
                                //Do not show Shipping method if it is not enabled
                                if (!$ShippingMethod->is_enabled()) continue;

                    ?>
                    <tr valign="top">
                        <th>
                            <?php echo $ShippingZone['zone_name']; ?>
                        </th>
                        <td>
                            <?php echo $ShippingMethod->get_method_title() . " - " .  $ShippingMethod->get_title(); ?>
                        </td>
                        <td>
                            <select name="tsseph_options[PredvolenyDruhZasielky_<?php echo $ShippingMethod->get_instance_id(); ?>]">
                            <?php
                                foreach(tsseph_get_druh_zasielky_options() as $key => $druh_zasielky) {

                                    //Backward compatibility with version 1.0.9
                                    if (isset($tsseph_options['PredvolenyDruhZasielky']) && empty($tsseph_options['PredvolenyDruhZasielky_' . $ShippingMethod->get_instance_id()])) {
                                        $tsseph_options['PredvolenyDruhZasielky_' . $ShippingMethod->get_instance_id()] = $tsseph_options['PredvolenyDruhZasielky'];
                                    }
                            ?>
                                    <option value="<?php echo $key;?>" <?php if (isset($tsseph_options['PredvolenyDruhZasielky_' . $ShippingMethod->get_instance_id()])) { echo ($key == $tsseph_options['PredvolenyDruhZasielky_' . $ShippingMethod->get_instance_id()] ? 'selected' : ''); } ?> ><?php echo $druh_zasielky;?></option>
                            <?php
                                }
                            }    
                            ?>
                            </select>                                            
                        </td>
                    </tr>    
                    <?php
                        }
                    ?>
                </table>  
            </div>
        </div>    					

        <div class="postbox">
            <h3><?php _e('Nastavenia podacích čísiel', 'spirit-eph'); ?></h3>
            <div class="inside">
            <p><input type="checkbox" value="1" name="tsseph_options[PodacieCislaEnabled]" id="tsseph_PodacieCislaEnabled" <?php checked($tsseph_options['PodacieCislaEnabled'],"1",true); ?>>Máte pridelené vlastné podacie čísla?</p>
                <div class="tsseph_podacie_cisla" style="<?php echo ($tsseph_options['PodacieCislaEnabled'] ? "" : "display:none"); ?>">
                    
                <?PHP 

                //Init podacie cisla if needed 
                if (!isset($tsseph_options['PodacieCisla'])) { $tsseph_options['PodacieCisla'] = tsseph_init_parcel_numbers(); }

                foreach($tsseph_options['PodacieCisla'] as $key => $podacie_cisla) {

                    $druh_zasielky = tsseph_posta_api_get_druh_zasielky($key);

                    if ($key == '14' || ($key == '8' && isset($tsseph_bonus_options[1450]) && $tsseph_bonus_options[1450]['Enabled'])) {
                ?>                            
                <strong><?php echo $druh_zasielky['name']; ?></strong>
                <hr>
                <p><?php _e('Ak máte pridelené podacie čísla, tu ich môžete zadať.', 'spirit-eph'); ?></p>
                <table class="form-table" style="max-width:650px;">

                    <tr>
                        <th><label><?php _e('Rozsah podacích čísiel', 'spirit-eph'); ?>:</label></th>
                        <td>
                            <input name="tsseph_options[PodacieCisla][<?php echo $key; ?>][RozsahPodCisFrom]" type="text" value="<?php esc_attr_e($podacie_cisla['RozsahPodCisFrom']); ?>">
                            <span> - </span>
                            <input name="tsseph_options[PodacieCisla][<?php echo $key; ?>][RozsahPodCisTo]" type="text" value="<?php esc_attr_e($podacie_cisla['RozsahPodCisTo']); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tsseph_options[PodacieCisla][<?php echo $key; ?>][AktualnePodCislo]"><?php _e('Aktuálne podacie číslo', 'spirit-eph'); ?>:</label></th>
                        <td>
                            <input name="tsseph_options[PodacieCisla][<?php echo $key; ?>][AktualnePodCislo]" type="text" value="<?php esc_attr_e($podacie_cisla['AktualnePodCislo']); ?>">
                            <div class="tooltip"><span class="dashicons dashicons-info"></span>
                                <span class="tooltiptext">
                                    <?php _e('Prvé podacie číslo v rozsahu, ktoré ešte nebolo použité.','spirit-eph')?>
                                </span>
                            </div>            
                        </td>
                    </tr>
                </table>
                
                <?php
                    }
                }
                ?>
                </div>				
            </div>
        </div>

        <?php
            if (!empty($tsseph_options['LastLog'])) {
        ?>
        <div class="postbox">
            <h3><?php _e('Plugin log', 'spirit-eph'); ?></h3>
            <div class="inside">
                <p><?php _e('Log poslednej API aktivity. Slúži na analýzu a nájdenie chyby v komunikácii.', 'spirit-eph'); ?></p>	
                <p><span id="spirit-eph-show-log"><?php _e('Zobraziť >>', 'spirit-eph'); ?></span></p>	
                <div id="spirit-eph-log">
                <?php
                    foreach($tsseph_options['LastLog'] as $id => $log) {

                        if (!empty($log)) {
                ?>
                    <table class="form-table" style="max-width:650px;">       
                        <tr>
                            <th><label><?php _e('API metóda', 'spirit-eph'); ?>:</label></th>
                            <td><pre><?php echo $id; ?></pre></td>
                        </tr>									                                           
                        <tr>
                            <th><label><?php _e('Do Slovenskej pošty sa poslalo', 'spirit-eph'); ?>:</label></th>
                            <td><pre><?php print_r(unserialize($log)[0]); ?></pre></td>
                        </tr>	
                        <tr>
                            <th><label><?php _e('Slovenská pošta odpovedala', 'spirit-eph'); ?>:</label></th>
                            <td><pre><?php print_r(unserialize($log)[1]); ?></pre></td>
                        </tr>	                               
                    </table>
                <?php
                        }
                    }
                ?>
                </div>
            </div>
        </div>
        <?php
            } //LastLog					
        ?>								
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e( 'Uložiť', 'spirit-eph' ); ?>" />
        </p>
    </div>
    <?php
}

?>