<?php
require_once 'lock.php';
include_once('../debug.php');
$GLOBALS['ezAdminUrl'] = $ezAdminUrl = plugins_url("/admin/", __DIR__);

function insertAlerts($width = 12) {
  ?>
  <div style="display:none" class="alert alert-info col-lg-<?php echo $width; ?>" role="alert">
    <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <span id="alertInfoText"></span>
  </div>
  <div style="display:none" class="alert alert-success col-lg-<?php echo $width; ?>" role="alert">
    <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <span id="alertSuccessText"></span>
  </div>
  <div style="display:none" class="alert alert-warning col-lg-<?php echo $width; ?>" role="alert">
    <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <span id="alertWarningText"></span>
  </div>
  <div style="display:none" class="alert alert-danger col-lg-<?php echo $width; ?>" role="alert">
    <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <span id="alertErrorText"></span>
  </div>
  <?php
}

function openRow($help = "") {
  if (empty($help)) {
    $help = "You can roll-up or temporarily suppress this box. For more help, click on the friendly Help button near the top right corner of this page, if there is one.";
  }
  else {
    ?>
    <a href="#" class="btn btn-primary btn-help" style="float:right" data-content="<?php echo $help; ?>"><i class="glyphicon glyphicon-question-sign large"></i> Help</a>
  <?php }
  ?>
  <div class="row">
    <?php
    return $help;
  }

  function closeRow() {
    ?>
  </div><!-- row -->
  <?php
}

function openCell($title, $icon = "edit", $size = "12", $help = "", $minWidth = "") {
  if (empty($help)) {
    $help = "You can roll-up or temporarily suppress this box. For more help, click on the friendly Help button near the top right corner of this page, if there is one.";
  }
  if (!empty($minWidth)) {
    $minWidth = "style='min-width:{$minWidth}px !important'";
  }
  ?>
  <div class="box col-lg-<?php echo $size; ?>" <?php echo $minWidth; ?>>
    <div class="box-inner" <?php echo $minWidth; ?>>
      <div class="box-header well" data-original-title="">
        <h2>
          <i class="glyphicon glyphicon-<?php echo $icon; ?>"></i>
          <?php echo $title; ?>
        </h2>
        <div class="box-icon">
          <a href="#" class="btn btn-help btn-round btn-default"
             data-content="<?php echo $help; ?>">
            <i class="glyphicon glyphicon-question-sign"></i>
          </a>
          <a href="#" class="btn btn-minimize btn-round btn-default">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </a>
          <a href="#" class="btn btn-close btn-round btn-default">
            <i class="glyphicon glyphicon-remove"></i>
          </a>
        </div>
      </div>
      <div class="box-content" <?php echo $minWidth; ?>>
        <?php
      }

      function closeCell() {
        ?>
      </div>
    </div>
  </div><!-- box -->
  <?php
}

function openBox($title, $icon = "edit", $size = "12", $help = "") {
  $help = openRow($help);
  openCell($title, $icon, $size, $help);
}

function closeBox() {
  closeCell();
  closeRow();
}

function showScreenshot($id) {
  $img = "../screenshot-$id.png";
  $iSize = getimagesize($img);
  $width = $iSize[0] . 'px';
  echo "<img src='$img' alt='screenshot' class='col-sm-12' style='max-width:$width'>";
}

