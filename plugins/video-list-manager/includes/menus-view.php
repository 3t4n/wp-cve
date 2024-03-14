<?php 
/**
 * Custom Menu Display
 * 
 * Author: Tung Pham
 */

/**
 * Function to display the "video manage" page
 */
function tnt_video_manage(){
	?>
		<div class="wrap">
			<?php screen_icon('upload') ?>
			<h2>Video Manage</h2>
			<hr />
			<?php
				//Get var filter
				$catID = (isset($_GET["vCat"])) ? $_GET["vCat"] : 0;
				$typeID = (isset($_GET["vLinkType"])) ? $_GET["vLinkType"] : 0;
				$orderBy = (isset($_GET["orderBy"])) ? $_GET["orderBy"] : null;
				$order = (isset($_GET["order"])) ? $_GET["order"] : null;

				$tntVideos = TNT_Video::tntGetVideos(array('catID' => $catID, 'typeID' => $typeID));
				$items = count($tntVideos);


				//Get Plugin Options
				$tntOptions = get_option('tntVideoManageOptions');
				$numLimit = $tntOptions['limitAdminPerPage']; 
				if($items > $numLimit) {
			        $p = new TNT_Pagination();
			        $p->items($items);
			        $p->limit($numLimit); // Limit entries per page
			        $p->target($_SERVER["REQUEST_URI"]); 
			        $p->calculate(); // Calculates what to show
			        $p->parameterName('paged');
			        $p->adjacents(1); //No. of page away from the current page
			                 
			        if(!isset($_GET['paged'])) {
			            $p->page = 1;
			        } else {
			            $p->page = $_GET['paged'];
			        }
			        $p->currentPage($p->page); // Gets and validates the current page
			         
			        //Query for limit paging
			        $limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
				         
				} else {
				    $limit = "LIMIT 0, 10";
				}
			?>
			<!-- Filter -->
			<form method="GET" action="">
				<input type="hidden" name="page" value="tnt_video_manage_page" />	
				<table width="100%">
					<tr>
						<td width="33%">			
							Filter by Category: <?php echo TNT_VideoCat::tntDisplayListCat($catID); ?>	
						</td>
						<td width="33%">
							Filter by Type: <?php echo TNT_VideoType::tntDisplayListType($typeID); ?>
						</td>
						<td width="34%">
							Order by: 
							<select name="orderBy">
								<option <?php echo ($orderBy == 'date_created') ? "selected" : "" ?> value="date_created">Created Date</option>
								<option <?php echo ($orderBy == 'date_modified') ? "selected" : "" ?> value="date_modified">Editing Date</option>
								<option <?php echo ($orderBy == 'video_title') ? "selected" : "" ?> value="video_title">Title</option>
								<option <?php echo ($orderBy == 'video_link_type') ? "selected" : "" ?> value="video_link_type">Type</option>
								<option <?php echo ($orderBy == 'video_status') ? "selected" : "" ?> value="video_status">Status</option>
								<option <?php echo ($orderBy == 'video_order') ? "selected" : "" ?> value="video_order">Order Number</option>
							</select>
							<select name="order">
								<option <?php echo ($order == 'ASC') ? "selected" : "" ?> value="ASC">Ascending</option>
								<option <?php echo ($order == 'DESC') ? "selected" : "" ?> value="DESC">Descending</option>
							</select>
							<input type="submit" value="Filter" class="button-secondary" />
						</td>
					</tr>
				</table>
			</form>
			<!-- Filter -->
			<!-- Message -->
			<div class='<?php echo ($items > 0) ? "updated" : "error" ?>'><p><?php echo $items ?> Result(s) found!</p></div>    
			<!--End  Message -->

			<form method="POST" action="">
			<table>
				<tr>
					<td align="left" width="70">Actions</td>
					<td align="left" width="95">
						<select name="tntActions">
							<option value="1">Publish</option>
							<option value="2">Unpublish</option>
							<option value="3">Delete</option>
						</select>
					</td>
					<td align="left" width="120"><input type="submit" class="button-secondary btnAct" name="tntBtnAct" value="Update" /></td>	
				</tr>
			</table>
			<?php 
				//show message
				if(isset($_GET["m"]))
				{
					$m = $_GET["m"];
					if($m) 
					{
						showMessage("Your video(s) updated successfully!", $m);
					}
					else
					{
						showMessage("Your video(s) updated failed!", $m);	
					}	
				}
			 ?>
			<!-- List Video -->
			<table class="tntTable widefat">
				<thead>
					<tr>
						<td><input type="checkbox" name="tntChkAll" class="tntChkAll" /></td>
						<th>ID</th>
						<th>Title</th>
						<th>Category</th>
						<th>Type</th>
						<th>Link</th>
						<th>Status</th>
						<th width="50">Order Num</th>
						<th width="100">Created Date (GMT + 0:00)</th>
						<th width="100">Last Modified (GMT + 0:00)</th>
						<th>Action</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th>ID</th>
						<th>Title</th>
						<th>Category</th>
						<th>Type</th>
						<th>Link</th>
						<th>Status</th>
						<th>Order Num</th>
						<th>Created Date (GMT +0)</th>
						<th>Last Modified (GMT +0)</th>
						<th>Action</th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
						$tntVideos = TNT_Video::tntGetVideos(array('catID' => $catID, 'typeID' => $typeID, 'limitText' => $limit, 'orderBy' => $orderBy, 'order' => $order));
						foreach ($tntVideos as $tntV):
					 ?>
							<tr>
								<td><input type="checkbox" name="tntChkVideos[]" class="tntSubChk" value="<?php echo $tntV->video_id ?>" /></td>
								<td><a href="<?php echo admin_url() ?>admin.php?page=tnt_video_edit_page&videoID=<?php echo $tntV->video_id; ?>"><b><?php echo $tntV->video_id ?></b></a></td>
								<td><b><a href="<?php echo admin_url() ?>admin.php?page=tnt_video_edit_page&videoID=<?php echo $tntV->video_id; ?>"><?php echo $tntV->video_title ?></a></b></td>
								<td><?php echo $tntV->video_cat_title ?></td>
								<td>
									<?php 
										switch ($tntV->video_type_title) {
											case 'DailyMotion':
												echo '<img src="'.TNT_IMG_URL.'/dailymotion.gif" alt="'.$tntV->video_type_title.'" title="'.$tntV->video_type_title.'" />';
												break;
											case 'Vimeo':
												echo '<img src="'.TNT_IMG_URL.'/vimeo.gif" alt="'.$tntV->video_type_title.'" title="'.$tntV->video_type_title.'" />';
												break;
											default:
												echo '<img src="'.TNT_IMG_URL.'/utube.gif" alt="'.$tntV->video_type_title.'" title="'.$tntV->video_type_title.'" />';
												break;
										}
									 ?>
								</td>
								<td><a href="<?php echo $tntV->video_link ?>" target=_blank>Click to watch</a></td>
								<td><?php echo ($tntV->video_status) ? '<img src="'.TNT_IMG_URL.'/publish.gif" alt="Published" />' : '<img src="'.TNT_IMG_URL.'/unpublish.gif" alt="Unpublished" />' ?></td>
								<td><?php echo $tntV->video_order ?></td>
								<td><?php echo date('Y-m-d H:i:s', $tntV->date_created); ?></td>
								<td><?php echo date('Y-m-d H:i:s', $tntV->date_modified); ?></td>
								<td>
									<a href="<?php echo admin_url() ?>admin.php?page=tnt_video_edit_page&videoID=<?php echo $tntV->video_id; ?>" class="button-secondary">Edit</a> 
									<a href="<?php echo admin_url() ?>admin.php?page=tnt_video_del_page&videoID=<?php echo $tntV->video_id; ?>" class="button-secondary">Delete</a>
								</td>
							</tr>
					 <?php endforeach ?>
				</tbody>
			</table><!-- List Video -->

			<?php if ($items > $numLimit): ?>
				<div class="tablenav">
				    <div class='tablenav-pages'>
				        <?php echo $p->show();  // Echo out the list of paging. ?>
				    </div>
				</div>				
			<?php endif ?>
			</form>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function($){

				//check if have any videos checked
				$(".btnAct").click(function(e){
					var rs = false;

					$('.tntTable tr').each (function ()
					{
						var checkbox = $(this).find(".tntSubChk");
						if (checkbox.is (':checked'))
						{
							rs = true;
						}
					});

					if(rs == false)
					{
						alert("No Items checked");
						e.preventDefault();	
					}
				});

				$(".tntChkAll").click(function(){
					if($(this).is(":checked"))
					{
						$('.tntTable tr').each (function ()
						{
							var checkbox = $(this).find(".tntSubChk");
							checkbox.attr("checked", "checked");
						});
					}
					else
					{
						$('.tntTable tr').each (function ()
						{
							var checkbox = $(this).find(".tntSubChk");
							checkbox.removeAttr("checked");
						});	
					}
				});
			});	
		</script>
	<?php
}//tnt_video_manage

