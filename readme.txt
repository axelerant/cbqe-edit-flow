=== Edit Flow for Custom Bulk/Quick Edit ===

Contributors: comprock, saurabhd
Donate link: http://axelerant.com/about-axelerant/donate/
Tags: custom, bulk edit, quick edit, custom post types, edit flow
Requires at least: 3.5
Tested up to: 3.9.0
Stable tag: 1.3.0RC1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Modify Edit Flow options via bulk and quick edit panels in conjunction with Custom Bulk/Quick Edit.


== Description ==

Modify Edit Flow options via bulk and quick edit panels in conjunction with Custom Bulk/Quick Edit.

= Editable Edit Flow Editorial Metadata Attributes =

* Checkbox
* Date - Uses date and picker
* Location (As input)
* Number - (As input) As int requires [Custom Bulk/Quick Edit Premium](http://axelerant.com/downloads/custom-bulkquick-edit-premium-wordpress-plugin/) 
* Paragraph (As textarea)
* Text (As input)
* User


== Installation ==

= Requirements =

* Plugin "[Custom Bulk/Quick Edit](http://wordpress.org/plugins/custom-bulkquick-edit/)" and "[Edit Flow](http://wordpress.org/plugins/edit-flow/)" are required to be installed and activated prior to activating "Edit Flow for Custom Bulk/Quick Edit".

= Install Methods =

* Through WordPress Admin > Plugins > Add New, Search for "Edit Flow for Custom Bulk/Quick Edit"
	* Find "Edit Flow for Custom Bulk/Quick Edit"
	* Click "Install Now" of "Edit Flow for Custom Bulk/Quick Edit"
* Download [`cbqe-edit-flow.zip`](http://downloads.wordpress.org/plugin/cbqe-edit-flow.zip) locally
	* Through WordPress Admin > Plugins > Add New
	* Click Upload
	* "Choose File" `cbqe-edit-flow.zip`
	* Click "Install Now"
* Download and unzip [`cbqe-edit-flow.zip`](http://downloads.wordpress.org/plugin/cbqe-edit-flow.zip) locally
	* Using FTP, upload directory `cbqe-edit-flow` to your website's `/wp-content/plugins/` directory

= Activation Options =

* Activate the "Edit Flow for Custom Bulk/Quick Edit" plugin after uploading
* Activate the "Edit Flow for Custom Bulk/Quick Edit" plugin through WordPress Admin > Plugins

= Usage =

1. Select the Edit Flow attributes to enable through WordPress Admin > Settings > Custom Bulk/Quick
1. Once you select 'Show' a configuration panel will open. Leave this blank as upon save, the proper configuration will be loaded.
1. Click "Save Changes"
1. Review and revise newly populated configuration options
1. Click "Save Changes"
1. Use edit page Bulk or Quick Edit panels as normal

= Upgrading =

* Through WordPress
	* Via WordPress Admin > Dashboard > Updates, click "Check Again"
	* Select plugins for update, click "Update Plugins"
* Using FTP
	* Download and unzip [`cbqe-edit-flow.zip`](http://downloads.wordpress.org/plugin/cbqe-edit-flow.zip) locally
	* Upload directory `cbqe-edit-flow` to your website's `/wp-content/plugins/` directory
	* Be sure to overwrite your existing `cbqe-edit-flow` folder contents


== Frequently Asked Questions ==

= Most Common Issues =

* Where is the Date picker? Buy [Custom Bulk/Quick Edit Premium](http://axelerant.com/downloads/custom-bulkquick-edit-premium-wordpress-plugin/) 
* [How do I add custom columns to my edit page?](https://nodedesk.zendesk.com/hc/en-us/articles/202330901)
* [How do you configure options?](https://nodedesk.zendesk.com/hc/en-us/articles/202331561)
* [Where can I find working samples?](https://nodedesk.zendesk.com/hc/en-us/articles/202331581)
* Got `Parse error: syntax error, unexpected T_STATIC, expecting ')'`? Read [Most Axelerant Plugins Require PHP 5.3+](https://nodedesk.zendesk.com/hc/en-us/articles/202331041) for the fixes.
* [Debug common theme and plugin conflicts](https://nodedesk.zendesk.com/hc/en-us/articles/202330781)

= Still Stuck or Want Something Done? Get Support! =

1. [Custom Bulk/Quick Edit Knowledge Base](https://nodedesk.zendesk.com/hc/en-us/sections/200861112-WordPress-FAQs) - read and comment upon frequently asked questions
1. [Open Edit Flow for Custom Bulk/Quick Edit Issues](https://github.com/michael-cannon/cbqe-edit-flow/issues) - review and submit bug reports and enhancement requests
1. [Edit Flow for Custom Bulk/Quick Edit Support on WordPress](http://wordpress.org/support/plugin/cbqe-edit-flow) - ask questions and review responses
1. [Contribute Code to Edit Flow for Custom Bulk/Quick Edit](https://github.com/michael-cannon/cbqe-edit-flow/blob/master/CONTRIBUTING.md)
1. [Beta Testers Needed](http://axelerant.com/become-beta-tester/) - get the latest Edit Flow for Custom Bulk/Quick Edit version


== Screenshots ==

1. Edit Flow field settings
2. Quick Edit of Edit Flow attributes
3. Bulk Edit of Edit Flow attributes

[gallery]

== Changelog ==

See [CHANGELOG](https://github.com/michael-cannon/cbqe-edit-flow/blob/master/CHANGELOG.md)


== Upgrade Notice ==

= 1.2.1 =

* Requires Custom Bulk/Quick Edit 1.5.1

= 1.2.0 =

* Requires Custom Bulk/Quick Edit 1.5.0

= 1.1.0 =

* Current configurations could be lost during upgrading. Please copy your Edit Flow field configuration data to someplace safe to make restoration easy. The underlying custom field key naming structure has changed to support Edit Flow 0.8.0.

= 0.0.1 =

* Initial release


== Notes ==

TBD


== API ==

* Read the [Edit Flow for Custom Bulk/Quick Edit API](https://github.com/michael-cannon/cbqe-edit-flow/blob/master/API.md).


== Localization ==

You can translate this plugin into your own language if it's not done so already. The localization file `cbqe-edit-flow.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://axelerant.com/contact-axelerant/) for plugin inclusion.

**[How do I localize?](https://nodedesk.zendesk.com/hc/en-us/articles/202294892)**


== Thank You ==

Current development by [Axelerant](http://axelerant.com/about-axelerant/).
