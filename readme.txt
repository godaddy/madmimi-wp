=== Mad Mimi Signup Forms ===
Contributors: madmimi, humanmade, illuminea, maor, beccawitz
Tags: forms, newsletter, opt-in
Requires at least: 3.5
Tested up to: 4.3
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add the Mad Mimi webform to your WordPress site! Easy to set up, the Mad Mimi plugin allows your site visitors to subscribe to your email lists.

== Description ==

The Official Mad Mimi Signup Form plugin makes it easy to grow your subscribers! Use this plugin to integrate your sign up forms into your WordPress site. To learn more about Mad Mimi, grab a [FREE forever account](http://madmimi.com) and explore.

Once the plugin is activated, you can select and insert any of your Mad Mimi webforms right into your site by using a widget, shortcode, or template tag. Setup is easy; in Settings, simply enter your account email address and API key (found in your [Mad Mimi account](http://help.madmimi.com/where-can-i-find-my-api-key/) area), and you're all set.

Official Mad Mimi Forms plugin features:

* Automatically add new forms for users to sign up to an email list of your choice
* Insert unlimited signup forms using the widget, shortcode, or template tag
* Use quick links to edit and preview your form in Mad Mimi

== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer.
2. Activate the plugin.
3. Go to the plugin settings page (under Settings > Mad Mimi Settings).
4. Enter your account email address and API key (found in your [Mad Mimi account](http://help.madmimi.com/where-can-i-find-my-api-key/) area).
5. Click **Save Changes**.

After your account is verified, you can insert a form into your site by using a widget, shortcode, or template tag:

* **Widget** Go to Appearance > Widgets, find the Mad Mimi Form widget, and drag it into the widget area of your choice. You can then add a title and select a form!
* **Shortcode** Add a form to any post or page by adding the shortcode (e.g., `[madmimi id=80326]`) in the page/post editor. You can find a form's ID on the Mad Mimi Settings page.
* **Template** tag Add the following template tag into any WordPress file: `<?php madmimi_form( $form_id ); ?>`. For example: `<?php madmimi_form( 91 ); ?>` You can find a form's ID on the Mad Mimi Settings page.

That's it.  You're ready to go!

== Frequently Asked Questions ==

= What is Mad Mimi? =
[Mad Mimi](https://madmimi.com) is the easiest way to create, send, share, and track email newsletters online. It's for people who want email marketing to be simple.

= Do I need a Mad Mimi account to use this plugin? =
Yes, this plugin requires a [Mad Mimi](https://madmimi.com) account.

= Is there a widget? =
Absolutely. Use it by finding the Mad Mimi Form widget under Appearance >Widgets in the WordPress Dashboard and dragging it into the widget area of your choice. You can then add a title and select a form!

= Is there a shortcode? =
Yes! You can add a form to any post or page by adding the shortcode with the form ID (ex. `[madmimi id=80326]`) in the page/post editor. Form IDs are listed on the Mad Mimi Settings page.

= Is there a template tag? =
Yup! Add the following template tag into any WordPress file: `<?php madmimi_form( $form_id ); ?>`. For example: `<?php madmimi_form( 91 ); ?>`. Form IDs are listed on the Mad Mimi Settings page.

= Where can I find the API Key? =
The API key is in your Mad Mimi account area. For more details see: http://help.madmimi.com/where-can-i-find-my-api-key/

== Screenshots ==

1. Settings screen.
2. A full list of your Mad Mimi Webforms, with ready shortcodes
3. The widget, on the front-end
4. The widget, on the widgets page

== Changelog ==

= 1.5 =
* This update includes various bug fixes.

= 1.4 =
* Added support for web form fancy fields
* Made some styling changes to mobile view

= 1.3 =
* Coding standards
* Fixed up some improper escaping and sanitization
* Updated Admin UI to more closely match default WordPress style
* Minor improvements to plugin copy

= 1.2 =
* Fixed the padding for p tags in the mad mimi signup form

= 1.1 =
* New! Upon form submit, the plugin checks to see if the Mad Mimi user has specified that the new subscriber should be redirected to a specific webpage after subscribing (Confirmation Landing Page). If the user has specified a Confirmation Landing Page for their webform, the new subscriber will be redirected to that page after subscribing.
* Better cache handling for the provided CSS and JS core files
* Bug fixes

= 1.0 =
* Initial version. Hoozah!
