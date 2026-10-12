import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	MediaUpload,
	InspectorControls,
	ColorPalette,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

registerBlockType( 'dwb/tagline-block', {
	title: __( 'Tagline', 'dwb' ),
	icon: 'format-status',
	category: 'text',
	attributes: {
		title: {
			type: 'array',
			source: 'children',
			selector: 'h1',
		},
		mediaID: {
			type: 'number',
		},
		mediaURL: {
			type: 'string',
			source: 'attribute',
			selector: 'img',
			attribute: 'src',
		},
		bg_color: {
			type: 'string',
			default: '#8ed2fc',
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
			attributes: { title, mediaID, mediaURL, bg_color: bgColor },
			setAttributes,
		} = props;

		const onChangeTitle = ( value ) => {
			setAttributes( { title: value } );
		};

		const onSelectImage = ( media ) => {
			setAttributes( {
				mediaURL: media.url,
				mediaID: media.id,
			} );
		};

		const onChangeBGColor = ( hexColor ) => {
			setAttributes( { bg_color: hexColor } );
		};

		return (
			<>
				<InspectorControls key="setting">
					<div id="digiwatt-tagline-controls">
						<fieldset>
							<legend className="blocks-base-control__label">
								{ __( 'Background color', 'gutenpride' ) }
							</legend>
							<ColorPalette // Element Tag for Gutenberg standard colour selector
								onChange={ onChangeBGColor }
							/>
						</fieldset>
					</div>
				</InspectorControls>

				<div
					className={ className }
					style={ { backgroundColor: bgColor } }
				>
					<div className="tagline-wrapper">
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
									{ ! mediaID ? (
										__( 'Upload Image', 'dwb' )
									) : (
										<div className="image-wrapper">
											<img
												className="tagline-image"
												src={ mediaURL }
												alt={ __(
													'tagline image',
													'dwb'
												) }
											/>
										</div>
									) }
								</Button>
							) }
						/>
						<div className="title-wrap">
							<RichText
								tagName="h1"
								placeholder={ __( 'Tagline', 'dwb' ) }
								value={ title }
								onChange={ onChangeTitle }
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
			attributes: { title, mediaURL, bg_color: bgColor },
		} = props;
		return (
			<div className={ className } style={ { backgroundColor: bgColor } }>
				<div className="tagline-wrapper">
					{ mediaURL && (
						<div className="image-wrapper">
							<img
								className="tagline-image"
								src={ mediaURL }
								alt={ __( 'tagline image', 'dwb' ) }
							/>
						</div>
					) }
					<div className="title-wrap">
						<RichText.Content tagName="h1" value={ title } />
					</div>
				</div>
			</div>
		);
	},
} );
