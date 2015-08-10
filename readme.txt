=== Post-Specific Comments Widget (PSCW) ===
Contributors: littlepackage
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PB2CFX8H4V49L
Tags: comment, excerpt, feedback, free, guest, page, post, recent, testimonial, widget, specific
Requires at least: 3.4
Tested up to: 4.3
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Allows you to specify which post/page ID to display comments for in a widget. This can come in very handy when trying to showcase the comments from a single post or page, such as customer feedback or testimonials.

Allows you to list comments in default manner (by Author and Post Title) or by Author and a variable-character excerpt and ellipsis (or other chosen trailing characters). Two new post formats to version 1.1 are (Post Title) - (Excerpt) and (Excerpt) - (Author).

Thanks for downloading. I hope this can help you. If this really helped you, and especially if you can profit from it, consider sending a dollar or two my way! Paypal link at right.

== Installation ==

1. Upload post-specific-comments-widget.php to the /wp-content/plugins/ directory
2. Activate plugin through the 'Plugins' menu in Wordpress
3. Edit settings under Appearance>Widgets (Hint: add the Post-Specific Comments widget to your sidebar).

The ID number can be found by hovering over the post or page title while in the "All Pages" or "All Posts" list view Wordpress panels. The number will show itself after "post=" in your browser's add-on bar (usually at the lower left hand corner of the screen).

You don't have to limit the comments to one post or page, though. You can show all your posts/pages by typing in the number 0 (zero) instead of a post number.

== Frequently Asked Questions ==

If you have questions, don't hesitate to ask.

= How do I use the shortcodes? = 

If you want to customize the way your comment is printed out, you can use the following shortcodes as placeholders for the intended output: <code>[AUTHOR], [TITLE\], [EXCERPT\], [DATE\].</code>

So for example, if you choose the "other" format, you could then enter "Comment by ~[AUTHOR]~ on [DATE]" and the plugin would magically replace the shortcodes with the author name/link, surrounded by tildes/swiggles, and then the date. 

== Screenshots ==

1. Screenshot of the menu settings
2. Screenshot of how to find post/page ID number. In this case the ID is 1696 when hovering over post “Change Password.”

== Upgrade Notice ==

= 1.0.1 = 
Fixes a minor security-related bug. Recommend immediate update. Added feature: excerpt display options. Now use the number 0 instead of the word "all" to show all posts/pages if not showing just one post/page.

= 1.0.2 = 
Fixed issue with Post ID not working in some cases.

= 1.0.5 = 
Localization and main class fixes

= 1.1 = 
Wordpress 4.3 ready

= 1.2 = 
Shortcodes added!

== Changelog ==

= 1.0 February 14 2013 =
* First version, adapted from "Recent comments widget with comment excerpts," "Wizzart Recent Comments, and "HTML Classified Recent Posts & Comments Widgets"

= 1.0.1 February 19 2013 =
* Added menu option to set excerpt length and trailing character.

= 1.0.2 February 20 2013 =
* Fixed issue with Post ID not working in some cases

= 1.0.3 December 13 2013 =
* Tested with Wordpress 3.8
* Helpful screenshot added

= 1.0.4 Oct 22 2014 =
* Tested with Wordpress 4.0

= 1.0.5 Oct 30 2014 =
* Language files added and l10n code fixes
* Main class fixes

= 1.0.6 May 3 2015 =
* ID tag fix to prevent page ID duplicates

= 1.1.0 August 7 2015 =
* Wordpress 4.3 ready
* ID tag unique identifier for HTML5
* Two additional formats, title/except and excerpt/author

= 1.2.0 August 10 2015 =
* Shortcodes for custom comment output