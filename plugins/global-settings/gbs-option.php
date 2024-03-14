<style>
.left{
text-align:left;
}
.widefat input[type='text']{
border:0;
background:none;
}
</style>
<div class="wrap" id="profile-page">

<h2>Global Settings for <?php echo bloginfo('name'); ?></h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options');?>

<table width="225" align="right">
<tr>
<td align="right" width="50"><input type="button" class="remove_all button-primary" value="Remove All Fields" style="display:none;"/></td>
<td align="right" width="20"><input type="button" id="add_text" class="button-primary" value="Add New Option"/></td>
</tr>
</table>

<div>

<table id="theForm" style="clear:both;" >
<tr>
<th class="left" width="82">Remove</th>
<th class="left" width="205">Option Name</th>
<th class="left" width="">Option Value</th>
<th class="left" width=""></th>
</tr>
<tr id="option_1" class="option_all">
<td align="left"><input type="checkbox" name="remove_option" id="chk_1" value=""/></td>
<td class="left"><input type="text" id="option_name_1" size="30" style="float:left;"/></td>
<td class="left"><textarea id="option_value_1" style="float:left;" cols="50"></textarea></td>
<td></td>
</tr>
</table>
<table style="margin:15px 0;">
<tr>
<th class="left" width="82"></th>
<td align="left" width="205"><input type="button" class="remove_text button-primary" value="Remove Checked"/></td>
<td align="left" width=""><input class="save_all button-primary" type="submit" name="Submit" value="Save Options"/></td>
</tr>
</table>
</form>

<?php
if(isset($_POST['del_ids'])){
$del = explode(',',$_POST['del_ids']);
foreach($del as $k => $v){
delete_option($v);
}
}

global $wpdb;
$my_op = $wpdb->get_results("select * from ".$wpdb->prefix."options where option_name like 'gbs_%'");
$total = $wpdb->num_rows;
$i= 1;
$opname = '';

if($total > 0){

echo '<form method="post" action="options.php" name="update_go">';
wp_nonce_field('update-options');

echo '<table class="widefat">
<tr>
<th class="left" width="40">Delete</th>
<th class="left" width="90">Option Name</th>
<th class="left" width="200">Option Value</th>
<th class="left" width="250">PHP Code to Use</th>
</tr>';


foreach($my_op as $k => $v){

$op = substr($v->option_name,4,strlen($v->option_name));

if($i % 2 == 0) $cl = ''; else $cl = 'alternate';

echo '<tr class="'.$cl.'">
<td class="left"><input type="checkbox" name="remove_opt" id="rid_'.$i.'" value="'.$v->option_name.'"/></td>
<td class="left"><input type="text" readonly id="nid_'.$i.'" name="" value="'.$op.'"/></td>
<td class="left"><textarea class="op_val" id="vid_'.$i.'" name="'.$v->option_name.'" cols="50" >'.$v->option_value.'</textarea></td>
<td class="left"><code>&lt;?php echo get_option(\''.$v->option_name.'\'); ?&gt;</code></td></tr>';
$i++;

$opname .= $v->option_name.',';
}
?>
</table>
	<input type="hidden" name="action" value="update" /> 
	<input type="hidden" name="page_options" value="<?php echo substr($opname,0,-1); ?>" />
</form>

<form action="" method="post">
<table style="margin:15px 0;">
<tr>
<th class="left" width="82"></th>
<td align="left" width="205"><input type="submit" class="delete_text button-primary" value="Delete Checked"/></td>
<td align="left" width=""><input type="button" value="Save Values" onclick="document.update_go.submit();" class="button-primary" /><input type="hidden" name="del_ids" id="del_ids" value=""/></td>
</tr>
</table>
</form>

<?php } ?>

</div>

<br style="clear:both" />
<script type="text/javascript">

var id = 2;
jQuery(document).ready(function() {
	jQuery('.widefat .op_val').attr("readonly","readonly");
});

jQuery('.widefat .op_val').dblclick(function(){
	document.getElementById(this.id).removeAttribute('readonly');
	jQuery('#'+this.id).css('color','#FFF');
	jQuery('#'+this.id).css('background-color','#298CBA');
	
});

jQuery('.widefat .op_val').blur(function(){
	jQuery('#'+this.id).css('color','#298CBA');
	jQuery('#'+this.id).css('background-color','');
	document.getElementById(this.id).setAttribute('readonly','readonly');
});


jQuery('.delete_text').click(function(){
var tot = '<?php echo $total; ?>';
var op = 1;
var did = '';
for(op = 1; op <= tot; op++){
if(jQuery('#rid_'+op).is(':checked')){
var ids = jQuery('#rid_'+op).val();
did += ids +',';
}
}
	if(did!=''){
		if(confirm('Are you sure to delete selected Option value Pairs?')){
			jQuery('#del_ids').val(did);
		}
	}else{
		alert('Please Select Options to delete.');
		return false;
	}
});

jQuery('#add_text').click(function(){

jQuery("#theForm").append('<tr id="option_'+id+'" class="option_all"><td align="left"><input type="checkbox" name="remove_option" id="chk_'+id+'" value=""/></td> <td class="left"><input type="text" id="option_name_'+id+'" size="30" style="float:left;"/></td><td class="left" colspan="2"><textarea id="option_value_'+id+'" style="float:left;" cols="50"></textarea></td><td></td></tr>');

if(id > 1 && id < 3){
jQuery(".remove_all").css('display', 'block');
}

id = id+1;
});

jQuery('.remove_all').click(function(){
jQuery('.option_all').remove();
var id = 2;
jQuery("#theForm").append('<tr id="option_1" class="option_all"> <td align="left"><input type="checkbox" name="remove_option" id="chk_1" value=""/></td> <td class="left"><input type="text" id="option_name_1" size="30" style="float:left;"/></td> <td class="left"><textarea id="option_value_1" style="float:left;" cols="50"></textarea></td> <td></td></tr>');
});

jQuery('.save_all').click(function(){

var total = id;
var op = 1;
var hidd_val = '';

for(op = 1; op < total; op++){

var onam = jQuery('#option_name_'+op).val();
var oval = jQuery('#option_value_'+op).val();
onam = onam.toLowerCase().replace(/ /g, '_')

jQuery('#option_value_'+op).attr('name', 'gbs_'+onam);

if(onam !='' && oval !=''){
hidd_val += 'gbs_'+onam+',';
}
}
hidd_val = hidd_val.substring(0,hidd_val.length -1);

jQuery("#theForm").append('<tr><td align="left" colspan="3"><input type="hidden" name="action" value="update" /> <input type="hidden" name="page_options" value="'+hidd_val+'" /></td></tr>');
// return false;
});

jQuery('.remove_text').click(function(){
var total = id;
var op = 1;

for(op = 1; op < total; op++){

if(jQuery('#chk_'+op).is(':checked')){
jQuery('#option_'+op).remove();
}
}
});

</script>