<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// place svg files in this folder
// npm i -g svgo@1.3.2
// svgo --disable={cleanupIDs,removeViewBox} --enable=removeDimensions -f . ./out/
// mv ./out/* .
$myfile = fopen("icon_rsrc.php", "w");
$data = [];
foreach (glob("*.svg") as $filename) {
    $name = str_replace('.svg', '', $filename);
    $content = (file_get_contents($filename));
    $data[$name] = $content;
}
ob_start();
var_export($data);
$c = ob_get_clean();
fwrite($myfile, "<?php \n return $c");
fwrite($myfile, ";");


fclose($myfile);
