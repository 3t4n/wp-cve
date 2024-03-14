<?php

defined('WPW') or die('No script kiddies please!');

?>
<div class="wrap about-wrap">
	<h1 style="margin-bottom:12px">WP Webcam Widget and Shortcode Info</h1>
	
	<table class="widefat" style="margin-top:12px" cellspacing="0">
    	<thead>
    		<tr>
				<th>Shortcode</th>
				<th>Descrizione</th>
    		</tr>
    	</thead>
		
    	<tbody>
    		<tr>
    			<td>[webcam url=""]</td>
				<td><?php _e('Show a streaming image from url by refreshing the image every 5 seconds','wpwws'); ?></td>
    		</tr>
    		<tr>
    			<td>[webcam url="" refresh="X"]</td>
				<td><?php _e('Show a streaming image from url by refreshing the image every X seconds','wpwws'); ?></td>
    		</tr>
    		<tr>
    			<td>[webcam url="" alt="My Webcam!"]</td>
				<td><?php _e('Show a streaming image from url by refreshing the image every 5 seconds and add the alternate text image for <em>img</em> html tag.','wpwws'); ?></td>
    		</tr>
    		<tr>
    			<td>[webcam url="" refresh="X" alt="My Webcam!"]</td>
				<td><?php _e('Show a streaming image from url by refreshing the image every X seconds and add the alternate text image for <em>img</em> html tag.','wpwws'); ?></td>
    		</tr>
    	</tbody>
    </table>
</div>