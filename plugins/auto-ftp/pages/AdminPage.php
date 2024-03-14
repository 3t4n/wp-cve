<?php 
$xArray = unserialize(get_option('xAFTP'));
?>

<form method="post" action="">
<h2>Auto FTP</h2>
Save your ftp details here:<br/>

<p><b>Host:<b/><br/><input type="text" name="xAFTPHost" value="<?php echo $xArray[0];?>" /></p>
<p><b>User:<b/><br/><input type="text" name="xAFTPUser" value="<?php echo $xArray[1];?>" /> </p>
<p><b>Password:<b/><br/><input type="password" name="xAFTPPassword" value="<?php echo $xArray[2];?>" /> </p>

<div>
<input type="hidden" name="xAFTPHidd" value="xAFTPHidd" />
<input type="submit" value="Update"/>
</div>
</form>