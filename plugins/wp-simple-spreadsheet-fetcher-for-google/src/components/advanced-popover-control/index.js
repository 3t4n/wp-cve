const { Button, Popover, IconButton } = wp.components;
const { Fragment, createRef, Component } = wp.element;
import { __ } from "@wordpress/i18n";

export class AdvancedPopOverControl extends Component {
	constructor(props) {
		super(props);
		this.state = { open: false };
		this.buttonRef = createRef();
	}

	render() {
		const popverBtnClass = "apc-icon-btn";

		const handleOpen = () => {
			this.setState({ open: !this.state.open });
		};

		const handleClose = () => {
			this.setState({ open: false });
		};

		const handleOnClickOutside = (event) => {
			if (
				event.relatedTarget &&
				!event.relatedTarget.closest(`.${popverBtnClass}`) &&
				event.relatedTarget !== this.buttonRef.current
			) {
				handleClose();
			}
		};

		return (
			<Fragment>
				<div className="components-base-control">
					<div
						className={
							"wssffg-button-icon-control__wrapper components-base-control__field"
						}
					>
						<Button
							isTertiary
							className={`${popverBtnClass}`}
							onClick={handleOpen}
						>
							<span className="components-base-control__label">
								{this.props.label}
							</span>
						</Button>
						<IconButton
							className={`${popverBtnClass} components-button is-button is-default`}
							icon="edit"
							label={__(
								"More",
								"wp-simple-spreadsheet-fetcher-for-google"
							)}
							onClick={handleOpen}
							id={`wssffg-button-icon-control__edit`}
							ref={this.buttonRef}
						/>
						{this.state.open && this.buttonRef.current && (
							<Popover
								anchorRect={this.buttonRef.current.getBoundingClientRect()}
								children={this.props.renderComp}
								onFocusOutside={handleOnClickOutside}
								focusOnMount={"container"}
								className={"wssffg-advanced-popover-control"}
							></Popover>
						)}
					</div>
				</div>
			</Fragment>
		);
	}
}
