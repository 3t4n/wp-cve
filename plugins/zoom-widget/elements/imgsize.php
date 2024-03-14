<?php
function Spider_imgsize($value)
{
		
		wp_enqueue_script( 'jquery-1.7.1',plugins_url("jquery-1.7.1.js",__FILE__));
		wp_enqueue_script( 'jquery.ui.core',plugins_url("jquery.ui.core.js",__FILE__));
		wp_enqueue_script( 'jquery.ui.widget',plugins_url("jquery.ui.widget.js",__FILE__));
		wp_enqueue_script( 'jquery.ui.mouse',plugins_url("jquery.ui.mouse.js",__FILE__));
		wp_enqueue_script( 'jquery.ui.slider',plugins_url("jquery.ui.slider.js",__FILE__));
		wp_enqueue_script( 'jquery.ui.sortable',plugins_url("jquery.ui.sortable.js",__FILE__));
		wp_enqueue_style( 'jquery-ui',plugins_url("jquery-ui.css",__FILE__));
		wp_enqueue_style( 'parseTheme',plugins_url("parseTheme.css",__FILE__));
switch ($value) {
    case "bms":
        $plus=60;
        $reset=50;
       	$minus=40;
        break;
    case "same":
        $plus=50;
        $reset=50;
       	$minus=50;
        break;
    default :
        $a=explode('***',$value);
        $plus=$a[1];
        $reset=$a[2];
       	$minus=$a[3];
        break;
}	?>

<input type="radio" name="name" value="bms" onclick="change_size_type('bms')" <?php if($value=="bms") echo "checked" ?> />Big - Medium - Small<br />
<input type="radio" name="name" value="same" onclick="change_size_type('same')" <?php if($value=="same") echo "checked" ?> />The Same size<br />
<input type="radio" name="name" value="custom" onclick="change_size_type('custom')"  <?php if($value!="bms" && $value!="same") echo "checked" ?>/>Custom size<br />

<div  id="sizes"  <?php if($value=="bms" || $value=="same") echo 'style="display:none"' ?>  >
Plus: <input type="text" id="img_plus_size" size=4 onkeypress="return check_isnum(event)" onkeyup="change_size(this.value, 'plus')" style="margin-left:8px" value="<?php echo $plus; ?>" /><br />
100%: <input type="text" id="img_100_size" size=4 onkeypress="return check_isnum(event)" onkeyup="change_size(this.value, '100')"  value="<?php echo $reset; ?>" /><br />
Minus: <input type="text" id="img_minus_size" size=4  onkeypress="return check_isnum(event)" onkeyup="change_size(this.value, 'minus')"  value="<?php echo $minus; ?>" />
</div>


<input name="Spider_Zoom_imgsize" id="Spider_Zoom_imgsize"  value="<?php echo  $value; ?>" type="hidden" >

<script type="text/javascript">
			if(document.getElementById('but_type_img').checked)
			{
				document.getElementById('plus').style.height="<?php echo $plus; ?>px";
				document.getElementById('minus').style.height="<?php echo $minus; ?>px";
				document.getElementById('100').style.height="<?php echo $reset; ?>px";
			}
			else
			{
				document.getElementById('plus').style.fontSize="<?php echo $plus; ?>px";
				document.getElementById('minus').style.fontSize="<?php echo $minus; ?>px";
				document.getElementById('100').style.fontSize="<?php echo $reset; ?>px";
			}

function change_size(x, type)
{
node=document.getElementById('plus');
node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";
node.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";

			if(document.getElementById('but_type_img').checked)
			{
				document.getElementById(type).style.height=x+"px";
			}
			else
			{
				document.getElementById(type).style.fontSize=x+"px";
			}
			document.getElementById('Spider_Zoom_imgsize').value='custom***'+document.getElementById('img_plus_size').value+"***"+document.getElementById('img_100_size').value+"***"+document.getElementById('img_minus_size').value;
}

function change_size_type(type)
{
node=document.getElementById('plus');
node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";
node.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";

	switch(type)
	{
		case 'bms':
		{
			document.getElementById('img_plus_size').value="60";
			document.getElementById('img_100_size').value="50";
			document.getElementById('img_minus_size').value="40";
			
			if(document.getElementById('but_type_img').checked)
			{
				document.getElementById('plus').style.height="60px";
				document.getElementById('100').style.height="50px";
				document.getElementById('minus').style.height="40px";
			}
			else
			{
				document.getElementById('plus').style.fontSize="60px";
				document.getElementById('100').style.fontSize="50px";
				document.getElementById('minus').style.fontSize="40px";
			}
			document.getElementById('sizes').style.display="none";
			document.getElementById('Spider_Zoom_imgsize').value='bms';
			break
		}
		 
		case 'same':
		{
			document.getElementById('img_plus_size').value="50";
			document.getElementById('img_100_size').value="50";
			document.getElementById('img_minus_size').value="50";
			
			if(document.getElementById('but_type_img').checked)
			{
				document.getElementById('plus').style.height="50px";
				document.getElementById('minus').style.height="50px";
				document.getElementById('100').style.height="50px";
			}
			else
			{
				document.getElementById('plus').style.fontSize="50px";
				document.getElementById('minus').style.fontSize="50px";
				document.getElementById('100').style.fontSize="50px";
			}
			document.getElementById('sizes').style.display="none";
			document.getElementById('Spider_Zoom_imgsize').value='same';
			break
		}
		
		case 'custom':
		{
			document.getElementById('sizes').style.display="block";
			document.getElementById('Spider_Zoom_imgsize').value='custom***'+document.getElementById('img_plus_size').value+"***"+document.getElementById('img_100_size').value+"***"+document.getElementById('img_minus_size').value;
			break
		}
	}
}

function check_isnum(e)
{
	
   	var chCode1 = e.which || e.keyCode;
    	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
        return false;
	return true;
}

</script>
        <?php

	}
	
	?>