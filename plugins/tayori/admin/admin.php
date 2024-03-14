<?php

load_plugin_textdomain( 'tayori', false, 'tayori/languages' );

require_once TAYORI_PLUGIN_DIR . '/admin/admin-functions.php';

add_action( 'admin_menu', 'tayori_admin_menu', 9);

add_action( 'admin_enqueue_scripts', 'tayori_admin_enqueue_scripts' );

function tayori_admin_enqueue_scripts( $hook_suffix ) {
  if ( false === strpos( $hook_suffix, 'tayori' ) ) {
    return;
  }

  wp_enqueue_style( 'tayori-admin',
    tayori_plugin_url( 'admin/css/styles.css' ),
    array(), TAYORI_VERSION, 'all' );

  wp_enqueue_script( 'tayori-admin-colpick',
    tayori_plugin_url( 'admin/js/colpick.js' ),
    array( 'jquery' ),
    TAYORI_VERSION, true );

  wp_enqueue_script( 'tayori-admin-jquery-easing',
    tayori_plugin_url( 'admin/js/jquery.easing.js' ),
    array( 'jquery' ),
    TAYORI_VERSION, true );

  wp_enqueue_script( 'tayori-admin-jquery-transit',
    tayori_plugin_url( 'admin/js/jquery.transit.js' ),
    array( 'jquery' ),
    TAYORI_VERSION, true );

  wp_enqueue_script( 'tayori-admin-jquery-color',
    tayori_plugin_url( 'admin/js/jquery.color.js' ),
    array( 'jquery' ),
    TAYORI_VERSION, true );

  wp_enqueue_script( 'tayori-admin-setting',
    tayori_plugin_url( 'admin/js/setting.js' ),
    array( 'jquery' ),
    TAYORI_VERSION, true );
}

function tayori_admin_menu() {
  add_menu_page( __('Tayori', 'tayori'), __('Tayori', 'tayori'), 8, __FILE__, 'tayori_form_page');
  add_submenu_page(__FILE__, __('Mail Setting', 'tayori'), __('Mail Setting', 'tayori'), 8, 'tayori-mail-setting', 'tayori_mail_page');
}

function tayori_form_page() {
  $tayori = new Tayori;
  if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $validate_data = tayori_form_validate($_POST);
    if ($validate_data && $validate_data['status'] == true) {
      $result = $tayori->save($validate_data);
      if ($result['status'] == true) {
        $save_message = __('Saved data', 'tayori');
        //var_dump($save_message);
      }
      else {
        //var_dump($result['message']);
        $save_message = __('Fatal save data', 'tayori');
      }
    }
    else {
      //var_dump($validate_data['message']);
      $save_message = __('Fatal save data', 'tayori');
    }
    $tayori_data = $validate_data['data'];
  }
  else {
    $tayori_data = $tayori->get();
  }
?>
<script>
  tayori_plugin_url = '<?php echo TAYORI_PLUGIN_URL; ?>';
</script>

