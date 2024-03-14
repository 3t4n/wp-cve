<?php
/* php_masonry_advanced
	* advanced masonry functions for masonry,  isotope
*/

/**
 * proto_masonry_advanced class.
 * advanced scripts & options
 */
class proto_masonry_advanced
{
	/**
	 * proto_widget_class function.
	 * add some classes to the widget as necessary based on options like displayas and gridclass
	 * @access public
	 * @param string $class - current class string for the widget html
	 * @param array $options - plugin options array
	 * @return widget classes
	 */
	public function proto_widget_class( $gridclass, $options )
	{
		$displayas = isset( $options['displayas'] ) ? sanitize_text_Field( $options['displayas'] ) : 'masonry';
		if ($displayas == 'filtered') $displayas = 'isotope';
		$gridclass .= ( $displayas != 'masonry' ) ?  " proto_masonry_$displayas" : '';
		return $gridclass;
	}
	/**
	 * masonry_isotope_script function.
	 * Generate filter & masonry javascript for isotope, replaces similar default masonry functionality
	 * @access public
	 * @param string $script - existing javascript which may not be blank but will be replaced
	 * @param string $masonryscriptoptions - current js script options for the masonry grid
	 * @param array $options - plugin options
	 * @return the full options	 */
	function masonry_isotope_script($script, $masonryscriptoptions, $options)
	{
		$masonryscript = proto_functions::generate_script($masonryscriptoptions);	//Get the masonry script code
		$prefix = sanitize_text_field( $options['prefix'] );						//prefix (ie: featured_image_pro)
		$uniqueId = sanitize_text_field( $options['uniqueid'] );					//the unique id for gtid & grid elements
		$uid = sanitize_text_field( $options['uid'] );								//Unique id with underscores
		$thisid = "#proto-masonry-widget_$uniqueId";								//this widget id
		$itemId = ".{$prefix}_masonry_item_{$uniqueId}";							//Grid item id
		$script .= "var grid_$uid = $('$thisid').isotope({itemSelector:  '$itemId',   layoutmode: 'masonry', masonry: {{$masonryscript}}})";  //Create the code for isotope

		return $script;

	}
	/**
	 * masonry_isotope_button_script function.
	 * Generate filter & masonry javascript for isotope, replaces similar default masonry functionality
	 * @access public
	 * @param string $script - existing javascript which may not be blank but will be replaced
	 * @param string $masonryscriptoptions - current js script options for the masonry grid
	 * @param array $options - plugin options
	 * @return the full options
	 */
	function masonry_isotope_button_script( $script, $options )
	{
		$uniqueid = sanitize_text_field( $options['uniqueid'] );
		$uid = str_replace( '-', '_', $uniqueid );
		$uid = sanitize_text_field( $options['uid'] );								//Unique id with underscores
		$filteredtaxonomies = isset( $options['filteredtaxonomies'] ) ? ( $options['filteredtaxonomies'] ) : array('category'); //isotope taxonomy names
		$filters =  false;
		$filterscript = '';
		if ( isset($filteredtaxonomies) && ! empty($filteredtaxonomies) && $filteredtaxonomies != '' )
		{

			foreach ($filteredtaxonomies as $key=>$taxonomy)							//Make sure that there is something to filter
			{
				if ( !empty($taxonomy))
				{
					$filters = true;
					break;
				}
			}
			if ($filters)															//If so, create the script
			{
				$filterscript = " // filter items when filter link is clicked

			    	$('.proto_isotope_button_$uid').on('click', function() {
				    $(this).closest('.proto_isotope_button_group').find('.is-checked').removeClass('is-checked');
				    $(this).addClass('is-checked');
			    	var selector = $(this).data('filter');
						grid_$uid.isotope({
							filter: selector
						});
					});";
			}
		}

			$script = $script . $filterscript;
		return $script;
	}
	/**
	 * proto_masonry_advanced_script_options function.
	 * parse out additional advanced masonry options
	 *
	 * @access public
	 * @param string  $originalscript - current masonry script
	 * @param array   $options        - plugin options
	 * @return masonry script
	 */
	public function proto_masonry_advanced_script_options( $originalscript, $options ) {
		$defaults = featured_image_pro_advanced::proto_masonry_defaults();									//get the advanced default values
		//parse out the masonry script values
		$scriptvalues = proto_functions:: proto_masonry_prefix_key_value($defaults, $options, '');			//get the script values from options if they are not defaults
		$version = isset( $settings['proto_masonry_version'] ) ? $settings['proto_masonry_version'] : '3';	//get masonry version name

		if ( $version == '3' )	//Wordpress currently uses version 3. This should be left alone for now because version 4 accepts version 3 setting names
		{

			if (array_key_exists('originTop', $scriptvalues)) //replace keys
			{
				$scriptvalues['isOriginTop'] = $scriptvalues['originTop'];
						unset( $scriptvalues['originTop'] );
			}
			if ( array_key_exists( 'originLeft', $scriptvalues ) )
			{

				$scriptvalues['isOriginLeft'] = $scriptvalues['originLeft'];
			    unset($scriptvalues['originLeft']);
			}
			if ( array_key_exists( 'resize', $scriptvalues ) )
			{
				$scriptvalues['isResizeBound'] = $scriptvalues['resize'];
			    unset($scriptvalues['resize']);

			}
		}
		$displayas = isset($options['displayas']) ? sanitize_text_field( $options['displayas'] ) : '';

		$mergedvalues =  array_merge( $originalscript, $scriptvalues );									//Merge the advanced options with any other options

		foreach ( $mergedvalues as $key => $value )														//Change the boolean values into integers for the script
		{
			if ( is_bool( $value ) || $value == 'false' || $value == 'true' )
			{

				$mergedvalues[$key] = intval(proto_boolval($value));
			}
		}
		return $mergedvalues;
	}
	/**
	 * proto_isotope_terms function.
	 * get a list of terms for each selected taxonomy name
	 * @access public
	 * @param array $atts  - not used
	 * @param array $options plugin options
	 * @return void
	 */
	public function proto_isotope_terms( $options, $atts = null )
	{
		$displayas = isset( $options['displayas'] ) ? sanitize_text_field( $options['displayas'] ) : '';
		if ($displayas == 'isotope' || $displayas == 'filtered')
		{
			//1. get the taxonomy name list
			$filteredtaxonomies = isset( $options['filteredtaxonomies'] ) ? sanitize_text_field( $options['filteredtaxonomies'] ) : '';
			//2. if there is none, set it to the taxonomies
			if ($filteredtaxonomies == '')
					$filteredtaxonomies = isset( $options['taxonomiesx'] ) ? sanitize_text_field( $options['taxonomiesx'] ) : '';
			//3 otherwise default it to 'category'
			if ($filteredtaxonomies == '')
				$filteredtaxonomies = 'category';


			if ($filteredtaxonomies != '')		//this should always be true but whatever
		   {
				$taxonomylist = explode( ',', $filteredtaxonomies ); ///create an array of the taxonomy names
				$taxonomylist = array_map('trim', $taxonomylist);
				unset ( $options['isotopetaxnomies'] );

				$options['filteredtaxonomynames'] = $taxonomylist;   //add array of taxonomy names to the options
				$options['filteredtaxonomies'] = $this->proto_term_ids($options, $taxonomylist); //replace this option with arrays of term id's for each taxonomy
			}
		}
		return $options;
	}
	/**
	 * proto_term_ids function.
	 * get the list of terms for the array of taxonomies
	 * @access public
	 * @param array $options plugin options
	 * @param mixed $taxonomylist array of taxonomy names
	 * @return array of terms for each taxonomy
	 */
	public function proto_term_ids($options, $taxonomylist)
	{
		if ( isset( $options['taxonomiesx'] ) )	 						//Get any default taxonomie term lists if one has been specified
		{
			$otaxarray = explode( ",", $options['taxonomiesx'] );		//Explode into an array
			$otaxarray = array_map('trim', $otaxarray);
		}
		else
			$otaxarray = array();
		$termarray = array();  											//initialize the term array for the taxonomies
		foreach ( $taxonomylist as $key=>$taxonomy ) 					//for each taxonomy name
		{

			$taxonomy =  sanitize_text_field( $taxonomylist[$key] );

			if ( $taxonomy=='cat' || $taxonomy == 'category' )			//fix common issues
				$taxonomy = 'category';
			if ( $taxonomy=='tag' )
				$taxonomy = 'post_tag';
			$termids = '';
			if ( $taxonomy != '')
			{
				if ( isset ( $options[  "isotope_{$taxonomy}_ids" ] ) )  							  	//If there is a set of id's specified for the isotope
					$termids = explode( ",", $options[ "isotope_{$taxonomy}_ids" ] ); 				  	//get the id's
				elseif (in_array($taxonomy, $otaxarray) && isset( $options[ $taxonomy."_termsx"  ] ))   // if not, check the taxonomy list
					$termids = explode(',', $options[  $taxonomy.'_termsx' ]);					  	  	 //if it is default to the taxonomy terms
				else
					$termids = get_terms( $taxonomy, array( 'hide_empty' => true, 'fields' => 'ids', ) ); //otherwise use all of the id's for the isotope taxonomy
				//error_log ( $termids );
				$termids = array_map('trim', $termids);
			}
			$termarray[$taxonomy] = $termids;
		}
		return $termarray; //return array of terms for list of taxonomies
	}
	/**
	 * proto_masonry_istope_item_class function
	 * custom classes for items when isotope is used. The class is used to determine what should be shown when a isotope button/menu item is clicked
	 * @access public
	 * @param string $itemclass - classes for the item
	 * @param object $proto_post - item attachment object
	 * @param array $options - plugin options
	 * @return void
	 */
	function proto_masonry_istope_item_class( $itemclass, $proto_post, $options  )
	{
		$uniqueid = sanitize_text_field( $options['uniqueid'] ); //create a class for the item using the unique id, taxonomy name & term id
		foreach ($proto_post->taxonomies as $key=>$term)
		{
			foreach ($term as $termid)
				$itemclass .= " term_{$uniqueid}_{$key}_{$termid}";
		}
		return $itemclass;
	}
	/**
	 * proto_masonry_isotope_post_terms function.
	 * get a list of terms that are assigned to the post
	 * @access public
	 * @param object $proto_post : proto masonry post object
	 * @param array $options : array of options
	 * @param object $post : Wordpress post
	 * @return void
	 */
	function proto_masonry_isotope_post_terms( $proto_post, $options, $post )
	{
		$filteredtaxonomynames = isset( $options['filteredtaxonomynames'] ) ?  $options['filteredtaxonomynames']  : array();  //get the list of taxonomy names
		$proto_post->taxonomies = array();																					//initialize the taxonomies array for the post
		foreach ($filteredtaxonomynames as $taxonomyname) //get the term ids for the selected taxonomy and add it to the proto post object
		{
			$proto_post->taxonomies[$taxonomyname] = wp_get_post_terms( $post->ID, $taxonomyname, array("fields" => "ids") );
		}
		return $proto_post;
	}
	/**
	 * proto_taxonomy_count function.
	 * count the taxonomy items for isotope
	 * @access public
	 * @param object $attobject - attachment object
	 * @param mixed $options - options
	 * @param mixed $_query
	 * @return void
	 */
	function proto_taxonomy_count($attobject)
	{
		$termcount = array();
		foreach ($attobject->attachments as $proto_post)		//Go through the attachments and count the terms
		{
			if ( !isset($termcount['all']) )
				$termcount['all'] = 0;
			if (isset($proto_post->taxonomies))					//If the post has terms
			{
				$count = 0;
				foreach ($proto_post->taxonomies as $tax)		//count the terms for each taxonomy
				{
					foreach ($tax as $key=>$tx)
					{
						$count ++;
						$termcount[$tx] =  $count;
						$termcount['all'] .= $count;
					}

				}
			}
		}
		$attobject->termcount=$termcount;
		return $attobject;
	}
	/**
	 * proto_masonry_isotope_navigation function.
	 * create isotope navigation fields for the top and left positions
	 * @access public
	 * @param string $output - output html
	 * @param  object $attobj (default: null) the attachment object
	 * @param array $options (default: array) plugin options
	 * @param array $atts - query attributes
	 * @return void
	 */
	function proto_masonry_isotope_navigation ( $output, $attobj, $options )
	{
		$isotopemenu = isset( $options['filteredmenuposition'] ) ? $options['filteredmenuposition'] : 'top';	//get the menu positions
		$menutype = explode(',', $isotopemenu);
		$menutype = array_map('trim', $menutype);
		$width = isset( $options['filteredmenuwidth'] ) ? $options['filteredmenuwidth'] : '100%';		//width of menu (should be different settings for each position?
		if ( in_array( 'top', $menutype ) ) {
			$width = isset( $options['filteredmenuwidth_top'] ) ? $options['filteredmenuwidth_top'] : $width;
			$output .= $this->proto_masonry_menu( $attobj, $options, 'top', $width );	//generate code for top menu
		}
		if ( in_array( 'left', $menutype ) ) {
			$width = isset( $options['filteredmenuwidth_left'] ) ? $options['filteredmenuwidth_left'] : $width;
			$output .= $this->proto_masonry_menu( $attobj, $options, 'left', $width ); //generate code for left menu
		}
		return $output;
	}
	/**
	 * proto_masonry_isotope_navigation_bottom function.
	 * isotope navigation under the grid
	 * @access public
	 * @param html string  $output - html output
	 * @param object $attobj - attachment object
	 * @param array $options - array
	 * @return  $output - html output
	 */
	function proto_masonry_isotope_navigation_bottom( $output, $attobj, $options )
	{

		$isotopemenu = isset( $options['filteredmenuposition'] ) ? $options['filteredmenuposition'] : 'top'; //get menu positions
		$menutype = explode( ',', $isotopemenu );
		$menutype = array_map('trim', $menutype);
		$width = isset( $options['filteredmenuwidth'] ) ? $options['filteredmenuwidth'] : '100%';		//width of menu (should be different settings for each position?

		if ( in_array( 'right', $menutype ) )
		{
			$width = isset( $options['filteredmenuwidth_right'] ) ? $options['filteredmenuwidth_right'] : $width;
			$output .= $this->proto_masonry_menu( $attobj, $options, 'right', $width ); //generate code for right menu position
		}
		if ( in_array( 'bottom', $menutype ) )
		{
			$width = isset( $options['filteredmenuwidth_bottom'] ) ? $options['filteredmenuwidth_bottom'] : $width;
			$output .= $this->proto_masonry_menu( $attobj, $options, 'bottom', $width ); //generate code for bottom menu position
		}
		return $output;
	}
	/**
	 * proto_masonry_menu function.
	 *
	 * @access public
	 * @param object $attobj - object containing post/image information
	 * @param array $options - widget settings
	 * @param string $isotopemenu html for menu
	 * @return html
	 */
	function proto_masonry_menu( $attobj, $options, $isotopemenu, $width='100%' )
	{
		if ( $width == '' )
			$width = '100%';
		$isotopeoutput = "";
		$uniqueid =  sanitize_text_field( $options['uniqueid'] );
		$termlist = isset( $options['filteredtaxonomynames'] ) ? $options['filteredtaxonomynames'] : array( );
		$all = __( 'All', 'featured_image_pro' );
		$filteredmenuclass = isset($options['filteredmenuclass']) ? $options['filteredmenuclass'] : '';
		$isotopeoutput = "<div id='proto_isotope_button_group_$uniqueid' style='width:$width;' class='proto_isotope_button_group_$isotopemenu $filteredmenuclass proto_isotope_button_group button-group filters-button-group'>";
		$isotopeoutput .= "<button class='proto_isotope_button_$uniqueid proto_isotope_button proto_isotope_button_all button is-checked' data-filter='*'><span>$all</span></button>";
		if ( $attobj->termcount['all'] > 0 ) //don't create the menu if there are no matches
		{
			$taxonomylist = $options['filteredtaxonomies'];
			//all button
			foreach ( $taxonomylist as $taxname=>$terms )	//for each taxonomy
			{
				foreach ($terms as $key=>$termid)
				{
					if ( isset( $attobj->termcount[$termid] ) &&  $attobj->termcount[$termid]  > 0 )
					{
						$terma = get_term_by('id', $termid, $taxname, 'ARRAY_A');
						$termname = $terma['name'];

						$isotopeoutput .= "<button class='proto_isotope_button_$uniqueid proto_isotope_button proto_isotope_button_$taxname button' data-filter='.term_{$uniqueid}_{$taxname}_{$termid}'><span>$termname</span></button>";
					}
				}
			}

		}
		$isotopeoutput .= '</div>';
		return $isotopeoutput;
	}

}