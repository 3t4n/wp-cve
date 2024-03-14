<div class="wrap">

<?php 

if (isset($_POST['featQuote']) || isset($_GET['featQuote'])) { 
		echo '<p class="iq-testimonials-success">Testimonial featured successfully!</p>';
 }
if (isset($_POST['unfeatQuote']) || isset($_GET['unfeatQuote'])) { 
		echo '<p class="iq-testimonials-success">Testimonial unfeatured successfully!</p>';
 }

if (isset($_POST['bulkQuote']) || isset($_GET['bulkQuote'])) {
		echo '<p class="iq-testimonials-success">Testimonial status updated successfully!</p>';
 }

if (isset($_GET['deleteQuote']) || isset($_POST['deleteQuote'])) {
		echo '<p class="iq-testimonials-success">Testimonial deleted successfully!</p>';
 } 

?>

<?php
	
	// Required for all WordPress database manipulations
	global $wpdb;
 
	// Grabbing DB prefix and settings table names to variable
	$testimonials = $wpdb->prefix . "iq_testimonials";
	$iq_testimonials_settings = $wpdb->prefix . "iq_testimonials_settings";

	$form_name = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='form_name'", ARRAY_A);
	$form_name = $form_name['value'];
	
	$form_location = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='form_location'", ARRAY_A);
	$form_location = $form_location['value'];
	
	$form_website = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='form_website'", ARRAY_A);
	$form_website = $form_website['value'];
 
	// Add
	if (isset($_POST['addQuote'])){
		$currdate = date("Y-m-d");	
		$status = $_POST['status'];		
		$name = $_POST['name'];
		$location = $_POST['location'];
		$quote = trim($_POST['quote']);
		$quote = nl2br($quote);
		$quote = stripslashes($quote);
		$website = $_POST['website'];
		$imagelink = $_POST['imagelink'];

		// start an empty array
		$formerrors = array();
		$formerrorBits = array();

		if ((empty($name)) && ($form_name == 'true')){
			$formerrorBits['name'] = true;
			$form_errors_present = true;
		}
	
		if ((empty($location)) && ($form_location == 'true')){
			$formerrorBits['location'] = true;
			$form_errors_present = true;
		}
		
		if ((empty($website)) && ($form_website == 'true')){
			$formerrorBits['website'] = true;
			$form_errors_present = true;
		}
		
		if (empty($quote)){
			$formerrorBits['quote'] = true;
			$form_errors_present = true;
		}
		
		if ($form_errors_present == true) {
			$formerrors[] = "<p class=\"iq-testimonials-error\">Please fill in all required fields.</p>\n";
		}
	
		$website_final = "";
		if (!empty($website)) {
			$website_final = (substr(ltrim($website), 0, 7) != 'http://' ? 'http://' : '') . $website;
		}
		
//		if (!empty($name) && !empty($quote)){
		if (!count($formerrors)){
	
			$insert = "INSERT INTO " . $testimonials .
			" (id, status, date, name, location, quote, website, imagelink) " .
			"VALUES ('', '" . $wpdb->escape($status) . "', '" . $wpdb->escape($currdate) . "', '" . $wpdb->escape($name) . "', '" . $wpdb->escape($location) . "', '" . $wpdb->escape($quote) . "', '" . $wpdb->escape($website_final) . "', '" . $wpdb->escape($imagelink) . "')";
		$results = $wpdb->query($insert);
		echo '<p class="iq-testimonials-success">Testimonial added successfully!</p>';
		}
		else 
		{
			// now output the errors for user to see
			foreach ($formerrors as $msg)
			$html .= $msg;
			echo $html;
		}
	};
	
	// Edit
	if (isset($_POST['editQuote'])) {
		$status = $_POST['status'];
		$name = $_POST['name'];
		$location = $_POST['location'];
		$quote = trim($_POST['quote']);
		$quote = nl2br($quote);
		$quote = stripslashes($quote);
		$website = $_POST['website'];
		$imagelink = $_POST['imagelink'];
		$id = $_POST['id'];
		
		$website_final = "";
		if (!empty($website)) {
			$website_final = (substr(ltrim($website), 0, 7) != 'http://' ? 'http://' : '') . $website;
		}
		
//		if (!empty($name) && !empty($quote)){
		if (!count($formerrors)){
			$update = "UPDATE " . $testimonials .
			" SET status='" . $wpdb->escape($status) . "', name='" . $wpdb->escape($name) . "', location='" . $wpdb->escape($location) . "', quote='" . $wpdb->escape($quote) . "', website='" . $wpdb->escape($website_final) . "', imagelink='" . $wpdb->escape($imagelink) . "' WHERE id='". $id ."'";
		echo '<p class="iq-testimonials-success">Testimonial edited successfully!</p>';
		$results = $wpdb->query($update);
		}
		else 
		{
			// now output the errors for user to see
			foreach ($formerrors as $msg)
			$html .= $msg;
			echo $html;
		}
	};
	
	// Feature
	if (isset($_POST['featQuote'])) {
		foreach($_POST as $id){
			$featsql = "UPDATE $testimonials SET featured=1 WHERE id=$id";
			$results = $wpdb->query($featsql);
		}
	};
	if (isset($_GET['featQuote'])) {
		$id = $_GET['id'];
		mysql_query("UPDATE $testimonials SET featured=1 WHERE id=$id");
	};
	
	// Unfeature
	if (isset($_POST['unfeatQuote'])) {
		foreach($_POST as $id){
			$featsql = "UPDATE $testimonials SET featured=0 WHERE id=$id";
			$results = $wpdb->query($featsql);
		}
	};
	if (isset($_GET['unfeatQuote'])) {
		$id = $_GET['id'];
		mysql_query("UPDATE $testimonials SET featured=0 WHERE id=$id");
	};
	
	// Set status bulk
	if (isset($_POST['bulkQuote'])){
		$bulkstatus = $_POST['bulkstatus'];
		$status = '';
		if ($bulkstatus == "public"){
			$status = '0';
		}
		else if ($bulkstatus == "hidden"){
			$status = '1';
		}
		else if ($bulkstatus == "pending"){
			$status = '2';
		}
		else {
			$status = '0';
		}
		foreach($_POST as $id){
			if (is_numeric($id)){
				$statussql = "UPDATE $testimonials SET status=$status WHERE id=$id";
				$results = $wpdb->query($statussql);
			}
		}
	};
	
	// Delete single
	if (isset($_GET['deleteQuote'])) {
		$id = $_GET['id'];
		mysql_query("DELETE FROM $testimonials WHERE id='$id'");
	};

	// Delete bulk
	if (isset($_POST['deleteQuote'])) {
		foreach($_POST as $id) {
		  mysql_query("DELETE FROM $testimonials WHERE id='$id'");
		}
	};

