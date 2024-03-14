<div id="fixed-class-fixed">
<h1>Plugin Settings</h1>
<?php
$fixedorstickyheader = array();

$myplugins_options = get_option('pluginoptions_fx');

$yor_instruct = isset($_GET['instruct']) ? $_GET['instruct'] : '';

if(isset($_POST['saveFixedheader']) && wp_verify_nonce( $_POST['nonceAmountoftime'], 'FixedorstickyAction' )):
$nosetToset = array('saveFixedheader');
foreach($nosetToset as $notsaved_ryt): unset($_POST[$notsaved_ryt]); endforeach; foreach($_POST as $key => $val): $fixedorstickyheader[$key] = $val; endforeach;
$submit_options = update_option('pluginoptions_fx', $fixedorstickyheader );
if($submit_options){ myfixedurl('options-general.php?page=myplugin_setting&instruct=1'); }else{
myfixedurl('options-general.php?page=myplugin_setting&instruct=2');}
endif;
?> 
<div id="fixed-content" class="">
  <div id="main-form">
      <form method="post" action=""><?php  wp_nonce_field( 'FixedorstickyAction', 'nonceAmountoftime' ); ?>
       <table class="form-table">
       <tbody>
        <tr><th><span>Add Fixed Header (Class or Id) </span></th>
            <td><input type="text" class="from-control" name="class-addfixed-fx" value="<?php if(!empty($myplugins_options['class-addfixed-fx'])){ _e($myplugins_options['class-addfixed-fx']);}?>" required> <em>Example: .header or #header</em></td>
        </tr>
        
        <tr><th><span>Background Color: </span></th>
            <td><input type="text" class="from-control" name="class-addbackgroundcolor-fx" value="<?php if(!empty($myplugins_options['class-addbackgroundcolor-fx'])){ _e($myplugins_options['class-addbackgroundcolor-fx']);}?>" > <em>Example: #fff</em></td>
        </tr>
        
		<tr><th><span>Text Color:</span></th>
		    <td><input type="text" class="from-control" name="class-textcolor-fx" value="<?php if(!empty($myplugins_options['class-textcolor-fx'])){ _e($myplugins_options['class-textcolor-fx']);}?>" > <em>Example: #000</em></td>
        </tr>
		
		<tr><th><span>Fixed Header Height:</span></th>
            <td><input name="fixed-header-height-fx" value="<?php if(!empty($myplugins_options['fixed-header-height-fx'])){ _e($myplugins_options['fixed-header-height-fx']);}?>"> 
         <em>Example: 100px or blank to default</em></td>
        </tr>
         
		<tr><th><span>Fixed Header Padding:</span></th>
		    <td><input name="fixed-header-padding-fx" value="<?php if(!empty($myplugins_options['fixed-header-padding-fx'])){ _e($myplugins_options['fixed-header-padding-fx'],'myfixedsticky-fxx');}else { echo '0px 0px 0px 0px';}?>" ><em> Example: 0px 0px 0px 0px</em></td>
        </tr>
		
		<tr><th><span>Fixed Header Scroll</span></th>
            <td><input type="text" class="header_scroll" name="fixed-scroll-fx" value="<?php if(!empty($myplugins_options['fixed-scroll-fx'])){ _e($myplugins_options['fixed-scroll-fx'],'myfixedsticky-fxx');}else{ _e('50', 'myfixedsticky-fxx');}?>"> <em>Example: 100</em></td>
        </tr>
       </tbody>
        </table>
        <span class="submit"><input type="submit" value="Save Settings" class="button button-primary" id="submit" name="saveFixedheader"></span>
       </form>
</div>
</div>
</div>
