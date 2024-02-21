<?php

namespace Export_Media_URLs;

defined('ABSPATH') || exit;

class Constants
{
    public const PLUGIN_NAME = 'Export Media URLs';
    public const PLUGIN_VERSION = '2.1';
    public const PLUGIN_SLUG = 'export-media-urls';
    public const PLUGIN_FILE = 'export-media-urls/export-media-urls.php';
    public const PLUGIN_DIR = 'export-media-urls';
    public const PLUGIN_URL = 'https://wordpress.org/plugins/export-media-urls/';
    public const PLUGIN_AUTHOR = 'Atlas Gondal';
    public const PLUGIN_AUTHOR_URI = 'https://AtlasGondal.com/';
    public const PLUGIN_LICENSE = 'GPL v2 or higher';
    public const PLUGIN_LICENSE_URI = 'http://www.gnu.org/licenses/gpl-2.0.html';
    public const PLUGIN_TEXT_DOMAIN = 'export-media-urls';
    public const PLUGIN_SETTINGS_PAGE_CAPABILITY = 'manage_options';
    public const PLUGIN_SETTINGS_PAGE_SLUG = 'export-media-urls-settings';
    public const PLUGIN_HOOK_SUFFIX = 'tools_page_export-media-urls-settings';
}