/**
 * Function to display the "add video" page
 */
function tnt_video_add(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Add Video</h2>
			<?php 
				//show message
				if(isset($_GET["m"]))
				{
					$m = $_GET["m"];
					if($m) 
					{
						showMessage("Your video(s) added successfully!", $m);
					}
					else
					{
						showMessage("Your video(s) added failed!", $m);	
					}	
				}
			 ?>
			<form id="addVideoForm" method="POST" action="">
				<div id="message" class="errorContainer error dpn"></div>
				<?php 
					$currentUser = wp_get_current_user();
				?>
				<input type="hidden" name="vUserID" value="<?php echo $currentUser->ID; ?>" />
				<table class="borderB form-table">
					<tr valign="top">
						<th scope="row"><label for="vCat">Select Category</label></th>
						<td>
							<?php echo TNT_VideoCat::tntDisplayListCat(); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="vLinkType">Type</label></th>
						<td>
							<?php echo TNT_VideoType::tntDisplayListType(); ?>
						</td>
					</tr>
				</table>
				<div class="infoVideoWrapper">
					<table class="infoVideo borderDB form-table">
						<tr valign="top">
							<th scope="row"><label for="vTitle">Title</label></th>
							<td><input type="text" class="required" size="50" name="vTitle[]" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="vLink">Link</label></th>
							<td><input type="url" class="required" size="50" name="vLink[]" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="vStatus">Status</label></th>
							<td>
								<select name="vStatus[]">
									<option value="1">Published</option>
									<option value="0">Unpublished</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="vOrder">Order Number</label></th>
							<td><input type="text" class="required digits" size="3" name="vOrder[]" value="100" /></td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td><a href="#" class="removeVideoItem button-secondary" title="Remove Video Item">Remove</a></td>
						</tr>
					</table>
				</div>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"></th>
						<td>
							<input type="submit" name="tntAddVideo" value="Add Video" class="button-primary"/>
							<button class="addMoreVideo button-secondary">Add More</button>
							<input type="submit" name="reset" value="Reset" class="button-secondary">
						</td>
					</tr>
				</table>
			</form>
		</div>
	<?php
}//tnt_video_add

