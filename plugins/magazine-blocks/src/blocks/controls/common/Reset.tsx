import { Button, ButtonProps } from "@chakra-ui/react";
import { getBlockType } from "@wordpress/blocks";
import { useSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import React from "react";
import { Reset as ResetIcon } from "../../../base/components/Icons";
import { isEqual, _get } from "../../utils";

type Props = {
	resetKey: string;
	saved: any;
	onReset: (...args: any[]) => void;
	buttonProps?: ButtonProps;
};

const Reset = ({ resetKey, saved, onReset, buttonProps }: Props) => {
	const { attributesDefinition, clientId, attributes } = useSelect(
		(select: (store: string) => any) => {
			const { getSelectedBlock } = select("core/block-editor");
			const { name, clientId, attributes } = getSelectedBlock();
			return {
				attributesDefinition: getBlockType(name)?.attributes ?? {},
				clientId,
				attributes,
			};
		},
		[saved]
	);

	let keys = resetKey.split(".");
	keys.splice(1, 0, "default");
	const _resetKey = keys.join(".");

	const _default = _get(attributesDefinition, _resetKey, undefined);
	const isDisabled = isEqual(_default, saved);

	return (
		<Button
			variant="icon"
			minW={4}
			width={4}
			height={4}
			aria-label={__("Reset", "button")}
			isDisabled={isDisabled}
			onClick={() => {
				onReset?.(_default, clientId, attributes);
			}}
			{...buttonProps}
		>
			<ResetIcon h="3" w="3" />
		</Button>
	);
};

export default Reset;
