ipb-syntaxhighlighter
=====================

SyntaxHighlighter Drop-in replacement for IPB 3.4.x

## Installation

Installation of the [SyntaxHighlighter](http://alexgorbatchev.com/SyntaxHighlighter/) code is fairly straightforward: 
 
 1. Upload the files from the uploads directory, similar to how you would for an IPB install / upgrade. If you've changed 
 your default admin folder name, you should do the same here.
 1. Within the `/installables` you'll find a few different XML files that you'll need to upload within your admin interface:
    1. `bbcode_SyntaxHighlighter.xml` is a BB Code replacement for the code bbcode. 
    You should delete that bbcode (if IN_DEV) and install this one. Alternatively, you can change the 
    following items in the default code bbcode:
        * "Custom BBCode Tag": codesyntax
        * "Custom BBCode Aliases": code,codebox
        * "OR PHP file to execute": codesyntax.php
    1. `hook_SyntaxHighlighter.xml` is a hook that replaces the prettify code with the SyntaxHighlighter code. 
    This also adds in the settings within the admin interface.  You should install this like you would for any other hook 
    (`Applications & Modules  >  Manage Hooks > Install Hook`)
 1. At this point, all new posts will be using SyntaxHighlighter. If you have existing posts with code, 
    you'll likely want to drop the cache for that BBCode (`Post Content > BBCode Management > "Code" > "Drop all cached items"`)

## Options

A few settings are also included with this hook, which you could find under `System Settings > System > Code Highlighter`. 
Although there are descriptions for each, here are all of the following settings and what they do: 

 * **Parse outdated bbcodes?**: Old BBCodes ([html], [php], [sql], [xml]) no longer work with the [code] tag. If this is enabled 
 it attempts to parse these into the correct language if your users enter in these bbcodes. By default this is turned off (No), 
 and currently does not work [waiting on 3rd party fix](https://github.com/MisterPhilip/ipb-syntaxhighlighter/issues/5)
 * **Enable clickable URLs**: If a URL is present within the code and this option is enabled (Yes), it will create hyperlinks for 
 each URL. It should be noted that these URLs are not filtered, nor do they have a rel="no-follow" attached, so you should use this 
 at your own risk. By default this is disabled (No).
 * **Tab size**: This should be the number of spaces is equal to one tab. Default is 4.
 * **Smart Tabs**: This enables smart tabs (I still have yet to figure out what these do.) Default is disabled (No).
 * **Collapse Code**: This will collapse the code and show "+ Expand code" if enabled. This is similar to how the spolier tag 
 works. By default this is disabled (No)
 * **Hide line numbers if no starting number**: If there are no line numbers, or if the starting line number is "0" and this is 
 enabled, the line numbers will be completely disabled. By default this is disabled (No).

## Languages

Also included are some language settings that are seen within SyntaxHighlighter. You can find these within 
`Manage Languages > English (USA) > public_js`. All are prefixed with `codesyntax_`:

 * **codesyntax_alert**: The name of the alert box. Default: `SyntaxHighlighter`
 * **codesyntax_brushNotHtmlScript**: The error message when a brush is not compatible with html-script mode. This really isn't applicable 
 as of now since that mode is always disabled. Default: `Brush wasn't made for html-script option:`
 * **codesyntax_expandSource**: The text to show when a code box is collapsed (See option: Collapse Code). Default: `+ expand source`
 * **codesyntax_help**: The text to show for the able / help box. Default: `?`
 * **codesyntax_noBrush**: The text to show when SyntaxHighlighter cannot find the brush. Default: `Can't find brush for:` 

## Styling

With SyntaxHighlighter comes [themes](http://alexgorbatchev.com/SyntaxHighlighter/manual/themes/). 
By default, the core & default theme are loaded. You can change this by replacing `/public/style_css/3rd_party/SyntaxHighlighter/shTheme.css` 
with the theme of your choice.