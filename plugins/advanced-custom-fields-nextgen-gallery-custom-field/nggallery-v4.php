<?php

class acf_field_nggallery extends acf_field
{
	// vars
	var $settings, // will hold info such as dir / path
		$defaults = array (
			'multiple'    => 0,
			'return_type' => 'nggallery_object'
		); // will hold default field options


	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	1.0.0
	*  @date	21-09-2013
	*/

	function __construct()
	{

		// Check if nggdb class exists. If not, exit this script.
		if ( !class_exists('nggdb') ) {
			add_action( 'admin_notices', 'acf_field_nggallery::nggalleryNotFound');
		} else {
			$this->init();
		}

	}


	/*
	*  init()
	*
	*  Inits the plugin
	*
	*  @type	init
	*  @since	1.0.0
	*  @date	21-09-2013
	*
	*  @param	{none}
	*/

	function init() {
		// vars
		$this->name = 'nggallery';
		$this->label = __('NextGEN Gallery');
		$this->category = __("Relational",'acf');


		// do not delete!
	    parent::__construct();


    	// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);
	}


	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	1.0.0
	*  @date	21-09-2013
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function create_options($field)
	{
		$key = $field['name'];
		$field = array_merge($this->defaults, $field);

		// Single or multiple galleries
		?>
		<tr class="field_option field_option_<?=$this->name;?>">
			<td class="label">
				<label><?=__('Can the user select one or more galleries?');?></label>
				<p class="description"><?=__('If you select multiple galleries, you can\'t limit the maximum number of galleries (yet).');?></p>
			</td>
			<td>
				<?php
					do_action('acf/create_field', array (
						'type'    => 'radio',
						'name'    => 'fields[' . $key .'][multiple]',
						'value'   => $field['multiple'],
						'layout'  => 'horizontal',
						'choices' => array(
							'1' => __('Multiple galleries'),
							'0'  => __('Only one')
						)
					));
				?>
			</td>
		</tr>
		<?php

		// Return type
		?>
		<tr class="field_option field_option_<?=$this->name;?>">
			<td class="label">
				<label><?=__('Return type');?></label>
				<p class="description"><?=__('NGGallery object or a list of all the images');?></p>
			</td>
			<td>
				<?php
					do_action('acf/create_field', array (
						'type'    => 'select',
						'name'    => 'fields[' . $key .'][return_type]',
						'value'   => $field['return_type'],
						'layout'  => 'horizontal',
						'choices' => array(
							'nggallery_object' => __('NGGAllery Object (in array)'),
							'images_array'  => __('All of the images (in array)'),
							'nggallery_id' => __('NGGallery id')
						)
					));
				?>
			</td>
		</tr>
		<?php
	}


	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	1.0.0
	*  @date	21-09-2013
	*/

	function create_field( $field )
	{

		// make NGGallery class global
		global $nggdb;

		// Settings  for NGGallery query
		$sOrderBy = 'title';
		$sOrderDirection = 'ASC';
		$iLimit = 0;
		$iStart = 0;

		$aGalleries = $nggdb->find_all_galleries( $sOrderBy, $sOrderDirection , true, $iLimit, $iStart, false);
		
		// Get selected galleries
		$aSelectedGalleries = $field['value'];

		// Check if multiple galleries can be selected. If this is true, 
		// the 'multiple' attribute will be added to the select element.
		$multiple = $field['multiple'];

		// create Field HTML
		?>
		<select name="<?=$field['name'];?>[]" id="<?=$field['name'];?>" class="<?=$field['class'];?>" <?php if ($multiple === 1) { echo 'multiple="multiple';} ?>>
			<option value="">- <?=__('Select');?> -</option>
			<?php foreach ($aGalleries as $oGallery) : ?>
			<option value="<?=$oGallery->gid;?>"<?php if ( $aSelectedGalleries && in_array( $oGallery->gid, $aSelectedGalleries ) ) { echo 'selected="selected"';} ?>><?=$oGallery->title;?></option>
			<?php endforeach;?>
		</select>
		<?php
	}


	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	1.0.0
	*  @date	21-09-2013
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

