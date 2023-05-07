import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import './editor.scss';

export function edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	return (
		<TextControl
            { ...blockProps }
            value={ attributes.message }
            onChange={ ( val ) => setAttributes( { message: val } ) }
        />
    );
}
