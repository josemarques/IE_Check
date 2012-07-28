=== IE Check ===
Contributors: José Marques
Tags: browser compatilibity, web standards, Internet Explorer
Tested up to: 3.4
Requires at least: 3.0
Stable tag: trunk
License: GPLv2 or later

Checks if the browser is an older version of Internet Explorer, releases rage if it's IE<9

== Description ==

Older versions of Internet Explorer are a pain that doesn't go away. Of course everyone tries to design and implement themes that work well on all browsers, but there's always something that doesn't quite work in an older version of Internet Explorer.
What this plugin does is display a message to the user informing that the version of Internet Explorer he/she is using is old. 

There are some options you can change like the text, the URI of the site with browser info and an option to show or hide the dismiss button, in case you really don't want to allow the users see your site unless they have a decent browser. 

There's a CSS file with all the styles you can change.

== Installation ==

Upload the IE plugin to your blog, Activate it, the add the following code to your template:

`<?php if (function_exists("ie_check")) { ie_check(); } ?>`

I recommend adding it to your footer.php

Finito!

== Features ==

* Personalizable text
* Personalizable display options
* Choose the recommended site
* Allow user to dismiss message

== Changelog ==

= 0.8.0 =
* First released version