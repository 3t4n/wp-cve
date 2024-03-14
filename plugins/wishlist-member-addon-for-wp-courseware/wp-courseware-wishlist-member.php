<?php
/*
 * Plugin Name: WP Courseware - Wishlist Member Add On
 * Version: 1.4
 * Plugin URI: http://flyplugins.com
 * Description: The official extension for WP Courseware to add support for the Wishlist Member membership plugin for WordPress.
 * Author: Fly Plugins
 * Author URI: http://flyplugins.com
 */



// Main parent class
include_once 'class_members.inc.php';

// Hook to load the class
// Set to priority of 1 so that it works correctly with WishList Member
// that specifically needs this to be a priority of 1.
add_action('init', 'WPCW_Members_WishList_init', 1);


/**
 * Initialize the membership plugin, only loaded if WP Courseware 
 * exists and is loading correctly.
 */
function WPCW_Members_WishList_init()
{
	$item = new WPCW_Members_WishList();
	
	// Check for WP Courseware
	if (!$item->found_wpcourseware()) {
		$item->attach_showWPCWNotDetectedMessage();
		return;
	}
	
	// Not found the membership tool
	if (!$item->found_membershipTool()) {
		$item->attach_showToolNotDetectedMessage();
		return;
	}
	
	// Found the tool and WP Coursewar, attach.
	$item->attachToTools();
}


/**
 * Membership class that handles the specifics of the WishList Member WordPress plugin and
 * handling the data for levels for that plugin.
 */
class WPCW_Members_WishList extends WPCW_Members
{
	const GLUE_VERSION  	= 1.00; 
	const EXTENSION_NAME 	= 'WishList Member';
	const EXTENSION_ID 		= 'WPCW_members_wishlist';
	
	
	
	/**
	 * Main constructor for this class.
	 */
	function __construct()
	{
		// Initialize using the parent constructor 
		parent::__construct(WPCW_Members_WishList::EXTENSION_NAME, WPCW_Members_WishList::EXTENSION_ID, WPCW_Members_WishList::GLUE_VERSION);
	}
	
	
	
	/**
	 * Get list of membership levels
	 */
	protected function getMembershipLevels()
	{
		// $levelData = new WLMAPI();
		// $levelData = $levelData->GetLevels();
		$levelData = wlmapi_get_levels();	
		
		if ($levelData && count($levelData) > 0)
		{
			$levelDataStructured = array();
			$levels = $levelData['levels']['level'];
			
			// Format the data in a way that we expect and can process
			foreach ($levels as $level)
			{
				$levelItem = array();
				$levelItem['name'] 	= $level['name'];
				$levelItem['id'] 	= $level['id'];
				//$levelItem['raw'] 	= $level;	
				$levelDataStructured[$level['id']] = $levelItem;
			}
			return $levelDataStructured;
		}
		return false;
	}

	
	/**
	 * Function called to attach hooks for handling when a user is updated or created.
	 */	
	protected function attach_updateUserCourseAccess()
	{
		// Events called whenever the user levels are changed, which updates the user access.
		add_action('wishlistmember_add_user_levels', 		array($this, 'handle_updateUserCourseAccess'), 10, 2);
		add_action('wishlistmember_remove_user_levels', 	array($this, 'handle_updateUserCourseAccess'), 10, 2);
		add_action('wishlistmember_unapprove_user_levels', 	array($this, 'handle_updateUserCourseAccess'), 10, 2);
		add_action('wishlistmember_approve_user_levels', 	array($this, 'handle_updateUserCourseAccess'), 10, 2);
		add_action('wishlistmember_unconfirm_user_levels', 	array($this, 'handle_updateUserCourseAccess'), 10, 2);
		add_action('wishlistmember_confirm_user_levels', 	array($this, 'handle_updateUserCourseAccess'), 10, 2);
		add_action('wishlistmember_cancel_user_levels', 	array($this, 'handle_updateUserCourseAccess'), 10, 2);
		add_action('wishlistmember_uncancel_user_levels', 	array($this, 'handle_updateUserCourseAccess'), 10, 2);
	}

/**
	 * Assign selected courses to members of a paticular level.
	 * @param Level ID in which members will get courses enrollment adjusted.
	 */
	protected function retroactive_assignment($level_ID)
    {
    	global $wpdb, $WishListMemberInstance;

    	// Batch Stuff START*******************************************************************

			$batchNumber = 50;

			$step = isset( $_GET['step'] ) ? absint( $_GET['step'] ) : 1;	
			$count = isset( $_GET['count'] ) ? absint( $_GET['count'] ) : 0;
			$steps = isset( $_GET['steps'] ) ? $_GET['steps'] : 'continue';

			if (isset($_POST['retroactive_assignment'])) {
				$step =  0;	
				$count = 0;
				$steps = 'continue';
			}


		// Batch Stuff END*******************************************************************


    	//Get members associated with $level_ID
		//$members = $WishListMemberInstance->MemberIDs($level_ID,null, null);

		$members = $wpdb->get_results($wpdb->prepare("
            SELECT DISTINCT {$wpdb->users}.ID
            FROM {$wpdb->users}
            WHERE {$wpdb->users}.ID IN (SELECT DISTINCT user_id FROM {$wpdb->prefix}wlm_userlevels WHERE level_id = %s)
            LIMIT %d
            OFFSET %d
        ", $level_ID, $batchNumber, $count), ARRAY_A);

        $count += count($members);

        $page = new PageBuilder(false);

        //Do we have members?
		if (!$count) {
			$page->showMessage(__('No existing customers found for the specified level.', 'wp_courseware'));
			return;
		}

		if ( isset($members) && 'continue' == $steps){
			
			if (count($members) < $batchNumber){
				$steps = 'final';
			}

			if (count($members) > 0){

				//Enroll members into of level
				foreach ($members as $member){

					// Get user levels
					$userLevels = $WishListMemberInstance->GetMemberActiveLevels($member['ID']);
					// Over to the parent class to handle the sync of data.
					parent::handle_courseSync($member['ID'], $userLevels);
				}

					// Batch Stuff START*******************************************************************

				$step += 1;

				?>
					<script type="text/javascript">
						document.location.href = "admin.php?page=WPCW_members_wishlist&level_id=<?php echo $level_ID; ?>&step=<?php echo $step; ?>&count=<?php echo $count; ?>&steps=<?php echo $steps; ?>&action=retroactiveassignment";
					</script>

				<?php

					// Batch Stuff END*********************************************************************
				
			}
		}else{
	           $page->showMessage(__('All existing customers have been updated.', 'wp_courseware'));
	        }
    }

	/**
	 * Function just for handling the membership callback, to interpret the parameters
	 * for the class to take over.
	 * 
	 * @param Integer $id The ID if the user being changed.
	 * @param Array $levels The list of levels for the user.
	 */
	public function handle_updateUserCourseAccess($id, $levels)
	{
		// Get all user levels, with IDs.
		// $userLevels = new WLMAPI();
		// $userLevels = $userLevels->GetUserLevels( $id, 'all', 'skus');
		$userLevels = array_keys( wlmapi_get_member_levels( $id ) );
		
		// Over to the parent class to handle the sync of data.
		parent::handle_courseSync($id, $userLevels);
	}
	
	
	/**
	 * Detect presence of the membership plugin.
	 */
	public function found_membershipTool()
	{
		return class_exists('WLMAPI') || class_exists( 'WishListMember' );
	}
	
	
}


?>