=== Plugin Name ===
Contributors: jjmrestituto, wannathemes
Donate link: https://www.paypal.me/wannathemes/5
Tags: isotope, masonry, filter, grid, layout, shortcode
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin to easily build Isotope/Masonry layouts with any content (posts, pages or custom post types). Responsive grids, filterable content.

== Description ==

A plugin to easily build Isotope/Masonry layouts with any content (posts, pages or custom post types). Responsive grids, filterable content.

= Shortode parameters =
**type** 

* example: *type="portfolio"* 
* default: *post*
* Display content based on posts, pages or custom post types.

**items**
 
* example: *items="12"*
* default: *4*
* Number of items to show.

**tax** 

* example: *tax="category"*
* default: *none*
* Show a filter based on taxonomy parameter.

**term** 

* example: *term="category-slug"*
* default: *none*
* Show a filter based on a term parameter and only show items based on the *term*.
* **REQUIRED:** use the *tax* parameter.

**order_by**
 
* example: *order_by="slug"*
* default: *menu_order*
* Sort retrieved posts by parameter.

**order**

* example: *order="DESC"*
* default: *ASC*
* Designates the ascending or descending order of the 'orderby' parameter.

**id**

* example: *id="my_custom_grid"*
* default: *none*
* set a custom id so you can style or target better.

== Installation ==

1. Upload `wanna-isotope` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place [isotope] shortcode in your content.

== Frequently Asked Questions ==

= How can I insert a grid in my content? =

You just need to use the shortcode [isotope] in your content, with some parameters you will find in the documentation you will be able to select how your grid works, what content will be displayed, etc.

= Will the plugin be updated? =

Of course, we are keen to upgrade the plugin, introduce new features. So if you want to make suggestions or want to contribute you are welcome.

== Screenshots ==
1. Wanna Isotope Grid

== Changelog ==

= 1.0.0 =
* Initial Realease.

= 1.0.1 =
* PHP Notices fixed.

= 1.0.2 =
* Imagesloaded Added

= 1.0.3 =
* Term parameter Added

= 1.0.4 =
* Re-structured some code.
* Sanitized outputs added.
* Templating system added.
