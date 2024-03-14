<?php
if (!defined('ABSPATH'))
{
	exit;
}

//require_once("rules/useroles.php");
//require_once("admin/admin.php");

function member_directory_loader_scripts()
{
	wp_register_style( 'directorycss', plugin_dir_url( __FILE__ ).'asset/js/jdirectory/directory.css');
	wp_enqueue_style( 'directorycss' );
	
	wp_register_script( 'directoryjs', plugin_dir_url( __FILE__ ).'asset/js/jdirectory/jquery.directory.js', array('jquery'));
	wp_enqueue_script( 'directoryjs' );
}
// add_action( 'wp_enqueue_scripts', 'member_directory_loader_scripts' );


function member_directory_shortcode($atts)
{
	global $table_prefix,$wpdb,$post;

	$member_user_role = '';
	$member_user_include = '';

	//9.4.9
    if (isset($atts['role'])) {
        $member_user_role = sanitize_text_field($atts['role']);
    }	

	//9.5.9
    if (isset($atts['include'])) {
        $member_user_include = sanitize_text_field($atts['include']);
    }	

	$return_content = '';
	$return_content .= '<div class="member_directory_table">';		


	//9.6.1
    if (isset($atts['exclude'])) {
        $member_user_exclude = sanitize_text_field($atts['exclude']);
    }	

	/*
	// before 9.5.9
	//9.4.9
	if (!(empty($member_user_role)))
	{
		$member_user_role_array = array('role' => $member_user_role);
		$results = get_users($member_user_role_array);
	}
	else
	{
		$results = get_users();
	}
	*/

	//9.5.9
	if (!(empty($member_user_role)))
	{
		$member_user_role_array = array('role' => $member_user_role);
		$results = get_users($member_user_role_array);
	}

	//9.5.9 10242
	if (!(empty($member_user_include)))
	{
		$member_user_include_array = array('include' => $member_user_include);
		$results = get_users($member_user_include_array);
	}

	if ((empty($member_user_role)) && (empty($member_user_include)))
	{
		$results = get_users();
	}
	// end 9.5.9

	//9.6.1
	if (!(empty($member_user_exclude)))
	{
		$member_user_exclude_array = array('exclude' => $member_user_exclude);
		$results = get_users($member_user_exclude_array);
	}

	if ((!(empty($results))) && (is_array($results)) && (count($results) >0))
	{
		$m_single = array();
		foreach ($results as $single)
		{
			$user_allowed_listed = true;
			$memberDirectoryUserRoleSelect = get_option('memberDirectoryUserRoleSelect');
			if (empty($memberDirectoryUserRoleSelect))
			{
				
			}
			else
			{
				$user_allowed_listed = check_user_role_allowed($single);
				
				if ($user_allowed_listed == false)
				{
					continue;
				}

				//8.7.1
				$check_user_exclude_pro = check_user_exclude_free($single);
				if ($check_user_exclude_pro == false)
				{
				    continue;
				}
				//8.7.1
				
			}
			
			$return_content .= '<div class="tooltips_list">';
			$return_content .= '<span class="tooltips_table_items">';
			$return_content .= '<div class="tooltips_table">';
			$return_content .= '<div class="tooltips_table_title">';
			$enabGlossaryIndexPage =  get_option("enabGlossaryIndexPage");

			$return_content .=	$single->display_name;
			$return_content .='</div>';
			$return_content .= '<div class="tooltips_table_content">';

			// old $m_content = $single->user_email;
			// 1.3.1
			$m_content = '';
			$m_content_user_email = $single->user_email;
			$m_content_user_bio_in_wp = get_the_author_meta('description',$single->ID);
			$m_content .= "<div class = 'member_content_user_email'>";
			$m_content .= $m_content_user_email;
			$m_content .= "</div>";
			$m_content .= "<div class = 'member_content_user_description'>";
			$m_content .= $m_content_user_bio_in_wp;
			$m_content .= "</div>";
			
			$return_content .=	$m_content;
			$return_content .='</div>';
			$return_content .='</div>';
			$return_content .='</span>';
			$return_content .='</div>';
		}
	}
	$return_content .= '</div>';
	
	return $return_content;
}
add_shortcode( 'member_directory', 'member_directory_shortcode',10 );



