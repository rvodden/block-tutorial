import { PostSelect } from './PostSelect';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

export function ConcertSidebar() {
	const postType = useSelect( select => select( 'core/editor' ).getCurrentPostType() );
	if ('concert' !== postType) return null;
	return (
		<PluginDocumentSettingPanel
		name="orchestra-manager-concert-sidebar"
		title={ __( 'Concert Settings', 'orchestra-manager' ) }
		>
			<PostSelect
				label="Conductor"
				metaField="conductor"
				postType="person"
				query={{
					role_slug: ["conductor"],
					per_page: -1
				}}
			/>
		</PluginDocumentSettingPanel>
	);
}
