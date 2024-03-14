<?php 
	/**
	 * Process: Insert Video
	 */

	if(isset($_POST['tntAddVideo']))
	{
		$videoCat 		= $_POST["vCat"];
		$videoType 		= $_POST["vLinkType"];
		$arrVideoTitle 	= $_POST["vTitle"]; 
		$arrVideoLink 	= $_POST["vLink"];
		$arrVideoStatus = $_POST["vStatus"];
		$arrVideoOrder 	= $_POST["vOrder"];
		$dateCreated 	= time();
		$dateModified 	= time();
		$userID 		= (int)$_POST['vUserID'];

		$countVideo = count($arrVideoTitle);

		for($i=0; $i<$countVideo; $i++)
		{
			$v = new TNT_Video();
			$v->videoTitle 	 = esc_html($arrVideoTitle[$i]);
			$v->videoCat 	 = $videoCat;
			$v->videoType 	 = $videoType;
			$v->videoLink 	 = esc_url($arrVideoLink[$i]);
			$v->videoStatus  = $arrVideoStatus[$i];
			$v->videoOrder 	 = $arrVideoOrder[$i];
			$v->dateCreated  = $dateCreated;
			$v->dateModified = $dateModified;
			$v->userID 		 = $userID;
			$v->tntInsertVideo();
		}
		
		// if($v->tntInsertVideo())
		// {
		// 	$location = add_query_arg(array('m'=>1));
		// }
		// else
		// {
		// 	$location = add_query_arg(array('m'=>0));			
		// }
		$location = add_query_arg(array('m'=>1));
		Header("Location: $location");
	}

	
	/**
	 * Process: Edit Video
	 */
	if(isset($_POST['tntEditVideo']))
	{	
		$videoID = $_POST["vID"];
		$v = new TNT_Video();
		$v->tntGetVideo($videoID);

		$v->videoID 	 = $videoID;
		$v->videoTitle 	 = esc_html($_POST["vTitle"]);
		$v->videoCat 	 = $_POST["vCat"];
		$v->videoType 	 = $_POST["vLinkType"];
		$v->videoLink 	 = esc_url($_POST["vLink"]);
		$v->videoStatus  = ($_POST["vStatus"]) ? $_POST["vStatus"] : "0";
		$v->videoOrder 	 = $_POST["vOrder"];
		$v->dateModified = time(); 
		
		if($v->tntUpdateVideo() >= 0)
		{
			// wp_die("Edited Sucessfully!");
			$location = add_query_arg(array('m'=>1));
		}
		else if($v->tntUpdateVideo() == false)
		{
			$location = add_query_arg(array('m'=>0));
		}
		Header("Location: $location");
	}


	/**
	 * Process: Delete Video (Click Yes)
	 */
	if(isset($_POST['tntDelVideo_Yes']))
	{
		$location = admin_url()."/admin.php?page=tnt_video_manage_page";
		$v = new TNT_Video();
		$v->videoID 	= $_POST["vID"];
		$v->tntDeleteVideo();
		Header("Location: $location");
	}

	/**
	 * Process: Delete Video (Click No)
	 */
	if(isset($_POST['tntDelVideo_No']))
	{
		$location = admin_url()."/admin.php?page=tnt_video_manage_page";
		Header("Location: $location");
	}

	/**
	 * Process: Insert Video Type
	 */

	if(isset($_POST['tntAddVideoType']))
	{
		$t = new TNT_VideoType();

		$t->videoTypeTitle 		= $_POST["typeTitle"];
		
		if($t->tntInsertVideoType())
		{
			$location = add_query_arg(array('m'=>1));
		}
		else
		{
			$location = add_query_arg(array('m'=>0));
		}
		Header("Location: $location");
	}

	/**
	 * Process: Edit Video Type
	 */
	if(isset($_POST['tntEditVideoType']))
	{	
		$typeID = $_POST["typeID"];
		$t = new TNT_VideoType();
		$t->tntGetType($typeID);
		
		$t->videoTypeTitle 		= $_POST["typeTitle"];

		if($t->tntUpdateVideoType() >= 0)
		{
			$location = add_query_arg(array('m'=>1));
		}
		else if($t->tntUpdateVideoType() == false)
		{
			$location = add_query_arg(array('m'=>0));	
		}

		Header("Location: $location");
	}

	/**
	 * Process: Delete Video Type (Click Yes)
	 */
	if(isset($_POST['tntDelVideoType_Yes']))
	{
		$location = admin_url()."/admin.php?page=tnt_video_type_manager_page";
		$t = new TNT_VideoType();
		$t->videoTypeID = $_POST["typeID"];

		//Update status of videos have typeID deleted into 0 (unpubslished)
		$args = array('typeID' => $t->videoTypeID);
		$videos = TNT_Video::tntGetVideos($args);
		if($videos != null)
		{
			wp_die("This type is having videos");
		}
		else
		{
			//Delete video type
			$t->tntDeleteVideoType();
		}
		Header("Location: $location");
	}

	/**
	 * Process: Delete Video Type (Click No)
	 */
	if(isset($_POST['tntDelVideoType_No']))
	{
		$location = admin_url()."/admin.php?page=tnt_video_type_manager_page";
		Header("Location: $location");
	}

	/**
	 * Process: Insert Video Category
	 */

	if(isset($_POST['tntAddVideoCat']))
	{
		$c = new TNT_VideoCat();

		$c->videoCatTitle 		= $_POST["catTitle"];
		$t->videoCatParent 		= 0;

		if($c->tntInsertVideoCat())
		{
			$location = add_query_arg(array('m'=>1));
		}
		else
		{
			$location = add_query_arg(array('m'=>0));
		}
		Header("Location: $location");
	}

	/**
	 * Process: Edit Video Category
	 */
	if(isset($_POST['tntEditVideoCat']))
	{	
		$catID = $_POST["catID"];
		$c = new TNT_VideoCat();
		$c->tntGetCat($catID);
		
		$c->videoCatTitle 	= $_POST["catTitle"];
		
		if($c->tntUpdateVideoCat() >= 0)
		{
			$location = add_query_arg(array('m'=>1));
		}
		else if($t->tntUpdateVideoType() == false)
		{
			$location = add_query_arg(array('m'=>0));
		}
		Header("Location: $location");
	}

	/**
	 * Process: Delete Video Category (Click Yes)
	 */
	if(isset($_POST['tntDelVideoCat_Yes']))
	{
		$location = admin_url()."/admin.php?page=tnt_video_cat_manager_page";
		$c = new TNT_VideoCat();
		$c->videoCatID = $_POST["catID"];

		$args = array('catID' => $c->videoCatID);
		$videos = TNT_Video::tntGetVideos($args);
		if($videos != null)
		{
			wp_die("This cat is having videos");
		}
		else
		{
			//Delete video cat
			$c->tntDeleteVideoCat();
		}
		Header("Location: $location");
	}

	/**
	 * Process: Delete Video Category (Click No)
	 */
	if(isset($_POST['tntDelVideoCat_No']))
	{
		$location = admin_url()."/admin.php?page=tnt_video_cat_manager_page";
		Header("Location: $location");
	}

	/**
	 * Process: Update Video Options
	 */
	if(isset($_POST['tntUpdateVideoOptions']))
	{
		$videoLimit               = $_POST['videoLimit'];
		$videoLimitAdmin          = $_POST['videoLimitAdmin'];
		$videoColumn              = $_POST['videoColumn'];
		$tntJquery                = $_POST['tntJquery'];
		$tntColorbox              = $_POST['tntColorbox'];
		$skinColorbox             = $_POST['skinColorbox'];
		$videoWidth               = $_POST['videoWidth'];
		$videoHeight              = $_POST['videoHeight'];
		$videoOrder               = $_POST['videoOrder'];
		$videoOrderBy             = $_POST['videoOrderBy'];
		$tntSocialFeature         = $_POST['tntSocialFeature'];
		$tntSocialFeatureFB       = $_POST['tntSocialFeatureFB'];
		$tntSocialFeatureTW       = $_POST['tntSocialFeatureTW'];
		$tntSocialFeatureG        = $_POST['tntSocialFeatureG'];
		$tntSocialFeatureP        = $_POST['tntSocialFeatureP'];
		$tntSocialFeatureIconSize = $_POST['tntSocialFeatureIconSize'];

		if($videoLimit != "")
		{
			$videoOptions = array(
				'limitPerPage'          => $videoLimit,
				'limitAdminPerPage'     => $videoLimitAdmin,
				'columnPerRow'          => $videoColumn,
				'tntJquery'             => $tntJquery,
				'tntColorbox'           => $tntColorbox,	
				'skinColorbox'          => $skinColorbox,
				'videoWidth'            => $videoWidth,
				'videoHeight'           => $videoHeight,
				'videoOrder'            => $videoOrder,
				'videoOrderBy'          => $videoOrderBy,
				'socialFeature'         => $tntSocialFeature,
				'socialFeatureFB'       => $tntSocialFeatureFB,
				'socialFeatureTW'       => $tntSocialFeatureTW,
				'socialFeatureG'        => $tntSocialFeatureG,
				'socialFeatureP'        => $tntSocialFeatureP,
				'socialFeatureIconSize' => $tntSocialFeatureIconSize
			);
			update_option('tntVideoManageOptions', $videoOptions);

			$location = add_query_arg(array('m'=>1));
		}
		else
		{
			$location = add_query_arg(array('m'=>0));
		}
		Header("Location: $location");
	}

	/**
	 * Process: Update Action Selected
	 */
	if(isset($_POST['tntBtnAct']))
	{
		$tntVid = new TNT_Video();

		$tntAction = $_POST["tntActions"]; 

		$arrVideoID = $_POST["tntChkVideos"];

		$tntResult = true;

		switch($tntAction)
		{
			case 1 : 
				foreach($arrVideoID as $vID)
				{
					if($vID)
					{
						$tntVid->tntGetVideo($vID);
						$tntVid->videoStatus = 1;
						$tntVid->tntUpdateVideo();
					}
					else
					{
						$tntResult = false; break;
					}
				}
				break;	
			case 2 : 
				foreach($arrVideoID as $vID)
				{
					if($vID)
					{
						$tntVid->tntGetVideo($vID);
						$tntVid->videoStatus = 0;
						$tntVid->tntUpdateVideo();
					}
					else
					{
						$tntResult = false; break;
					}
				}
				break;
			case 3: 
				foreach($arrVideoID as $vID)
				{
					if($vID)
					{
						$tntVid->tntGetVideo($vID);
						$tntVid->tntDeleteVideo();
					}
					else
					{
						$tntResult = false; break;
					}
				}
				break;

			default: 
				break;
		}
		if($tntResult == true)
		{
			$location = add_query_arg(array('m'=>1));
		}
		else
		{
			$location = add_query_arg(array('m'=>0));
		}
		Header("Location: $location");
	}
 ?>