function getHeader() {
  global $ezAdminUrl;
  ob_start();
  $plgName = EzGA::getPlgName(__DIR__);
  $plgSlug = EzGA::getSlug();
  $plgModeName = EzGA::$pluginModes[$plgSlug];
  $isPro = EzGA::$isPro;
  $plgPrice = EzGA::$plgPrice;
  if ($isPro && !empty(EzGA::$options['eztheme'])) {
    $themeCSS = $ezAdminUrl . "css/bootstrap-" . strtolower(EzGA::$options['eztheme']) . ".min.css";
  }
  else {
    $themeCSS = $ezAdminUrl . "css/bootstrap-default.min.css";
  }
  ?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <title><?php echo $plgName; ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="Ads EZ Plugin for Google AdSense">
      <meta name="author" content="Manoj Thulasidas">

      <!-- The styles -->
      <link id="bs-css" href="<?php echo $themeCSS; ?>" rel="stylesheet">
      <link href="<?php echo $ezAdminUrl; ?>css/bootstrap-editable.css" rel="stylesheet">
      <link href="<?php echo $ezAdminUrl; ?>css/charisma-app.css" rel="stylesheet">
      <link href='<?php echo $ezAdminUrl; ?>css/bootstrap-tour.min.css' rel='stylesheet'>
      <link href="<?php echo $ezAdminUrl; ?>css/font-awesome.min.css" rel="stylesheet">
      <link href="<?php echo $ezAdminUrl; ?>css/fileinput.min.css" rel="stylesheet">
      <link href="<?php echo $ezAdminUrl; ?>css/bootstrap-colorpicker.min.css" rel="stylesheet">
      <link href="<?php echo $ezAdminUrl; ?>css/ez-admin.css" rel="stylesheet">
      <style type="text/css">
        .popover{width:600px;}
        <?php
        if (empty(EzGA::$options['breadcrumbs'])) {
          ?>
          .breadcrumb {display:none;}
          <?php
        }
        ?>
      </style>
      <!-- jQuery -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

      <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->

      <!-- The fav icon -->
      <link rel="shortcut icon" href="img/favicon.ico">

    </head>

    <body>
      <script>
        parent.clearTimeout(parent.errorTimeout);
      </script>
      <!-- topbar starts -->
      <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
          <a id="index" class="navbar-brand" href="index.php"> <img alt="Ads EZ Logo" src="<?php echo $ezAdminUrl; ?>img/ads-ez.png" class="hidden-xs"/>
            <span><?php echo $plgName; ?></span></a>
          <div class="btn-group pull-right">
            <?php
            $standaloneURL = network_site_url() . "/ezadsense-ep/admin/index.php";
            ?>
            <a id="standAloneMode" href="<?php echo $standaloneURL; ?>" target="_blank" data-content="Open <?php echo $plgName; ?> admin apge in a new window independent of WordPress admin interface. The standalone mode still uses WP authentication, and cannot be accessed unless logged in." data-toggle="popover" data-trigger="hover" data-placement="left"  title='Standalone Admin Screen'><span class="btn btn-info"><i class="glyphicon glyphicon-resize-full"></i> Standalone Mode</span></a>
            <a id="update" href="update.php" data-content="If you would like to check for regular updates, or install a purchased module or Pro upgrade, visit the Updates page." data-toggle="popover" data-trigger="hover" data-placement="left" title='Updates Page'><span class="btn btn-info"  ><i class="fa fa-cog fa-spin"></i> Updates
                <?php
                if (!$isPro) {
                  ?>
                  &nbsp;<span class="badge red">Pro</span>
                  <?php
                }
                ?>
              </span>
            </a>&nbsp;
              <?php
              if (!empty(EzGA::$options['show_google_translate'])) {
                ?>
                <span id='GoogleTranslatorWidget' style='display:inline-block'>
                  <span id='google_translate_element'></span>
                </span>
                <?php
              }
              ?>
          </div>
        </div>
      </div>
      <!-- topbar ends -->
      <div class="ch-container">
        <div class="row">
          <!-- left menu starts -->
          <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
              <div class="nav-canvas">
                <div class="nav-sm nav nav-stacked">

                </div>
                <ul class="nav nav-pills nav-stacked main-menu">
                  <li id="dashboard"><a href="index.php"><i class="glyphicon glyphicon-home"></i><span> Dashboard</span></a>
                  </li>
                  <?php
                  if ($isPro) {
                    $optionSets = EzGA::getOptionSets();
                    echo <<<EOF
                  <li class="accordion">
                    <a id='$plgSlug' href="#"><i class="glyphicon glyphicon-plus"></i><span> $plgModeName</span></a>
                    <ul class="nav nav-pills nav-stacked">
                      <li id='$plgSlug-admin'><a href="$plgSlug-admin.php"><i class="glyphicon glyphicon-check"></i><span> Settings</span></a></li>
EOF;
                    foreach ($optionSets as $o) {
                      if (in_array($o, EzGA::$unSettable)) {
                        $verb = 'Edit ';
                      }
                      else {
                        $verb = 'Switch to ';
                      }
                      echo <<<EOF
                  <li id="$plgSlug-$o"><a href="$plgSlug-admin.php?optionset=$o"><i class="glyphicon glyphicon-export red"></i><span> $verb <code>$o</code></span></a></li>
EOF;
                    }
                    echo <<<EOF
                  <li id="$plgSlug-add"><a href="optionset.php"><i class="glyphicon glyphicon-export red"></i><span> Manage Option Sets</span></a></li>
                    </ul>
                  </li>
EOF;
                    ?>
                    <li id='pro'><a href="pro.php" class="red"><i class="glyphicon glyphicon-cog"></i><span><b> Pro Features</b></span></a></li>
                    <?php
                  }
                  else {
                    echo <<<EOF
                    <li id='$plgSlug-admin'><a href="$plgSlug-admin.php"><i class="glyphicon glyphicon-check"></i><span> $plgModeName</span></a></li>
EOF;
                    ?>
                    <li id='goPro'><a href="pro.php" class="red goPro" data-toggle="popover" data-trigger="hover" data-content="Get the Pro version of <?php echo $plgModeName; ?> for <i>only</i> $<?php echo $plgPrice[$plgSlug]; ?>. Tons of extra features. Instant download." data-placement="right" title="Upgrade to Pro" data-product="<?php echo $plgSlug; ?>"><i class="glyphicon glyphicon-shopping-cart"></i><span><b> Go Pro!</b></span></a></li>
                    <?php
                  }
                  ?>
                  <li id="options"><a href="options.php"><i class="glyphicon glyphicon-cog"></i><span> Options</span></a></li>
                  <li id="i18n"><a href="i18n.php"><i class="glyphicon glyphicon-globe"></i><span> Languages</span></a></li>
                </ul>
              </div>
            </div>
          </div>
          <!--/span-->
          <!-- left menu ends -->

          <noscript>
          <div class="alert alert-block col-md-12">
            <h4 class="alert-heading">Warning!</h4>

            <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
              enabled to use this site.</p>
          </div>
          </noscript>

          <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            <?php
            $header = ob_get_clean();
            return $header;
          }

          EzGA::verifyOptionSets();
          if (!empty($_REQUEST['optionset'])) {
            $optionset = $_REQUEST['optionset'];
            EzGA::setOptionset($optionset);
          }
          else {
            $optionset = EzGA::getGenOption('editing');
            if (empty($optionset)) {
              $optionset = 'Default';
            }
          }
          $options = EzGA::getOptions($optionset);

          $header = getHeader($options);
          if (method_exists('EzGA', 'toggleMenu')) {
            $header = EzGA::toggleMenu($header);
          }
          http_response_code(200);
          echo $header;

      if (version_compare(PHP_VERSION, "5.4.0", "<")) {
        $warning = EzGA::getPlgName() . " requires PHP V5.4 or higher for some of its advanced features."
                . " You are using PHP Version " . PHP_VERSION
                . ". Please get your PHP upgraded on your server.";
        EzGA::flashWarning($warning, true);
      }

