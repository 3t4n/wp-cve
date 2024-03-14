<?php
/**
 * Cognito Forms WordPress Plugin.
 *
 * The Cognito Forms WordPress Plugin is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * The Cognito Forms WordPress Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>

<?php require_once dirname( __FILE__ ) . '/../api.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cognito Forms</title>
	<style>
		html, body {
			margin: 0;
			padding: 0;
			height: 100%;
			line-height: 0;
		}
		#cognito-frame {
			width: 100%;
			height: 100%;
			border: none;
		}
	</style>
</head>
<body>
	<iframe id="cognito-frame" src="<?= CognitoAPI::$formsBase ?>/integrations/cms"></iframe>

	<script type="text/javascript">
		window.addEventListener('message', handleMessage);

		function handleMessage(event) {
			var frame = document.getElementById('cognito-frame');
			var baseUrl = new URL(frame.getAttribute('src')).origin;

			if (event.origin === baseUrl) {
				sendData(event.data);
			}
		}

		function sendData(data) {
			embedCode = data.embedCodes.Seamless;
			console.log(embedCode);

			if (top.tinyMCE && top.tinyMCE.activeEditor) {
				top.tinyMCE.activeEditor.insertContent(embedCode);
			}

			top.tinymce.activeEditor.windowManager.close();
		}
	</script>
</body>
</html>
