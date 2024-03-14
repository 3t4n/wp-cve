<?php
/** @var object $stream */
$id = $stream->id;
if (!isset($stream->filtercolor)) $stream->filtercolor = 'rgb(205, 205, 205)';
$bradius = ( isset( $stream->bradius ) ) ? intval( $stream->bradius ) : 4;
?>
#ff-stream-<?php echo $id;?> .ff-header h1,#ff-stream-<?php echo $id;?> .ff-controls-wrapper > span:hover { color: <?php echo $stream->headingcolor;?>; }
#ff-stream-<?php echo $id;?> .ff-controls-wrapper > span:hover { border-color: <?php echo $stream->headingcolor;?> !important; }
#ff-stream-<?php echo $id;?> .ff-header h2 { color: <?php echo $stream->subheadingcolor;?>; }
#ff-stream-<?php echo $id;?> .ff-loadmore-wrapper .ff-btn:hover {
	background-color: <?php echo $stream->filtercolor;?>;
}
#ff-stream-<?php echo $id;?> .ff-loadmore-wrapper .ff-btn,
#ff-stream-<?php echo $id;?> .ff-square:nth-child(1) {
	background-color: <?php echo $stream->headingcolor;?>;
}
<?php if( isset($stream->hidetext) && $stream->hidetext === 'yep' ):?>
#ff-stream-<?php echo $id;?> .ff-item:not(.ff-ad) .ff-content, #ff-stream-<?php echo $id;?> .ff-item:not(.ff-ad) h4, #ff-stream-<?php echo $id;?> .readmore-js-toggle {
	display: none !important;
}
#ff-stream-<?php echo $id;?> .ff-theme-flat.ff-style-3 .ff-content + .ff-item-meta {
	padding: 7px 0 20px;
}
<?php endif?>
<?php if( isset($stream->hidemeta) && $stream->hidemeta === 'yep' ):?>
#ff-stream-<?php echo $id;?> .ff-item-meta, .ff-theme-flat .ff-icon, .ff-theme-flat.ff-style-3 .ff-item-cont:before {
	display: none !important;
}
#ff-stream-<?php echo $id;?> .ff-theme-flat.ff-style-3 .ff-item-cont {
	padding-bottom: 15px;
}
#ff-stream-<?php echo $id;?> .ff-theme-flat .ff-img-holder + .ff-item-cont,
#ff-stream-<?php echo $id;?> .ff-theme-flat a + .ff-item-cont {
	margin-top: 0;
}
<?php endif?>
#ff-stream-<?php echo $id;?> .ff-layout-justified .ff-item {
display: none !important;
}
<?php if( isset($stream->hidetext) && $stream->hidetext === 'yep' && isset($stream->hidemeta) && $stream->hidemeta === 'yep' ):?>
#ff-stream-<?php echo $id;?> .ff-item-cont > .ff-img-holder:first-child {
	margin-bottom: 0;
}

#ff-stream-<?php echo $id;?> .ff-filter-holder {
display: none !important;
}
#ff-stream-<?php echo $id;?> .ff-theme-flat .ff-item-cont {
	display: none;
}
<?php endif?>
#ff-stream-<?php echo $id;?> .ff-header h1, #ff-stream-<?php echo $id;?> .ff-header h2 {
	text-align: <?php echo $stream->hhalign;?>;
}
#ff-stream-<?php echo $id;?> .ff-controls-wrapper, #ff-stream-<?php echo $id;?> .ff-controls-wrapper > span {
	border-color: <?php echo $stream->filtercolor;?>;
}
#ff-stream-<?php echo $id;?> .ff-controls-wrapper, #ff-stream-<?php echo $id;?> .ff-controls-wrapper > span {
	color: <?php echo $stream->filtercolor;?>;
}

#ff-stream-<?php echo $id;?> .shuffle__sizer {
	margin-left: <?php echo $stream->margin;?>px !important;
}

