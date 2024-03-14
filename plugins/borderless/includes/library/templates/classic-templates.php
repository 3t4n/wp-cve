<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( $theme == 'Aesir' ) {
  $aesir = [
    
    [
      'import_file_name'           => 'Business',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/business/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/business/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/business/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-business-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/business/',
    ],
    
    [
      'import_file_name'           => 'Gym',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/gym/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/gym/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/gym/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-gym-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/gym/',
    ],
    
    [
      'import_file_name'           => 'Architects',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/architects/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/architects/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/architects/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-architects-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/architects/',
    ],
    
    [
      'import_file_name'           => 'Creative',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-creative-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/creative/',
    ],
    
    [
      'import_file_name'           => 'Cafe',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cafe/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cafe/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cafe/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-cafe-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/cafe/',
    ],
    
    [
      'import_file_name'           => 'Church',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/church/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/church/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/church/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-church-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/church/',
    ],
    
    [
      'import_file_name'           => 'Construction',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/construction/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/construction/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/construction/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-construction-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/construction/',
    ],
    
    [
      'import_file_name'           => 'Cryptocurrency',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cryptocurrency/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cryptocurrency/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cryptocurrency/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-cryptocurrency-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/cryptocurrency/',
    ],
    
    [
      'import_file_name'           => 'Creative Studio',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative-studio/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative-studio/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative-studio/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-creative-studio-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/creative-studio/',
    ],
    
    [
      'import_file_name'           => 'Education',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/education/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/education/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/education/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-education-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/education/',
    ],
    
    [
      'import_file_name'           => 'Employment',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/employment/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/employment/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/employment/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-employment-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/employment/',
    ],
    
    [
      'import_file_name'           => 'Financial',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/financial/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/financial/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/financial/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-financial-cover-1-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/financial/',
    ],
    
    [
      'import_file_name'           => 'Fitness',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Shop', 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fitness/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fitness/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fitness/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-fitness-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/fitness/',
    ],
    
    [
      'import_file_name'           => 'Restaurant',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/restaurant/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/restaurant/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/restaurant/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-restaurant-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/restaurant/',
    ],
    
    [
      'import_file_name'           => 'Community',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment', 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/community/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/community/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/community/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-community-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/community/',
    ],
    
    [
      'import_file_name'           => 'Hotel',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Travel' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hotel/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hotel/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hotel/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-hotel-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/hotel/',
    ],
    
    [
      'import_file_name'           => 'Writer Blog',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Blog' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/writer-blog/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/writer-blog/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/writer-blog/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-writer-blog-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/writer-blog/',
    ],
    
    [
      'import_file_name'           => 'IT',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/it/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/it/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/it/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-it-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/it/',
    ],
    
    [
      'import_file_name'           => 'Startup',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/startup/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/startup/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/startup/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-startup-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/startup/',
    ],
    
    [
      'import_file_name'           => 'Mechanic',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mechanic/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mechanic/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mechanic/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-mechanic-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/mechanic/',
    ],
    
    [
      'import_file_name'           => 'Hospital',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hospital/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hospital/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hospital/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-hospital-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/hospital/',
    ],
    
    [
      'import_file_name'           => 'Minimalist Agency',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/minimalist-agency/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/minimalist-agency/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/minimalist-agency/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-minimalist-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/minimalist-agency/',
    ],
    
    [
      'import_file_name'           => 'Music',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/music/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/music/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/music/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-music-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/music/',
    ],
    
    [
      'import_file_name'           => 'Graphic Designer',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/graphic-designer/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/graphic-designer/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/graphic-designer/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-graphic-designer-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/graphic-designer/',
    ],
    
    [
      'import_file_name'           => 'Nonprofit',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nonprofit/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nonprofit/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nonprofit/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-nonprofit-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/nonprofit/',
    ],
    
    [
      'import_file_name'           => 'Creative Agency',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative-agency/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative-agency/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/creative-agency/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-creative-agency-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/creative-agency/',
    ],
    
    [
      'import_file_name'           => 'Petshop',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/petshop/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/petshop/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/petshop/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-petshop-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/petshop/',
    ],
    
    [
      'import_file_name'           => 'Photographer',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/photographer/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/photographer/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/photographer/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-photographer-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/photographer/',
    ],
    
    [
      'import_file_name'           => 'Politic',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/politic/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/politic/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/politic/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-politic-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/politic/',
    ],
    
    [
      'import_file_name'           => 'Agency',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/agency/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/agency/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/agency/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-agency-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/agency/',
    ],
    
    [
      'import_file_name'           => 'Real Estate',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Real Estate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/realestate/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/realestate/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/realestate/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-realestate-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/realestate/',
    ],
    
    [
      'import_file_name'           => 'Salon',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/salon/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/salon/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/salon/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-salon-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/salon/',
    ],
    
    [
      'import_file_name'           => 'Fashion Shop',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fashion-shop/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fashion-shop/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fashion-shop/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-fashion-shop-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/fashion-shop/',
    ],
    
    [
      'import_file_name'           => 'Digital Agency',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/digital-agency/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/digital-agency/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/digital-agency/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-digital-agency-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/digital-agency/',
    ],
    
    [
      'import_file_name'           => 'Sports',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Shop', 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/sports/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/sports/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/sports/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-sport-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/sports/',
    ],
    
    [
      'import_file_name'           => 'Freelancer Designer',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/freelancer-designer/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/freelancer-designer/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/freelancer-designer/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-freelancer-designer-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/freelancer-designer/',
    ],
    
    [
      'import_file_name'           => 'Travel Blog',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Blog', 'Travel' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/travel-blog/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/travel-blog/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/travel-blog/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-travel-blog-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/travel-blog/',
    ],
    
    [
      'import_file_name'           => 'Wedding',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Wedding' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wedding/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wedding/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wedding/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-wedding-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/wedding/',
    ],
    
    [
      'import_file_name'           => 'Wines',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food', 'Health / Beauty', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wines/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wines/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wines/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-wines-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/wines/',
    ],
    
    [
      'import_file_name'           => 'Web Studio',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/web-studio/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/web-studio/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/web-studio/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-web-studio-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/web-studio/',
    ],
    
    [
      'import_file_name'           => 'Psychology',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/psychology/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/psychology/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/psychology/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-psychology-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/psychology/',
    ],
    
    [
      'import_file_name'           => 'Veterinarian',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/veterinarian/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/veterinarian/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/veterinarian/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-veterinarian-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/veterinarian/',
    ],
    
    [
      'import_file_name'           => 'Barber',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/barber/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/barber/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/barber/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-barber-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/barber/',
    ],
    
    [
      'import_file_name'           => 'Dentist',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/dental/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/dental/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/dental/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-dentist-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/dental/',
    ],
    
    [
      'import_file_name'           => 'Spa',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/spa/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/spa/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/spa/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-spa-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/spa/',
    ],
    
    [
      'import_file_name'           => 'Bakery',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Food' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/bakery/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/bakery/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/bakery/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-bakery-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/bakery/',
    ],
    
    [
      'import_file_name'           => 'Nutritionist',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nutritionist/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nutritionist/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nutritionist/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-nutritionist-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/nutritionist/',
    ],
    
    [
      'import_file_name'           => 'Lawyer',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/law/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/law/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/law/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-lawyer-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/law/',
    ],
    
    [
      'import_file_name'           => 'Logistics',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/logistics/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/logistics/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/logistics/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-logistics-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/logistics/',
    ],
    
    [
      'import_file_name'           => 'Hosting',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hosting/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hosting/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hosting/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-hosting-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/hosting/',
    ],
    
    [
      'import_file_name'           => 'Repair',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/repair/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/repair/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/repair/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-repair-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/repair/',
    ],
    
    [
      'import_file_name'           => 'Oculist',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/oculist/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/oculist/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/oculist/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-oculist-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/oculist/',
    ],
    
    [
      'import_file_name'           => 'Biker',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/biker/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/biker/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/biker/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-biker-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/biker/',
    ],
    
    [
      'import_file_name'           => 'Swimming Pool',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/swimming-pool/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/swimming-pool/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/swimming-pool/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-swimming-pool-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/swimming-pool/',
    ],
    
    [
      'import_file_name'           => 'Coach',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/coach/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/coach/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/coach/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-coach-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/coach/',
    ],
    
    [
      'import_file_name'           => 'Aromatherapy',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/aromatherapy/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/aromatherapy/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/aromatherapy/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-aromatherapy-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/aromatherapy/',
    ],
    
    [
      'import_file_name'           => 'Data',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/data/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/data/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/data/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-data-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/data/',
    ],
    
    [
      'import_file_name'           => 'ERP',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/erp/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/erp/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/erp/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-erp-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/erp/',
    ],
    
    [
      'import_file_name'           => 'School',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/school/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/school/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/school/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-school-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/school/',
    ],
    
    [
      'import_file_name'           => 'Horse',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/horse/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/horse/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/horse/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-horse-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/horse/',
    ],
    
    [
      'import_file_name'           => 'Farm',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/farm/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/farm/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/farm/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-farm-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/farm/',
    ],
    
    [
      'import_file_name'           => 'Home',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/home/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/home/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/home/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-home-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/home/',
    ],
    
    [
      'import_file_name'           => 'Ice Cream',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/ice-cream/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/ice-cream/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/ice-cream/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-ice-cream-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/ice-cream/',
    ],
    
    [
      'import_file_name'           => 'Electrician',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/electric/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/electric/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/electric/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-electrician-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/electric/',
    ],
    
    [
      'import_file_name'           => 'Craftbeer',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/craftbeer/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/craftbeer/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/craftbeer/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-craftbeer-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/craftbeer/',
    ],
    
    [
      'import_file_name'           => 'Mall',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mall/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mall/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mall/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-mall-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/mall/',
    ],
    
    [
      'import_file_name'           => 'Eco Food',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/eco-food/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/eco-food/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/eco-food/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-eco-food-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/eco-food/',
    ],
    
    [
      'import_file_name'           => 'Honey',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/honey/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/honey/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/honey/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-honey-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/honey/',
    ],
    
    [
      'import_file_name'           => 'Bar',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/bar/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/bar/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/bar/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-bar-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/bar/',
    ],
    
    [
      'import_file_name'           => 'Lab',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lab/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lab/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lab/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-lab-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/lab/',
    ],
    
    [
      'import_file_name'           => 'Tea',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food', 'Health / Beauty', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/tea/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/tea/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/tea/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-tea-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/tea/',
    ],
    
    [
      'import_file_name'           => 'Model',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/model/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/model/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/model/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-model-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/model/',
    ],
    
    [
      'import_file_name'           => 'Car Specification',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/car-specification/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/car-specification/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/car-specification/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-car-specification-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/car-specification/',
    ],
    
    [
      'import_file_name'           => 'Interior',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/interior/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/interior/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/interior/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-interior-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/interior/',
    ],
    
    [
      'import_file_name'           => 'Animals',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/animals/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/animals/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/animals/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-animals-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/animals/',
    ],
    
    [
      'import_file_name'           => 'Manicure',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/manicure/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/manicure/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/manicure/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-manicure-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/manicure/',
    ],
    
    [
      'import_file_name'           => 'Carpenter',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/carpenter/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/carpenter/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/carpenter/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-carpenter-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/carpenter/',
    ],
    
    [
      'import_file_name'           => 'Consultant',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/consultant/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/consultant/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/consultant/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-consultant-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/consultant/',
    ],
    
    [
      'import_file_name'           => 'Mining',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mining/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mining/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/mining/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-mining-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/mining/',
    ],
    
    [
      'import_file_name'           => 'Whiskey',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/whiskey/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/whiskey/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/whiskey/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-whiskey-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/whiskey/',
    ],
    
    [
      'import_file_name'           => 'Pest Control',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Food', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pestcontrol/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pestcontrol/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pestcontrol/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-pest-control-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/pestcontrol/',
    ],
    
    [
      'import_file_name'           => 'Call Center',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/callcenter/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/callcenter/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/callcenter/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-call-center-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/callcenter/',
    ],
    
    [
      'import_file_name'           => 'Paintball',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/paintball/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/paintball/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/paintball/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-paintball-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/paintball/',
    ],
    
    [
      'import_file_name'           => 'Cleaner',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cleaner/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cleaner/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cleaner/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-cleaner-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/cleaner/',
    ],
    
    [
      'import_file_name'           => 'Video',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/video/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/video/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/video/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-video-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/video/',
    ],
    
    [
      'import_file_name'           => 'Shoes',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/shoes/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/shoes/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/shoes/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-shoes-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/shoes/',
    ],
    
    [
      'import_file_name'           => 'Eco Meat',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/eco-meat/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/eco-meat/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/eco-meat/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-eco-meat-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/eco-meat/',
    ],
    
    [
      'import_file_name'           => 'Drone',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/drone/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/drone/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/drone/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-drone-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/drone/',
    ],
    
    [
      'import_file_name'           => 'Garden',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/garden/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/garden/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/garden/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-garden-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/garden/',
    ],
    
    [
      'import_file_name'           => 'Science',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/science/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/science/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/science/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-science-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/science/',
    ],
    
    [
      'import_file_name'           => 'Beauty',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/beauty/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/beauty/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/beauty/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-beauty-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/beauty/',
    ],
    
    [
      'import_file_name'           => 'Jeweler',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/jeweler/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/jeweler/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/jeweler/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-jeweler-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/jeweler/',
    ],
    
    [
      'import_file_name'           => 'Fire Brigade',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/firebrigade/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/firebrigade/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/firebrigade/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-fire-brigade-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/firebrigade/',
    ],
    
    [
      'import_file_name'           => 'Scooter Rental',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/scooter-rental/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/scooter-rental/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/scooter-rental/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-scooter-rental-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/scooter-rental/',
    ],
    
    [
      'import_file_name'           => 'Dance School',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/danceschool/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/danceschool/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/danceschool/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-dance-school-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/danceschool/',
    ],
    
    [
      'import_file_name'           => 'Fishing School',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fishing-school/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fishing-school/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fishing-school/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-fishing-school-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/fishing-school/',
    ],
    
    [
      'import_file_name'           => 'Driving',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Education' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/driving/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/driving/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/driving/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-driving-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/driving/',
    ],
    
    [
      'import_file_name'           => 'Industry Factory',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/industry-factory/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/industry-factory/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/industry-factory/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-industry-factory-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/industry-factory/',
    ],
    
    [
      'import_file_name'           => 'Rally Driver',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment', 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/rallydriver/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/rallydriver/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/rallydriver/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-rally-driver-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/rallydriver/',
    ],
    
    [
      'import_file_name'           => 'Marathon',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/marathon/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/marathon/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/marathon/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-marathon-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/marathon/',
    ],
    
    [
      'import_file_name'           => 'Funeral Home',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/funeralhome/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/funeralhome/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/funeralhome/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-funeral-home-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/funeralhome/',
    ],
    
    [
      'import_file_name'           => 'Boutique',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/boutique/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/boutique/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/boutique/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-boutique-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/boutique/',
    ],
    
    [
      'import_file_name'           => 'Boxing',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/boxing/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/boxing/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/boxing/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-boxing-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/boxing/',
    ],
    
    [
      'import_file_name'           => 'Aeroclub',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education', 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/aeroclub/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/aeroclub/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/aeroclub/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-aeroclub-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/aeroclub/',
    ],
    
    [
      'import_file_name'           => 'Renovate',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/renovate/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/renovate/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/renovate/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-renovate-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/renovate/',
    ],
    
    [
      'import_file_name'           => 'Cakes',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cakes/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cakes/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/cakes/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-cakes-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/cakes/',
    ],
    
    [
      'import_file_name'           => 'Taxi',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/taxi/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/taxi/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/taxi/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-taxi-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/taxi/',
    ],
    
    [
      'import_file_name'           => 'Xmas',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment', 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/xmas/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/xmas/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/xmas/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-xmas-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/xmas/',
    ],
    
    [
      'import_file_name'           => 'Language',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/language/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/language/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/language/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2021/12/aesir-language-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/language/',
    ],
    
    [
      'import_file_name'           => 'Pet',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pet/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pet/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pet/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-pet-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/pet/',
    ],
    
    [
      'import_file_name'           => 'Football',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/football/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/football/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/football/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-football-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/football/',
    ],
    
    [
      'import_file_name'           => 'Clothing',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/clothing/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/clothing/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/clothing/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-clothing-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/clothing/',
    ],
    
    [
      'import_file_name'           => 'Birthday',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment', 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/birthday/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/birthday/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/birthday/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-birthday-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/birthday/',
    ],
    
    [
      'import_file_name'           => 'Music School',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/music-school/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/music-school/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/music-school/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-school-music-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/music-school/',
    ],
    
    [
      'import_file_name'           => 'Fast Food',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Food' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fast-food/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fast-food/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fast-food/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-fast-food-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/fast-food/',
    ],
    
    [
      'import_file_name'           => 'Ceramic Store',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/ceramic-store/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/ceramic-store/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/ceramic-store/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-ceramic-store-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/ceramic-store/',
    ],
    
    [
      'import_file_name'           => 'Astrology',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment', 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/astrology/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/astrology/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/astrology/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-astrology-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/astrology/',
    ],
    
    [
      'import_file_name'           => 'Pianist',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education', 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pianist/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pianist/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/pianist/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-pianist-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/pianist/',
    ],
    
    [
      'import_file_name'           => 'Florist',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/florist/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/florist/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/florist/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-florist-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/florist/',
    ],
    
    [
      'import_file_name'           => 'Lingerie',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lingerie/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lingerie/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lingerie/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-lingerie-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/lingerie/',
    ],
    
    [
      'import_file_name'           => 'Food Truck',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/food-truck/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/food-truck/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/food-truck/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-food-truck-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/food-truck/',
    ],
    
    [
      'import_file_name'           => 'Medical Shop',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/medical-shop/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/medical-shop/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/medical-shop/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-medical-shop-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/medical-shop/',
    ],
    
    [
      'import_file_name'           => 'Organic',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/organic/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/organic/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/organic/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-organic-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/organic/',
    ],
    
    [
      'import_file_name'           => 'Glasses',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/glasses/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/glasses/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/glasses/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-glasses-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/glasses/',
    ],
    
    [
      'import_file_name'           => 'Artist',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/artist/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/artist/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/artist/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-artist-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/artist/',
    ],
    
    [
      'import_file_name'           => 'Herbal',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/herbal/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/herbal/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/herbal/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-herbal-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/herbal/',
    ],
    
    [
      'import_file_name'           => 'Makeup',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/makeup/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/makeup/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/makeup/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-makeup-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/makeup/',
    ],
    
    [
      'import_file_name'           => 'Festival',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/festival/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/festival/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/festival/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-festival-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/festival/',
    ],
    
    [
      'import_file_name'           => 'Catering',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Food' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/catering/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/catering/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/catering/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-catering-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/catering/',
    ],
    
    [
      'import_file_name'           => 'Casino',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/casino/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/casino/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/casino/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-casino-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/casino/',
    ],
    
    [
      'import_file_name'           => 'Marketing',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/marketing/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/marketing/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/marketing/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-marketing-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/marketing/',
    ],
    
    [
      'import_file_name'           => 'Underwater',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/underwater/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/underwater/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/underwater/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-underwater-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/underwater/',
    ],
    
    [
      'import_file_name'           => 'Model 2',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Entertainment' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/model-2/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/model-2/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/model-2/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-model-2-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/model-2/',
    ],
    
    [
      'import_file_name'           => 'Charity',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/charity/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/charity/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/charity/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-charity-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/charity/',
    ],
    
    [
      'import_file_name'           => 'Wedding 2',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wedding-2/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wedding-2/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/wedding-2/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-wedding-2-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/wedding-2/',
    ],
    
    [
      'import_file_name'           => 'Horse 2',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/horse-2/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/horse-2/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/horse-2/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-horse-2-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/horse-2/',
    ],
    
    [
      'import_file_name'           => 'Church 2',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Nonprofit' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/church-2/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/church-2/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/church-2/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-church-2-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/church-2/',
    ],
    
    [
      'import_file_name'           => 'Nursing Home 2',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nursinghome-2/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nursinghome-2/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/nursinghome-2/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-nursing-home-2-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/nursinghome-2/',
    ],
    
    [
      'import_file_name'           => 'Language 2',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Education' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/language-2/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/language-2/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/language-2/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-language-2-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/language-2/',
    ],
    
    [
      'import_file_name'           => 'Makeup 2',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/makeup-2/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/makeup-2/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/makeup-2/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-makeup-2-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/makeup-2/',
    ],
    
    [
      'import_file_name'           => 'Lingerie 2',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lingerie-2/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lingerie-2/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/lingerie-2/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-lingerie-2-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/lingerie-2/',
    ],
    
    [
      'import_file_name'           => 'Psychologist',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/psychologist/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/psychologist/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/psychologist/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-psychologist-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/psychologist/',
    ],
    
    [
      'import_file_name'           => 'Minimal Photography',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/minimal-photography/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/minimal-photography/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/minimal-photography/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-minimal-photography-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/minimal-photography/',
    ],
    
    [
      'import_file_name'           => 'Jeweler Showcase',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/jeweler-showcase/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/jeweler-showcase/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/jeweler-showcase/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-jeweler-showcase-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/jeweler-showcase/',
    ],
    
    [
      'import_file_name'           => 'Hairdresser',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hairdresser/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hairdresser/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/hairdresser/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-hairdresser-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/hairdresser/',
    ],
    
    [
      'import_file_name'           => 'Artist Minimal',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Creative / Portfolio' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/artist-minimal/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/artist-minimal/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/artist-minimal/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-artist-minimal-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/artist-minimal/',
    ],
    
    [
      'import_file_name'           => 'Fashion Retail',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Health / Beauty' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fashion-retail/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fashion-retail/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/fashion-retail/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-fashion-retail-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/fashion-retail/',
    ],
    
    [
      'import_file_name'           => 'Rattan Furniture',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/rattan-furniture/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/rattan-furniture/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/rattan-furniture/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-rattan-furniture-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/rattan-furniture/',
    ],
    
    [
      'import_file_name'           => 'Yoga Studio',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Health / Beauty', 'Sports' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/yoga-studio/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/yoga-studio/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/yoga-studio/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-yoga-studio-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/yoga-studio/',
    ],
    
    [
      'import_file_name'           => 'Optician',
      'page_builder'               => 'wpbakery',
      'categories'                  => [ 'Corporate', 'Shop' ],
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/optician/content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/optician/widgets.wie',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/aesir/optician/redux.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://aesir.visualmodo.com/wp-content/uploads/2022/01/aesir-optician-cover-768x576.jpg',
      'preview_url'                => 'https://aesir.visualmodo.com/prebuilt-website/optician/',
    ],
    
  ];
}

