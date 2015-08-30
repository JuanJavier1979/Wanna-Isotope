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
 * Version:           1.0.0
 * Author:            jjmrestituto, wannathemes
 * Author URI:        http://www.wannathemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wanna-isotope
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wanna-isotope-activator.php
 */
/*function activate_wanna_isotope() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wanna-isotope-activator.php';
	Wanna_Isotope_Activator::activate();
}*/

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wanna-isotope-deactivator.php
 */
/*function deactivate_wanna_isotope() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wanna-isotope-deactivator.php';
	Wanna_Isotope_Deactivator::deactivate();
}*/

//register_activation_hook( __FILE__, 'activate_wanna_isotope' );
//register_deactivation_hook( __FILE__, 'deactivate_wanna_isotope' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wanna-isotope.php';
require plugin_dir_path( __FILE__ ) . 'public/wanna-isotope-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wanna_isotope() {

	$plugin = new Wanna_Isotope();
	$shortcode = new Wanna_Isotope_Shortcode();
	$plugin->run();

}
run_wanna_isotope();
