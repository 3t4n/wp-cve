<div class="sbs-6310-header">
<ul class="sbs-6310-nav">
    <li>
      <a href="<?php echo admin_url("admin.php?page=sbs-6310-service-box"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-service-box') echo "sbs-6310-active" ?>">All Service Box</a>
    </li>
    <li class="has-dropdown">
      <a class="<?php if(isset($_GET['page']) && ($_GET['page'] == 'sbs-6310-template-01-10' || $_GET['page'] == 'sbs-6310-template-11-20'|| $_GET['page'] == 'sbs-6310-template-21-30'|| $_GET['page'] == 'sbs-6310-template-31-40'|| $_GET['page'] == 'sbs-6310-template-41-50')) echo "sbs-6310-active" ?>">All Templates</a>
      <ul class="dropdown-menu">
        <li>
          <a href="<?php echo admin_url("admin.php?page=sbs-6310-template-01-10"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-template-01-10') echo "sbs-6310-active" ?>">Team Template 01-10</a>
        </li>
        <li>
          <a href="<?php echo admin_url("admin.php?page=sbs-6310-template-11-20"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-template-11-20') echo "sbs-6310-active" ?>">Team Template 11-20</a>
        </li>
        <li>
          <a href="<?php echo admin_url("admin.php?page=sbs-6310-template-21-30"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-template-21-30') echo "sbs-6310-active" ?>">Team Template 21-30</a>
        </li>
        <li>
          <a href="<?php echo admin_url("admin.php?page=sbs-6310-template-31-40"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-template-31-40') echo "sbs-6310-active" ?>">Team Template 31-40</a>
        </li>
        <li>
          <a href="<?php echo admin_url("admin.php?page=sbs-6310-template-41-50"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-template-41-50') echo "sbs-6310-active" ?>">Team Template 41-50</a>
        </li>
      </ul>
    </li>
    <li>
      <a href="<?php echo admin_url("admin.php?page=sbs-6310-service-box-manage-items"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-service-box-manage-items') echo "sbs-6310-active" ?>">Manage Items</a>
    </li>
    <li>
      <a href="<?php echo admin_url("admin.php?page=sbs-6310-service-box-license"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-service-box-license') echo "sbs-6310-active" ?>">License</a>
    </li>
    <li>
      <a href="<?php echo admin_url("admin.php?page=sbs-6310-service-box-use"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-service-box-use') echo "sbs-6310-active" ?>">Help</a>
    </li>
    <li>
      <a href="<?php echo admin_url("admin.php?page=sbs-6310-wpmart-plugins"); ?>" class="<?php if(isset($_GET['page']) && $_GET['page'] == 'sbs-6310-wpmart-plugins') echo "sbs-6310-active" ?> sbs-6310-plugin-menu">WpMart Plugins</a>
    </li>
    <li>
      <a href="https://wpmart.org/downloads/service-box/" target="_blank" class="sbs-6310-pro">Upgrade To Pro<i class="fas fa-star"></i></a>
    </li>
  </ul>
  <h3>
    <span class="dashicons dashicons-flag"></span>
    Notifications
  </h3>
  <p>Thank you for using the "Service box slider" plugin free version. I Just wanted to see if you have any questions or concerns about my plugins. If you do, Please do not hesitate to <a href="https://wordpress.org/support/plugin/service-box-with-slider/" target="_blank">file a bug report</a></p>
  <p>By the way, did you know we also have a <a href="https://wpmart.org/downloads/service-box/" target="_blank">Premium Version</a>? It offers 50+ templates with exclusive CSS3 effects. It also comes with 16/7 personal support.</p>
  <p>Thank you Again!</p>
  <p></p>
</div>
<?php sbs_6310_service_with_slider_install() ?>