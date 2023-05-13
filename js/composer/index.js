import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import { Edit } from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit
} );

import { registerPlugin } from '@wordpress/plugins';
import { PersonSidebar }from '../components/PersonSidebar';
import { PieceSidebar } from '../components/PieceSidebar';
import { ConcertSidebar } from '../components/ConcertSidebar';

registerPlugin(
    'orchestra-manager-perons-sidebar',
    {
        icon: 'visibility',
        render: PersonSidebar,
    }
);

registerPlugin(
    'orchestra-manager-piece-sidebar',
    {
        icon: 'visibility',
        render: PieceSidebar,
    }
);

registerPlugin(
    'orchestra-manager-concert-sidebar',
    {
        icon: 'visibility',
        render: ConcertSidebar,
    }
);
