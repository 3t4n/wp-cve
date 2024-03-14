import { __ } from "@wordpress/i18n";

export default ({ attributes, className }) => {
	const { RichText } = wp.blockEditor;
	const {
		block_id,
		layoutStyle,
		specificationTitle,
		specificationTable,
		edSpecificationTitle,
	} = attributes;

	return (
		<div
			id={`affiliatex-specification-style-${block_id}`}
			className={className}
		>
			<div className="affx-specification-block-container">
				<table className={`affx-specification-table ${layoutStyle}`}>
					{edSpecificationTitle && (
						<thead>
							<tr>
								<th clasName="affx-spec-title" colSpan="2">
									<RichText.Content
										value={specificationTitle}
									/>
								</th>
							</tr>
						</thead>
					)}
					<tbody>
						{specificationTable?.map((specification, index) => (
							<tr key={index}>
								<td className="affx-spec-label">
									<RichText.Content
										value={
											specification?.specificationLabel
										}
									/>
								</td>
								<td className="affx-spec-value">
									<RichText.Content
										value={
											specification?.specificationValue
										}
									/>
								</td>
							</tr>
						))}
					</tbody>
				</table>
			</div>
		</div>
	);
};
