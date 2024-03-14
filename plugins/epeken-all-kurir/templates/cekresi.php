<?php
 $kec_url = admin_url('admin-ajax.php');
 wp_enqueue_script('ajax_cek_resi',plugins_url('/js/cekresi.js',__FILE__), array('jquery'));
 wp_enqueue_script('select2-js', plugins_url().'/woocommerce/assets/js/select2/select2.js');
 wp_enqueue_style('select2', plugins_url().'/woocommerce/assets/css/select2.css');
 wp_enqueue_style('jne', plugins_url().'/epeken-all-kurir/assets/jne.css');
 wp_localize_script( 'ajax_cek_resi', 'PT_Ajax_Cek_Resi', array(
 'ajaxurl'       => $kec_url,
 'nextNonce'     => wp_create_nonce('myajax-next-nonce'),
 ));
 get_header();
 $_noresi = $_GET["noresi"];
?>

 <div class="clearfix"> </div>
 <div id="cekresiwrapper">
  <div id="plakatcekresi">
   <div id="form_div">
     <div style="margin: 2px;">
      <input placeholder="<?php echo  __('Your tracking number','epeken-all-kurir');?>" type="text"  name="noresi" style="width: 60%;border: 1px solid #286090" id="noresi" value="<?php echo $_noresi; ?>"/>
      &nbsp;
	<select name="kurir" id="kurir" style="width: 100px;">
            <option value="jne">JNE</option>
	    <option value="tiki">TIKI</option>
   	    <option value="jnt">J&T</option>
	    <option value="wahana">WAHANA</option>
	    <option value="pos">POS</option>
	    <option value="sicepat">SICEPAT</option>
        </select>
     </div>
     <div class="clearfix">
      <button type="submit" class="btn button" style="margin-top: 10px;" id="cekbutton">Cek Resi</button>
     </div>
   </div>
  <div id="cekresiresult" style="width: 100%;">
        </div>
  </div>
 </div>
  <div class="clearfix"> </div>
<script type="text/javascript">
jQuery(document).ready(function($){
 $('#kurir').select2();
 do_cek_resi();
});     
</script>
<?php
 $shipping = WC_Shipping::instance();
 $methods = $shipping -> get_shipping_methods();
 $epeken_tikijne = $methods['epeken_courier'];
 if($epeken_tikijne -> settings['show_footer_in_cek_resi'] == "yes") {
  get_footer();
 }
?>
