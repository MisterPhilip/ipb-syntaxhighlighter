/**
 * SyntaxHighlighter
 * http://alexgorbatchev.com/SyntaxHighlighter
 *
 * SyntaxHighlighter is donationware. If you are using it, please donate.
 * http://alexgorbatchev.com/SyntaxHighlighter/donate.html
 *
 * Modified by Philip Lawrence (Jan 5 2013)
 * https://github.com/MisterPhilip/SyntaxHighlighter
 *
 * @version
 * 3.0.83 (July 02 2010)
 * 
 * @copyright
 * Copyright (C) 2004-2010 Alex Gorbatchev.
 *
 * @license
 * Dual licensed under the MIT and GPL licenses.
 */
 (function(){var d=SyntaxHighlighter;d.autoloader=function(){function m(){for(a=0;a<h.length;a++){var j=n[h[a].params.brush];j&&(f[j]=!1,r(j))}}function r(a){var b=document.createElement("script"),c=!1;b.src=a;b.type="text/javascript";b.language="javascript";b.onload=b.onreadystatechange=function(){if(!c&&(!this.readyState||"loaded"==this.readyState||"complete"==this.readyState)){c=!0;f[a]=!0;a:{for(var e in f)if(!1==f[e])break a;if(null!==SyntaxHighlighter.vars.discoveredBrushes)for(var g in SyntaxHighlighter.brushes)if(SyntaxHighlighter.brushes.hasOwnProperty(g)&&
(e=SyntaxHighlighter.brushes[g].aliases,"string"!=typeof SyntaxHighlighter.vars.discoveredBrushes[SyntaxHighlighter.brushes[g].aliases[0]]))for(var d in e)e.hasOwnProperty(d)&&(SyntaxHighlighter.vars.discoveredBrushes[e[d]]=g);p&&SyntaxHighlighter.highlight(q)}b.onload=b.onreadystatechange=null;b.parentNode.removeChild(b)}};document.body.appendChild(b)}var c=arguments,h=d.findElements(),n={},f={},p=!1,q=null,a;SyntaxHighlighter.all=function(a){h=d.findElements();m();q=a;p=!0};for(a=0;a<c.length;a++)for(var k=
c[a].pop?c[a]:c[a].split(/\s+/),s=k.pop(),l=0;l<k.length;l++)n[k[l]]=s;m()}})();