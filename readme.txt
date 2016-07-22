=== Mad Mimi Signup Forms ===
Contributors: madmimi, humanmade, xwp, illuminea, maor, beccawitz
Tags: email, forms, mailing list, marketing, newsletter, opt-in, signup, subscribe, widget, contacts
Requires at least: 3.8
Tested up to: 4.5
Stable tag: 1.5.1
License: GPL-2.0
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add the Mad Mimi webform to your WordPress site! Easy to set up, the Mad Mimi plugin allows your site visitors to subscribe to your email lists.

== Description ==

The Official Mad Mimi Signup Form plugin makes it easy to grow your subscribers! Use this plugin to integrate your sign up forms into your WordPress site. To learn more about Mad Mimi, grab a [FREE forever account](http://madmimi.com) and explore.

Once the plugin is activated, you can select and insert any of your Mad Mimi webforms right into your site by using a widget, shortcode, or template tag. Setup is easy; in Settings, simply enter your account email address and API key (found in your [Mad Mimi account](http://help.madmimi.com/where-can-i-find-my-api-key/) area), and you're all set.

Official Mad Mimi Forms plugin features:

* Automatically add new forms for users to sign up to an email list of your choice
* Insert unlimited signup forms using the widget, shortcode, or template tag
* Use quick links to edit and preview your form in Mad Mimi

== Installation ==

1. [Install the plugin manually](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation) by uploading a ZIP file, or [install it automatically](https://codex.wordpress.org/Managing_Plugins#Automatic_Plugin_Installation) by searching for **Mad Mimi Signup Forms**.
2. Once the plugin has been installed, click **Activate**.
3. Nagivate to **Settings > Mad Mimi Settings**.
4. Enter your Mad Mimi username and API key.
5. Click **Save Settings**.

After your account is verified, you can insert a form into your site by using a **widget**, **shortcode**, or **template tag** directly in your theme. See the FAQ section for more details.

== Frequently Asked Questions ==

= What is Mad Mimi? =

[Mad Mimi](https://madmimi.com) is the easiest way to create, send, share, and track email newsletters online. It's for people who want email marketing to be simple.

= Do I need a Mad Mimi account to use this plugin? =

Yes, this plugin requires a [Mad Mimi](https://madmimi.com) account.

= Is there a widget? =

Absolutely. Use it by finding the Mad Mimi Form widget under **Appearance > Widgets** in the WordPress Dashboard and dragging it into the widget area of your choice. You can then add a title and select a form!

= Is there a shortcode? =

Yes! You can add a form to any post or page by adding the shortcode with the form ID (e.g., `[madmimi id=123456 ]`) in the page/post editor.

= Is there a template tag? =

Yup! Add the following template tag into any WordPress theme template file: `<?php madmimi_form( $form_id ); ?>`. For example: `<?php madmimi_form( 123456 ); ?>` where `123456` is your form ID.

= Where can I find my form IDs? =

To find your form IDs, navigate to **Settings > Mad Mimi Settings** and scroll down to the **Available Forms** section. If you've recently created new forms click the **Refresh Forms** button to pull them into your WordPress site.

= Where can I find the API Key? =

Your API Key can be found in your Mad Mimi account area. For more details [see this help article](https://help.madmimi.com/where-can-i-find-my-api-key/).

== Screenshots ==

1. Settings screen.
2. A full list of your Mad Mimi forms, with handy shortcodes.
3. The widget on the front-end.
4. The widget on the widgets page.

== Changelog ==

= 1.5.1 =
* Fixed shortcode display.

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
