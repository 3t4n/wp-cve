import { registerFormatType, create, insert } from "@wordpress/rich-text";
import { BlockControls } from "@wordpress/block-editor";
import { useSelect } from "@wordpress/data";
const { Toolbar, IconButton } = wp.components;

function TickIcon(props) {
	const selectedBlock = useSelect((select) => {
		return select("core/block-editor").getSelectedBlock();
	}, []);

	if (selectedBlock && selectedBlock.name !== "affiliatex/versus-line") {
		if (selectedBlock && selectedBlock.name !== "affiliatex/versus") {
			if (
				selectedBlock &&
				selectedBlock.name !== "affiliatex/product-comparison"
			) {
				return null;
			}
		}
	}

	return (
		<BlockControls>
			<Toolbar>
				<IconButton
					icon="yes"
					title="Tick"
					onClick={() => {
						let temp = create({
							html:
								'<i class="fas fa-check" aria-hidden="true"> </i>',
						});

						props.onChange(insert(props.value, temp));
					}}
					isActive={props.isActive}
				></IconButton>
			</Toolbar>
		</BlockControls>
	);
}

registerFormatType("affiliatex/tick-icon", {
	title: "Tick",
	tagName: "tick",
	className: null,
	edit: TickIcon,
});

function CheckIcon(props) {
	const selectedBlock = useSelect((select) => {
		return select("core/block-editor").getSelectedBlock();
	}, []);

	if (selectedBlock && selectedBlock.name !== "affiliatex/versus-line") {
		if (selectedBlock && selectedBlock.name !== "affiliatex/versus") {
			if (
				selectedBlock &&
				selectedBlock.name !== "affiliatex/product-comparison"
			) {
				return null;
			}
		}
	}

	return (
		<BlockControls>
			<Toolbar>
				<IconButton
					icon="yes-alt"
					title="Check"
					onClick={() => {
						let temp = create({
							html:
								'<i class="fas fa-check-circle" aria-hidden="true"> </i>',
						});

						props.onChange(insert(props.value, temp));
					}}
					isActive={props.isActive}
				></IconButton>
			</Toolbar>
		</BlockControls>
	);
}

registerFormatType("affiliatex/check-icon", {
	title: "Check",
	tagName: "check",
	className: null,
	edit: CheckIcon,
});

function WrongIcon(props) {
	const selectedBlock = useSelect((select) => {
		return select("core/block-editor").getSelectedBlock();
	}, []);

	if (selectedBlock && selectedBlock.name !== "affiliatex/versus-line") {
		if (selectedBlock && selectedBlock.name !== "affiliatex/versus") {
			if (
				selectedBlock &&
				selectedBlock.name !== "affiliatex/product-comparison"
			) {
				return null;
			}
		}
	}

	return (
		<BlockControls>
			<Toolbar>
				<IconButton
					icon="no-alt"
					title="Wrong"
					onClick={() => {
						let temp = create({
							html:
								'<i class="fas fa-times" aria-hidden="true"> </i>',
						});

						props.onChange(insert(props.value, temp));
					}}
					isActive={props.isActive}
				></IconButton>
			</Toolbar>
		</BlockControls>
	);
}

registerFormatType("affiliatex/wrong-icon", {
	title: "Wrong",
	tagName: "wrong",
	className: null,
	edit: WrongIcon,
});

function CrossIcon(props) {
	const selectedBlock = useSelect((select) => {
		return select("core/block-editor").getSelectedBlock();
	}, []);

	if (selectedBlock && selectedBlock.name !== "affiliatex/versus-line") {
		if (selectedBlock && selectedBlock.name !== "affiliatex/versus") {
			if (
				selectedBlock &&
				selectedBlock.name !== "affiliatex/product-comparison"
			) {
				return null;
			}
		}
	}

	return (
		<BlockControls>
			<Toolbar>
				<IconButton
					icon="dismiss"
					title="Cross"
					onClick={() => {
						let temp = create({
							html:
								'<i class="fas fa-times-circle" aria-hidden="true"> </i>',
						});

						props.onChange(insert(props.value, temp));
					}}
					isActive={props.isActive}
				></IconButton>
			</Toolbar>
		</BlockControls>
	);
}

registerFormatType("affiliatex/cross-icon", {
	title: "Cross",
	tagName: "cross",
	className: null,
	edit: CrossIcon,
});
