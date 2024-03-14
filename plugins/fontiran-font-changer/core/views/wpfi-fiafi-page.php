<?php defined('FIRAN_VERSION') OR exit('Go way!');

$font_list = $this->fonts;
wp_enqueue_style("fontiran_prestyle", FIRAN_URL . 'fontiran.css');

?>
 <section id="wpfi-box-fonts-wlists" class="firan-section box-fonts-lists">
  <div class="box-content" style="padding:0;">
  
   <div class="wrap" id="fontiran" style="margin: 0;">
   
        <table class="wp-list-table widefat fixed striped posts" cellspacing="0" cellpadding="5" style="width: 100%;">
         <thead>
          <th>نام</th>
          <th>مشخصات</th>
          <th>پیش نمایش</th>
          <th class="del">زدودن</th>
         </thead>
         <tbody>
		  <?php
		  if(isset($font_list[0])){
		  for($i=0;$i<count($font_list);$i++) {
				$font_name = $font_list[$i]['name'];
			echo '<style type="text/css">#preview_'.$font_name. $i .' {font-family: '.$font_name.'; font-weight: '.$font_list[$i]['weight'].';}</style>';
		?>

          <tr>
           <td><?php echo ucwords($font_name); ?></td>
           <td><span id="fi-font-weight"><?php echo $font_list[$i]['weight']; ?></span><span id="fi-font-style"><?php echo $font_list[$i]['style']; ?></td>
           <td><span id="preview_<?php echo $font_name . $i; ?>" class="font-prv"> چو <strong>ایران</strong> نباشد تن من مباد </span></td>
           <td class="del"><span class="fi_font_del font-del" id="del_<?php echo $font_name; ?>">زدودن</span></td>
          </tr>

          <?php } }?>
         </tbody>
        </table>

      
<script type="text/javascript" charset="utf8">
jQuery(document).ready(function($) {
	
	// delete
	jQuery('.fi_font_del').click(function() {
		var font_name = jQuery(this).attr('id').substr(4),
			font_weight = jQuery(this).closest('tr').find('#fi-font-weight').text(),	
			font_style = jQuery(this).closest('tr').find('#fi-font-style').text(),
			font_tr = jQuery(this);

		if(confirm('آیا می خواهید فونت ' + font_name +'  را پاک کنید؟')) {
			var data = {
				action: 'fi_delete_webfont',
				font_name: font_name,
				font_weight: font_weight,
				font_style : font_style
			};
			
			jQuery.post(ajaxurl, data, function(response) {				
				if( jQuery.trim(response) == 'success') {
					font_tr.closest('tr').slideUp(function() {
						font_tr.closest('tr').remove();
					});
				}
				else {
					console.log(response);
					alert('یک چیزی اشتباه است');
				}
			});	
		}
	});
});
</script>
   

   </div>
  </div>
 </section>

