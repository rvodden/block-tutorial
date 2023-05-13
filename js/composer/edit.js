import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
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
	const [ meta  ] = useEntityProp(
		'postType',
		postType,
		'meta',
		postId
	);
	const { composer } = meta;

	const composerPost = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecord( 'postType', 'person', composer )
	} )

	return (
		<p { ...useBlockProps() }>
				{ composerPost ? composerPost.title.rendered : "Loading..." }
		</p>
	);
}
