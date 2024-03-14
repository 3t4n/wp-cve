<?php
function Spider_imggroup($value)
{		

wp_enqueue_script( 'jquery-1.7.1',plugins_url("jquery-1.7.1.js",__FILE__));
wp_enqueue_script( 'jquery.ui.core',plugins_url("jquery.ui.core.js",__FILE__));
wp_enqueue_script( 'jquery.ui.widget',plugins_url("jquery.ui.widget.js",__FILE__));
wp_enqueue_script( 'jquery.ui.mouse',plugins_url("jquery.ui.mouse.js",__FILE__));
wp_enqueue_script( 'jquery.ui.slider',plugins_url("jquery.ui.slider.js",__FILE__));
wp_enqueue_script( 'jquery.ui.sortable',plugins_url("jquery.ui.sortable.js",__FILE__));
wp_enqueue_style( 'jquery-ui',plugins_url("jquery-ui.css",__FILE__));
wp_enqueue_style( 'parseTheme',plugins_url("parseTheme.css",__FILE__));
$a=explode('***', $value);
$but_type		=$a[0];
$but_pos		=$a[1];
$img_group_id	=$a[2];
$text['plus']	=$a[3];
$text['100']	=$a[4];
$text['minus']	=$a[5];






	?>
    
<script>
function submitbutton(pressbutton) 
{
	if ( ( pressbutton == 'save' || pressbutton == 'apply' ) && ( document.adminForm.title.value == "" ) ) 
	{
		alert("Module must have a title");
		return;
	}

	tox='';
	for(k=0; k<document.getElementById('tr_arr').childNodes.length; k++)
		if(document.getElementById('tr_arr').childNodes[k].id)
			tox+='***'+document.getElementById('tr_arr').childNodes[k].id;
			
	but_type=0;
	but_pos=0;
	
	if(document.getElementById('but_type_img').checked)
		but_type=1;
	if(document.getElementById('but_pos_hor').checked)
		but_pos=1;
	global_id_.value=but_type+'***'+but_pos+'***'+document.getElementById('img_group_id').value+'***'+document.getElementById('change_plus_text').value+'***'+document.getElementById('change_100_text').value+'***'+document.getElementById('change_minus_text').value+tox;
  
	submitform(pressbutton);
}
</script>

    
<table cellpadding="0" cellspacing="0">
    <tr>
        <td><label style="color:#666"><strong>Type:</strong></label></td> 
        <td><input type="radio" value="1" name="but_type" onclick="change_type('img')" id="but_type_img" <?php if($but_type) echo 'checked="checked"'?> />Image</td>
        <td><input type="radio" value="1" name="but_type"  onclick="change_type('text')" id="but_type_text" <?php if(!$but_type) echo 'checked="checked"'?> />Text (HTML)</td>
    </tr>
    <tr>
        <td><label style="color:#666"><strong>Position:</strong></label></td> 
        <td><input type="radio" value="1" name="but_pos"  onclick="change_pos('hor')" id="but_pos_hor" <?php if($but_pos) echo 'checked="checked"'?>/>Horizontal</td>
        <td><input type="radio" value="1" name="but_pos" onclick="change_pos('ver')" id="but_pos_ver"  <?php if(!$but_pos) echo 'checked="checked"'?> />Vertical</td>
    </tr>
</table><br />
<select onchange="change_img(this.value)" id="img_group_id" style="width:100px;"   <?php if(!$but_type) echo 'disabled'?> >
    <option value="1" id="img1"  selected="selected">Style 1</option>
        <option value="2" id="img2"  disabled="disabled">Style 2</option>
    <option value="3" id="img3" disabled="disabled">Style 3</option>
    <option value="4" id="img4" disabled="disabled">Style 4</option>
    <option value="5" id="img5" disabled="disabled">Style 5</option>
    <option value="6" id="img6" disabled="disabled">Style 6</option>
    <option value="7" id="img7" disabled="disabled">Style 7</option>
    <option value="8" id="img8" disabled="disabled">Style 8</option>
    <option value="9" id="img9" disabled="disabled">Style 9</option>
    <option value="10" id="img10" disabled="disabled">Style 10</option>
    <option value="11" id="img11" disabled="disabled">Style 11</option>
    <option value="12" id="img12" disabled="disabled">Style 12</option>
    <option value="13" id="img13" disabled="disabled">Style 13</option>
    <option value="14" id="img14" disabled="disabled">Style 14</option>
    <option value="15" id="img15" disabled="disabled">Style 15</option>
    <option value="16" id="img16" disabled="disabled">Style 16</option>
    <option value="17" id="img17" disabled="disabled">Style 17</option>
    <option value="18" id="img18" disabled="disabled">Style 18</option>
    <option value="19" id="img19" disabled="disabled">Style 19</option>
    <option value="20" id="img20" disabled="disabled">Style 20</option>
    <option value="21" id="img21" disabled="disabled">Style 21</option>
    <option value="22" id="img22" disabled="disabled">Style 22</option>
    <option value="23" id="img23" disabled="disabled">Style 23</option>
    <option value="24" id="img24" disabled="disabled">Style 24</option>
    <option value="25" id="img25" disabled="disabled">Style 25</option>
    <option value="26" id="img26" disabled="disabled">Style 26</option>
    <option value="27" id="img27" disabled="disabled">Style 27</option>
    <option value="28" id="img28" disabled="disabled">Style 28</option>
    <option value="29" id="img29" disabled="disabled">Style 29</option>
    <option value="30" id="img30" disabled="disabled">Style 30</option>
    <option value="31" id="img31" disabled="disabled">Style 31</option>
    <option value="32" id="img32" disabled="disabled">Style 32</option>
    <option value="33" id="img33" disabled="disabled">Style 33</option>
    <option value="34" id="img34" disabled="disabled">Style 34</option>
    <option value="35" id="img35" disabled="disabled">Style 35</option>
    <option value="36" id="img36" disabled="disabled">Style 36</option>
    <option value="37" id="img37" disabled="disabled">Style 37</option>
    <option value="38" id="img38" disabled="disabled">Style 38</option>
    <option value="39" id="img39" disabled="disabled">Style 39</option>
    <option value="40" id="img40" disabled="disabled">Style 40</option>
    <option value="41" id="img41" disabled="disabled">Style 41</option>
    <option value="42" id="img42" disabled="disabled">Style 42</option>
</select>
<br /><br />
 
<div  id="tr_arr" style="display:block"><?php 
if($but_type)
for($i=6; $i<=8; $i++)
{?><img src="<?php echo  plugins_url('/',__FILE__); ?>images/<?php echo  $img_group_id; ?>/<?php echo  $a[$i]; ?>.png" id="<?php echo  $a[$i]; ?>" style="display:inline; margin:5px; cursor:move" /><?php

}
else
for($i=6; $i<=8; $i++)
{?><span id="<?php echo  $a[$i]; ?>" style="display:inline; line-height:inherit; margin:5px; cursor:move"><?php echo  $text[$a[$i]]; ?></span><?php

}
 ?></div>
<table  <?php if($but_type) echo 'style="display:none"'?> id="button_text">
<tr><td>Bigger:</td><td><input type="text" id="change_plus_text" value="<?php echo $text['plus']; ?>"  onkeyup="change_text('plus', this.value)"/></td></tr>
<tr><td>Reset:</td><td><input type="text" id="change_100_text" value="<?php echo $text['100']; ?>"  onkeyup="change_text('100', this.value)"/></td></tr>
<tr><td>Smaller:</td><td><input type="text" id="change_minus_text" value="<?php echo $text['minus']; ?>"  onkeyup="change_text('minus', this.value)"/></td></tr>
</table>

<input type="hidden" name="Spider_Zoom_imggroup" id="Spider_Zoom_imggroup"  value="<?php echo  $value; ?>" size="30"  />
<script type="text/javascript">
var ar=new Array();
jQuery(function() {
	jQuery( "#tr_arr" ).sortable();
	jQuery( "#tr_arr" ).disableSelection();
});

global_id_='Spider_Zoom_imggroup';

function change_text(id, value)
{
	document.getElementById(id).innerHTML=value;
}

function change_pos(x)
{
node=document.getElementById('plus');
	switch (x) 
	{
	   case 'ver':
	   {
		   document.getElementById('plus').style.display="block";
		   document.getElementById('minus').style.display="block";
		   document.getElementById('100').style.display="block";
		   break;
		}
	   case 'hor':
	   {
		   document.getElementById('plus').style.display="inline";
		   document.getElementById('minus').style.display="inline";
		   document.getElementById('100').style.display="inline";
		   break;
		}
	}
}

function change_type(x)
{
node=document.getElementById('plus');
node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";
	switch (x) 
	{
	   case 'img':
	   {
		  	div=document.getElementById('tr_arr');
			
			img_plus=document.createElement('img');
			img_minus=document.createElement('img');
			img_100=document.createElement('img');
			
			img_plus.style.height=document.getElementById('img_plus_size').value+"px";
			img_minus.style.height=document.getElementById('img_minus_size').value+"px";
			img_100.style.height=document.getElementById('img_100_size').value+"px";
			
			img_plus.style.cursor="move";
			img_minus.style.cursor="move";
			img_100.style.cursor="move";

			img_plus.style.margin="5px";
			img_minus.style.margin="5px";
			img_100.style.margin="5px";
			
			img_plus.id="plus";
			img_minus.id="minus";
			img_100.id="100";
			
			if(document.getElementById('but_pos_ver').checked)
			{
				img_plus.style.display="block";
				img_minus.style.display="block";
				img_100.style.display="block";
			}
		
			img_plus.src="<?php echo  plugins_url('/',__FILE__)?>images/"+document.getElementById('img_group_id').value+"/plus.png";
			img_minus.src="<?php echo  plugins_url('/',__FILE__)?>images/"+document.getElementById('img_group_id').value+"/minus.png";
			img_100.src="<?php echo  plugins_url('/',__FILE__)?>images/"+document.getElementById('img_group_id').value+"/100.png";
			
			div.innerHTML="";
		
			div.appendChild(img_plus);
			div.appendChild(img_100);
			div.appendChild(img_minus);
			
		  	document.getElementById('img_group_id').disabled=false;
		  	document.getElementById('button_text').style.display="none";
			break;
	   }
	   case 'text':
	   {
		  	div=document.getElementById('tr_arr');
			
			span_plus=document.createElement('span');
			span_minus=document.createElement('span');
			span_100=document.createElement('span');
			
			span_plus.style.fontSize=document.getElementById('img_plus_size').value+"px";
			span_minus.style.fontSize=document.getElementById('img_minus_size').value+"px";
			span_100.style.fontSize=document.getElementById('img_100_size').value+"px";
			
			span_plus.style.cursor="move";
			span_minus.style.cursor="move";
			span_100.style.cursor="move";
			
			span_plus.style.margin="5px";
			span_minus.style.margin="5px";
			span_100.style.margin="5px";
			
			span_plus.style.lineHeight="initial";
			span_minus.style.lineHeight="initial";
			span_100.style.lineHeight="initial";

			span_plus.id="plus";
			span_minus.id="minus";
			span_100.id="100";
		
			span_plus.innerHTML=document.getElementById('change_plus_text').value;
			span_minus.innerHTML=document.getElementById('change_minus_text').value;
			span_100.innerHTML=document.getElementById('change_100_text').value;
			
			if(document.getElementById('but_pos_ver').checked)
			{
				span_plus.style.display="block";
				span_minus.style.display="block";
				span_100.style.display="block";
			}
			div.innerHTML="";
		
			div.appendChild(span_plus);
			div.appendChild(span_100);
			div.appendChild(span_minus);
			
		  	document.getElementById('button_text').style.display="block";
		  	document.getElementById('img_group_id').disabled=true;
			break;
	   }
	}
}

function change_img(x)
{
node=document.getElementById('plus');
node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";
node.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";	
	
document.getElementById('plus').src="<?php echo  plugins_url('/',__FILE__) ?>images/"+x+"/plus.png";
document.getElementById('minus').src="<?php echo  plugins_url('/',__FILE__) ?>images/"+x+"/minus.png";
document.getElementById('100').src="<?php echo  plugins_url('/',__FILE__) ?>images/"+x+"/100.png";
}

<?php if(!$but_pos) echo 'change_pos("ver");'?> 
</script>







        <?php

}
	
	?>