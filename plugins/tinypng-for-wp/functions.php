<?php

function __tinypngforwp_maintable()
{
	?>
	<center>
	<table class="maintable">
		<tr>
			<td><?php $images = __tinypngforwp_images(); ?></td>
		</tr>
		<tr>
			<td><?php $images_location = __tinypngforwp_upload_and_move($images); $imagenames = __tinypngforwp_get_image_names($images)?></td>
		</tr>
	</table>
	<table class="midtable">
		<tr>
			<td><?php __tinypngforwp_shrink($images_location,$imagenames) ?></td>
		</tr>
	</table>
	</center>
	<?php
}

function __tinypngforwp_sidetable()
{
	?>
	<table class="sidetable">
		<tr>
			<td><a href="https://tinypng.com/"><img src="<?php echo plugins_url('tinypng.png',__FILE__) ?>"></a></td>
		</tr>
		<tr>
			<td>
				<form method="post" action="options.php">
					<?php settings_fields( 'tinypng-settings-group' ); ?>
					<?php do_settings_sections( 'tinypng-settings-group' ); ?>
					<tr>
						<td>Your API Key</td>
						</tr>
						<tr>
							<td><input type="text" name="tinypng-api" size="50" value="<?php echo get_option('tinypng-api'); ?>" /></td>
						</tr>
						<tr>
							<td><input type="submit" name="submit" id="submit" class="button button-primary" value="Submit"/></td>
						</tr>
				</form>
			</td>
		</tr>
			<tr>
				<td style="padding-top:20px;">If you liked this plugin, donate some money and support the author.<br/>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="58QFX2EFEDV7N">
					<input type="image" src="<?php echo plugins_url('donate.gif',__FILE__) ?>" border="0" name="submit" alt="Donate to the Author">
					<img alt="Donate to the Author" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
				</form>
			</td>
			</tr>
	</table>
	<?php
}

function __tinypngforwp_make___tinypngforwp_dir()
{
	$upload_dir = wp_upload_dir();
	$__tinypngforwp_dir = $upload_dir['basedir']."/TinyPNG";
	if (!is_dir($__tinypngforwp_dir)) {
		mkdir( $upload_dir['basedir']."/TinyPNG", 0755);
	}
	return $__tinypngforwp_dir;
}

function __tinypngforwp_upload_directory_link()
{
	$upload_dir = wp_upload_dir();
	$__tinypngforwp_dir = $upload_dir['baseurl']."/TinyPNG/";
	return $__tinypngforwp_dir;
}

function __tinypngforwp_upload_and_move($images)
{
	$__tinypngforwp_dir = __tinypngforwp_make___tinypngforwp_dir();
	$images_location = array();
	for ($i=0; $i<5; $i++) {
		if ($images['name'][$i] != NULL) {
			$allowedExts = array( 'png' );
			$file_name = current(explode('.', $images['name'][$i]));
			$extension = end(explode('.', $images['name'][$i]));
			if (($images['type'][$i] == 'image/png') && ($images['size'][$i] < 2000000) && in_array($extension, $allowedExts)) {
				if ($images['error'][$i] > 0) {
					echo 'Return Code: ' . $images['error'][$i] . '<br>';
				} else {
					if (file_exists( $__tinypngforwp_dir . '/' . $images['name'][$i] )) {
						echo $images['name'][$i] . ' already exists. ';
					} else {
						move_uploaded_file($images['tmp_name'][$i], $__tinypngforwp_dir . '/' . $images['name'][$i]);
					}
					$images_location[$i] =  $__tinypngforwp_dir . '/' . $images['name'][$i];
				}
			} else {
				echo "Invalid file";
			}
		 }
	}
	return $images_location;
}

