import { registerBlockType } from '@wordpress/blocks';
import edit from './edit';

const name = 'dwb/read-time';

registerBlockType( name, {
	title: 'Read Time',
	icon: 'editor-table',
	category: 'common',
	attributes: {
        "text": {
            "type": "string",
            "default": 'minute',
        },
        "pluralText": {
            "type": "string",
            "default": 'minutes',
        },
        "position": {
            "type": "string",
            "selector": "before,after",
            "default": "before"
        },                    
	},
	edit,
} );