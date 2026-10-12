import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

registerBlockType( 'dwb/about-block', {
	title: __( 'About', 'dwb' ),
	icon: 'editor-quote',
	category: 'text',
	attributes: {
		text: {
			type: 'array',
			source: 'children',
			selector: 'p',
		},
		mediaID: {
			type: 'number',
		},
		mediaURL: {
			type: 'string',
		},
		align: {
			type: 'string',
		},
	},
	supports: {
		align: [ 'wide', 'full' ],
	},
	edit: ( props ) => {
		const {
			className,
			attributes: { text, mediaID, mediaURL },
			setAttributes,
		} = props;

		const onChangeText = ( value ) => {
			setAttributes( { text: value } );
		};

		const onSelectImage = ( media ) => {
			setAttributes( {
				mediaURL: media.url,
				mediaID: media.id,
			} );
		};

		const removeMedia = () => {
			setAttributes( {
				mediaID: 0,
				mediaURL: '',
			} );
		};

		const blockStyle = {
			background:
				mediaURL !== ''
					? 'url("' + mediaURL + '") no-repeat center center fixed'
					: 'none',
		};

		return (
			<>
				<div className={ className }>
					<div className="image-wrap" style={ blockStyle }>
						<MediaUploadCheck>
							<MediaUpload
								onSelect={ onSelectImage }
								allowedTypes="image"
								value={ mediaID }
								render={ ( { open } ) => (
									<Button
										className={
											mediaID
												? 'image-button'
												: 'button button-large'
										}
										onClick={ open }
									>
										{ ! mediaID
											? __( 'Upload Image', 'dwb' )
											: '' }
									</Button>
								) }
							/>
						</MediaUploadCheck>

						{ !! mediaID && (
							<MediaUploadCheck>
								<MediaUpload
									title={ __( 'Replace image', 'awp' ) }
									value={ mediaID }
									onSelect={ onSelectImage }
									allowedTypes={ [ 'image' ] }
									render={ ( { open } ) => (
										<Button
											onClick={ open }
											isSecondary
											className="replace-image"
										>
											{ __( 'Replace image', 'dwb' ) }
										</Button>
									) }
								/>
							</MediaUploadCheck>
						) }

						{ !! mediaID && (
							<MediaUploadCheck>
								<Button
									onClick={ removeMedia }
									isLink
									isDestructive
									className="remove-image"
								>
									{ __( 'Remove image', 'dwb' ) }
								</Button>
							</MediaUploadCheck>
						) }
					</div>

					<div className="about-text-wrap">
						<div className="text-inner">
							<RichText
								tagName="p"
								placeholder={ __( 'Sample text', 'dwb' ) }
								value={ text }
								onChange={ onChangeText }
							/>
						</div>
					</div>
				</div>
			</>
		);
	},
	save: ( props ) => {
		const {
			className,
			attributes: { text, mediaURL },
		} = props;

		const blockStyle = {
			background:
				mediaURL !== ''
					? 'url("' + mediaURL + '") no-repeat center center fixed'
					: 'none',
		};

		return (
			<div className={ className }>
				<div className="image-wrap" style={ blockStyle }></div>
				<div className="about-text-wrap">
					<div className="text-inner">
						<RichText.Content tagName="p" value={ text } />
					</div>
				</div>
			</div>
		);
	},
} );
