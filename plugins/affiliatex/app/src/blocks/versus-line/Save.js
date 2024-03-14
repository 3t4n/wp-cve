import { __ } from "@wordpress/i18n";

export default ({ attributes, className }) => {
	const { RichText } = wp.blockEditor;
	const { block_id, versusTable, vsLabel } = attributes;

	return (
		<div
			id={`affiliatex-versus-line-style-${block_id}`}
			className={`affx-versus-line-block-container ${className}`}
		>
			<div className="affx-versus-table-wrap">
				<table className="affx-product-versus-table">
					<tbody>
						{versusTable?.map((item, index) => (
							<tr key={index}>
								<td className="data-label">
									<RichText.Content
										value={item.versusTitle}
									/>
									<span className="data-info">
										<RichText.Content
											value={item.versusSubTitle}
										/>
									</span>
								</td>
								<td>
									<RichText.Content
										value={item.versusValue1}
									/>
								</td>
								<td>
									<span className="affx-vs-icon">
										<RichText.Content value={vsLabel} />
									</span>
								</td>
								<td>
									<RichText.Content
										value={item.versusValue2}
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
