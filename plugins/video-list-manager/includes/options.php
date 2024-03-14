<?php 
	/**
	 * Create Options
	 * 
	 * Create options for plugins
	 *
	 * Author: Tung Pham
	 */

	function tnt_videos_create_options(){
        $videoOptions = array(
            'limitPerPage'          => 4,
            'limitAdminPerPage'     => 10,
            'columnPerRow'          => 2,
            'tntJquery'             => 1,
            'tntColorbox'           => 1,
            'skinColorbox'          => 1,
            'videoWidth'            => 480,
            'videoHeight'           => 360,
            'videoOrder'            => 'addingdate',
            'videoOrderBy'          => 'desc',
            'socialFeature'         => 1,
            'socialFeatureFB'       => 1,
            'socialFeatureTW'       => 1,
            'socialFeatureG'        => 1,
            'socialFeatureP'        => 1,     
            'socialFeatureIconSize' => 32    
        );

        add_option('tntVideoManageOptions', $videoOptions);
    }
 ?>