/**
 * Function to display the "edit video" page
 */
function tnt_video_edit(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Edit Video</h2>
			<?php
				$videoID = (isset($_GET['videoID'])) ? $_GET['videoID'] : 0;
				$v = new TNT_Video(); 
				if($videoID != 0)
				{
					$v->tntGetVideo($videoID);		
				}
				else
				{
					wp_die("videoID not found");
				}

				//show message
				if(isset($_GET["m"]))
				{
					$m = $_GET["m"];
					if($m) 
					{
						showMessage("Your video edited successfully!", $m);
					}
					else
					{
						showMessage("Your video edited failed!", $m);	
					}	
				}
			 ?>
			<form id="editVideoForm" method="POST" action="">
				<input type="hidden" name="vID" value="<?php echo $v->videoID ?>" />
				<?php 
					$currentUser = wp_get_current_user();
				?>
				<input type="hidden" name="vUserID" value="<?php echo $currentUser->ID; ?>" />
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="vCat">Category</label></th>
						<td>
							<?php echo TNT_VideoCat::tntDisplayListCat($v->videoCat); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="vLinkType">Type</label></th>
						<td>
							<?php echo TNT_VideoType::tntDisplayListType($v->videoType); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="vTitle">Title</label></th>
						<td><input type="text" size="50" name="vTitle" value="<?php echo $v->videoTitle ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="vLink">Link</label></th>
						<td><input type="text" size="50" name="vLink" value="<?php echo $v->videoLink ?>"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="vStatus">Published ?</label></th>
						<td><input type="checkbox" name="vStatus" <?php echo ($v->videoStatus == 1) ? "checked" : "" ?> value="1" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="vOrder">Order Number</label></th>
						<td><input type="text" size="3" name="vOrder" value="<?php echo $v->videoOrder ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td>
							<input type="submit" name="tntEditVideo" value="Edit Video" class="button-primary"/>
							<input type="submit" name="reset" value="Reset" class="button-secondary">
						</td>
					</tr>
				</table>
			</form>
		</div>
	<?php
}//tnt_video_edit