elseif ( $theme == 'Architect' ) {
  $architect = [
    [
      'import_file_name'           => 'Architect Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/architect/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/architect/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/architect/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/architect/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/architect/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/architect/',
    ],
  ];
}

elseif ( $theme == 'Beyond' ) {
  $beyond = [
    [
      'import_file_name'           => 'Beyond Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/beyond/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/beyond/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/beyond/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/beyond/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/beyond/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/beyond/',
    ],
  ];
}

elseif ( $theme == 'Cafe' ) {
  $cafe = [
    [
      'import_file_name'           => 'Cafe Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/cafe/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/cafe/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/cafe/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/cafe/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/cafe/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/cafe/',
    ],
  ];
}

elseif ( $theme == 'Church' ) {
  $church = [
    [
      'import_file_name'           => 'Church Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/church/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/church/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/church/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/church/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/church/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/church/',
    ],
  ];
}

elseif ( $theme == 'Construction' ) {
  $construction = [
    [
      'import_file_name'           => 'Construction Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/construction/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/construction/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/construction/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/construction/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/construction/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/construction/',
    ],
  ];
}

elseif ( $theme == 'Cryptocurrency' ) {
  $cryptocurrency = [
    [
      'import_file_name'           => 'Cryptocurrency Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/cryptocurrency/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/cryptocurrency/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/cryptocurrency/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/cryptocurrency/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/cryptocurrency/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/cryptocurrency/',
    ],
  ];
}

