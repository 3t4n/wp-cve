<div class="col-md-12">
<script>readMoreArgs = [];</script>
<?php
$id = 0;
if(isset($_GET['readMoreId'])) {
	$id = (int)$_GET['readMoreId'];
}

$savedData = $dataParams;
$type = 'button';

if(!empty($_GET['yrm_type'])) {
    $type = esc_attr($_GET['yrm_type']);
}
$savedData['type'] = $type;
if(empty($savedData)) {
	return $content;
}

echo "<script>
function yrmAddEvent(element, eventName, fn) {
    if (element.addEventListener)
        element.addEventListener(eventName, fn, false);
    else if (element.attachEvent)
        element.attachEvent('on' + eventName, fn);
}
</script>";
$savedData['moreName'] = 'More';
$savedData['lessName'] = 'less';

$dataObj = new ReadMoreData();
$dataObj->setId($id);

$includeManagerObj = new ReadMoreIncludeManager();
$includeManagerObj->setId($id);
$includeManagerObj->setDataObj($dataObj);
$includeManagerObj->setData($savedData);
$includeManagerObj->setToggleContent('It is a long established fact that a 
		reader will be distracted by the readable content of a page when looking at its layout.
		 The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, 
		 as opposed to using \'Content here, content here\', making it look like readable English.
		  Many desktop publishing packages and web page editors now use Lorem Ipsum as their default 
		  model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy.
		   Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like'
);
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
echo wp_kses($includeManagerObj->render(), $allowedTag);
?>
</div>