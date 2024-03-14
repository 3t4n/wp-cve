<?php
/**
 * Aweber for WordPress *
 * @author    Renzo Johnson (email: renzo.johnson at gmail.com)
 * @link      http://renzojohnson.com/
 * @copyright 2017 Renzo Johnson (email: renzo.johnson at gmail.com) *
 * @package Aweber
 */


/**
 * Function Comment *
 */
function wpcf7_awb_admin_ajax_scripts() {

  wp_enqueue_script( 'aweberx-wp-ajax', SPARTAN_AWB_PLUGIN_URL . '/assets/js/aweberx-wp-ajax.js', array( 'jquery' ), SPARTAN_AWB_VERSION, true );
  wp_localize_script( 'aweberx-wp-ajax', 'my_ajax_url',
  array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'admin_enqueue_scripts', 'wpcf7_awb_admin_ajax_scripts' );

add_action( 'wp_ajax_wpcf7_awb_savetool',  'wpcf7_awb_savetool' );
add_action( 'wp_ajax_no_priv_wpcf7_awb_savetool',  'wpcf7_awb_savetool' );
add_action( 'wp_ajax_wpcf7_awb_listacampos',  'wpcf7_awb_listacampos' );
add_action( 'wp_ajax_no_priv_wpcf7_awb_listacampos',  'wpcf7_awb_listacampos' );

add_action( 'wp_ajax_wpcf7_awb_activalista',  'wpcf7_awb_activalista' );
add_action( 'wp_ajax_no_priv_wpcf7_awb_activalista',  'wpcf7_awb_activalista' );

add_action( 'wpcf7_after_save', 'wpcf7_awb_save_aweber' );
add_action( 'wpcf7_before_send_mail', 'wpcf7_awb_subscribe' );
add_filter( 'wpcf7_editor_panels', 'show_awb_metabox' );
add_filter( 'wpcf7_form_class_attr', 'spartan_awb_class_attr' );


/**
 * Function Comment *
 */
function wpcf7_awb_savetool() {
  global $wpdb;

  $cf7_awb_defaults = array();
  $idformxx = 'cf7_awb_'. wp_unslash( $_POST['idformxx'] );
  $cf7_awb = get_option( $idformxx, $cf7_awb_defaults );

  $logfileEnabled = $cf7_awb['logfileEnabled'];
  $logfileEnabled = ( is_null( $logfileEnabled ) ) ? false : $logfileEnabled;

  $msgerror = '';
  $awb_tool_unpolluted = wpcf7_awb_tool_management_page( $_POST['tool_key'],1,$msgerror,$logfileEnabled );

  if ( get_option( 'wpcf7-awb-api-tool_key' ) !== false ) {
    update_option( 'wpcf7-awb-api-tool_key', $_POST['tool_key'] );
  } else {

    $deprecated = null;
    $autoload = 'no';
    add_option( 'wpcf7-awb-api-tool_key', $_POST['tool_key'], $deprecated, $autoload );
  }

  if ( get_option( 'wpcf7-awb-api-tool_unpolluted' ) !== false ) {
      update_option( 'wpcf7-awb-api-tool_unpolluted', $awb_tool_unpolluted );
  } else {
    $deprecated = null;
    $autoload = 'no';
    add_option( 'wpcf7-awb-api-tool_unpolluted', $awb_tool_unpolluted, $deprecated, $autoload );
  }

  if ( get_option( 'wpcf7-awb-api-msgerrtool_unpolluted' ) !== false ) {
      update_option( 'wpcf7-awb-api-msgerrtool_unpolluted', $msgerror );
  } else {
    $deprecated = null;
    $autoload = 'no';
    add_option( 'wpcf7-awb-api-msgerrtool_unpolluted', $msgerror, $deprecated, $autoload );
  }

  // Verificar si ya tiene algo grabado
  $numelemen = 0;

  $apivalid = ( isset( $cf7_awb['code-validation'] ) ) ? $cf7_awb['code-validation'] : 0 ;
  $tmpaccount = ( isset( $cf7_awb['account'] ) ) ? $cf7_awb['account'] : null ;

  $listacampos = ( isset( $cf7_awb['merge-vars'] ) ) ? $cf7_awb['merge-vars'] : null ;
  $listatags = ( isset( $cf7_awb['listatags'] ) ) ? $cf7_awb['listatags'] : null ;

  if ( count( $listacampos ) + 3 > count( $listatags ) ) {
    $numelemen = count( $listacampos ) + 3;
  } else {    $numelemen = count( $listatags );
  }

  $awb_valid = '<span class="awb valid"><span class="dashicons dashicons-yes"></span>Code Key</span>';
  $awb_invalid = '<span class="awb invalid"><span class="dashicons dashicons-no"></span>Error: Code Key</span>';
  $awb_tool_unpolluted = 1;

  html_panelconfig( $cf7_awb, $awb_tool_unpolluted,$apivalid,$awb_valid,$awb_invalid,$tmpaccount,$numelemen,$listacampos,$listatags,$msgerror );

  wp_die();
}

/**
 * Function Comment *
 */
function wpcf7_awb_activalista() {
  global $wpdb;
  $idformxx = 'cf7_awb_'. wp_unslash( $_POST['idformxx'] );
  //$listName = wp_unslash( $_POST['namelista'] );
  $apicodetmp = $_POST['apicode'];


  $apicodetmp = ( is_null( $apicodetmp ) ) ? 0 : $apicodetmp;

  $account = null;
  $Aweber  = null;


  $logfileEnabled = 1;
  $cf7_awb_defaults = array();
  $cf7_awb = get_option( $idformxx, $cf7_awb_defaults );

  //AkpgSpyqx8LX4YUHum3Z0GNb
  //oN23IJmMRcXEptyc0OLQPAznjJyO0QAa52wgEwpI
  //f11768b7
  //e233dabd

  $tmppost = $cf7_awb;
  $consumerKey = isset( $tmppost['consumerkey'] ) ? $tmppost['consumerkey'] : 0 ;
  $consumerSecret = isset( $tmppost['consumersecret'] ) ? $tmppost['consumersecret'] : 0 ;


  $consumerKey = ( is_null( $consumerKey ) ) ? 0 : $consumerKey;
  $consumerSecret = ( is_null( $consumerSecret ) ) ? 0 : $consumerSecret;

  $oldapicode = isset( $cf7_awb['code'] ) ? $cf7_awb['code'] : 0 ;
  $oldapicode = ( is_null( $oldapicode ) ) ? 0 : $oldapicode;
  $oldapivalid = isset( $cf7_awb['code-validation'] ) ? $cf7_awb['code-validation'] : 0 ;

  unset( $tmppost['appid'],$tmppost['consumerkey'],$tmppost['consumersecret'],$tmppost['classAweber'],
  $tmppost['account'],$tmppost['code-validation'],$tmppost['lisdata'],$tmppost['code'] );


  if ( ( $apicodetmp == $oldapicode ) && '1' == $oldapivalid ) {

      $oldAweber  = $cf7_awb['classAweber'];
      $oldaccount = $cf7_awb['account'];
      $tmp = array( 'appID-validation' => 0 );
      $tmppost = $tmppost + $tmp ;
      $tmp = array( 'code-validation' => 0, 'account' => $oldaccount, 'classAweber' => $oldAweber );
      $tmppost = $tmppost + $tmp ;
      $Aweber = $oldAweber;
      $account = $oldaccount;

  }

    $tmp = wpcf7_awb_validate_code( $Aweber,$apicodetmp,$account,$logfileEnabled,$idformxx,$consumerKey,$consumerSecret );
    $tmppost = $tmppost + $tmp ;

    $apptmp = array( 'appid' => 'f11768b7', 'consumerkey' => $consumerKey, 'consumersecret' => $consumerSecret );

    $tmp = wpcf7_awb_listasasociadas( $account,$list_data,$logfileEnabled,$idformxx ); //el tmp viene el lisdata

    $tmppost = $tmppost + array( 'code' => $apicodetmp ) + $tmp ;
    $tmppost = $tmppost + $apptmp;

    $apivalid = $tmppost['code-validation'];


    $awb_tool_unpolluted = get_option( 'wpcf7-awb-api-tool_unpolluted', 0 );

    update_option( $idformxx, $tmppost );
    $awb_tool_unpolluted = 1;



  html_panellistamail( $apivalid,$awb_tool_unpolluted,$account,$cf7_awb );
  wp_die();
}

/**
 * Function Comment *
 */
function wpcf7_awb_listacampos() {
  global $wpdb;


  $idformxx = 'cf7_awb_'. wp_unslash( $_POST['idformxx'] );
  $listName = wp_unslash( $_POST['namelista'] );


  $logfileEnabled = 1;
  $cf7_awb_defaults = array();
  $cf7_awb = get_option( $idformxx, $cf7_awb_defaults );

  $apivalid = $cf7_awb['code-validation'];
  $account = $cf7_awb['account'];

  $awb_tool_unpolluted = get_option( 'wpcf7-awb-api-tool_unpolluted', 0 );

  $listdata = $cf7_awb['lisdata'];  // Modificar


  $tmp = wpcf7_awb_merge_var( $listdata,$listName,$logfileEnabled,$idformxx );
  $listacampos = $tmp['merge-vars'];

  $listatags = $cf7_awb['listatags'];

  if ( count( $listacampos ) + 3 > count( $listatags ) ) {
    $numelemen = count( $listacampos ) + 3;
  } else {
    $numelemen = count( $listatags );
  }
  $awb_tool_unpolluted = 1;

  hmtl_mapeocampos( $numelemen,$cf7_awb,$listacampos,$listatags,$apivalid,$awb_tool_unpolluted );
  wp_die();
}

/**
 * Missing function doc comment
 */
function html_listacamposmail( $numelemen, $cf7_awb, $listacampos ) {

  for ( $i = 1 ; $i < $numelemen ; $i++ ) {
    ?>    <select class="awb-select" id="wpcf7-Aweber-CustomKey<?php echo esc_textarea( $i ); ?>"
            name="wpcf7-Aweber[CustomKey<?php echo esc_textarea( $i ); ?>]" style="width:95%">
                                <option value=" " >Choose...</option>
                <option value="email" <?php if ( 'email' == $cf7_awb[ 'CustomKey'.$i ] ) {
                        echo 'selected'; } ?>>*|EMAIL|* - Required by Aweber</option>
                <option value="name" <?php if ( 'name' == $cf7_awb[ 'CustomKey'.$i ] ) {
                        echo 'selected'; } ?>>*|NAME|* - Required by Aweber</option>
    <?php
    foreach ( $listacampos as $list ) {
      if ( trim( $list->name ) != 'EMAIL' ) {
            ?>
            <option value="<?php echo esc_textarea( $list->name ) ?>"
                <?php if ( $cf7_awb[ 'CustomKey'.$i ] == $list->name ) {
                      echo 'selected'; } ?>>
                  <?php echo esc_textarea( $list->name ).' &nbsp; or &nbsp; *|'. esc_textarea( $list->id ).'|*' ?>
                            </option>
              <?php
      }
    }
    ?>
        </select>
  <?php
  }
}

/**
 * Missing function doc comment
 */
function html_listacamposforma( $numelemen, $cf7_awb, $listatags ) {

  for ( $i = 1 ; $i < $numelemen ; $i++ ) {
    ?>
    <select class="awb-select" id="wpcf7-Aweber-CustomValue<?php echo esc_textarea( $i ); ?>"
        name="wpcf7-Aweber[CustomValue<?php echo esc_textarea( $i ); ?>]" style="width:95%">
            <option value=" "
          <?php if ( ' ' == $cf7_awb[ 'CustomValue'.$i ] ) { echo 'selected="selected"'; } ?>>
          <?php echo 'Choose.. ' ?>
            </option>
      <?php
      foreach ( $listatags as $list ) {
        if ( 'opt-in' != trim( $list['name'] ) && '' != trim( $list['name'] ) ) {
        ?>
          <option value="<?php echo esc_textarea( $list['name'] ) ?>"
              <?php if ( $cf7_awb[ 'CustomValue'.$i ] == $list['name'] ) { echo 'selected="selected"'; } ?>>
              <?php echo '&#91;'.esc_textarea( $list['name'] ).'&#93;' ?>
                    </option>
        <?php
        }
      }
    ?>
        </select>
    <?php
  }
}

/**
 * Missing function doc comment
 */


/**
 * Missing function doc comment
 */
function html_panelcodeapi( $awb_tool_unpolluted, $apivalid, $awb_valid, $awb_invalid, $cf7_awb ) {

  if ( 1 == $awb_tool_unpolluted  or 2 == $awb_tool_unpolluted  ) {

      $aweber_auth_endpoint = 'https://auth.aweber.com/1.0/oauth/authorize_app/f11768b7';
      $link = sprintf(__( '<a href="%1$s" target="_blank">Generate authorization code</a><br/>', 'AWeBer' ),
      esc_url( $aweber_auth_endpoint ) );
      echo $link;

    ?>

    <p class="mail-field">
      <label for="wpcf7-Aweber-code" class="dbl"><?php echo esc_textarea( __( 'Aweber Authorization Code:', 'wpcf7' ) ); ?></label>
      <input type="text" id="wpcf7-Aweber-code" name="wpcf7-Aweber[code]" class="wide" size="70" placeholder="" value="<?php echo ( isset( $cf7_awb['code'] ) ) ? esc_textarea( $cf7_awb['code'] ) : ''; ?>"/> <span><input id="activalist" type="button" value="Authorize and fetch your mailing lists" class="button button-primary button-large" /><span class="spinner"></span></span>
      <small class="description dbl">Input your Aweber Authorization Code and hit 'Authorize and fetch your mailing lists'. <a href="//renzojohnson.com/contributions/contact-form-7-aweber-extension/aweber-authorization-code?utm_source=AWeber&amp;utm_campaign=w4.7.3c4.7en_US&amp;utm_medium=cme-0.4.37&amp;utm_term=F1C1P5.4.45S5.5.51" class="helping-field" target="_blank" title="get help with AWeber:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
    </p>




  <?php
  } // end if


}

/**
 * Missing function doc comment
 */
function html_panellistamail( $apivalid, $awb_tool_unpolluted, $tmpaccount, $cf7_awb ) {

  if( !isset( $cf7_awb['list'] ) ) {
    $cf7_awb['list'] = array( 'id' => 0, 'name' => 'sin lista' );
  }

  $count = ( is_null ( $tmpaccount->lists )  ) ? 0 : count ( $tmpaccount->lists ) ;
  $i = 0 ;


  ?>

    <input class="spt-hidden" type="text" id="txcomodin2" name="wpcf7-Aweber[txtcomodin2]" value="<?php echo( isset( $apivalid ) ) ? esc_textarea( $apivalid ) : ''; ?>" />

  <?php

   if ( isset( $apivalid ) && '1' == $apivalid  && ( 1 == $awb_tool_unpolluted  or 2 == $awb_tool_unpolluted ) ) {

  ?>

    <p>
      <label for="wpcf7-Aweber-list"><?php echo esc_textarea( __( 'These are ALL your ' . $count .' Aweber Lists:', 'wpcf7' ) ); ?></label><br />
      <select id="wpcf7-Aweber-list" name="wpcf7-Aweber[list]" style="width:45%;">
      <?php

        foreach ( $tmpaccount->lists as $list ) {
        $i = $i + 1 ;
          ?>
          <option value="<?php echo esc_textarea( $list->name ) ?>"
          <?php
          if ( $cf7_awb['list'] === $list->name ) {
            echo 'selected="selected"'; } ?>>
              <?php echo $i . ' :'. $list->total_subscribers . ' - ' . esc_textarea( $list->name ).' - Unique id: '. esc_textarea( $list->id ).'' ?></option>
          <?php
            //wpcf7-Aweber-list
        }

        ?>
      </select> <span><input id="selgetcampos" type="button" value="Connect List" class="button button-primary button-large" /><span class="spinner"></span></span>
    <small class="description dbl">These are your mailing lists <a href="//renzojohnson.com/contributions/contact-form-7-aweber-extension/aweber-authorization-code?utm_source=AWeber&amp;utm_campaign=w4.7.3c4.7en_US&amp;utm_medium=cme-0.4.37&amp;utm_term=F1C1P5.4.45S5.5.51" class="helping-field" target="_blank" title="get help with AWeber:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>


    </p>

    <?php
  }

}

/**
 * Missing function doc comment
 */
function html_panelconfig( $cf7_awb, $awb_tool_unpolluted, $apivalid, $awb_valid, $awb_invalid, $tmpaccount, $numelemen, $listacampos, $listatags, $txcomodin ) {


  $awb_tool_unpolluted = 1;
  ?>
    <input class="spt-hidden" type="text" id="txcomodin" name="wpcf7-Aweber[txtcomodin]" value="<?php echo( isset( $txcomodin ) ) ? esc_textarea( $txcomodin ) : ''; ?>" />
  <?php

    if ( 1 == $awb_tool_unpolluted  or 2 == $awb_tool_unpolluted ) {

    ?>

    <div id="panelcodeapi" class="<?php echo ( 1 == $awb_tool_unpolluted or 2 == $awb_tool_unpolluted  )  ? 'spt-response-out spt-valid' : 'spt-response-out'; ?>" >
      <?php
      html_panelcodeapi( $awb_tool_unpolluted,$apivalid,$awb_valid,$awb_invalid,$cf7_awb );
      ?>
    </div>

    <div id="panellistamail" class="<?php echo ( 1 == $apivalid  )  ? 'spt-response-out spt-valid' : 'spt-response-out'; ?>" >
      <?php
      html_panellistamail( $apivalid,$awb_tool_unpolluted,$tmpaccount,$cf7_awb );
      ?>
    </div>

    <div id="panelconfigcampos" class="<?php echo ( 1 == $apivalid  )  ? 'spt-response-out spt-valid' : 'spt-response-out'; ?>" >
      <?php
      hmtl_mapeocampos( $numelemen,$cf7_awb,$listacampos,$listatags,$apivalid,$awb_tool_unpolluted );
      ?>
    </div>

    <?php
  }
}

/**
 * Missing function doc comment
 */
function get_unit_tags( $id = 0 ) {
  static $global_count = 0;
  $global_count += 1;
  if ( in_the_loop() ) {
    $unit_tag = sprintf( 'wpcf7-f%1$d-p%2$d-o%3$d', absint( $id ), get_the_ID(), $global_count );
  } else {
    $unit_tag = sprintf( 'wpcf7-f%1$d-o%2$d', absint( $id ), $global_count );
  }
  return $unit_tag;
}

if ( is_admin() ) {
  /**
   * Missing function doc comment
   */
  function wpcf7_custom_form_action_url() {
    return '#/'.get_unit_tags();
  }
  add_filter( 'wpcf7_form_action_url', 'wpcf7_custom_form_action_url' );
}

/**
 * Missing function doc comment
 */
function wpcf7_awb_add_aweber( $args ) {
  wpcf7_awb_add_aweber_prueba( $args -> id() );
}

/**
 * Missing function doc comment
 */
function wpcf7_awb_add_aweber_prueba( $idform ) {
  $host = esc_url_raw( $_SERVER['HTTP_HOST'] );
  $url = $_SERVER['REQUEST_URI'];
  $urlactual = $url;


  $idformxx = $idform;
  $cf7_awb_defaults = array();
  $cf7_awb = get_option( 'cf7_awb_'.$idform, $cf7_awb_defaults );
  $listatags = wpcf7_form_awb_tags();


  if ( ( ! isset( $cf7_awb['listatags'] ) ) or is_null( $cf7_awb['listatags'] ) ) {
    unset( $cf7_awb['listatags'] );
    $cf7_awb = $cf7_awb + array( 'listatags' => $listatags, 'logfileEnabled' => 1 ) ;
    update_option( 'cf7_awb_'.$idform, $cf7_awb );
  }

  // $apivalid = $cf7_awb['code-validation'];
  // $appIDvalidation = $cf7_awb['appID-validation'];
  // $listdata = $cf7_awb['lisdata'];
  // $listacampos = $cf7_awb['merge-vars'];

  $apivalid = ! isset($cf7_awb['code-validation']) ? "0" : $cf7_awb['code-validation'] ;
  $appIDvalidation = ! isset( $cf7_awb['appID-validation'] ) ? "0" : isset( $cf7_awb['appID-validation'] ) ;
  $listdata = ! isset( $cf7_awb['lisdata'] ) ? "0" : $cf7_awb['lisdata'] ;
  $listacampos = ! isset( $cf7_awb['merge-vars'] ) ? array() : $cf7_awb['merge-vars'] ;

  if ( count( $listacampos ) + 3 > count( $listatags ) ) {
    $numelemen = count( $listacampos ) + 3;
  } else {    $numelemen = count( $listatags );
  }
    $awb_tool_unpolluted = get_option( 'wpcf7-awb-api-tool_unpolluted', 0 );
    $awb_tool_key = get_option( 'wpcf7-awb-api-tool_key', 0 );
    $msgerrortool = get_option( 'wpcf7-awb-api-msgerrtool_unpolluted', 0 );
    $tmpaccount = ! isset( $cf7_awb['account'] ) ? "0" : $cf7_awb['account']  ;

  switch ( $awb_tool_unpolluted ) {
    case 1:
      $classtool = 'awb-activated';
      break;
    case 2:
      $classtool = 'awb-in-use';
      break;
    case -2:
      $classtool = 'awb-invalid';
      break;
    case -2:
      $classtool = 'awb-no-access';
      break;
  }
    $awb_appvalid = '<span class="awb valid"><span class="dashicons dashicons-yes"></span>APP Id</span>';
    $awb_appinvalid = '<span class="awb invalid"><span class="dashicons dashicons-no"></span>Error: APP Id</span>';
    $awb_valid = '<span class="awb valid"><span class="dashicons dashicons-yes"></span>Code Key</span>';
    $awb_invalid = '<span class="awb invalid"><span class="dashicons dashicons-no"></span>Error: Code Key</span>';


  $apivalid = ( is_null( $apivalid ) ) ? '0' : $apivalid;
  $tmpaccount = ( is_null( $tmpaccount ) ) ? '' : $tmpaccount;
  $listacampos = ( is_null( $listacampos ) ) ? '' : $listacampos;


  $awb_tool_unpolluted = 1;

  //( is_null( $consumerSecret ) ) ? 0 : $consumerSecret

  ?>
  <div class="awb-main-fields">
    <div id="spcodeapi">
      <h2>Aweber <span class="aw-lite">Lite</span>  <?php echo isset( $apivalid ) && '1' == $apivalid ? $awb_valid : $awb_invalid ; ?> <span class="aw-code"><?php global $wpdb; $awb_sents = get_option( 'awb_sent'); echo SPARTAN_AWB_VERSION . 'CF7:' . WPCF7_VERSION . 'WP' . get_bloginfo( 'version' ) . 'P' . PHP_VERSION . 'S' . $wpdb->db_version() .' - ' . $awb_sents .  ' sent in ' .  awb_difer_dateact_date(); ?></span></h2>

    </div>

    <div class="awb-custom-fields">

      <div id="panelconfig" >
        <?php
        html_panelconfig( $cf7_awb, $awb_tool_unpolluted,$apivalid,$awb_valid,$awb_invalid,$tmpaccount,$numelemen,$listacampos,$listatags,'' )
        ?>
      </div>


      <div id="awb-container" class="awb-container cme-container awb-support" style ="display:none" >

        <div class="Aweber-custom-fields">
          <h2 class="title">Map your form fields</h2>
          <p>In the following fields, you can use these mail-tags: <?php echo awb_mail_tags(); ?>.</p>

            <?php for($i=1;$i<=5;$i++){ ?>

              <div class="col-6">
                <label for="wpcf7-Aweber-CustomValue<?php echo $i; ?>"><?php echo esc_html( __( 'Contact Form Value '.$i.':', 'wpcf7' ) ); ?></label><br />
                <input type="text" id="wpcf7-Aweber-CustomValue<?php echo $i; ?>" name="wpcf7-Aweber[CustomValue<?php echo $i; ?>]" class="wide" size="70" placeholder="[your-example-value]" value="<?php echo (isset( $cf7_awb['CustomValue'.$i]) ) ?  esc_attr( $cf7_awb['CustomValue'.$i] ) : '' ;  ?>" />
              </div>

              <div class="col-6">
                <label for="wpcf7-Aweber-CustomKey<?php echo $i; ?>"><?php echo esc_html( __( 'Aweber Custom Field Name '.$i.':', 'wpcf7' ) ); ?></label><br />
                <input type="text" id="wpcf7-Aweber-CustomKey<?php echo $i; ?>" name="wpcf7-Aweber[CustomKey<?php echo $i; ?>]" class="wide" size="70" placeholder="example-field" value="<?php echo (isset( $cf7_awb['CustomKey'.$i]) ) ?  esc_attr( $cf7_awb['CustomKey'.$i] ) : '' ;  ?>" />
              </div>

            <?php }

            ?>

        </div>
        <?php include SPARTAN_AWB_PLUGIN_DIR . '/lib/tanuaw.php'; ?>

      </div>

      <div class="<?php echo ( ( $apivalid == 1  ) ? 'awb-active' : 'awb-inactive' ) ;  ?>">
        <p class="p-author"><a type="button" aria-expanded="false" class="awb-trigger a-support ">Show Advanced Settings</a> &nbsp; <a class="awb-trigger-sys a-support ">Get System Information</a> &nbsp; <a class="awb-trigger-log a-support ">View Debug Logger</a></p>
      </div>

      <?php include SPARTAN_AWB_PLUGIN_DIR . '/lib/systemaw.php'; ?>

      <?php  echo awb_html_log_view() ; ?>

    </div>

    <p class="p-author">This <a href="<?php echo AWB_URL ?>" title="This FREE WordPress plugin" alt="This FREE WordPress plugin">FREE WordPress plugin</a> is currently developed in Orlando, Florida by <a href="//renzojohnson.com" target="_blank" title="Front End Developer: Renzo Johnson" alt="Front End Developer: Renzo Johnson">Renzo Johnson</a>. Feel free to contact with your comments or suggestions.</p>
    <input class="spt-hidden" type="text" id="idformxx" name="wpcf7-Aweber[idformxx]" value="<?php echo( isset( $idformxx ) ) ? esc_textarea( $idformxx ) : ''; ?>" style="width:0%;" />
  </div>
<?php
}

/**
 * Missing function doc comment
 */
function wpcf7_awb_validate_appid( $consumerKey, $consumerSecret, $Aweber, $logenabled = false, $idform = '0' ) {
  $sRpta = 0;
  try {
    //$Aweber = new AWeberAPI( $consumerKey, $consumerSecret );

    $sRpta = 1;
    $authorizationURL = $Aweber->getAuthorizeUrl();
    $tmp = array( 'appID-validation' => 1 );
    $awb_db_log = new awb_db_log('awb_db_issues', $logenabled,'api',$idform );
    $awb_db_log->awb_log_insert_db( 1, ' ===============  App ID Response - IdForm:'.$idform .'  ===============  ' , 'Complete'  ) ;

    return $tmp;
  } catch ( Exception $e ) {
    $tmp = array( 'appID-validation' => 0 );
    $awb_db_log = new awb_db_log('awb_db_issues', $logenabled,'api',$idform );

    $awb_db_log->awb_log_insert_db( 4, ' ===============  App ID Response - IdForm:'.$idform.' ===============  ' , $e->getMessage() ) ;
    return $tmp;
  }
}

/**
 * Missing function doc comment
 */
function wpcf7_awb_validate_code( &$Aweber, $code, &$account, $logenabled = false, $idform = '0'
                                ,&$consumerKey,&$consumerSecret) {
  $sRpta = 0;
  try {

    $Aweber = null;
    $account = null;
    //require_once( SPARTAN_AWB_PLUGIN_DIR .'/api/aweber_api/aweber.php' );


    $credentials = AWeberAPI::getDataFromAweberID( $code );


    list($consumerKey, $consumerSecret, $accessKey, $accessSecret) = $credentials;
    //La Respuesta es NULL

    $Aweber = new AWeberAPI( $consumerKey, $consumerSecret ) ;

    $account = $Aweber->getAccount( $accessKey, $accessSecret );
    $tmp = array( 'code-validation' => 1, 'account' => $account, 'classAweber' => $Aweber,'appID-validation' => 1 );

    $awb_db_log = new awb_db_log('awb_db_issues', $logenabled,'api',$idform );
    $awb_db_log->awb_log_insert_db( 1, ' ===============  Code Response - IdForm:'.$idform. '  ===============  ' , 'AccessToken Valid'  ) ;

    return $tmp;
  } catch ( Exception $e ) {
    $Aweber = null;
    $account = null;

    $tmp = array( 'code-validation' => 0, 'account' => null, 'classAweber' => null,'appID-validation' => 0 );

    $awb_db_log = new awb_db_log('awb_db_issues', $logenabled,'api',$idform );
    $awb_db_log->awb_log_insert_db( 4, ' ===============  Code Response - IdForm:'.$idform. '  ===============  ' , $e->getMessage() ) ;

    return $tmp;
  }
}

/**
 * Missing function doc comment
 */
function wpcf7_awb_listasasociadas( &$account, &$list_data, $logenabled = false, $idform = '0' ) {
  try {
    $awb_db_log = new awb_db_log('awb_db_issues', $logenabled,'api',$idform );

    if ( is_null($account)) {
      throw new Exception('Not object instance!');
    }

    $list_data = $account->lists;
    $tmp = array( 'lisdata' => $list_data );

    $awb_db_log->awb_log_insert_db( 1, ' ===============  List ID RESPONSE  ===============  ' , 'Complete Lists'  ) ;

    return $tmp;
  } catch ( Exception $e ) {
    $list_data = array( 'id' => 0, 'name' => 'sin lista' );
    $tmp = array( 'lisdata' => $list_data );

    $awb_db_log = new awb_db_log('awb_db_issues', $logenabled,'api',$idform );
    $awb_db_log->awb_log_insert_db( 4, ' ===============  List ID RESPONSE  ===============  ' , $e->getMessage()  ) ;

    return $tmp;
  }
}

/**
 * Missing function doc comment
 */
function wpcf7_awb_merge_var( $list_data, $listName, $logenabled = false, $idform = '0' ) {

  try {
    if ( is_null( $list_data ) ) {
      throw new Exception( 'Not object instance!' );
    }

    if (  trim($listName) == ''  ) {
      throw new Exception( 'No Hay Nombre Lista!' );
    }


    $foundLists = $list_data->find( array( 'name' => $listName ) );

    $list = $foundLists[0];
    $merge_vars = $list->custom_fields;
    $tmp = array( 'merge-vars' => $merge_vars, 'llist_id' => $list->id );

    $awb_db_log = new awb_db_log('awb_db_issues', $logenabled,'api',$idform );
    $awb_db_log->awb_log_insert_db( 1, ' ===============  Custom Fields - IdForm:'.$idform. ' ===============  ' , 'Complete Fields'  ) ;


    return $tmp;
  } catch ( Exception $e ) {

    $merge_vars = array( 'id' => ' ', 'name' => 'sin campos' );
    $tmp = array( 'merge-vars' => $merge_vars, 'llist_id' => 0 );

    $awb_db_log = new awb_db_log('awb_db_issues', $logenabled,'api',$idform );
    $awb_db_log->awb_log_insert_db( 4, ' ===============  Custom Fields - IdForm:'.$idform. ' ===============  ' , $e->getMessage()  ) ;

    return $tmp;
  }
}

/**
 * Missing function doc comment
 */
function wpcf7_form_awb_tags() {
  $manager = WPCF7_FormTagsManager::get_instance();
  $form_tags = $manager->get_scanned_tags();
  return $form_tags;
}

/**
 * Missing function doc comment
 */
function wpcf7_awb_save_aweber( $args ) {
  if ( ! empty( $_POST ) ) {
    $cf7_awb_defaults = array();
    $cf7_awb = get_option( 'cf7_awb_'.$args->id(), $cf7_awb_defaults );
    $idform = 'cf7_awb_'.$args->id();
    $apptmp = array( 'appid' => 'f11768b7' );
    $tmppost = $_POST['wpcf7-Aweber'];
    $apicodetmp = $_POST['wpcf7-Aweber']['code'];
    $apicodetmp = ( is_null( $apicodetmp ) ) ? 0 : $apicodetmp;
    $logfileEnabled = $_POST['wpcf7-Aweber']['logfileEnabled'];


    $oldapicode = 0;
    $oldapivalid = 0;

    if (  isset( $cf7_awb['code-validation'] ) ) {
      $oldapicode = $cf7_awb['code'];
      $oldapicode = ( is_null( $oldapicode ) ) ? 0 : $oldapicode;
      $oldapivalid = $cf7_awb['code-validation'];
      $logfileEnabled = ( is_null( $logfileEnabled ) ) ? false : $logfileEnabled;
    }
    $awb_tool_unpolluted = 1;
    if ( ( $apicodetmp == $oldapicode ) && '1' == $oldapivalid ) {
      $oldAweber  = $cf7_awb['classAweber'];
      $oldaccount = $cf7_awb['account'];
      $tmp = array( 'appID-validation' => 1);
      $tmppost = $tmppost + $tmp ;
      $tmp = array( 'code-validation' => 1, 'account' => $oldaccount, 'classAweber' => $oldAweber );
      $tmppost = $tmppost + $tmp ;
      $Aweber = $oldAweber;
      $account = $oldaccount;
    } else {
        $tmp = wpcf7_awb_validate_code( $Aweber,$apicodetmp,$account,$logfileEnabled,$idform,$consumerKey,$consumerSecret );
        $tmppost = $tmppost + $tmp;
        $apptmp = array( 'appid' => 'f11768b7', 'consumerkey' => $consumerKey, 'consumersecret' => $consumerSecret );
        $tmppost = $tmppost + $apptmp;

    }


    $tmp = wpcf7_awb_listasasociadas( $account,$list_data,$logfileEnabled,$idform );
    $tmppost = $tmppost + $tmp;
    $listName = ( !isset( $_POST['wpcf7-Aweber']['list'] ) ) ? '' : trim( $_POST['wpcf7-Aweber']['list'] );


    $tmp = wpcf7_awb_merge_var( $list_data,$listName,$logfileEnabled,$idform );
    $tmppost = $tmppost + $tmp;


    update_option( $idform, $tmppost );
  }
}
/**
 * Missing function doc comment
 * @param string $panels Missing parameter comment.
 */
function show_awb_metabox( $panels ) {
  $new_page = array( 'Aweber-Extension' => array( 'title' => __( 'Aweber', 'contact-form-7' ), 'callback' => 'wpcf7_awb_add_aweber' ) );
  $panels = array_merge( $panels, $new_page );
  return $panels;
}

/**
 * Missing function doc comment
 */
function spartan_awb_author_wpcf7( $awb_supps, $class, $content, $args ) {
  // $output, $class,$content
  $cf7_awb_defaults = array();
  $cf7_awb = get_option( 'cf7_awb_'.$args->id(), $cf7_awb_defaults );
  $cfsupp = ( isset( $cf7_awb['cf-supp'] ) ) ? $cf7_awb['cf-supp'] : 0;


  if ( '1' == $cfsupp ) {
    $awb_supps .= awb_referer();
    $awb_supps .= awb_author();
  } else {
    $awb_supps .= awb_referer();
    $awb_supps .= '<!-- awbmail extension by Renzo Johnson -->';
  }
    return $awb_supps;
}
  add_filter( 'wpcf7_form_response_output', 'spartan_awb_author_wpcf7', 40,4 );

/**
 * Missing function doc comment
 */
function cf7_awb_tag_replace( $pattern, $subject, $posted_data, $html = false ) {
  if ( preg_match( $pattern,$subject,$matches ) > 0 ) {
    if ( isset( $posted_data[ $matches[1] ] ) ) {
      $submitted = $posted_data[ $matches[1] ];
      if ( is_array( $submitted ) ) {
        $replaced = join( ', ', $submitted );
      } else {
        $replaced = $submitted;
      }
      if ( $html ) {
        $replaced = strip_tags( $replaced );
        $replaced = wptexturize( $replaced );
      }
      $replaced = apply_filters( 'wpcf7_mail_tag_replaced', $replaced, $submitted );
      return stripslashes( $replaced );
    }
    if ( $special = apply_filters( 'wpcf7_special_mail_tags', '', $matches[1] ) ) {
      return $special;
    }
    return $matches[0];
  }
  return $subject;
}

/**
 * Missing function doc comment
 * @param string $obj Missing parameter comment.
 */
function wpcf7_awb_subscribe( $obj ) {
  $cf7_awb = get_option( 'cf7_awb_'.$obj->id() );
  $idform = 'cf7_awb_'.$obj->id();
  $logfileEnabled = $cf7_awb['logfileEnabled'];
  $logfileEnabled = ( is_null( $logfileEnabled ) ) ? false : $logfileEnabled;
  $submission = WPCF7_Submission::get_instance();
  $list_id = esc_html( $cf7_awb['llist_id'] );
  $account = $cf7_awb['account'];


  $account_id = esc_html( $account->id );


  $listURL = "/accounts/{$account_id}/lists/{$list_id}";


  $list = $account->loadFromUrl( $listURL );


  if ( $cf7_awb ) {
    $subscribe = false;
    $regex = '/\[\s*([a-zA-Z_][0-9a-zA-Z:._-]*)\s*\]/';
    $callback = array( &$obj, 'cf7_awb_callback' );
    $email = cf7_awb_tag_replace( $regex, $cf7_awb['email'], $submission->get_posted_data() );
    $name = cf7_awb_tag_replace( $regex, $cf7_awb['name'], $submission->get_posted_data() );

    //$email = 'ffcossiop77@gmail.com';
    //$name = 'Roberta Carla mucha nepe';
    $merge_vars = array();

    for ( $i = 1; $i <= 20; $i++ ) {
      if ( isset( $cf7_awb[ 'CustomKey'.$i ] ) && isset( $cf7_awb[ 'CustomValue'.$i ] ) && strlen( trim( $cf7_awb[ 'CustomValue'.$i ] ) ) != 0 ) {
        $NameField = trim( $cf7_awb[ 'CustomKey'.$i ] );
        $NameField = strtr( $NameField, '[', '' );
        $NameField = strtr( $NameField, ']', '' );
        $txvalorfield = '['.trim( $cf7_awb[ 'CustomValue'.$i ] ).']';
        $valorfield = cf7_awb_tag_replace( $regex, $txvalorfield, $submission->get_posted_data() );

        if ( 'email' != $NameField && 'name' != $NameField ) {
          if ( count( $merge_vars ) != 0 ) {
            $merge_vars = $merge_vars + array( $NameField => $valorfield );
          } else {
            $merge_vars = array( $NameField => $valorfield );
          }
        } else {
          if ( 'email' == $NameField ) { $email = $valorfield; }
          if ( 'name' == $NameField ) { $name = $valorfield; }
        }
      }
    }
    if ( isset( $cf7_awb['accept'] ) && strlen( $cf7_awb['accept'] ) !== 0 ) {
      $accept = cf7_awb_tag_replace( $regex, $cf7_awb['accept'], $submission->get_posted_data() );
      if ( $accept !== $cf7_awb['accept'] ) {
        if ( strlen( $accept ) > 0 ) {
          $subscribe = true;
        }
      }
    } else {
      $subscribe = true;
    }
    if ( $subscribe ) {
      try {
        $params = array( 'email' => $email, 'name' => $name, 'custom_fields' => $merge_vars );

        $new_subscriber = $list->subscribers->create( $params );

        $awb_db_log = new awb_db_log('awb_db_issues', $logfileEnabled,'api',$idform );
        $awb_db_log->awb_log_insert_db( 1, ' ===============  Submission mail idForm:'.$idform.'  ===============  ' , $new_subscriber  ) ;


      } catch ( Exception $e ) {

        $awb_db_log = new awb_db_log('awb_db_issues', $logfileEnabled,'api',$idform );
        $awb_db_log->awb_log_insert_db( 1, ' ===============  Submission mail idForm:'.$idform.'  ===============  ' , $e->getMessage()  ) ;

      }
    }
  }
}

/**
 * Missing function doc comment
 * @param string $class Missing parameter comment.
 */
function spartan_awb_class_attr( $class ) {
  $class .= ' Aweber-ext-' . SPARTAN_AWB_VERSION;
  return $class;
}