elseif ( $theme == 'Dark' ) {
  $dark = [
    [
      'import_file_name'           => 'Dark Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/dark/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/dark/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/dark/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/dark/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/dark/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/dark/',
    ],
  ];
}

elseif ( $theme == 'Edge' ) {
  $edge = [
    [
      'import_file_name'           => 'Edge Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/edge/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/edge/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/edge/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/edge/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/edge/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/edge/',
    ],
  ];
}

elseif ( $theme == 'Education' ) {
  $education = [
    [
      'import_file_name'           => 'Education Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/education/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/education/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/education/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/education/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/education/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/education/',
    ],
  ];
}

elseif ( $theme == 'Employment' ) {
  $employment = [
    [
      'import_file_name'           => 'Employment Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/employment/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/employment/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/employment/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/employment/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/employment/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/employment/',
    ],
  ];
}

elseif ( $theme == 'Financial' ) {
  $financial = [
    [
      'import_file_name'           => 'Financial Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/financial/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/financial/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/financial/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/financial/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/financial/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/financial/',
    ],
  ];
}

elseif ( $theme == 'Fitness' ) {
  $fitness = [
    [
      'import_file_name'           => 'Fitness Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/fitness/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/fitness/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/fitness/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/fitness/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/fitness/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/fitness/',
    ],
  ];
}

