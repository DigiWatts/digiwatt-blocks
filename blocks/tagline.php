<?php
/**
 * Register tagline block
 *
 * @package dwb
 * @since 0.1.0
 */

/**
 * Initialize tagline block.
 *
 * @return void
 */
function dwb_tagline_block_init() {
    // Skip block registration if Gutenberg is not enabled/merged.
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // automatically load dependencies and version.
    $asset_file = include DWB_ASSETS_PATH . 'build/blocks.asset.php';
    $block_slug = 'tagline';

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
        "dwb/{$block_slug}-block",
        array(
            'editor_script' => 'dwb-block-script',
            'editor_style'  => "dwb-{$block_slug}-block-editor",
            'style'         => "dwb-{$block_slug}-block-style",
        )
    );
}
add_action( 'init', 'dwb_tagline_block_init' );
