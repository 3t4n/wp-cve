<?php

if(!file_exists(FIRAN_DATA .'/fonts')) {
	mkdir(FIRAN_DATA .'/fonts');
}
		
if(!is_writable(FIRAN_DATA .'/fonts')) { ?>
 <section id="wphb-box-dashboard-welcome" class="firan-section box-dashboard-welcome">
  <div class="box-content" style="padding:10px 15px 0;direction:ltr;">
	 <p><?php _e('To upload font packages the', 'fontiran') ?> "<em><?php echo FP_DIR.'/fonts' ?></em>" <?php _e('directory needs to be writtable. <br/>Please check your server permissions.', 'fontiran') ?></p>
  </div>
 </section>
<?php 
return;
}

?>

  <section id="wphb-box-dashboard-welcome" class="firan-section box-dashboard-welcome">
    <div class="box-content" style="padding:10px 15px 0;">
     <div class="row">

		<div class="wrap" id="fontiran">

			<form name="cp_admin" method="post" class="form-wrap cp_form" enctype="multipart/form-data">  
             <input type="hidden" name="fiwp_nonce" value="<?php echo wp_create_nonce('fiwp') ?>" />
             <table class="form-table fti-table" cellspacing="0" cellpadding="5" style="width: 100%;">
              <tbody>
               <tr>
                <td>
                 <table style="width: 100%;">
                  <tbody>
                   <tr>
                    <td>
                     <div class="cp-upload" style="display:block;">
              			<input id="package_file" type="file" name="package_file" size="20"  />
              			<span>فایل حاوی فونت ها را بگونه فشرده و با فرمت zip بارگذاری کنید.</span>
             		  </div>
                    </td>
                   </tr>
                  </tbody>
                 </table>
                </td>
               </tr>
               <tr>
                <td>
                 
                 <table style="width: 100%;">
                  <tbody>
                   <tr>
                    <th>
                     <label for="fn[font_name]">نام فونت</label>
                    </th>
                    <td><input type="text" name="fn[font_name]" size="20"  placeholder="ex: iransans"/><span class="field-info">نام فونت</span></td>
                   </tr>
                   <tr>
                    <th>
                     <label for="fn[font_weight]">وزن فونت<span class="field-info"></span></label>
                    </th>
                    <td><select name="fn[font_weight]">
                      <option value="normal"><?php _e('normal', 'fontiran'); ?></option>
  					  <option value="100">100</option>
  					  <option value="200">200</option>
  					  <option value="300">300</option>
                      <option value="400">400</option>
                      <option value="500">500</option>
                      <option value="600">600</option>
                      <option value="700">700</option>
                      <option value="800">800</option>
                      <option value="900">900</option>
                      <option value="bold">bold</option>
  					 </select> <span class="field-info">وزن فونت را انتخاب کنید.</span></td>
                    
                    
                   </tr>
                    <tr>
                    <th>
                     <label for="fn[font_style]">استایل فونت</label>
                    </th>
                    <td><select name="fn[font_style]">
  					  <option value="normal"><?php _e('normal', 'fontiran'); ?></option>
  					  <option value="italic"><?php _e('italic', 'fontiran'); ?></option>
  					  <option value="oblique"><?php _e('oblique', 'fontiran'); ?></option>
  					 </select> <span class="field-info">وزن فونت را انتخاب کنید.</span></td>
                   </tr>
                  </tbody>
                 </table>
                </td>
               </tr>
               <tr>
                <td>
                 <input type="submit" name="fi_ul_font" value="بارگذاری فونت" class="button-primary" style="margin: 0 15px 0 10px;" />
                </td>
               </tr>
              </tbody>
             </table>
            </form>
		
		</div>

     </div>
    </div>    
  </section>

