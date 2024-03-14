<?php

$secFieldsArray = array();
// 1. Feldtitel
$secFieldsArray['titel']="Category";

// 5. Felderfordernis + Eingabe in die Datenbank
$secFieldsArray['mandatory']="off";

$secFieldsArray = serialize($secFieldsArray);

// Zuerst Form Input kreiren
$wpdb->query( $wpdb->prepare(
    "
							INSERT INTO $tablename_form_input
							( id, GalleryID, Field_Type, Field_Order, Field_Content,Show_Slider,Use_as_URL,Active)
							VALUES ( %s,%d,%s,%d,%s,%d,%d,%d )
						",
    '',$nextIDgallery,'selectc-f',1,$secFieldsArray,1,0,1
) );

// create Categories

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'People',1,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Nature',2,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Food',3,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Architecture',4,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Animals',5,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Lost Places',6,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Machines',7,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Macro',8,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Monochrome',9,1
) );

$wpdb->query( $wpdb->prepare(
    "
                      INSERT INTO $tablenameCategories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
    '',$nextIDgallery,'Landscape',10,1
) );


// make json file

$categories = $wpdb->get_results("SELECT * FROM $tablenameCategories WHERE GalleryID = '$nextIDgallery' ORDER BY Field_Order");

$categoriesArray = array();

foreach($categories as $category){

    $categoriesArray[$category->id] = $category;

}

$fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-categories.json', 'w');
fwrite($fp, json_encode($categoriesArray));
fclose($fp);

// create Categories --- END