<?php
if (!defined('ABSPATH'))
{
	exit;
}

function product_table_shortcode_free($atts)
{
	global $table_prefix,$wpdb,$post;

	$args = array( 'post_type' => 'product', 'post_status' => 'publish' );

	$productarray = array();
	$m_single = array();
	$searchFormEnable = '';
	
	$return_content = '';
	$return_content .= '<div class="tooltips_directory">';

	//14.2.4
	$limit_sql = '';

	$post_type = 'product';
	$user_args_catid_query = '';

	$user_args_catid = '';

	$sql = $wpdb->prepare( "SELECT ID, post_title, post_content, post_excerpt FROM $wpdb->posts WHERE post_type=%s AND post_status='publish' order by post_title ASC $limit_sql",$post_type);
	$results = $wpdb->get_results( $sql );



	$enableGlossaryPaginateNavigation = get_option("enableGlossaryPaginateNavigation");
	if ($enableGlossaryPaginateNavigation == 'YES')
	{
		$show_glossary_page_current_result = $results;

	}
	else
	{
		$show_glossary_page_current_result = $results;
	}

	$enableGlossaryCatNameUnderTerm = get_option("enableGlossaryCatNameUnderTerm");
	$return_content .= '<div class="tooltips_list_start">';
	
	if ((!(empty($show_glossary_page_current_result))) && (is_array($show_glossary_page_current_result)) && (count($show_glossary_page_current_result) >0))
	{
		$m_single = array();
		
		foreach ($show_glossary_page_current_result as $single)
		{

			if (empty($single->post_title))
			{
				continue ;
			}
			
			$bulkremovetermfromglossarylist = '';
			$bulkremovetermfromglossarylist = get_option("bulkremovetermfromglossarylist");
			if (!(empty($bulkremovetermfromglossarylist)))
			{
				$patterns = '';
				$replacements = '';
				$bulkremovetermfromglossarylist = trim($bulkremovetermfromglossarylist);
				$bulkremovetermfromglossarylistarray = explode(',', $bulkremovetermfromglossarylist);
				
				if ((!(empty($bulkremovetermfromglossarylistarray))) && (is_array($bulkremovetermfromglossarylistarray)) && (count($bulkremovetermfromglossarylistarray) > 0))
				{
				    $bulkremovetermfromglossarylistarray = array_filter($bulkremovetermfromglossarylistarray);
				}
				//end 16.0.2
					
				if ((!(empty($bulkremovetermfromglossarylistarray))) && (is_array($bulkremovetermfromglossarylistarray)) && (count($bulkremovetermfromglossarylistarray) > 0))
				{
					if (in_array($single->ID, $bulkremovetermfromglossarylistarray))
					{
						continue;
					}
				}
			}
			//version 11.2.2
				
			$return_content .= '<div class="tooltips_list">';
			$return_content .= '<span class="tooltips_table_items">';
			$return_content .= '<div class="tooltips_table">';
			$return_content .= '<div class="tooltips_table_title">';
			$enabGlossaryIndexPage =  get_option("enabGlossaryIndexPage");
			if (empty($enabGlossaryIndexPage))
			{
				$enabGlossaryIndexPage = 'YES';
			}
				
			if ($enabGlossaryIndexPage == 'YES')
			{
			    //7.9.3
			    $return_content .=	'<a href="'.esc_url(get_permalink($single->ID)).'">'.$single->post_title.'</a>';
				//$return_content .=	'<a href="'.get_permalink($single->ID).'">'.$single->post_title.'</a>';
			}
			else
			{
				$return_content .=	$single->post_title;
			}


			 
			//16.2.8 end
			$return_content .='</div>';
			$return_content .= '<div class="tooltips_table_content">';

			$glossaryExcerptOrContentSelect = get_option("glossaryExcerptOrContentSelect");
			
			if ($glossaryExcerptOrContentSelect == 'glossaryexcerpt')
			{
			    
				// old $m_content= get_the_excerpt($single->ID);
				// $m_content = wp_trim_excerpt($single->post_content); //!!! 14.0.4
				// 25.0.8 this caused hook conflicts the_content $m_content =  wp_trim_excerpt('',$single->ID);  //!!! 14.2.8
			    $m_content =  ''; //25.0.8
				
				//14.5.2
				if (empty($single->post_excerpt))
				{
				    
					//$m_content =  wp_trim_excerpt('',$single->ID);
				    //before 9.3.5 $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
                    
					//9.3.5
                    $excerpt_more = tt_excerpt_more_free($single->ID,'Read More');

				    $excerpt_length = 20;
				    $m_content = tt_wp_trim_words_free( $single->post_content, $excerpt_length, $excerpt_more );
				    
				}
				else 
				{
				    
					$m_content =  $single->post_excerpt;
				}
				//end 14.5.2
			}
				
			if ($glossaryExcerptOrContentSelect == 'glossarycontent')
			{
			    
				$m_content = $single->post_content;
			}

			//14.1.0
			if (empty($glossaryExcerptOrContentSelect))
			{
			    
				$m_content = $single->post_content;
			}

			//24.6.8 avoid conflicts
			//$m_content = do_shortcode($m_content);

			$return_content .=	$m_content;
			$return_content .='</div>';
			$return_content .='</div>';
			$return_content .='</span>';
			$return_content .='</div>';
			
		}
	}
	$return_content .='</div>'; // product_list_start  12.7.6

	
	$return_content .= '</div>';

	//!!! star 14.6.4 advanced glossary shortcode only for multisite license
	//[glossary titlebackgroundcolor='red' contentbackgroundcolor='#963412']
	$css_content = '';
	$return_content = $css_content.$return_content;
	//end 14.6.4
	
	return $return_content;
}
add_shortcode( 'producttable', 'product_table_shortcode_free',10 );
add_shortcode( 'productdirectory', 'product_table_shortcode_free',10 );

//!!! 14.0.4
function disabletooltipforproductdirectory_free()
{
	$tooltipforleftcolumnglossarytable = get_option("tooltipforleftcolumnglossarytable");
	$tooltipforrightcolumnglossarytable = get_option("tooltipforrightcolumnglossarytable");
	
	if ((empty($tooltipforleftcolumnglossarytable)) || ($tooltipforleftcolumnglossarytable == 'NO'))
	{
		{
			echo '<script type="text/javascript">';
			// $disabletooltipforclassandidsSingle = trim($disabletooltipforclassandidsSingle);
			//!!! 15.3.4 disabletooltipforclassandidSinglei = jQuery(this).html(); 
			?>
			jQuery(document).ready(function () {
				jQuery('.tooltips_table_items .tooltips_table_title .tooltipsall').each
				(function()
				{
				disabletooltipforclassandidSinglei = jQuery(this).text();
				jQuery(this).replaceWith(disabletooltipforclassandidSinglei);
				})
			})
			<?php 
			echo '</script>';
		}
	}
	
	//14.0.8
	if ((empty($tooltipforrightcolumnglossarytable)) || ($tooltipforrightcolumnglossarytable == 'NO'))
	{
		{
			echo '<script type="text/javascript">';
			// $disabletooltipforclassandidsSingle = trim($disabletooltipforclassandidsSingle);
			?>
				jQuery(document).ready(function () {
					jQuery('.tooltips_table_items .tooltips_table_content .tooltipsall').each
					(function()
					{
					disabletooltipforclassandidSinglei = jQuery(this).html();
					jQuery(this).replaceWith(disabletooltipforclassandidSinglei);
					})
				})
				<?php 
				echo '</script>';
			}
	}
		

}
add_action('wp_footer','disabletooltipforproductdirectory_free');

