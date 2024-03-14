<?php 
    global $post; 
    if( !empty(  get_post_meta( $post->ID, 'timelines-data' )  ) ) {
        // print_r();
        $timelines = unserialize( get_post_meta($post->ID, 'timelines-data', true ) );

    } else {
        $timelines = '';
    }

    $twpData = [];

    if ( ! empty ( $timelines ) ) {
        // $i = count($timelines) ;
        foreach( $timelines as $index => $Value ) {
            // print_r($Value);
            array_push( $twpData, array(
                    'twp_index' 		=> $index,
                    'twp_title' 		=> $Value[ 'twp_title' ],
                    'twp_date' 			=> $Value[ 'twp_date' ],
                    'icon_type' 		=> $Value[ 'icon_type' ],
                    'twp_icon' 			=> $Value[ 'twp_icon' ],
                    'twp_image' 		=> $Value[ 'twp_image' ] !== '0' ? $Value[ 'twp_image' ] : '0' ,
                    'twp_image_url' 	=> $Value[ 'twp_image' ] !== '0' ? wp_get_attachment_image_src($Value[ 'twp_image' ], 'twp-icon')[0] : TIMELINE_WP_IMAGES . 'timeline-default.png' ,
                    'twp_description' 	=> $Value[ 'twp_description' ],
            ));
            // echo $Value[ 'twp_image' ];
            // print_r( 
                
            // );
        }

    } else {

		for ( $i = 0; $i < 3 ; $i++ ) {

            array_push( 
                $twpData,  
                array( 
                    'twp_index' 		=> $i ,
                    'twp_title' 		=> 'Timeline '. $i . ' Heading' ,
                    'twp_date' 			=> date('Y') ,
                    'icon_type' 		=> 'icon' ,
                    'twp_icon' 			=> 'fas fa-home' ,
                    'twp_image' 		=> 0 ,                
                    'twp_image_url' 	=> TIMELINE_WP_IMAGES . 'timeline-default.png',
                    'twp_description' 	=> 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ab repellendus, eius ad veniam facilis obcaecati optio porro explicabo. Dolorem doloremque neque ad omnis nesciunt esse earum minus illum atque iusto.' ,
                ) 
            );
		}

    }
?>


