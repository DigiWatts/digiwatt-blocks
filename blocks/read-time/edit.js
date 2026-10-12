import { __ } from '@wordpress/i18n';
import { count } from '@wordpress/wordcount';
import { store as coreStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	SelectControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalInputControl as InputControl,
} from '@wordpress/components';

export default function ReadTimeEdit( { attributes, setAttributes } ) {
	const { className, readTimeText, timePosition } = attributes;

	const { post } = useSelect( ( select ) => {
		const { getEntityRecord } = select( coreStore );
		const currentPostId = select( 'core/editor' ).getCurrentPostId();
		const currentPostType = select( 'core/editor' ).getCurrentPostType();

		return {
			post: getEntityRecord( 'postType', currentPostType, currentPostId ),
		};
	} );

	// setup reading timer.
	const getReadingTime = () => {
		let postWordCount = 0;
		let readingTime = '';

		if ( typeof post !== 'undefined' && post !== null ) {
			postWordCount = count( post.content.raw, 'words', {} );
		}

		const readingTimeNumber = Math.ceil( postWordCount / 200 );

		if ( 'before' === timePosition ) {
			readingTime = readTimeText + ' ' + readingTimeNumber;
		} else {
			readingTime = readingTimeNumber + ' ' + readTimeText;
		}

		return readingTime;
	};

	const inspectorControls = (
		<InspectorControls>
			<PanelBody title={ __( 'Read Table', 'dwb' ) } icon="clock">
				<PanelRow>
					<SelectControl
						label="Time Position"
						labelPosition="side"
						value={ attributes.timePosition }
						options={ [
							{ label: 'Before', value: 'before' },
							{ label: 'After', value: 'after' },
						] }
						onChange={ ( newval ) =>
							setAttributes( { timePosition: newval } )
						}
					/>
				</PanelRow>
				<PanelRow>
					<InputControl
						label="Text"
						labelPosition="side"
						value={ attributes.readTimeText }
						onChange={ ( newval ) =>
							setAttributes( { readTimeText: newval } )
						}
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);

	return (
		<div>
			{ inspectorControls }
			<div className={ className }>{ getReadingTime() }</div>
		</div>
	);
}
