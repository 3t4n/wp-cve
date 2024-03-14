<?php

// namespace CWS-GOOGLE-PHOTOS-GALLERY;
namespace App;

use App\GooglePhotoService;
use Google\ApiCore\PagedListResponse;

class Photocontroller 
{
    private $service;
    private $args;  // gpp settings from wp admin

    public function __construct($args=[])
    {
        $this->service = new GooglePhotoService();
        $url = $this->service->createAuthUrl();

        $this->args = $args;
    }

    /**
	 * Start Authorization of plugin.
     * 
     * @return str
	 *
	 * @since    4.0.0
	 */
    public function start()
    {
        $service = new GooglePhotoService();
        $url = $service->createAuthUrl();
        return $url;
    }

     /**
	 * List Users Albums in Google Photos
     * 
     * @return array
	 *
	 * @since    4.0.0
	 */   
    public function listAlbums()
    {
        // Safely Grab pagetoken from url
        $pagetoken = ( isset( $_GET['cws_pagetoken'] ) ) ? sanitize_text_field( $_GET['cws_pagetoken'] ) : '';  

        $token = get_option( 'cws_gpp_token' );
        $accessToken = $token['access_token'];

        $client = $this->service->getPhotosLibraryClient($accessToken);
// var_dump($this->args['num_results']);
        $options = array(
            'pageSize' => $this->args['num_results'],
            'pageToken' => $pagetoken
        );

        // List all albums
        $pagedListResponse = $client->listAlbums($options);
        $data = [];
        $count = 0;
        $page = $pagedListResponse->getPage();
    
        foreach($page->getIterator() as $album){
            $data[$count]['id'] = $album->getId();
            $data[$count]['title'] = $album->getTitle();
            $data[$count]['productUrl'] = $album->getProductUrl();
            $data[$count]['coverPhotoBaseUrl'] = $album->getCoverPhotoBaseUrl();
            $data[$count]['coverPhotoediaItemId'] = $album->getCoverPhotoMediaItemId();
            // $data[$count]['imgUrl'] = $album->getCoverPhotoBaseUrl() . "=w500-h500-c";
            $data[$count]['imgUrl'] = $album->getCoverPhotoBaseUrl() . "=w{$this->args['album_thumb_size']}-h{$this->args['album_thumb_size']}-c";

            $data[$count]['mediaItems'] = $album->getMediaItemsCount();

            $data[$count]['nextPageToken'] = $page->getNextPageToken();
            $count++;
        }

        return $data;
    }

    /**
	 * List Images in Album
     * 
     * @param string $cws_album album id 
     * @return array
	 *
	 * @since    4.0.0
	 */    
    public function listMediaItems($cws_album, $args){

        // Safely Grab pagetoken from url
        $pagetoken = ( isset( $_GET['cws_pagetoken'] ) ) ? sanitize_text_field( $_GET['cws_pagetoken'] ) : '';
        
        // Safely Grab album id from url
        $albumId = ( isset( $_GET['cws_album'] ) ) ? sanitize_text_field( $_GET['cws_album'] ) : '';  

        $token = get_option( 'cws_gpp_token' );
        $accessToken = $token['access_token'];

        $client = $this->service->getPhotosLibraryClient($accessToken);

        $options = array(
            'pageSize' => $this->args['num_results'],
            'pageToken' => "$pagetoken",
            'albumId' => "$albumId"
        );
// var_dump($options);
// die();
        $response = $client->searchMediaItems($options);

        $data = [];
        $count = 0;
        $page = $response->getPage();

        // Decide whether to crop the image
if($this->args['crop'] === true){
    $strCrop='-c';
}
else{
    $strCrop='';
}

        if($this->args['imgmax'] > 800) { $this->args['imgmax'] = 800; }

        foreach($page as $element){
            $data[$count]['id'] = $element->getId();
            $data[$count]['description'] = $element->getDescription();
            $data[$count]['mimeType'] = $element->getMimeType();
            $data[$count]['productUrl'] = $element->getProductUrl();
            $data[$count]['filename'] = pathinfo( $element->getFilename(), PATHINFO_FILENAME);
$data[$count]['imgUrl'] = $element->getBaseUrl() . "=w{$this->args['thumb_size']}-h{$this->args['thumb_size']}{$strCrop}";
            $data[$count]['bigImgUrl'] = $element->getBaseUrl() . "=w{$this->args['imgmax']}-h{$this->args['imgmax']}";

            $data[$count]['bigImgWidth'] = 800;//$width;
            $data[$count]['bigImgHeight'] = 800;//$height;

            
            // var_dump($data[$count]['bigImgWidth']);
            // var_dump($data[$count]['bigImgHeight']);

            $data[$count]['nextPageToken'] = $page->getNextPageToken();

            $count++;
        }
        
        return $data;
    }
}