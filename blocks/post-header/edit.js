/**
 * External dependencies
 *
 */
import { get } from 'lodash';

import { __ } from '@wordpress/i18n';
import { store as coreStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { date } from '@wordpress/date';
import {
	Panel,
	PanelBody,
	PanelRow,
	RadioControl,
	Spinner,
	ToggleControl,
} from '@wordpress/components';
import {
	InspectorControls,
	PanelColorSettings,
	useBlockProps,
} from '@wordpress/block-editor';

export default function PostHeaderEdit( { attributes, setAttributes } ) {
	const {
		featuredImageSizeSlug,
		featuredImageSizeWidth,
		featuredImageSizeHeight,
		imageAlign,
		backgroundColor,
		showAuthor,
		textColor,
	} = attributes;

	const { post, postTitle, postAuthorDetails, postImage } = useSelect(
		( select ) => {
			const { getUser, getMedia, getEditedEntityRecord } =
				select( coreStore );

			const currentPostID = select( 'core/editor' ).getCurrentPostId();
			const currentPostType =
				select( 'core/editor' ).getCurrentPostType();
			const currentPost = getEditedEntityRecord(
				'postType',
				currentPostType,
				currentPostID
			); // post needs to be page?
			const title = currentPost.title;

			const authorID = getEditedEntityRecord(
				'postType',
				currentPostType,
				currentPostID
			)?.author;

			const image = getMedia( currentPost.featured_media );

			const featuredImageUrl = get(
				image,
				[
					'media_details',
					'sizes',
					featuredImageSizeSlug,
					'source_url',
				],
				null
			);

			const featuredImageAlt = image?.alt_text;
			const featuredImage = (
				<img
					src={ featuredImageUrl }
					alt={ featuredImageAlt }
					style={ {
						maxWidth: featuredImageSizeWidth,
						maxHeight: featuredImageSizeHeight,
					} }
				/>
			);

			return {
				post: currentPost,
				postTitle: title,
				postAuthorDetails: authorID ? getUser( authorID ) : null,
				postImage: featuredImageUrl ? featuredImage : null,
			};
		}
	);

	// convert post object to array - lazy, but easier.
	const postArr = Object.keys( post );

	const hasPost = !! postArr?.length;

	const onChangeTextColor = ( hexColor ) => {
		setAttributes( { textColor: hexColor } );
	};

	const onChangeBGColor = ( hexColor ) => {
		setAttributes( { backgroundColor: hexColor } );
	};

	const inspectorControls = (
		<InspectorControls>
			<Panel>
				<PanelColorSettings
					title={ __( 'Color Settings', 'dwb' ) }
					colorSettings={ [
						{
							value: textColor,
							onChange: onChangeTextColor,
							label: __( 'Text Color' ),
						},
						{
							value: backgroundColor,
							onChange: onChangeBGColor,
							label: __( 'Background Color' ),
						},
					] }
				/>
				<PanelBody title="Image Settings" initialOpen={ true }>
					<PanelRow>
						<RadioControl
							label="Alignement"
							selected={ imageAlign }
							options={ [
								{ label: 'Left', value: 'left' },
								{ label: 'Right', value: 'right' },
							] }
							onChange={ ( value ) => {
								setAttributes( { imageAlign: value } );
							} }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody title="Misc" initialOpen={ true }>
					<PanelRow>
						<ToggleControl
							label="Show Author"
							checked={ showAuthor }
							onChange={ ( value ) => {
								setAttributes( { showAuthor: value } );
							} }
						/>
					</PanelRow>
				</PanelBody>
			</Panel>
		</InspectorControls>
	);

	// get block properties and add custom ones.
	const blockProps = useBlockProps( {
		style: {
			color: textColor !== undefined ? textColor : '',
			backgroundColor:
				backgroundColor !== undefined ? backgroundColor : '',
		},
	} );

	if ( ! hasPost ) {
		return (
			<div>
				{ ! Array.isArray( post ) ? (
					<Spinner />
				) : (
					__( 'No post found.' )
				) }
			</div>
		);
	}

	// byline.
	const byline = (
		<div className="byline">
			<span className="author vcard">
				<a
					className="url fn n"
					href={ postAuthorDetails ? postAuthorDetails.link : '#' }
					rel="author"
				>
					By { postAuthorDetails ? postAuthorDetails.name : '' }
				</a>
			</span>
		</div>
	);

	return (
		<>
			{ inspectorControls }
			<header { ...blockProps }>
				<div className={ 'columns image-' + imageAlign }>
					<div className="column">
						<div className="header-content">
							<div className="title">
								<h1 className="entry-title">{ postTitle }</h1>
							</div>
							<div className="meta">
								<div className="entry-date">
									<a
										className="header-link"
										href={ post.link }
										rel="bookmark"
									>
										<time
											dateTime={ date( 'c', post.date ) }
											className="entry-date"
										>
											{ date( 'F j, Y', post.date ) }
										</time>
									</a>
								</div>

								{ showAuthor ? byline : '' }
							</div>
						</div>
					</div>
					{ postImage ? (
						<div className="column">{ postImage }</div>
					) : (
						<div className="column no-thumb"></div>
					) }
				</div>
			</header>
		</>
	);
}
