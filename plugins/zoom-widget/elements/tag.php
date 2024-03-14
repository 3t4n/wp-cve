<?php

function Spider_tag($value)
{

?>
<input type="hidden" name="Spider_Zoom_tag" id="Spider_Zoom_tag" value="<?php echo $value; ?>"><br />
<input type="checkbox" name="all" id="all" value="#all" onclick="check(this)" <?php if(strpos($value,'#all')!='') echo 'checked="checked"'; ?> /> All<br /><br />
<table width="100%">
<tr>
<td width="40%" align="left">
<input style="margin:0px" type="checkbox" id="1" value="#P" onclick="setpost(this)" <?php if(strpos($value,'#P')!='') echo 'checked="checked"'; ?> /> P<br />
<input style="margin:0px" type="checkbox" id="2" value="#A" onclick="setpost(this)" <?php if(strpos($value,'#A')!='') echo 'checked="checked"'; ?> /> A<br />
<input style="margin:0px" type="checkbox" id="18" value="#LABEL" onclick="setpost(this)" <?php if(strpos($value,'#LABEL')!='') echo 'checked="checked"'; ?> /> LABEL<br />
<input style="margin:0px" type="checkbox" id="3" value="#H1" onclick="setpost(this)" <?php if((strpos($value,'#H1')!='')) echo 'checked="checked"'; ?> /> H1<br />
<input style="margin:0px" type="checkbox" id="4" value="#H2" onclick="setpost(this)" <?php if((strpos($value,'#H2')!='')) echo 'checked="checked"'; ?> /> H2<br />
<input style="margin:0px" type="checkbox" id="5" value="#H3" onclick="setpost(this)" <?php if((strpos($value,'#H3')!='')) echo 'checked="checked"'; ?> /> H3<br />
<input style="margin:0px" type="checkbox" id="6" value="#H4" onclick="setpost(this)" <?php if((strpos($value,'#H4')!='')) echo 'checked="checked"'; ?> /> H4<br />
<input style="margin:0px" type="checkbox" id="7" value="#H5" onclick="setpost(this)" <?php if((strpos($value,'#H5')!='')) echo 'checked="checked"'; ?> /> H5<br />
<input style="margin:0px" type="checkbox" id="8" value="#H6" onclick="setpost(this)" <?php if((strpos($value,'#H6')!='')) echo 'checked="checked"'; ?> /> H6<br />
<input style="margin:0px" type="checkbox" id="19" value="#EM" onclick="setpost(this)" <?php if((strpos($value,'#EM')!='')) echo 'checked="checked"'; ?> /> EM<br />
</td><td width="60%" align="left">
<input style="margin:0px" type="checkbox" id="9" value="#SPAN" onclick="setpost(this)" <?php if((strpos($value,'#SPAN')!='')) echo 'checked="checked"'; ?> /> SPAN<br />
<input style="margin:0px" type="checkbox" id="10" value="#DIV" onclick="setpost(this)" <?php if((strpos($value,'#DIV')!='')) echo 'checked="checked"'; ?> /> DIV<br />
<input style="margin:0px" type="checkbox" id="11" value="#TD" onclick="setpost(this)" <?php if((strpos($value,'#TD')!='')) echo 'checked="checked"'; ?> /> TD<br />
<input style="margin:0px" type="checkbox" id="12" value="#LI" onclick="setpost(this)" <?php if((strpos($value,'#LI')!='')) echo 'checked="checked"'; ?> /> LI<br />
<input style="margin:0px" type="checkbox" id="13" value="#BUTTON" onclick="setpost(this)" <?php if((strpos($value,'#BUTTON')!='')) echo 'checked="checked"'; ?> /> BUTTON<br />
<input style="margin:0px" type="checkbox" id="14" value="#B" onclick="setpost(this)" <?php if((strpos($value,'#B#')!='') or strrpos($value,'#B')==(strlen($value)-2)) echo 'checked="checked"'; ?> /> B<br />
<input style="margin:0px" type="checkbox" id="17" value="#STRONG" onclick="setpost(this)" <?php if((strpos($value,'#STRONG')!='')) echo 'checked="checked"'; ?> /> STRONG<br />
<input style="margin:0px" type="checkbox" id="15" value="#I" onclick="setpost(this)" <?php if((strpos($value,'#I')!='')) echo 'checked="checked"'; ?> /> I<br />
<input style="margin:0px" type="checkbox" id="16" value="#FONT" onclick="setpost(this)" <?php if((strpos($value,'#FONT')!='')) echo 'checked="checked"'; ?> /> FONT<br />
</td></tr></table>
    </label>
    <script >
    all_sel();
    function  all_sel()
    {
    	x=true;
    	for(i=1; i<21; i++)
	    if(document.getElementById(i))
		    if(!document.getElementById(i).checked)
		    	x=false;
	if(x)
		document.getElementById('all').checked=true;
	else
		document.getElementById('all').checked=false;
	}
    
    
    function  setpost(x)
    {
    	if(x.checked)
			x.setAttribute('checked', 'checked');
		else
			x.removeAttribute('checked');

	post='#';
	for(i=1; i<21; i++)
	    if(document.getElementById(i))
		    if(document.getElementById(i).checked)
		    {
			    post=post+document.getElementById(i).value;
		    }
	document.getElementById('Spider_Zoom_tag').value=post;
	all_sel();
    }
   
    function  check(x)
    {
    	var attr = document.createAttribute('checked');
	attr.nodeValue = 'checked';

    	if(x.checked)
	x.setAttributeNode(attr)
	else
	x.removeAttribute('checked');

	    if(document.getElementById('all').checked)
	    {
	    for(i=1; i<21; i++)
	    if(document.getElementById(i))
			document.getElementById(i).checked=true;
	   }
	    else
	    {
	    for(i=1; i<21; i++)
	    if(document.getElementById(i))
			document.getElementById(i).checked=false;
	   }
	   refresh_tags();
    }
    
   function refresh_tags()
   {
   	post='#';
	for(i=1; i<21; i++)
	    if(document.getElementById(i))
		    if(document.getElementById(i).checked)
		    {
			    post=post+document.getElementById(i).value;
		    }
	document.getElementById('Spider_Zoom_tag').value=post;

   }

    </script>
        <?php
	}
	
	?>