<div class="wrap" id="form-setting-panel">
  <!--//main-section-head-->
  <div class="row">
    <div class="col-md-12 main-area-head">
      <h1 class="page-header color-title page-right-clm-title">
      <svg version="1.1" id="レイヤー_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
         y="0px" viewBox="0 0 34.015747 34.015747" style="width:27px;margin:0px 0px -4px -4px;fill:#43BFA0;enable-background:new 0 0 34.015747 34.015747;" xml:space="preserve">
      <style type="text/css">
        .st0{fill:#EFEFEF;}
        .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#5D6261;}
        .st2{fill:none;stroke:#5D6261;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
        .st3{fill:#5D6261;}
        .st4{fill:none;stroke:#5D6261;stroke-linecap:round;stroke-miterlimit:10;}
        .st5{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:#5D6261;stroke-linecap:round;stroke-miterlimit:10;}
        .st6{clip-path:url(#SVGID_2_);fill:#5D6261;}
        .st7{fill:#5D6260;}
        .st8{fill:#040000;}
      </style>
      <path class="st1" d="M33.331165,8.097595l-1.179504-1.179504c-0.778868-0.77887-2.037474-0.78302-2.830137,0.009644
        l-3.580139,3.580139l-6-7H8.744192c-1.10614,0-2.002808,0.898315-2.002808,2.007324v22.985352
        c0,1.108582,0.89093,2.007324,1.997437,2.007324h15.005188c1.103088,0,1.997375-0.898254,1.997375-1.990784V18.507874
        l7.580137-7.580139C34.105701,10.143555,34.107594,8.874023,33.331165,8.097595z M19.741385,5.007874l4.699951,5.5H20.73815
        c-0.546082,0-0.996765-0.45166-0.996765-1.008789V5.007874z M24.741385,28.514465c0,0.548462-0.447693,0.993408-0.999939,0.993408
        H8.741385c-0.545288,0-1-0.445679-1-0.995483V5.503296c0-0.540161,0.44574-0.995422,0.995605-0.995422h10.004395v4.99408
        c0,1.119385,0.894531,2.00592,1.997925,2.00592h4.002075l-10,10l-1,5l5-1l6-6V28.514465z M15.645132,22.118652l2.495361,2.495361
        l-3.134949,0.617371L15.645132,22.118652z M18.898428,23.957764l-2.605591-2.605591l11.292114-11.29364l2.59967,2.59967
        L18.898428,23.957764z M32.620533,10.219421l-1.729185,1.731262l-2.599365-2.599304l1.727356-1.7276
        c0.391357-0.391418,1.023926-0.388367,1.416565,0.003174l1.179808,1.176575
        C33.007618,9.194336,33.010487,9.828979,32.620533,10.219421z"/>
      </svg>
      <?php echo __('Form Setting', 'tayori'); ?>
      </h1>
    </div>
  </div>
  <!--//main-section-head-->
  <div class="p-project-unit-body">
    <div class="p-project-unit-body__wrapper">
      <div class="u-center">
        <div class="c-text-basic u-bold mb-1">
          <?php echo __('Select Form Type', 'tayori'); ?>
        </div>
        <div class="l-vertical-divide">
          <div class="l-vertical-divide__col">
            <div class="l-vertical-divide__col__inner">
              <div class="form-group u-center">
                <label class="mr-sm-1">PC :</label>
                <select class="custom-select custom-select-sm" style="min-width:160px;padding:0 1.75rem 0.375rem 0.75rem;" name="form_setup_form[form_type_pc]" id="form_setup_form_form_type_pc">
                  <option <?php if ($tayori_data['form_type_pc'] == 1) { ?>selected="selected"<?php } ?> value="1"><?php echo __('Standard Type', 'tayori'); ?></option>
                  <option <?php if ($tayori_data['form_type_pc'] == 2) { ?>selected="selected"<?php } ?> value="2"><?php echo __('Talk Type', 'tayori'); ?></option>
                </select>
                <?php if ($validate_data && array_key_exists('form_type_pc', $validate_data['message'])) { ?>
                <p class="control-label"><?php echo $validate_data['message']['form_type_pc']; ?></p>
                <?php } ?>
              </div>
              <div id="form_pc" class="mt-1 mb-1 form-type-pc" style="margin: auto; max-width: 200px;">
                <img id="form-type-pc-1" alt="<?php echo __('Standard Type', 'tayori'); ?>" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-form-pc-standerd.png" style="width:100%;">
                <img id="form-type-pc-2" class="hider" alt="<?php echo __('Talk Type', 'tayori'); ?>" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-form-pc-talk.png" style="width:100%;">
              </div>
            </div>
          </div>
          <div class="l-vertical-divide__divider"></div>
          <div class="l-vertical-divide__col">
            <div class="l-vertical-divide__col__inner">
              <div class="form-group u-center">
                <label class="mr-sm-1"><?php echo __('Smartphone', 'tayori'); ?> :</label>
                <select class="custom-select custom-select-sm" style="min-width:160px;padding:0 1.75rem 0.375rem 0.75rem;" name="form_setup_form[form_type_sp]" id="form_setup_form_form_type_sp">
                  <option <?php if ($tayori_data['form_type_sp'] == 1) { ?>selected="selected"<?php } ?> value="1"><?php echo __('Standard Type', 'tayori'); ?></option>
                  <option <?php if ($tayori_data['form_type_sp'] == 2) { ?>selected="selected"<?php } ?>value="2"><?php echo __('Talk Type', 'tayori'); ?></option>
                </select>
                <?php if ($validate_data && array_key_exists('form_type_sp', $validate_data['message'])) { ?>
                <p class="control-label"><?php echo $validate_data['message']['form_type_sp']; ?></p>
                <?php } ?>
              </div>
              <div id="form_sp" class="mt-2 mb-1 form-type-sp" style="margin: auto; max-width: 44px;">
                <img id="form-type-sp-1" alt="<?php echo __('Standard Type', 'tayori'); ?>" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-form-sp-standard.png" style="width:100%;">
                <img id="form-type-sp-2" class="hider" alt="<?php echo __('Talk Type', 'tayori'); ?>" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-form-sp-talk.png" style="width:100%;">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="p-project-unit-body">
    <div class="p-project-group">
      <div class="panel embed-panel" style="display: block;">
        <div class="c-text-basic u-center mb-1">
          <?php echo __('Select Form Explanation', 'tayori'); ?>
        </div>
        <div class="p-project-group__layout p-project-group-tab js-form-btn-tab-parent">
          <div id="simple-button-box" class="p-project-group__layout__col p-project-group__item p-project-group-tab__item js-form-btn-tab button-box<?php if ($tayori_data['button_type'] == 1) { ?> is-active<?php } ?>" data-button-type="1">
            <div class="p-project-group__item__inner">
              <div class="c-text-basic u-bold u-center">
                <?php echo __('Simple Button', 'tayori'); ?>
              </div>
              <div class="u-center">
                <img alt="<?php echo __('Simple Button', 'tayori'); ?>" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/form-setting-button1.png" style="width:60%;">
              </div>
              <div class="u-center">
                <button class="js-form-btn-tab-trigger btn btn-sm btn-width-s btn-primary" type="button" data-id="simple" data-buttontype="1"">選択中</button>
                <button class="js-form-btn-tab-trigger btn btn-sm btn-width-s btn-util" type="button"  data-id="simple" data-buttontype="1">選択</button>
              </div>
            </div>
          </div>
          <div id="pop-button-box" class="p-project-group__layout__col p-project-group__item p-project-group-tab__item js-form-btn-tab button-box<?php if ($tayori_data['button_type'] == 2) { ?> is-active<?php } ?>" data-button-type="2">
            <div class="p-project-group__item__inner">
              <div class="c-text-basic u-bold u-center">
                <?php echo __('Pop Button', 'tayori'); ?>
              </div>
              <div class="u-center">
                <img alt="<?php echo __('Pop Button', 'tayori'); ?>" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/form-setting-button2.png" style="width:60%;">
              </div>
              <div class="u-center">
                <button class="js-form-btn-tab-trigger btn btn-sm btn-width-s btn-primary" type="button" data-id="pop" data-buttontype="2">選択中</button>
                <button class="js-form-btn-tab-trigger btn btn-sm btn-width-s btn-util" type="button" data-id="pop" data-buttontype="2">選択</button>
              </div>
            </div>
          </div>
          <div id="classic-button-box" class="p-project-group__layout__col p-project-group__item p-project-group-tab__item js-form-btn-tab button-box<?php if ($tayori_data['button_type'] == 3) { ?> is-active<?php } ?>" data-button-type="3">
            <div class="p-project-group__item__inner">
              <div class="c-text-basic u-bold u-center">
                <?php echo __('Classic Button', 'tayori'); ?>
              </div>
              <div class="u-center">
                <img alt="<?php echo __('Classic Button', 'tayori'); ?>" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/form-setting-button3.png" style="width:60%;">
              </div>
              <div class="u-center">
                <button class="js-form-btn-tab-trigger btn btn-sm btn-width-s btn-primary" type="button" data-id="classic" data-buttontype="3">選択中</button>
                <button class="js-form-btn-tab-trigger btn btn-sm btn-width-s btn-util" type="button" data-id="classic" data-buttontype="3">選択</button>
              </div>
            </div>
          </div>
          <?php if ($validate_data && array_key_exists('button_type', $validate_data['message'])) { ?>
          <p class="control-label"><?php echo $validate_data['message']['button_type']; ?></p>
          <?php } ?>
        </div>
        <form class="form-horizontal" id="form_setup_form" enctype="multipart/form-data" action="" accept-charset="UTF-8" method="post">
          <input value="<?php echo $tayori_data['button_type']; ?>" type="hidden" name="form_setup_form[button_type]" id="form_setup_form_button_type" />
          <input value="<?php echo $tayori_data['pop_button_type']; ?>" type="hidden" name="form_setup_form[pop_button_type]" id="form_setup_form_pop_button_type" />
          <input value="<?php echo $tayori_data['form_type_sp']; ?>" type="hidden" name="form_setup_form[form_type_sp]" id="form_setup_form_form_type_sp" />
          <input value="<?php echo $tayori_data['form_type_pc']; ?>" type="hidden" name="form_setup_form[form_type_pc]" id="form_setup_form_form_type_pc" />
          <div class="js-form-btn-panel">
            <div class="p-project-group__item button-panel" id="simple-button-setting"<?php if ($tayori_data['button_type'] != 1) { ?> style="display:none"<?php } ?>>
              <div class="p-project-group__item__inner">
                <div class="p-project-group__item__title">
                  <?php echo __('Setting Title Position Color', 'tayori'); ?>
                </div>
                <div class="l-vertical-divide">
                  <div class="l-vertical-divide__col">
                    <div class="l-vertical-divide__col__inner">
                      <div id="simple-color-setting-area">

                      </div>
                    </div>
                  </div>
                  <div class="l-vertical-divide__divider"></div>
                  <div class="l-vertical-divide__col">
                    <div class="l-vertical-divide__col__inner">
                      <div id="simple-trasnparency-setting-area">

                      </div>
                    </div>
                  </div>
                </div>
                <div class="c-section-basic mt-1">
                  <div class="c-section-basic__content u-center">
                    <div class="text-basic">
                      <?php echo __('Button Preview', 'tayori'); ?>
                    </div>
                    <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                      <div class="p-btn-color-simulator__inner">
                        <img src="<?php echo TAYORI_PLUGIN_URL; ?>/images/simple-button-icon.png" style="width:18px; height:29px;">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="p-project-group__item button-panel" id="pop-button-setting"<?php if ($tayori_data['button_type'] != 2) { ?> style="display:none"<?php } ?>>
              <div class="p-project-group__item__inner">
                <div class="p-project-group__item__title">
                  <?php echo __('Setting Title Position Color', 'tayori'); ?>
                </div>
                <div class="p-icon-selector">
                  <div class="p-icon-selector__inner">
                    <div class="p-icon-selector__scroll js-scroll-y ps-container ps-theme-default ps-active-y" data-ps-id="7ae29f5c-5963-2891-1582-a491cb2b285c">
                      <div class="p-icon-selector__list">
                        <div class="p-icon-selector__list__inner">
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 1) { ?> is-focus<?php } ?>" data-poptype="1">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/01.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 2) { ?> is-focus<?php } ?>" data-poptype="2">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/02.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 3) { ?> is-focus<?php } ?>" data-poptype="3">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/03.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 4) { ?> is-focus<?php } ?>" data-poptype="4">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/04.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 5) { ?> is-focus<?php } ?>" data-poptype="5">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/05.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 6) { ?> is-focus<?php } ?>" data-poptype="6">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/06.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 7) { ?> is-focus<?php } ?>" data-poptype="7">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/07.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 8) { ?> is-focus<?php } ?>" data-poptype="8">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/08.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 9) { ?> is-focus<?php } ?>" data-poptype="9">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/09.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 10) { ?> is-focus<?php } ?>" data-poptype="10">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/10.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 11) { ?> is-focus<?php } ?>" data-poptype="11">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/11.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 12) { ?> is-focus<?php } ?>" data-poptype="12">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/12.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 13) { ?> is-focus<?php } ?>" data-poptype="13">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/13.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 14) { ?> is-focus<?php } ?>" data-poptype="14">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/14.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 15) { ?> is-focus<?php } ?>" data-poptype="15">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/15.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 16) { ?> is-focus<?php } ?>" data-poptype="16">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/16.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 17) { ?> is-focus<?php } ?>" data-poptype="17">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/17.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 18) { ?> is-focus<?php } ?>" data-poptype="18">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/18.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 19) { ?> is-focus<?php } ?>" data-poptype="19">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/19.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 20) { ?> is-focus<?php } ?>" data-poptype="20">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/20.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 21) { ?> is-focus<?php } ?>" data-poptype="21">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/21.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 22) { ?> is-focus<?php } ?>" data-poptype="22">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/22.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 23) { ?> is-focus<?php } ?>" data-poptype="23">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/23.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 24) { ?> is-focus<?php } ?>" data-poptype="24">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/24.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 25) { ?> is-focus<?php } ?>" data-poptype="25">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/25.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 26) { ?> is-focus<?php } ?>" data-poptype="26">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/26.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 27) { ?> is-focus<?php } ?>" data-poptype="27">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/27.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 28) { ?> is-focus<?php } ?>" data-poptype="28">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/28.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 29) { ?> is-focus<?php } ?>" data-poptype="29">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/29.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 30) { ?> is-focus<?php } ?>" data-poptype="30">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/30.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 31) { ?> is-focus<?php } ?>" data-poptype="31">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/31.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 32) { ?> is-focus<?php } ?>" data-poptype="32">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/32.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 33) { ?> is-focus<?php } ?>" data-poptype="33">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/33.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 34) { ?> is-focus<?php } ?>" data-poptype="34">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/34.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 35) { ?> is-focus<?php } ?>" data-poptype="35">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/35.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 36) { ?> is-focus<?php } ?>" data-poptype="36">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/36.png">
                              </div>
                            </div>
                          </div>
                          <div class="p-icon-selector__list__item<?php if ($tayori_data['pop_button_type'] == 37) { ?> is-focus<?php } ?>" data-poptype="37">
                            <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                              <div class="p-btn-color-simulator__inner">
                                <img alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/37.png">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; right: 0px; height: 240px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 117px;"></div></div></div>
                  </div>
                </div>
                <div class="l-vertical-divide">
                  <div class="l-vertical-divide__col">
                    <div class="l-vertical-divide__col__inner">
                      <div id="pop-color-setting-area">

                      </div>
                    </div>
                  </div>
                  <div class="l-vertical-divide__divider"></div>
                  <div class="l-vertical-divide__col">
                    <div class="l-vertical-divide__col__inner">
                      <div id="pop-trasnparency-setting-area">

                      </div>
                    </div>
                  </div>
                </div>
                <div class="c-section-basic mt-1">
                  <div class="c-section-basic__content u-center">
                    <div class="text-basic">
                      <?php echo __('Button Preview', 'tayori'); ?>
                    </div>
                    <div class="p-btn-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                      <div class="p-btn-color-simulator__inner">
                        <img id="pop-button-preview-image" alt="" src="https://s3-ap-northeast-1.amazonaws.com/tayori/images/popbutton/basic/01.png">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="p-project-group__item button-panel" id="classic-button-setting"<?php if ($tayori_data['button_type'] != 3) { ?> style="display:none"<?php } ?>>
              <div class="p-project-group__item__inner">
                <div class="p-project-group__item__title">
                  <?php echo __('Setting Title Position Color', 'tayori'); ?>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label class="c-form-label u-bold" style="margin-bottom: 0.5em;" for=""><?php echo __('Button Title', 'tayori'); ?></label>
                      <input class="form-control form-control-sm " placeholder="<?php echo __('Button Title', 'tayori'); ?>" value="<?php echo $tayori_data['button_title'] ?>" maxlength="100" size="100" type="text" name="form_setup_form[button_title]" id="form_setup_form_button_title">
                      <?php if ($validate_data && array_key_exists('button_title', $validate_data['message'])) { ?>
                        <p class="control-label"><?php echo $validate_data['message']['button_title']; ?></p>
                      <?php } ?>
                    </div>
                    <div id="classic-color-setting-area">
                      <div id="color-setting-area">
                        <label class="c-form-label u-bold" style="margin-bottom: 0.5em;" for=""><?php echo __('Button Color', 'tayori'); ?></label>
                        <div class="form-group">
                          <div class="c-color-chip area-color-box">
                            <input value="<?php echo $tayori_data['button_color'] ?>" type="hidden" name="form_setup_form[button_color]" id="form_setup_form_button_color">
                            <div class="c-color-chip__target button-color-chip__target" style="background-color: <?php echo $tayori_data['button_color'] ?>;" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $tayori_data['button_color'] ?>"></div>
                          </div>
                          <?php if ($validate_data && array_key_exists('button_color', $validate_data['message'])) { ?>
                            <p class="control-label"><?php echo $validate_data['message']['button_color']; ?></p>
                          <?php } ?>
                          <select id="form-button-color-select" class="custom-select custom-select-sm form-button-color-select" style="vertical-align:top; min-width:120px; padding:0 1.75rem 0.375rem 0.75rem;">
                            <option>
                              <?php echo __('Select Color', 'tayori'); ?>
                            </option>
                            <option value="1">
                              <?php echo __('Turquoise', 'tayori'); ?>
                            </option>
                            <option value="2">
                              <?php echo __('Sky', 'tayori'); ?>
                            </option>
                            <option value="3">
                              <?php echo __('Indigo', 'tayori'); ?>
                            </option>
                            <option value="4">
                              <?php echo __('Lavender', 'tayori'); ?>
                            </option>
                            <option value="5">
                              <?php echo __('Cherry', 'tayori'); ?>
                            </option>
                            <option value="6">
                              <?php echo __('Tangerine', 'tayori'); ?>
                            </option>
                            <option value="7">
                              <?php echo __('Amber', 'tayori'); ?>
                            </option>
                            <option value="8">
                              <?php echo __('Dusk Blue', 'tayori'); ?>
                            </option>
                            <option value="9">
                              <?php echo __('Strawberry', 'tayori'); ?>
                            </option>
                            <option value="10">
                              <?php echo __('Titanium', 'tayori'); ?>
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <label class="c-form-label u-bold" style="margin-bottom: 0.5em;" for=""><?php echo __('Button Font Color', 'tayori'); ?></label>
                    <div class="form-group">
                      <div class="c-color-chip area-color-box">
                        <input value="<?php echo $tayori_data['button_font_color'] ?>" type="hidden" name="form_setup_form[button_font_color]" id="form_setup_form_button_font_color" />
                        <div class="c-color-chip__target button-font-color-chip__target" style="background-color: <?php echo $tayori_data['button_font_color'] ?>;" data-toggle="tooltip" data-placement="top" title="" data-original-title="#ffffff"></div>
                      </div>
                      <?php if ($validate_data && array_key_exists('button_font_color', $validate_data['message'])) { ?>
                        <p class="control-label"><?php echo $validate_data['message']['button_font_color']; ?></p>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-8">
                    <div style="padding-left:2em; border-left : 1px solid rgba(0,0,0,0.1);">
                      <div id="classic-trasnparency-setting-area">
                        <div id="trasnparency-setting-area">
                          <label class="c-form-label u-bold" style="margin-bottom: 0.5em;" for=""><?php echo __('Button Transparent', 'tayori'); ?></label>
                          <div class="form-group">
                            <label class="custom-control custom-radio">
                              <input class="custom-control-input" id="inlineRadio10" name="form_setup_form[button_icon_transparent_type]" type="radio" value="1"<?php if ($tayori_data['button_icon_transparent_type'] == 1) { ?> checked="checked"<?php } ?>>
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description"><?php echo __('Normal', 'tayori'); ?></span>
                            </label>
                            <label class="custom-control custom-radio">
                              <input class="custom-control-input" id="inlineRadio11" name="form_setup_form[button_icon_transparent_type]" type="radio" value="2"<?php if ($tayori_data['button_icon_transparent_type'] == 2) { ?> checked="checked"<?php } ?>>
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description"><?php echo __('Semitransparent', 'tayori'); ?></span>
                            </label>
                            <label class="custom-control custom-radio">
                              <input class="custom-control-input" id="inlineRadio12" name="form_setup_form[button_icon_transparent_type]" type="radio" value="3"<?php if ($tayori_data['button_icon_transparent_type'] == 3) { ?> checked="checked"<?php } ?>>
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description"><?php echo __('Un Scroll Semitransparent', 'tayori'); ?></span>
                            </label>
                          </div>
                          <?php if ($validate_data && array_key_exists('button_icon_transparent_type', $validate_data['message'])) { ?>
                            <p class="control-label"><?php echo $validate_data['message']['button_icon_transparent_type']; ?></p>
                          <?php } ?>
                        </div>
                      </div>
                      <!-- <label class="c-form-label u-bold" style="margin-bottom: 0.5em;" for="">ボタンアイコン</label>
                      <div class="form-group">
                        <label class="custom-control custom-radio"><input class="custom-control-input" name="button-icon-type" type="radio" value="1" checked="checked"><span class="custom-control-indicator"></span><span class="custom-control-description">Tayoriアイコン</span></label><label class="custom-control custom-radio"><input class="custom-control-input" name="button-icon-type" type="radio" value="2"><span class="custom-control-indicator"></span><span class="custom-control-description">アイコンなし</span></label><label class="custom-control custom-radio"><input class="custom-control-input" name="button-icon-type" type="radio" value="3"><span class="custom-control-indicator"></span><span class="custom-control-description">オリジナルアイコン</span></label>
                      </div>
                      <div class="row">
                        <div class="col-2">
                          <div class="c-with-del-btn">
                            <div class="c-preview-image">
                              <img class="button-icon-type-image" id="button-icon-type-1" src="/assets/logo-540cd5ee3d908457831d98b2b4825f0f732ead424131568f3e992661a69ae97a.svg" style="height:55px;"><img class="button-icon-type-image" id="button-icon-type-2" src="/assets/avatar/icon-bcabd0839a13e8d1ed905e4e24d61c20afbd900f1c144514ab6451e29852cf68.png" style="display:none"><img class="button-icon-type-image" id="button-icon-type-3" src="/assets/avatar/icon-bcabd0839a13e8d1ed905e4e24d61c20afbd900f1c144514ab6451e29852cf68.png" style="display:none">
                            </div>
                          </div>
                        </div>
                        <div class="col-10">
                          <label class="custom-file" style="display: block;width: 350px;">
                            <div class="form-group">
                              <input class="custom-file-input" data-toggle="custom-file" id="icon-image" name="file" type="file"><span class="custom-file-control" data-content-value="選択してください.."></span>
                            </div>
                          </label><small class="text-muted">※ ファイル形式はpng、gif、jpgです。 最大サイズは「5MB」です。</small>
                        </div>
                      </div> -->
                    </div>
                  </div>
                </div>
                <div class="c-section-basic mt-1">
                  <div class="c-section-basic__content u-center">
                    <div class="text-basic">
                      <?php echo __('Button Preview', 'tayori'); ?>
                    </div>
                    <div class="p-btn-tab-color-simulator" style="background-color: <?php echo $tayori_data['button_color'] ?>;">
                      <div class="p-btn-tab-color-simulator__layout">
                        <div class="p-btn-tab-color-simulator__layout__left">
                          <img class="button-icon-type-preview" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/logo.svg" style="">
                        </div>
                        <div class="p-btn-tab-color-simulator__layout__center button-title" style="color: <?php echo $tayori_data['button_font_color'] ?>;" data-color="<?php echo $tayori_data['button_font_color'] ?>">
                          <?php echo $tayori_data['button_title'] ?>
                        </div>
                        <div class="p-btn-tab-color-simulator__layout__right">
                          <svg style="enable-background:new 0 0 100 100;" version="1.1" viewBox="0 0 100 100" x="0px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" y="0px">
                            <rect style="fill: #ffffff;" height="6" transform="matrix(0.707107 0.707107 -0.707107 0.707107 47.95752 -8.669399)" width="50" x="9.443651" y="50.555149"></rect>
                            <rect style="fill: #ffffff;" height="6" transform="matrix(-0.707107 0.707107 -0.707107 -0.707107 149.780899 45.069019)" width="50" x="40.556351" y="50.555149"></rect>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="p-project-group__item button-position-panel">
              <div class="p-project-group__item__inner">
                <div class="p-project-group__item__title">
                  <?php echo __('Setting Position', 'tayori'); ?>
                </div>
                <div class="l-vertical-divide">
                  <div class="l-vertical-divide__col">
                    <div class="l-vertical-divide__col__inner">
                      <div class="form-group u-center">
                        <label class="mr-sm-1">PC :</label>
                        <select class="custom-select custom-select-sm" style="min-width:100px;padding:0 1.75rem 0.375rem 0.75rem;" name="form_setup_form[button_position_pc]" id="form_setup_form_button_position_pc">
                          <option <?php if ($tayori_data['button_position_pc'] == 1) { ?>selected="selected"<?php } ?> value="1"><?php echo __('Right', 'tayori'); ?></option>
                          <option <?php if ($tayori_data['button_position_pc'] == 2) { ?>selected="selected"<?php } ?> value="2"><?php echo __('Left', 'tayori'); ?></option>
                          <option <?php if ($tayori_data['button_position_pc'] == 3) { ?>selected="selected"<?php } ?> value="3"><?php echo __('Lower Right ', 'tayori'); ?></option>
                          <option <?php if ($tayori_data['button_position_pc'] == 4) { ?>selected="selected"<?php } ?> value="4"><?php echo __('Lower Left', 'tayori'); ?></option>
                        </select>
                      </div>
                      <div id="thumb_pc" class="mt-1 mb-1" style="margin: auto; max-width: 200px;">
                        <img src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-pc-pos-tab-right.png" width="200" />
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-pc-pos-tab-left.png" width="200" />
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-pc-pos-tab-right-bottom.png" width="200" />
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-pc-pos-tab-left-bottom.png" width="200" />
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-pc-pos-right-bottom.png" width="200" />
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-pc-pos-left-bottom.png" width="200" />
                      </div>
                    </div>
                  </div>
                  <div class="l-vertical-divide__divider"></div>
                  <div class="l-vertical-divide__col">
                    <div class="l-vertical-divide__col__inner">
                      <div class="form-group u-center">
                        <label class="mr-sm-1"><?php echo __('Smartphone', 'tayori'); ?> :</label>
                        <select class="custom-select custom-select-sm" style="min-width:100px;padding: 0 1.75rem 0.375rem 0.75rem;" name="form_setup_form[button_position_sp]" id="form_setup_form_button_position_sp">
                          <option <?php if ($tayori_data['button_position_sp'] == 1) { ?>selected="selected"<?php } ?> value="1"><?php echo __('Right', 'tayori'); ?></option>
                          <option <?php if ($tayori_data['button_position_sp'] == 2) { ?>selected="selected"<?php } ?> value="2"><?php echo __('Left', 'tayori'); ?></option>
                          <option <?php if ($tayori_data['button_position_sp'] == 3) { ?>selected="selected"<?php } ?> value="3"><?php echo __('Low', 'tayori'); ?></option>
                        </select>
                      </div>
                      <div id="thumb_sp" class="mt-2 mb-1" style="margin: auto; max-width: 44px;">
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-sp-pos-tab-right.png" width="54" />
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-sp-pos-tab-left.png" width="54" />
                        <img src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-sp-pos-tab-bottom.png" width="54" />
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-sp-pos-right-bottom.png" width="54" />
                        <img class="hider" src="<?php echo TAYORI_PLUGIN_URL; ?>/images/mock-btn-sp-pos-left-bottom.png" width="54" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-12 panel-group" style="text-align: center;">
    <button id="btn-submit" class="btn btn-success form-btn-common" type="button"><?php echo __('Setting Save', 'tayori'); ?></button>
  </div>
</div>

<?php
}