?>

<?php
if (($_GET['action'] == "edit") && (!isset($_POST['editQuote']))){
	
global $wpdb;
$testimonials = $wpdb->prefix . "iq_testimonials";

$oldid = $_GET['oldid'];
$query = "SELECT * FROM $testimonials WHERE id='$oldid'";
$results = mysql_query($query);
$count = mysql_num_rows($results);
while ($data = mysql_fetch_array($results)) {
	$id = $data['id'];
	$status = $data['status'];
	$name = stripslashes($data['name']);
	$location = stripslashes($data['location']);
	$quote = preg_replace('`<br(?: /)?>([\\n\\r])`', '$1', $data['quote']);
	$quote = stripslashes($quote);
	$website = $data['website'];
	$imagelink = $data['imagelink'];
?>
<h3 class="title">Edit Testimonial</h3>
<p>Make changes in the form below to edit a testimonial. <strong>Required fields are marked *</strong></p>

<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="name" <?php if($formerrorBits['name'] == true){echo 'class="iq-testimonials-error"';} ?>>Name <?php if ($form_name == 'true') echo '*'; ?></label></th>
			<td>
				<input type="text" id="name" name="name" value="<?php echo $name; ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="location" <?php if($formerrorBits['location'] == true){echo 'class="iq-testimonials-error"';} ?>>Location <?php if ($form_location == 'true') echo '*'; ?></label></th>
			<td>
				<input type="text" id="location" name="location" value="<?php echo $location; ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="quote" <?php if($formerrorBits['quote'] == true){echo 'class="iq-testimonials-error"';} ?>>Testimonial *</label></th>
			<td>
				<textarea class="large-text code" id="quote" cols="50" rows="3" name="quote"><?php echo $quote; ?></textarea>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="website" <?php if($formerrorBits['website'] == true){echo 'class="iq-testimonials-error"';} ?>>Website URL <?php if ($form_website == 'true') echo '*'; ?></label></th>
			<td>
				<input type="text" id="website" name="website" class="regular-text" value="<?php echo $website; ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="imagelink">Image Link</label></th>
			<td>
				<input type="text" id="imagelink" name="imagelink" class="regular-text" value="<?php echo $imagelink; ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="status">Testimonial Status *</label></th>
			<td>
				<input type="radio" name="status" value="0" <?php if ($status == 0){echo 'checked';} ?>> Public<br />
				<input type="radio" name="status" value="1" <?php if ($status == 1){echo 'checked';} ?>> Hidden<br />
				<input type="radio" name="status" value="2" <?php if ($status == 2){echo 'checked';} ?>> Pending<br />
			</td>
		</tr>
	<tbody>
</table>
	<p class="submit">
		<input type="hidden" value="editQuote" name="editQuote" />
		<input type="hidden" value="<?php echo $id; ?>" name="id" />
		<input type="submit" value="Submit" class="button-primary" name="Update" />
	</p>
</form>
	
<?php } } else { ?>
<h3 class="title">Add New Testimonial</h3>
<p>Fill in the form below to add a new testimonial. <strong>Required fields are marked *</strong></p>

<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="name" <?php if($formerrorBits['name'] == true){echo 'class="iq-testimonials-error"';} ?>>Name <?php if ($form_name == 'true') echo '*'; ?></label></th>
			<td>
				<input type="text" id="name" name="name" value="<?php echo $name; ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="location" <?php if($formerrorBits['location'] == true){echo 'class="iq-testimonials-error"';} ?>>Location <?php if($form_location == 'true') echo '*'; ?></label></th>
			<td>
				<input type="text" id="location" name="location" value="<?php echo $location; ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="quote" <?php if($formerrorBits['quote'] == true){echo 'class="iq-testimonials-error"';} ?>>Testimonial *</label></th>
			<td>
				<textarea class="large-text code" id="quote" cols="50" rows="3" name="quote"><?php echo $quote; ?></textarea>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="website" <?php if($formerrorBits['website'] == true){echo 'class="iq-testimonials-error"';} ?>>Website URL <?php if ($form_website == 'true') echo '*'; ?></label></th>
			<td>
				<input type="text" id="website" name="website" class="regular-text" value="<?php echo $website; ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="imagelink">Image Link</label></th>
			<td>
				<input type="text" id="imagelink" name="imagelink" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="status">Testimonial Status *</label></th>
			<td>
				<input type="radio" name="status" value="0" checked> Public<br />
				<input type="radio" name="status" value="1"> Hidden<br />
				<input type="radio" name="status" value="2"> Pending<br />
			</td>
		</tr>
	<tbody>
</table>
	<p class="submit">
		<input type="hidden" value="addQuote" name="addQuote" />
		<input type="submit" value="Submit" class="button-primary" name="Submit" />
	</p>
</form>
<?php } ?>
<br>
<h3 class="title">All Testimonials</h3>

