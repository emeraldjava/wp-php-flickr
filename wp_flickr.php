<?php
/**
 * wp_flickr
 *
 * PHP Flickr for Wordpress
 *
 * @package   wp_flickr
 * @author    emeraldjava <paul.t.oconnell@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/emeraldjava/wp_flickr
 * @copyright 2013 emeraldjava
 *
 * @wordpress-plugin
 * Plugin Name:       wp_flickr
 * Plugin URI:        https://github.com/emeraldjava/wp_flickr
 * Description:       PHP Flickr for Wordpress
 * Version:           0.0.2
 * Author:            emeraldjava
 * Author URI:        
 * Text Domain:       wp_flickr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/emeraldjava/wp_flickr
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-wp_flickr.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'wp_flickr', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'wp_flickr', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'wp_flickr', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * TODO:
 *
 * - replace `class-plugin-admin.php` with the name of the plugin's admin file
 * - replace Plugin_Name_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wp_flickr-admin.php' );
	add_action( 'plugins_loaded', array( 'wp_flickr_Admin', 'get_instance' ) );

}
