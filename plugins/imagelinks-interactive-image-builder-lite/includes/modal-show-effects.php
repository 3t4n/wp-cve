<?php
defined('ABSPATH') || exit;
?>
<div id="imagelinks-modal-{{ modalData.id }}" class="imagelinks-modal" tabindex="-1">
	<div class="imagelinks-modal-dialog">
		<div class="imagelinks-modal-header">
			<div class="imagelinks-modal-close" al-on.click="modalData.deferred.resolve('close');">&times;</div>
			<div class="imagelinks-modal-title"><?php esc_html_e('Select a show effect', 'imagelinks'); ?></div>
		</div>
		<div class="imagelinks-modal-data">
			<div class="imagelinks-modal-effects">
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">General</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounce">Bounce</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-pulse">Pulse</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rubberBand">Rubber Band</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-shake">Shake</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-headShake">Head Shake</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-swing">Swing</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tada">Tada</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-wobble">Wobble</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-jello">Jello</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Bounce</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceIn">BounceIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceInDown">BounceInDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceInLeft">BounceInLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceInRight">BounceInRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceInUp">BounceInUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Fade</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeIn">FadeIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeInDown">FadeInDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeInLeft">FadeInLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeInRight">FadeInRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeInUp">FadeInUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Rotate</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateIn">RotateIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateInDownLeft">RotateInDownLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateInDownRight">RotateInDownRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateInUpLeft">RotateInUpLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateInUpRight">RotateInUpRight</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Zoom</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomIn">ZoomIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomInDown">ZoomInDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomInLeft">ZoomInLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomInRight">ZoomInRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomInUp">ZoomInUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Slide</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-slideInDown">SlideInDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-slideInLeft">SlideInLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-slideInRight">SlideInRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-slideInUp">SlideInUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Perspective</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-perspectiveInDown">PerspectiveInDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-perspectiveInLeft">PerspectiveInLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-perspectiveInRight">PerspectiveInRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-perspectiveInUp">PerspectiveInUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Tin</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tinInDown">TinInDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tinInLeft">TinInLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tinInRight">TinInRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tinInUp">TinInUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Space</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-spaceInDown">SpaceInDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-spaceInLeft">SpaceInLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-spaceInRight">SpaceInRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-spaceInUp">SpaceInUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Flip</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-flip">Flip</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-flipInX">FlipInX</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-flipInY">FlipInY</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Advanced</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-lightSpeedIn">LightSpeedIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rollIn">RollIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-vanishIn">VanishIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-swashIn">SwashIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-foolishIn">FoolishIn</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-holeIn">HoleIn</div>
				</div>
			</div>
			</div>
		</div>
		<div class="imagelinks-modal-footer">
			<div class="imagelinks-modal-text"><?php esc_html_e('Selected effect:', 'imagelinks'); ?> <b>{{modalData.selectedClass}}</b></div>
			<div class="imagelinks-modal-btn imagelinks-modal-btn-close" al-on.click="modalData.deferred.resolve('close');"><?php esc_html_e('Close', 'imagelinks'); ?></div>
			<div class="imagelinks-modal-btn imagelinks-modal-btn-create" al-on.click="modalData.deferred.resolve(true);"><?php esc_html_e('OK', 'imagelinks'); ?></div>
		</div>
	</div>
</div>