<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">	
	<table cellspacing="0" class="widefat fixed">
	<thead>
		<tr class="thead">
		
		<?php if ($_GET['dir'] == "desc" || !isset($_GET['dir']) || empty($_GET['dir'])){ ?>
			<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
			<th class="column_id" id="id" scope="col" style="width:35px;"><a href="admin.php?page=iq_testimonials_admin&sort=id&dir=asc">ID</a></th>
			<th class="column_id" id="id" scope="col" style="width:30px;"><a href="admin.php?page=iq_testimonials_admin&sort=featured&dir=asc"><img src="<?php $image = get_option('siteurl') . '/wp-content/plugins/iq-testimonials/images/star.png'; echo $image; ?>" alt="" style="width:18px;" /></a></th>
			<th class="column-name" id="name" scope="col" style="width:130px;"><a href="admin.php?page=iq_testimonials_admin&sort=name&dir=asc">Name</a></th>
			<th class="column-location" id="location" scope="col" style="width:140px;"><a href="admin.php?page=iq_testimonials_admin&sort=location&dir=asc">Location</a></th>
			<th class="column-testimonial" id="testimonial" scope="col"><a href="admin.php?page=iq_testimonials_admin&sort=quote&dir=asc">Testimonial</a></th>
			<th class="column-website" id="website" scope="col" style="width:120px;"><a href="admin.php?page=iq_testimonials_admin&sort=website&dir=asc">Website</a></th>
			<th class="column-imagelink" id="imagelink" scope="col" style="width:120px;"><a href="admin.php?page=iq_testimonials_admin&sort=imagelink&dir=asc">Image Link</a></th>
			<th class="column-status" id="status" scope="col" style="width:60px;"><a href="admin.php?page=iq_testimonials_admin&sort=status&dir=asc">Status</a></th>
		<?php } else { ?>
			<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
			<th class="column_id" id="id" scope="col" style="width:35px;"><a href="admin.php?page=iq_testimonials_admin&sort=id&dir=desc">ID</a></th>
			<th class="column_id" id="id" scope="col" style="width:30px;"><a href="admin.php?page=iq_testimonials_admin&sort=featured&dir=desc"><img src="<?php $image = get_option('siteurl') . '/wp-content/plugins/iq-testimonials/images/star.png'; echo $image; ?>" alt="" style="width:18px;" /></a></th>
			<th class="column-name" id="name" scope="col" style="width:130px;"><a href="admin.php?page=iq_testimonials_admin&sort=name&dir=desc">Name</a></th>
			<th class="column-location" id="location" scope="col" style="width:140px;"><a href="admin.php?page=iq_testimonials_admin&sort=location&dir=desc">Location</a></th>
			<th class="column-testimonial" id="testimonial" scope="col"><a href="admin.php?page=iq_testimonials_admin&sort=quote&dir=desc">Testimonial</a></th>
			<th class="column-website" id="website" scope="col" style="width:100px;"><a href="admin.php?page=iq_testimonials_admin&sort=website&dir=desc">Website</a></th>
			<th class="column-imagelink" id="imagelink" scope="col" style="width:100px;"><a href="admin.php?page=iq_testimonials_admin&sort=imagelink&dir=desc">Website</a></th>
			<th class="column-status" id="status" scope="col" style="width:60px;"><a href="admin.php?page=iq_testimonials_admin&sort=status&dir=desc">Status</a></th>		
		<?php } ?>

		</tr>
	</thead>
	<tbody>