function __tinypngforwp_images()
{
	__tinypngforwp_make___tinypngforwp_dir();
	echo 'You can upload upto 5 PNG images. Any other image type is not allowed. ';
	echo 'If you receive any errors after uploading the file. Please look into the FAQ page.<br/><br/>';
	?>
	<form method="post" action="" enctype="multipart/form-data">
		<center>
		<?php
		for ($i=0; $i<5; $i++) {
			?>
			<input type="file" name="image[]"><br />
			<?php
		}
		?><br/>
		<input type="submit" value="Convert these Images" name="submit" class="button button-primary"></center>
	</form>
	<?php
	if (!empty($_FILES['image']['name'])) {
		return $_FILES['image'];
	}
}

function __tinypngforwp_get_image_names($images)
{
	$imagenames = array();
	if (!empty($images)) {
		for ($i=0;$i<5;$i++) {
			$imagenames[$i] = $images['name'][$i];
		}
	}
	return $imagenames;
}

function __tinypngforwp_shrink($images_location,$filenames)
{
	$key = get_option('tinypng-api');
	$__tinypngforwp_directory_link = __tinypngforwp_upload_directory_link();
	for ($i=0; $i<5; $i++) {
		if (isset($images_location[$i])) {
			$in = $images_location[$i];
			$fileno = $i + 1;
			if (file_exists($in)) {
				$req = curl_init();
				curl_setopt_array($req, array(
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_URL => "https://api.tinypng.com/shrink",
						CURLOPT_USERPWD => 'api:' . $key,
						CURLOPT_POSTFIELDS => file_get_contents($in),
						CURLOPT_BINARYTRANSFER => true,
						CURLOPT_SSL_VERIFYPEER => false
					));
				$res = json_decode(curl_exec($req));
				$file_name_length = strlen($filenames[$i]);
				$file_directory = substr($in,0,'-'.$file_name_length);
				$file_name = current(explode('.',end(explode('/', $filenames[$i]))));
				$extension = end(explode('.',end(explode('/', $filenames[$i]))));
				$old_file_name = $file_name.'.'.$extension;
				$new_file_name = $file_name.'_new.'.$extension;
				$out = $file_directory.$file_name.'_new.'.$extension;
				if (curl_getinfo($req, CURLINFO_HTTP_CODE) === 201) {
					$opened = fopen($out,'w');
					$last_url = curl_getinfo($req, CURLINFO_EFFECTIVE_URL);
					$req = curl_init();
					curl_setopt_array($req, array(
						CURLOPT_URL => $res->output->url,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_SSL_VERIFYPEER => false
					));
					file_put_contents($out, curl_exec($req));
					fclose($opened);
					echo '<strong>' . $fileno.'. '.$old_file_name.' - '.$new_file_name . '</strong><br/>';
					echo 'The new and optimized file has been created.<br/>';
					$output_link = $__tinypngforwp_directory_link.$new_file_name;
					echo 'Your site link - <a href="'.$output_link.'">'.$output_link.'</a><br/>';
					$req_curl_error = curl_error($req);
					echo $req_curl_error;
					echo "<br/>";
				}
				else if ($res->error == 'Unauthorized') {
				   echo 'The request was not authorized with a valid API key. Please input a valid API Key.<br/>';
				}
				else if ($res->error == 'InputMissing') {
				   echo 'The file that was uploaded is empty or no data was posted.<br/>';
				}
				else if ($res->error == 'BadSignature') {
				   echo 'The file was not recognised as a PNG file. It may be corrupted or it is a different file type.<br/>';
				}
				else if ($res->error == 'DecodeError') {
				   echo 'The file had a valid PNG signature, but could not be decoded. It may be corrupted or is of an unsupported type. If you are positive you are sending a PNG file, feel free to contact us and send the file to us for diagnostics.<br/>';
				}
				else if ($res->error == 'TooManyRequests') {
				   echo 'Your monthly upload limit has been exceeded. Either wait until the next calendar month, or upgrade your subscription.<br/>';
				}
				else if ($res->error == 'InternalServerError') {
				   echo 'An internal error occurred during conversion. This error is usually temporary. If the uploaded file is a valid PNG file, you can try again later.<br/>';
				}
				else {
					print($res->error . ": " . $body->message);
				}
			}
		}
	}
}

?>