elseif ( $theme == 'Food' ) {
  $food = [
    [
      'import_file_name'           => 'Food Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/food/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/food/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/food/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/food/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/food/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/food/',
    ],
  ];
}

elseif ( $theme == 'Forum' ) {
  $forum = [
    [
      'import_file_name'           => 'Forum Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/forum/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/forum/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/forum/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/forum/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/forum/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/forum/',
    ],
  ];
}

elseif ( $theme == 'Gym' ) {
  $gym = [
    [
      'import_file_name'           => 'Gym Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/gym/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/gym/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/gym/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/gym/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/gym/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/gym/',
    ],
  ];
}

elseif ( $theme == 'Hotel' ) {
  $hotel = [
    [
      'import_file_name'           => 'Hotel Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/hotel/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/hotel/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/hotel/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/hotel/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/hotel/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/hotel/',
    ],
  ];
}

elseif ( $theme == 'Ink' ) {
  $ink = [
    [
      'import_file_name'           => 'Ink Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/ink/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/ink/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/ink/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/ink/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/ink/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/ink/',
    ],
  ];
}

elseif ( $theme == 'IT' ) {
  $it = [
    [
      'import_file_name'           => 'IT Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/it/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/it/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/it/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/it/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/it/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/it/',
    ],
  ];
}

elseif ( $theme == 'Marvel' ) {
  $marvel = [
    [
      'import_file_name'           => 'Marvel Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/marvel/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/marvel/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/marvel/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/marvel/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/marvel/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/marvel/',
    ],
  ];
}

