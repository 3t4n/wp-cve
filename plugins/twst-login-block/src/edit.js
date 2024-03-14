import { __ } from '@wordpress/i18n';
import { RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, SelectControl } from '@wordpress/components';

import './editor.scss';

export default function Edit( { attributes, className, setAttributes } ) {

	const set = ( attribute ) => {
		return ( value ) => {
			setAttributes( { [attribute]: value } );
		}
	}

	const rememberMe = (
		<p className="login-remember">
			<input
				type="checkbox"
				onChange={ ( event ) => ( set( 'defaultRememberMe' ) )( event.target.value === "on" ) }
				checked={ attributes.defaultRememberMe }
			/>
			<RichText
				tagName="label"
				placeholder={ __( 'Remember Me', 'twst-login-block' ) }
				keepPlaceholderOnFocus="true"
				formattingControls={ [ 'bold', 'italic' ] }
				onChange={ set( 'labelRememberMe' ) }
				value={ attributes.labelRememberMe }
			/>
		</p>
	);

	/**
	 * Note to self: These are <label> as only select elements trigger :hover for CSS.
	 */
	return (
		<>
		{
			<InspectorControls>
				<PanelBody title={ __( 'Login Form Settings', 'twst-login-block' ) }>
					<ToggleControl
						label={ __( 'Show Remember Me', 'twst-login-block' ) }
						onChange={ set( 'showRememberMe' ) }
						checked={ attributes.showRememberMe }
					/>
					<SelectControl
						label={ __( 'Logged in behaviour', 'twst-login-block' ) }
						value={ attributes.loggedInBehaviour }
						onChange={ set( 'loggedInBehaviour' ) }
						options={ [
							{ value: 'hide', label: __( 'Display nothing', 'twst-login-block' ) },
							{ value: 'user', label: __( 'Show "Logged in as USER. Log Out"', 'twst-login-block' ) },
							{ value: 'logout', label: __( 'Just the log out link', 'twst-login-block' ) },
							{ value: 'login', label: __( 'Show log in form', 'twst-login-block' ) },
						] }
					/>
				</PanelBody>
			</InspectorControls>
		}
		<form className={ className }>
			<p className="login-username">
				<RichText
					tagName="label"
					placeholder={ __( 'Username or Email Address', 'twst-login-block' ) }
					keepPlaceholderOnFocus="true"
					formattingControls={ [ 'bold', 'italic' ] }
					onChange={ set( 'labelUsername' ) }
                    value={ attributes.labelUsername }
                />
				<input
					type="text"
					className="input"
					placeholder={ __( 'Default username', 'twst-login-block' ) }
					onChange={ ( event ) => ( set( 'defaultUsername' ) )( event.target.value ) }
					value={ attributes.defaultUsername }
					size="20"
				/>
			</p>
			<p className="login-password">
				<RichText
					tagName="label"
					placeholder={ __( 'Password', 'twst-login-block' ) }
					keepPlaceholderOnFocus="true"
					formattingControls={ [ 'bold', 'italic' ] }
                    onChange={ set( 'labelPassword' ) }
                    value={ attributes.labelPassword }
                />
				<input type="password" className="input" size="20" readOnly />
			</p>
			{ attributes.showRememberMe ? rememberMe : (<br />) }
			<p className="login-submit">
				<RichText
					tagName="label"
					className="wp-block-button__link"
					placeholder={ __( 'Log In', 'twst-login-block' ) }
					keepPlaceholderOnFocus="true"
					formattingControls={ [] }
					onChange={ set( 'labelLogIn' ) }
                    value={ attributes.labelLogIn }
                />
			</p>
		</form>
	</>
	);
}