<?php
	global $wpdb;
	$testimonials = $wpdb->prefix . "iq_testimonials";
	
	$sort = "";
	if (isset($_GET['sort']) && !empty($_GET['sort'])){
		$sort = $_GET['sort'];
	}
	else {
		$sort = "id";
	}
	
	$dir = "";
	if (isset($_GET['dir']) && !empty($_GET['dir'])){
		$dir = $_GET['dir'];
	}
	else {
		$dir = "asc";
	}
	  
	$query = "SELECT * FROM $testimonials ORDER BY $sort $dir";
	$results = mysql_query($query);
	$count = mysql_num_rows($results);
	while ($data = mysql_fetch_array($results)) {
		$id = $data['id'];
		$status = $data['status'];
		
		$rawdate = $data['date'];
			$year = date("Y", strtotime($rawdate));
			$month = date("M", strtotime($rawdate));
			$day = date("j", strtotime($rawdate));
		$date = $month." ".$day.", ".$year;
		
		$featured = $data['featured'];
		$name = stripslashes($data['name']);
		$location = stripslashes($data['location']);
		$quote = stripslashes($data['quote']);
		$website = $data['website'];
		$imagelink = $data['imagelink'];
?>
	<tr style="background-color:<?php if ($status == 0) { echo "#8CFF8C"; } else if ($status == 1) { echo "#FF7171"; } else if ($status == 2) { echo "#FFFF80"; } ?>">
		<th class="check-column" scope="row">
			<input type="checkbox" value="<?php echo $id; ?>" class="administrator" id="<?php echo $id; ?>" name="<?php echo $id; ?>"/>
		</th>
		<td class="id column-id">
			<?php echo $id; ?>
		</td>
		<td class="featured column-featured" style="width:10px;">
			<?php if ($featured == 1){ ?><a href="?page=iq_testimonials_admin&amp;unfeatQuote&amp;id=<?php echo $id; ?>" class="feat"><span>*</span></a>
			<?php } else if ($featured == 0){ ?><a href="?page=iq_testimonials_admin&amp;featQuote&amp;id=<?php echo $id; ?>" class="unfeat"><span>*</span></a><?php } ?>
		</td>
		<td class="name column-name" style="width: 105px;">
			<?php echo $name; ?>
			<div class="row-actions"><span class='edit'><a href="?page=iq_testimonials_admin&amp;action=edit&amp;oldid=<?php echo $id; ?>" title="Edit this post">Edit</a> | </span><span class='delete'><a class='submitdelete' title='Delete this testimonial' href='?page=iq_testimonials_admin&amp;deleteQuote&amp;id=<?php echo $id; ?>' onclick="if ( confirm('You are about to delete a testimonial by \'<?php echo $name; ?>\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span></div>
		</td>
		<td class="location column-location">
			<?php echo $location; ?>
		</td>
		<td class="quote column-quote">
			<?php echo $quote; ?>
		</td>
		<td class="website column-website">
			<a target="_blank" href="<?php echo $website; ?>"><?php echo str_replace("http://", "", $website); ?></a>
		</td>
        <td class="website column-wimagelink">
			<a target="_blank" href="<?php echo $imagelink; ?>"><?php echo $imagelink; ?></a>
		</td>
		<td class="status column-status">
			<?php if ($status == 0) { echo "Public"; } else if ($status == 1) { echo "Hidden"; } else if ($status == 2) { echo "Pending"; } ?>
		</td>
	</tr>

<?php
}
	mysql_free_result($results);
	if ($count < 1) {
?>
	<tr>
		<th class="check-column" scope="row"></th>
		<td class="name column-name" colspan="6">
			<p>There aren't any testimonials yet!</p>
		</td>
	</tr>
<?php } ?>
	</tbody>
	<tfoot>
		<tr class="thead">
			<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
			<th class="column_id" id="id" scope="col">ID</th>
			<th class="column_id" id="id" scope="col"><img src="<?php $image = get_option('siteurl') . '/wp-content/plugins/iq-testimonials/images/star.png'; echo $image; ?>" alt="" style="width:18px;" /></th>
			<th class="column-name" id="name" scope="col">Name</th>
			<th class="column-location" id="location" scope="col">Location</th>
			<th class="column-testimonial" id="testimonial" scope="col">Testimonial</th>
			<th class="column-website" id="website" scope="col">Website</th>
			<th class="column-imagelink" id="imagelink" scope="col">Image Link</th>
			<th class="column-status" id="status" scope="col">Status</th>
		</tr>
	</tfoot>
</table>

	<p class="submit">
		<input type="submit" value="<?php echo __('Delete','iq-testimonials'); ?>" class="button-primary" name="deleteQuote" /> |		<input type="submit" value="<?php echo __('Feature','iq-testimonials'); ?>" class="button-secondary" name="featQuote" />
		<input type="submit" value="<?php echo __('Unfeature','iq-testimonials'); ?>" class="button-secondary" name="unfeatQuote" /> |
		<select name="bulkstatus">
			<option value="">--</option>
			<option value="public">Public</option>
			<option value="hidden">Hidden</option>
			<option value="pending">Pending</option>
		</select>
		<input type="submit" value="<?php echo __('Set Status','iq-testimonials'); ?>" class="button-secondary" name="bulkQuote" />
	</p>
</form>
</div>