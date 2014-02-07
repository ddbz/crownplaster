=== Plugin Name ===

Contributors:      flippercode
Plugin Name:       Amazon S3 Photo Gallery
Plugin URI:        http://wordpress.org/extend/plugins/amazon-s3-photo-gallery/
Tags:              amazon s3, amazon photo, amazon gallery,amazon s3 storage
Author URI:        http://profiles.wordpress.org/flippercode/
Author:            flippercode
Donate link:       (a link for donating)
Requires at least: 2.0.0 
Tested up to:      3.3.1
Stable tag:        Use amazon s3 buckets for your photo gallery

== Description ==
This WordPress plugin allows you to use Amazon's Simple Storage Service to host your images for your Photo Gallery.

This plugin for WordPress works with your amazon s3 storage account. The plugin allows you to create albums and each albums points to a bucket. insert all albums or individual album into your posts or pages using a shortcode. 

Usage:

Once activated, make sure to configure the plugin in the settings menu with your amazon secret and key.

Notes:

This plugin for WordPress, the publicly available  If you experience any problems with the plugin, feel free to contact me.

== Installation ==
This section describes how to install the plugin and get it working.
	
	1.	Upload the amazon-s3-photo-gallery directory to the /wp-content/plugins/ directory

	2.	Activate the plugin through the 'Plugins' menu in WordPress

	3.	Enter your API credentials in the 'Settings'  menu in WordPress

== Upgrade Notice ==
In version 1.0.1, shortcode bug and pagination css updated
In version 1.0.2, removed permission error. add ability to add caption on photos.
== Screenshots ==

1. Screenshot Album Creation 
2. Screenshot Amazon S3 Settings

== Changelog ==

1. Shortcode Bug removed.
2. Pagniation css updated.

== Frequently Asked Questions ==

= Can i create unlimited albums? = 

Yes, you can create, edit or delete any albumb. Don't worry, those images will remain in your bucket.

= Can i display  a individual album on any post or page ? = 

Yes, just pass the id of the album in short code [amazons3_photo_gallery id={ALBUM_ID_HERE}]

= Can i display all albums on any post or page ? = 

Yes, just use short code [amazons3_photo_gallery]

= Can i update photos in my bucket using this plugin? = 

Yes, it's awesome. even you can add alt text to your image. 

= Can i use pretty photo similar jquery plugins to customize display ? = 

Yes 

= Can i update my amazon settings using backend ? =

Yes


== Donations ==

