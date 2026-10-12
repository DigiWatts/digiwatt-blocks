<?php
/**
 * Plugin Name: DigiWatt Blocks
 * Plugin URI:
 * Description: A collection of Gutenberg Blocks for more awesomeness.
 * Author: Erik Mitchell
 * Author URI: https://erikmitchell.net
 * Text Domain: dwb
 * Domain Path: /languages
 * x-release-please-start-version
 * Version: 0.3.0
 * x-release-please-end
 *
 * @package dwb
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! defined( 'DWB_PLUGIN_FILE' ) ) {
    define( 'DWB_PLUGIN_FILE', __FILE__ );
}

// Check GitHub releases for plugin updates.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';

    $dwb_update_checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
        'https://github.com/DigiWatts/digiwatt-blocks/',
        __FILE__,
        'digiwatt-blocks'
    );
    // Install the digiwatt-blocks.zip attached to each release, not GitHub's source zipball.
    $dwb_update_checker->getVcsApi()->enableReleaseAssets();
}

// Include the main DWB class.
if ( ! class_exists( 'DWB' ) ) {
    include_once __DIR__ . '/includes/class-dwb.php';
}