/**
 * Function to display the "delete video" page
 */
function tnt_video_del(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Delete Video</h2>
			<?php
				$v = new TNT_Video(); 
				$videoID = (isset($_GET['videoID'])) ? $_GET['videoID'] : 0;
				if($videoID != 0)
				{
					$v->tntGetVideo($videoID);		
				}
				else
				{
					wp_die("videoID not found");
				}
			 ?>
			<form method="POST" action="">
				<input type="hidden" name="vID" value="<?php echo $v->videoID ?>" />
				<div style="padding-bottom: 10px;">
					<p>Do you want to delete the video <a href="<?php echo $v->videoLink; ?>"><?php echo $v->videoTitle ?></a> ?</p>
					<input type="submit" name="tntDelVideo_Yes" class="button-secondary" value="Yes" />
					<input type="submit" name="tntDelVideo_No" class="button-secondary" value="No" />
				</div>
			</form>
		</div>
	<?php
}//tnt_video_del

/**
 * Function to display the "video type manage" page
 */
function tnt_video_type_manager(){
	?>
		<div class="wrap">
			<?php screen_icon('upload') ?>
			<h2>Video Type Manage</h2>
			<hr />
			<table class="tntTable widefat">

				<thead>
					<tr>
						<th>Type</th>
						<th>Action</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Type</th>
						<th>Action</th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
						$tntVideoTypes = TNT_VideoType::tntGetTypes();
						foreach ($tntVideoTypes as $tntT):
					 ?>
							<tr>
								<td><?php echo $tntT->video_type_title ?></td>
								<td>
									<a href="<?php echo admin_url() ?>admin.php?page=tnt_video_type_edit_page&typeID=<?php echo $tntT->video_type_id; ?>" class="button-secondary">Edit</a> 
									<a href="<?php echo admin_url() ?>admin.php?page=tnt_video_type_del_page&typeID=<?php echo $tntT->video_type_id; ?>" class="button-secondary">Delete</a>
								</td>
							</tr>
					 <?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php
}//tnt_video_type_manager

/**
 * Function to display the "add video type" page
 */
