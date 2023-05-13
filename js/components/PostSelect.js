import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';

export function PostSelect(props) {
	const targetPostType = props.postType;
	const metaField = props.metaField;
	const query = props.query;

	const selectProps = {...props};

	delete selectProps.postType;
	delete selectProps.metaField;
	delete selectProps.query;

	const postType = useSelect( select => select( 'core/editor' ).getCurrentPostType() );
	const postId = useSelect( select => select( 'core/editor' ).getCurrentPostId() );

	const [ meta, updateMeta ] = useEntityProp(
		'postType',
		postType,
		'meta',
		postId
	);

	selectProps.value = meta[metaField];

	const targetItems = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecords( 'postType', targetPostType, query )
	} )

	selectProps.options = [];
	if( targetItems ) {
		selectProps.options.push( { value: 0, label: 'Please select...' } )
		targetItems.forEach( ( item ) => {
			console.log(item);
			selectProps.options.push( { value : item.id, label : item.title.rendered } )
		})
	} else {
		selectProps.options.push( { value: 0, label: 'Loading...' } )
	}

	selectProps.onChange=( newValue ) => {
		meta[metaField] = newValue;
		updateMeta({
			...meta,
		})
	};

	return (
		<SelectControl {...selectProps} />
	)
}
