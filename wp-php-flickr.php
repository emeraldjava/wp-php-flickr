<?php
/**
 * @package   Wp_Php_Flickr
 * @author    emeraldjava <paul.t.oconnell@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/emeraldjava/wp-php-flickr
 * @copyright 2013 emeraldjava
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress PHP Flickr
 * Plugin URI:        https://github.com/emeraldjava/wp-php-flickr
 * Description:       Wraps the php flickr class within a wordpress plugin
 * Version:           0.0.16
 * Author:            emeraldjava
 * Author URI:        https://github.com/emeraldjava
 * Text Domain:       wp-php-flickr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/emeraldjava/wp-php-flickr
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-wp-php-flickr.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Wp_Php_Flickr', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Wp_Php_Flickr', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Wp_Php_Flickr', 'get_instance' ) );

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

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wp-php-flickr-admin.php' );
	add_action( 'plugins_loaded', array( 'Wp_Php_Flickr_Admin', 'get_instance' ) );
}
