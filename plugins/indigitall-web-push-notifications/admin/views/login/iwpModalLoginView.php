<?php
	$loginView = isset($loginView) ? $loginView : '';
	$signUpView = isset($signUpView) ? $signUpView : '';
	$doubleFaView = isset($doubleFaView) ? $doubleFaView : '';
	$projectSelectionView = isset($projectSelectionView) ? $projectSelectionView : '';
	$recoverPassView = isset($recoverPassView) ? $recoverPassView : '';

//	$showModal = true;
	$showModalData = isset($showModal) ? (int)$showModal : 0;
?>

<div id="iwpAdminModalLogin" class="iwp-admin-modalLogin-container" data-show="<?php echo($showModalData); ?>">
	<?php
		echo($loginView);
		echo($signUpView);
		echo($doubleFaView);
		echo($projectSelectionView);
		echo($recoverPassView);
	?>
</div>
