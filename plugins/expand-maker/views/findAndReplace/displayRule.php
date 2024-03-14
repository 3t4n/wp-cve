<?php
use yrm\DisplayConditionBuilder;

$savedData = $typeObj->getOptionValue('yrm-display-settings');
$obj = new DisplayConditionBuilder();
$obj->setSavedData($savedData);
$data = $obj->filterForSave();
$obj->setSavedData($data);
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>

<div class="yrm-bootstrap-wrapper">
	<?php echo wp_kses($obj->render(), $allowedTag); ?>
</div>