<form>
    <div class="col-9">

        <div class="row">
            <div class="timeline-container container w-100">
                <div class="twp-content" id="TimelineTabContent" data-items="25">

                    <?php foreach ( $twpData as $i => $timeline) { ?> 

                        <div class="tab-pane twp-content-item fade <?php echo ( $i === 0 ) ? ' show active' : ''  ; ?>" data-id="<?php echo $i ; ?>" id="timeline-<?php echo $i ; ?>" role="tabpanel" aria-labelledby="timeline-<?php echo $i ; ?>-tab">
                            <div class="row">

                                <div class="col-12 col-sm-4 section-one">

                                    <div class="form-group">
                                        <label for="twp-title">Title : </label>
                                        <input type="text" class="form-control twp-title" id="twp-title-<?php echo $i ; ?>" name="twp_title[]" placeholder="Timeline Title" onkeyup="setTitle(this)" value="<?php echo esc_attr( $timeline['twp_title'] ); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="twp-date">Date : </label>
                                        <input type="text" class="form-control" id="twp-date-<?php echo $i ; ?>" name="twp_date[]" placeholder="Timeline Date" value="<?php echo esc_attr( $timeline['twp_date'] ); ?>" >
                                    </div>

                                    <div class="form-group twp-type">
                                        <label for="iconType">Icon Type : </label>
                                        <select class="form-control selIconType" id="iconType-<?php echo $i ; ?>" name="icon_type[]" onchange="selIconType(this)">
                                            <option value="icon" <?php echo esc_attr( $timeline['icon_type'] === 'icon' ? 'selected' : '' ); ?> >Icon</option>
                                            <option value="image" <?php echo esc_attr( $timeline['icon_type'] === 'image' ? 'selected' : '' ); ?> >Image</option>
                                        </select>
                                    </div>

                                    <div class="form-group iconTypeBlock twp-type-icon" style="<?php echo ( $timeline['icon_type'] === 'icon' ) ? esc_attr('display: block;') : '' ; ?>">
                                        <label for="twp-icon">Select Icon : </label>
                                        <button class="btn bt-bg-transparent iconBtn" id="twp-icon-<?php echo $i ; ?>" name="twp_icon[]" data-footer="false" data-iconset="fontawesome5"  data-icon="<?php echo esc_attr( $timeline['twp_icon'] ); ?>" data-cols="6" data-rows="6" role="iconpicker"></button>
                                    </div>

                                    <div class="form-group iconTypeBlock twp-type-image" style="<?php echo ( $timeline['icon_type'] === 'image' ) ? esc_attr('display: block;') : '' ; ?>">
                                        <label for="twp-image">Select Image : </label>
                                        <div class="image-preview">
                                            <img data-img-id="<?php echo $i ; ?>" class="imgBtn" src="<?php echo esc_url( $timeline['twp_image_url'] ); ?>" alt="Default">
                                            <input type="hidden" class="form-control" id="twp-image-<?php echo $i ; ?>" name="twp_image[]" value="<?php echo esc_attr( $timeline['twp_image'] ); ?>" placeholder="Timeline Image">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-sm-8 section-two">
                                    <div class="form-group twpDescription">                                    
                                        <div class="form-group twpDescription">
                                            <label for="twpDescription">Timeline Description : </label>
                                            <textarea name="twp_description[]" class="form-control twpDescription-control" id="twp-description-<?php echo $i ; ?>" rows="80"><?php echo  $timeline['twp_description']; ?></textarea>
                                            <?php
                                                // $editor_settings = array('wpautop' => false,'textarea_name'=>'twp_description[]','editor_class' => 'form-control twpDescription-control' );
                                                // $editor_id = 'twp-description-'.$i;
                                                // wp_editor( 'Timeline DescriptionContent', $editor_id,$editor_settings);
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php } ?>
       
                </div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="row">
            <div class="timeline-list container w-100">
                <ul class="nav nav-pills w-100 ui-sortable twp-lists" id="twp-lists" role="tablist" data-items="25" >

                    <?php foreach ( $twpData as $i => $timeline) { ?> 
                        <li id="timeline-tab-<?php echo $i ; ?>" class="nav-item twp-list-item" data-order="<?php echo $i ; ?>">
                            <input type="hidden" name="order[]" class="itemOrder" value="<?php echo $i ?>" >                            
                            <a class="nav-link  <?php echo ( $i === 0 ) ? ' active' : ''  ; ?>"  data-toggle="pill" href="#timeline-<?php echo $i ; ?>" role="tab" aria-controls="timeline-<?php echo $i ; ?>" aria-selected="<?php echo ( $i === 0 ) ? 'true' : ''  ; ?>">
                                <div class="drag-item">
                                    <i class="fas fa-bars"></i>
                                </div>
                                <div class="heading">
                                    <?php echo esc_attr( $timeline['twp_title'] ); ?>
                                </div>
                                <div class="del-item" data-nav="#timeline-tab-<?php echo $i ; ?>" data-content="#timeline-<?php echo $i ; ?>" onclick="del_item(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </div>
                            </a>
                        </li>
                    <?php } ?>

                </ul>
            </div>
            <div class="timeline-list-add container w-100">
                <button id="twp-add-timeline" type="button" class="btn btn-new btn-popup" data-original-title="" title="">
                    <i class="dashicons dashicons-plus-alt"></i> Add New Timeline
                </button>

                <div class="popup-box hide">
                    <div class="popover-container">
                        <div class="arrow"></div>
                        <form id="popForm" class="addForm" method="get">
                            <input type="text" name="name" id="newTimelineTitle" data-items="23" class="form-control form-control-custom input-md" placeholder="Timeline Name">
                            <hr>
                            <a class="btn btn-success newTimelineTitleBtn" onclick="newTimelineTitleBtn(this)"> - ADD - </a>
                        </form>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
