import React from 'react';
import HeadingLevelIcon from './HeadingLevelIcon';

const { DropdownMenu } = wp.components;

class ElementToolbar extends React.Component {
	createLevelControl( targetElement, selectedElement, onChange ) {
		const isActive = targetElement === selectedElement;
		return {
			icon: <HeadingLevelIcon element={ targetElement } __unstableActive={ isActive } />,
			title: `${ targetElement }`,
			isActive,
			onClick: () => onChange( targetElement ),
		};
	}

	render() {
		const { selectedElement, onChange } = this.props;

		return (
			<DropdownMenu
				className="sizeDropdown"
				icon={ <div><HeadingLevelIcon element={ selectedElement } /></div> }
				isCollapsed={ true }
				label="Size"
				controls={ [ 'h2', 'h3', 'h4', 'h5', 'h6', 'span' ].map(
					( element ) => this.createLevelControl( element, selectedElement, onChange )
				) }
			/>
		);
	}
}

export default ElementToolbar;
