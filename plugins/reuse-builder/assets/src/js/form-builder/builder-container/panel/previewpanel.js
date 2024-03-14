import React, { Component } from 'react';
import { connect } from 'react-redux';
const ReuseForm = __REUSEFORM__;
// import 'reuse-form/elements/less/common.less';
import { randomNumber } from 'utility/helper';
import EditPanelView from '../../builder-components/reuseEditPanelView';
import { deleteField, editField } from '../../builder-modules/builderModule';
import { changeView } from 'form-builder/builder-modules/viewModule';

class PreviewPanel extends Component {
	render() {
		const {
			fields,
			conditionDecisions,
			deleteField,
			editField,
			changeView,
		} = this.props;
		let preValue = {};
		const reuseOption = {
			reuseFormId: `builder-preview-${randomNumber()}`,
			fields,
			getUpdatedFields: () => {},
			preValue: {},
			errorMessages: {},
			// conditions: conditionDecisions,
			// EditPanelView,
			editPanelAction: (type, field) => {
				switch (type) {
					case 'edit':
						changeView('edit_field');
						editField(field);
						break;
					case 'delete':
						deleteField(field);
						break;
				}
			},
		};
		return (
			<div className="scwpFormSettingsWrapper">
				<div className="scwpSettingsPannelWrapper">
					{fields.length > 0 ? (
						<ReuseForm {...reuseOption} />
					) : (
						<div className="scwpNoFieldNotice">
							<span>No field is added</span>
						</div>
					)}
				</div>
			</div>
		);
	}
}
function mapStateToProps() {
	return {};
}
export default connect(mapStateToProps, {
	changeView,
	deleteField,
	editField,
})(PreviewPanel);
