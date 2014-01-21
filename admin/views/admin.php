<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   wp-php-flickr
 * @author    emeraldjava <paul.t.oconnell@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/emeraldjava/wp-php-flickr
 * @copyright 2013 emeraldjava
 */
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<!-- TODO: Provide markup for your options page here. -->
   	<form method="post" action="options.php">
   	    <?php settings_fields( 'wp_php_flickr' ); ?>
   	    <?php do_settings_sections( 'wp_php_flickr' ); ?>
       	    <table class="form-table">
       	        <tr valign="top">
       	        <th scope="row">wp_php_flickr_username</th>
       	        <td><input type="text" name="wp_php_flickr_username" value="<?php echo get_option('wp_php_flickr_username'); ?>" /></td>
       	        </tr>
       	        <tr valign="top">
       	        <th scope="row">wp_php_flickr_user_id</th>
       	        <td><input type="text" name="wp_php_flickr_user_id" value="<?php echo get_option('wp_php_flickr_user_id'); ?>" /></td>
       	        </tr>
       	        <tr valign="top">
       	        <th scope="row">wp_php_flickr_api_key</th>
       	        <td><input type="text" name="wp_php_flickr_api_key" size=40 value="<?php echo get_option('wp_php_flickr_api_key'); ?>" /></td>
       	        </tr>
       	        <tr valign="top">
       	        <th scope="row">wp_php_flickr_secret</th>
       	        <td><input type="text" name="wp_php_flickr_secret" value="<?php echo get_option('wp_php_flickr_secret'); ?>" /></td>
       	        </tr>
       	    </table>
   	    <?php submit_button(); ?>
   	</form>
</div>