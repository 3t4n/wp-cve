<?php

function Spider_id($value)
{

            ?>
<input type="hidden" name="Spider_Zoom_id" id="Spider_Zoom_id" value="<?php echo $value; ?>">
<div id="div_id"></div>


<script type="text/javascript">
x=document.getElementById("Spider_Zoom_id").value;

i=1;
while(x.indexOf('#')!=-1)
{
val=x.substr(0,x.indexOf('#'));
	var input=document.createElement('input');
	    input.setAttribute("type", "text"); 
	    input.setAttribute("value", val); 
	    input.setAttribute("name", "id_"+i); 
	    input.setAttribute("id", "id_"+i); 
	    input.setAttribute("onchange", "add('"+i+"')"); 
	var span=document.createElement('span');
   	    span.setAttribute("style", "cursor:pointer; border:1px solid black; margin-left:10px; font-size:10px"); 
	    span.setAttribute("id", "span_"+i); 
	    span.setAttribute("onclick", "remove_('"+i+"')"); 
    	    span.innerHTML="&nbsp;X&nbsp;";
	var br=document.createElement('br');
	    br.setAttribute("id", "br_"+i); 

	    document.getElementById("div_id").appendChild(input);
	    document.getElementById("div_id").appendChild(span);
	    document.getElementById("div_id").appendChild(br);
i++;	    
x=x.substr(x.indexOf('#')+1);
}

var input=document.createElement('input');
    input.setAttribute("type", "text"); 
    input.setAttribute("name", "id_"+i); 
    input.setAttribute("id", "id_"+i); 
    input.setAttribute("onchange", "add('"+i+"')");


document.getElementById("div_id").appendChild(input);
 



function add(x)
{
node=document.getElementById('id_'+x);
node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";

if(!node.nextSibling)
{

i=(parseInt(x));
	var input=document.createElement('input');
	    input.setAttribute("type", "text"); 
	    input.setAttribute("name", "id_"+(i+1)); 
	    input.setAttribute("id", "id_"+(i+1)); 
	    input.setAttribute("onchange", "add('"+(i+1)+"')"); 
	var span=document.createElement('span');
  	    span.setAttribute("style", "cursor:pointer; border:1px solid black; margin-left:10px; font-size:10px"); 
	    span.setAttribute("id", "span_"+i); 
	    span.setAttribute("onclick", "remove_('"+i+"')"); 
   	    span.innerHTML="&nbsp;X&nbsp;";
	var br=document.createElement('br');
	    br.setAttribute("id", "br_"+i); 
	node.parentNode.appendChild(span);
	node.parentNode.appendChild(br);
	node.parentNode.appendChild(input);
}

refresh_hidden(node.parentNode);
}

function remove_(x)
{
node=document.getElementById('id_'+x);
parent_=node.parentNode;
br=document.getElementById('br_'+x);
input=document.getElementById('id_'+x);
input.value="";
refresh_hidden(node.parentNode);

span=document.getElementById('span_'+x);
parent_.removeChild(br);
parent_.removeChild(span);
parent_.removeChild(input);

}


function refresh_hidden(div)
{
hidden='';
for(i=1; i<50; i++)
if(document.getElementById('id_'+i))
if(document.getElementById('id_'+i).value)
{
hidden=hidden+document.getElementById('id_'+i).value+'#';
}
document.getElementById("Spider_Zoom_id").value=hidden;
}
    </script>
        <?php

	}
	
	?>