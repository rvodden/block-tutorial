import { TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from '@wordpress/i18n';

export function PersonSidebar() {
	const postType = useSelect( select => select( 'core/editor' ).getCurrentPostType() );
	const postId = useSelect( select => select( 'core/editor' ).getCurrentPostId() );

	const [ meta, updateMeta ] = useEntityProp(
		'postType',
		postType,
		'meta',
		postId
	);
	const { firstname, secondname } = meta;
	if ('person' !== postType) return null;

	function updateTitle({firstname, secondname}) {
		wp.data.dispatch( 'core/editor' ).editPost( { title: secondname + ', ' + firstname } );
	}

	return (
		<PluginDocumentSettingPanel
			name="orchestra-manager-person-sidebar"
			title={ __( 'Person Settings', 'wholesome-plugin' ) }
		>
			<TextControl
				label={ __( 'First Name', 'orchestra-manager' ) }
				value={ firstname }
				onChange={ ( value ) => {
					updateMeta({
						...meta,
						firstname: value
					})
					updateTitle({firstname: value, secondname})
				}}
			/>
			<TextControl
				label={ __( 'Second Name', 'orchestra-manager' ) }
				value={ secondname }
				onChange={ ( value ) => {
					updateMeta({
						...meta,
						secondname: value
					})
					updateTitle({firstname, secondname: value})
				}}
			/>
		</PluginDocumentSettingPanel>
	);
}
