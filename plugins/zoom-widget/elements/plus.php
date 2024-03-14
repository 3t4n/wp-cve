<?php
function Spider_plus()
{	

?>
        <script type="text/javascript">
	path="<?php echo JPATH_SITE.DS.DS.'media'.DS.DS.'com_plus'.DS.DS;?>"+document.getElementById('paramsimggroup').value;
	Dim fso
Set fso = CreateObject("Scripting.FileSystemObject")
var fso;
fso = new ActiveXObject("Scripting.FileSystemObject");
 

// if condition to check whether the specified file exists or not.

if(fso.FileExists(path))
{

alert("File.txt exists.");
}

else

{

alert("File.txt does not exist.");
}
fso = null;
	</script>
        
<select>

            
</select>
 
<input type="text" name="Spider_Zoom_plus" id="Spider_Zoom_plus" value="<?php echo $value; ?>"> <span style="font-size:10px"><strong>%</strong></span>


        <?php
    }
	
	?>