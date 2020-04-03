<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝.
 *
 * Plugin Name: Better Thumbnail Sizes
 * Plugin URI:  https://wordpress.org/plugins/better-thumbnail-sizes
 * Description: Adds a Media option to place the image sizes in a sub-directory.
 * Version:     1.1.0
 * Author:      Paul Ryley
 * Author URI:  https://profiles.wordpress.org/pryley#content-plugins
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: better-thumbnail-sizes
 * Domain Path: languages
 */
defined('WPINC') || die;

require_once __DIR__.'/autoload.php';

(new GeminiLabs\BetterThumbnailSizes\Application())->init();
