<?php require 'header.php'; ?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">Dashboard</a>
    </li>
  </ul>
</div>

<?php
require 'adsense-now-options.php';
insertAlerts();
?>
<div class="col-md-12">
  <?php
  openRow();
  openCell("Settings for your AdSense Now! Plugin", 'cog', 7);
  ?>
  <p>This plugin, working in AdSense Now! mode, will let you place up to three ad blocks in your pages and posts. The ad blocks will all share the same format (ad size) and colors. All you have to do is to <a href="https://support.google.com/adsense/answer/181960" target="_blank" class="popup">generate</a> the ad code at your Google AdSense page, and paste it below. See the steps below.</p>
  <p>This plugin remembers your settings for each WordPress theme that you use. Currently, you are editing the settings for the theme <strong><code><?php echo $options['theme']; ?></code></strong>.</p>
  <p>More help is available. Click the button below to show or hide it.</p>
  <button id="showAdvanced" class="btn-sm btn-primary">Show More Help</button>
  <button id="hideHelp" class="btn-sm btn-warning">Hide Top Panels</button>
  <div class="clearfix visible-xs-block"></div>
  <?php
  closeCell();
  require 'box-optionset.php';
  closeRow();
  ?>
  <div id='advancedHelp'>
    <?php
    openBox("Advanced Set up");
    ?>
    <h4><?php _e('How to Set it up', 'easy-adsenser'); ?></h4>
    <ol>
      <li>
        <?php _e('<a href="https://support.google.com/adsense/answer/181960" target="_blank" class="popup">Generate</a> AdSense code (from your <a href="http://www.google.com/adsense" target="_blank" class="popup">AdSense homepage</a>, click on <strong>My ads</strong> tab, click on <strong>+New ad unit</strong>).', 'easy-adsenser'); ?>
      </li>
      <li>
        <?php _e('Cut and paste the AdSense code into the box below, deleting the existing text.', 'easy-adsenser'); ?>
      </li>
      <li>
        <?php _e('Decide how to align and show the code in your blog posts.', 'easy-adsenser'); ?>
      </li>
    </ol>
    <h4><?php _e('How to Control AdSense on Each Post', 'easy-adsenser'); ?></h4>
    <ul>
      <li>
        <?php _e('If you want to suppress AdSense in a particular post or page, give the <b><em>comment </em></b> <code>&lt;!--noadsense--&gt;</code> somewhere in its text.', 'easy-adsenser'); ?>
      </li>
      <li>
        <?php _e('Or, insert a <b><em>Custom Field</em></b> with a <b>key</b> <code>adsense</code> and give it a <b>value</b> <code>no</code>.', 'easy-adsenser'); ?>
      </li>
      <li>
        <?php _e('Other <b><em>Custom Fields</em></b> you can use to fine-tune how a post or page displays AdSense blocks are:', 'easy-adsenser'); ?>
        <ul>
          <li>
            <b>Keys</b>: <code>adsense-top</code>, <code>adsense-middle</code>, <code>adsense-bottom</code>, <code>adsense-widget</code>, <code>adsense-search</code>
          </li>
          <li>
            <b>Values</b>: <code>left</code>, <code>right</code>, <code>center</code>, <code>no</code>.
          </li>
        </ul>
      </li>
    </ul>

    <?php
    closeBox();
    ?>
  </div>
</div>
<div class="clearfix"></div>
<div id="left" class="col-md-6 col-sm-12 pull-left">
  <?php
  openBox(__('Ad Blocks in Your Posts', 'adsense-now'));
  ?>
  <p><?php
    _e('Cut and paste your ad code from your Google AdSense homepage. It will appear in your posts and pages.', 'adsense-now');
    ?></p>
  <?php
  echo EzGA::renderOptionCell('ad_text', $ezOptions['ad_text']);
  closeBox();
  require 'box-ad-alignment.php';
  ?>
</div>
<div class="clearfix visible-xs-block"></div>
<div id="right" class="col-md-6 col-sm-12">
  <?php
  require 'box-suppressing-ads.php';
  require 'box-more-info.php';
  ?>
</div>
<div class="clearfix visible-xs-block"></div>
<script>
  var xeditHandler = 'ajax/options.php';
  var xparams = {};
  xparams.plugin_slug = '<?php echo $options['plugin_slug']; ?>';
  xparams.theme = '<?php echo $options['theme']; ?>';
  xparams.provider = '<?php echo $options['provider']; ?>';
  xparams.optionset = '<?php echo $options['optionset']; ?>';
  $("#showAdvanced").click(function (e) {
    e.preventDefault();
    $("#advancedHelp").find(".btn-minimize").click();
    if ($(this).text() === 'Show More Help')
      $(this).text('Hide Help');
    else
      $(this).text('Show More Help');
  });
  $("#hideHelp").click(function (e) {
    e.preventDefault();
    $(this).closest(".row").find(".glyphicon-chevron-up").click();
  });
  $(document).ready(function () {
    setTimeout(function () {
      $("#advancedHelp").find(".btn-minimize").click();
    }, 10);
  });
</script>
<?php
require_once 'footer.php';