function tnt_video_type_add(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Add Video Type</h2>
			<?php 
				//show message
				if(isset($_GET["m"]))
				{
					$m = $_GET["m"];
					if($m) 
					{
						showMessage("Your video type added successfully!", $m);
					}
					else
					{
						showMessage("Your video type added failed!", $m);	
					}	
				}
			 ?>
			<form method="POST" action="">
				<table class="tntFormTable form-table">
					<tr valign="top">
						<th scope="row"><label for="typeTitle">Type</label></th>
						<td>
							<select name="typeTitle">
								<option value="Youtube">Youtube</option>
								<option value="Vimeo">Vimeo</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td>
							<input type="submit" name="tntAddVideoType" value="Add Type" class="button-primary"/>
							<input type="submit" name="reset" value="Reset" class="button-secondary">
						</td>
					</tr>
				</table>
			</form>
		</div>
	<?php
}//tnt_video_type_add

/**
 * Function to display the "edit video type" page
 */
function tnt_video_type_edit(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Edit Video Type</h2>
			<?php
				$typeID = (isset($_GET['typeID'])) ? $_GET['typeID'] : 0;
				$t = new TNT_VideoType(); 
				if($typeID != 0)
				{
					$t->tntGetType($typeID);	
				}
				else
				{
					wp_die("typeID not found");
				}

				//show message
				if(isset($_GET["m"]))
				{
					$m = $_GET["m"];
					if($m) 
					{
						showMessage("Your video type edited successfully!", $m);
					}
					else
					{
						showMessage("Your video type edited failed!", $m);	
					}	
				}
			 ?>
			<form method="POST" action="">
				<input type="hidden" name="typeID" value="<?php echo $t->videoTypeID ?>" />
				<table class="tntFormTable form-table">
					<tr valign="top">
						<th scope="row"><label for="typeTitle">Type</label></th>
						<td>
							<select name="typeTitle">
								<option value="Youtube" <?php echo ($t->videoTypeTitle == "Youtube") ? "selected" : "" ?>>Youtube</option>
								<option value="Vimeo" <?php echo ($t->videoTypeTitle == "Vimeo") ? "selected" : "" ?>>Vimeo</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td>
							<input type="submit" name="tntEditVideoType" value="Edit Type" class="button-primary"/>
							<input type="submit" name="reset" value="Reset" class="button-secondary">
						</td>
					</tr>
				</table>
			</form>
		</div>
	<?php
}//tnt_video_type_edit

/**
 * Function to display the "delete video type" page
 */
function tnt_video_type_del(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Delete Video Type</h2>
			<?php
				$t = new TNT_VideoType(); 
				$typeID = (isset($_GET['typeID'])) ? $_GET['typeID'] : 0;
				if($typeID != 0)
				{
					$t->tntGetType($typeID);
				}
				else
				{
					wp_die("typeID not found");
				}
			 ?>
			<form method="POST" action="">
				<input type="hidden" name="typeID" value="<?php echo $t->videoTypeID ?>" />
				<div style="padding-bottom: 10px;">
					<p>Do you want to delete the video type "<?php echo $t->videoTypeTitle ?> [<?php echo $t->videoTypeWidth ?>x<?php echo $t->videoTypeHeight ?>]" ? </p>
					<input type="submit" name="tntDelVideoType_Yes" class="button-secondary" value="Yes" />
					<input type="submit" name="tntDelVideoType_No" class="button-secondary" value="No" />
				</div>
			</form>
		</div>
	<?php
}//tnt_video_del

/**
 * Function to display the "video category manage" page
 */
