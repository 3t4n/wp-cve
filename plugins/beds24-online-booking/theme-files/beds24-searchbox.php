<?php
//This file can be modified and placed in your theme directory, The plugin will search for a file with this name there first and use it if it exists
//Modify the code entering your search criteria as dropdownlist or checkboxes. You can make them always visible or only visible upon clicking "Advanced Search"

//default values
$daysinadvance = 0; //initial date to show, this many days ahead of today
if (!isset($_REQUEST['numadult'])) $_REQUEST['numadult'] = 2; //number of guests
if (!isset($_REQUEST['numnight'])) $_REQUEST['numnight'] = 1; //number of nights


if (!isset($_REQUEST['fdate_date'])) $_REQUEST['fdate_date'] = date('d', strtotime('+'.$daysinadvance.' days'));
if (!isset($_REQUEST['fdate_monthyear'])) $_REQUEST['fdate_monthyear'] = date('Y-m', strtotime('+'.$daysinadvance.' days'));
?>

<div class="B24agency_searchbox">
<div class="B24agency_searchbox_inner">
    
<h2><?php echo $translate['Search Accommodation']; ?></h2><!--Feel free to chage the headline-->

<!-- Search criteria example for seach category 1. using dropdownlist select. -->
<!-- The name of the select must be either category1, category2, category3 or category4 to connect to the coresponding category in Beds24 -->
<!-- Each option must have a numeric number value reperesenting the position of the option defined in the Beds24 agency search options list for this category. -->

<div class="B24agency_searchitem agency_searchitem1">
<select class="B24agency_searchinput" name="category1">    
<option value="0">Choose Location</option>
<!--Search category 1 set to "Selector Upper"  -->
<option <?php echo ($_REQUEST['category1']==1)?'selected="selected"':''; ?> value="1">Paris</option>  
<option <?php echo ($_REQUEST['category1']==2)?'selected="selected"':''; ?> value="2">Orange</option>
<option <?php echo ($_REQUEST['category1']==3)?'selected="selected"':''; ?> value="3">St. Tropez</option>
</select>
</div>


<!-- Search criteria example for seach category 2. using checkboxes.  -->
<!-- The name of each checkbox must start with either category1, category2, category3 or category4 to connect to the coresponding category in Beds24 -->
<!-- This must be followed by an underscore and the numeric number value reperesenting the position of the option defined in the Beds24 agency search options list for this category. -->


<div class="B24advanced_search_group">    
<h4 class="B24advanced_search_group_header">Type</h4>
<ul class="B24advanced_search_group_options">
<li class="B24checkbox">
        
