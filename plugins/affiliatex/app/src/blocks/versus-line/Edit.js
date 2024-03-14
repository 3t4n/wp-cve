import { __ } from "@wordpress/i18n";
import BlockInspector from "./Inspector";
import { useEffect } from "@wordpress/element";
import blocks_styling from "./styling";
const { RichText } = wp.blockEditor;
const { Button } = wp.components;

export default ({
	attributes,
	setAttributes,
	className,
	isSelected,
	clientId,
}) => {
	const { Fragment } = wp.element;

	useEffect(() => {
		const $style = document.createElement("style");
		$style.setAttribute("id", "affiliatex-versus-line-style-" + clientId);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById(
			"affiliatex-versus-line-style-" + clientId
		);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-versus-line-style",
				clientId
			);
		}
	}, [attributes]);

	const { versusTable, vsLabel, itemCount } = attributes;

	const handleVersusRow = (versusTable) => {
		let itemCount = 0;
		let tempVersusTable = [...versusTable];
		tempVersusTable = [
			...versusTable,
			{
				versusTitle: "",
				versusSubTitle: "",
				versusValue1: "",
				versusValue2: "",
			},
		];
		setAttributes({ versusTable: tempVersusTable });
		itemCount = versusTable.length;
		setAttributes({ itemCount });
	};

	const handleRemoveVersusRow = (index) => {
		let tempversusTable = [...versusTable];
		tempversusTable.splice(index, 1);
		setAttributes({
			versusTable: tempversusTable,
			itemCount: itemCount - 1,
		});
	};

	const onChangeVersusTable = (value, index, item) => {
		let tempversusTable = [...versusTable];
		tempversusTable[index] = {
			...tempversusTable[index],
			[`${item}`]: value,
		};
		setAttributes({ versusTable: tempversusTable });
	};

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-versus-line-style-${clientId}`}
				className={`affx-versus-line-block-container ${className}`}
			>
				<div className="affx-versus-table-wrap">
					<table className="affx-product-versus-table affx-product-versus-line">
						<tbody>
							{versusTable?.map((item, index) => (
								<tr key={index}>
									<td className="data-label">
										<RichText
											placeholder="Title"
											value={item.versusTitle}
											onChange={(newTitle) =>
												onChangeVersusTable(
													newTitle,
													index,
													"versusTitle"
												)
											}
										/>
										<span className="data-info">
											<RichText
												placeholder="Subtitle"
												value={item.versusSubTitle}
												onChange={(newSubTitle) =>
													onChangeVersusTable(
														newSubTitle,
														index,
														"versusSubTitle"
													)
												}
											/>
										</span>
									</td>
									<td>
										<RichText
											placeholder="Value 1"
											value={item.versusValue1}
											onChange={(newValue) =>
												onChangeVersusTable(
													newValue,
													index,
													"versusValue1"
												)
											}
										/>
									</td>
									<td>
										<span className="affx-vs-icon">
											<RichText
												placeholder="VS Label"
												value={vsLabel}
												onChange={(newLabel) =>
													setAttributes({
														vsLabel: newLabel,
													})
												}
											/>
										</span>
									</td>
									<td>
										<RichText
											placeholder="Value 2"
											value={item.versusValue2}
											onChange={(newValue) =>
												onChangeVersusTable(
													newValue,
													index,
													"versusValue2"
												)
											}
										/>
									</td>
									{index > 0 ? (
										<td className="affx-spec-remove">
											<Button
												className="affx-specification-remove-btn"
												onClick={() => {
													handleRemoveVersusRow(
														index
													);
												}}
											>
												<svg
													xmlns="http://www.w3.org/2000/svg"
													width="13.549"
													height="18.065"
													viewBox="0 0 13.549 18.065"
												>
													<g
														transform="translate(-132.5 -10)"
														opacity="0.2"
													>
														<path
															d="M146.049,12.319h-3.764v-.94A1.378,1.378,0,0,0,140.906,10h-3.268a1.376,1.376,0,0,0-1.373,1.379v.94H132.5v.691h.992l1.11,13.676a1.378,1.378,0,0,0,1.379,1.379h6.633a1.378,1.378,0,0,0,1.379-1.379l1.1-13.676h.96Zm-9.1-.94a.691.691,0,0,1,.688-.691h3.27a.688.688,0,0,1,.688.691v.94H136.95ZM143.3,26.65v.037a.691.691,0,0,1-.688.691h-6.633a.688.688,0,0,1-.688-.691V26.65l-1.115-13.638H144.4Z"
															transform="translate(0 0)"
															fill="%23191919"
														/>
														<path
															d="M482.1,255h.723v12.466H482.1Z"
															transform="translate(-343.188 -241.83)"
															fill="%23191919"
														/>
														<path
															d="M600.009,255h-.756l-.553,12.466h.756Z"
															transform="translate(-457.688 -241.83)"
															fill="%23191919"
														/>
														<path
															d="M337.45,255h-.75l.553,12.466h.756Z"
															transform="translate(-200.472 -241.83)"
															fill="%23191919"
														/>
													</g>
												</svg>
											</Button>
										</td>
									) : (
										<td></td>
									)}
								</tr>
							))}
							<tr>
								<td className="row-appender" colSpan="5">
									<Button
										isDefault
										className="affx-add-specification-btn components_panel_btn"
										onClick={() =>
											handleVersusRow(versusTable)
										}
									>
										<svg
											xmlns="http://www.w3.org/2000/svg"
											viewBox="-2 -2 24 24"
											width="24"
											height="24"
											role="img"
											aria-hidden="true"
											focusable="false"
										>
											<path d="M10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6zM10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6z"></path>
										</svg>
									</Button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</Fragment>
	);
};