function tayori_mail_page() {
  $tayori = new Tayori;
  if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $validate_data = tayori_mail_validate($_POST);
    if ($validate_data && $validate_data['status'] == true) {
      if ($tayori->save($validate_data)) {
        $save_message = __('Saved data', 'tayori');
        $tayori_data = $tayori->get();
      }
      else {
        $save_message = __('Fatal Save data', 'tayori');
        $tayori_data = $tayori->get();
      }
    }
    else {
      $save_message = __('Fatal Save data', 'tayori');
      $tayori_data = $tayori->get();
    }
    $tayori_data = $validate_data['data'];
  }
  else {
    $tayori_data = $tayori->get();
  }
?>

<div class="wrap">
<!--//main-section-head-->
<div class="row">
  <div class="col-md-12 main-area-head">
    <h1 class="page-header color-title page-right-clm-title">
      <svg version="1.1" id="レイヤー_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
         y="0px" viewBox="0 0 34.015747 34.015747" style="width:27px;margin:0px 0px -4px -4px;fill:#43BFA0;enable-background:new 0 0 34.015747 34.015747;" xml:space="preserve">
      <style type="text/css">
        .st0{fill:#EFEFEF;}
        .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#5D6261;}
        .st2{fill:none;stroke:#5D6261;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
        .st3{fill:#5D6261;}
        .st4{fill:none;stroke:#5D6261;stroke-linecap:round;stroke-miterlimit:10;}
        .st5{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:#5D6261;stroke-linecap:round;stroke-miterlimit:10;}
        .st6{clip-path:url(#SVGID_2_);fill:#5D6261;}
        .st7{fill:#5D6260;}
        .st8{fill:#040000;}
      </style>
      <path class="st1" d="M33.331165,8.097595l-1.179504-1.179504c-0.778868-0.77887-2.037474-0.78302-2.830137,0.009644
        l-3.580139,3.580139l-6-7H8.744192c-1.10614,0-2.002808,0.898315-2.002808,2.007324v22.985352
        c0,1.108582,0.89093,2.007324,1.997437,2.007324h15.005188c1.103088,0,1.997375-0.898254,1.997375-1.990784V18.507874
        l7.580137-7.580139C34.105701,10.143555,34.107594,8.874023,33.331165,8.097595z M19.741385,5.007874l4.699951,5.5H20.73815
        c-0.546082,0-0.996765-0.45166-0.996765-1.008789V5.007874z M24.741385,28.514465c0,0.548462-0.447693,0.993408-0.999939,0.993408
        H8.741385c-0.545288,0-1-0.445679-1-0.995483V5.503296c0-0.540161,0.44574-0.995422,0.995605-0.995422h10.004395v4.99408
        c0,1.119385,0.894531,2.00592,1.997925,2.00592h4.002075l-10,10l-1,5l5-1l6-6V28.514465z M15.645132,22.118652l2.495361,2.495361
        l-3.134949,0.617371L15.645132,22.118652z M18.898428,23.957764l-2.605591-2.605591l11.292114-11.29364l2.59967,2.59967
        L18.898428,23.957764z M32.620533,10.219421l-1.729185,1.731262l-2.599365-2.599304l1.727356-1.7276
        c0.391357-0.391418,1.023926-0.388367,1.416565,0.003174l1.179808,1.176575
        C33.007618,9.194336,33.010487,9.828979,32.620533,10.219421z"/>
      </svg>
      <?php echo __('Mail Setting', 'tayori'); ?>
      </h1>
  </div>
</div>
<div id="form-setting-panel" class="panel panel-default panel-body sizer nopad">
  <form class="form-horizontal" id="form_setup_form" enctype="multipart/form-data" action="" accept-charset="UTF-8" method="post">
    <div class="pad15">
      <div class="p-project-group__item">
        <div class="p-project-group__item__inner">
          <div class="row">
            <div class="col-md-6">
              <label class="txt-larger3 mrt5 must-value"><?php echo __('Reception Mail', 'tayori'); ?><span>*</span></label>
              <input value="<?php echo $tayori_data['mail']; ?>" placeholder="mail@example.com" class="form-control" type="text" name="form_setup_form[mail]" id="form_setup_form_mail">
              <?php if ($validate_data && array_key_exists('mail', $validate_data['message'])) { ?>
                <p class="control-label"><?php echo $validate_data['message']['mail']; ?></p>
              <?php } ?>
            </div>
          </div>
          <div class="row" style="padding: 10px 0;">
            <div class="col-md-12 panel-group">
              <button class="btn btn-success form-btn-common mrtb20" onclick="this.disabled=true;this.value='...';this.form.submit();" type="submit"><?php echo __('Save', 'tayori'); ?></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php 
}

?>
