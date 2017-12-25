<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * This contains functions and classes that will be used by scripts in digui module
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Remove html tags that no add formatting to the text. 
 * @param string $html, contains a html document.
 * @return string, a html document without non formatting tags.
 */

function strip_non_formatting_tags($string) {

    //
    // First: delete tags which do not contain readable text. The text 
    // inside these tags will be deleted too.
    //

    // Tags without end tag, so do not contain readable text.
    $string = preg_replace('~<!DOCTYPE("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<base("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<basefont("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<br("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // A \b character left intentionally, to do not confuse with colgroup tags.
    $string = preg_replace('~<col\b("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<embed("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<frame("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<hr("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<img("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<input("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<keygen("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<link("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<meta("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<param("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<source("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = preg_replace('~<track("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    
    // Tags with end tags, but are not inside the body section (like title), or 
    // do not contain readable text (like frameset). 
    $string = preg_replace('~<applet("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</applet>~si', '', $string);
    $string = preg_replace('~<audio("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</audio>~si', '', $string);
    // See http://www.perlmonks.org/?node_id=285983.
    $string = preg_replace('~<!--(?:[^-]*|-[^-]+)*-->~si', '', $string); 
    $string = preg_replace('~<canvas("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</canvas>~si', '', $string);
    $string = preg_replace('~<colgroup("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</colgroup>~si', '', $string);
    $string = preg_replace('~<datalist("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</datalist>~si', '', $string);
    $string = preg_replace('~<dialog("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</dialog>~si', '', $string);
    $string = preg_replace('~<figcaption("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</figcaption>~si', '', $string);
    $string = preg_replace('~<figure("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</figure>~si', '', $string);
    $string = preg_replace('~<frameset("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</frameset>~si', '', $string);
    $string = preg_replace('~<head("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</head>~si', '', $string);
    $string = preg_replace('~<iframe("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</iframe>~si', '', $string);
    $string = preg_replace('~<map("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</map>~si', '', $string);
    $string = preg_replace('~<menu("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</menu>~si', '', $string);
    $string = preg_replace('~<menuitem("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</menuitem>~si', '', $string);
    $string = preg_replace('~<meter("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</meter>~si', '', $string);
    $string = preg_replace('~<noframes("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</noframes>~si', '', $string);
    $string = preg_replace('~<noscript("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</noscript>~si', '', $string);
    $string = preg_replace('~<object("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</object>~si', '', $string);
    $string = preg_replace('~<output("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</output>~si', '', $string);
    $string = preg_replace('~<progress("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</progress>~si', '', $string);
    $string = preg_replace('~<script("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</script>~si', '', $string);
    // Don't delete the style tags, because we must preserve the style.
    // $string = preg_replace('~<style("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</style>~si', '', $string);
    $string = preg_replace('~<strike("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</strike>~si', '', $string);
    // textarea tag is an exception. It may contains readable text, but it is
    // editable text, so is not a valid tag for our purposes.
    $string = preg_replace('~<textarea("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</textarea>~si', '', $string);
    $string = preg_replace('~<title("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</title>~si', '', $string);
    $string = preg_replace('~<video("[^"]*"|\'[^\']*\'|[^\'">])*>(.*?)</video>~si', '', $string);

    //
    // Second: delete tags which do not format text (like abbr). We must 
    // preserve the text inside these tags.
    //

    // Don't delete the a tags, because underline and colorize the text.
    // Example: <a href="http://www.w3schools.com">Visit W3Schools.com</a>
    // A \b character left intentionally, to do not confuse with abbr and 
    // other tags.
    // $string = preg_replace('~<a\b("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</a>', '', $string);

    // Don't delete the abbr tags, because would be displayed with some css
    // rules.
    // Example: <abbr title="World Health Org.">WHO</abbr> was founded...
    // $string = preg_replace('~<abbr("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</abbr>', '', $string);

    // Don't delete the acronym tags, because would be displayed with some css
    // rules.
    // Example: This <acronym title="as soon as possible">ASAP</acronym>
    // $string = preg_replace('~<acronym("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</acronym>', '', $string);

    // Example:
    // <address>
    // Written by <a href="mailto:webmaster@example.com">Jon Doe</a>.<br>
    // Visit us at:<br>
    // Box 564, Disneyland<br>
    // USA
    // </address> 
    $string = preg_replace('~<address("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</address>', '', $string);
    
    // Don't delete the article tags, because "Most browsers will display the 
    // <article> element with" default css rules. 
    // See http://www.w3schools.com/tags/tag_article.asp
    $string = preg_replace('~<article("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</article>', '', $string);
    
    // Don't delete the aside tags, because text inside this tag will be 
    // displayed separated from others elements. 
    // Example:
    // <p>My family and I visited The Epcot center this summer.</p>
    // <aside><h4>Epcot Center</h4></aside>
    // $string = preg_replace('~<aside("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</aside>', '', $string);
    
    // Don't delete the b tags, because add bold formatting to the text.
    // Example: <b>Strong text</b>
    // $string = preg_replace('~<b ("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</b>', '', $string);
     
    // Don't delete the bdi tags, because are useful for arabic purposes. 
    // Example: <bdi>إيان</bdi>
    // $string = preg_replace('~<bdi("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</bdi>', '', $string);
    
    // Don't delete the bdo tags, because are useful for arabic purposes. 
    // Example:  <bdo dir="rtl">This text will go right-to-left.</bdo> 
    // $string = preg_replace('~<bdo("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</bdo>', '', $string);

    // Don't delete the big tags, because make text bigger than normal.
    // Example:  <p><big>Bigger text</big></p>
    // $string = preg_replace('~<big("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</big>', '', $string);
    
    // Don't delete the blockquote tags, because they display the text as a
    // paragraph. 
    // Example: <blockquote>Hello.</blockquote>
    // $string = preg_replace('~<blockquote("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</blockquote>', '', $string);
    
    $string = preg_replace('~<body("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</body>', '', $string);

    // Example: <table><caption>Monthly savings</caption></table>.
    $string = preg_replace('~<caption("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</caption>', '', $string);

    // Don't delete the center tags, because centers the text.
    // Example:  <center>This text will be center-aligned.</center> .
    // $string = preg_replace('~<center("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</center>', '', $string);
    
    // Don't delete the cite tag, because it add italic format to text.
    // Example:  <cite>A piece of computer code</cite> 
    // $string = preg_replace('~<cite("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</cite>', '', $string);

    // Don't delete the code tags, because display text with courier style.
    // Example:  <code>A piece of computer code</code> 
    // $string = preg_replace('~<code("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</code>', '', $string);

    // Don't delete the del tags, because strikeout the text.
    // Example: My favorite color is <del>red</del>
    // $string = preg_replace('~<del("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</del>', '', $string);
    
    // Example: <details><summary>Copyright 1999-2014.</summary></details>
    $string = preg_replace('~<details("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</details>', '', $string);
    
    // Don't delete the dfn tag, because it add italic format to text.
    // Example: <dfn>HTML</dfn> is a standard markup language.
    // $string = preg_replace('~<dfn("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</dfn>', '', $string);
    
    // Don't delete the div tag, because "By default, browsers always place a 
    // line break before and after the <div> element".
    // See http://www.w3schools.com/tags/tag_div.asp.
    // Example: <div>This is a division</div>.
    // $string = preg_replace('~<div("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</div>', '', $string);
    
    // Don't delete the dd tags, because are usefull to indent text.
    // Example: <dl><dt>Coffee</dt><dd>Black hot drink</dd></dl> 
    // $string = preg_replace('~<dd("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</dd>', '', $string);

    // Don't delete the dl tags, because are usefull to indent text.
    // Example: <dl><dt>Coffee</dt><dd>Black hot drink</dd></dl> 
    // $string = preg_replace('~<dl("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</dl>', '', $string);

    // Don't delete the dt tags, because are usefull to indent text.
    // Example: <dl><dt>Coffee</dt><dd>Black hot drink</dd></dl> 
    // $string = preg_replace('~<dt("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</dt>', '', $string);
    
    // Don't delete the dir tags, because are usefull to indent text.
    // Example: <dir><li>html</li><li>xhtml</li><li>css</li></dir>
    // $string = preg_replace('~<dir("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</dir>', '', $string);

    // Don't delete the em tag, because it add italic format to text.
    // Example:  <em>A piece of computer code</em> 
    // $string = preg_replace('~<em>~si', '', $string);
    // $string = str_ireplace( '</em>', '', $string);

    // Example: <form><fieldset><legend>Personalia:</legend></fieldset></form> 
    $string = preg_replace('~<fieldset("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</fieldset>', '', $string);

    // Don't delete the font tags, because add formatting to text.
    // Example: <font size="3" color="red">This is some text!</font>
    // $string = preg_replace('~<font("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</font>', '', $string);
    
    // Example: 
    // <footer>
    // <p>Posted by: Hege Refsnes</p>
    // <p>Contact information: <a href="mailto:someone@example.com">someone@example.com</a>.</p>
    // </footer>
    $string = preg_replace('~<footer("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</footer>', '', $string);
    
    // Example: 
    // <form action="demo_form.asp" method="get">
    // Name: <input type="text" name="fname"><br>
    // <input type="submit" value="Submit">
    // </form> 
    $string = preg_replace('~<form("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</form>', '', $string);

    // Don't delete the h tags, because make text bigger than normal.
    // Example: <h1>This is heading 1</h1>.
    // $string = preg_replace('~<h(\d){1}("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</h(\d){1}>', '', $string);
    
    // Example: 
    // <header>
    // <h1>Most important heading here</h1>
    // <p>Some additional information here.</p>
    // </header>
    $string = preg_replace('~<header("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</header>', '', $string);

    $string = preg_replace('~<html("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</html>', '', $string);
    
    // Don't delete the i tag, because it add italic format to text.
    // Example:  <i>A piece of computer code</i> 
    // A \b character left intentionally, to do not confuse with ins and other 
    // tags.
    // $string = preg_replace('~<i\b("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</i>', '', $string);

    // Don't delete the ins tag, because it strikeouts the text.
    // Example: My favorite color is <ins>red</ins>
    // $string = preg_replace('~<ins("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</ins>', '', $string);
    
    // Don't delete the kbd tags, because display text with courier style.
    // Example:  <kbd>Keyboard input</kbd>
    // $string = preg_replace('~<kbd("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</kbd>', '', $string);
    
    // Example: <form action="form.asp"><label for="male">Male</label></form> 
    $string = preg_replace('~<label("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</label>', '', $string);
    
    // Don't delete the li tags, because are usefull to indent text.
    // Example: <ol><li>Coffee</li><li>Tea</li><li>Milk</li></ol> 
    // $string = preg_replace('~<li("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</li>', '', $string);
    
    // Don't delete the mark tags, because highlight the text.
    // Example: <p>Do not forget to buy <mark>milk</mark> today.</p> 
    // $string = preg_replace('~<mark("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</mark>', '', $string);
    
    $string = preg_replace('~<main("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</main>', '', $string);
    
    // Example:
    // <nav><a href="/js/">JavaScript</a> | <a href="/jquery/">jQuery</a></nav>    
    $string = preg_replace('~<nav("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</nav>', '', $string);
    
    // Don't delete the ol tags, because are usefull to indent text.
    // Example: <ol><li>Coffee</li><li>Tea</li><li>Milk</li></ol> 
    // $string = preg_replace('~<ol("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</ol>', '', $string);

    // Example: <select><option value="volvo">Volvo</option></select>  
    // $string = preg_replace('~<option("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</option>', '', $string);

    // Example: 
    // <select>
    // <optgroup label="Swedish Cars">
    // <option value="saab">Saab</option>
    // </optgroup>
    // </select> 
    $string = preg_replace('~<optgroup("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</optgroup>', '', $string);

    // Don't delete the p tag, because "Browsers automatically add some space 
    // (margin) before and after each <p> element".
    // See http://www.w3schools.com/tags/tag_p.asp.
    // A \b character left intentionally, to do not confuse with pre tags.
    // Example:  <p>The World Wide Fund for Nature.</p>
    // $string = preg_replace('~<p\b("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</p>', '', $string);

    // Example: <pre>Text in a pre element.</pre>
    $string = preg_replace('~<pre("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</pre>', '', $string);
    
    // Don't delete the q tag, because it enclose text between quotes.
    // A \b character left intentionally, to do not confuse with other tags.
    // Example:  <q>The World Wide Fund for Nature.</q>
    // $string = preg_replace('~<q\b("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</q>', '', $string);

    // Example:
    // <ruby>漢 <rt><rp>(</rp>ㄏㄢˋ<rp>)</rp></rt></ruby>
    $string = preg_replace('~<rp("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</rp>', '', $string);
    
    // Example:
    // <ruby>漢 <rt><rp>(</rp>ㄏㄢˋ<rp>)</rp></rt></ruby>
    $string = preg_replace('~<rt("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</rt>', '', $string);
    
    // Example:
    // <ruby>漢 <rt><rp>(</rp>ㄏㄢˋ<rp>)</rp></rt></ruby>
    $string = preg_replace('~<ruby("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</ruby>', '', $string);

    // Don't delete the s tags, because strikeout the text.
    // Example: My favorite color is <s>red</s>
    // $string = preg_replace('~<s>~si', '', $string);
    // $string = str_ireplace( '</s>', '', $string);
    
    // Don't delete the samp tags, because display text with courier style.
    // Example:  <samp>Sample input</samp>
    // $string = preg_replace('~<samp("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</samp>', '', $string);
    
    // Don't delete the section tag, because browsers always place a line break 
    // before and after the <section> element.
    // Example: 
    // <section><h1>WWF</h1><p>The World Wide Fund for Nature.</p></section> 
    // $string = preg_replace('~<section("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</section>', '', $string);
    
    // Example: <form><select><option value="saab">Saab</option></select></form> 
    $string = preg_replace('~<select("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</select>', '', $string);
    
    // Don't delete the small tags, because make text smaller than normal.
    // Example:  <p><small>Bigger text</small></p>
    // $string = preg_replace('~<small("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</small>', '', $string);
    
    // Example: <span>The World Wide Fund for Nature.</span> 
    $string = preg_replace('~<span("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</span>', '', $string);
    
    // Don't delete the strong tags, because add bold formatting to the text.
    // Example: <strong>Strong text</strong>
     $string = preg_replace('~<strong("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
     $string = str_ireplace( '</strong>', '', $string);

    // Don't delete the sub tags, because add subscript formatting to the text.
    // Example: <p>This text contains <sub>subscript</sub> text.</p>
    // $string = preg_replace('~<sub("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</sub>', '', $string);
     
    // Don't delete the sup tags, because add superscript formatting to the text.
    // Example: <p>This text contains <sup>superscript</sup> text.</p>
    // $string = preg_replace('~<sup("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</sup>', '', $string);
             
    // Example: 
    // <details>
    // <summary>Copyright 1999-2014.</summary>
    // <p> - by Refsnes Data. All Rights Reserved.</p>
    // </details> 
    $string = preg_replace('~<summary("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</summary>', '', $string);
    
    // Don't delete the tbody tags, because are usefull to indent text.
    // Example: 
    // <table>
    // <thead><tr><th>Month</th></tr></thead>
    // <tbody><tr><td>January</td></tr></tbody>
    // </table>  
    // $string = preg_replace('~<tbody("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</tbody>', '', $string);

    // Don't delete the td tags, because are usefull to indent text.
    // Example: 
    // <table>
    // <thead><tr><th>Month</th></tr></thead>
    // <tbody><tr><td>January</td></tr></tbody>
    // </table>  
    // $string = preg_replace('~<td("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</td>', '', $string);

    // Don't delete the tfoot tags, because are usefull to indent text.
    // Example: 
    // <table>
    // <thead><tr><th>Month</th></tr></thead>
    // <tfoot><tr><td>Sum</td><td>$180</td></tr></tfoot>
    // </table>  
    // $string = preg_replace('~<tfoot("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</tfoot>', '', $string);

    // Don't delete the th tags, because are usefull to indent text.
    // A \b character left intentionally, to do not confuse with thead tags.
    // Example: 
    // <table>
    // <thead><tr><th>Month</th></tr></thead>
    // <tbody><tr><td>January</td></tr></tbody>
    // </table>  
    // $string = preg_replace('~<th\b("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</th>', '', $string);

    // Don't delete the thead tags, because are usefull to indent text.
    // Example: 
    // <table>
    // <thead><tr><th>Month</th></tr></thead>
    // <tbody><tr><td>January</td></tr></tbody>
    // </table>  
    // $string = preg_replace('~<thead("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</thead>', '', $string);

    // Don't delete the tr tags, because are usefull to indent text.
    // Example: 
    // <table>
    // <thead><tr><th>Month</th></tr></thead>
    // <tbody><tr><td>January</td></tr></tbody>
    // </table>  
    // $string = preg_replace('~<tr("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</tr>', '', $string);

    // Example: 
    // <p>We open at <time>10:00</time> every morning.</p>
    $string = preg_replace('~<time("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</time>', '', $string);
    
    // Don't delete the tt tags, because display text with courier style.
    // Example: <p><tt>This text is teletype text.</tt></p>
    // $string = preg_replace('~<tt("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</tt>', '', $string);

    // Don't delete the u tags, because underline the text.
    // Example:  <p>This is a <u>parragraph</u>.</p> 
    // A \b character left intentionally, to do not confuse with ul tags.
    // $string = preg_replace('~<u\b("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</u>', '', $string);

    // Don't delete the ul tags, because are usefull to indent text.
    // Example: <ul><li>Coffee</li><li>Tea</li><li>Milk</li></ul> 
    // $string = preg_replace('~<ul("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</ul>', '', $string);
    
    // Don't delete the var tags, because display text with courier style.
    // Example: <var>Variable</var>
    // $string = preg_replace('~<var("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    // $string = str_ireplace( '</var>', '', $string);
    
    // Example: <wbr>Variable</wbr>
    $string = preg_replace('~<wbr("[^"]*"|\'[^\']*\'|[^\'">])*>~si', '', $string);
    $string = str_ireplace( '</wbr>', '', $string);
   
    return $string;
}

// This function does not search for return carriage characters. 
function read_token($buffer, $numchars, & $i) {
    
    $token = '';
    $simplequoteread = false;
    $doublequoteread = false;
    
    if ($i >= $numchars) {
        return '';
    }
    else if ($buffer[$i] == '<') {
        // We may find tags like <body class=">">. So, we must add the third 
        // ($simplequoteread) and fourth ($doublequoteread) checking.
        while ($i < $numchars && (($c = $buffer[$i]) != '>' || $simplequoteread || $doublequoteread)) {
            if ($c == '\'') {
                $simplequoteread ? $simplequoteread = false : $simplequoteread = true;
            } 
            else if ($c == '"') {
                $doublequoteread ? $doublequoteread = false : $doublequoteread = true;
            } 
            $token .= $c;
            $i++;
        }
        
        if ($i < $numchars) {
            $token .= '>';
            $i++;
        }
    }
    else {
        // According to the HTML specification, "authors wishing to put the "<" 
        // character in text should use "&lt;"". However, most of navigators
        // support HTML texts like <p>0 < 1</p>. So, read_token will support
        // these too.
        // See https://www.w3.org/TR/html4/charset.html#h-5.3.2.
        while ($i < $numchars && (($c = $buffer[$i]) != '<' || ($i+1 < $numchars && preg_match('~[^a-zA-Z/]~si', $buffer[$i+1]) == 1))) {
            $token .= $c;
            $i++;
        }            
    }

    return $token;
}