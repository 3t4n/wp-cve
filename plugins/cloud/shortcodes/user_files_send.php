<h3>Send new file to:</h3>
<form action="<?php echo get_site_url(); ?>/?cloud=send&redirect=<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post" enctype="multipart/form-data">
<label for="file">File:</label>
<input class="wpcloud_buttonupload" type="file" name="file" id="file"><br/>
<label for="username">Contact e-mail or username: </label>
<input class="wpcloud_buttonsend" type="text" name="username" id="send"><br/>
<input class="button button-small" type="submit" name="submit" value="Submit">

</form>

<style>
.wpcloud_button {
	background: #f5f5f5;
	padding: 5px 10px;
	border-radius: 5px;
	border: solid 1px #ECE9E9;
	cursor: pointer;
	margin: 10px 0px 0px 0px;
}
.wpcloud_buttonupload {
	background: #f5f5f5;
	padding: 2px 5px;
	border-radius: 3px;
	border: solid 1px #ECE9E9;
	cursor: pointer;
	margin: 5px 0px 0px 0px;
}

.wpcloud_buttonsend {
	background: #f5f5f5;
	padding: 2px 5px;
	border-radius: 3px;
	border: solid 1px #ECE9E9;
	margin: 5px 0px 0px 0px;
}
</style>