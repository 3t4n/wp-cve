const { __ } = wp.i18n;

const { Component, Fragment } = wp.element;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { Panel, PanelBody, Button, Icon } = wp.components;
const { registerPlugin } = wp.plugins;

import '../../../css/admin/sidebar.scss';

import Relations from './relations';
import AddRelationModal from '../modal';

class Sidebar extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			isModalOpen: false,
		}
	}

	openModal() {
		if ( ! this.state.isModalOpen ) {
			this.setState( { isModalOpen: true } );
		}
	}

	closeModal() {
		if ( this.state.isModalOpen ) {
			this.setState( { isModalOpen: false } );
		}
	}

	render() {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem
					target="sidebar-custom-related-posts"
					icon="admin-links"
				>
				Custom Related Posts
				</PluginSidebarMoreMenuItem>
				<PluginSidebar
					name="sidebar-custom-related-posts"
					title="Custom Related Posts"
					icon={ <Icon icon={ <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="20" height="20"><title>link</title><g className="nc-icon-wrapper" fill="#111111"><path d="M17.619,10.138l-2.241,2.24c-.06.061-.1.13-.158.193a4.958,4.958,0,0,1,2.816,1.393,5.008,5.008,0,0,1,0,7.072l-5.5,5.5a5,5,0,0,1-7.072-7.072l2.385-2.385a10.054,10.054,0,0,1-.23-4.011L3.343,17.343A8,8,0,0,0,14.657,28.657l5.5-5.5a7.99,7.99,0,0,0-2.538-13.019Z" fill="#111111"/> <path data-color="color-2" d="M17.343,3.343l-5.5,5.5a7.99,7.99,0,0,0,2.538,13.019l2.241-2.24c.06-.061.107-.129.162-.193a4.953,4.953,0,0,1-2.82-1.393,5.008,5.008,0,0,1,0-7.072l5.5-5.5a5,5,0,0,1,7.072,7.072l-2.383,2.382a10.086,10.086,0,0,1,.241,4l4.263-4.263A8,8,0,0,0,17.343,3.343Z"/></g></svg> } /> }
				>
					<Panel>
						<PanelBody title={ __( 'Relations' ) }>
							<Relations />
							<Button
								isPrimary={true}
								onClick={ this.openModal.bind(this) }
							>{ __( 'Add Relations' ) }</Button>
						</PanelBody>
					</Panel>
				</PluginSidebar>
				{
					this.state.isModalOpen
					&&
					<AddRelationModal
						onClose={this.closeModal.bind(this)}
					/>
				}
			</Fragment>
	) }
}

registerPlugin( 'custom-related-posts', {
	render: Sidebar,
} );