</form>

<div id="append-block" class="hide" data-last="25">
</div>


<script>
jQuery( document ).ready( function( $ ) {     
		
});
</script>


<script>
var TimlineCount = <?php echo esc_attr( count( $twpData) ); ?>;
var tabContent = '';
var tabNav = '';
var imgUrlDefault = "<?php echo esc_url( TIMELINE_WP_IMAGES . 'timeline-default.png' ); ?>";


jQuery( document ).ready( function( $ ) {      
    	
	let option = $('.selIconType').val();
    $('#twp-lists').sortable({
		revert: true,
		handle: 'a' ,
		placeholder: "ui-state-highlight",
		stop: function( event, ui ) { reArrangeOrder( event, ui ) },		
    });
    
	// $('.selIconType').parents('.section-one' ).children( ".twp-type-" + option ).show();

    $('.iconBtn').on('change', function(e) {
    });

    $('.imgBtn').on('click', function( e ) {
        selImage(this);
    });

    $('.btn-popup').on('click', function( e ) {
        var block = $(this);
        $(block).next('.popup-box').toggleClass('show');
    });


    $('#twp-lists').on('sortstop', function( e ) {
    });
});


function reArrangeOrder ( event, ui ) {
    var $ = jQuery;
    let listArray = [];
    $( "#twp-lists li" ).each(function( index ) {
        listArray[index] = $(this).children('a').attr('href') ;
    });
    let length = listArray.length;
    length--;
    $( listArray ).each(function( index ) {
        if( index < length ) {
            $("#TimelineTabContent " + listArray[ index ] ).after($("#TimelineTabContent " + listArray[ index + 1 ] ));
        }
    });
}

function newTimelineTitleBtn ( e ) {	
	var $ = jQuery;
	// e.preventDefault(); 
	
	$('#append-block').html(' ');	
	let id = TimlineCount;
	let name = $('#newTimelineTitle').val();
    
    $('#TimelineTabContent').append( getTabContent( id , name, imgUrlDefault ) ) ;
    $('#twp-lists').append( getTabNav( id , name ) ) ;   
    TimlineCount++;   
    $('#newTimelineTitle').val('');
    $('.popup-box').toggleClass('show');	
    $('.iconBtn').iconpicker();
    return;
}

function selIconType ( e ) {
	var $ = jQuery;
	let option = $(e).val();
	$(e).parents('.section-one' ).children( ".iconTypeBlock" ).hide();
	$(e).parents('.section-one' ).children( ".twp-type-" + option ).show();
};

function del_item( e ) {		
	var $ = jQuery;
	var block = $(e);
	if ( confirm('Are you sure you want to delete this ?') ) { 
		$(block.data('content')).remove();
		$(block.data('nav')).remove();
	}
}
function setTitle( e ) { 
	var $ = jQuery;
	let TargetNav = 'timeline-tab-' + $(e).parents('.twp-content-item').data('id');
	$( '#'+ TargetNav + ' .heading').text( $( e ).val() ) 
}


// Bind Events 

function selImage( e ){
	var $ = jQuery;
	var img = $(e);
	var input = $(img).next('input');

	media_uploader = wp.media({
		frame	:    	"post", 
		state	:    	"insert", 
		size	: 		"thumbnail",
		library	: { 
		  type	: 'image' // limits the frame to show only images
		   },
		multiple: false
	});

	media_uploader.on("insert", function(){
		var json = media_uploader.state().get("selection").first().toJSON();
	
		var image_url = json.url;
		var id = json.id;
			
		img.attr( 'alt', json.caption );
		img.attr( 'src', image_url );
		img.data( 'img-id', id );
		input.val(id);

		media_uploader.remove();
	});
	media_uploader.open();
} 

