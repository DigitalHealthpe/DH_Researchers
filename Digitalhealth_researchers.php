<?php
/*
Plugin Name: Digital Health - Researchers
Plugin URI: https://digitalhealth.pe
Description: Plugin that allows researchers to create tools for their personal WordPress pages.
Version: 1.0.1
Author: David Villarreal-Zegarra
Author URI: https://ipops.pe/david-villarreal-zegarra
License: GPLv2 or later
*/

// Define the plugin's directory path
if (!defined('DIGITALHEALTH_PLUGIN_PATH')) {
    define('DIGITALHEALTH_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

// Define the plugin's URL
if (!defined('DIGITALHEALTH_PLUGIN_URL')) {
    define('DIGITALHEALTH_PLUGIN_URL', plugin_dir_url(__FILE__));
}

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
