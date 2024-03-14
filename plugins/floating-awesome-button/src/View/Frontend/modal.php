<!--Modal-->
<?php foreach ( $fab_to_display as $fab_item ) : ?>
	<?php
        if ( 'link' == $fab_item->getType() ) {
            continue;} // Bypass if fab type is link
	?>
	<div class="fab-container">
		<div
			id="fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?>"
			class="hidden modal" data-title="<?php echo esc_attr( $fab_item->getTitle() ); ?>"
			data-icon="<?php echo ( $fab_item->getIconClass() ) ? esc_attr( $fab_item->getIconClass() ) : 'fas fa-circle'; ?>">
			<div class="fab-modal-content w-full overflow-hidden" data-id="fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?>">
                <?php $fab_item->render(); ?>
			</div>
		</div>
	</div>
    <style>
        .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-content-pane {
            <?php
                $overflow = ($fab_item->getModal()->getLayout()['id']==='overflow') ? 'overflow: visible !important;' : '';
                echo esc_attr($overflow);
            ?>
        }

        .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-content {
            <?php
                /** Background */
                $background = isset($fab_item->getModal()->getLayout()['background']['color']) ? $fab_item->getModal()->getLayout()['background']['color'] : '';
                $background = ($background) ? sprintf('background: %s !important;', $background) : '';
                echo esc_attr($background);
            ?>

            /** Modal Spacing */
            padding: <?php echo esc_attr( $fab_item->getModal()->getSizing('padding') ); ?> !important;
            margin: <?php echo esc_attr( $fab_item->getModal()->getSizing('margin') ); ?> !important;
        }
        .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-box {
            /** Modal Size */
            <?php if ( isset( $fab_item->getSize()['type'] ) && $fab_item->getSize()['type'] === 'custom' ) : ?>
                width: <?php echo esc_attr( $fab_item->getSize()['custom'] ); ?>;
            <?php endif; ?>
        }
        /** Modal Background */
        .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-bg {
            <?php
                /** Background */
                $background = isset($fab_item->getModal()->getLayout()['overlay']['color']) ? $fab_item->getModal()->getLayout()['overlay']['color'] : '';
                $background = ($background) ? sprintf('background: %s !important;', $background) : '';
                echo esc_attr($background);

                /** Opacity */
                $opacity = isset($fab_item->getModal()->getLayout()['overlay']['opacity']) ? $fab_item->getModal()->getLayout()['overlay']['opacity'] : '';
                $opacity = ($opacity) ? sprintf('opacity: %s !important;', $opacity) : '';
                echo esc_attr($opacity);
            ?>
        }

        /** Modal Animation (In) */
        .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-box-container.jconfirm-animation-fabmodalopen {
            transform: translate3d(0,0,0);
            animation: <?php echo isset($fab_item->getAnimation()['modal']['in']) ? esc_attr( $fab_item->getAnimation()['modal']['in'] ) : 'fadeIn'; ?> 1s 1;
        }

        /** Modal Animation (Out) */
        .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-box-container.jconfirm-animation-fabmodalclose {
            transform: translate3d(0,0,0);
            animation: <?php echo isset($fab_item->getAnimation()['modal']['out']) ? esc_attr( $fab_item->getAnimation()['modal']['out'] ) : 'fadeOut'; ?> 1s 1;
        }
    </style>
<?php endforeach; ?>