	function format_value_for_api($value, $post_id, $field)
	{
		return $this->buildReturnFormat($value, $post_id, $field);
	}


	/*
	*  buildReturnFormat()
	*
	*  Function determines what values need to be returned and calls apropriate function
	*
	*  @since	1.0.0
	*  @date	21-09-2013
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$output	- the user desired output
	*/
	function buildReturnFormat($value, $post_id, $field)
	{
		switch ($field['return_type']) {
			case 'images_array' :
				return $this->formatImagesArrayForApi($value, $post_id, $field);
			break;
			case 'nggallery_object' :
				return $this->formatNGGalleryObjectForApi($value, $post_id, $field);
			break;
			case 'nggallery_id' :
				return $this->formatNGGalleryIdForApi($value, $post_id, $field);
				break;
			default:
				return 'Are you kidding? Seems like you\'re using a return type that I\'m not aware of.';
				break;
		}
	}


	/*
	*  formatNGGalleryObjectForApi()
	*
	*  Loops through galleries and fetches NGGallery objects.
	*
	*  @since	1.0.0
	*  @date	21-09-2013
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$aGalleries	- NGGAllery objects
	*/
	function formatNGGalleryObjectForApi($value, $post_id, $field)
	{
		// make NGGallery class global
		global $nggdb;

		// Settings  for NGGallery query
		$sOrderBy = 'sortorder';
		$sorderDirection = 'ASC';
		$iLimit = 0;
		$iStart = 0;

		foreach($value as $gallery) {
			$aGalleries[] = $nggdb->get_gallery( $gallery, $sOrderBy, $sorderDirection, true, $iLimit, $istart);
		}

		return $aGalleries;
	}


	/*
	*  formatImagesArrayForApi()
	*
	*  Loops through galleries and fetches all of the images.
	*
	*  @since	1.0.0
	*  @date	21-09-2013
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$aImages  - NGGallery images array
	*/
	function formatImagesArrayForApi($value, $post_id, $field)
	{
		$iCount = 0;

		$aGalleries = $this->formatNGGalleryObjectForApi($value, $post_id, $field);
		foreach($aGalleries as $aGallery) {
			$iImageCount = 0;
				foreach($aGallery as $aImage){
					$aImages[$iCount][$iImageCount]['imageURL'] = $aImage->imageURL;
					$aImages[$iCount][$iImageCount]['thumbURL'] = $aImage->thumbURL;
					$aImages[$iCount][$iImageCount]['imagedate'] = $aImage->imagedate;
					$aImages[$iCount][$iImageCount]['description'] = $aImage->description;
					$aImages[$iCount][$iImageCount]['alttext'] = $aImage->alttext;

					$iImageCount++;
				}

			$iCount++;
		}

		return $aImages;
	}


	/*
	*  formatNGGalleryIdForApi()
	*
	*  Loops through all values and returns them
	*
	*  @since	1.1.0
	*  @date	21-09-2013
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$aGalleryIds  - NGGallery id's
	*  @return  $iGalleryId   - NGGallery id as a string, of there is only 1 id
	*/
	function formatNGGalleryIdForApi($value, $post_id, $field)
	{
		$aGalleryIds = array();

		if (count($value) === 1) {

			return $value[0];

		} else {

			foreach($value as $gallery) {
				$aGalleryIds = $value[$gallery];
			}
			return $aGalleryIds;

		}	

	}


	/*
	*  nggalleryNotFound()
	*
	*  This function is called if the nggdb class doesn't exist. It will show a warning to the user in the admin panel
	*
	*  @type	action
	*  @since	1.0.0
	*  @date	21-09-2013
	*
	*  @param	{none}
	*
	*  @return	{string}  - Sets warning for user in admin_notices action
	*/
	function nggalleryNotFound() {
		?>
		<div class="updated">
			<p>You don't have the NGGallery plugin installed or activated. Please do so before you can use this ACF extension. Until you do, the field won't be available in the ACF settings. To remove this error, just disable this plugin.</p>
		</div>
		<?php
	}


}


// create field
new acf_field_nggallery();

?>