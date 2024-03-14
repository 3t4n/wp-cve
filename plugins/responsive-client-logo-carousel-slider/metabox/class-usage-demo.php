<?php
//include the main class file
require_once("meta-box-class/my-meta-box-class.php");
if (is_admin()){
  /* 
   * prefix of meta keys, optional
   * use underscore (_) at the beginning to make keys hidden, for example $prefix = '_ba_';
   *  you also can make prefix empty to disable it
   * 
   */
  $prefix = 'ba_';
  /* 
   * configure your meta box
   */
  $config = array(
    'id'             => 'demo_meta_box',          // meta box id, unique per meta box
    'title'          => 'Simple Meta Box fields',          // meta box title
    'pages'          => array(''),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $my_meta =  new AT_Meta_Box($config);
  
  /*
   * Add fields to your meta box
   */
  
  //text field
  $my_meta->addText($prefix.'text_field_id',array('name'=> 'My Text '));
  //textarea field
  $my_meta->addTextarea($prefix.'textarea_field_id',array('name'=> 'My Textarea '));
  //checkbox field
  $my_meta->addCheckbox($prefix.'checkbox_field_id',array('name'=> 'My Checkbox '));
  //select field
  $my_meta->addSelect($prefix.'select_field_id',array('selectkey1'=>'Select Value1','selectkey2'=>'Select Value2'),array('name'=> 'My select ', 'std'=> array('selectkey2')));
  //radio field
  $my_meta->addRadio($prefix.'radio_field_id',array('radiokey1'=>'Radio Value1','radiokey2'=>'Radio Value2'),array('name'=> 'My Radio Filed', 'std'=> array('radionkey2')));
  //Image field
  $my_meta->addImage($prefix.'image_field_id',array('name'=> 'My Image '));
  //file upload field
  $my_meta->addFile($prefix.'file_field_id',array('name'=> 'My File'));
  //file upload field with type limitation
  $my_meta->addFile($prefix.'file_pdf_field_id',array('name'=> 'My File limited to PDF Only','ext' =>'pdf','mime_type' => 'application/pdf'));
  /*
   * Don't Forget to Close up the meta box Declaration 
   */
  //Finish Meta Box Declaration 
  $my_meta->Finish();

  /**
   * Create a second metabox
   */
  /* 
   * configure your meta box
   */
  $config2 = array(
    'id'             => 'demo_meta_box2',          // meta box id, unique per meta box
    'title'          => 'Carousel Setup..',          // meta box title
    'pages'          => array('scrollingcarousel'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your 2nd meta box
   */
  $my_meta2 =  new AT_Meta_Box($config2);
  
  /*
   * Add fields to your 2nd meta box
   */
  //add checkboxes list 

 $my_meta->addImage($prefix.'image_field_id',array('name'=> 'My Image '));
    
  /*
   * To Create a reapeater Block first create an array of fields
   * use the same functions as above but add true as a last param
   */
  $repeater_fields[] = $my_meta2->addImage($prefix.'image_field_id',array('name'=> 'Image'),true);

  /*
   * Then just add the fields to the repeater block
   */
  //repeater block
  $my_meta2->addRepeaterBlock($prefix.'re_',array(
    'inline'   => false, 
    'name'     => 'Click On + To add logo / image',
    'fields'   => $repeater_fields, 
    'sortable' => true
  ));


	$my_meta2->addNumber($prefix.'width',array('name'=> 'Image Width','desc' =>'Only numerical value. Leave 0 if you want the actual width of the image'));  
	$my_meta2->addNumber($prefix.'height',array('name'=> 'Image Height','desc' =>'Only numerical value. Leave 0 if you want the actual height of the image. '));  	
	
	$my_meta2->addNumber($prefix.'speed',array('name'=> 'Carousel Speed','std'=>'5','desc' =>'Default value 5. Change the speed of the carousel.'));  
	
	$my_meta2->addRadio($prefix.'behavior',array('pause'=>'Pause','cursor driven'=>'Cursor Driven'),array('name'=> 'Mouseover Behavior', 'std'=> array('cursor driven')));
	
    $my_meta2->addCheckbox($prefix.'mouse_direction',array('name'=> 'Save Direction','desc' =>'Check if you want direction will be changed with mouse pointer provided direction.'));
	
     //text field
    $my_meta2->addNumber($prefix.'padding',array('name'=> 'Padding','desc' =>'Only Numarical value ! change the distance between two image.'));

	$my_meta2->addNumber($prefix.'boarder_size',array('name'=> 'Border size','desc' =>'Example: enter 3 if you want a 3 pixel border for each image item. '));  
	
    $my_meta2->addColor($prefix.'boarder_color',array('name'=> 'Border Color','desc' =>'Choos the border color for image item'));	
	
    $my_meta2->addColor($prefix.'bg_color',array('name'=> 'Background Color ','desc' =>'Change background color of the carousel area'));
 
  //Finish Meta Box Declaration 
  $my_meta2->Finish();
}