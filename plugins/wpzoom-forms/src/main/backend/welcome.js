import { Guide } from '@wordpress/components';
import { withSelect, dispatch, useDispatch } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { PluginMoreMenuItem } from '@wordpress/edit-post';
import { store as preferencesStore } from '@wordpress/preferences';
import { __ } from '@wordpress/i18n';

function WelcomeGuide( props ) {
	const { toggle } = useDispatch( preferencesStore );

	useEffect( () => {
		dispatch( preferencesStore ).setDefaults( 'wpzoom-forms', { welcomeGuide: true } );
	}, [] );

	return <>
		<PluginMoreMenuItem
			onClick={ () => toggle( 'wpzoom-forms', 'welcomeGuide' ) }
		>
			{ __( 'WPZOOM Forms Welcome Guide', 'wpzoom-forms' ) }
		</PluginMoreMenuItem>

		{ props.isActive && <Guide
            className="edit-post-welcome-guide"
			onFinish={ () => toggle( 'wpzoom-forms', 'welcomeGuide' ) }
			pages={ [
				{
					image: (
                        <img width="312" src={require('../images/step-1.png')} />
					),
					content: (
						<div className="wpzoom-forms-welcome-guide-page">
							<h2 class="edit-post-welcome-guide__heading">{ __( 'Getting started with WPZOOM Forms', 'wpzoom-forms' ) }</h2>
							<p class="edit-post-welcome-guide__text">
								{ __( 'With WPZOOM Forms, you can quickly create forms in WordPress thanks to the block editor.', 'wpzoom-forms' ) }
							</p>
						</div>
					),
				},
                {
                    image: (
                        <img width="312" src={require('../images/step-2.png')} />
                    ),
                    content: (
                        <div className="wpzoom-forms-welcome-guide-page">
                            <h2 class="edit-post-welcome-guide__heading">{ __( 'Drag & Drop Fields', 'wpzoom-forms' ) }</h2>
                            <p class="edit-post-welcome-guide__text">
                                { __( 'No coding knowledge or learning is required. Just edit your form and its fields visually as you want. Remove fields or add new ones using the block editor.', 'wpzoom-forms' ) }
                            </p>
                        </div>
                    ),
                },
                {
                    image: (
                        <img width="312" src={require('../images/step-3.png')} />
                    ),
                    content: (
                        <div className="wpzoom-forms-welcome-guide-page">
                            <h2 class="edit-post-welcome-guide__heading">{ __( 'Display your forms anywhere you want', 'wpzoom-forms' ) }</h2>
                            <p class="edit-post-welcome-guide__text">
                                { __( 'Forms created with the WPZOOM Forms plugin can be embedded in pages using a block or a shortcode. You can even embed the shortcode in page builders.', 'wpzoom-forms' ) }
                            </p>
                        </div>
                    ),
                },
                {
                    image: (
                        <img width="312" src={require('../images/step-4.png')} />
                    ),
                    content: (
                        <div className="wpzoom-forms-welcome-guide-page">
                            <h2 class="edit-post-welcome-guide__heading">{ __( 'Adjust your form\'s settings', 'wpzoom-forms' ) }</h2>
                            <p class="edit-post-welcome-guide__text">
                                { __( 'Each form and field can be customized from the right sidebar.', 'wpzoom-forms' ) }
                            </p>
                        </div>
                    ),
                },
			] }
		/> }
	</>;
}

export default withSelect( select => {
	return {
		isActive: select( preferencesStore ).get( 'wpzoom-forms', 'welcomeGuide' ),
	};
} )( WelcomeGuide );
