<?php
/*
Plugin Name: Digital Health - Researchers
Plugin URI: https://research.digitalhealth.pe/
Description: Plugin that allows researchers to create tools for their personal WordPress pages. Includes features such as PubMed integration and metrics visualization (Altmetric, Dimensions, Scite.ai).
Version: 1.0.1
Author: David Villarreal-Zegarra
Author URI: https://ipops.pe/david-villarreal-zegarra
License: GPLv2 or later
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define the plugin's directory path
if (!defined('DIGITALHEALTH_PLUGIN_PATH')) {
    define('DIGITALHEALTH_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

// Define the plugin's URL
if (!defined('DIGITALHEALTH_PLUGIN_URL')) {
    define('DIGITALHEALTH_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Enqueue styles and scripts for the plugin
function digitalhealth_enqueue_assets() {
    // Enqueue plugin styles
    wp_enqueue_style('digitalhealth-styles', DIGITALHEALTH_PLUGIN_URL . 'assets/css/style.css');

    // Enqueue plugin scripts
    wp_enqueue_script('digitalhealth-scripts', DIGITALHEALTH_PLUGIN_URL . 'assets/js/main.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'digitalhealth_enqueue_assets');

// Include the menu file safely
if (file_exists(DIGITALHEALTH_PLUGIN_PATH . 'includes/menu.php')) {
    require_once DIGITALHEALTH_PLUGIN_PATH . 'includes/menu.php';
} else {
    error_log('Error: menu.php not found in includes folder.');
}

// Include the paper file safely
if (file_exists(DIGITALHEALTH_PLUGIN_PATH . 'includes/paper.php')) {
    require_once DIGITALHEALTH_PLUGIN_PATH . 'includes/paper.php';
} else {
    error_log('Error: paper.php not found in includes folder.');
}

// Add a settings link to the plugin on the Plugins page
function digitalhealth_settings_link($links) {
    $settings_link = '<a href="admin.php?page=digitalhealth-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'digitalhealth_settings_link');

// Activation hook
function digitalhealth_activate() {
    // Add default options or setup tasks if necessary
    if (!get_option('digitalhealth_options')) {
        add_option('digitalhealth_options', array(
            'api_key' => '',
            'default_author' => '',
        ));
    }
}
register_activation_hook(__FILE__, 'digitalhealth_activate');

// Deactivation hook
function digitalhealth_deactivate() {
    // Cleanup tasks if necessary
    // Uncomment the following line if you want to remove plugin options on deactivation
    // delete_option('digitalhealth_options');
}
register_deactivation_hook(__FILE__, 'digitalhealth_deactivate');

// Uninstallation hook
register_uninstall_hook(__FILE__, 'digitalhealth_uninstall');
function digitalhealth_uninstall() {
    // Cleanup all plugin data when the plugin is uninstalled
    delete_option('digitalhealth_options');
}
