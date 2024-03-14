<?php 
/** 
 * Singleton 
 * Present the company to prospective clients and allow them to create an account with us 
 */ 
class ShiptimizeConnector {
    /** 
     * ShiptimizeConnector $me 
     */ 
    private static $me; 

    /** 
     * @param int userid - the user to connect does not always match the currently logged in user
     */ 
    private function __construct($userid='') {

        $this->userid = $userid ? $userid : wp_get_current_user()->ID;
        $this->connected = get_user_meta( $this->userid , 'shiptimize_marketplace_installed', true) == 'YES'; 
        $this->shiptimize  = WooShiptimize::instance();  
    }

    public static function getInstance($userid=''){
        if(!self::$me){
            self::$me = new ShiptimizeConnector($userid); 
        }

        return self::$me;
    }

     /** 
     * User has requested an account or already has one. 
     */ 
    public function connect_user() {   
        update_user_meta($this->userid,'shiptimize_marketplace_installed','YES');
        if($this->shiptimize->is_dev) {
            error_log("connected user $this->userid to marketplace ");
        }
    } 

    public function disconnect_user($urlto = '') {         
        update_user_meta($this->userid,'shiptimize_marketplace_installed','NO');
        update_user_meta($this->userid,'shiptimize_private_key','');
        update_user_meta($this->userid,'shiptimize_public_key','');

        if($this->shiptimize->is_dev) {
            error_log("disconnected user $this->userid from marketplace. $urlto");
        }

        wp_redirect($urlto);
        die();
    }

    /** 
     * Marketplace pays shiptimize, we can connect the user account to the marketplace account 
     * Client
     *  Address : {
     *   AddressType* : 1 - main address 
     *   City*: 
     *   CompanyName: 
     *   Country*: 
     *   Email*:
     *   HouseNumber: * 
     *   Name: * 
     *   Neighborhood: 
     *   NumberExtension: 
     *   Phone*: 
     *   PostalCode*: 
     *   State: 
     *   Streetname1*: 
     *   Streetname2: 
     *   Timezone: Continent/City 
     *  },
     *  Contact {
     *   Email*: 
     *   Name*:
     *   Phone*:  
     *  }
     * User {
     *  Email: 
     *  LoginName*:
     *  Name:
     *  Password*: 10-100 chars  
     * }
     * @return boolean if the user was successfully connected 
     */ 
    public function connect_to_master($clientdata){ 
        error_log("connect_to_master ". var_export($clientdata, true)); 
        $api = WooShiptimize::get_api(); 
        $resp = $api->create_client($clientdata);  
        if ( empty($resp->response->ErrorList) && (isset($resp->response->Client) && empty($resp->response->Client[0]->ErrorList) ) ) {
          error_log("connected user $this->userid to Shiptimize");
          //$this->connect_user(); 
        }
        else if(isset($resp->response->Client) && isset($resp->response->Client[0]->ErrorList)) {
          $email = $clientdata->Client[0]['User']['Email']; 
 
          foreach ($resp->response->Client[0]->ErrorList as $error) { 
            if ($error->Id == 11) { 
              $clients = $this->get_clients(); 
              error_log(json_encode( $clients->response )); 
              if(!empty($clients->response)) {
                foreach($clients->response as $client) { 
                  echo var_export($client);
                }
              }
            }
          }
        }
        return $resp;  
    }

    /** 
     * When there is a repeat client request association the api rejects 
     * the request but does not return a list of clients. 
     */ 
    public function get_clients () {
      error_log("\n\n\n Get Clients ");
      $api = WooShiptimize::get_api(); 
      $clients = $api->get_clients(); 

      return $clients; 
    }

    /** 
     * If there is a master account then connecting the user should be done automatically 
     * @param $options  array(
        'item_wrapper' => , 
        'item_wrapper_class' => '',
        'label_item' => '', 
        'label_class' => '',
        'input_class' => '',
        'select_class' => '', 
        'errors' => array({'Id', 'Info'}), 
        ''
      )
     */ 
    public function master_options_section($options){
      echo "<div class='shiptimize-marketplace-settings'>"; 
      ?>
            <h1><?php echo $this->shiptimize->translate('shiptimizesettings'); ?></h1>

        
      <?php
      echo "</div>";
    }

