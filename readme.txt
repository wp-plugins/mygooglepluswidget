=== My Google Plus Widget ===
Contributors: arjenketelaar
Donate link: http://blog.ketelaar.info/projects/googlepluswidget/
Tags: google plus widget api
Requires at least: 2.0.2
Tested up to: 3.2.1
Stable tag: 1.3.1

The Google Plus Widget is based on the official Google Plus API published by Google.

== Description ==

The **Google Plus Widget** is based on the official Google Plus API published by Google.

All you Google Plus updates will be presented in a widget. You can also show (public) updates from other Google Plus users.


== Installation ==

Setup instructions:

1. Install and activate the Plugin (by uploading the plugin by FTP or by selecting the plugin in your WordPress Dashboard.

1. Create a new project on the Google API Console (*https://code.google.com/apis/console*)

1. Turn on the Google+ API on the *All Services Page*

1. Create an Oauth 2.0 client ID on the API Access Page

 - Product name = *Google Plus Widget*; press next
 - Select *Web Application*
 - Fill in the url of your wordpress installation; click *Create Client ID*
 - Click *edit settings*. Replace the *Authorized Redirect URI* with the url of your wordpress installation added with */.index.php* (e.g. *http://www.mywordpress.com/index.php*)

1. Go back to you WordPress Dashboard. Click on the Configuration Page by selecting *Google Plus Widget* under settings:

 - paste your Google Pus ID (a big number) at *google_plus_id*
 - paste your oauth2_client_id from the API Access page
 - paste your oauth2_client_secret from the API Acces page
 - paste your oauth2_redirect_uri from the API Acces page
 - paste your developer_id from the API Access page (sometimes called API key)

1. Install the widget by dragging the Google Plus Widget in the Appearance Menu to the widget space you want the widget to appear

1. fill your Google Plus id to display the Google Plus updates on your wordpress site

== Frequently Asked Questions ==

= Please email your questions =

EMAIL: projects -at- ketelaar -dot- info
And I will in include in this FAQ

PROJECT WEBSITE: http://blog.ketelaar.info/projects/google-plus-widget/


== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt, so in this case, `/tags/4.3/screenshot-1.png` (or jpg, jpeg, gif)
2. This is the second screen shot

== Changelog ==

= 1.3.1 = 
error in googlepluswidget.php resolved

= 1.3 = 
readme file clearified

= 1.1 =
Upgrade of the readme file to get versions 1.1

= 1.0 =
* first release

== Upgrade Notice ==

= 1.1 =
Only a upgrade of the readme file


== Arbitrary section ==

No text yet

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`

