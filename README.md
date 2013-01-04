ipb-syntaxhighlighter
=====================

SyntaxHighlighter Drop-in replacement for IPB 3.4.x

## Installation

Installation of the [SyntaxHighlighter](http://alexgorbatchev.com/SyntaxHighlighter/) code is fairly straightforward: 
 
 1. Upload the files from the uploads directory, similar to how you would for an IPB install / upgrade
 1. Within the `/installables` you'll find a few different XML files. 
    1. `bbcode_SyntaxHighlighter.xml` is a BB Code replacement for the code bbcode. 
    You should delete that bbcode and install this one. Alternatively, you can change the 
    following items in the default code bbcode:
        * Custom BBCode Tag: codesyntax
        * Custom BBCode Aliases: code,codebox
        * OR PHP file to execute: codesyntax.php
    1. `hook_SyntaxHighlighter.xml` is a (skin overloading) hook that replaces the prettify code with the 
    SyntaxHighlighter code.  You should install this like a normal hook (Applications & Modules  >  Manage Hooks 
    > Install Hook)
 1. At this point, all new posts will be using SyntaxHighlighter. If you have existing posts with code, 
    you'll likely want to rebuild the content of them (Tools & Settings  >  Recount & Rebuild > Rebuild Content: Post Content)

## Styling

With SyntaxHighlighter comes [themes](http://alexgorbatchev.com/SyntaxHighlighter/manual/themes/). 
By default, the core & default theme are loaded. You can change this by replacing `/public/style_css/3rd_party/SyntaxHighlighter/shTheme.css` 
with the theme of your choice.