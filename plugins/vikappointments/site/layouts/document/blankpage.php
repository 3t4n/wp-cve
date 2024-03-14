<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$app      = JFactory::getApplication();
$document = JFactory::getDocument();

$body  = isset($displayData['body'])   ? $displayData['body']  : '';
$title = !empty($displayData['title']) ? $displayData['title'] : $app->get('sitename');

if (!headers_sent())
{
	$app->setHeader('Content-Type', 'text/html; charset=' . $document->getCharset());
	$app->sendHeaders();
}

?>

<!DOCTYPE html>
<html lang="<?php echo $document->getLanguage(); ?>" dir="<?php echo $document->getDirection(); ?>">
	<head>
		<meta charset="<?php echo $document->getCharset(); ?>" />
		<meta http-equiv="content-type" content="text/html; charset=<?php echo $document->getCharset(); ?>" />
		<meta name="robots" content="nofollow" />
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="HandheldFriendly" content="true" />
		<title><?php echo $title; ?></title>
	</head>
	<body>
		<?php echo $body; ?>
	</body>
</html>
