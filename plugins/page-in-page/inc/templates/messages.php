<?php

if (!empty($successes)) {
	foreach ($successes as $success) {
		echo '<div class="twl-message twl-success">' . $success . '</div>';
	}
}

if (!empty($errors)) {
	foreach ($errors as $error) {
		echo '<div class="twl-message twl-error">' . $error . '</div>';
	}
}