elseif ( $theme == 'Mechanic' ) {
  $mechanic = [
    [
      'import_file_name'           => 'Mechanic Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/mechanic/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/mechanic/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/mechanic/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/mechanic/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/mechanic/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/mechanic/',
    ],
  ];
}

elseif ( $theme == 'Medical' ) {
  $medical = [
    [
      'import_file_name'           => 'Medical Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/medical/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/medical/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/medical/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/medical/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/medical/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/medical/',
    ],
  ];
}

elseif ( $theme == 'Minimalist' ) {
  $minimalist = [
    [
      'import_file_name'           => 'Minimalist Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/minimalist/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/minimalist/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/minimalist/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/minimalist/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/minimalist/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/minimalist/',
    ],
  ];
}

elseif ( $theme == 'Music' ) {
  $music = [
    [
      'import_file_name'           => 'Music Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/music/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/music/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/music/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/music/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/music/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/music/',
    ],
  ];
}

elseif ( $theme == 'Nectar' ) {
  $nectar = [
    [
      'import_file_name'           => 'Nectar Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/nectar/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/nectar/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/nectar/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/nectar/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/nectar/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/nectar/',
    ],
  ];
}

elseif ( $theme == 'Nonprofit' ) {
  $nonprofit = [
    [
      'import_file_name'           => 'Nonprofit Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/nonprofit/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/nonprofit/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/nonprofit/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/nonprofit/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/nonprofit/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/nonprofit/',
    ],
  ];
}

