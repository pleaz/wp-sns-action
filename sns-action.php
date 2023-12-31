<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://google.com
 * @since             1.0.0
 * @package           Sns_Action
 *
 * @wordpress-plugin
 * Plugin Name:       SnsAction
 * Plugin URI:        https://google.com
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            pleaz
 * Author URI:        https://google.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sns-action
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SNS_ACTION_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sns-action-activator.php
 */
function activate_sns_action()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sns-action-activator.php';
    Sns_Action_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sns-action-deactivator.php
 */
function deactivate_sns_action()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sns-action-deactivator.php';
    Sns_Action_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_sns_action');
register_deactivation_hook(__FILE__, 'deactivate_sns_action');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-sns-action.php';

/**
 * Libs
 */
require plugin_dir_path(__FILE__) . 'vendor/autoload.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sns_action()
{
    $plugin = new Sns_Action();
    $plugin->run();
}
run_sns_action();
