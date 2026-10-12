import { registerBlockType } from '@wordpress/blocks';
import edit from './edit';

const name = 'dwb/post-header';

registerBlockType( name, {
	title: 'Post Header',
	icon: 'layout',
	apiVersion: 2,
	category: 'common',
	attributes: {
		align: {
			type: 'string',
			default: 'full',
		},
		backgroundColor: {
			type: 'string',
		},
		featuredImageSizeSlug: {
			type: 'string',
			default: 'single',
		},
		featuredImageSizeWidth: {
			type: 'number',
			default: null,
		},
		featuredImageSizeHeight: {
			type: 'number',
			default: null,
		},
		imageAlign: {
			type: 'string',
			default: 'right',
		},
		showAuthor: {
			type: 'boolean',
			default: true,
		},
		textColor: {
			type: 'string',
		},
	},
	supports: {
		align: [ 'wide', 'full' ],
		imageAlign: [ 'left', 'right' ],
	},
	edit,
} );
