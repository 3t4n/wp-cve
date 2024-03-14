<?php

$_POST = cg1l_sanitize_post($_POST);

if(!empty($isBackendCall)){

    if(empty($_POST['cgGalleryHash'])){
        echo 0;die;
    }else{

        $galleryHash = $_POST['cgGalleryHash'];
        $galleryHashDecoded = wp_salt( 'auth').'---cngl1---'.$_POST['GalleryID'];
        $galleryHashToCompare = md5($galleryHashDecoded);

        if ($galleryHash != $galleryHashToCompare){
            echo 0;die;
        }

    }

}


global $wpdb;

$tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";

$GalleryID = absint($_POST['GalleryID']);

$wp_upload_dir = wp_upload_dir();


$jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
$fp = fopen($jsonFile, 'r');
$options = json_decode(fread($fp, filesize($jsonFile)),true);
fclose($fp);


$CatWidget = 0;
$ShowCatsUnchecked = 1;
$ShowCatsUnfolded = 1;

if(empty($_POST['ShowCatsUnchecked'])){
    $ShowCatsUnchecked = 0;
}
if(empty($_POST['ShowCatsUnfolded'])){
    $ShowCatsUnfolded = 0;
}

if(!empty($_POST['CatWidget'])){
    $CatWidget = 1;
    $wpdb->update(
        "$tablename_pro_options",
        array('CatWidget' => 1, 'ShowCatsUnchecked' => $ShowCatsUnchecked, 'ShowCatsUnfolded' => $ShowCatsUnfolded),
        array('GalleryID' => $GalleryID),
        array('%d','%d','%d'),
        array('%d')
    );
}
else{
    $wpdb->update(
        "$tablename_pro_options",
        array('CatWidget' => 0, 'ShowCatsUnchecked' => $ShowCatsUnchecked, 'ShowCatsUnfolded' => $ShowCatsUnfolded),
        array('GalleryID' => $GalleryID),
        array('%d','%d','%d'),
        array('%d')
    );
}

$ShowOther = 0;
if(!empty($_POST['Category'])){

    $wpdb->update(
        "$tablename_categories",
        array('Active' => 0),
        array('GalleryID' => $GalleryID),
        array('%s'),
        array('%s')
    );
    //  var_dump($_POST['Category']);
    foreach($_POST['Category'] as $key => $value){
        //   var_dump(is_string ($key));
        //    var_dump($key!=0);
        //   var_dump($value);
        //   var_dump($value);
        if($key=='Continue' && is_string($key)) {
            continue;
        }

        if($key=='ShowOther' && is_string($key)){
            // var_dump(4444444);

            $wpdb->update(
                "$tablename_pro_options",
                array('ShowOther' => 1),
                array('GalleryID' => $GalleryID),
                array('%d'),
                array('%s')
            );
            $ShowOther = 1;

        }
        else{
            $wpdb->update(
                "$tablename_categories",
                array('Active' => 1),
                array('id' => $value),
                array('%s'),
                array('%s')
            );

        }

    }

    if(empty($_POST['Category']['ShowOther'])){
        // var_dump(11111111111);
        $wpdb->update(
            "$tablename_pro_options",
            array('ShowOther' => 0),
            array('GalleryID' => $GalleryID),
            array('%d'),
            array('%s')
        );
    }

    // make json file

    $categories = $wpdb->get_results("SELECT * FROM $tablename_categories WHERE GalleryID = '$GalleryID' ORDER BY Field_Order");

    $categoriesFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-categories.json';

    $categoriesArray = array();

    foreach($categories as $category){

        $categoriesArray[$category->id] = $category;

    }

    $fp = fopen($categoriesFile, 'w');
    fwrite($fp, json_encode($categoriesArray));
    fclose($fp);

}


// Set table names --- END

if(!empty($options[$GalleryID])){
    $options[$GalleryID]['pro']['ShowOther'] = $ShowOther;
    $options[$GalleryID]['pro']['CatWidget'] = $CatWidget;
    $options[$GalleryID]['pro']['ShowCatsUnchecked'] = $ShowCatsUnchecked;
    $options[$GalleryID]['pro']['ShowCatsUnfolded'] = $ShowCatsUnfolded;
    $options[$GalleryID.'-u']['pro']['ShowOther'] = $ShowOther;
    $options[$GalleryID.'-u']['pro']['CatWidget'] = $CatWidget;
    $options[$GalleryID.'-u']['pro']['ShowCatsUnchecked'] = $ShowCatsUnchecked;
    $options[$GalleryID.'-u']['pro']['ShowCatsUnfolded'] = $ShowCatsUnfolded;
    $options[$GalleryID.'-nv']['pro']['ShowOther'] = $ShowOther;
    $options[$GalleryID.'-nv']['pro']['CatWidget'] = $CatWidget;
    $options[$GalleryID.'-nv']['pro']['ShowCatsUnchecked'] = $ShowCatsUnchecked;
    $options[$GalleryID.'-nv']['pro']['ShowCatsUnfolded'] = $ShowCatsUnfolded;
    $options[$GalleryID.'-w']['pro']['ShowOther'] = $ShowOther;
    $options[$GalleryID.'-w']['pro']['CatWidget'] = $CatWidget;
    $options[$GalleryID.'-w']['pro']['ShowCatsUnchecked'] = $ShowCatsUnchecked;
    $options[$GalleryID.'-w']['pro']['ShowCatsUnfolded'] = $ShowCatsUnfolded;
}else{
    $options['pro']['ShowOther'] = $ShowOther;
    $options['pro']['CatWidget'] = $CatWidget;
    $options['pro']['ShowCatsUnchecked'] = $ShowCatsUnchecked;
    $options['pro']['ShowCatsUnfolded'] = $ShowCatsUnfolded;
}

// set image data, das ganze gesammelte
$jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
$fp = fopen($jsonFile, 'w');
fwrite($fp, json_encode($options));
fclose($fp);