=== Easy Facebook Share Thumbnail ===
Contributors: hebeisenconsulting
Donate link: http://hebeisenconsulting.com/wordpress-easy-facebook-share-thumbnail/
Tags: facebook share, facebook thumbnails, facebook share thumbnail, featured image
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.9.6

== Description ==

Easy Facebook Share Thumbnail makes it easy and almost instant! Using the post's featured image, Easy Facebook Share Thumbnail is an easy no hassle way of instantly enabling wordpress websites to use post-specific thumbnails when used by the Facebook URL Sharing and Posting system. A default image can also be specified for the whole site.

== Installation ==

1. Upload easy-facebook-share-thumbnails folder to the `/wp-content/plugins/` directory OR through wp-admin,
upload easy-facebook-share-thumbnails.zip in plugin 'Add New' page.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Settings can be found under 'Setting' menu at the left side of wp-admin.

== Frequently Asked Questions ==

= How do I upload thumbnails for Facebook Share? =

You can upload new thumbnail in Settings->Easy Facebook Share Thumbnails.

= How Facebook can determine my thumbnail? =

For default, Facebook Share will use your active thumbnail if the page that you are going to share does not
have thumbnail.

= I want to use different thumbnail for each pages/posts that I want to share in Facebook =

You can do this using Featured Image in Post/Page settings.

= My current theme does not support Featured Image =

The plugin will automatically activate Featured Image even the current theme used does not support this feature.

= What image files that are currently supported? =

Only .GIF, .PNG and .JPEG that are currently supported.

= Something is not working correctly. Whats wrong? =

The plugin is still in its infancy. The rapid evolution of facebook is great, but its hard to keep up sometimes when developing free plugins. Were committed to the plugin though! Please help by reporting any bugs you find. Please do this before giving us a bad rating - we will do everything we can to fix things! Report A bug

<a href="http://easyfacebooksharethumbnail.com/report-bug/" target="_blank">Report a Bug</a>

= I have updated the featured image but it is not being reflected when sharing the post on facebook  =

When trying out sharing functionality on facebook, your page may be saved or cached in the incorrect manner. If you have been making a number of changes to your post and would like facebook to get a fresh copy, kindly add a random number to your post url instead of the Xs like so: http://domain.com/this-is-the-post-title/?efs=XXXX This will trick facebook into thinking that you are sharing a different page, and load up the latest copy (of the same page).

= I have updated the plugin and it stopped working! =

Kindly try and deactivate and reactive the plugin on the Plugins page. If this doesnt work, please make sure that no other facebook function-adding plugins are running alongside this one. 

= I cannot seem to upload an image.  =

Be sure that the thumbnails directory in the plugin directory is writable.

== Changelog ==

= 1.9.6 =
* Re debug on page/post content extraction
* Fix premium subscription error

= 1.9.5 =
* Fix content extraction for og:description
* Fix thumbnail overlays and display

= 1.9 =
* Fix shorcode content stripping
* Strip html tags on post description

= 1.8.2 =
* Add masking feature for post and page

= 1.8 =
* Add new masking feature.
* Add facebook share App ID feature
* Fix bug on conditional statement

= 1.7 =
* Fix html strip in meta og:description

= 1.6 =
* Fix thumbnail is not visible on admin interface after upload.

= 1.5 =
* Fix bug on home page meta property og:type

= 1.4 =
* Fix bug on database prefix. NOTE: Special thanks to Ryan Hemeon for contributing the solution. 

= 1.3 =
* Fix bug on page title that appears at the header.
* Fix problem on content excerpt.

= 1.2 =
* Fix bug for thumbnail directory and path
* Fix param problem in admin area

= 1.1 =
* Fix bug for index page sharing

= 1.0 =
* First major release