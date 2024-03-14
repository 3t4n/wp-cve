<?php
if (!class_exists("EzPlugin")) {

  class EzPlugin {

    var $name, $key, $slogan, $file, $class;
    var $adminLogo, $mainLogo;
    var $isPro, $strPro, $plgDir, $plgURL;
    var $css = array();
    var $wpRoot, $keyEp, $endPoint, $siteUrl;
    var $lang = 'en';
    static $db = false;

    function __construct($file) { //constructor
      if (defined('ABSPATH')) {
        $this->file = $file;
        $this->plgDir = dirname($file);
        $this->plgURL = plugin_dir_url($file);
        $this->siteUrl = network_site_url();
        $this->wpRoot = parse_url($this->siteUrl, PHP_URL_PATH);
        if (empty($this->wpRoot) || $this->wpRoot == DIRECTORY_SEPARATOR) {
          $this->wpRoot = "";
        }
        else {
          $this->wpRoot .= DIRECTORY_SEPARATOR;
        }
        $this->siteUrl = trailingslashit($this->siteUrl);
        $this->isPro = file_exists("{$this->plgDir}/admin/options-advanced.php");
        $this->mkEndPoint();
      }
      if ($this->isPro) {
        $this->strPro = 'Pro';
      }
      else {
        $this->strPro = 'Lite';
      }
    }

    function mkEndPoint() {
      $this->keyEp = $this->key . '-ep';
      $siteUrl = $this->siteUrl;
      if (defined('SUBDOMAIN_INSTALL')) {
        if (SUBDOMAIN_INSTALL) {
          $siteUrl = site_url();
        }
      }
      if (function_exists('get_sites')) {
        $info = get_sites();
        if (!empty($info[1]) && $info[1]->path == "/") {
          $siteUrl = site_url();
        }
      }
      else if (function_exists('wp_get_sites')) {
        $info = wp_get_sites();
        if (!empty($info[1]) && $info[1]['path'] == "/") {
          $siteUrl = site_url();
        }
      }
      $siteUrl = trailingslashit($siteUrl);
      $this->endPoint = $siteUrl . $this->keyEp;
    }

    static function isEmptyHtaccess($data) {
      if (empty($data)) {
        return true;
      }
      $lines = explode("\n", $data);
      foreach ($lines as $l) {
        $l = trim($l);
        if (empty($l)) {
          continue;
        }
        if ($l[0] == '#') {
          continue;
        }
        return false;
      }
      return true;
    }

    function getServerSoftware() {
      $server = "unknown";
      if (!empty($_SERVER['SERVER_SOFTWARE'])) {
        $serverSoftware = strtolower($_SERVER['SERVER_SOFTWARE']);
      }
      else {
        $serverSoftware = "";
      }
      if (strpos($serverSoftware, "apache") !== false) {
        $server = "apache";
      }
      if (strpos($serverSoftware, "nginx") !== false) {
        $server = "nginx";
      }
      if (strpos($serverSoftware, "microsoft") !== false) {
        $server = "microsoft";
      }
      return $server;
    }

    function getNginxMsg() {
      $data = 'location / {
        try_files $uri $uri/ /index.php?q=$request_uri;
}';
      return "<p>You are running on <code>nginx</code>. You may have to edit your config file after enabling Permalinks. Please see <a href='https://codex.wordpress.org/Nginx' target='_blank' class='popup-long'>Nginx instructions</a>. In most cases, all you have to do is to add this to your <code>location</code> block:</p><pre>$data</pre>";
    }

    function getMSMsg() {
      return "<p>You are running on a Microsoft server. Please enable and configure Permalinks. Here is <a href='http://www.iis.net/learn/extensions/url-rewrite-module/enabling-pretty-permalinks-in-wordpress' target='_blank' class='popup-long'>how to do it</a>.";
    }

    function getApacheMsg() {
      return "<p>You are running on an Apache or a generic server. Please enable and configure Permalinks. Add the <a href='https://codex.wordpress.org/htaccess' target='_blank' class='popup-long'>standard WordPress directives</a> to your <code>.htaccess</code> file or equivalent.";
    }

    function checkPerma() {
      $permaStructure = get_option('permalink_structure');
      if (!empty($permaStructure)) {
        return;
      }
      $server = $this->getServerSoftware();
      $msg = "";
      switch ($server) {
        case "nginx":
          return $this->getNginxMsg();
        case "microsoft":
          return $this->getMSMsg();
        default:
          $msg = "<p>Depending on your server, follow the steps below.</p>";
          $msg .= $this->getNginxMsg();
          $msg .= $this->getMSMsg();
          $msg .= $this->getApacheMsg();
        case "apache":
          $file = ABSPATH . ".htaccess";
          if (empty($this->wpRoot)) {
            $wpRoot = "/";
          }
          else {
            $wpRoot = $this->wpRoot;
          }
          $data = "
# BEGIN WordPress: Inserted by $this->name
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $wpRoot
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$wpRoot}index.php [L]
</IfModule>
# END WordPress: Inserted by $this->name
";
          if (file_exists($file)) {
            // Dangerous to create
            $currentData = file_get_contents($file);
            if (!self::isEmptyHtaccess($currentData)) {
              $currentData = htmlspecialchars($currentData);
              $data = htmlspecialchars($data);
              return "$msg<p>You already have an <code>.htaccess</code> file (<code>$file</code>) with these contents:</p><pre>$currentData</pre><p>Please edit it and add the <a href='https://codex.wordpress.org/htaccess' target='_blank' class='popup-long'>standard WordPress directives</a>  (pointing all missing files to <code>index.php</code>). Here is what you need to add:</p><pre>$data</pre>";
            }
          }
          // No htaccess or it is empty. Safe to create a default one.
          $url = wp_nonce_url('plugin-install.php', 'plugin-install');
          $creds = request_filesystem_credentials($url, '', false, false, ABSPATH);
          if ($creds !== false) {
            WP_Filesystem($creds);
            global $wp_filesystem;
            if (!empty($wp_filesystem)) {
              $abspath = trailingslashit($wp_filesystem->abspath());
              $file = "{$abspath}.htaccess";
              if ($wp_filesystem->put_contents($file, "$currentData\n$data")) {
                return "$msg<p>A default <code>.htaccess</code> has been created for you.</p>";
              }
            }
          }
          else {
            // Cannot create a new one.
            $data = htmlspecialchars($data);
            return "$msg<p>You do not have an <code>.htaccess</code> file and it does not look like I can create one for you. Please create <code>$file</code> and add the <a href='https://codex.wordpress.org/htaccess' target='_blank' class='popup-long'>standard WordPress directives</a> (pointing all missing files to <code>index.php</code>). Here is what you need to add:</p><pre>$data</pre>";
          }
          break;
      }
    }

    static function install($dir, $mOptions) {
      $ezOptions = get_option($mOptions);
      if (empty($ezOptions)) {
        // create the necessary tables
        $GLOBALS['isInstallingWP'] = true;
        chdir($dir . '/admin');
        require_once('dbSetup.php');
        $ezOptions['isSetup'] = true;
      }
      update_option($mOptions, $ezOptions);
    }

    static function uninstall($mOptions) {
      delete_option($mOptions);
    }

    static function getDB() {
      if (empty(self::$db)) {
        require_once 'DbHelper.php';
        self::$db = new DbHelper();
      }
      return self::$db;
    }

    function printAdminPage() {
      ?>
      <div id="loading" class="updated"><h2><img src="<?php echo $this->plgURL; ?>/admin/img/loading.gif" alt="Loading">&emsp; Loading... Please wait!</h2></div>
      <?php
      $permaMsg = $this->checkPerma();
      $permalink = admin_url('options-permalink.php');
      if (!empty($permaMsg)) {
        ?>
        <div class='error' style='padding:10px;margin:10px;color:#a00;font-weight:500;background-color:#fee;display:none' id="permalinks">
          <p><strong>Permalinks</strong> are not enabled on your blog, which this plugin needs. Please <a href='<?php echo $permalink; ?>'>enable a permalink structure</a> for your blog from <strong><a href='<?php echo $permalink; ?>'>Settings &rarr; Permalinks</a></strong>. Any structure (other than the ugly default structure using <code><?php echo network_site_url(); ?>/?p=123</code>) will do.</p>
          <?php echo $permaMsg; ?>
        </div>
        <?php
      }
      else {
        ?>
        <div class='error' style='padding:10px;margin:10px;color:#a00;font-weight:500;background-color:#fee;display:none' id="adBlocked">
          <?php
          $server = $this->getServerSoftware();
          echo "<p><strong>Permalink Configuration</strong>:  <em>This step is <strong>not</strong> optional.</em> This plugin requires Permalinks to be enabled and configured. Looks like they are enabled, but not fully configured. In most cases, you just need to visit <a href='$permalink'>Settings &rarr; Permalinks</a> and hit the <strong>Save Changes</strong> button to configure them.</p>";
          switch ($server) {
            case "nginx":
              echo $this->getNginxMsg();
              break;
            case "microsoft":
              echo $this->getMSMsg();
              break;
            case "apache":
              echo $this->getApacheMsg();
              break;
            default:
              echo "<p>Depending on your server, follow the steps below.</p>";
              echo $this->getNginxMsg();
              echo $this->getMSMsg();
              echo $this->getApacheMsg();
          }
          ?>
          <p><strong>AdBlock</strong>: This plugin loads its admin pages in an iFrame, which may look like an ad to some browser-side ad blockers. If you are running AdBlock or similar extensions on your browser, please disable it for your blog domain so that the admin page is not blocked. Looks like your browser is preventing the admin pages from being displayed.</p>
          <p>
            If you think this message is in error, and would like the plugin to try to open the admin page any way, please click the button below:
          </p>
          <form method="post">
            <input type="submit" value="Force Admin Page" name="ez_force_admin">
          </form>
          <p>
            <strong>
              Note that if the plugin still cannot load the admin page after forcing it, you may see a blank or error page here upon reload. If that happens, please deactivate and delete the plugin. It is not compatible with your blog setup.
            </strong>
          </p>
        </div>
        <?php
      }
      if (!empty($_POST['ez_force_admin'])) {
        update_option('ez_force_admin', true);
      }
      $forceAdmin = get_option('ez_force_admin');
      $lastSrc = get_option("$this->key-last-src");
      $lastTarget = $this->plgDir . "/admin/" . str_replace('.ezp', '.php', $lastSrc);
      if (!empty($_REQUEST['target'])) {
        $src = "{$this->endPoint}/admin/{$_REQUEST['target']}";
      }
      else if (!empty($lastSrc) && file_exists($lastTarget)) {
        $src = "{$this->endPoint}/admin/$lastSrc";
      }
      else {
        $src = "{$this->endPoint}/admin/index.ezp";
      }
      ?>
      <script>
        var errorTimeout;
        function calcHeight() {
          var w = window,
                  d = document,
                  e = d.documentElement,
                  g = d.getElementsByTagName('body')[0],
                  y = w.innerHeight || e.clientHeight || g.clientHeight;
          document.getElementById('the_iframe').height = y - 70;
        }
        if (window.addEventListener) {
          window.addEventListener('resize', calcHeight, false);
        }
        else if (window.attachEvent) {
          window.attachEvent('onresize', calcHeight);
        }
        jQuery(document).ready(function () {
      <?php
      if (empty($forceAdmin)) {
        ?>
            errorTimeout = setTimeout(function () {
              jQuery("#the_iframe").fadeOut();
              jQuery("#adBlocked, #permalinks").fadeIn();
            }, 8000);
        <?php
      }
      ?>
          jQuery("#loading").delay(10000).fadeOut();
        });
      </script>
      <?php
      echo "<iframe src='$src' frameborder='0' style='width:100%;position:absolute;top:5px;left:-10px;right:0px;bottom:0px;'  width='100%' height='900px' id='the_iframe' onLoad='calcHeight();'></iframe>";
    }

    function parseRequest(&$wp) {
      if (strpos($_SERVER['REQUEST_URI'], $this->keyEp) === false) {
        return;
      }
      $request = $_SERVER['REQUEST_URI'];
      if (!empty($this->wpRoot)) {
        $request = str_replace($this->wpRoot, "", $_SERVER['REQUEST_URI']);
      }
      $request = trim($request, DIRECTORY_SEPARATOR . "\\/");
      if (strpos($request, $this->keyEp) !== 0) {
        return;
      }
      $request = preg_replace('/\?.*/', '', $request);
      $request = preg_replace("/$this->keyEp/", basename($this->plgDir), $request, 1);
      $target = WP_PLUGIN_DIR . "/" . $request;
      $target = preg_replace("/\.ezp$/", ".php", $target, 1);
      if (file_exists($target)) {
        chdir(dirname($target));
        $ext = pathinfo($target, PATHINFO_EXTENSION);
        if ($ext == 'php') {
          include $target;
        }
        else {
          $url = str_replace(ABSPATH, $this->siteUrl, $target);
          header("location: $url");
        }
        exit();
      }
      else {
        setcookie('ez-last-request', $request, time() + 30);
        $url = "$this->siteUrl$this->keyEp/admin/index.ezp";
        header("location: $url");
        exit();
      }
    }

    function pluginActionLinks($links, $file) {
      if ($file == plugin_basename($this->file)) {
        $settings_link = "<a href='options-general.php?page="
                . basename($this->file)
                . "'>Settings</a>";
        array_unshift($links, $settings_link);
        if (empty($this->isPro) && !empty($this->key)) {
          $buyLink = "<a href='http://buy.thulasidas.com/$this->key' class='popup'><b style='color:red'>Go <i>Pro</i>!</b></a>";
          array_unshift($links, $buyLink);
        }
      }
      return $links;
    }

    function adminMenu() {
      $mName = "$this->name $this->strPro";
      $adminPage = add_options_page($mName, $mName, 'activate_plugins', basename($this->file), array($this, 'printAdminPage'));
      add_action('load-' . $adminPage, array($this, 'load'));
    }

    function setGoogleTranCookie() {
      if (!empty($_GET['lang'])) {
        $lang = $_GET['lang'];
      }
      else {
        $locale = get_locale();
        $lang = substr($locale, 0, 2);
      }
      $lang = strtolower($lang);
      if ($lang == 'en') {
        setcookie("googtrans", "", 1, '/');
      }
      else {
        setcookie("googtrans", "/en/$lang", time() + 300, '/');
      }
      $this->lang = $lang;
    }

    function init() {
      add_action('parse_request', array($this, 'parseRequest'));
      add_action('admin_menu', array($this, 'adminMenu'));
      add_filter('plugin_action_links', array($this, 'pluginActionLinks'), -10, 2);
      register_activation_hook($this->file, array($this->class, 'install'));
      register_deactivation_hook($this->file, array($this->class, 'uninstall'));
    }

    function load() { // Runs inits specific to the admin page (JS/CSS etc.)
      $this->setGoogleTranCookie();
    }

  }

} //End Class EzPlugin