elseif ( $theme == 'Peak' ) {
  $peak = [
    [
      'import_file_name'           => 'Peak Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/peak/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/peak/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/peak/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/peak/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/peak/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/peak/',
    ],
  ];
}

elseif ( $theme == 'Petshop' ) {
  $petshop = [
    [
      'import_file_name'           => 'Petshop Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/petshop/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/petshop/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/petshop/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/petshop/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/petshop/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/petshop/',
    ],
  ];
}

elseif ( $theme == 'Photography' ) {
  $photography = [
    [
      'import_file_name'           => 'Photography Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/photography/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/photography/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/photography/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/photography/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/photography/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/photography/',
    ],
  ];
}

elseif ( $theme == 'Politic' ) {
  $politic = [
    [
      'import_file_name'           => 'Politic Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/politic/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/politic/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/politic/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/politic/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/politic/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/politic/',
    ],
  ];
}

elseif ( $theme == 'Rare' ) {
  $rare = [
    [
      'import_file_name'           => 'Rare Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/rare/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/rare/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/rare/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/rare/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/rare/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/rare/',
    ],
  ];
}

elseif ( $theme == 'Realestate' ) {
  $realestate = [
    [
      'import_file_name'           => 'Real Estate Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/realestate/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/realestate/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/realestate/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/realestate/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/realestate/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/realestate/',
    ],
  ];
}

