import { Heading, Text } from '@elementor/app-ui';
import ConditionsProvider from '../../context/conditions';
import { Context as TemplatesContext } from '../../context/templates';
import ConditionsRows from './conditions-rows';

import './conditions.scss';
import BackButton from '../../molecules/back-button';

export default function Conditions( props ) {
	const { findTemplateItemInState, updateTemplateItemState } = React.useContext( TemplatesContext ),
		template = findTemplateItemInState( parseInt( props.id ) );

	if ( ! template ) {
		return <div>{ __( 'Not Found', 'lastudio-kit' ) }</div>;
	}

	return (
		<section className="e-site-editor-conditions">
			<BackButton/>
			<div className="e-site-editor-conditions__header">
				<img
					className="e-site-editor-conditions__header-image"
					src={ `${ elementorAppProConfig.baseUrl }/modules/theme-builder/assets/images/conditions-tab.svg` }
					alt={ __( 'Import template', 'lastudio-kit' ) }
				/>
				<Heading variant="h1" tag="h1">
					{ __( 'Where Do You Want to Display Your Template?', 'lastudio-kit' ) }
				</Heading>
				<Text variant="p">
					{ __( 'Set the conditions that determine where your template is used throughout your site.', 'lastudio-kit' ) }
					<br/>
					{ __( 'For example, choose \'Entire Site\' to display the template across your site.', 'lastudio-kit' ) }
				</Text>
			</div>
			<ConditionsProvider currentTemplate={ template } onConditionsSaved={ updateTemplateItemState }>
				<ConditionsRows onAfterSave={() => history.back()} />
			</ConditionsProvider>
		</section>
	);
}

Conditions.propTypes = {
	id: PropTypes.string,
};
