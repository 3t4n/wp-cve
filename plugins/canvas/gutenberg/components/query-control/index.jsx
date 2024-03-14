/**
 * Internal dependencies
 */
import PostTypeSelectorControl from '../post-type-selector-control';
import PostFormatsSelectorControl from '../post-formats-selector-control';
import CategoriesSelectorControl from '../categories-selector-control';
import TagsSelectorControl from '../tags-selector-control';
import PostsSelectorControl from '../posts-selector-control';

/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	Component,
	Fragment,
} = wp.element;

const {
	TextControl,
	SelectControl,
} = wp.components;

/**
 * Component
 */
export default class ComponentQueryControl extends Component {
	constructor() {
		super( ...arguments );

		this.updateValue = this.updateValue.bind( this );
	}

	updateValue( newData ) {
		const {
			value,
			onChange,
		} = this.props;

		const result = {
			...value,
			...newData,
		};

		// reset categories, tags and posts filters if post_type != 'post'
		if ( 'post' === value.post_type && 'post' !== result.post_type ) {
			result.categories = '';
			result.tags = '';
			result.posts = '';
		}

		onChange( result );
	}

	render() {
		const {
			value: {
				posts_type = 'post',
				categories = '',
				tags = '',
				exclude_categories = '',
				exclude_tags = '',
				formats = '',
				posts = '',
				offset = '',
				orderby = 'date',
				order = 'DESC',
				time_frame = '',
				taxonomy = '',
				terms = '',
			},
		} = this.props;

		return (
			<Fragment>
				<PostTypeSelectorControl
					label={ __( 'Post Type' ) }
					value={ posts_type }
					onChange={ ( val ) => {
						this.updateValue( {
							posts_type: val,
						} );
					} }
				/>
				{ 'post' === posts_type ? (
					<Fragment>
						<CategoriesSelectorControl
							label={ __( 'Filter by Categories' ) }
							value={ categories }
							onChange={ ( val ) => {
								this.updateValue( {
									categories: val,
								} );
							} }
						/>
						<TagsSelectorControl
							label={ __( 'Filter by Tags' ) }
							value={ tags }
							onChange={ ( val ) => {
								this.updateValue( {
									tags: val,
								} );
							} }
						/>
					</Fragment>
				) : '' }
				{ 'post' === posts_type ? (
					<Fragment>
						<CategoriesSelectorControl
							label={ __( 'Exclude Categories' ) }
							value={ exclude_categories }
							onChange={ ( val ) => {
								this.updateValue( {
									exclude_categories: val,
								} );
							} }
						/>
						<TagsSelectorControl
							label={ __( 'Exclude Tags' ) }
							value={ exclude_tags }
							onChange={ ( val ) => {
								this.updateValue( {
									exclude_tags: val,
								} );
							} }
						/>
					</Fragment>
				) : '' }
				<PostFormatsSelectorControl
					label={ __( 'Filter by Formats' ) }
					value={ formats }
					onChange={ ( val ) => {
						this.updateValue( {
							formats: val,
						} );
					} }
				/>
				<PostsSelectorControl
					label={ __( 'Filter by Posts' ) }
					value={ posts }
					postType={ posts_type }
					onChange={ ( val ) => {
						this.updateValue( {
							posts: val,
						} );
					} }
				/>
				<TextControl
					label={ __( 'Offset' ) }
					value={ offset }
					onChange={ ( val ) => {
						this.updateValue( {
							offset: val,
						} );
					} }
				/>
				<SelectControl
					label={ __( 'Order by' ) }
					value={ orderby }
					options={ [
						{
							label: __( 'Published Date' ),
							value: 'date',
						},
						{
							label: __( 'Modified Date' ),
							value: 'modified',
						},
						{
							label: __( 'Title' ),
							value: 'title',
						},
						{
							label: __( 'Random' ),
							value: 'rand',
						},
						{
							label: __( 'Views' ),
							value: 'views',
						},
						{
							label: __( 'Comment Count' ),
							value: 'comment_count',
						},
						{
							label: __( 'ID' ),
							value: 'ID',
						},
						{
							label: __( 'Custom' ),
							value: 'post__in',
						},
					] }
					onChange={ ( val ) => {
						this.updateValue( {
							orderby: val,
						} );
					} }
				/>
				{ 'views' === orderby ? (
					<TextControl
						label={ __( 'Filter by Time Frame' ) }
						help={ __( 'Add period of posts in English. For example: «2 months», «14 days» or even «1 year»' ) }
						value={ time_frame }
						onChange={ ( val ) => {
							this.updateValue( {
								time_frame: val,
							} );
						} }
					/>
				) : '' }
				<SelectControl
					label={ __( 'Order' ) }
					value={ order }
					options={ [
						{
							label: __( 'Descending' ),
							value: 'DESC',
						},
						{
							label: __( 'Ascending' ),
							value: 'ASC',
						},
					] }
					onChange={ ( val ) => {
						this.updateValue( {
							order: val,
						} );
					} }
				/>
				<TextControl
					label={ __( 'Filter by Taxonomy' ) }
					value={ taxonomy }
					onChange={ ( val ) => {
						this.updateValue( {
							taxonomy: val,
						} );
					} }
				/>
				<TextControl
					label={ __( 'Filter by Terms' ) }
					value={ terms }
					onChange={ ( val ) => {
						this.updateValue( {
							terms: val,
						} );
					} }
				/>
			</Fragment>
		);
	}
}
