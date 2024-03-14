<?php

function Spider_class($value)
{

            ?>
<input type="hidden" name="Spider_Zoom_class" id="Spider_Zoom_class" value="<?php echo $value; ?>">
<div id="div_class"></div>


<script type="text/javascript">
x=document.getElementById("Spider_Zoom_class").value;

i=1;
while(x.indexOf('#')!=-1)
{
val=x.substr(0,x.indexOf('#'));
	var input=document.createElement('input');
	    input.setAttribute("type", "text"); 
	    input.setAttribute("value", val); 
	    input.setAttribute("name", "class_"+i); 
	    input.setAttribute("id", "class_"+i); 
	    input.setAttribute("onchange", "add_class('"+i+"')"); 
	var span=document.createElement('span');
   	    span.setAttribute("style", "cursor:pointer; border:1px solid black; margin-left:10px; font-size:10px"); 
	    span.setAttribute("id", "class_span_"+i); 
	    span.setAttribute("onclick", "remove_class('"+i+"')"); 
            span.innerHTML="&nbsp;X&nbsp;";
	var br=document.createElement('br');
	    br.setAttribute("id", "class_br_"+i); 

	    document.getElementById("div_class").appendChild(input);
	    document.getElementById("div_class").appendChild(span);
	    document.getElementById("div_class").appendChild(br);
i++;	    
x=x.substr(x.indexOf('#')+1);
}

var input=document.createElement('input');
    input.setAttribute("type", "text"); 
    input.setAttribute("name", "class_"+i); 
    input.setAttribute("id", "class_"+i); 
    input.setAttribute("onchange", "add_class('"+i+"')");


document.getElementById("div_class").appendChild(input);
 



function add_class(x)
{
node=document.getElementById('class_'+x);
node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.height="inherit";
if(!node.nextSibling)
{

i=(parseInt(x));
	var input=document.createElement('input');
	    input.setAttribute("type", "text"); 
	    input.setAttribute("name", "class_"+(i+1)); 
	    input.setAttribute("id", "class_"+(i+1)); 
	    input.setAttribute("onchange", "add_class('"+(i+1)+"')"); 
	var span=document.createElement('span');
   	    span.setAttribute("style", "cursor:pointer; border:1px solid black; margin-left:10px; font-size:10px"); 
	    span.setAttribute("id", "class_span_"+i); 
	    span.setAttribute("onclick", "remove_class('"+i+"')"); 
            span.innerHTML="&nbsp;X&nbsp;";
	var br=document.createElement('br');
	    br.setAttribute("id", "class_br_"+i); 
	node.parentNode.appendChild(span);
	node.parentNode.appendChild(br);
	node.parentNode.appendChild(input);
}

refresh_hidden_class(node.parentNode);
}

function remove_class(x)
{
node=document.getElementById('class_'+x);
parent_=node.parentNode;
br=document.getElementById('class_br_'+x);
input=document.getElementById('class_'+x);
input.value="";
refresh_hidden_class(parent_);

span=document.getElementById('class_span_'+x);
parent_.removeChild(br);
parent_.removeChild(span);
parent_.removeChild(input);

}


function refresh_hidden_class(div)
{
hidden='';
for(i=1; i<50; i++)
{
if(document.getElementById('class_'+i))
if(document.getElementById('class_'+i).value)
{
hidden=hidden+document.getElementById('class_'+i).value+'#';
}
}
document.getElementById("Spider_Zoom_class").value=hidden;
}
    </script>
<?php
	}
	
	?>