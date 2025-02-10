<?php

/**
 * Plugin Name: Export Media URLs
 * Plugin URI:  https://wordpress.org/plugins/export-media-urls/
 * Description: This plugin allows you to extract all URLs of your media, along with title, date, and type. It supports writing output in CSV file, or you can view URLs within the dashboard. It can be very useful during migration, seo analysis and security audit.
 * Version:     2.2
 * Author:      Atlas Gondal
 * Author URI:  https://AtlasGondal.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: export-media-urls
 *
 * @package Export Media URLs
 */

/*
    Copyright (c) 2020- Atlas Gondal (contact : https://atlasgondal.com/contact-me/)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

 */

if (!defined('WPINC')) {
    die;
}

define('Export_Media_URLs__FILE__', __FILE__);

add_action('plugins_loaded', 'export_media_urls_load_textdomain');

function activate_export_media_urls()
{
    if (version_compare(PHP_VERSION, '5.4', '<')) {
        deactivate_plugins(plugin_basename(Export_Media_URLs__FILE__));
        $plugin_data = get_plugin_data(Export_Media_URLs__FILE__);
        $plugin_version = $plugin_data['Version'];
        $plugin_name = $plugin_data['Name'];
        wp_die('<h1>' . __('Could not activate plugin: PHP version error') . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('You are using PHP version') . ' ' . PHP_VERSION . '</strong>. ' . __('This plugin has been tested with PHP versions 5.4 and greater.') . '</p><p>' . __('WordPress itself recommends using PHP version 7.3 or greater') . ': <a href="https://wordpress.org/about/requirements/" target="_blank">' . __('Official WordPress requirements') . '</a>' . '. ' . __('Please upgrade your PHP version or contact your Server administrator.') . '</p>', __('Could not activate plugin: PHP version error'), array('back_link' => true));
    }
    set_transient('export_media_urls_activation_redirect', true, 30);
}

register_activation_hook(__FILE__, 'activate_export_media_urls');


function export_media_urls_load_textdomain()
{
    load_plugin_textdomain('export-media-urls');
}

if (!class_exists('ExportMediaURLsAdmin')) {
    require_once dirname(Export_Media_URLs__FILE__) . '/classes/class-export-media-urls-admin.php';
}