function tnt_video_cat_manager(){
	?>
		<div class="wrap">
			<?php screen_icon('upload') ?>
			<h2>Video Category Manage</h2>
			<hr />
			<table class="tntTable widefat">

				<thead>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Amount of Videos</th>
						<th>Parent</th>
						<th>Shortcode</th>
						<th>Action</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Amount of Videos</th>
						<th>Parent</th>
						<th>Shortcode</th>
						<th>Action</th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
						$tntVideoCats = TNT_VideoCat::tntGetCats();
						foreach ($tntVideoCats as $tntC):
					 ?>
							<tr>
								<td><?php echo $tntC->video_cat_id ?></td>
								<td><b><a href="<?php echo admin_url() ?>admin.php?page=tnt_video_cat_edit_page&catID=<?php echo $tntC->video_cat_id; ?>"><?php echo $tntC->video_cat_title ?></a></b></td>
								<td>
									<?php 
										$videos = TNT_Video::tntGetVideos(array('catID'=>$tntC->video_cat_id)) ;
										$videosCount = count($videos);
									?>
									<b><a href="<?php echo admin_url() ?>admin.php?page=tnt_video_manage_page&vCat=<?php echo $tntC->video_cat_id; ?>"><?php echo $videosCount ?></a></b>
								</td>
								<td><?php echo $tntC->video_cat_parent_id ?></td>
								<td><?php echo '[tnt_video_list id='.$tntC->video_cat_id.']' ?></td>
								<td>
									<a href="<?php echo admin_url() ?>admin.php?page=tnt_video_cat_edit_page&catID=<?php echo $tntC->video_cat_id; ?>" class="button-secondary">Edit</a> 
									<a href="<?php echo admin_url() ?>admin.php?page=tnt_video_cat_del_page&catID=<?php echo $tntC->video_cat_id; ?>" class="button-secondary">Delete</a>
								</td>
							</tr>
					 <?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php
}//tnt_video_cat_manager

/**
 * Function to display the "add video category" page
 */
function tnt_video_cat_add(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Add Video Category</h2>
			<?php 
				//show message
				if(isset($_GET["m"]))
				{
					$m = $_GET["m"];
					if($m) 
					{
						showMessage("Your video category added successfully!", $m);
					}
					else
					{
						showMessage("Your video category added failed!", $m);	
					}	
				}
			 ?>
			<form method="POST" action="">
				<table class="tntFormTable form-table">
					<tr valign="top">
						<th scope="row"><label for="catTitle">Category Title</label></th>
						<td>
							<input type="text" size="50" name="catTitle" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td>
							<input type="submit" name="tntAddVideoCat" value="Add Category" class="button-primary"/>
							<input type="submit" name="reset" value="Reset" class="button-secondary">
						</td>
					</tr>
				</table>
			</form>
		</div>
	<?php
}//tnt_video_cat_add

/**
 * Function to display the "edit video category" page
 */
function tnt_video_cat_edit(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Add Video Category</h2>
			<?php
				$catID = (isset($_GET['catID'])) ? $_GET['catID'] : 0;
				$c = new TNT_VideoCat(); 
				if($catID != 0)
				{
					$c->tntGetCat($catID);	
				}
				else
				{
					wp_die("catID not found");
				}

				//show message
				if(isset($_GET["m"]))
				{
					$m = $_GET["m"];
					if($m) 
					{
						showMessage("Your video category edited successfully!", $m);
					}
					else
					{
						showMessage("Your video category edited failed!", $m);	
					}	
				}
			 ?>
			<form id="editVideoCatForm" method="POST" action="">
				<input type="hidden" name="catID" value="<?php echo $c->videoCatID ?>" /> 
				<table class="tntFormTable form-table">
					<tr valign="top">
						<th scope="row"><label for="catTitle">Category Title</label></th>
						<td>
							<input type="text" size="50" name="catTitle" value="<?php echo $c->videoCatTitle ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td>
							<input type="submit" name="tntEditVideoCat" value="Edit Category" class="button-primary"/>
							<input type="submit" name="reset" value="Reset" class="button-secondary">
						</td>
					</tr>
				</table>
			</form>
		</div>
	<?php
}//tnt_video_cat_edit

/**
 * Function to display the "delete video cat" page
 */
