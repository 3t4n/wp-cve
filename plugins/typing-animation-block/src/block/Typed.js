import React from 'react';
import Typed from 'typed.js';

export default class TypedComponent extends React.PureComponent {
	componentDidMount() {
		this.typed = new Typed( this.el, this.props );
	}

	componentDidUpdate() {
		this.typed.destroy();
		this.typed = new Typed( this.el, this.props );
	}

	componentWillUnmount() {
		// Make sure to destroy Typed instance on unmounting
		// to prevent memory leaks
		this.typed.destroy();
	}

	render() {
		const { element: Component } = this.props;
		return (
			<Component>
				<span ref={ el => this.el = el } className="blink-text" />
			</Component>
		);
	}
}