<label class="B24checkbox_label">
<input type="checkbox" <?php if (isset($_REQUEST['category2_1'])) echo ($_REQUEST['category2_1']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category2_1" value="1">
Hotel<!--Label for search category 2 -->
</label>
</li>
<li class="B24checkbox">
<label class="B24checkbox_label">
<input type="checkbox" <?php if (isset($_REQUEST['category2_2']))  echo ($_REQUEST['category2_2']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category2_2" value="1">
B&amp;B<!--Label for search category 2 -->
</label>
</li>
<li class="B24checkbox">
<label class="B24checkbox_label">
<input type="checkbox" <?php if (isset($_REQUEST['category2_3']))  echo ($_REQUEST['category2_3']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category2_3" value="1">
Appartment<!--Label for search category 2 -->
</label>
</li>
</ul>
</div>

<div class="B24clearboth"></div>    
<div class="B24agency_searchitem B24agency_searchitem2">
<div class="B24checkintext">Check In</div>
<div class="B24checkinselect">
<input type="hidden" id="datepicker">


<select id="fdate_date" class="B24checkin_day" name="fdate_date">
<option value="0" class="B24checkin_day_text"><?php echo $translate['Day']; ?></option>
<?php for ($i=1; $i<=31; $i++) { ?>
	<option <?php echo ($_REQUEST['fdate_date']==$i)?'selected="selected"':''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>
</select>

<select id="fdate_monthyear" class="B24checkin_month" name="fdate_monthyear">
<option value="0" class="B24checkin_month_text"><?php echo $translate['Month']; ?></option>
<?php $thismonthyear = date('Y-m-01'); ?>
<?php for ($i=0; $i<24; $i++) { ?>
	<option <?php echo ($_REQUEST['fdate_monthyear']==date('Y-m', strtotime('+ '.$i.' months')))?'selected="selected"':''; ?> value="<?php echo date('Y-m', strtotime($thismonthyear.'+ '.$i.' months')) ?>"><?php echo date('M Y', strtotime($thismonthyear.'+ '.$i.' months')) ?></option>
<?php } ?>
</select>
</div>
</div>

<div class="B24agency_searchitem B24agency_searchitem3">
<div class="B24checkouttext"><?php echo $translate['Nights']; ?></div>
<div class="B24checkoutselect">
<select class="B24nights" name="numnight">
<?php for ($i=1; $i<=31; $i++) { ?>
	<option <?php echo ($_REQUEST['numnight']==$i)?'selected="selected"':''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>
</select>
</div>
</div>

<div class="B24agency_searchitem B24agency_searchitem4">
<div class="B24guesttext"><?php echo $translate['Guests']; ?></div>
<div class="B24cguestselect">
<select class="B24guest" name="numadult">
<?php for ($i=1; $i<=12; $i++) { ?>
	<option <?php echo ($_REQUEST['numadult']==$i)?'selected="selected"':''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>
</select>
</div>

</div>
<div class="B24buttondiv">
<div class="B24button">
<input type="submit" value="<?php echo $translate['Search']; ?>">
</div>
</div>

<div class="B24advancedsearch">
</div>
<div class="B24clearboth"></div>
			
			
					
<div class="B24advancedsearchmore">
<a onclick="jQuery('#B24advancedsearch').toggle();jQuery('#showmoredetails').val(1-jQuery('#showmoredetails').val());return false;" href=""><?php echo $translate['more search options']; ?></a>
<input type="hidden" id="showmoredetails" name="showmoredetails" value="<?php echo ($_REQUEST['showmoredetails']==1)?'1':'0'; ?>">

</div>

<div id="B24advancedsearch" class="B24advancedsearch">

	<div class="B24clearboth"></div>
    
<!--Below here search criteria upon clicking "Advanced Search" -->
    
    <!--Search criteria for seach category 3. using checkboxes lower -->
	<div class="B24advanced_search_group">
    <h4 class="B24advanced_search_group_header">Facilities</h4>
    <ul class="B24advanced_search_group_options">
    <li class="B24checkbox">
    <label class="B24checkbox_label">
    <input type="checkbox" <?php echo ($_REQUEST['category3_1']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category3_1" value="1">
    Restaurant<!--Label for search category 3 -->
    </label>
    </li>
    <li class="B24checkbox">
    <label class="B24checkbox_label">
    <input type="checkbox" <?php echo ($_REQUEST['category3_2']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category3_2" value="1">
    Bar<!--Label for search category 3 -->
    </label>
    </li>
	 <li class="B24checkbox">
    <label class="B24checkbox_label">
    <input type="checkbox" <?php echo ($_REQUEST['category3_3']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category3_3" value="1">
    Pool<!--Label for search category 3 -->
    </label>
    </li>
	 <li class="B24checkbox">
    <label class="B24checkbox_label">
    <input type="checkbox" <?php echo ($_REQUEST['category3_4']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category3_4" value="1">
    Elevator<!--Label for search category 3 -->
    </label>
    </li>
	 <li class="B24checkbox">
    <label class="B24checkbox_label">
    <input type="checkbox" <?php echo ($_REQUEST['category3_5']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category3_5" value="1">
		W-Lan<!--Label for search category 3 -->
    </label>
    </li>
	 <li class="B24checkbox">
    <label class="B24checkbox_label">
    <input type="checkbox" <?php echo ($_REQUEST['category3_6']==1)?'checked="checked"':''; ?> class="B24checkbox_selector" name="category3_6" value="1">
		Parking<!--Label for search category 3 -->
    </label>
    </li>
    </ul>
    </div>
    
    <!--Search criteria for seach category 4. -->
<div class="B24agency_searchitem agency_searchitem1">
<select class="B24agency_searchinput" name="category4">    
<option value="0">Choose Location</option><!--Label for search category 1 -->    
<!--Search category 1 set to "Selector Lower"  -->
<option <?php echo ($_REQUEST['category4']==1)?'selected="selected"':''; ?> value="1">Paris</option>  
<option <?php echo ($_REQUEST['category4']==2)?'selected="selected"':''; ?> value="2">Orange</option>
<option <?php echo ($_REQUEST['category4']==3)?'selected="selected"':''; ?> value="3">St. Tropez</option>
</select>
</div>


<div class="B24clearboth"></div>
		
</div>
</div>
</div>