function tnt_video_cat_del(){
	?>
		<div class="wrap">
			<?php screen_icon('edit') ?>
			<h2>Delete Video Category</h2>
			<?php
				$c = new TNT_VideoCat(); 
				$catID = (isset($_GET['catID'])) ? $_GET['catID'] : 0;
				if($catID != 0)
				{
					$c->tntGetCat($catID);
				}
				else
				{
					wp_die("catID not found");
				}
			 ?>
			<form method="POST" action="">
				<input type="hidden" name="catID" value="<?php echo $c->videoCatID ?>" />
				<div style="padding-bottom: 10px;">
					<p>Do you want to delete the video type "<?php echo $c->videoCatTitle ?>" ? </p>
					<input type="submit" name="tntDelVideoCat_Yes" class="button-secondary" value="Yes" />
					<input type="submit" name="tntDelVideoCat_No" class="button-secondary" value="No" />
				</div>
			</form>
		</div>
	<?php
}//tnt_video_cat_del

/**
 * Function to display the "video option" page
 */
function tnt_video_option(){
	?>
		<div class="wrap">
			<?php screen_icon('options-general') ?>
			<h2>Video Manager Default Settings</h2>
			<?php 
				//show message
				if(isset($_GET["m"]))
				{
					$m = $_GET["m"];
					if($m) 
					{
						showMessage("Your options updated successfully!", $m);
					}
					else
					{
						showMessage("Your options updated failed!", $m);	
					}	
				}

				//Get Plugin Options
				$tntOptions = get_option('tntVideoManageOptions');
			 ?>
			<form id="optionVideoForm" method="POST" action="">
				<table class="tntFormTable form-table">
					<tr valign="top">
						<th scope="row"><label for="videoLimit">Limit videos per page (Frontend)</label></th>
						<td>
							<input type="text" size="50" name="videoLimit" value="<?php echo $tntOptions['limitPerPage']; ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="videoLimitAdmin">Limit videos per page (Backend)</label></th>
						<td>
							<input type="text" size="50" name="videoLimitAdmin" value="<?php echo $tntOptions['limitAdminPerPage']; ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="videoColumn">Column(s) per row</label></th>
						<td>
							<input type="text" size="50" name="videoColumn" value="<?php echo $tntOptions['columnPerRow']; ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="videoOrder">Default order</label></th>
						<td>
							<select name="videoOrder">
								<option <?php echo ($tntOptions['videoOrder'] == 'videoid') ? 'selected' : ""; ?> value="videoid">Video ID</option>
								<option <?php echo ($tntOptions['videoOrder'] == 'addingdate') ? 'selected' : ""; ?> value="addingdate">Created Date</option>
								<option <?php echo ($tntOptions['videoOrder'] == 'editingdate') ? 'selected' : ""; ?> value="editingdate">Modified Date</option>
								<option <?php echo ($tntOptions['videoOrder'] == 'alphabet') ? 'selected' : ""; ?> value="alphabet">Alphabet</option>
								<option <?php echo ($tntOptions['videoOrder'] == 'ordernumber') ? 'selected' : ""; ?> value="ordernumber">Order Number</option>
							</select>
							<label for="videoOrderByASC"><input type="radio" name="videoOrderBy" <?php echo ($tntOptions['videoOrderBy'] == 'asc') ? 'checked' : ""; ?> id="videoOrderByASC" value="asc" /> Ascending</label>
							<label for="videoOrderByDESC"><input type="radio" name="videoOrderBy" <?php echo ($tntOptions['videoOrderBy'] == 'desc') ? 'checked' : ""; ?> id="videoOrderByDESC" value="desc" /> Descending</label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="videoWidth">Default video width</label></th>
						<td>
							<input type="text" size="50" name="videoWidth" value="<?php echo $tntOptions['videoWidth']; ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="videoHeight">Default video height</label></th>
						<td>
							<input type="text" size="50" name="videoHeight" value="<?php echo $tntOptions['videoHeight']; ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="videoJquery">Load Jquery</label></th>
						<td>
							<input type="checkbox" name="tntJquery" <?php echo ($tntOptions['tntJquery']) ? "checked" : "" ?> value="1" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="videoColorbox">Load Colorbox Jquery</label></th>
						<td>
							<input type="checkbox" name="tntColorbox" <?php echo ($tntOptions['tntColorbox']) ? "checked" : "" ?> value="1" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="videoColorbox">Skin Colorbox</label></th>
						<td>
							<select name="skinColorbox">
								<option <?php echo ($tntOptions['skinColorbox'] == 1) ? "selected" : "" ?> value="1">Skin 1</option>
								<option <?php echo ($tntOptions['skinColorbox'] == 2) ? "selected" : "" ?> value="2">Skin 2</option>
								<option <?php echo ($tntOptions['skinColorbox'] == 3) ? "selected" : "" ?> value="3">Skin 3</option>
								<option <?php echo ($tntOptions['skinColorbox'] == 4) ? "selected" : "" ?> value="4">Skin 4</option>
								<option <?php echo ($tntOptions['skinColorbox'] == 5) ? "selected" : "" ?> value="5">Skin 5</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="tntSocialFeature">Enable Social Feature</label></th>
						<td>
							<input class="tntSocialFeature" type="checkbox" name="tntSocialFeature" <?php echo ($tntOptions['socialFeature']) ? "checked" : "" ?> value="1" />
							<div class="socialFeatureDetail">
								<ul class="tntSocialItems">
									<li><label for="tntSocialItemFB"><span>Facebook</span> <input type="checkbox" class="tntSocialItem" id="tntSocialItemFB" name="tntSocialFeatureFB" <?php echo ($tntOptions['socialFeatureFB']) ? "checked" : "" ?> value="1" /></label></li>
									<li><label for="tntSocialItemTW"><span>Twitter</span> <input type="checkbox" class="tntSocialItem" id="tntSocialItemTW" name="tntSocialFeatureTW" <?php echo ($tntOptions['socialFeatureTW']) ? "checked" : "" ?> value="1" /></label></li>
									<li><label for="tntSocialItemP"><span>Pinterest </span><input type="checkbox" class="tntSocialItem" id="tntSocialItemP" name="tntSocialFeatureP" <?php echo ($tntOptions['socialFeatureP']) ? "checked" : "" ?> value="1" /></label></li>
								</ul>
								<div class="tntSocialIconSize">
									<label for="">Social Icons Size: </label>
									<select name="tntSocialFeatureIconSize">
										<option <?php echo ($tntOptions['socialFeatureIconSize'] == 32) ? "selected" : "" ?> value="32">32x32</option>
										<option <?php echo ($tntOptions['socialFeatureIconSize'] == 24) ? "selected" : "" ?> value="24">24x24</option>
									</select>
								</div>								
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
						<td>
							<input type="submit" name="tntUpdateVideoOptions" value="Save Settings" class="button-primary"/>
							<input type="submit" name="reset" value="Reset" class="button-secondary">
						</td>
					</tr>
				</table>
			</form>
		</div>
	<?php
}//tnt_video_option


/**
 * Function to display the "video rate" page
 */
function tnt_video_rate(){
	?>
		<div class="wrap">
			<?php screen_icon('options-general') ?>
			<h2>About Author</h2>
			<?php showMessage("If you would like to support further development of this plugin, please rate me in wordpress! <a href='http://wordpress.org/plugins/video-list-manager/'' target=_blank>Click here to rate me </a>", 1); ?>
			<table border="1" cellpadding="10" cellspacing="0">
				<tr>
					<td><strong>Full Name</strong></td>
					<td>Tung Pham</td>
				</tr>
				<tr>
					<td><strong>Email</strong></td>
					<td><a href="mailto:tungpham.bh@gmail.com">tungpham.bh@gmail.com</a></td>
				</tr>
				<tr>
					<td><strong>Skype</strong></td>
					<td><a href="skype:tung.tnt.1988?chat">tung.tnt.1988</a></td>
				</tr>
				<tr>
					<td>Website</td>
					<td><a href="https://tungpham.net">https://tungpham.net</a></td>
				</tr>
				<tr>
					<td colspan="2">If you have issues with this plugin. Please don't hesitate contact me via email or skype. I will help you.</td>
				</tr>
				
			</table>
		</div>
	<?php
}//tnt_video_rate
?>