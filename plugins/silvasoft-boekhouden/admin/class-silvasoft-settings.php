<?php
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

/**
 * Silvasoft admin settings page
 */
if (!class_exists("Silvasoft_Settings")) :

    class Silvasoft_Settings
    {
        function __construct() {
           //init admin settings fields			
            add_action('admin_init', array($this,'silvasoft_admin_settings_init'), 20);
        }

		//register all the admin settings, default values and rendering of the settings     
        function silvasoft_admin_settings_init()
        {
            register_setting('silva_pluginPage', 'silva_settings');

            add_settings_section(
                'silva_pluginPage_section',
                __('Connectie met Silvasoft', 'wordpress'),
                array($this,'silva_settings_callback'),
                'silva_pluginPage'
            );

			 add_settings_field(
                'silva_api_url',
                __('API url van Silvasoft', 'wordpress'),
                array($this,'silvasoft_render_text'),
                'silva_pluginPage',
                'silva_pluginPage_section',
                array(
                    'name' => 'silva_api_url',
					'type' => 'public'
                )
            );
			
            add_settings_field(
                'silva_username',
                __('Uw silvasoft gebruikersnaam (e-mail)', 'wordpress'),
                array($this,'silvasoft_render_text'),
                'silva_pluginPage',
                'silva_pluginPage_section',
                array(
                    'name' => 'silva_username',
					'type' => 'public',
					'autocomplete' => 'off'					
                )
            );
	
            add_settings_field(
                'silva_api_token',
                __('Uw Silvasoft API token', 'wordpress'),
                array($this,'silvasoft_render_text'),
                'silva_pluginPage',
                'silva_pluginPage_section',
                array(
                    'name' => 'silva_api_token',
					'type' => 'private',
					'autocomplete' => 'off',
					'hint' => '<strong>Let op: uw API token is NIET hetzelfde als uw Silvasoft wachtwoord. Het is een aparte token die u kunt aanmaken via het menu: Beheer > API in Silvasoft.</strong>'
					
                )
            );
			
			
			add_settings_section(
                'silva_pluginPage_section2',
                __('Instellingen', 'wordpress'),
                array($this,'silva_settings_callback'),
                'silva_pluginPage'
            );		

   			add_settings_field(
                'silvasoft_status_sale',
                __('Bij welke order status wilt u de orders versturen naar uw boekhouding, als VERKOOP?', 'wordpress'),
                array($this,'silvasoft_render_select1'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_status_sale'
                )
            );
			
			add_settings_field(
                'silvasoft_status_credit',
                __('Bij welke order status wilt u de orders versturen naar uw boekhouding, als CREDITNOTA?', 'wordpress'),
                array($this,'silvasoft_render_select1'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_status_credit'
                )
            );
			
			
			
			add_settings_field(
                'silvasoft_directorcron',
                __('Wanneer wilt u een order versturen naar Silvasoft?', 'wordpress'),
                array($this,'silvasoft_render_select4'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_directorcron',
					'hint' => '<br/><div style="background: #fff;padding:5px;border:1px solid #ccc"><u>Uitleg bij de keuzes:</u><br/><ol><li>Direct na statuswijziging bestelling: zodra een order voldoet aan de status die hierboven is ingesteld wordt deze verstuurd naar Silvasoft. Dit gebeurd direct bij het ontvangen van de statuswijziging (bijvoorbeeld bij het ontvangen van een betaalbevestiging, of bij het handmatig afronden van een order). </li><li>In de achtergrond elke 10 minuten (CRON): indien u voor deze optie kiest wordt elke 10 minuten gezocht naar nieuwe orders die nog niet verstuurd zijn naar Silvasoft. Indien de status bij die orders overeenkomt met de status die hierboven is ingesteld, zal de order verstuurd worden naar Silvasoft. Hiervoor maakt de plugin gebruik van het Wordpress CRON systeem. Bij sommige webshops is het CRON systeem niet actief, als orders bij deze instelling niet worden verstuurd naar Silvasoft kunt u uw webbouwer contacteren voor hulp. </li><li>Enkel handmatig: orders worden nooit automatisch verstuurd naar Silvasoft. U kunt handmatig orders versturen vanuit het orderoverzicht in WooCommerce (dit kan ook in bulk voor een selectie van orders).</li></ol></div> ',
                )
            );
			
			add_settings_field(
                'silvasoft_endpoint',
                __('Hoe wilt u de orders versturen naar Silvasoft?', 'wordpress'),
                array($this,'silvasoft_render_select2'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_endpoint'
                )
            );
			
			add_settings_field(
                'silvasoft_relationtype',
                __('Hoe wilt u de relaties aanmaken in Silvasoft?', 'wordpress'),
                array($this,'silvasoft_render_select2a'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_relationtype',
					'hint'=> 'Standaard bepalen we automatisch het relatie type (bedrijf or particulier). U kunt dit echter overschrijven om relaties altijd naar Silvasoft te versturen als ofwel zakelijke ofwel particuliere relaties. '
                )
            );
			
			add_settings_field(
                'silvasoft_invoicenote',
                __('Welke opmerking wilt u automatisch toevoegen op de factuur in Silvasoft?', 'wordpress'),
                array($this,'silvasoft_render_text'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_invoicenote',
					'hint' => 'U kunt de token {ordernr} gebruiken om het ordernummer van Woocommerce op te nemen in de tekst. Voorbeeld / standaard: "Boeking aangemaakt vanuit WooCommerce met order nummer: #{ordernr}". U kunt ook de token {orderid} gebruiken voor de order ID, of de token {empty} om geen omschrijving mee te sturen. ',
                )
            );
          
		  add_settings_field(
                'silvasoft_address2ashousenumber',
                __('Gebruik address_2 als huisnummer?', 'wordpress'),
                array($this,'silvasoft_render_select_yesno'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_address2ashousenumber',
					'hint' => 'Normaliter heeft Woocommerce 1 veld voor het volledige adres inclusief huisnummer. Indien uw webshop zo is ingericht dat het Woocommerce veld adddress_2 geld als huisnummer kunt u deze instelling op "Ja" zetten om het huisnummer mee te sturen naar het juiste veld in Silvasoft',
                )
            );
			
		
			add_settings_field(
                'silvasoft_ordernrasreferentie',
                __('Stuur het ordernummer mee als referentie in Silvasoft?', 'wordpress'),
                array($this,'silvasoft_render_select_yesno'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_ordernrasreferentie',
					'hint' => 'Indien gewenst kunt u het WooCommerce ordernummer meesturen naar het referentieveld van een factuur in Silvasoft. Dit veld wordt enkel gebruikt indien u bij de instelling "Hoe wilt u de orders versturen naar Silvasoft" heeft gekozen voor "Aanmaken als Facturatie verkoopfactuur".',
                )
            );
          
		  	add_settings_field(
                'silvasoft_postzerolines',
                __('Orderregels met bedrag 0 euro meesturen naar Silvasoft?', 'wordpress'),
                array($this,'silvasoft_render_select_yesno'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_postzerolines',
					'hint' => 'Kies of u orderregels van 0 euro (bijvoorbeeld gratis producten / gratis verzending) wel of niet wilt meesturen naar Silvasoft.',
                )
            );
			
		
		
			
			add_settings_field(
                'silvasoft_dateuse',
                __('Met welke datum wilt u de order versturen naar Silvasoft?', 'wordpress'),
                array($this,'silvasoft_render_select3'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_dateuse'
                )
            );
			
			
			add_settings_field(
                'silvasoft_shippingtaxdistinguish',
                __('Voeg btw-percentage toe aan artikelnummer in Silvasoft voor verzendkosten?', 'wordpress'),
                array($this,'silvasoft_render_select_yesno'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_shippingtaxdistinguish',
					'hint' => 'Verzendkosten worden normaliter in Silvasoft geboekt onder artikel "woo-shipping". Indien deze instelling is ingeschakeld zal hier het btw percentage aan toegevoegd worden. Dan komt het dus binnen als woo-shipping-21 voor 21% btw. Zo kunt u in Silvasoft onderscheid maken tussen verschillende btw-tarieven voor verzendkosten. ',
                )
            );
			
			add_settings_field(
                'silvasoft_usearticledescription',
                __('Gebruik artikelomschrijving in Silvasoft?', 'wordpress'),
                array($this,'silvasoft_render_select_yesno'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_usearticledescription',
					'hint' => 'Indien ingeschakelt zal Silvasoft voor artikelen welke reeds aanwezig zijn in uw Silvasoft administratie de omschrijving van het artikel zoals geconfigureerd in Silvasoft gebruiken als omschrijving voor de factuur- of orderregel. Anders zal de naam van het artikel gebruikt worden als omschrijving.',
                )
            );
			
			add_settings_field(
                'silvasoft_debugmode',
                __('Debug mode?', 'wordpress'),
                array($this,'silvasoft_render_select_yesno'),
                'silva_pluginPage',
                'silva_pluginPage_section2',
                array(
                    'name' => 'silvasoft_debugmode',
					'hint' => 'Indien ingeschakelt zal Silvasoft meer logs genereren. Dit kan helpen bij het opsporen van fouten of problemen met de koppeling. Het is niet aanbevolen om deze instelling in te schakelen.',
                )
            );
			
			 add_settings_section(
                'silva_pluginPage_section3',
                __('Boekhouding', 'wordpress'),
                array($this,'silva_settings_callback'),
                'silva_pluginPage'
            );
			
			add_settings_field(
                'silvasoft_shippingtaxpc',
                __('Welk btw-percentage is van toepassing op gratis verzending?', 'wordpress'),
                array($this,'silvasoft_render_number'),
                'silva_pluginPage',
                'silva_pluginPage_section3',
                array(
                    'name' => 'silvasoft_shippingtaxpc',
					'hint' => 'We nemen het btw-tarief vanuit WooCommerce automatisch over voor verzendkosten. Echter, indien u werkt met gratis verzending kunt u hier het backup btw-tarief invullen die gebruikt kan worden. Deze instelling is dus enkel van toepassing op gratis verzending.'
                )
            );
			
		  	add_settings_field(
                'silvasoft_vatexemptvat',
                __('(optioneel) Op welke btw-code wilt u orders waarop de btw is verlegd boeken?', 'wordpress'),
                array($this,'silvasoft_render_text'),
                'silva_pluginPage',
                'silva_pluginPage_section3',
                array(
                    'name' => 'silvasoft_vatexemptvat',
					'hint' => 'Indien bij een order wordt herkend dat de btw is verlegd naar de klant dan zal de order standaard op 0% btw worden geboekt. Mogelijk heeft u een andere btw-code speciaal voor verlegde verkopen. U kunt dan hier de btw-code opgeven. Dit moet exact de btw-code zijn zoals in Silvasoft bekend. ',
                )
            );
			
			add_settings_field(
                'silvasoft_ledgerdomestic',
                __('(optioneel) Op welk opbrengstrekeningnummer wilt u BINNENLANDSE verkopen boeken?', 'wordpress'),
                array($this,'silvasoft_render_number'),
                'silva_pluginPage',
                'silva_pluginPage_section3',
                array(
                    'name' => 'silvasoft_ledgerdomestic',
					'hint' => 'Let op: dit moet het nummer zijn van een resultaatrekening die aanwezig is in Silvasoft. Laat leeg om te boeken op uw standaard opbrengstrekening.'
                )
            );
			add_settings_field(
                'silvasoft_ledgereu',
                __('(optioneel) Op welk opbrengstrekeningnummer wilt u verkopen BINNEN DE EU boeken?', 'wordpress'),
                array($this,'silvasoft_render_number'),
                'silva_pluginPage',
                'silva_pluginPage_section3',
                array(
                    'name' => 'silvasoft_ledgereu',
					'hint' => 'Let op: dit moet het nummer zijn van een resultaatrekening die aanwezig is in Silvasoft. Laat leeg om te boeken op uw standaard opbrengstrekening.'
                )
            );
			add_settings_field(
                'silvasoft_ledgerexport',
                __('(optioneel)  Op welk opbrengstrekeningnummer wilt u verkopen BUITEN DE EU boeken?', 'wordpress'),
                array($this,'silvasoft_render_number'),
                'silva_pluginPage',
                'silva_pluginPage_section3',
                array(
                    'name' => 'silvasoft_ledgerexport',
					'hint' => 'Let op: dit moet het nummer zijn van een resultaatrekening die aanwezig is in Silvasoft. Laat leeg om te boeken op uw standaard opbrengstrekening.'
                )
            );
			
				
			add_settings_field(
                'silvasoft_countryledger',
                __('(optioneel)  Afwijkende opbrengstrekeningen voor landen', 'wordpress'),
                array($this,'silvasoft_render_countryledger'),
                'silva_pluginPage',
                'silva_pluginPage_section3',
                array(
                    'name' => 'silvasoft_countryledger',
					'hint' => '<div style="background: #fff;padding:5px;border:1px solid #ccc"><u>Uitleg</u><br/>Koppel hier afwijkende opbrengstrekeningen aan landcodes. Zo kunt u omzet uit verschillende landen scheiden op uw grootboekrekeningen. De landcode moet een 2-letterige landcode zijn (voorbeeld NL, BE, DE, ES) en de opbrengstrekening moet een nummer zijn van een bestaande grootboekrekening in Silvasoft.</div>'
                )
            );
			
			add_settings_field(
                'silvasoft_countrytax',
                __('(optioneel)  Afwijkende btw-codes voor landen', 'wordpress'),
                array($this,'silvasoft_render_countrytax'),
                'silva_pluginPage',
                'silva_pluginPage_section3',
                array(
                    'name' => 'silvasoft_countrytax',
					'hint' => '<div style="background: #fff;padding:5px;border:1px solid #ccc"><u>Uitleg</u><br/>Koppel hier afwijkende btw-codes aan landcodes. Zo kunt u omzet uit verschillende landen scheiden op verschillende btw-codes in Silvasoft. Als u dit leeglaat zal Silvasoft standaard de eerste btw-code gebruiken die het juiste btw-percentage heeft. De landcode moet een 2-letterige landcode zijn (voorbeeld NL, BE, DE, ES). Vervolgens kiest u het btw-percentage (1 land kan meerdere btw-percentages hebben, bijvoorbeeld hoog en laag tarief). Tot slot kiest u de btw-code die in Silvasoft gebruikt moet worden. Let op: u kunt enkel btw-codes kiezen als uw API verbinding met Silvasoft correct is ingesteld.</div>'
                )
            );
          
			
		   if ( get_option('silva_settings') === false) {
				//set default values for options
				 update_option( 'silva_settings', array('silvasoft_status_credit' => 'wc-refunded',
				 										'silvasoft_status_sale' => 'wc-completed',
														'silvasoft_endpoint' => 'addsalestransaction',
														'silva_api_url' => 'https://rest-api.silvasoft.nl/rest/',
														'silvasoft_invoicenote' => 'Boeking aangemaakt vanuit WooCommerce (order #{ordernr})',	
														'silvasoft_address2ashousenumber' => 'no',	
														'silvasoft_ordernrasreferentie' => 'yes',	
														'silvasoft_postzerolines' => 'yes',
														'silvasoft_shippingtaxpc' => 21,
														'silvasoft_debugmode' => 'no',
														'silvasoft_shippingtaxdistinguish' => 'no',
														'silvasoft_usearticledescription' => 'no',
														'silvasoft_taxmethod'=>'calculated',
														'silvasoft_countryledger'=>'',
														'silvasoft_countrytax'=>'',
														'silvasoft_relationtype'=>'auto',
														) );  
		   }
        }

		/* Render the text settings */
        function silvasoft_render_text($args)
        {
            $options = get_option('silva_settings');
          ?>
            <input type='<?php echo $args['type'] == 'private' ? 'password' : 'text' ?>'  name='silva_settings[<?php echo $args['name'] ?>]' value='<?php echo isset($options[$args['name']]) ?$options[$args['name']] : '' ; ?>' style='width: 400px'>
        	
			<?php
			if(isset($args['hint'])) {
			 echo '<br/>';
			 echo '<i>'.$args['hint'].'</i>';
			}
        }
		
		/* country ledger list */
		function silvasoft_render_countryledger($args)
		{
			$options = get_option('silva_settings');
			$valCur = $options[$args['name']];
          
			if(isset($args['hint'])) {
			 echo '<i style="padding-top:5px;">'.$args['hint'].'</i>';
			}

			?>
            <input type='text' id="ledgerCountryInput" name='silva_settings[<?php echo $args['name'] ?>]' value='<?php echo $valCur; ?>' style='width: 2px;visibility:hidden;'>
			
			<table style="border:1px solid #ccc;padding-left:0px;margin-left:0px;">
				<thead><td><strong>Landcode</strong></td><td><strong>Opbrengstrekening</strong></td></thead>
			<?php
				//$val = NL:44000;BE:45000;DE:46000
				
				$count = 1;
				//fill existing values, build html
				if($valCur != null && $valCur != '') {
					$vals = explode(';',$valCur);
					
					foreach($vals as $val) {
						$keyval = explode(':',$val);
						$country = isset($keyval[0]) ? $keyval[0] : '';
						$ledger = isset($keyval[1]) ? $keyval[1] : '';

						$inputCountry = "<input class='inputCountry' onChange='onChangeFormLedger()' type='text' id='country_".$count."' name='country_".$count."' value='".$country."' />";
						$inputLedger = "<input onChange='onChangeFormLedger()' type='text' name='ledger_".$count."' id='ledger_".$count."' value='".$ledger."' />";

						echo "<tr><td>".$inputCountry."</td><td>".$inputLedger."</td></tr>";

						$count++;
					}
				}
			
				$inputCountry = "<input class='inputCountry' onChange='onChangeFormLedger()' type='text' name='country_".$count."' id='country_".$count."' value='' />";
				$inputLedger = "<input onChange='onChangeFormLedger()' type='text' name='ledger_".$count."' id='ledger_".$count."' value='' />";
				
				$count++;
				
				$inputCountry1 = "<input class='inputCountry' onChange='onChangeFormLedger()' type='text' name='country_".$count."' id='country_".$count."' value='' />";
				$inputLedger1 = "<input onChange='onChangeFormLedger()' type='text' name='ledger_".$count."' id='ledger_".$count."' value='' />";
			
				$count++;
				
				$inputCountry2 = "<input class='inputCountry' onChange='onChangeFormLedger()' type='text' name='country_".$count."' id='country_".$count."' value='' />";
				$inputLedger2 = "<input onChange='onChangeFormLedger()' type='text' name='ledger_".$count."' id='ledger_".$count."' value='' />";
			
				$count++;
				
				$inputCountry3 = "<input class='inputCountry' onChange='onChangeFormLedger()' type='text' name='country_".$count."' id='country_".$count."' value='' />";
				$inputLedger3 = "<input onChange='onChangeFormLedger()' type='text' name='ledger_".$count."' id='ledger_".$count."' value='' />";
			
				echo "<tr><td>".$inputCountry."</td><td>".$inputLedger."</td></tr>";
				echo "<tr><td>".$inputCountry1."</td><td>".$inputLedger1."</td></tr>";
				echo "<tr><td>".$inputCountry2."</td><td>".$inputLedger2."</td></tr>";
				echo "<tr><td>".$inputCountry3."</td><td>".$inputLedger3."</td></tr>";
				
			?>
			</table>
			Sla uw instellingen op om een nieuwe lege regels toe te voegen. Verwijder de landcode uit een regel om de regel te verwijderen na opslaan.    
        	
			
			
			<script type="text/javascript">
				function onChangeFormLedger() {
					var countries = document.getElementsByClassName("inputCountry");
					var valString = "";
					for(var j=0;j<countries.length;j++){
						var countryInput = countries[j];
						if(typeof countryInput !== 'undefined' && countryInput != null) {
							var countryInputId = countryInput.id;
							var countryInputNumber = countryInputId.split("_")[1];
							var countryVal = countryInput.value; 
							var ledgerInput = document.getElementById("ledger_"+countryInputNumber);
							if(typeof countryInput !== 'undefined' && countryInput != null) {
								var ledgerInputId = ledgerInput.id;
								var ledgerInputVal = ledgerInput.value;  

								if(typeof ledgerInputVal !== 'undefined' && ledgerInputVal != null && ledgerInputVal != '' && typeof countryVal !== 'undefined' && countryVal != null && countryVal != '') {
									valString += countryVal + ":" + ledgerInputVal + ";";
								}
							}
						}
						
					}
					
					console.log(valString);
					document.getElementById("ledgerCountryInput").value = valString;
				}
				
				
			</script>

			<?php
			
		}
		

		/* country tax list */
		function silvasoft_render_countrytax($args)
		{
			$options = get_option('silva_settings');
			$valCur = isset($options[$args['name']]) ? $options[$args['name']] : null;
			
			$result = '';
			try {
				$result = $this->CallAPI('GET','listtaxcodes');					
			} catch (Exception $e) {
				$result = '';
			}	
			
			$optionsHtmlTaxCodes = '<option value=""></option>';
			$resultTaxCodes = array();
			
			//validate
			$validated = $this->validateResult($result,200,true);

			$msg = isset($validated['msg']) ? $validated['msg'] : '';
			//process validation
			if($validated['ok'] === true) {			
				$resultresponse = $result['response'];
				$resultTaxCodes = json_decode($resultresponse);
				
			} else{
				
				echo '<strong><font style="color:red">Connectie met Silvasoft is niet werkend / actief. Daarom konden er geen btw-codes opgehaald worden. Controleer uw instellingen. Foutmelding: '.$msg.'</font></strong><br/><br/>';
			}
			
			if(isset($args['hint'])) {
			 echo '<i style="padding-top:5px;">'.$args['hint'].'</i>';
			}

			?>
            <input type='text' id="txCountryInput" name='silva_settings[<?php echo $args['name'] ?>]' value='<?php echo $valCur; ?>' style='width: 2px;visibility:hidden;'>
			
			<table style="border:1px solid #ccc;padding-left:0px;margin-left:0px;">
				<thead><td><strong>Landcode</strong></td><td><strong>Btw-percentage</strong></td><td><strong>Silvasoft btw-code</strong></td></thead>
			<?php
				//||= seperator for info in a line |NEXT| = seperator for next line. Using these weird seperators because this won't be used in tax_code field by users
				//$val = NL||21||21%-hoogtarief|NEXT|BE||9||9%laag tarief - BE
				
				//fill existing values, build html
				$count = 1;
				if($valCur != null && $valCur != '') {
					$vals = explode('|NEXT|',$valCur);
					
					foreach($vals as $val) {
						$keyval = explode('||',$val);
						$country = isset($keyval[0]) ? $keyval[0] : '';
						$taxpc = isset($keyval[1]) ? $keyval[1] : '';
						$taxcode = isset($keyval[2]) ? $keyval[2] : '';
					
						$inputCountry = "<input class='inputCountryTx' onChange='onChangeFormTx()' type='text' id='tx_country_".$count."' name='tx_country_".$count."' value='".$country."' />";
						$inputTaxPc = "<input onChange='onChangeFormTx()' type='number' id='tx_pc_".$count."' name='tx_pc_".$count."' value='".$taxpc."' />";
						
						$misMatchTaxPcAndTaxCodePc = false;
						$misMatchGivenPc = '';
						$misMatchCodePc = '';
						
						$optionsHtmlTaxCodesCr = $optionsHtmlTaxCodes;
						foreach($resultTaxCodes as $resultTaxCode) {
							if($resultTaxCode->TaxType == 'Sales') {
								if($taxcode == $resultTaxCode->TaxCode) {
									$optionsHtmlTaxCodesCr .=  '<option selected data-pc="'.$resultTaxCode->TaxPc.'" value="'.$resultTaxCode->TaxCode.'">'.$resultTaxCode->TaxCode.'</option>';
									
									if($resultTaxCode->TaxPc != $taxpc) {
										$misMatchTaxPcAndTaxCodePc = true;
										$misMatchGivenPc = $taxpc;
										$misMatchCodePc = $resultTaxCode->TaxPc;
									}
									
								} else {
									$optionsHtmlTaxCodesCr .=  '<option data-pc="'.$resultTaxCode->TaxPc.'" value="'.$resultTaxCode->TaxCode.'">'.$resultTaxCode->TaxCode.'</option>';
								}
								
							}
						}
						
						$inputTaxCode = "<select onChange='onChangeFormTx()' name='tx_code_".$count."' id='tx_code_".$count."' value='".$taxcode."' >'.$optionsHtmlTaxCodesCr.'</select>";

						echo "<tr><td>".$inputCountry."</td><td>".$inputTaxPc."</td><td>".$inputTaxCode."</td></tr>";
						
						if($misMatchTaxPcAndTaxCodePc) {
							echo '<tr><td colspan="3" style="color:red;font-weight:bold;">FOUT! Opgegeven percentage ('.$misMatchGivenPc.') bij land '.$country .' komt niet overeen met het percentage van de gekozen btw-code ('.$misMatchCodePc.'). Wijzig btw-code en sla uw instellingen opnieuw op. </td></tr>';
						}

						$count++;
					}
				}
			
				//add 5 more rows
				$more = $count + 5; 
				//5more empty rows
				$optionsHtmlTaxCodesNw = $optionsHtmlTaxCodes;
				foreach($resultTaxCodes as $resultTaxCode) {
					if($resultTaxCode->TaxType == 'Sales') {
						$optionsHtmlTaxCodesNw .=  '<option data-pc="'.$resultTaxCode->TaxPc.'" value="'.$resultTaxCode->TaxCode.'">'.$resultTaxCode->TaxCode.'</option>';
					}
				}	
				while($count<$more) {
					$inputCountry = "<input class='inputCountryTx' onChange='onChangeFormTx()' type='text' id='tx_country_".$count."' name='tx_country_".$count."' value='' />";
					$inputTaxPc = "<input onChange='onChangeFormTx()' type='number' id='tx_pc_".$count."' name='tx_pc_".$count."' value='' />";
					
					
					
					$inputTaxCode = "<select onChange='onChangeFormTx()' name='tx_code_".$count."' id='tx_code_".$count."' value='' >'.$optionsHtmlTaxCodesNw.'</select>";
					
					echo "<tr><td>".$inputCountry."</td><td>".$inputTaxPc."</td><td>".$inputTaxCode."</td></tr>";
					
					$count++;
				}
				
			?>
			</table>
			Sla uw instellingen op om een nieuwe lege regels toe te voegen. Verwijder de landcode uit een regel om de regel te verwijderen na opslaan.    
        	
			
			
			<script type="text/javascript">
				function onChangeFormTx() {
					var countries = document.getElementsByClassName("inputCountryTx");
					var valString = "";
					for(var j=0;j<countries.length;j++){
						var countryInput = countries[j];
						if(typeof countryInput !== 'undefined' && countryInput != null) {
							var countryInputId = countryInput.id;
							var countryInputNumber = countryInputId.split("_")[2];
							var countryVal = countryInput.value; 
							var taxPcInput = document.getElementById("tx_pc_"+countryInputNumber);
							var taxCodeInput = document.getElementById("tx_code_"+countryInputNumber);
							
							
							if(typeof countryInput !== 'undefined' && countryInput != null) {
								var taxPcInputId = taxPcInput.id;
								var taxPcInputVal = taxPcInput.value;  
								var taxCodeInputId = taxCodeInput.id;
								var taxCodeInputVal = taxCodeInput.value; 
								
								//set val if undefined or null
								if(typeof taxCodeInputVal !== 'undefined' && taxCodeInputVal != null) {
								} else {taxCodeInputVal = '';}
									
								
								if(typeof taxPcInputVal !== 'undefined' && taxPcInputVal != null && taxPcInputVal != '' && typeof countryVal !== 'undefined' && countryVal != null && countryVal != '') {
									valString += countryVal + "||" + taxPcInputVal + "||" + taxCodeInputVal + "|NEXT|";
								}
							}
						}
						
					}
					
					console.log(valString);
					document.getElementById("txCountryInput").value = valString;
				}
				
				
			</script>

			<?php
			
		}

		/* Render the number settings */
        function silvasoft_render_number($args)
        {
            $options = get_option('silva_settings');
          ?>
            <input type='text' name='silva_settings[<?php echo $args['name'] ?>]' value='<?php echo isset($options[$args['name']]) ? $options[$args['name']] : ''; ?>' style='width:400px;'>
        	
			<?php
			if(isset($args['hint'])) {
			 echo '<br/>';
			 echo '<i>'.$args['hint'].'</i>';
			}
        }

		/* Render the select containing WooCommmerce Order Statusses */
        function silvasoft_render_select1($args)
        {
            $options = get_option('silva_settings');
			
			global $woocommerce;
			$statussen = wc_get_order_statuses();
			
            ?>
            <select name='silva_settings[<?php echo $args['name']?>]'>                
			<?php 
                foreach($statussen as $status => $label) {
                    ?>
                     <option value='<?php echo $status ?>'
                    <?php if(isset($options[$args['name']])) {
                         selected($options[$args['name']], $status);
                            }; ?>
                        >
                        <?php echo $label; ?>
                        </option>
                    
                    <?php
                };							
                ?>                 
            </select>
            <?php
			if(isset($args['hint'])) {
			 echo '<br/>';
			 echo '<i>'.$args['hint'].'</i>';
			}
        }
		
		/* Render the select for which endpoint to use */
		function silvasoft_render_select2($args)
        {
            $options = get_option('silva_settings');
			
		    ?>
            <select name='silva_settings[<?php echo $args['name']?>]'>                
               <option value='addsalesinvoice'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'addsalesinvoice');
                        }; ?>
                    >
                    Aanmaken als verkoopfactuur (module 'Facturatie')
                    </option>
                    
                <option value='addsalestransaction'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'addsalestransaction');
                        }; ?>
                    >
                    Aanmaken als verkoopboeking (module 'Boekhouding')
                    </option>
                    
               <option value='addorder'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'addorder');
                        }; ?>
                    >
                    Aanmaken als verkooporder (module 'Offertes &amp; orders')
                    </option>
                             
            </select>
            <?php
			if(isset($args['hint'])) {
			 echo '<br/>';
			 echo '<i>'.$args['hint'].'</i>';
			}
        }
		
		/* Render the select for which relation type to use */
		function silvasoft_render_select2a($args)
        {
            $options = get_option('silva_settings');
			
		    ?>
            <select name='silva_settings[<?php echo $args['name']?>]'>                
               <option value='auto'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'auto');
                        }; ?>
                    >
                    Automatisch bepalen
                    </option>
                    
                <option value='business'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'business');
                        }; ?>
                    >
                    Altijd als bedrijf aanmaken
                    </option>
                    
               <option value='private'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'private');
                        }; ?>
                    >
                    Altijd als particulier aanmaken
                    </option>
                             
            </select>
            <?php
			if(isset($args['hint'])) {
			 echo '<br/>';
			 echo '<i>'.$args['hint'].'</i>';
			}
        }
		
		
		/* Render the select for which date to use */
		function silvasoft_render_select3($args)
        {
            $options = get_option('silva_settings');
			
		    ?>
            <select name='silva_settings[<?php echo $args['name']?>]'>                
               <option value='orderdate'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'orderdate');
                        }; ?>
                    >
                    Datum van bestelling (orderdatum)
                    </option>
                    
                <option value='currentdate'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'currentdate');
                        }; ?>
                    >
                    Datum van versturen naar Silvasoft
                    </option>
                    
             
                             
            </select>
            <?php
			if(isset($args['hint'])) {
			 echo '<br/>';
			 echo '<i>'.$args['hint'].'</i>';
			}
        }

		/* Render the select for which date to use */
		function silvasoft_render_select4($args)
        {
            $options = get_option('silva_settings');
			
		    ?>
            <select name='silva_settings[<?php echo $args['name']?>]'>                
               <option value='direct'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'direct');
                        }; ?>
                    >
                    Direct na statuswijziging van bestelling
                    </option>
                    
                <option value='cron'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'cron');
                        }; ?>
                    >
                    In de achtergrond elke 10 minuten (CRON)
                    </option>
                    
             	<option value='manual'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'manual');
                        }; ?>
                    >
                    Enkel handmatig (vanuit het orderoverzicht)
                    </option>
                             
            </select>
            <?php
			if(isset($args['hint'])) {
			 echo '<br/>';
			 echo '<i>'.$args['hint'].'</i>';
			}
        }
		
		/* Render the select for yes no */
		function silvasoft_render_select_yesno($args)
        {
            $options = get_option('silva_settings');
			
		    ?>
            <select name='silva_settings[<?php echo $args['name']?>]'>                
               <option value='no'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'no');
                        }; ?>
                    >
                   Nee
                    </option>
                    
               <option value='yes'
                <?php if(isset($options[$args['name']])) {
                     selected($options[$args['name']], 'yes');
                        }; ?>
                    >
                    Ja
                    </option>
                    
                
                             
            </select>
            <?php
			if(isset($args['hint'])) {
			 echo '<br/>';
			 echo '<i>'.$args['hint'].'</i>';
			}
        }

		//validation
        function silva_settings_callback()
        {
            echo __('Fill in the required fields', 'wordpress');
        }
		
		/* Call API using CURL */
		function CallAPI($method, $endpoint, $data = false)
		{
			$options = get_option('silva_settings');
			$url = ( isset($options['silva_api_url']) ? $options['silva_api_url'] : '' ) . $endpoint;
			$apikey = isset($options['silva_api_token']) ? $options['silva_api_token'] : '';
			$user =  isset($options['silva_username']) ? $options['silva_username'] : '';
			
			//initiate and build url
			$curl = curl_init();
			$data_string = json_encode($data);

			//setup curl postdata
			switch ($method)
			{
				case "POST":
					curl_setopt($curl, CURLOPT_POST, 1);	
					if ($data)
						curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
					break;
				case "PUT":
					curl_setopt($curl, CURLOPT_PUT, 1);
					if ($data)
						curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
					break;
				default:
					if ($data)
						$url = sprintf("%s?%s", $url, http_build_query($data));

			}
			//set headers
			if($method == 'POST' || $method == 'PUT') {
				curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
					'ApiKey: '.$apikey,
					'Username: '.$user,
					'Content-Type: application/json',                                                                                
					'Content-Length: ' . strlen($data_string))                                                                       
				);    
			} else {
				//GET
				curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
					'ApiKey: '.$apikey,
					'Username: '.$user)                                                                      
				);
			}

			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($curl, CURLOPT_FAILONERROR, true);

			$response = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);


			if($httpcode === 0 || $httpcode === 404) {
				$response = '{"errorCurl": " '.$httpcode. ' - vermoedelijk is uw API url verkeerd ingesteld onder instellingen. Voorbeeld van een correcte API url is: https://rest-api.silvasoft.nl/rest/"}';
			}

			//build and return response
			$result = array('httpcode' => $httpcode, 'response' => $response);	
			curl_close($curl);	
			return $result;

		}
		
		/* VALIDATE response > DUPLICATE in class-silvasoft-api-connector.php */
		function validateResult($result, $expectedHttpCode, $mayNotBeEmpty) {
		/* Validation 1 - JSON response error */
		$resultresponse = $result['response'];
		$resultcode = $result['httpcode'];
		$resultPHP = json_decode($resultresponse);
		if(isset($resultPHP->errorCode)) {
			$error = $resultPHP->errorCode . ' - '. $resultPHP->errorMessage; 
			return array('ok'=>false,'msg'=>$error);	
		}
		
		/* Validation 2 - Invalid HTTP response code */		
		if($resultcode !== $expectedHttpCode) {
			$error = 'HTTP code voldoet niet aan de verwachtingen. Teruggekregen code is: ' . $resultcode . '. ';
			
			if(isset($resultPHP->errorCurl)){
				$error .= " Error: " . $resultPHP->errorCurl;
			}
			
			return array('ok'=>false,'msg'=>$error);	
		}
		
		//check curl errors
		if(isset($resultPHP->errorCurl)){
			$error .= "CURL error: " . $resultPHP->errorCurl;
			return array('ok'=>false,'msg'=>$error);	
		}
		
		/* Validation 3 - Empty result */
		if($mayNotBeEmpty) {
			if(empty($resultPHP)) {
				$error = 'Het antwoord van Silvasoft voldoet niet aan de verwachting. Reden: leeg antwoord ontvangen. ' . $resultcode; 
				return array('ok'=>false,'msg'=>$error);
			}
		}
		
		return array('ok'=>true,'msg'=>'');	
		
	}
		
      	//render the actual page
        function silva_options_page()
        {
            ?>
            <form action='options.php' method='post'>

                <h2>Silvasoft - WooCommerce connector</h2>
				
                <?php
                if (empty($_SERVER['HTTPS'])) {
					
					echo ' <div class="error notice"><p>
						LET OP! Het lijkt erop dat uw website geen gebruik maakt van SSL (HTTPS). Dit is onveilig! 
						We raden aan uw website te beveiligen met SSL om gevoelige gegevens te beschermen. 
					</p></div>';
				}
				?>
                
                <?php
				echo ' <div class="updated notice"><p>
						Wilt u oude orders in bulk versturen naar Silvasoft? Dat kan vanuit het WooCommerce order overzicht door de orders te selecteren en vervolgens via de keuze \'Acties\' te kiezen voor "Verstuur orders naar Silvasoft (Bulk)". Let op dat u hiervoor een groot API plan nodig heeft bij Silvasoft. Met het gratis plan kunnen ongeveer 3 orders per uur verstuurd worden. 
						</p></div>';
			
				$maxexectime = ini_get('max_execution_time');
				if(!is_null($maxexectime)) {
					if($maxexectime < 500) {
						echo ' <div class="notice-warning notice fade"><p>
						LET OP! Wilt u grote hoeveelheden orders tegelijk naar Silvasoft versturen vanuit het order overzicht? Verhoog dan op uw server de instelling voor \'max_execution_time\'. Uw huidige instelling hiervoor ('.$maxexectime.' seconden) is te kort om grote hoeveelheden orders in 1 keer te versturen naar Silvasoft. 
						</p></div>';
					}
				}
			
				?>
                <?php
                settings_errors();
                settings_fields('silva_pluginPage');
                do_settings_sections('silva_pluginPage');
                submit_button();
                ?>

            </form>
            <?php
        }
    }
endif;
?>