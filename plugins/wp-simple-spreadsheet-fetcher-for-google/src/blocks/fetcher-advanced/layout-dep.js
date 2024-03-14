import { __ } from '@wordpress/i18n';
import { InnerBlocks, RichText } from '@wordpress/block-editor';
import { Component } from '@wordpress/element';

export class DeprecatedEventRegistrationAdvance extends Component {
	render() {
		const { setAttributes, className, attributes } = this.props.props;
		const { price, after, caption } = attributes;
		const for_ = this.props.for_;
		const ALLOWED_BLOCKS = [ 'wp2s2fg/fetcher-item' ];
		const TEMPLATE = [ ALLOWED_BLOCKS ];

		if ( for_ === 'edit' ) {
			return (
				<div
					className={ `${ className } wp2s2fg_fetcher-advanced_container wp2s2fg_fetcher-advanced_event-advance` }
				>
					<div className={ `wp2s2fg_fetcher-advanced_description` }>
						<RichText
							tagName="h4"
							className={ 'wp2s2fg_fetcher-advanced_caption' }
							onChange={ ( value ) =>
								setAttributes( { caption: value } )
							}
							value={ caption }
							placeholder={ __(
								'General Participant',
								'wp-simple-spreadsheet-fetcher-for-google'
							) }
						/>
						<RichText
							tagName="p"
							className={ 'wp2s2fg_fetcher-advanced_price' }
							onChange={ ( value ) => setAttributes( { price: value } ) }
							value={ price }
							placeholder={ __(
								'Free',
								'wp-simple-spreadsheet-fetcher-for-google'
							) }
						/>
					</div>
					<div
						className={ `wp2s2fg_fetcher-advanced_number_container` }
					>
						<InnerBlocks
							template={ TEMPLATE }
							allowedBlocks={ ALLOWED_BLOCKS }
							templateLock={ 'all' }
						/>
						<span
							className={ 'wp2s2fg_fetcher-advanced_number_line' }
						>
							/
						</span>
						<RichText
							tagName="p"
							className={ 'wp2s2fg_fetcher-advanced_number_after' }
							onChange={ ( value ) => setAttributes( { after: value } ) }
							value={ after }
							placeholder={ __(
								'100',
								'wp-simple-spreadsheet-fetcher-for-google'
							) }
						/>
					</div>
				</div>
			);
		} else if ( for_ === 'save' ) {
			return (
				<div
					className={ `${ className } wp2s2fg_fetcher-advanced_container wp2s2fg_fetcher-advanced_event-advance` }
				>
					<div className={ `wp2s2fg_fetcher-advanced_description` }>
						<RichText.Content
							tagName="h4"
							className={ 'wp2s2fg_fetcher-advanced_caption' }
							value={ caption }
						/>
						<RichText.Content
							tagName="p"
							className={ 'wp2s2fg_fetcher-advanced_price' }
							value={ price }
						/>
					</div>
					<div
						className={ `wp2s2fg_fetcher-advanced_number_container` }
					>
						<InnerBlocks.Content />
						<span
							className={ 'wp2s2fg_fetcher-advanced_number_line' }
						>
							/
						</span>
						<RichText.Content
							tagName="p"
							className={ 'wp2s2fg_fetcher-advanced_number_after' }
							value={ after }
						/>
					</div>
				</div>
			);
		}
	}
}
