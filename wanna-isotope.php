<?php
/**
 * @link              http://www.wannathemes.com
 * @since             1.0.0
 * @package           Wanna_Isotope
 *
 * @wordpress-plugin
 * Plugin Name:       Wanna Isotope
 * Plugin URI:        http://wordpress.org/extend/plugins/wanna-isotope/
 * Description:       A plugin to easily build Isotope Layouts with any content.
 * Version:           1.0.4
 * Author:            jjmrestituto, wannathemes
 * Author URI:        http://www.wannathemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wanna-isotope
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wanna-isotope.php';
require plugin_dir_path( __FILE__ ) . 'public/wanna-isotope-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_wanna_isotope() {

	$plugin = new Wanna_Isotope();
	$shortcode = new Wanna_Isotope_Shortcode();
	$plugin->run();

}
run_wanna_isotope();
