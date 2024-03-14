import { FormControl, FormLabel } from '@chakra-ui/react';
import { createBlock, serialize } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import React, { useCallback, useState } from 'react';
import { useFormContext } from 'react-hook-form';
import BlockEditor from '../../../../../../../assets/js/back-end/components/common/BlockEditor';
import ContentCreateWithAIModal from '../../../../../../../assets/js/back-end/components/common/ContentCreateWithAIModal';
import Editor from '../../../../../../../assets/js/back-end/components/common/Editor';
import localized from '../../../../../../../assets/js/back-end/utils/global';

interface Props {
	defaultValue?: string;
}

const Description: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const [editorValue, setEditorValue] = useState(defaultValue);
	const [blockAiContent, setBlockAiContent] = useState('');
	const { setValue } = useFormContext();

	const handleContentCreation = useCallback(
		(newContent: string) => {
			const data = serialize([
				createBlock('core/paragraph', {
					content: newContent,
				}),
			]);
			setEditorValue(data);
			setValue('description', data);
			setBlockAiContent(newContent);
		},
		[setValue],
	);
	return (
		<FormControl>
			<FormLabel>{__('Description', 'masteriyo')}</FormLabel>
			<ContentCreateWithAIModal
				onContentCreated={handleContentCreation}
				elementId="mto-announcement-description"
			/>
			{'classic_editor' === localized.defaultEditor ? (
				<Editor
					id="mto-announcement-description"
					name="description"
					defaultValue={editorValue}
				/>
			) : (
				<BlockEditor
					defaultValue={editorValue}
					name="description"
					id="mto-announcement-description"
					blockAiContent={blockAiContent}
				/>
			)}
		</FormControl>
	);
};

export default Description;
