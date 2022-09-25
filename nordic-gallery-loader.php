<?php use NordicGLoader\NordicGLoaderPlugin;

/**
 *
 * Plugin Name:       Nordic Gallery Loader
 * Plugin URI:        https://github.com/Vladimir988/Nordic-Gallery-Loader
 * Description:
 * Version:           1.1
 * Author:            Vladimir
 * Author URI:        https://github.com/Vladimir988
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nordic-gallery-loader
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

call_user_func(function () {
    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

    $main = new NordicGLoaderPlugin(__FILE__);

    register_activation_hook(__FILE__, [$main, 'activate']);

    register_deactivation_hook(__FILE__, [$main, 'deactivate']);

    register_uninstall_hook(__FILE__, [NordicGLoaderPlugin::class, 'uninstall']);
});