#ff-stream-<?php echo $id;?> .picture-item__inner {
	background: <?php echo $stream->cardcolor;?>;
	color: <?php echo $stream->textcolor;?>;
	box-shadow: 0 1px 4px 0 <?php echo $stream->shadow;?>;
}

#ff-stream-<?php echo $id;?> .ff-content a {
	color: <?php echo $stream->linkscolor;?>;
}

#ff-stream-<?php echo $id;?> .ff-mob-link {
	background-color: <?php echo $stream->textcolor;?>;
}

#ff-stream-<?php echo $id;?> .ff-mob-link:after {
	color: <?php echo $stream->cardcolor;?>;
}
#ff-stream-<?php echo $id;?> .ff-layout-grid .ff-item {
display: none !important;
}
#ff-stream-<?php echo $id;?> {
	color: <?php echo $stream->textcolor;?>;
}
#ff-stream-<?php echo $id;?> .ff-square {
background: <?php echo ( ( $stream->bgcolor == 'rgb(255, 255, 255)' &&  $stream->cardcolor == 'rgb(255, 255, 255)' ) || strpos( $stream->bgcolor, ', 0)') !== false  ? 'rgb(205, 205, 205)' : $stream->cardcolor ) ;?>;
}
#ff-stream-<?php echo $id;?> .ff-style-2 .ff-icon:after {
	text-shadow: -1px 0 <?php echo $stream->cardcolor;?>, 0 1px <?php echo $stream->cardcolor;?>, 1px 0 <?php echo $stream->cardcolor;?>, 0 -1px <?php echo $stream->cardcolor;?>;
}

#ff-stream-<?php echo $id;?> .ff-item h1, #ff-stream-<?php echo $id;?> .ff-stream-wrapper.ff-infinite .ff-nickname, #ff-stream-<?php echo $id;?> h4,
#ff-stream-<?php echo $id;?> .ff-name {
	color: <?php echo $stream->namecolor;?> !important;
}
#ff-stream-<?php echo $id;?> .ff-layout-carousel .ff-item {
display: none !important;
}
#ff-stream-<?php echo $id;?> .ff-mob-link:hover {
	background-color: <?php echo $stream->namecolor;?>;
}
#ff-stream-<?php echo $id;?> .ff-nickname,
#ff-stream-<?php echo $id;?> .ff-timestamp,
#ff-stream-<?php echo $id;?> .ff-item-bar,
#ff-stream-<?php echo $id;?> .ff-item-bar a {
	color: <?php echo $stream->restcolor;?> !important;
}
#ff-stream-<?php echo $id;?>-slideshow {
	display: none !important;
}
#ff-stream-<?php echo $id;?> .ff-item, #ff-stream-<?php echo $id;?> .ff-stream-wrapper.ff-infinite .ff-content {
	text-align: <?php echo $stream->talign;?>;
}
#ff-stream-<?php echo $id;?> .ff-overlay {
	background-color: <?php echo $stream->bcolor;?>;
}

.ff-upic-round .ff-img-holder.ff-img-loaded {
background-color: <?php echo $stream->bgcolor;?>;
}

.ff-upic-round .picture-item__inner,
.ff-upic-round .picture-item__inner:before {
border-radius: <?php echo $bradius + 2;?>px;
}

.ff-upic-round .ff-img-holder:first-child,
.ff-upic-round .ff-img-holder:first-child img {
border-radius: <?php echo $bradius;?>px <?php echo $bradius;?>px 0 0;
}

.ff-upic-round .ff-has-overlay .ff-img-holder,
.ff-upic-round .ff-has-overlay .ff-overlay,
.ff-upic-round .ff-has-overlay .ff-img-holder img {
border-radius: <?php echo $bradius;?>px !important;
}
#ff-stream-<?php echo $id;?> .ff-layout-list .ff-item {
display: none !important;
}
<?php if(isset($stream->mborder) && $stream->mborder == 'yep'):?>
#ff-stream-<?php echo $id;?> .picture-item__inner {
	border: 1px solid #eee;
}
<?php endif;?>
<?php
  if(!empty($stream->css)) echo stripslashes($stream->css);
?>