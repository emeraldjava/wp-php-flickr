<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   wp_flickr
 * @author    emeraldjava <paul.t.oconnell@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/emeraldjava/wp-php-flickr
 * @copyright 2013 emeraldjava
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here