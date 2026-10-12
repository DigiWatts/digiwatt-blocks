<?php
/**
 * Register Post Header block
 *
 * @package dwb
 * @since 0.2.0
 */

/**
 * Register Post Header block.
 *
 * @access public
 * @return void
 */
function dwb_post_header_block_init() {
    // Skip block registration if Gutenberg is not enabled/merged.
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // automatically load dependencies and version.
    $asset_file = include DWB_ASSETS_PATH . 'build/blocks.asset.php';
    $block_slug = 'post-header';

    register_block_type(
        "dwb/{$block_slug}",
        array(
            'attributes'      => array(
                'align'                   => array(
                    'type'    => 'string',
                    'default' => 'full',
                ),
                'featuredImageSizeSlug'   => array(
                    'type'    => 'string',
                    'default' => 'single',
                ),
                'featuredImageSizeWidth'  => array(
                    'type'    => 'number',
                    'default' => null,
                ),
                'featuredImageSizeHeight' => array(
                    'type'    => 'number',
                    'default' => null,
                ),
                'imageAlign'              => array(
                    'type'    => 'string',
                    'default' => 'right',
                ),
                'backgroundColor'         => array(
                    'type' => 'string',
                ),
                'showAuthor'              => array(
                    'type'    => 'boolean',
                    'default' => true,
                ),
                'textColor'               => array(
                    'type' => 'string',
                ),
            ),
            'api_version'     => 2,
            'render_callback' => 'render_block_digiwatt_post_header',
            'editor_script'   => 'dwb-block-script',
            'editor_style'    => "dwb-{$block_slug}-block-editor",
            'style'           => "dwb-{$block_slug}-block-style",
        )
    );

    wp_register_script(
        'dwb-block-script',
        DWB_ASSETS_URL . 'build/blocks.js',
        $asset_file['dependencies'],
        $asset_file['version'],
        true
    );

    $editor_css = 'editor.css';
    wp_register_style(
        "dwb-{$block_slug}-block-editor",
        DWB_ABSURL . "blocks/{$block_slug}/{$editor_css}",
        array(),
        filemtime( DWB_ABSPATH . "blocks/{$block_slug}/{$editor_css}" )
    );

    $style_css = 'style.css';
    wp_register_style(
        "dwb-{$block_slug}-block-style",
        DWB_ABSURL . "blocks/{$block_slug}/{$style_css}",
        array(),
        filemtime( DWB_ABSPATH . "blocks/{$block_slug}/{$style_css}" )
    );
}
add_action( 'init', 'dwb_post_header_block_init' );

/**
 * Render the Post Header block.
 *
 * @access public
 * @param mixed $attributes (array).
 * @return html
 */
function render_block_digiwatt_post_header( $attributes ) {
    global $post;

    $html                  = '';
    $header_content_styles = '';
    $header_content_style  = array();
    $no_thumb_style        = '';
    $align_image           = '';

    if ( ! empty( $attributes['textColor'] ) ) {
        $header_content_style['color'] = $attributes['textColor'];
    }

    if ( ! empty( $attributes['backgroundColor'] ) ) {
        $header_content_style['background-color'] = $attributes['backgroundColor'];
    }

    if ( ! has_post_thumbnail() && ! empty( $attributes['backgroundColor'] ) ) {
        $no_thumb_style = 'style="background:' . $attributes['backgroundColor'] . '; height:400px;">';
    }

    if ( ! empty( $attributes['align'] ) ) {
        $align_image = ' align' . $attributes['align'];
    }

    $header_content_styles = implode(
        ' ',
        array_map(
            function ( $v, $k ) {
                return $k . ':' . $v . ';';
            },
            $header_content_style,
            array_keys( $header_content_style )
        )
    );

    $html                 .= '<div class="columns image-' . $attributes['imageAlign'] . '">';
        $html             .= '<div class="column">';
            $html         .= '<div class="header-content" style="' . $header_content_styles . '">';
                $html     .= '<div class="title">';
                    $html .= '<h1 class="entry-title">' . get_the_title( $post ) . '</h1>';
                $html     .= '</div>';
                $html     .= '<div class="meta">';
                    $html .= get_dwb_post_header_posted_on( $attributes['showAuthor'] );
                $html     .= '</div>';
            $html         .= '</div>';
        $html             .= '</div>';

    if ( has_post_thumbnail() ) :
        $html     .= '<div class="column column-image">';
            $html .= '<div class="post-thumbnail">' . get_dwb_post_header_post_thumbnail( $attributes['featuredImageSizeSlug'], $post->ID ) . '</div>';
        $html     .= '</div>';
        else :
            $html .= '<div class="column column-image no-thumb" ' . $no_thumb_style . '></div>'; // force style here.
        endif;
        $html .= '</div>';

        $wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $align_image ) );

        return sprintf(
            '<header %1$s>%2$s</header>',
            $wrapper_attributes,
            $html
        );
}

/**
 * Get post date and author.
 *
 * @access public
 * @param bool $show_author byline (default: true).
 * @return html
 */
function get_dwb_post_header_posted_on( $show_author = true ) {
    $html = '';

    $html .= wp_kses_post( '<div class="entry-date"><a href="' . get_permalink() . '" rel="bookmark"><time class="entry-date" datetime="' . get_the_date( 'c' ) . '">' . get_the_date() . '</time></a></div>' );

    if ( $show_author ) {
        $html .= wp_kses_post( '<div class="byline"><span class="author vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" rel="author">By ' . get_the_author() . '</a></span></div>' );
    }

    return $html;
}

/**
 * Get the thumbnail.
 *
 * @access public
 * @param string $size (default: 'full').
 * @param int    $post_id (default: 0).
 * @return html
 */
function get_dwb_post_header_post_thumbnail( $size = 'full', $post_id = 0 ) {
    $thumb = '';

    if ( has_post_thumbnail( $post_id ) ) {
        $thumb_id      = get_post_thumbnail_id( $post_id );
        $thumb_src_url = wp_get_attachment_image_url( $thumb_id, $size );
        $thumb_meta    = wp_get_attachment_metadata( $thumb_id );
        $thumb_base    = '<img src="' . $thumb_src_url . '" class="img-responsive" />';
        $thumb         = wp_image_add_srcset_and_sizes( $thumb_base, $thumb_meta, $thumb_id );
    }

    return wp_kses_post( $thumb );
}