function member_directory_load_footer_js()
{
	global $post;
	?>
<script type="text/javascript">
var inboxs = new Array();
inboxs['hidezeronumberitem'] = "yes";
inboxs['selectors'] = '.tooltips_list > span';
<?php 
$glossaryNavItemFontSize = '12px';
$glossarySelectedNavItemFontSize = get_option("glossarySelectedNavItemFontSize");
$glossarySelectedNavItemFontSize = '14px';
$glossaryNavItemFontSize = '12px';
?>
<?php
/*
 before 9.5.9 inboxs['navitemdefaultsize'] = '<?php echo $glossaryNavItemFontSize; ?>'; 
*/ 
//9.5.9 
?>
inboxs['navitemdefaultsize'] = '<?php echo esc_attr($glossaryNavItemFontSize); ?>'; 
<?php
/* 
before 9.5.9 inboxs['navitemselectedsize'] = '<?php echo $glossarySelectedNavItemFontSize; ?>';
*/
//9.5.9
?>
inboxs['navitemselectedsize'] = '<?php echo esc_attr($glossarySelectedNavItemFontSize); ?>';
<?php 

$glossaryNumbersOrNot = 'no';

//8.4.3
$choseLanguageForGlossary = get_option("enableLanguageForGlossary");
if (empty($choseLanguageForGlossary)) $choseLanguageForGlossary = 'en';
$hidezeronumberitem = get_option('hidezeronumberitem');
if (empty($hidezeronumberitem)) $hidezeronumberitem = 'no';
//end 8.4.3

if ($choseLanguageForGlossary == 'custom')
{
	$glossaryLanguageCustomNavLetters = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
	/* before 9.5.9
	inboxs['alphabetletters'] = "<?php echo $glossaryLanguageCustomNavLetters; ?>";
	*/
	?>
	inboxs['alphabetletters'] = "<?php echo esc_attr($glossaryLanguageCustomNavLetters); ?>";
	<?php
}
?>
inboxs['number'] = "no";
jQuery(document).ready(function () {
	jQuery('.member_directory_table').directory(inboxs);
	<?php 
	/*
	//before 9.5.9
	jQuery('.navitem').css('font-size','<?php echo $glossaryNavItemFontSize; ?>');	
	*/
	//9.5.9
	?>
	jQuery('.navitem').css('font-size','<?php echo esc_attr($glossaryNavItemFontSize); ?>');	
})
</script>
<?php
}
add_action('wp_footer','member_directory_load_footer_js');


function check_user_role_allowed($checkuser)
{
	$memberDirectoryUserRoleSelect = get_option('memberDirectoryUserRoleSelect');
	$saved_allowed_user_roles_in_member_directory = get_option('saved_allowed_user_roles_in_member_directory');
	
	//18.3.8
	if (empty($checkuser))
	{
	    return false;
	}
	
	if (empty($memberDirectoryUserRoleSelect))
	{
		return true;
	}
	else
	{
		if ('enableMemberDirectoryUserRolesOption' == $memberDirectoryUserRoleSelect)
		{
			$can_listed = false;
			
			$checking_user_roles = $checkuser->roles;
			
			
			
			
			if (empty($checking_user_roles))
			{
				return false ;
			}
			else 
			{
				foreach ($checking_user_roles as $checking_user_role)
				{
					if (in_array(strtolower($checking_user_role), $saved_allowed_user_roles_in_member_directory) )
					{
						
						$can_listed = true;
						
						
						
						return true;
					}
				}
			}
			
		}
		
		if ('disableMemberDirectoryUserRolesOption' == $memberDirectoryUserRoleSelect)
		{
			$can_listed = true;
			$checking_user_roles = $checkuser->roles;
			if (empty($checking_user_roles))
			{
				return false ;
			}
			else
			{
				foreach ($checking_user_roles as $checking_user_role)
				{
					if (in_array(strtolower($checking_user_role), $saved_allowed_user_roles_in_member_directory) )
					{
						$can_listed = false;
						return false;
					}
				}
			}
				
		}
		
		return $can_listed;
	}
	
	
}


//8.7.1
function check_user_exclude_free($checkuser)
{
    
    $bulkremoveuseridfrommemberdirectory = '';
    $bulkremoveuseridfrommemberdirectory = get_option('bulkremovetermfromglossarylist');
    
    
    if (!(empty($bulkremoveuseridfrommemberdirectory)))
    {
        $patterns = '';
        $replacements = '';
        $bulkremoveuseridfrommemberdirectory = trim($bulkremoveuseridfrommemberdirectory);
        $bulkremoveuseridfrommemberdirectoryarray = explode(',', $bulkremoveuseridfrommemberdirectory);
        
        if ((!(empty($bulkremoveuseridfrommemberdirectoryarray))) && (is_array($bulkremoveuseridfrommemberdirectoryarray)) && (count($bulkremoveuseridfrommemberdirectoryarray) > 0))
        {
            $bulkremoveuseridfrommemberdirectoryarray = array_filter($bulkremoveuseridfrommemberdirectoryarray);
        }
        
        if ((!(empty($bulkremoveuseridfrommemberdirectoryarray))) && (is_array($bulkremoveuseridfrommemberdirectoryarray)) && (count($bulkremoveuseridfrommemberdirectoryarray) > 0))
        {
            if (in_array($checkuser->data->ID, $bulkremoveuseridfrommemberdirectoryarray))
            {
                return false;
            }
        }
    }
    return true;
}

