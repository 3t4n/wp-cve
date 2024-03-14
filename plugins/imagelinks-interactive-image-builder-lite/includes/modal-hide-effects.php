<?php
defined('ABSPATH') || exit;
?>
<div id="imagelinks-modal-{{ modalData.id }}" class="imagelinks-modal" tabindex="-1">
	<div class="imagelinks-modal-dialog">
		<div class="imagelinks-modal-header">
			<div class="imagelinks-modal-close" al-on.click="modalData.deferred.resolve('close');">&times;</div>
			<div class="imagelinks-modal-title"><?php esc_html_e('Select a hide effect', 'imagelinks'); ?></div>
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
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-hinge">Hinge</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Bounce</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceOut">BounceOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceOutDown">BounceOutDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceOutLeft">BounceOutLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceOutRight">BounceOutRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bounceOutUp">BounceOutUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Fade</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeOut">fadeOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeOutDown">fadeOutDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeOutLeft">fadeOutLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeOutRight">fadeOutRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-fadeOutUp">fadeOutUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Rotate</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateOut">rotateOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateOutDownLeft">rotateOutDownLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateOutDownRight">rotateOutDownRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateOutUpLeft">rotateOutUpLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rotateOutUpRight">rotateOutUpRight</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Zoom</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomOut">zoomOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomOutDown">zoomOutDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomOutLeft">zoomOutLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomOutRight">zoomOutRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-zoomOutUp">zoomOutUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Slide</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-slideOutDown">slideOutDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-slideOutLeft">slideOutLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-slideOutRight">slideOutRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-slideOutUp">slideOutUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Perspective</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-perspectiveOutDown">perspectiveOutDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-perspectiveOutLeft">perspectiveOutLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-perspectiveOutRight">perspectiveOutRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-perspectiveOutUp">perspectiveOutUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Tin</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tinOutDown">tinOutDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tinOutLeft">tinOutLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tinOutRight">tinOutRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-tinOutUp">tinOutUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Space</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-spaceOutDown">spaceOutDown</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-spaceOutLeft">spaceOutLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-spaceOutRight">spaceOutRight</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-spaceOutUp">spaceOutUp</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Flip</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-flip">Flip</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-flipOutX">flipOutX</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-flipOutY">flipOutY</div>
				</div>
			</div>
			
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-title">Advanced</div>
				<div class="imagelinks-modal-btn-group">
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-lightSpeedOut">LightSpeedOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-rollOut">RollOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-vanishOut">VanishOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-swashOut">SwashOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-foolishOut">FoolishOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-holeOut">HoleOut</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bombOutLeft">BombOutLeft</div>
					<div class="imagelinks-modal-effect" data-fx-name="imgl-fx-bombOutRight">BombOutRight</div>
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