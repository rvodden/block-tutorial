import { SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from '@wordpress/i18n';

export function PieceSidebar() {
	const postType = useSelect( select => select( 'core/editor' ).getCurrentPostType() );
	const postId = useSelect( select => select( 'core/editor' ).getCurrentPostId() );

	const [ meta, updateMeta ] = useEntityProp(
		'postType',
		postType,
		'meta',
		postId
	);
	const { composer } = meta;
	if ('piece' !== postType) return null;

	const composers = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecords( 'postType', 'person', { role: [4], per_page: -1 } )
	} )

	console.log(composers);

	const options = [];
	if( composers ) {
		options.push( { value: 0, label: 'Select a composer' } )
		composers.forEach( ( page ) => {
			options.push( { value : page.id, label : page.title.rendered } )
		})
	} else {
		options.push( { value: 0, label: 'Loading...' } )
	}

	return (
		<PluginDocumentSettingPanel
		name="orchestra-manager-piece-sidebar"
		title={ __( 'Piece Settings', 'wholesome-plugin' ) }
		>
			<SelectControl
				label="Composer"
				options={ options }
				value={ composer }
				onChange={ ( composer ) => {
					updateMeta({
						...meta,
						composer
					})
				}}
			/>
		</PluginDocumentSettingPanel>
	);
}