    /** 
     * Defines basic options for a vendor in a marketplace connected to Shiptimize 
     * @param $options  array( 
        'masteraccount' => bool indicating if this marketplace is a master account 
        'item_wrapper' => , 
        'item_wrapper_class' => '',
        'label_item' => '', 
        'label_class' => '',
        'input_class' => '',
        'select_class' => '',
        'private_key' => '',
        'public_key'=> '',
        'token_expires' => '',
        'automatic_export' => boolean if this marketplace allows for automatic import,
        'auto_export_status' =>'',
        'username' => '',
        'password' => ''
      )
     */
    public function options_section($options){
      global $wp; 

        $this->shiptimize = WooShiptimize::instance(); 
        $site_url = get_site_url(null);  
        // default wp methods do not return the correct url for sites installed in sub directories :S 
        $protocol =  isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http'; 
        $current_url = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 

        echo "<div class='shiptimize-marketplace-settings'>";  
        ?>

        <?php     
        if(!$this->connected) {
            $connect_url = $options['masteraccount'] ? "href='#!' onclick='jQuery.get(\"" . admin_url('admin-ajax.php').'?&action=shiptimizeconnectuser&userid='.$this->userid . "\"); document.location.reload();'" : "href='$site_url/?shiptimize_connect=1&urlto=". urlencode($current_url) . "&userid=" . $this->userid . "'";
            echo "<a class='shiptimize-connect' $connect_url>" . WooShiptimize::instance()->translate('connect2shiptimize') . "</a>"; 
        }
        else {
?>            
            <h1><?php echo $this->shiptimize->translate('shiptimizesettings'); ?></h1>

            <?php if(isset($options['username']) && $options['username']): ?> 
              <<?php echo $options['item_wrapper']?>>
                  <<?php echo $options['label_item']?> class="<?php echo $options['label_class']?>">Username:</<?php echo $options['label_item']?>>
                   <?php echo $options['username']?> 
              </<?php echo $options['item_wrapper']?>> 
            <?php endif; ?> 

            <?php if(isset($options['password']) && $options['password']): ?> 
              <<?php echo $options['item_wrapper']?>>
                  <<?php echo $options['label_item']?> class="<?php echo $options['label_class']?>">Password:</<?php echo $options['label_item']?>>
                   <?php echo $options['password']?> 
              </<?php echo $options['item_wrapper']?>> 
            <?php endif; ?>
                <input type="hidden" value="<?php echo $protocol . '://' .$_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI'] ?>" />

                <<?php echo $options['item_wrapper']?> class="<?php echo $options['item_wrapper_class']?>">
                    <<?php echo $options['label_item']?> class="<?php echo $options['label_class']?>"><?php echo $this->shiptimize->translate('Public Key')?></<?php echo $options['label_item']?>>
                    <input type="text" value="<?php echo $options['public_key']?>" name='shiptimize_public_key' class="<?php echo $options['input_class']?>"/>
                </<?php echo $options['item_wrapper']?>>
                <<?php echo $options['item_wrapper']?>>
                    <<?php echo $options['label_item']?> class="<?php echo $options['label_class']?>"><?php echo $this->shiptimize->translate('Private Key')?></<?php echo $options['label_item']?>>
                    <input type="text" value="<?php echo $options['private_key']?>" name='shiptimize_private_key' class="<?php echo $options['input_class']?>" />
                </<?php echo $options['item_wrapper']?>> 
                <?php if($options['token_expires']): ?>
                    <<?php echo $options['item_wrapper']?>>
                        <<?php echo $options['label_item']?> class="<?php echo $options['label_class']?>">
                          Token <?php echo $this->shiptimize->translate('expires at') ?>
                        </<?php echo $options['label_item']?>> 
                        <?php echo $options['token_expires']; ?>
                    </<?php echo $options['item_wrapper']?>>
                <?php endif; ?>
                <?php if ($options['automatic_export']): ?>  

                  <<?php echo $options['item_wrapper']?>>
                    <<?php echo $options['label_item']?> class="<?php echo $options['label_class']?>">
                      <strong><?php echo $this->shiptimize->__('automaticexport')?></strong>
                      <br/><?php  echo $this->shiptimize->__('automaticexportdescription'); ?>
                    </<?php echo $options['label_item']?>>

                    <select name="shiptimize_autoexport" class="<?php echo $options['select_class']?>">
                      <option value="">-</option>
                      <?php   
                      $woo_statuses = wc_get_order_statuses(); 
                    
                      foreach ( $woo_statuses as $status_key => $status_label) {
                        $selected = $options['auto_export_status']  == $status_key ? 'selected' :''; 
                        echo '<option value="' . $status_key . '" ' . $selected . '>' . $status_label . '</option>';
                      }
                      ?> 
                    </select>
                  </<?php echo $options['item_wrapper']?>> 
              <?php endif; ?> 

            <?php echo "<a class='shiptimize-connect' href='$site_url/?shiptimize_disconnect=1&urlto=". urlencode($current_url) . "&userid=" . $this->userid . "'>" . WooShiptimize::instance()->translate('disconnectshiptimize') . "</a>";  ?>

            <?php if(!empty($options['errors'])): ?>  
              <div class='shiptimize-errors'>
                <h3><?php echo $this->shiptimize->translate('errors'); ?></h3>
                <?php foreach($options['errors'] as $error): ?>
                  <<?php echo $options['item_wrapper']?> class="<?php echo $options['item_wrapper_class']?>">
                    <?php 
                        if(isset($error->Info)) { 
                          echo  $error->Info; 
                        } 
                        else if(isset($error->Tekst )) { 
                          echo $error->Tekst; 
                        }
                        else  {
                          echo var_export($error,true); 
                        }
                    ?> 
                  </<?php echo $options['item_wrapper']?>>
                <?php endforeach; ?>
              </div>
            <?php endif; ?> 
<?php 
        } 
        echo "<br/><br/><hr/></div>"; // ! close the  settings div
    }