elseif ( $theme == 'Resume' ) {
  $resume = [
    [
      'import_file_name'           => 'Resume Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/resume/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/resume/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/resume/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/resume/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/resume/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/resume/',
    ],
  ];
}

elseif ( $theme == 'Salon' ) {
  $salon = [
    [
      'import_file_name'           => 'Salon Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/salon/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/salon/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/salon/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/salon/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/salon/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/salon/',
    ],
  ];
}

elseif ( $theme == 'Seller' ) {
  $seller = [
    [
      'import_file_name'           => 'Seller Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/seller/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/seller/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/seller/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/seller/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/seller/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/seller/',
    ],
  ];
}

elseif ( $theme == 'Spark' ) {
  $spark = [
    [
      'import_file_name'           => 'Spark Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/spark/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/spark/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/spark/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/spark/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/spark/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/spark/',
    ],
  ];
}

elseif ( $theme == 'Sport' ) {
  $sport = [
    [
      'import_file_name'           => 'Sport Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/sport/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/sport/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/sport/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/sport/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/sport/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/sport/',
    ],
  ];
}

elseif ( $theme == 'Stream' ) {
  $stream = [
    [
      'import_file_name'           => 'Stream Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/stream/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/stream/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/stream/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/stream/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/stream/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/stream/',
    ],
  ];
}

