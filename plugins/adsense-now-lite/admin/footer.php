<!-- content ends -->
</div><!--/#content.col-md-0-->
</div><!--/fluid-row-->
<hr>

<footer class="row">
  <p class="col-md-4 col-sm-4 col-xs-12 copyright">&copy; <a href="http://www.thulasidas.com" target="_blank">Manoj Thulasidas</a> 2013 - <?php echo date('Y') ?></p>
  <p class="col-md-4 col-sm-4 col-xs-12"><a href='http://www.thulasidas.com/packages/ezpaypal' class='popup-tall'><img class="col-md-4 col-sm-6 col-xs-4 center" src="<?php echo $ezAdminUrl; ?>img/paypal-partner.png" alt="Official PayPal Partner" title="This developer is an official PayPal partner" data-toggle="tooltip"/></a></p>
  <p class="col-md-4 col-sm-4 col-xs-12 powered-by pull-right"><a class='popup' href="http://www.thulasidas.com/adsense">AdSense Plugins</a> by <a href="http://ads-ez.com/" target="_blank">Ads EZ Classifieds</a></p>
</footer>
</div><!--/.fluid-container-->

<!-- external javascript -->
<script src="<?php echo $ezAdminUrl; ?>js/ez-admin.js"></script>
<script src="<?php echo $ezAdminUrl; ?>js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo $ezAdminUrl; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $ezAdminUrl; ?>js/bootstrap-editable.min.js"></script>
<script src="<?php echo $ezAdminUrl; ?>js/bootstrap-tour.min.js"></script>
<script src="<?php echo $ezAdminUrl; ?>js/fileinput.min.js"></script>
<script src="<?php echo $ezAdminUrl; ?>js/bootbox.min.js"></script>
<!-- application specific -->
<script src="<?php echo $ezAdminUrl; ?>js/charisma.js"></script>
<?php
if (!empty(EzGA::$options['show_google_translate'])) {
  ?>
  <!-- Google translator -->
  <script type='text/javascript'>
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
    }
  </script>
  <script type='text/javascript' src='//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'></script>
  <!-- Exceptions to Google translator -->
  <script>
  $(document).ready(function () {
    $("code, pre").addClass('notranslate');
    $("a.xedit[data-type='text']").addClass('notranslate');
    $("a.xedit[data-type='textarea']").addClass('notranslate');
    $("a.xedit:not([data-type])").addClass('notranslate');
  });
  </script>
  <?php
}
?>
</body>
</html>
