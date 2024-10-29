=== AJS Footnotes ===
Contributors: ajseidl
Tags: footnotes, bibliography, citataions
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Produces attractive, footnotes that are capable of containing HTML data, like images or links, simply by including them in the text.

== Description ==
This plugin allows an author to include footnotes in text by enclosing the note in doubled parenthesis ((Note.)) When the content is shown to the end user, the notes are removed from the text and included in the style of a footnote. Hovering over the footnote link will displays a popup with the footnotes content. Notes are also gathered and listed at the bottom of the page.

== Installation ==
1. Upload the ajs-footnotes folder to the `/wp-content/plugins` directory
1. Activate the plugin via the Wordpress Plugins menu
1. Use the 'AJS Footnotes' page in 'Settings' to determine how you want the note to look and function
1. Add notes to your text by enclosing them in double parenthesis

== Frequently Asked Questions ==
= How do I mark a footnote? =
Enclose the text you want to make a note from with double parenthesis. For instance ((This text would be in the note.))

= What does "Escape HTML Entities" do? =
The "Escape HTML Entities" option will cause WordPress to escape the text for the HTML special characters before displaying it. This will turn characters like & into &amp;.This is necessary if your characters are not already escaped. In most cases, this is not the case, as WordPress escapes those characters when they're entered into the system. However, if things aren't working right, you may need to turn this option on.

= I'm seeing strange &'s with letters after where punctuation should be� =
Turn "Escape HTML Entitees" off. (See above FAQ)

= Where can I find more information? =
Check out the [AJS Footnotes Project Page] (http://www.ajseidl.com/projects/ajs-footnotes/) for complete documentation

== Changelog ==

= 1.0 =
* Original release
= 1.1 =
* Fixed some CSS bugs
= 1.7 =
* Implemented new JavaScript
* Added ability to turn plugin on/off by page type
* Added pre and post footnote link text
* Stopped JS from enqueueing on pages without footnotes
* Added popup-placement support
* Added popup-adjustment support
* Added CSS Alpha channel support
* Fixed Z-Index Bug