elseif ( $theme == 'Traveler' ) {
  $traveler = [
    [
      'import_file_name'           => 'Traveler Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/traveler/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/traveler/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/traveler/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/traveler/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/traveler/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/traveler/',
    ],
  ];
}

elseif ( $theme == 'Visualmentor' ) {
  $visualmentor = [
    [
      'import_file_name'           => 'Visualmentor Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/visualmentor/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/visualmentor/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/visualmentor/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/visualmentor/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/visualmentor/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/visualmentor/',
    ],
  ];
}

elseif ( $theme == 'Wedding' ) {
  $wedding = [
    [
      'import_file_name'           => 'Wedding Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/wedding/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/wedding/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/wedding/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/wedding/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/wedding/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/wedding/',
    ],
  ];
}

elseif ( $theme == 'Winehouse' ) {
  $winehouse = [
    [
      'import_file_name'           => 'Winehouse Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/winehouse/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/winehouse/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/winehouse/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/winehouse/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/winehouse/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/winehouse/',
    ],
  ];
}

elseif ( $theme == 'Zenith' ) {
  $zenith = [
    [
      'import_file_name'           => 'Zenith Main Demo',
      'page_builder'               => 'wpbakery',
      'import_file_url'            => 'https://cdn.visualmodo.com/library/templates/wpbakery/zenith/demo-content.xml',
      'import_widget_file_url'     => 'https://cdn.visualmodo.com/library/templates/wpbakery/zenith/widgets.wie',
      'import_customizer_file_url' => 'https://cdn.visualmodo.com/library/templates/wpbakery/zenith/customizer.dat',
      'import_redux'               => [
        [
          'file_url'    => 'https://cdn.visualmodo.com/library/templates/wpbakery/zenith/theme-options.json',
          'option_name' => 'vslmd_options',
        ],
      ],
      'import_preview_image_url'   => 'https://cdn.visualmodo.com/library/templates/wpbakery/zenith/cover.jpg',
      'preview_url'                => 'https://theme.visualmodo.com/zenith/',
    ],
  ];
}