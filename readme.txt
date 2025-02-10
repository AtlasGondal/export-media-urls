=== Export Media URLs ===
Contributors: Atlas_Gondal, waqasgondal
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YWT3BFURG6SGS&source=url
Tags: export media urls, media links, export utilities, export, csv
Requires at least: 3.1
Tested up to: 6.7.1
Stable tag: 2.2
Requires PHP: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An efficient media information extraction utility with CSV export option, suitable for several use-cases including migration and SEO.

== Description ==

The ultimate solution for seamlessly managing and extracting information from your media library. This user-friendly plugin simplifies the task of gathering essential details like title, date, caption, alt-text, description and type of media file. It facilitates the generation of CSV output or allows you to conveniently view URLs directly within the dashboard, proving invaluable for tasks such as migration, SEO analysis, and security audits. 

You can export Media's:

* ID
* Title
* File Name
* File Size
* Caption
* Alt Text
* Description
* URL
* Date Uploaded
* And its Type

The data can be filtered by Author, or between selected date range before extraction.

== When we need this plugin? ==

* To check Media URLs of your website
* During migration
* During security audit
* To remove demo images, imported by theme

You'll be surprised to know that, there exist some media, which you never know off or maybe it is imported by theme demo. But don't worry, you'll be able to find those URLs with the help of this small utility and perform the cleanup. :)

= System requirements =

* PHP version 5.4 or higher
* Wordpress version 3.1.0 or higher


= Feedback =

If you like this plugin, then please consider leaving us a good [rating](https://wordpress.org/support/plugin/export-media-urls/reviews/?filter=5#new-post).

= Contact =

For further information please send me an [email](https://AtlasGondal.com/contact-me/?utm_source=self&utm_medium=wp&utm_campaign=export-media-urls&utm_term=plugin-description).

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'Export Media URLs'
3. Activate Export Media URLs from your Plugins page.

= From WordPress.org =

1. Download Export Media URLs.
2. Unzip plugin.
2. Upload the 'Export All URLs' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate Export Media URLs from your Plugins page.

= Usage =

1. Go to Tools > Export Media URLs to export media URLs of your website.
2. Choose Data (e.g Media ID, Title, URLs, Date, Type)
3. Filter by Author, if needs to
4. Select Export type (dashboard or csv)
5. Finally, Click on Export Now.

= Uninstalling: =

1. In the Admin Panel, go to "Plugins" and deactivate the plugin.
2. Go to the "plugins" folder of your WordPress directory and delete the files/folder for this plugin.


== Frequently Asked Questions ==

= About Plugin Support? =

Post your question on support forum and we will try to answer your question as quick as possible.

= Why did you make this plugin?  =

We couldn't find a plugin that would export media URLs, along with additional data such as name, date and type. So, we make this utility, and it works just like that.

= In what scenarios can this plugin be beneficial? =

The plugin proves to be extremely useful during tasks such as website migration, SEO analysis, and security audits. It simplifies the process of managing and understanding your media assets, providing valuable insights.

= Can I export the extracted data in a specific format? = 

Yes, the plugin supports exporting data in CSV format, offering a structured and easily readable output that can be utilized for further analysis or documentation.

= Why the file name is randomly generated?  =

Exporting the file with static name can be easily found by malicious attacker, and may result in sensitive information leakage. So we decided to generate random name, which are harder to guess.

= Can I view and manage URLs directly within the WordPress dashboard? =

Yes, the plugin allows you to conveniently view and manage extracted URLs directly within the WordPress dashboard, providing a seamless and centralized experience.

= Is it user-friendly for individuals with minimal technical knowledge? =

Absolutely! This plugin is designed with user-friendliness in mind. Its intuitive interface ensures that users, regardless of their technical expertise, can efficiently navigate and utilize its features.

= Is there a limit to the number of URLs that can be processed by the plugin? =

The plugin's capacity to handle URLs depends on your server configuration and resources. For average website, this is not a big concern. And if you ran into any issue, try increasing server resource or reach out to support and we'll be happy to help.

= Does Export Media URLs make changes to the database? =

No. It has no settings / configurations to store so it does not touch the database.

= Is this plugin compatible with the latest version of WordPress? =

The plugin is designed to be compatible with the latest WordPress versions. Regular updates ensure that it remains functional and aligned with the evolving WordPress environment.

= Which PHP version do I need? =

This plugin has been tested and works with PHP versions 5.4 and greater. WordPress itself [recommends using PHP version 7.4 or greater](https://wordpress.org/about/requirements/). If you're using a PHP version lower than 5.4 please upgrade your PHP version or contact your Server administrator.

= Are there any server requirements? =

Yes. The plugin requires a PHP version 5.4 or higher and Wordpress version 3.1.0 or higher.

== Screenshots ==

1. Admin screenshot of Export Media URLs
2. Exported data in the dashboard
3. Exported data to a CSV file
4. Test run on Wordpress 3.1

== Changelog ==

= 2.2 = 
* Added - additional file size data field
* Improvement - preserves the previously selected values
* Compatibility - tested with wordpress 6.7.1

= 2.1 = 
* Improvement - author filtering is simplified
* Compatibility - tested with wordpress 6.4.3

= 2.0 =
* Added - additional data fields (file name, caption, alt-text, description)
* Added - enables user to delete the file once downloaded
* Added - support for the translation
* Fixed - patched a security vulnerability
* Improvement - a few code refinements and validation checks
* Compatibility - tested with wordpress 6.4.1 & PHP 8.2.0

= 2.0 =
* Added - additional data fields (file name, caption, alt-text, description)
* Added - enables user to delete the file once downloaded
* Added - support for the translation
* Fixed - patched a security vulnerability
* Improvement - a few code refinements and validation checks
* Compatibility - tested with wordpress 6.4.1 & PHP 8.2.0

= 1.0 =

* initial release

== Upgrade Notice ==

= 2.2 = 
* Added - additional file size data field
* Improvement - preserves the previously selected values
* Compatibility - tested with wordpress 6.7.1