    public  function account_page() {
    ?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500&amp;display=swap" type="text/css">
    <link rel='stylesheet' href='<?php echo SHIPTIMIZE_PLUGIN_URL.'assets/css/connect.css'?>'/>
</head>
<body>
<div class="steps">
    <div  class="step selected" id='step0'>
      <div class="step__content">
        <h1><?php echo $this->shiptimize->translate('welcometitle') ?></h1>
        <h2><?php echo $this->shiptimize->translate('welcomedescription')?></h2>
        <img src='<?php echo SHIPTIMIZE_PLUGIN_URL.'assets/images/connect/'?>mainbox.svg'/>
        <button class="btn btn-large" onclick="shopplugin.installloadstep(1)"><?php echo $this->shiptimize->translate('start')?></button>
        <div> 
            <a href='#!' onclick="shopplugin.installloadstep(4)"><?php echo $this->shiptimize->translate('welcomeskip')?></a> 
        </div>
      </div>
    </div>
    <div  class="step" id='step1'>
      <div class="step__content">
        <h1><?php echo $this->shiptimize->translate('step1title')?></h1>
        <h2><?php echo $this->shiptimize->translate('step1description')?></h2>
        <div class='features'>
          <span class="feature">
            <div class="feature__img">
              <img src="<?php echo SHIPTIMIZE_PLUGIN_URL.'assets/images/connect/'?>automation.svg"/>
            </div>
            <h3><?php echo $this->shiptimize->translate('feature1title')?></h3>
            <span  class="feature__description">
              <?php echo $this->shiptimize->translate('feature1description')?>
            </span>
          </span>
          <span class="feature">
            <div class="feature__img">
              <img src="<?php echo SHIPTIMIZE_PLUGIN_URL.'assets/images/connect/'?>aggregation.svg"/>
            </div>
            <h3><?php echo $this->shiptimize->translate('feature2title')?></h3>
            <span  class="feature__description">
              <?php echo $this->shiptimize->translate('feature2description')?>
            </span>
          </span>
          <span class="feature">
            <div class="feature__img">
              <img src="<?php echo SHIPTIMIZE_PLUGIN_URL.'assets/images/connect/'?>assistance.svg"/>
            </div>
            <h3><?php echo $this->shiptimize->translate('feature3title')?></h3>
            <span  class="feature__description">
              <?php echo $this->shiptimize->translate('feature3description')?>
            </span>
          </span>
        </div>
        <button class="btn btn-secondary" onclick="shopplugin.installloadstep(0)"><?php echo $this->shiptimize->translate('stepback')?></button>
        <button class='btn' onclick="shopplugin.installloadstep(2)"><?php echo $this->shiptimize->translate('continue')?></button> 
      </div>
    </div>
    <div  class="step" id='step2'>
      <div class="step__content">
        <h1><?php echo $this->shiptimize->translate('step2title')?></h1>
        <h2><?php echo $this->shiptimize->translate('step2description')?></h2>

        <form onsubmit="return false;" class="quote" id="quote">
          <div class="form-item">
            <label></label>
            <input type='text' name="shipments" placeholder="<?php echo $this->shiptimize->translate('averageshipments')?>"/>
          </div>
          <div class="form-item">
            <label></label>
            <input type='text' name="companyName" autocomplete="company" placeholder="<?php echo $this->shiptimize->translate('companyname')?>"/>
          </div>
          <div class="form-item">
            <label></label>
            <input type='text' name="name"  autocomplete="name" placeholder="<?php echo $this->shiptimize->translate('contactperson')?>"   />
          </div>
          <div class="form-item">
            <label></label>
            <input type='text' autocomplete="email" name="email"  placeholder="<?php echo $this->shiptimize->translate('contactemail')?>" />
          </div>
          <div class="form-item">
            <label></label>
            <input type='text' name="phone"  placeholder="<?php echo $this->shiptimize->translate('contactphone')?>" />
          </div>
          <div class="form-item">
            <label></label>
            <input type='text' name="originCountry" autocomplete="nothing" placeholder="<?php echo $this->shiptimize->translate('origincountry')?>"/>
          </div>
          <div class="form-item">
            <label></label>
            <input type='text'  name="contriesship"  autocomplete="nothing" placeholder="<?php echo $this->shiptimize->translate('contriesship')?>"/>
          </div>
          <div id="formErrors" class='error'></div>
          <input type="hidden" name="marketplace" value="WCFM"> 
          <input type="hidden" name='urlto' id='urlto' value="<?php echo $_GET['urlto']?>"/>
        </form>
        <button class="btn btn-secondary" onclick="shopplugin.installloadstep(1)"><?php echo $this->shiptimize->translate('stepback')?></button>
        <button class='btn' onclick="shopplugin.requestaccount('$this->{modulename}')"><?php echo $this->shiptimize->translate('continue')?></button>  
      </div>
    </div>
    <div  class="step" id='step3'>
      <div class="step__content">
        <h1><?php echo $this->shiptimize->translate('step3title')?></h1>
        <h2><?php echo $this->shiptimize->translate('step3description')?></h2>
        <img src='<?php echo SHIPTIMIZE_PLUGIN_URL.'assets/images/connect/'?>emailonhisway.svg'/>
        <a class="btn" href="<?php echo $_GET['urlto']?>"><?php echo $this->shiptimize->translate('finishsetup');?></a>
      </div>
    </div>  
    <div class="step" id="step4">
        <!-- Go To settings !  --->
    </div> 
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> 
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
  <script src='<?php echo SHIPTIMIZE_PLUGIN_URL.'assets/js/connect.js'?>'></script>
  <script> 
    var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>"; 
    var requestaccounturl = '<?php echo get_site_url()?>?shiptimize_create_account=1';
       var countries = [{"nl":"afganistan","en":"afghanistan","iso2":"af","iso3":"afg"},{"en":"albania","nl":"albanie","iso2":"al","iso3":"alb"},{"iso2":"dz","en":"algeria","nl":"algerie","iso3":"dza"},{"iso3":"asm","iso2":"as","en":"american samoa","nl":"amerikaans samoa"},{"iso3":"and","iso2":"ad","nl":"andorra","en":"andorra"},{"iso3":"ago","nl":"angola","en":"angola","iso2":"ao"},{"iso3":"aia","nl":"anguilla","en":"anguilla","iso2":"ai"},{"iso2":"ar","en":"argentina","nl":"argentinie","iso3":"arg"},{"iso2":"am","nl":"armenie","en":"armenia","iso3":"arm"},{"nl":"aruba","en":"aruba","iso2":"aw","iso3":"abw"},{"nl":"ascension","en":"ascension","iso2":"sh","iso3":"shn"},{"iso2":"au","en":"australia","nl":"australie","iso3":"aus"},{"iso3":"aut","iso2":"at","nl":"oostenrijk","en":"austria"},{"iso2":"az","nl":"azerbaijan","en":"azerbaijan","iso3":"aze"},{"iso2":"bs","en":"bahamas","nl":"bahama","iso3":"bhs"},{"iso2":"bh","en":"bahrain","nl":"bahrain","iso3":"bhr"},{"en":"bangladesh","nl":"bangladesh","iso2":"bd","iso3":"bgd"},{"iso3":"brb","nl":"barbados","en":"barbados","iso2":"bb"},{"iso2":"ag","nl":"barbuda","en":"barbuda","iso3":"atg"},{"iso2":"by","en":"belarus","nl":"belarus","iso3":"blr"},{"iso3":"bel","en":"belgium","nl":"belgie","iso2":"be"},{"iso2":"bz","nl":"belize","en":"belize","iso3":"blz"},{"nl":"benin","en":"benin","iso2":"bj","iso3":"ben"},{"nl":"bermuda","en":"bermuda","iso2":"bm","iso3":"bmu"},{"iso3":"btn","nl":"bhutan","en":"bhutan","iso2":"bt"},{"nl":"bolivie","en":"bolivia","iso2":"bo","iso3":"bol"},{"iso3":"bih","en":"bosnia and herzegovina","nl":"bosnia en herzegovina","iso2":"ba"},{"iso3":"bwa","en":"botswana","nl":"botswana","iso2":"bw"},{"iso3":"bra","iso2":"br","en":"brazil","nl":"brazilie"},{"iso3":"iot","iso2":"io","en":"british indian ocean territory","nl":"british indian ocean territory"},{"iso3":"vgb","iso2":"vg","en":"british virgin islands","nl":"british virgin islands"},{"iso3":"brn","iso2":"bn","en":"brunei","nl":"brunei"},{"nl":"bulgarije","en":"bulgaria","iso2":"bg","iso3":"bgr"},{"iso3":"bfa","en":"burkina faso","nl":"burkina faso","iso2":"bf"},{"iso2":"bi","nl":"burundi","en":"burundi","iso3":"bdi"},{"iso2":"kh","nl":"cambodja","en":"cambodia","iso3":"khm"},{"iso3":"cmr","iso2":"cm","nl":"cameroon","en":"cameroon"},{"en":"canada","nl":"canada","iso2":"ca","iso3":"can"},{"iso3":"cpv","iso2":"cv","nl":"cape verde","en":"cape verde"},{"iso3":"cym","iso2":"ky","nl":"cayman elianden","en":"cayman islands"},{"nl":"central african republic","en":"central african republic","iso2":"cf","iso3":"rca"},{"nl":"chad","en":"chad","iso2":"td","iso3":"tcd"},{"en":"chile","nl":"chili","iso2":"cl","iso3":"chl"},{"iso2":"cn","en":"china","nl":"china","iso3":"chn"},{"nl":"christmas island","en":"christmas island","iso2":"cx","iso3":"cxr"},{"en":"cocos-keeling islands","nl":"cocos-keeling islands","iso2":"cc","iso3":"cck"},{"iso2":"co","en":"colombia","nl":"colombie","iso3":"col"},{"iso2":"km","nl":"comoros","en":"comoros","iso3":"com"},{"iso2":"cd","en":"congo","nl":"congo","iso3":"rcb"},{"iso3":"rcb","nl":"congo - brazzaville","en":"congo - brazzaville","iso2":"cd"},{"en":"congo - kinshasa","nl":"congo - kinshasa","iso2":"cd","iso3":"rcb"},{"iso3":"cok","iso2":"ck","en":"cook islands","nl":"cook islands"},{"en":"costa rica","nl":"costa rica","iso2":"cr","iso3":"cri"},{"nl":"kroatie","en":"croatia","iso2":"hr","iso3":"hrv"},{"en":"cuba","nl":"cuba","iso2":"cu","iso3":"cub"},{"iso3":"cuw","en":"curacao","nl":"curacao","iso2":"cw"},{"iso2":"cy","nl":"cyprus","en":"cyprus","iso3":"cyp"},{"iso2":"cz","en":"czech republic","nl":"tsjechie","iso3":"cze"},{"nl":"denemarken","en":"denmark","iso2":"dk","iso3":"dnk"},{"iso3":"dji","iso2":"dj","en":"djibouti","nl":"djibouti"},{"iso2":"dm","nl":"dominica","en":"dominica","iso3":"dma"},{"iso3":"dom","iso2":"do","en":"dominican republic","nl":"dominikaanse republiek"},{"nl":"oost timor","en":"east timor","iso2":"tl","iso3":"tmp"},{"nl":"equador","en":"ecuador","iso2":"ec","iso3":"ecu"},{"iso2":"eg","nl":"egypte","en":"egypt","iso3":"egy"},{"iso2":"sv","en":"el salvador","nl":"el salvador","iso3":"slv"},{"iso3":"gnq","nl":"equatorial guinea","en":"equatorial guinea","iso2":"gq"},{"en":"eritrea","nl":"eritrea","iso2":"er","iso3":"eri"},{"iso3":"est","nl":"estland","en":"estonia","iso2":"ee"},{"iso2":"et","en":"ethiopia","nl":"ethiopie","iso3":"eth"},{"iso3":"flk","iso2":"fk","nl":"falkland eilanden","en":"falkland islands"},{"iso3":"fro","iso2":"fo","en":"faroe islands","nl":"faroe eiland"},{"iso2":"fj","en":"fiji","nl":"fiji","iso3":"fji"},{"iso2":"fi","en":"finland","nl":"finland","iso3":"fin"},{"nl":"frankrijk","en":"france","iso2":"fr","iso3":"fra"},{"nl":"frans guiana","en":"french guiana","iso2":"gf","iso3":"guf"},{"iso2":"pf","en":"french polynesia","nl":"frans polynesie","iso3":"pyf"},{"iso3":"gab","en":"gabon","nl":"gabon","iso2":"ga"},{"iso2":"gm","en":"gambia","nl":"gambie","iso3":"gmb"},{"iso3":"geo","nl":"georgie","en":"georgia","iso2":"ge"},{"nl":"duitsland","en":"germany","iso2":"de","iso3":"deu"},{"iso3":"gha","en":"ghana","nl":"ghana","iso2":"gh"},{"iso3":"gib","nl":"gibraltar","en":"gibraltar","iso2":"gi"},{"iso3":"grc","en":"greece","nl":"griekenland","iso2":"gr"},{"iso3":"grl","en":"greenland","nl":"groenland","iso2":"gl"},{"iso3":"grd","iso2":"gd","nl":"grenada","en":"grenada"},{"iso2":"gp","en":"guadeloupe","nl":"guadeloupe","iso3":"glp"},{"iso3":"gum","en":"guam","nl":"guam","iso2":"gu"},{"iso3":"gtm","iso2":"gt","nl":"guatemala","en":"guatemala"},{"iso3":"gin","iso2":"gn","en":"guinea","nl":"guinea"},{"iso2":"gw","en":"guinea-bissau","nl":"guinea-bissau","iso3":"gnb"},{"iso3":"guy","iso2":"gy","nl":"guyana","en":"guyana"},{"iso2":"ht","en":"haiti","nl":"haiti","iso3":"hti"},{"en":"honduras","nl":"honduras","iso2":"hn","iso3":"hnd"},{"en":"hong kong","nl":"hong kong","iso2":"hk","iso3":"hkg"},{"iso3":"hun","nl":"hongarije","en":"hungary","iso2":"hu"},{"nl":"ijsland","en":"iceland","iso2":"is","iso3":"isl"},{"en":"india","nl":"indie","iso2":"in","iso3":"ind"},{"iso3":"idn","nl":"indonesie","en":"indonesia","iso2":"id"},{"iso2":"ir","nl":"iran","en":"iran","iso3":"irn"},{"iso3":"irq","en":"iraq","nl":"iraq","iso2":"iq"},{"iso2":"ie","nl":"ierland","en":"ireland","iso3":"irl"},{"iso2":"il","nl":"israel","en":"israel","iso3":"isr"},{"iso2":"it","nl":"italie","en":"italy","iso3":"ita"},{"iso2":"jm","en":"jamaica","nl":"jamaica","iso3":"jam"},{"en":"japan","nl":"japan","iso2":"jp","iso3":"jpn"},{"iso3":"jor","en":"jordan","nl":"jordanie","iso2":"jo"},{"iso3":"kaz","iso2":"kz","nl":"kazakhstan","en":"kazakhstan"},{"en":"kenya","nl":"kenia","iso2":"ke","iso3":"ken"},{"iso2":"ki","en":"kiribati","nl":"kiribati","iso3":"kir"},{"iso2":"kp","en":"north korea","nl":"noord korea","iso3":"prk"},{"iso3":"kor","iso2":"kr","en":"south korea","nl":"zuid korea"},{"en":"kuwait","nl":"koeweit","iso2":"kw","iso3":"kwt"},{"iso3":"kgz","en":"kyrgyzstan","nl":"kyrgyzstan","iso2":"kg"},{"iso2":"la","en":"laos","nl":"laos","iso3":"lao"},{"iso3":"lva","nl":"letland","en":"latvia","iso2":"lv"},{"iso3":"lbn","iso2":"lb","nl":"libanon","en":"lebanon"},{"iso3":"lso","en":"lesotho","nl":"lesotho","iso2":"ls"},{"nl":"liberie","en":"liberia","iso2":"lr","iso3":"lbr"},{"iso3":"lby","en":"libya","nl":"libie","iso2":"ly"},{"iso3":"lie","nl":"liechtenstein","en":"liechtenstein","iso2":"li"},{"iso2":"lt","en":"lithuania","nl":"litouwen","iso3":"ltu"},{"en":"luxembourg","nl":"luxemburg","iso2":"lu","iso3":"lux"},{"nl":"macau","en":"macau","iso2":"mo","iso3":"mac"},{"nl":"macedonie","en":"macedonia","iso2":"mk","iso3":"mkd"},{"iso3":"mdg","iso2":"mg","en":"madagascar","nl":"madagascar"},{"iso3":"mwi","nl":"malawi","en":"malawi","iso2":"mw"},{"iso3":"mys","nl":"maleisie","en":"malaysia","iso2":"my"},{"iso3":"mdv","iso2":"mv","en":"maldives","nl":"maladiven"},{"iso2":"ml","en":"mali","nl":"mali","iso3":"mli"},{"iso3":"mlt","en":"malta","nl":"malta","iso2":"mt"},{"en":"marshall islands","nl":"marshall eilanden","iso2":"mh","iso3":"mhl"},{"iso2":"mq","en":"martinique","nl":"martinique","iso3":"mtq"},{"iso3":"mrt","iso2":"mr","nl":"mauritanie","en":"mauritania"},{"iso3":"mus","iso2":"mu","nl":"mauritius","en":"mauritius"},{"iso3":"myt","nl":"mayotte","en":"mayotte","iso2":"yt"},{"iso2":"mx","en":"mexico","nl":"mexico","iso3":"mex"},{"iso3":"fsm","iso2":"fm","en":"micronesia","nl":"micronesie"},{"iso2":"md","nl":"moldavie","en":"moldova","iso3":"mda"},{"iso2":"mc","nl":"monaco","en":"monaco","iso3":"mco"},{"iso3":"mng","iso2":"mn","en":"mongolia","nl":"mongolie"},{"en":"montenegro","nl":"montenegro","iso2":"me","iso3":"mne"},{"iso3":"msr","iso2":"ms","nl":"montserrat","en":"montserrat"},{"en":"morocco","nl":"marocco","iso2":"ma","iso3":"mar"},{"iso3":"moz","nl":"mozambique","en":"mozambique","iso2":"mz"},{"en":"myanmar","nl":"myanmar","iso2":"mm","iso3":"mmr"},{"iso2":"na","nl":"namibie","en":"namibia","iso3":"nam"},{"iso3":"nru","nl":"nauru","en":"nauru","iso2":"nr"},{"nl":"nepal","en":"nepal","iso2":"np","iso3":"npl"},{"iso3":"nld","en":"the netherlands","nl":"nederland","iso2":"nl"},{"nl":"nederlandse antillen","en":"netherlands antilles","iso2":"an","iso3":"ant"},{"iso3":"ncl","en":"new caledonia","nl":"nieuw caledonie","iso2":"nc"},{"en":"new zealand","nl":"new zealand","iso2":"nz","iso3":"nzl"},{"iso2":"ni","nl":"nicaragua","en":"nicaragua","iso3":"nic"},{"iso3":"ner","iso2":"ne","nl":"niger","en":"niger"},{"nl":"nigeria","en":"nigeria","iso2":"ng","iso3":"nga"},{"iso2":"nu","en":"niue","nl":"niue","iso3":"niu"},{"iso2":"nf","en":"norfolk island","nl":"norfolk eiland","iso3":"nfk"},{"iso3":"mnp","en":"northern mariana islands","nl":"noord mariana islands","iso2":"mp"},{"iso2":"no","nl":"noorwegen","en":"norway","iso3":"nor"},{"iso3":"omn","nl":"oman","en":"oman","iso2":"om"},{"iso2":"pk","nl":"pakistan","en":"pakistan","iso3":"pak"},{"iso2":"pw","en":"palau","nl":"palau","iso3":"plw"},{"iso2":"ps","en":"palestinian territories","nl":"palestinian territories","iso3":"pse"},{"iso2":"pa","nl":"panama","en":"panama","iso3":"pan"},{"iso2":"pg","nl":"papua new guinea","en":"papua new guinea","iso3":"png"},{"iso3":"pry","en":"paraguay","nl":"paraguay","iso2":"py"},{"iso3":"per","iso2":"pe","en":"peru","nl":"peru"},{"iso3":"phl","en":"philippines","nl":"philipijnen","iso2":"ph"},{"iso2":"pl","nl":"polen","en":"poland","iso3":"pol"},{"iso3":"prt","nl":"portugal","en":"portugal","iso2":"pt"},{"iso2":"pr","en":"puerto rico","nl":"puerto rico","iso3":"pri"},{"nl":"qatar","en":"qatar","iso2":"qa","iso3":"qat"},{"iso3":"rou","en":"romania","nl":"roemenie","iso2":"ro"},{"iso3":"rus","iso2":"ru","nl":"rusland","en":"russia"},{"en":"rwanda","nl":"rwanda","iso2":"rw","iso3":"rwa"},{"nl":"saint helena","en":"saint helena","iso2":"sh","iso3":"shn"},{"iso3":"kna","iso2":"kn","nl":"saint kitts and nevis","en":"saint kitts and nevis"},{"nl":"saint lucia","en":"saint lucia","iso2":"lc","iso3":"lca"},{"iso3":"maf","iso2":"mf","en":"saint martin","nl":"saint martin"},{"nl":"saint pierre and miquelon","en":"saint pierre and miquelon","iso2":"pm","iso3":"spm"},{"en":"saint vincent and the grenadines","nl":"saint vincent and the grenadines","iso2":"vc","iso3":"vct"},{"iso2":"ws","nl":"samoa","en":"samoa","iso3":"wsm"},{"iso3":"rsm","iso2":"sm","en":"san marino","nl":"san marino"},{"iso3":"sau","en":"saudi arabia","nl":"saudi arabia","iso2":"sa"},{"iso3":"sen","iso2":"sn","nl":"senegal","en":"senegal"},{"en":"serbia","nl":"servie","iso2":"rs","iso3":"srb"},{"iso3":"syc","iso2":"sc","nl":"seychelles","en":"seychelles"},{"en":"sierra leone","nl":"sierra leone","iso2":"sl","iso3":"wal"},{"iso2":"sg","en":"singapore","nl":"singapore","iso3":"sgp"},{"iso3":"sxm","iso2":"sx","nl":"sint maarten","en":"sint maarten"},{"iso3":"svk","iso2":"sk","en":"slovakia","nl":"slowakije"},{"iso3":"svn","iso2":"si","nl":"slovenie","en":"slovenia"},{"iso3":"slb","nl":"solomon eilanden","en":"solomon islands","iso2":"sb"},{"iso2":"so","nl":"somalie","en":"somalia","iso3":"som"},{"nl":"zuid afrika","en":"south africa","iso2":"za","iso3":"zaf"},{"en":"south georgia and the south sand","nl":"south georgia and the south sand","iso2":"gs","iso3":"sgs"},{"iso2":"es","en":"spain","nl":"spanje","iso3":"esp"},{"iso3":"lka","en":"sri lanka","nl":"sri lanka","iso2":"lk"},{"iso3":"sdn","iso2":"sd","nl":"sudan","en":"sudan"},{"iso2":"sr","en":"suriname","nl":"suriname","iso3":"sur"},{"iso3":"swz","iso2":"sz","nl":"swaziland","en":"swaziland"},{"nl":"zweden","en":"sweden","iso2":"se","iso3":"swe"},{"iso3":"che","iso2":"ch","nl":"zwitserland","en":"switzerland"},{"iso2":"sy","en":"syria","nl":"syrie","iso3":"syr"},{"iso3":"twn","iso2":"tw","nl":"taiwan","en":"taiwan"},{"iso2":"tj","en":"tajikistan","nl":"tajikistan","iso3":"tjk"},{"iso3":"tza","en":"tanzania","nl":"tanzania","iso2":"tz"},{"en":"thailand","nl":"thailand","iso2":"th","iso3":"tha"},{"iso2":"tl","nl":"timor-leste","en":"timor-leste","iso3":"tls"},{"iso3":"tgo","iso2":"tg","nl":"togo","en":"togo"},{"iso3":"tkl","en":"tokelau","nl":"tokelau","iso2":"tk"},{"iso2":"to","nl":"tonga","en":"tonga","iso3":"ton"},{"en":"trinidad and tobago","nl":"trinidad and tobago","iso2":"tt","iso3":"tto"},{"iso2":"tn","en":"tunisia","nl":"tunisie","iso3":"tun"},{"en":"turkey","nl":"turkije","iso2":"tr","iso3":"tur"},{"iso2":"tm","en":"turkmenistan","nl":"turkmenistan","iso3":"tkm"},{"iso3":"tca","iso2":"tc","nl":"turks and caicos islands","en":"turks and caicos islands"},{"iso3":"tuv","iso2":"tv","en":"tuvalu","nl":"tuvalu"},{"nl":"uganda","en":"uganda","iso2":"ug","iso3":"uga"},{"iso3":"ukr","iso2":"ua","nl":"ukraine","en":"ukraine"},{"iso3":"are","nl":"united arab emirates","en":"united arab emirates","iso2":"ae"},{"iso2":"gb","en":"united kingdom","nl":"united kingdom","iso3":"gbr"},{"nl":"verenigde staten van amerika","en":"united states","iso2":"us","iso3":"usa"},{"iso3":"ury","nl":"uruguay","en":"uruguay","iso2":"uy"},{"iso2":"vi","en":"u.s. virgin islands","nl":"u.s. virgin islands","iso3":"vir"},{"iso3":"uzb","iso2":"uz","nl":"uzbekistan","en":"uzbekistan"},{"iso3":"vut","nl":"vanuatu","en":"vanuatu","iso2":"vu"},{"iso3":"vat","en":"vatican","nl":"vaticaan stad","iso2":"va"},{"en":"venezuela","nl":"venezuela","iso2":"ve","iso3":"ven"},{"iso2":"vn","en":"vietnam","nl":"vietnam","iso3":"vnm"},{"nl":"wallis and futuna","en":"wallis and futuna","iso2":"wf","iso3":"wlf"},{"iso3":"zwe","iso2":"zw","en":"zimbabwe","nl":"zimbabwe"},{"iso2":"re","en":"reunion","nl":"reunion","iso3":"reu"},{"iso3":"imn","en":"isle of man","nl":"isle of man","iso2":"im"},{"iso3":"bes","en":"bonaire, sint eustatius and saba","nl":"bonaire, sint eustatius and saba","iso2":"bq"},{"iso3":"ggy","en":"guernsey","nl":"guernsey","iso2":"gg"}];
    jQuery(function(){ 
        shopplugin = new ShopPlugin();

        if (window.location.search.indexOf("step=")>0) {
            var step = /step=([\d]*)/.exec(window.location.search)[1]; 
            shopplugin.installloadstep(step);
        }


    jQuery.validator.addMethod("checkCountries", function(value,  element,params){
      var input = $(element);  
      var  value = value.toLowerCase();
      var  separator = value.indexOf(",")>-1?",":" ";
      var values = value.split(separator);   
      var errors =  false; 
      countryError = "";

      for(var  j=0; j< values.length;++j){
        var  country  = values[j].trim(); 
        if(!country){continue;}

        var found = false; 
        for(var  x=0;x < countries.length && !found;  ++x){
          var c = countries[x];   
          var en = new  RegExp(c.en,"ig"); 
          var nl = new  RegExp(c.nl,"ig"); 

          if( c.iso2 == country  || c.iso3 == country || country.match(en) || country.match(nl) ){
            found = true; 
          }
        }

        if(!found){ 
          countryError +=  "<br/>cannot find country "+country;
          errors =  true; 
          console.log(countryError);
        }
      } 
      return !errors;
    },function(value,elem){
      return countryError;
    });

    

    $(function(){

      $("#quote").validate({
        debug:true,
        rules:  {  
          volumeEstimate:{
            required:true,
            number:true
          },
          companyName: {
              required:true,
           },
           contactPerson:{
              required:true,
              minlength:3
           },
          contriesship:{
            required:true,
            //checkCountries:true
          },
          email:{
            required: true,
            email:true
          },
          originCountry:{
            required:true,
            //checkCountries:true
          },
          phoneNumber:{
            required:true,
          } 
        }
      });    

      $(".quote input").keyup(function(){
        shopplugin.inputLabel($(this));
      });

      $("input[name='contriesship'],input[name='originCountry']").focus(function (){
        shopplugin.removeAllAutocomplete();
        shopplugin.addAutocomplete(this); 
      });
  
      $("input[name='contriesship'],input[name='originCountry']").keyup(function(){
          shopplugin.autoCompleteRefresh(this, $(this).attr("name") == 'contriesship' ? 1:  0); 
      });   
    });
    });
  </script>
</body>
</html>
<?php 
    die();
    }    


} 
