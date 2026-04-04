=== OperWEB Maintenance Mode ===
Contributors: operweb
Donate link: https://www.operweb.com/contatti/
Tags: maintenance, maintenance mode, under construction, coming soon, operweb
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 2.3.3
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight plugin to display a customizable maintenance page, with your logo, colors, and custom text.

== Description ==

"OperWEB Maintenance Mode" is an essential plugin for anyone who needs to take their site offline for updates while maintaining a professional look.

Main features:
* **Simple Activation:** Enable and disable maintenance mode with a single click.
* **Settings Shortcut:** "Settings" link added directly to the plugins list page.
* **Custom Logo:** Upload your own logo. If you don't, a default maintenance icon (🛠️) is displayed.
* **Custom Text:** Write your own message using the WordPress WYSIWYG editor.
* **Custom Colors:** Customize the background and top border colors.
* **Admin Access:** Logged-in administrators see the normal site so they can continue working.
* **Admin Branding:** The settings page is branded with the OperWEB logo. The frontend (the maintenance page) is 100% clean.
* **Multilingual:** Translation-ready and includes English and Italian language files.

== Installation ==

1.  Upload the `operweb-maintenance-mode` folder to your `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Click the "Settings" link (directly under the plugin's name) or go to 'Settings' > 'Maintenance'.
4.  Configure the options: upload your logo (optional), write your text, and choose your colors.
5.  Check the "Enable Maintenance" box and save.

== Frequently Asked Questions ==

= How do I disable maintenance mode? =
Simply go back to 'Settings' > 'Maintenance', uncheck the "Enable Maintenance" box, and save.

= I see the normal site even though maintenance is active, why? =
If you are logged in as an Administrator, you will always see the normal site. Try opening your site in an "incognito" or "private" browser window to see the maintenance page.

== Screenshots ==

1. The plugin settings page, with the OperWEB brand in the footer.
2. An example of the maintenance page on the frontend with the default icon (🛠️).
3. An example of the page with a custom logo and different colors.
4. The "Settings" shortcut link on the plugins list page.

== Changelog ==

= 2.3.3 =
* Fixed all remaining WordPress code standards violations (Security & i18n).
* Refactored admin footer PHP to correctly pass translator comments.
* Removed one redundant tag (plugin tags are limited to 5).
* Ensured `maintenance.php` template uses `wp_kses_post` for all HTML output.

= 2.3.2 =
* Fixed WordPress code standards violations (Security, i18n, and Enqueued Resources).
* Removed deprecated `load_plugin_textdomain` function.
* Implemented `wp_enqueue_style` for Dashicons (the correct way).
* Added `wp_head()` and `wp_footer()` to the maintenance template.

= 2.3.1 =
* (Failed release) Attempted to fix linter errors.

= 2.3.0 =
* Added internationalization (i18n) support.
* Added English (en_US) and Italian (it_IT) language files.
* Made JavaScript strings translatable.

= 2.2.8 =
* Tested and verified for WordPress 6.8.3. Updated "Tested up to" tag.

= 2.2.7 =
* Updated author to "Fabrizio Ortis | OperWEB".

= 2.2.6 =
* Moved OperWEB logo from admin header to footer for a cleaner design.

= 2.2.5 =
* Added "Settings" shortcut link to the plugins list page.

= 2.2.4 =
* Added branded footer to the admin settings page.

= 2.2.3 =
* Fixed fatal error (missing curly brace) on activation.

= 2.2.2 =
* Removed "Powered by OperWEB" link from the frontend.
* Removed "Link Color" setting, no longer needed.

= 2.2.1 =
* Fixed fatal error for PHP < 5.4 compatibility (replaced [] with array()).

= 2.2 =
* Separated admin logo (OperWEB) from frontend logo.
* Using Dashicons (🛠️) as the new frontend default (GPL compatible).

= 2.1 =
* Added default logo option and color pickers.

= 2.0 =
* Initial version with admin panel, logo upload, and text editor.