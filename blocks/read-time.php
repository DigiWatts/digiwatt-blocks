<?php
/**
 * Register Read Time block
 *
 * @package dwb
 * @since 0.2.0
 */

/**
 * Register Read Time block.
 *
 * @access public
 * @return void
 */
function dwb_read_time_block_init() {
    // Skip block registration if Gutenberg is not enabled/merged.
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // automatically load dependencies and version.
    $asset_file = include DWB_ASSETS_PATH . 'build/blocks.asset.php';
    $block_slug = 'read-time';

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

    register_block_type(
        "dwb/{$block_slug}",
        array(
            'attributes'      => array(
                'readTimeText' => array(
                    'type'    => 'string',
                    'default' => 'Minute Read',
                ),
                'timePosition' => array(
                    'type'    => 'string',
                    'default' => 'after',
                ),
            ),
            'render_callback' => 'render_block_digiwatt_read_time',
            'editor_script'   => 'dwb-block-script',
            'editor_style'    => "dwb-{$block_slug}-block-editor",
            'style'           => "dwb-{$block_slug}-block-style",
        )
    );
}
add_action( 'init', 'dwb_read_time_block_init' );

/**
 * Render the Read Time block.
 *
 * @access public
 * @param mixed $attributes (array).
 * @return html
 */
function render_block_digiwatt_read_time( $attributes ) {
    $reading_time_text = dwb_reading_time( $attributes );

    $class = 'wp-block-dwb-read-time-block';

    $wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $class ) );

    return sprintf(
        '<div %1$s>%2$s</div>',
        $wrapper_attributes,
        $reading_time_text
    );
}