function getTabContent( $id ,$title ,$imgurl ) {
    var $desc = 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ab repellendus, eius ad veniam facilis obcaecati optio porro explicabo. Dolorem doloremque neque ad omnis nesciunt esse earum minus illum atque iusto.';
    var xyz = 
        '<div class="tab-pane twp-content-item fade " data-id="'+ $id +'" id="timeline-'+ $id +'" role="tabpanel" aria-labelledby="timeline-'+ $id +'-tab">' +
            '<div class="row">'+
                '<div class="col-12 col-sm-4 section-one">'+

                    '<div class="form-group">'+
                        '<label for="twp-title">Title : </label>'+
                        '<input type="text" class="form-control twp-title" id="twp-title-'+ $id +'" name="twp_title[]" placeholder="Timeline Title" onkeyup="setTitle(this)" value="'+ $title +'">'+
                    '</div>'+

                    '<div class="form-group">'+
                        '<label for="twp-date">Date : </label>'+
                        '<input type="text" class="form-control" id="twp-date-'+ $id +'" name="twp_date[]" placeholder="Timeline Date" value="<?php echo esc_attr( date('Y') ); ?>" >'+
                    '</div>'+

                    '<div class="form-group twp-type">'+
                        '<label for="iconType">Icon Type : </label>'+
                        '<select class="form-control selIconType" id="iconType-'+ $id +'" name="icon_type[]" onchange="selIconType(this)">'+
                            '<option value="icon" selected >Icon</option>'+
                            '<option value="image" >Image</option>'+
                        '</select>'+
                    '</div>'+

                    '<div class="form-group iconTypeBlock twp-type-icon" style="display: block;">'+
                        '<label for="twp-icon">Select Icon : </label>'+
                        '<button class="btn bt-bg-transparent iconBtn" id="twp-icon-'+ $id +'" name="twp_icon[]" data-footer="false" data-iconset="fontawesome5"  data-icon="fas fa-home" data-cols="6" data-rows="6" role="iconpicker"></button>'+
                    '</div>'+

                    '<div class="form-group iconTypeBlock twp-type-image">'+
                        '<label for="twp-image">Select Image : </label>'+
                        '<div class="image-preview">'+
                            '<img data-img-id="'+ $id +'" class="imgBtn" src="'+ $imgurl +'" alt="Default" onclick="selImage(this)">'+
                            '<input type="hidden" class="form-control" id="twp-image-'+ $id +'" name="twp_image[]" value="0" placeholder="Timeline Image">'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-12 col-sm-8 section-two">'+
                    '<div class="form-group twpDescription">'+
                        '<div class="form-group twpDescription">'+
                            '<label for="twpDescription">Timeline Description : </label>'+
                            '<textarea name="twp_description[]" class="form-control twpDescription-control" id="twp-description-'+ $id +'" cols="30" rows="10">'+ $desc +'</textarea>'+
                        '</div>'+
                    '</div>'+
                '</div>'+

            '</div>'+
        '</div>';

    return xyz;
}

function getTabNav( $id ,$title ) {

    var listItem = 
        '<li id="timeline-tab-'+ $id +'" class="nav-item twp-list-item" data-order="'+ $id +'">'+
            '<input type="hidden" name="order[]" class="itemOrder" value="'+ $id +'" >'+
            '<a class="nav-link"  data-toggle="pill" href="#timeline-'+ $id +'" role="tab" aria-controls="timeline-'+ $id +'" aria-selected="">'+
                '<div class="drag-item">'+
                    '<i class="fas fa-bars"></i>'+
                '</div>'+
                '<div class="heading">'+
                $title +
                '</div>'+
                '<div class="del-item" data-nav="#timeline-tab-'+ $id +'" data-content="#timeline-'+ $id +'" onclick="del_item(this)">'+
                    '<i class="fas fa-trash-alt"></i>'+
                '</div>'+
            '</a>'+
        '</li>';
    return listItem;
}
</script>