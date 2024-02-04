<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/maha-alii
 * @since             1.0.0
 * @package           Notes_Manage
 *
 * @wordpress-plugin
 * Plugin Name:       Notes Managment
 * Plugin URI:        https://wordpress.org/plugins/video-conferencing-with-bbb/
 * Description:       Manage notes on frontend with AJAX
 * Version:           1.0.0
 * Author:            Maha Ali
 * Author URI:        https://github.com/maha-alii/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       notes-manage
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NOTES_MANAGE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-notes-manage-activator.php
 */
function activate_notes_manage() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-notes-manage-activator.php';
	Notes_Manage_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-notes-manage-deactivator.php
 */
function deactivate_notes_manage() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-notes-manage-deactivator.php';
	Notes_Manage_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_notes_manage' );
register_deactivation_hook( __FILE__, 'deactivate_notes_manage' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-notes-manage.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_notes_manage() {

	$plugin = new Notes_Manage();
	$plugin->run();

}
run_notes_manage();
