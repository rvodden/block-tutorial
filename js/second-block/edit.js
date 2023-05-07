import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';

import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export function Edit( {
	context: { postType, postId } }
) {
	const [ meta, updateMeta ] = useEntityProp(
		'postType',
		postType,
		'firstname',
		postId
	);
	console.log(meta);
	let firstname;
	if ( meta !== undefined) {
		firstname = meta.firstname;
	} else {
		firstname = '';
	};

	return (
		<blockquote { ...useBlockProps() }>
			<RichText
				tagName="p"
				placeholder={ __( 'FirstName', 'orchestra-manager' ) }
				allowedFormats={ [] }
				disableLineBreaks
				value={ firstname }
				onChange={ ( newFirstname ) => {
					updateMeta( {
						...meta,
						firstname: newFirstname,
					} )}
				}
			/>
		</blockquote>
	);
}
