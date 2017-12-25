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
 * This contains functions and classes that will not be used by scripts in digui module
 *
 * @package mod-digui-1.0
 * @copyrigth 2016 Fernando Martin fermitanio@hotmail.com
 *
 * @author Fernando Martin
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
* Read 4000 characters from a text, ignorating whitespace characters at the
* beginning.
* @param string $text, text where search for.
* @param $start, index into text of current read position.
* @return string, 4000 characters read from text.
*/
function digui_str_read_chars($text, & $start) {

    if ($start < 0)
        return "";
    
    $strlen = strlen($text);
    if ($start > $strlen - 1)
        return "";
    
    // Start to read from the end of text. The end is equal to the begin plus 
    // 4000 characters. 4000 characters because one page contains 4000
    // characters approximately. 
    if ($start + 4000 > $strlen - 1) {
        $endofpage = true;
        $end = $strlen - 1; 
    }
    else {
        $endofpage = false;
        $end =  $start + 4000;
    }

    //
    if (!$endofpage && ($text[$end + 1] != ' ' && $text[$end + 1] != ',' && $text[$end + 1] != '?')) {
        for(; $end > $start && $text[$end] != ' ' && $text[$end] != ',' && $text[$end] != '?'; $end--);  // "tex( )t"
    }
    
    // The text doesn't contain whitespace characters.
    // Example: "text".
    if ($end == $start) {         // "(t)exto"
        $end = $start + 4000;     // "text(o)"
    }
    // The text contains whitespace characters.
    // Example: "tex t".
    else {
        $endaux = $end + 1;       // "tex (t)o"
        $count = 0;
        for(; $end > $start && ($text[$end] == ' ' || $text[$end] == ',' || $text[$end] == '?'); $end--, $count++);
        // There are whitespace characters at the beginning of text. 
        // Example: "  text".
        if ($end == $start) {     // "( ) text"
            $start = $endaux;     // "  (t)ext"
            return digui_str_read_chars($text, $start);
        }
    }
    
    // Exit.
    $substr = substr($text, $start, ($end + 1) - $start);
    
    // All characters have been read.
    if ($endofpage) {
        // Although all whitespace characters are ignorated -see the first for 
        // loop-, we must consider it after reading all characters.
        $start = $strlen;
    }
    else {
        $start = $end + 1;
    }
    
    return $substr;
}

/**
* Read a word from text based on pattern.
* @param string $text, text to read.
* @param $i, index into text of current read position.
* @return string, a word read from text.
*/
function digui_str_read_char($buffer, & $i, & $l) {
    $j = strlen($buffer);
    $token = '';
    
    if ($i >= $j)
        return $token;
    
    if ($buffer[$i] == '&') {
        $k = $i;
        $tokenaux = digui_str_read_token($buffer, false, $k);
        if (digui_is_html_entity($tokenaux)) {
            $l++;
            $i = $k;
            // @TODO: use a conversion function, to return the character 
            // associated to the html entity.
            return '&';
        }
        else {
            $i++;
            $l++;
            return $buffer[$i-1];
        }
    }
    else {
        $i++;
        $l++;
        return $buffer[$i-1];
    }
}

function digui_str_get_ending_tag($openingtag) {

    if (preg_match('/<!--/s', $token)) {
        return "-->";
    }
    
    if (preg_match('/<abbr("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</abbr>";
    }
    
    if (preg_match('/<acronym("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</acronym>";
    }
    
    if (preg_match('/<address("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</address>";
    }

    if (preg_match('/<applet("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</applet>";
    }

    if (preg_match('/<area("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</area>";
    }   

    if (preg_match('/<article("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</article>";
    }
    
    if (preg_match('/<aside("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</aside>";
    }
    
    if (preg_match('/<audio("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</audio>";
    }

    if (preg_match('/<a\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</a>";
    }
    
    if (preg_match('/<bdi("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</bdi>";
    }
    
    if (preg_match('/<bdo("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</bdo>";
    }
    
    if (preg_match('/<big("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</big>";
    }

    if (preg_match('/<blockquote("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</blockquote>";
    }
    
    if (preg_match('~<body("[^"]*"|\'[^\']*\'|[^\'">])*>~si', $token)) { 
        return "</body>";
    }

    if (preg_match('/<button("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</button>";
    }
    
    if (preg_match('/<b\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</b>";
    }
    
    if (preg_match('/<canvas("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</canvas>";
    }
    
    if (preg_match('/<caption("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</caption>";
    }
    
    if (preg_match('/<center("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</center>";
    }

    if (preg_match('/<cite("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</cite>";
    }
    
    if (preg_match('/<code("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</code>";
    }
    
    if (preg_match('/<colgroup("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</colgroup>";
    }

    if (preg_match('/<col\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</col>";
    }

    if (preg_match('/<datalist("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</datalist>";
    }

    if (preg_match('/<dd("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</dd>";
    }

    if (preg_match('/<del("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</del>";
    }
    
    if (preg_match('/<details("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</details>";
    }
    
    if (preg_match('/<dfn("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</dfn>";
    }
    
    if (preg_match('/<dialog("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</dialog>";
    }
    
    if (preg_match('/<dir("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</dir>";
    }
    
    if (preg_match('/<div("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</div>";
    }
    
    if (preg_match('/<dl("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</dl>";
    }
    if (preg_match('/<dt("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</dt>";
    }

    if (preg_match('/<em("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</em>";
    }

    if (preg_match('/<figcaption("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</figcaption>";
    }

    if (preg_match('/<figure("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</figure>";
    }
    
    if (preg_match('/<fieldset("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</fieldset>";
    }
    
    if (preg_match('/<font("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</font>";
    }

    if (preg_match('/<footer("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</footer>";
    }
    
    if (preg_match('/<form("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</form>";
    }

    if (preg_match('/<frameset("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</frameset>";
    }
    
    if (preg_match('/<frame\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</frame>";
    }
    
    if (preg_match('/<h1("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</h1>";
    }

    if (preg_match('/<h2("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</h2>";
    }
    
    if (preg_match('/<h3("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</h3>";
    }
    
    if (preg_match('/<h4("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</h4>";
    }
    
    if (preg_match('/<h5("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</h5>";
    }
    
    if (preg_match('/<h6("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</h6>";
    }
    if (preg_match('/<header("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</header>";
    }

    if (preg_match('/<head\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</head>";
    }
    
    if (preg_match('/<html("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</html>";
    }

    if (preg_match('/<iframe("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</iframe>";
    }
    
    if (preg_match('/<input("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</input>";
    }

    if (preg_match('/<ins("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</ins>";
    }

    if (preg_match('/<i\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {  
        return "</i>";
    }
    
    if (preg_match('/<kbd("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</kbd>";
    }
    
    if (preg_match('/<keygen("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</keygen>";
    }
    
    if (preg_match('/<label("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</label>";
    }
    
    if (preg_match('/<legend("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</legend>";
    }
    
    if (preg_match('/<li("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</li>";
    }

    if (preg_match('/<main("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</main>";
    }
    
    if (preg_match('/<map("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</map>";
    }   
    
    if (preg_match('/<mark("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</mark>";
    }

    if (preg_match('/<menuitem("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</menuitem>";
    }
    
    if (preg_match('/<menu("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</menu>";
    }
    
    if (preg_match('/<meter("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</meter>";
    }
    
    if (preg_match('/<nav("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</nav>";
    }
    
    if (preg_match('/<noframes("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</noframes>";
    }
    
    if (preg_match('/<noscript("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</noscript>";
    }
    
    if (preg_match('/<object("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</object>";
    }
    
    if (preg_match('/<ol("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</ol>";
    }

    if (preg_match('/<option("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</option>";
    }

    if (preg_match('/<optgroup("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</optgroup>";
    }

    if (preg_match('/<output("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</output>";
    }
    
    if (preg_match('/<pre("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</pre>";
    }
    
    if (preg_match('/<progress("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</progress>";
    }
    
    if (preg_match('/<p\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</p>";
    }

    if (preg_match('/<q\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</q>";
    }

    if (preg_match('/<rp("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</rp>";
    }
    
    if (preg_match('/<rt("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</rt>";
    }
    
    if (preg_match('/<ruby("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</ruby>";
    }
    
    if (preg_match('/<samp("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</samp>";
    }
    
    if (preg_match('/<script("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</script>";
    }

    if (preg_match('/<section("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</section>";
    }
    
    if (preg_match('/<select("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</select>";
    }

    if (preg_match('/<small("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</small>";
    }
    
    if (preg_match('/<span("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</span>";
    }
    
    if (preg_match('/<strong("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</strong>";
    }
    
    if (preg_match('/<style("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</style>";
    }
    
    if (preg_match('/<sub("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</sub>";
    }
    
    if (preg_match('/<summary("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</summary>";
    }
    
    if (preg_match('/<sup("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</sup>";
    }
    
    if (preg_match('/<s\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</s>";
    }
    
    if (preg_match('/<strike("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</strike>";
    }
    
    if (preg_match('/<table("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</table>";
    }
    if (preg_match('/<tbody("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</tbody>";
    }
    
    if (preg_match('/<td("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</td>";
    }

    if (preg_match('/<textarea("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</textarea>";
    }
    if (preg_match('/<tfoot("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</tfoot>";
    }
    
    if (preg_match('/<thead("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</thead>";
    }

    if (preg_match('/<th("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</th>";
    }

    if (preg_match('/<time("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</time>";
    }
    
    if (preg_match('/<title("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</title>";
    }
    
    if (preg_match('/<tr("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</tr>";
    }
    
    if (preg_match('/<tt("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</tt>";
    }
    
    if (preg_match('/<ul("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</ul>";
    }
    
    if (preg_match('/<u\b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</u>";
    }
    
    if (preg_match('/<var("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
        return "</var>";
    }
    
    if (preg_match('/<video("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</video>";
    }
   
    if (preg_match('/<wbr("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
        return "</wbr>";
    }
}

function digui_span_new($diguiid, $subdiguiid, $pagenum, $pageversion, $wholetext, $ltcharacters, $gtcharacters) {
 
    // 
    // Get necessary variables.
    if (!$subdigui = digui_subdigui_get_subdigui_by_id($subdiguiid)) {
        print_error('incorrectsubdiguiid', 'digui');
    }

    if (strlen($wholetext) == 0) {
        return $pageversion;
    }

    // Replace non ASCII characteres with its html entities. If we don't do 
    // this, the html document could be shown incorrectly
    // $wholetext = fm_htmlentities_ex($wholetext);

    // In order for some internal digui functions run properly, spans and other 
    // html tags must be enclosed by angle characcters.
    if (strlen($ltcharacters) > 0) {        
        $ltcharacters = explode(',', $ltcharacters);
        foreach ($ltcharacters as $i) {
            $i = intval($i);
            $wholetext[$i] = '<';
        }

        $gtcharacters = explode(',', $gtcharacters);
        foreach ($gtcharacters as $i) {
            $i = intval($i);
            $wholetext[$i] = '>';
        }
    }
    
    // In javascript script, we inserted '&lt;span&gt;' tag in the text. 
    // "&lt;span&gt;" is a literal used as control string. It is 
    // inserted at the beginning and at the end of the selected text, to 
    // calculate the start and end position of the selected text. 
    // However, maybe php replaces '&' character with 'amp;' string. So, we 
    // must replace 'amp;lt;span&amp;gt;' string with the original 
    // '&lt;span&gt;' string.
    $wholetext = str_ireplace('&amp;', '&', $wholetext);
    $wholetext = str_ireplace('&lt;openingtag&gt;', '<openingtag>', $wholetext);
    $wholetext = str_ireplace('&lt;closingtag&gt;', '<closingtag>', $wholetext);
    $selectionstart = stripos($wholetext, '<openingtag>');
    $selectionend = stripos($wholetext, '<closingtag>');
    
    // Remove the control string "&amp;lt;span&amp;gt;" from the whole text.
    // "&amp;lt;span&amp;gt;" is a literal used as control string. It is 
    // inserted at the beginning and at the end of the selected text, to 
    // calculate the start and end position of the selected text. 
    //$wholetext = str_ireplace('&lt;span&gt;', '', $wholetext);    

    $nextpageversion = $pageversion + 1;
    
    $newspans = array();
    $lastspanidontostack = null;
    $startadded = false;
    $endadded = false;
    $finished = false;
    $offset = 0;
    $newspanid = digui_span_get_new_id();
        
    $i = 0; 
    $numchars = strlen($wholetext);
    $stack = array();

    $token = '';
    do {
        if (digui_is_html_start_tag($token)) {
            if (preg_match('/<span("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) { 
                preg_match('~id=\"(.*?)\"~', $token, $matches);
                $id = $matches[1]; 
                array_push($stack, $id);

                // The start of a span (a,b), a, is inmediatly outside of a new span 
                // (a',b'). Examples of this situation are "a' b' a b". 
                // The $i variable is pointing to a. 
                if ($endadded) { 
                    $newspan = digui_span_get_by_id($id);
                    $newspan->start += $offset;
                    $newspan->id = $newspanid++;
                    $newspan->pageversion = $nextpageversion;

                    $newspans[] = $newspan;
                    $finished = true;
                }
                // The start of a older span (a,b), a, is inside of the new span (a',b'). 
                // Examples of this situation are 
                // "a' a b b'", or "a' a b' b". The $i variable is pointing to a. 
                else if ($startadded) {
                    // Get the previous span.
                    $id = end($stack);     
                    $span = digui_span_get_by_id($id);

                    // Update new span (a',a).
                    $lastnewspanadded = $newspans[count($newspans) - 1];
                    array_pop($newspans);
                    $lastnewspanadded->end = $span->start + $offset;

                    // The new generated span (a',a) must be not empty. If not, we 
                    // must discard it.
                    $newspans[] = $lastnewspanadded;
                    $offset += strlen('</span>');

                    // Create a new span, starting from a.
                    $newspan = new stdClass();
                    $newspan->pageversion = $nextpageversion;
                    $newspan->diguiid = $diguiid;
                    // $newspan->subdiguiids = digui_span_include($newspan->subdiguiid, $span->subdiguiids); 
                    $newspan->subdiguiids = digui_span_include($subdiguiid, $span->subdiguiids);
                    $newspan->pagenum = $pagenum; 
                    $newspan->start = $span->start + $offset; 
                    // $newspan->end = $selectionstart + $offset; 
                    $color = digui_color_get_color_by_subdiguiid($subdiguiid);
                    // $newspan->colorid = digui_color_get_color_sum($span->colorid, $newspan->subdiguiid, $span->subdiguiids);
                    $newspan->colorid = digui_color_get_color_sum($span->colorid, $subdiguiid, $span->subdiguiids);
                    $newspan->id = $newspanid++;

                    // Update the offset.
                    $spantohtml = digui_span_to_html($newspan);
                    $offset += (strlen($spantohtml) - strlen($token)); 

                    // Add the new span onto the stack
                    $newspans[] = $newspan;
                }
                // The start of a span, (a,b), a, is outside of a new span (a',b'). 
                //  Examples of this situation are 
                // "a a' b b'", or "a a' b' b". The $i variable is pointing to a. 
                else {
                    $newspan = digui_span_get_by_id($id);
                    $newspan->id = $newspanid++;
                    $newspan->pageversion = $nextpageversion;
                    $newspans[] = $newspan;
                }
            }
            else if (strpos($token, '<openingtag>') !== FALSE) {
                $startadded = true;
                // The stack isn't empty, so the start of the new span (a',b'), a', 
                // is inside a
                // previous span (a,b), like "a a' b' b" or "a a' b b'". 
                // The $i variable is pointing to a', 
                if (count($stack) > 0) {
                    $id = end($stack);
                    $span = digui_span_get_by_id($id);

                    // Update new span (a',a).
                    $lastnewspanadded = $newspans[count($newspans) - 1];
                    array_pop($newspans);
                    $lastnewspanadded->end = $selectionstart /*+ $offset*/;

                    // The new generated span (a',a) must be not empty. If not, we 
                    // must discard it.
                    $newspans[] = $lastnewspanadded;
                    $offset += strlen('</span>');

                    // Create a new span.
                    $newspan = new stdClass();
                    $newspan->pageversion = $nextpageversion;
                    $newspan->diguiid = $diguiid;
                    // $newspan->subdiguiids = digui_span_include($newspan->subdiguiid, $span->subdiguiids); 
                    $newspan->subdiguiids = digui_span_include($subdiguiid, $span->subdiguiids); 
                    $newspan->pagenum = $span->pagenum; 
                    $newspan->start = $selectionstart + $offset;
                    $color = digui_color_get_color_by_subdiguiid($subdiguiid);
                    // $newspan->colorid = digui_color_get_color_sum($span->colorid, $newspan->subdiguiid, $span->subdiguiids);
                    $newspan->colorid = digui_color_get_color_sum($span->colorid, $subdiguiid, $span->subdiguiids);
                    $newspan->id = $newspanid++;

                    $spantohtml = digui_span_to_html($newspan);
                    $offset += strlen($spantohtml);

                    // Add the new span onto the stack
                    $newspans[] = $newspan;
                }
                // The stack is empty, so the start of the new span (a',b'), a', span is outside of the 
                // rest of spans (a,b), like "a' a b b'", or "a' a b' b". The $i 
                // variable is pointing to a', 
                else {
                    // Create a new span.
                    $newspan = new stdClass();
                    $newspan->pageversion = $nextpageversion;
                    $newspan->diguiid = $diguiid;
                    $newspan->subdiguiids = $subdiguiid + ''; 
                    $newspan->pagenum = $pagenum; 
                    $newspan->start = $selectionstart; 
                    $color = digui_color_get_color_by_subdiguiid($subdiguiid);
                    $newspan->colorid = $color->identifier;
                    $newspan->id = $newspanid++;

                    $spantohtml = digui_span_to_html($newspan);
                    $offset += strlen($spantohtml);

                    // Add the new span onto the stack
                    $newspans[] = $newspan;
                }
            }
            else if (strpos($token, '<closingtag>') !== FALSE) {
                $endadded = true;
                // The stack isn't empty, so the end of the new span (a',b'), b', is inside a
                // previous span (a,b), like "a a' b' b" or "a' a b' b". 
                // The $i variable is pointing to b'.
                if (count($stack) > 0) {
                    $id = end($stack);

                    // Update last span added.
                    $lastnewspanadded = $newspans[count($newspans) - 1];
                    array_pop($newspans);
                    $lastnewspanadded->end = $selectionend - strlen('<openingtag>') + $offset;
                    $newspans[] = $lastnewspanadded;

                    $offset += strlen('</span>');

                    // Add new span after b'.
                    $span = digui_span_get_by_id($id);
                    $newspan = new stdClass();
                    $newspan->pageversion = $nextpageversion;
                    $newspan->diguiid = $span->diguiid;
                    $newspan->subdiguiids = $span->subdiguiids; 
                    $newspan->pagenum = $span->pagenum; 
                    $newspan->start = $selectionend - strlen('<openingtag>') + $offset;                 
                    $newspan->colorid = $span->colorid;
                    $newspan->id = $newspanid++;
                    $newspans[] = $newspan;

                    $spantohtml = digui_span_to_html($newspan);
                    $offset += strlen($spantohtml);
                }
                // The stack is empty, so the end of the new span (a',b'), b', is outside of the 
                // rest of spans (a,b), like "a' a b b'", or "a a' b b'". The $i 
                // variable is pointing to a', 
                else {
                    // Update last span added. 
                    $lastnewspanadded = $newspans[count($newspans) - 1]; 
                    array_pop($newspans);
                    $lastnewspanadded->end = $selectionend - strlen('<openingtag>') + $offset;

                    // The new generated span (a',a) must be not empty. If not, we 
                    // must discard it.
                    // $spantohtml = digui_span_to_html($lastnewspanadded);
                    $newspans[] = $lastnewspanadded;
                    $offset += strlen('</span>');
                }
            }
            else {
                // Nuevo código.
                if ($startadded && !$endadded) { 
                    // Get the previous span.
                    $id = end($stack);     
                    $span = digui_span_get_by_id($id);

                    // Update new span (a',a).
                    $lastnewspanadded = $newspans[count($newspans) - 1];
                    array_pop($newspans);
                    $lastnewspanadded->end = $span->start + $offset;

                    // The new generated span (a',a) must be not empty. If not, we 
                    // must discard it.
                    $newspans[] = $lastnewspanadded;
                    $offset += strlen('</span>');

                    // Create a new span, starting from a.
                    $newspan = new stdClass();
                    $newspan->pageversion = $nextpageversion;
                    $newspan->diguiid = $diguiid;
                    // $newspan->subdiguiids = digui_span_include($newspan->subdiguiid, $span->subdiguiids); 
                    $newspan->subdiguiids = digui_span_include($subdiguiid, $span->subdiguiids);
                    $newspan->pagenum = $pagenum; 
                    $newspan->start = $span->start + $offset; 
                    // $newspan->end = $selectionstart + $offset; 
                    $color = digui_color_get_color_by_subdiguiid($subdiguiid);
                    // $newspan->colorid = digui_color_get_color_sum($span->colorid, $newspan->subdiguiid, $span->subdiguiids);
                    $newspan->colorid = digui_color_get_color_sum($span->colorid, $subdiguiid, $span->subdiguiids);
                    $newspan->id = $newspanid++;

                    // Update the offset.
                    $spantohtml = digui_span_to_html($newspan);
                    $offset += (strlen($spantohtml) - strlen($token)); 

                    // Add the new span onto the stack
                    $newspans[] = $newspan;
                }
                // Nuevo código hasta aquí.
            }
        }
        else if (digui_is_html_end_tag($token)) {
            
            if (strpos($token, '</span>') !== FALSE) { 

                $lastspanidontostack = $stack[count($stack) - 1]; 
                array_pop($stack);

                // The end of a span (c,d), d, is outside of a new span (a',b'). 
                // Examples of this situation are "c a' a b' b d". 
                // The $i variable is pointing to b. 
                if ($finished) {
                    // Update new span.
                    $lastnewspanadded = $newspans[count($newspans) - 1];
                    array_pop($newspans);
                    $lastnewspanadded->end += $offset; 

                    $newspans[] = $lastnewspanadded;
                }
                // The end of a span (a,b), b, is inmediatly outside of a new span 
                // (a',b'). Examples of this situation are "a' a b' b", or 
                // "a a' b' b". 
                // The $i variable is pointing to b. 
                else if ($endadded) { 
                    $span = digui_span_get_by_id($lastspanidontostack);

                    // Update new span.
                    $lastnewspanadded = $newspans[count($newspans) - 1];
                    array_pop($newspans);
                    $lastnewspanadded->end = $span->end + $offset; // fallo

                    // The new generated span (a',a) must be not empty. If not, we 
                    // must discard it.
                    $newspans[] = $lastnewspanadded;
                    $finished = true;
                }
                // The end of a span (a,b), b, is inside of a new span (a',b'). 
                // Examples of this situation are "a' a b b'", or "a a' b b'". 
                // The $i variable is pointing to b. 
                else if ($startadded) {
                    // Get the old span (a,b).
                    $span = digui_span_get_by_id($lastspanidontostack);

                    // Update new span (a',b).
                    $lastnewspanadded = $newspans[count($newspans) - 1];
                    array_pop($newspans);
                    $lastnewspanadded->end = $span->end + $offset; 
                    $newspans[] = $lastnewspanadded;

                    // Create a new span, starting from b.
                    $newspan = new stdClass();
                    $newspan->pageversion = $nextpageversion;
                    $newspan->diguiid = $diguiid;
                    // $newspan->subdiguiids = digui_span_include($subdiguiid, $span->subdiguiids); 
                    $newspan->subdiguiids = $subdiguiid; 
                    $newspan->pagenum = $pagenum; 
                    $newspan->start = $span->end + $offset; 
                    $color = digui_color_get_color_by_subdiguiid($subdiguiid);
                    $newspan->colorid = $color->identifier;
                    $newspan->id = $newspanid++;

                    $spantohtml = digui_span_to_html($newspan);
                    $offset += strlen($spantohtml);

                    // Add the new span onto the stack
                    $newspans[] = $newspan;
                }
                // The end of a span (a,b), b, is outside of a new span (a',b'). 
                // Examples of this situation are "a b a' b'". 
                // The $i variable is pointing to b. 
                else {
                    // Nothing to do. 
                }
            }
            // Nuevo código.
            else {
                if ($startadded && !$endadded) { 
                    // Get the old span (a,b).
                    $span = digui_span_get_by_id($lastspanidontostack);

                    // Update new span (a',b).
                    $lastnewspanadded = $newspans[count($newspans) - 1];
                    array_pop($newspans);
                    $lastnewspanadded->end = $span->end + $offset; 
                    $newspans[] = $lastnewspanadded;

                    // Create a new span, starting from b.
                    $newspan = new stdClass();
                    $newspan->pageversion = $nextpageversion;
                    $newspan->diguiid = $diguiid;
                    // $newspan->subdiguiids = digui_span_include($subdiguiid, $span->subdiguiids); 
                    $newspan->subdiguiids = $subdiguiid; 
                    $newspan->pagenum = $pagenum; 
                    $newspan->start = $span->end + $offset; 
                    $color = digui_color_get_color_by_subdiguiid($subdiguiid);
                    $newspan->colorid = $color->identifier;
                    $newspan->id = $newspanid++;

                    $spantohtml = digui_span_to_html($newspan);
                    $offset += strlen($spantohtml);

                    // Add the new span onto the stack
                    $newspans[] = $newspan;
                }
            }
            // Nuevo código hasta aquí
        }
        
        // else if ($i == $selectionstart) {
        
        // else if ($i == $selectionend) {
        
    }
    // while (($token = digui_str_read_token_ex($wholetext, $i)) != '');
    while (($token = read_token($wholetext, $numchars, $i)) != '');
    
    // Remove spans without text between them, that is, the empty spans.
    $offset = 0;
    for ($i = 0, $j = count($newspans); $i < $j; $i++) {
        $span = $newspans[$i];
        $spantohtml = digui_span_to_html($span);
        $spanchars = strlen($spantohtml);
        // The span doesn't contain text between its tags. So we must remove it.
        if (($span->end - $spanchars - $span->start) == 0) { // **
            $spanchars += strlen('</span>');
            // Update the start and end position of the following spans.
            for ($k = $i+1, $l = count($newspans); $k < $l; $k++) {
                $newspans[$k]->start -= $spanchars;
                $newspans[$k]->end -= $spanchars;
            }

            // Remove the span from array.
            $newspans = digui_array_remove_by_index($newspans, $i);

            $i--;
            $j--;
        }    
    }
    
    // Insert spans in database.
    foreach ($newspans as $span) {
        digui_span_add($span);
    }
    
    return $nextpageversion;
}

/**
 * Summarize a text based on pattern.
 * @param string $buffer, text to summarize.
 * @param string $append, if it is true, text is appended.
 * @param $i, index into text of current read position.
 * @return string, text summarized.
 */
function digui_str_summarize_text_recursive($buffer, $append, & $i) {
    $bufferAux = '';
    $j = strlen($buffer);

    while (($token = read_token($buffer, $j, $i)) != '') {
        if (preg_match('/<span("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) {
            $bufferAux .= digui_str_summarize_text_recursive($buffer, true, $i);
            $bufferAux .= " (...) ";
        }
        else if (digui_is_html_start_tag($token)) {
            $bufferAux .= digui_str_summarize_text_recursive($buffer, false, $i);
        }
        else if (digui_is_html_end_tag($token)) {
            break;
        }
        else if ($append) {
            $bufferAux .= $token;
        }
    }
    return $bufferAux;
}

/**
 * Convert special characters to HTML entities. See fm_htmlentities_ex 
 * function.
 * @param string $text, text that contains characters to convert.
 * @return string, text with characters converted.
 */ 
function digui_html_entity_decode_ex($text) {
    $text = str_ireplace('&#225;','á', $text);
    $text = str_ireplace('&#233;','é', $text);
    $text = str_ireplace('&#237;','í', $text);
    $text = str_ireplace('&#243;','ó', $text);
    $text = str_ireplace('&#250;','ú', $text);
    $text = str_ireplace('&#193;','Á', $text);
    $text = str_ireplace('&#201;','É', $text);
    $text = str_ireplace('&#205;','Í', $text);
    $text = str_ireplace('&#211;','Ó', $text);
    $text = str_ireplace('&#218;','Ú', $text);
                                  
    $text = str_ireplace('&#224;','à', $text);
    $text = str_ireplace('&#232;','è', $text);
    $text = str_ireplace('&#236;','ì', $text);
    $text = str_ireplace('&#242;','ò', $text);
    $text = str_ireplace('&#249;','ù', $text);
    $text = str_ireplace('&#192;','À', $text);
    $text = str_ireplace('&#200;','È', $text);
    $text = str_ireplace('&#204;','Ì', $text);
    $text = str_ireplace('&#210;','Ò', $text);
    $text = str_Replace('&#217;','Ù', $text);
                                  
    $text = str_ireplace('&#226;','â', $text);
    $text = str_ireplace('&#234;','ê', $text);
    $text = str_ireplace('&#238;','î', $text);
    $text = str_ireplace('&#244;','ô', $text);
    $text = str_ireplace('&#251;','û', $text);
    $text = str_ireplace('&#194;','Â', $text);
    $text = str_ireplace('&#202;','Ê', $text);
    $text = str_ireplace('&#206;','Î', $text);
    $text = str_ireplace('&#212;','Ô', $text);
    $text = str_ireplace('&#219;','Û', $text);
                                  
    $text = str_ireplace('&#228;','ä', $text);
    $text = str_ireplace('&#235;','ë', $text);
    $text = str_ireplace('&#239;','ï', $text);
    $text = str_ireplace('&#246;','ö', $text);
    $text = str_ireplace('&#252;','ü', $text);
    $text = str_ireplace('&#196;','Ä', $text);
    $text = str_ireplace('&#203;','Ë', $text);
    $text = str_ireplace('&#207;','Ï', $text);
    $text = str_ireplace('&#214;','Ö', $text);
    $text = str_ireplace('&#220;','Ü', $text);
                                  
    $text = str_ireplace('&#231;','ç', $text);
    $text = str_ireplace('&#199;','Ç', $text);
    $text = str_ireplace('&#229;','å', $text);
    $text = str_ireplace('&#197;','Å', $text);
    
    return $text;    
}

function digui_is_html_end_tag($token) {
    
    if ($token == "-->" || 
    $token == "</a>" || 
    $token == "</abbr>" || 
    $token == "</acronym>" || 
    $token == "</address>" || 
    $token == "</applet>" || 
    $token == "</area>" || 
    $token == "</article>" || 
    $token == "</aside>" || 
    $token == "</audio>" || 
    $token == "</b>" || 
    $token == "</bdi>" || 
    $token == "</bdo>" || 
    $token == "</big>" || 
    $token == "</blockquote>" || 
    $token == "</body>" || 
    $token == "</button>" || 
    $token == "</canvas>" || 
    $token == "</caption>" ||
    $token == "</center>" || 
    $token == "</cite>" || 
    $token == "</code>" || 
    $token == "</colgroup>" || 
    $token == "</datalist>" || 
    $token == "</dd>" || 
    $token == "</col>" || 
    $token == "</del>" || 
    $token == "</details>" || 
    $token == "</dfn>" || 
    $token == "</dialog>" || 
    $token == "</dir>" || 
    $token == "</div>" || 
    $token == "</dl>" || 
    $token == "</dt>" || 
    $token == "</em>" || 
    $token == "</fieldset>" || 
    $token == "</figcaption>" ||         
    $token == "</figure>" || 
    $token == "</font>" || 
    $token == "</footer>" || 
    $token == "</form>" || 
    $token == "</frame>" ||         
    $token == "</frameset>" || 
    preg_match('/<\/h(\d){1}>/s', $token) == 1 || 
    $token == "</head>" || 
    $token == "</header>" || 
    $token == "</html>" || 
    $token == "</i>" || 
    $token == "</iframe>" || 
    $token == "</input>" || 
    $token == "</ins>" || 
    $token == "</kbd>" || 
    $token == "</keygen>" || 
    $token == "</label>" || 
    $token == "</legend>" || 
    $token == "</li>" || 
    $token == "</main>" || 
    $token == "</map>" || 
    $token == "</mark>" || 
    $token == "</menu>" || 
    $token == "</menuitem>" ||         
    $token == "</meter>" || 
    $token == "</<nav>" || 
    $token == "</noframes>" || 
    $token == "</noscript>" || 
    $token == "</object>" || 
    $token == "</ol>" || 
    $token == "</option>" || 
    $token == "</optgroup>" || 
    $token == "</output>" || 
    $token == "</p>" |
    $token == "</pre>" || 
    $token == "</progress>" || 
    $token == "</q>" ||         
    $token == "</rp>" || 
    $token == "</rt>" || 
    $token == "</ruby>" || 
    $token == "</samp>" || 
    $token == "</script>" || 
    $token == "</section>" ||         
    $token == "</select>" || 
    $token == "</small>" ||
    $token == "</span>" || 
    $token == "</strike>" || 
    $token == "</strong>" || 
    $token == "</style>" || 
    $token == "</sub>" || 
    $token == "</summary>" ||
    $token == "</sup>" || 
    $token == "</table>" || 
    $token == "</tbody>" || 
    $token == "</td>" || 
    $token == "</textarea>" || 
    $token == "</tfoot>" || 
    $token == "</th>" || 
    $token == "</thead>" || 
    $token == "</time>" || 
    $token == "</title>" || 
    $token == "</tr>" || 
    $token == "</tt>" || 
    $token == "</u>" || 
    $token == "</ul>" || 
    $token == "</var>" || 
    $token == "</video>" || 
    $token == "</wbr>") {
        return true;
    }
    
    return false;

}

function digui_span_insert($text, $spans) {
   
//    $spans = digui_span_get_by_diguiid($subdiguiid, $pagenum);
    
//    $text = digui_str_remove_span_tags($text, -1);
    if (!is_null($spans) && count($spans) > 0) {

        // We'll use the substr_replace function to insert the spans in the
        // text, so we need to order the spans by it position, from min to max. 
        // So, we create an associative array (the keys must be uniques).
        foreach($spans as $span) {
            $spansaux["start," . $span->id] = $span->start;
            $spansaux["end," . $span->id] = $span->end;
            // $text = digui_str_add_span_tag($text, $span);
        }

        // Order the spans by value (its position).
        asort($spansaux);
        
        // Maybe, the end position of a span, is equal to the start position of 
        // the next span, like (a,b)(c,d), where b=c. In this case, the 
        // substr_replace function insert the spans in inverse (and incorrect) 
        // order, like (a,c)(b,d). To solve it, we must swap the spans involved,
        // b and c.
        $arraykeys = array_keys($spansaux);
        $arrayvalues = array_values($spansaux);
        
        for ($i = 0, $j = count($arraykeys); $i < $j; $i++) {
            
            if ($i < $j-1) {
                $key1 = $arraykeys[$i];
                $value1 = $arrayvalues[$i];
                $key2 = $arraykeys[$i+1];
                $value2 = $arrayvalues[$i+1];

                $keyparts1 = explode(',', $key1);
                $keyparts2 = explode(',', $key2);
                //
                if ($keyparts1[0] == "end" && $keyparts2[0] == "start" && 
                        $value1 == $value2) {
                    // Intercambiar keys.
                    $keyaux = $arraykeys[$i];
                    $arraykeys[$i] = $arraykeys[$i+1];
                    $arraykeys[$i+1] = $keyaux;
                    // Intercambiar values.
                    $valueaux = $arrayvalues[$i];
                    $arrayvalues[$i] = $arrayvalues[$i+1];
                    $arrayvalues[$i+1] = $valueaux;
                }
            }
        }
        
        // foreach($spansaux as $key => $value) {
        for ($i = 0, $j = count($spansaux); $i < $j; $i++) {
            $key = $arraykeys[$i];
            $value = $arrayvalues[$i];
            
            $keyparts = explode(',', $key);
            if ($keyparts[0] == "start") {
                $span = digui_span_get_by_id($keyparts[1]);
                $spantohtml = digui_span_to_html($span);
            }
            else {
                $spantohtml = "</span>";
            }
            $text = substr_replace($text, $spantohtml, $value, 0);
        }
    }
    return $text;
}

function digui_is_html_entity($token) {
    if($token=="&quot;"||
        $token=="&amp;"||
        $token=="&apos;"||
        $token=="&lt;"||
        $token=="&gt;"||
        $token=="&nbsp;"||
        $token=="&iexcl;"||
        $token=="&cent;"||
        $token=="&pound;"||
        $token=="&curren;"||
        $token=="&yen;"||
        $token=="&brvbar;"||
        $token=="&sect;"||
        $token=="&uml;"||
        $token=="&copy;"||
        $token=="&ordf;"||
        $token=="&laquo;"||
        $token=="&not;"||
        $token=="&shy;"||
        $token=="&reg;"||
        $token=="&macr;"||
        $token=="&deg;"||
        $token=="&plusmn;"||
        $token=="&sup2;"||
        $token=="&sup3;"||
        $token=="&acute;"||
        $token=="&micro;"||
        $token=="&para;"||
        $token=="&middot;"||
        $token=="&cedil;"||
        $token=="&sup1;"||
        $token=="&ordm;"||
        $token=="&raquo;"||
        $token=="&frac14;"||
        $token=="&frac12;"||
        $token=="&frac34;"||
        $token=="&iquest;"||
        $token=="&Agrave;"||
        $token=="&Aacute;"||
        $token=="&Acirc;"||
        $token=="&Atilde;"||
        $token=="&Auml;"||
        $token=="&Aring;"||
        $token=="&AElig;"||
        $token=="&Ccedil;"||
        $token=="&Egrave;"||
        $token=="&Eacute;"||
        $token=="&Ecirc;"||
        $token=="&Euml;"||
        $token=="&Igrave;"||
        $token=="&Iacute;"||
        $token=="&Icirc;"||
        $token=="&Iuml;"||
        $token=="&ETH;"||
        $token=="&Ntilde;"||
        $token=="&Ograve;"||
        $token=="&Oacute;"||
        $token=="&Ocirc;"||
        $token=="&Otilde;"||
        $token=="&Ouml;"||
        $token=="&times;"||
        $token=="&Oslash;"||
        $token=="&Ugrave;"||
        $token=="&Uacute;"||
        $token=="&Ucirc;"||
        $token=="&Uuml;"||
        $token=="&Yacute;"||
        $token=="&THORN;"||
        $token=="&szlig;"||
        $token=="&agrave;"||
        $token=="&aacute;"||
        $token=="&acirc;"||
        $token=="&atilde;"||
        $token=="&auml;"||
        $token=="&aring;"||
        $token=="&aelig;"||
        $token=="&ccedil;"||
        $token=="&egrave;"||
        $token=="&eacute;"||
        $token=="&ecirc;"||
        $token=="&euml;"||
        $token=="&igrave;"||
        $token=="&iacute;"||
        $token=="&icirc;"||
        $token=="&iuml;"||
        $token=="&eth;"||
        $token=="&ntilde;"||
        $token=="&ograve;"||
        $token=="&oacute;"||
        $token=="&ocirc;"||
        $token=="&otilde;"||
        $token=="&ouml;"||
        $token=="&divide;"||
        $token=="&oslash;"||
        $token=="&ugrave;"||
        $token=="&uacute;"||
        $token=="&ucirc;"||
        $token=="&uuml;"||
        $token=="&yacute;"||
        $token=="&thorn;"||
        $token=="&yuml;"||
        $token=="&OElig;"||
        $token=="&oelig;"||
        $token=="&Scaron;"||
        $token=="&scaron;"||
        $token=="&Yuml;"||
        $token=="&fnof;"||
        $token=="&circ;"||
        $token=="&tilde;"||
        $token=="&Alpha;"||
        $token=="&Beta;"||
        $token=="&Gamma;"||
        $token=="&Delta;"||
        $token=="&Epsilon;"||
        $token=="&Zeta;"||
        $token=="&Eta;"||
        $token=="&Theta;"||
        $token=="&Iota;"||
        $token=="&Kappa;"||
        $token=="&Lambda;"||
        $token=="&Mu;"||
        $token=="&Nu;"||
        $token=="&Xi;"||
        $token=="&Omicron;"||
        $token=="&Pi;"||
        $token=="&Rho;"||
        $token=="&Sigma;"||
        $token=="&Tau;"||
        $token=="&Upsilon;"||
        $token=="&Phi;"||
        $token=="&Chi;"||
        $token=="&Psi;"||
        $token=="&Omega;"||
        $token=="&alpha;"||
        $token=="&beta;"||
        $token=="&gamma;"||
        $token=="&delta;"||
        $token=="&epsilon;"||
        $token=="&zeta;"||
        $token=="&eta;"||
        $token=="&theta;"||
        $token=="&iota;"||
        $token=="&kappa;"||
        $token=="&lambda;"||
        $token=="&mu;"||
        $token=="&nu;"||
        $token=="&xi;"||
        $token=="&omicron;"||
        $token=="&pi;"||
        $token=="&rho;"||
        $token=="&sigmaf;"||
        $token=="&sigma;"||
        $token=="&tau;"||
        $token=="&upsilon;"||
        $token=="&phi;"||
        $token=="&chi;"||
        $token=="&psi;"||
        $token=="&omega;"||
        $token=="&thetasym;"||
        $token=="&upsih;"||
        $token=="&piv;"||
        $token=="&ensp;"||
        $token=="&emsp;"||
        $token=="&thinsp;"||
        $token=="&zwnj;"||
        $token=="&zwj;"||
        $token=="&lrm;"||
        $token=="&rlm;"||
        $token=="&ndash;"||
        $token=="&mdash;"||
        $token=="&lsquo;"||
        $token=="&rsquo;"||
        $token=="&sbquo;"||
        $token=="&ldquo;"||
        $token=="&rdquo;"||
        $token=="&bdquo;"||
        $token=="&dagger;"||
        $token=="&Dagger;"||
        $token=="&bull;"||
        $token=="&hellip;"||
        $token=="&permil;"||
        $token=="&prime;"||
        $token=="&Prime;"||
        $token=="&lsaquo;"||
        $token=="&rsaquo;"||
        $token=="&oline;"||
        $token=="&frasl;"||
        $token=="&euro;"||
        $token=="&image;"||
        $token=="&weierp;"||
        $token=="&real;"||
        $token=="&trade;"||
        $token=="&alefsym;"||
        $token=="&larr;"||
        $token=="&uarr;"||
        $token=="&rarr;"||
        $token=="&darr;"||
        $token=="&harr;"||
        $token=="&crarr;"||
        $token=="&lArr;"||
        $token=="&uArr;"||
        $token=="&rArr;"||
        $token=="&dArr;"||
        $token=="&hArr;"||
        $token=="&forall;"||
        $token=="&part;"||
        $token=="&exist;"||
        $token=="&empty;"||
        $token=="&nabla;"||
        $token=="&isin;"||
        $token=="&notin;"||
        $token=="&ni;"||
        $token=="&prod;"||
        $token=="&sum;"||
        $token=="&minus;"||
        $token=="&lowast;"||
        $token=="&radic;"||
        $token=="&prop;"||
        $token=="&infin;"||
        $token=="&ang;"||
        $token=="&and;"||
        $token=="&or;"||
        $token=="&cap;"||
        $token=="&cup;"||
        $token=="&int;"||
        $token=="&there4;"||
        $token=="&sim;"||
        $token=="&cong;"||
        $token=="&asymp;"||
        $token=="&ne;"||
        $token=="&equiv;"||
        $token=="&le;"||
        $token=="&ge;"||
        $token=="&sub;"||
        $token=="&sup;"||
        $token=="&nsub;"||
        $token=="&sube;"||
        $token=="&supe;"||
        $token=="&oplus;"||
        $token=="&otimes;"||
        $token=="&perp;"||
        $token=="&sdot;"||
        $token=="&vellip;"||
        $token=="&lceil;"||
        $token=="&rceil;"||
        $token=="&lfloor;"||
        $token=="&rfloor;"||
        $token=="&lang;"||
        $token=="&rang;"||
        $token=="&loz;"||
        $token=="&spades;"||
        $token=="&clubs;"||
        $token=="&hearts;"||
        $token=="&diams;"||
        $token=="&#34;"||
        $token=="&#38;"||
        $token=="&#39;"||
        $token=="&#60;"||
        $token=="&#62;"||
        $token=="&#160;"||
        $token=="&#161;"||
        $token=="&#162;"||
        $token=="&#163;"||
        $token=="&#164;"||
        $token=="&#165;"||
        $token=="&#166;"||
        $token=="&#167;"||
        $token=="&#168;"||
        $token=="&#169;"||
        $token=="&#170;"||
        $token=="&#171;"||
        $token=="&#172;"||
        $token=="&#173;"||
        $token=="&#174;"||
        $token=="&#175;"||
        $token=="&#176;"||
        $token=="&#177;"||
        $token=="&#178;"||
        $token=="&#179;"||
        $token=="&#180;"||
        $token=="&#181;"||
        $token=="&#182;"||
        $token=="&#183;"||
        $token=="&#184;"||
        $token=="&#185;"||
        $token=="&#186;"||
        $token=="&#187;"||
        $token=="&#188;"||
        $token=="&#189;"||
        $token=="&#190;"||
        $token=="&#191;"||
        $token=="&#192;"||
        $token=="&#193;"||
        $token=="&#194;"||
        $token=="&#195;"||
        $token=="&#196;"||
        $token=="&#197;"||
        $token=="&#198;"||
        $token=="&#199;"||
        $token=="&#200;"||
        $token=="&#201;"||
        $token=="&#202;"||
        $token=="&#203;"||
        $token=="&#204;"||
        $token=="&#205;"||
        $token=="&#206;"||
        $token=="&#207;"||
        $token=="&#208;"||
        $token=="&#209;"||
        $token=="&#210;"||
        $token=="&#211;"||
        $token=="&#212;"||
        $token=="&#213;"||
        $token=="&#214;"||
        $token=="&#215;"||
        $token=="&#216;"||
        $token=="&#217;"||
        $token=="&#218;"||
        $token=="&#219;"||
        $token=="&#220;"||
        $token=="&#221;"||
        $token=="&#222;"||
        $token=="&#223;"||
        $token=="&#224;"||
        $token=="&#225;"||
        $token=="&#226;"||
        $token=="&#227;"||
        $token=="&#228;"||
        $token=="&#229;"||
        $token=="&#230;"||
        $token=="&#231;"||
        $token=="&#232;"||
        $token=="&#233;"||
        $token=="&#234;"||
        $token=="&#235;"||
        $token=="&#236;"||
        $token=="&#237;"||
        $token=="&#238;"||
        $token=="&#239;"||
        $token=="&#240;"||
        $token=="&#241;"||
        $token=="&#242;"||
        $token=="&#243;"||
        $token=="&#244;"||
        $token=="&#245;"||
        $token=="&#246;"||
        $token=="&#247;"||
        $token=="&#248;"||
        $token=="&#249;"||
        $token=="&#250;"||
        $token=="&#251;"||
        $token=="&#252;"||
        $token=="&#253;"||
        $token=="&#254;"||
        $token=="&#255;"||
        $token=="&#338;"||
        $token=="&#339;"||
        $token=="&#352;"||
        $token=="&#353;"||
        $token=="&#376;"||
        $token=="&#402;"||
        $token=="&#710;"||
        $token=="&#732;"||
        $token=="&#913;"||
        $token=="&#914;"||
        $token=="&#915;"||
        $token=="&#916;"||
        $token=="&#917;"||
        $token=="&#918;"||
        $token=="&#919;"||
        $token=="&#920;"||
        $token=="&#921;"||
        $token=="&#922;"||
        $token=="&#923;"||
        $token=="&#924;"||
        $token=="&#925;"||
        $token=="&#926;"||
        $token=="&#927;"||
        $token=="&#928;"||
        $token=="&#929;"||
        $token=="&#931;"||
        $token=="&#932;"||
        $token=="&#933;"||
        $token=="&#934;"||
        $token=="&#935;"||
        $token=="&#936;"||
        $token=="&#937;"||
        $token=="&#945;"||
        $token=="&#946;"||
        $token=="&#947;"||
        $token=="&#948;"||
        $token=="&#949;"||
        $token=="&#950;"||
        $token=="&#951;"||
        $token=="&#952;"||
        $token=="&#953;"||
        $token=="&#954;"||
        $token=="&#955;"||
        $token=="&#956;"||
        $token=="&#957;"||
        $token=="&#958;"||
        $token=="&#959;"||
        $token=="&#960;"||
        $token=="&#961;"||
        $token=="&#962;"||
        $token=="&#963;"||
        $token=="&#964;"||
        $token=="&#965;"||
        $token=="&#966;"||
        $token=="&#967;"||
        $token=="&#968;"||
        $token=="&#969;"||
        $token=="&#977;"||
        $token=="&#978;"||
        $token=="&#982;"||
        $token=="&#8194;"||
        $token=="&#8195;"||
        $token=="&#8201;"||
        $token=="&#8204;"||
        $token=="&#8205;"||
        $token=="&#8206;"||
        $token=="&#8207;"||
        $token=="&#8211;"||
        $token=="&#8212;"||
        $token=="&#8216;"||
        $token=="&#8217;"||
        $token=="&#8218;"||
        $token=="&#8220;"||
        $token=="&#8221;"||
        $token=="&#8222;"||
        $token=="&#8224;"||
        $token=="&#8225;"||
        $token=="&#8226;"||
        $token=="&#8230;"||
        $token=="&#8240;"||
        $token=="&#8242;"||
        $token=="&#8243;"||
        $token=="&#8249;"||
        $token=="&#8250;"||
        $token=="&#8254;"||
        $token=="&#8260;"||
        $token=="&#8364;"||
        $token=="&#8465;"||
        $token=="&#8472;"||
        $token=="&#8476;"||
        $token=="&#8482;"||
        $token=="&#8501;"||
        $token=="&#8592;"||
        $token=="&#8593;"||
        $token=="&#8594;"||
        $token=="&#8595;"||
        $token=="&#8596;"||
        $token=="&#8629;"||
        $token=="&#8656;"||
        $token=="&#8657;"||
        $token=="&#8658;"||
        $token=="&#8659;"||
        $token=="&#8660;"||
        $token=="&#8704;"||
        $token=="&#8706;"||
        $token=="&#8707;"||
        $token=="&#8709;"||
        $token=="&#8711;"||
        $token=="&#8712;"||
        $token=="&#8713;"||
        $token=="&#8715;"||
        $token=="&#8719;"||
        $token=="&#8721;"||
        $token=="&#8722;"||
        $token=="&#8727;"||
        $token=="&#8730;"||
        $token=="&#8733;"||
        $token=="&#8734;"||
        $token=="&#8736;"||
        $token=="&#8743;"||
        $token=="&#8744;"||
        $token=="&#8745;"||
        $token=="&#8746;"||
        $token=="&#8747;"||
        $token=="&#8756;"||
        $token=="&#8764;"||
        $token=="&#8773;"||
        $token=="&#8776;"||
        $token=="&#8800;"||
        $token=="&#8801;"||
        $token=="&#8804;"||
        $token=="&#8805;"||
        $token=="&#8834;"||
        $token=="&#8835;"||
        $token=="&#8836;"||
        $token=="&#8838;"||
        $token=="&#8839;"||
        $token=="&#8853;"||
        $token=="&#8855;"||
        $token=="&#8869;"||
        $token=="&#8901;"||
        $token=="&#8942;"||
        $token=="&#8968;"||
        $token=="&#8969;"||
        $token=="&#8970;"||
        $token=="&#8971;"||
        $token=="&#9001;"||
        $token=="&#9002;"||
        $token=="&#9674;"||
        $token=="&#9824;"||
        $token=="&#9827;"||
        $token=="&#9829;"||
        $token=="&#9830;") {
        return true;
    }
    return false;
}

/**
* Return type of a HTML tag. There are four tag types. According to type 1, this 
* tags don't end with an end tag. According to type 2, this tags end with an 
* end tag. According to type 3, this tags aren't formatting tags. According to 
* type 4, this tags are formatting tags. 
* @param string $text, text containing tags.
* @return string, type of token.
*/
function digui_str_get_type_token($token) {
    
    if (    preg_match('/&nbsp;/s', $token) == 1 ||
            preg_match('/<!--(.*?)-->/s', $token) == 1 ||
            preg_match('/<!DOCTYPE("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<base("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<basefont("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<br("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<embed("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<hr("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<img("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<link("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<meta("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<param("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<source("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<track("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) {
               return 'tokentype_1';
                }
  
    if (    preg_match('/<applet("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<area("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<audio("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<button("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<canvas("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<del("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<dialog("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<figcaption("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<figure("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<frameset("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<frame("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<head("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<iframe("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<map("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<menuitem("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<menu("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<noframes("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<noscript("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<object("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<output("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<progress("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<script("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<style("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<strike("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<title("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<video("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) {
                return 'tokentype_2';
            }
    
    if (    preg_match('/<abbr("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<acronym("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<address("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<article("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<aside("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<bdi("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<bdo("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<blockquote("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<body("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<cite("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<code("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<details("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<dfn("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<div("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<footer("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<form("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<header("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<html("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<ins("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<kbd("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<main("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<meter("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<nav("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<rp("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<rt("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<ruby("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<samp("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<section("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<span("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<summary("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<time("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<tt("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<var("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) { 
        return 'tokentype_3';
    }
    
    if (preg_match('/<big("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<caption("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<center("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<colgroup("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<datalist("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<dd("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<dir("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<dl("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<dt("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<fieldset("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<font("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<input("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<h(\d){1}("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<keygen("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<label("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<legend("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<li("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<mark("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<ol("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<option("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<optgroup("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<pre("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<select("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<small("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<strong("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<sub("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<sup("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<table("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<tbody("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<td("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
            preg_match('/<textarea("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<tfoot("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<thead("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<tr("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<ul("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
            preg_match('/<wbr("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) {           
                return 'tokentype_4';
                }

    if (preg_match('/<s("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) {
        return 'tokentype_2';
    }

    if (preg_match('/<a("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
        preg_match('/<i("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) {
        return 'tokentype_3';
    }
    
    if (preg_match('/<b("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
        preg_match('/<col("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
        preg_match('/<em("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
        preg_match('/<p("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
        preg_match('/<q("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 ||
        preg_match('/<th("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1 || 
        preg_match('/<u("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) {
        return 'tokentype_4';
    }
    
    return 'tokentype_undefined';
}

function digui_str_get_ending_tag($openingtag) {
    // Extract the first word only, including the opening angle character (<).
    // For example, if we receive "<script src='script.js'>", the following
    // instruction will extract a "<script" string.
    preg_match('/<[^\s>]+/s', $openingtag, $match);
    $endingtag = $match[0];
    // Insert a forward slash character (/). For example, if we have a 
    // "<script" string, the resulting string will be "</script".
    $endingtag = substr_replace($endingtag, '/', 1, 0);
    // Insert an angle character (/) at the end of the tag.
    $endingtag .= '>';

    return $endingtag;
}

function digui_span_intersection($text, $spans) {
    
    $spanids = array();    
    
    // if (is_null($spans) || count($spans) == 0) {
    //     return $text;
    // }
    
    // Insert spans in database.
    if (!is_null($spans) && count($spans) > 0) {
        foreach ($spans as $span) {
            $spantohtml = digui_span_to_html($span);
            preg_match('~id=\"(.*?)\"~', $spantohtml, $matches);
            $id = $matches[1]; 
            $spanids[] = intval($id);
        }
    }
    
    $i = 0;
    $token = '';
    $newtext = '';
    $spandeletedpreviously = false;
    while (($token = digui_str_read_token_ex($text, $i)) != '') {
        if (digui_is_html_start_tag($token) && preg_match('/<span("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token) == 1) { 
            
            preg_match('~id=\"(.*?)\"~', $token, $matches);
            $id = $matches[1]; 
            if (array_search($id, $spanids) !== FALSE) {
                $newtext .= $token;
            }
            else {
                $spandeletedpreviously = true;
            }
        }
        else if (digui_is_html_end_tag($token) && strpos($token, '</span>') !== FALSE) { 

            if (!$spandeletedpreviously) {
                $newtext .= $token;
            }
            else {
                $spandeletedpreviously = false;
            }
        }
        else {
            $newtext .= $token;
        }
    }
    
    return $newtext;
}

/**
 * 
 * @param int $subdiguiid, id of subdigui.
 * @param int $pagenum, page number.
 * @return object
 */
function digui_get_index_of($text, $pos) {
   
    $i = 0;
    $k = 0;
    while ($i < $pos && ($token = digui_str_read_token($text, false, $i)) != '') {
        
        if (digui_is_html_start_tag($token) || digui_is_html_end_tag($token)) {
        }
        else {
            $k += strlen($token);
        }
    }
    
    return $k - ($i - $pos);
}

function digui_span_colorize($spans, $subdiguiid) {
    foreach($spans as $span) {
        $color = digui_color_get_color_by_subdiguiid($subdiguiid);
        $span->colorid = $color->identifier;
    }
    return $spans;
}

function digui_span_colorize_ex($text, $subdiguiid) {
    
    $i = 0;
    $token = '';
    $newtext = '';
    while (($token = digui_str_read_token_ex($text, $i)) != '') {
        if (digui_is_html_start_tag($token) && preg_match('/<span("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
            
            // Get span frm database.
            preg_match('~id=\"(.*?)\"~', $token, $matches);
            $id = $matches[1]; 
            $span = digui_span_get_by_id($id);
            
            // Change the span color.
            $color = digui_color_get_color_by_subdiguiid($subdiguiid);
            $span->colorid = $color->identifier;
            
            // Insert the new span.
            $spantohtml = digui_span_to_html($span);
            $newtext .= $spantohtml;
            }
        else {
            $newtext .= $token;
        }
    }
    
    return $newtext;
}
        
/**
 * Remove HTML tags from a HTML document, except <span> tags.
 * @param string $text, text that contains the tags.
 * @return string, text without tags.
 */
function digui_strip_tags_except_span($text) {
    $i = 0;
    $textAux = digui_strip_tags_except_span_recursive($text, $i);
    return $textAux;
}

/**
 * Remove HTML tags from a HTML document, except <span> tags.
 * @param string $text, text that contains the tags.
 * @return string, text without tags.
 */
function digui_strip_tags_except_span_recursive($text, & $i) {
    $textaux = '';
    $j = strlen($text);
// 250
    while (($token = digui_str_read_token($text, false, $i)) != '') {
        if (digui_is_html_start_tag($token)) {
            if (preg_match('/<span("[^"]*"|\'[^\']*\'|[^\'">])*>/si', $token)) { 
                $textaux .= $token;
                $textaux .= digui_strip_tags_except_span_recursive($text, $i);
                // $textaux .= digui_str_get_ending_tag($token);
                $textaux .= "</span>";
            }
            else {
                $textaux .= digui_strip_tags_except_span_recursive($text, $i);
            }
        }
        else if (digui_is_html_end_tag($token)) {
            break;
        }
        else {
            $textaux .= $token;
        }
    }
    return $textaux;
}

/**
* Read a word from text based on pattern.
* @param string $text, text to read.
* @param $i, index into text of current read position.
* @return string, a word read from text.
*/
function digui_str_read_token($buffer, $endtagonlyexpected, & $i) {
    $j = strlen($buffer);
    $token = '';
    
    if ($i >= $j)
        return $token;
    
    if ($buffer[$i] == ' ') {
        while ($i < $j && $buffer[$i] == ' ') {
           $token .= $buffer[$i];
           $i++;
        }
    }
    else if ($buffer[$i] == "\r\n" || $buffer[$i] == "\r" || $buffer[$i] == "\n") {
//        while ($i < $j && ($buffer[$i] == "\r\n" || $buffer[$i] == "\r" || $buffer[$i] == "\n")) {
           $token .= $buffer[$i];
           $i++;
//        }
    }
    else if ($buffer[$i] == '<') {
        while ($i < $j && $buffer[$i] != '>') {
            $token .= $buffer[$i];
            $i++;
        }
        
        $i++;
        $token .= '>';
    }
    else if ($buffer[$i] == '&') {
        $k = 0;
        while ($i < $j && $buffer[$i] != ';' && $buffer[$i] != ' ' && 
                $buffer[$i] != '\r\n' && $buffer[$i] != '\r' && 
                $buffer[$i] != '\n' && $buffer[$i] != '<') {
            $token .= $buffer[$i];
            $i++;
            $k++;
        }
        
        if ($buffer[$i] == ';') {
            $i++;
            $k++;
            $token .= ';';
        }
    }
    else {
        while ($i < $j && $buffer[$i] != ' ' && $buffer[$i] != "\r\n" && $buffer[$i] != "\r" && $buffer[$i] != "\n" && $buffer[$i] != "&") {
            // Distinguish between "var i = 1 < 0; var j = 1 > 0;" and "<span>".
            if ($buffer[$i] == '<') {
                $j = $i;
                $tokenaux = digui_str_read_token($buffer, $endtagonlyexpected, $j);
                if (!$endtagonlyexpected && digui_is_html_start_tag($tokenaux)) {
//                    $i = $j;
                    break;
                }
                else if (digui_is_html_end_tag($tokenaux)) {
//                    $i = $j;
                    break;
                }
                else {
                    $token .= $buffer[$i];
                    $i++;
                }
            }
            else {
                $token .= $buffer[$i];
                $i++;
            }
        }
    }
    return $token;
}

/**
* Read a word from text based on pattern.
* @param string $text, text to read.
* @param $i, index into text of current read position.
* @return string, a word read from text.
*/
function digui_str_read_token_ex($buffer, & $i) {
    $j = strlen($buffer);
    $token = '';
    
    if ($i >= $j)
        return $token;
    
    if ($buffer[$i] == ' ') {
        $token .= $buffer[$i];
        $i++;
    }
    else if ($buffer[$i] == "\r\n") {
        $token .= $buffer[$i];
        $i+=2;
    }
    else if ($buffer[$i] == "\r" || $buffer[$i] == "\n") {
        $token .= $buffer[$i];
        $i++;
    }
    else if ($buffer[$i] == '<') {
        $k = $i;
        while ($k < $j && $buffer[$k] != '>') {
            $token .= $buffer[$k];
            $k++;
        }
        
        if ($k < $j) {
            $token .= '>';
            if (digui_is_html_start_tag($token) || digui_is_html_end_tag($token)) { 
                $i = $k;
                $i++;
            }
            else {
                $token = $buffer[$i];
                $i++;
            }
        }
        else {
            $token = $buffer[$i];
            $i++;
        }
    }
    else if ($buffer[$i] == '&') {
        $k = 0;
        while ($i < $j && $buffer[$i] != ';' && $buffer[$i] != ' ' && 
                $buffer[$i] != '\r\n' && $buffer[$i] != '\r' && 
                $buffer[$i] != '\n' && $buffer[$i] != '<') {
            $token .= $buffer[$i];
            $i++;
            $k++;
        }
        
        if ($buffer[$i] == ';') {
            $i++;
            $k++;
            $token .= ';';
        }
    }
    else {
        $token .= $buffer[$i];
        $i++;
    }
    return $token;
}


/**
* Read a word from text based on pattern.
* @param string $text, text to read.
* @param $i, index into text of current read position.
* @return string, a word read from text.
*/
function read_token_deprecated($buffer, $endtagonlyexpected, & $i) {
    $j = strlen($buffer);
    $token = '';
    
    if ($i >= $j)
        return $token;
    
    if ($buffer[$i] == ' ') {
        while ($i < $j && $buffer[$i] == ' ') {
           $token .= $buffer[$i];
           $i++;
        }
    }
    else if ($buffer[$i] == "\r\n" || $buffer[$i] == "\r" || $buffer[$i] == "\n") {
//        while ($i < $j && ($buffer[$i] == "\r\n" || $buffer[$i] == "\r" || $buffer[$i] == "\n")) {
           $token .= $buffer[$i];
           $i++;
//        }
    }
    else if ($buffer[$i] == '<') {
        while ($i < $j && $buffer[$i] != '>') {
            $token .= $buffer[$i];
            $i++;
        }
        
        $i++;
        $token .= '>';
    }
    else if ($buffer[$i] == '&') {
        $k = 0;
        while ($i < $j && $buffer[$i] != ';' && $buffer[$i] != ' ' && 
                $buffer[$i] != '\r\n' && $buffer[$i] != '\r' && 
                $buffer[$i] != '\n' && $buffer[$i] != '<') {
            $token .= $buffer[$i];
            $i++;
            $k++;
        }
        
        if ($buffer[$i] == ';') {
            $i++;
            $k++;
            $token .= ';';
        }
    }
    else {
        while ($i < $j && $buffer[$i] != ' ' && $buffer[$i] != "\r\n" && $buffer[$i] != "\r" && $buffer[$i] != "\n" && $buffer[$i] != "&") {
            // Distinguish between "var i = 1 < 0; var j = 1 > 0;" and "<span>".
            if ($buffer[$i] == '<') {
                $j = $i;
                $tokenaux = str_read_token($buffer, $endtagonlyexpected, $j);
                if (!$endtagonlyexpected && is_html_start_tag($tokenaux)) {
//                    $i = $j;
                    break;
                }
                else if (is_html_end_tag($tokenaux)) {
//                    $i = $j;
                    break;
                }
                else {
                    $token .= $buffer[$i];
                    $i++;
                }
            }
            else {
                $token .= $buffer[$i];
                $i++;
            }
        }
    }
    return $token;
}

/**
* Read a word from text based on pattern.
* @param string $text, text to read.
* @param $i, index into text of current read position.
* @return string, a word read from text.
*/
function read_token_ex($buffer, & $i) {
    $j = strlen($buffer);
    $token = '';
    
    if ($i >= $j)
        return $token;
    
    if ($buffer[$i] == ' ') {
        $token .= $buffer[$i];
        $i++;
    }
    else if ($buffer[$i] == "\r\n") {
        $token .= $buffer[$i];
        $i+=2;
    }
    else if ($buffer[$i] == "\r" || $buffer[$i] == "\n") {
        $token .= $buffer[$i];
        $i++;
    }
    else if ($buffer[$i] == '<') {
        $k = $i;
        while ($k < $j && $buffer[$k] != '>') {
            $token .= $buffer[$k];
            $k++;
        }
        
        if ($k < $j) {
            $token .= '>';
            if (is_html_start_tag($token) || is_html_end_tag($token)) { 
                $i = $k;
                $i++;
            }
            else {
                $token = $buffer[$i];
                $i++;
            }
        }
        else {
            $token = $buffer[$i];
            $i++;
        }
    }
    else if ($buffer[$i] == '&') {
        $k = 0;
        while ($i < $j && $buffer[$i] != ';' && $buffer[$i] != ' ' && 
                $buffer[$i] != '\r\n' && $buffer[$i] != '\r' && 
                $buffer[$i] != '\n' && $buffer[$i] != '<') {
            $token .= $buffer[$i];
            $i++;
            $k++;
        }
        
        if ($buffer[$i] == ';') {
            $i++;
            $k++;
            $token .= ';';
        }
    }
    else {
        $token .= $buffer[$i];
        $i++;
    }
    return $token;
}

/**
* Return type of a HTML tag. There are four tag types. According to type 1, this 
* tags don't end with an end tag. According to type 2, this tags end with an 
* end tag. According to type 3, this tags aren't formatting tags. According to 
* type 4, this tags are formatting tags. 
* @param string $text, text containing tags.
* @return string, type of token.
*/
function get_type_token($token) {
    
    if (    preg_match('/&nbsp;/s', $token) ||
            preg_match('/<!--(.*?)-->/s', $token) ||
            preg_match('/<!DOCTYPE[^>]*>/s', $token) || 
            preg_match('/<base[^>]*>/s', $token) || 
            preg_match('/<basefont[^>]*>/s', $token) || 
            preg_match('/<br[^>]*>/s', $token) || 
            preg_match('/<embed[^>]*>/s', $token) || 
            preg_match('/<hr[^>]*>/s', $token) || 
            preg_match('/<img[^>]*>/s', $token) ||
            preg_match('/<link[^>]*>/s', $token) ||
            preg_match('/<meta[^>]*>/s', $token) ||
            preg_match('/<param[^>]*>/s', $token) ||
            preg_match('/<source[^>]*>/s', $token) ||
            preg_match('/<track[^>]*>/s', $token)) {
               return 'tokentype_1';
                }
  
    if (    preg_match('/<applet[^>]*>/s', $token) ||
            preg_match('/<area[^>]*>/s', $token) ||
            preg_match('/<audio[^>]*>/s', $token) ||
            preg_match('/<button[^>]*>/s', $token) ||
            preg_match('/<canvas[^>]*>/s', $token) ||
            preg_match('/<del[^>]*>/s', $token) ||
            preg_match('/<dialog[^>]*>/s', $token) ||
            preg_match('/<figcaption[^>]*>/s', $token) ||
            preg_match('/<figure[^>]*>/s', $token) ||
            preg_match('/<frameset[^>]*>/s', $token) ||
            preg_match('/<frame[^>]*>/s', $token) ||
            preg_match('/<head[^>]*>/s', $token) || 
            preg_match('/<iframe[^>]*>/s', $token) ||
            preg_match('/<map[^>]*>/s', $token) ||
            preg_match('/<menuitem[^>]*>/s', $token) ||
            preg_match('/<menu[^>]*>/s', $token) ||
            preg_match('/<noframes[^>]*>/s', $token) ||
            preg_match('/<noscript[^>]*>/s', $token) ||
            preg_match('/<object[^>]*>/s', $token) ||
            preg_match('/<output[^>]*>/s', $token) ||
            preg_match('/<progress[^>]*>/s', $token) ||
            preg_match('/<script[^>]*>/s', $token) ||
            preg_match('/<style[^>]*>/s', $token) ||
            preg_match('/<strike[^>]*>/s', $token) ||
            preg_match('/<title[^>]*>/s', $token) ||
            preg_match('/<video[^>]*>/s', $token)) {
                return 'tokentype_2';
            }
    
    if (    preg_match('/<abbr[^>]*>/s', $token) || 
            preg_match('/<acronym[^>]*>/s', $token) || 
            preg_match('/<address[^>]*>/s', $token) || 
            preg_match('/<article[^>]*>/s', $token) || 
            preg_match('/<aside[^>]*>/s', $token) || 
            preg_match('/<bdi[^>]*>/s', $token) || 
            preg_match('/<bdo[^>]*>/s', $token) || 
            preg_match('/<blockquote[^>]*>/s', $token) || 
            preg_match('/<body[^>]*>/s', $token) || 
            preg_match('/<cite[^>]*>/s', $token) || 
            preg_match('/<code[^>]*>/s', $token) || 
            preg_match('/<details[^>]*>/s', $token) || 
            preg_match('/<dfn[^>]*>/s', $token) || 
            preg_match('/<div[^>]*>/s', $token) || 
            preg_match('/<footer[^>]*>/s', $token) || 
            preg_match('/<form[^>]*>/s', $token) || 
            preg_match('/<header[^>]*>/s', $token) || 
            preg_match('/<html[^>]*>/s', $token) || 
            preg_match('/<ins[^>]*>/s', $token) ||
            preg_match('/<kbd[^>]*>/s', $token) ||
            preg_match('/<main[^>]*>/s', $token) ||
            preg_match('/<meter[^>]*>/s', $token) ||
            preg_match('/<nav[^>]*>/s', $token) ||
            preg_match('/<rp[^>]*>/s', $token) ||
            preg_match('/<rt[^>]*>/s', $token) ||
            preg_match('/<ruby[^>]*>/s', $token) ||
            preg_match('/<samp[^>]*>/s', $token) ||
            preg_match('/<section[^>]*>/s', $token) ||
            preg_match('/<span[^>]*>/s', $token) || 
            preg_match('/<summary[^>]*>/s', $token) ||
            preg_match('/<time[^>]*>/s', $token) ||
            preg_match('/<tt[^>]*>/s', $token) ||
            preg_match('/<var[^>]*>/s', $token)) { 
        return 'tokentype_3';
    }
    
    if (preg_match('/<big[^>]*>/s', $token) || 
            preg_match('/<caption[^>]*>/s', $token) ||
            preg_match('/<center[^>]*>/s', $token) || 
            preg_match('/<colgroup[^>]*>/s', $token) || 
            preg_match('/<datalist[^>]*>/s', $token) || 
            preg_match('/<dd[^>]*>/s', $token) || 
            preg_match('/<dir[^>]*>/s', $token) || 
            preg_match('/<dl[^>]*>/s', $token) || 
            preg_match('/<dt[^>]*>/s', $token) || 
            preg_match('/<fieldset[^>]*>/s', $token) || 
            preg_match('/<font[^>]*>/s', $token) || 
            preg_match('/<input[^>]*>/s', $token) || 
            preg_match('/<h(\d){1}[^>]*>/s', $token) || 
            preg_match('/<keygen[^>]*>/s', $token) || 
            preg_match('/<label[^>]*>/s', $token) || 
            preg_match('/<legend[^>]*>/s', $token) || 
            preg_match('/<li[^>]*>/s', $token) || 
            preg_match('/<mark[^>]*>/s', $token) || 
            preg_match('/<ol[^>]*>/s', $token) || 
            preg_match('/<option[^>]*>/s', $token) || 
            preg_match('/<optgroup[^>]*>/s', $token) || 
            preg_match('/<pre[^>]*>/s', $token) ||
            preg_match('/<select[^>]*>/s', $token) || 
            preg_match('/<small[^>]*>/s', $token) || 
            preg_match('/<strong[^>]*>/s', $token) || 
            preg_match('/<sub[^>]*>/s', $token) || 
            preg_match('/<sup[^>]*>/s', $token) || 
            preg_match('/<table[^>]*>/s', $token) || 
            preg_match('/<tbody[^>]*>/s', $token) || 
            preg_match('/<td[^>]*>/s', $token) ||
            preg_match('/<textarea[^>]*>/s', $token) || 
            preg_match('/<tfoot[^>]*>/s', $token) || 
            preg_match('/<thead[^>]*>/s', $token) || 
            preg_match('/<tr[^>]*>/s', $token) || 
            preg_match('/<ul[^>]*>/s', $token) || 
            preg_match('/<wbr[^>]*>/s', $token)) {           
                return 'tokentype_4';
                }

    if (preg_match('/<s[^>]*>/s', $token)) {
        return 'tokentype_2';
    }

    if (preg_match('/<a[^>]*>/s', $token) ||
        preg_match('/<i[^>]*>/s', $token)) {
        return 'tokentype_3';
    }
    
    if (preg_match('/<b[^>]*>/s', $token) ||
        preg_match('/<col[^>]*>/s', $token) ||
        preg_match('/<em[^>]*>/s', $token) ||
        preg_match('/<p[^>]*>/s', $token) || 
        preg_match('/<q[^>]*>/s', $token) ||
        preg_match('/<th[^>]*>/s', $token) || 
        preg_match('/<u[^>]*>/s', $token)) {
        return 'tokentype_4';
    }
    
    return 'tokentype_undefined';
}

function is_html_start_tag($token) {
    if (($tokentype = str_get_type_token($token)) == "tokentype_1" ||
            $tokentype == "tokentype_2" ||
            $tokentype == "tokentype_3" ||
            $tokentype == "tokentype_4") {
        return true;
    }
    
    return false;
}

function get_end_token($token) {
    if (preg_match('/<!--/s', $token)) {
        return "-->";
    }
    
    if (preg_match('/<abbr[^>]*>/s', $token)) { 
        return "</abbr>";
    }
    
    if (preg_match('/<acronym[^>]*>/s', $token)) { 
        return "</acronym>";
    }
    
    if (preg_match('/<address[^>]*>/s', $token)) { 
        return "</address>";
    }

    if (preg_match('/<applet[^>]*>/s', $token)) {
        return "</applet>";
    }

    if (preg_match('/<area[^>]*>/s', $token)) {
        return "</area>";
    }   

    if (preg_match('/<article[^>]*>/s', $token)) { 
        return "</article>";
    }
    
    if (preg_match('/<aside[^>]*>/s', $token)) { 
        return "</aside>";
    }
    
    if (preg_match('/<audio[^>]*>/s', $token)) {
        return "</audio>";
    }

    if (preg_match('/<a[^>]*>/s', $token)) { 
        return "</a>";
    }
    
    if (preg_match('/<bdi[^>]*>/s', $token)) { 
        return "</bdi>";
    }
    
    if (preg_match('/<bdo[^>]*>/s', $token)) { 
        return "</bdo>";
    }
    
    if (preg_match('/<big[^>]*>/s', $token)) { 
        return "</big>";
    }

    if (preg_match('/<blockquote[^>]*>/s', $token)) { 
        return "</blockquote>";
    }
    
    if (preg_match('/<body[^>]*>/s', $token)) { 
        return "</body>";
    }

    if (preg_match('/<button[^>]*>/s', $token)) {
        return "</button>";
    }
    
    if (preg_match('/<b[^>]*>/s', $token)) { 
        return "</b>";
    }
    
    if (preg_match('/<canvas[^>]*>/s', $token)) {
        return "</canvas>";
    }
    
    if (preg_match('/<caption[^>]*>/s', $token)) {
        return "</caption>";
    }
    
    if (preg_match('/<center[^>]*>/s', $token)) { 
        return "</center>";
    }

    if (preg_match('/<cite[^>]*>/s', $token)) { 
        return "</cite>";
    }
    
    if (preg_match('/<code[^>]*>/s', $token)) { 
        return "</code>";
    }
    
    if (preg_match('/<colgroup[^>]*>/s', $token)) { 
        return "</colgroup>";
    }

    if (preg_match('/<col[^>]*>/s', $token)) { 
        return "</col>";
    }

    if (preg_match('/<datalist[^>]*>/s', $token)) { 
        return "</datalist>";
    }

    if (preg_match('/<dd[^>]*>/s', $token)) { 
        return "</dd>";
    }

    if (preg_match('/<del[^>]*>/s', $token)) {
        return "</del>";
    }
    
    if (preg_match('/<details[^>]*>/s', $token)) { 
        return "</details>";
    }
    
    if (preg_match('/<dfn[^>]*>/s', $token)) { 
        return "</dfn>";
    }
    
    if (preg_match('/<dialog[^>]*>/s', $token)) { 
        return "</dialog>";
    }
    
    if (preg_match('/<dir[^>]*>/s', $token)) { 
        return "</dir>";
    }
    
    if (preg_match('/<div[^>]*>/s', $token)) { 
        return "</div>";
    }
    
    if (preg_match('/<dl[^>]*>/s', $token)) { 
        return "</dl>";
    }
    if (preg_match('/<dt[^>]*>/s', $token)) { 
        return "</dt>";
    }

    if (preg_match('/<em[^>]*>/s', $token)) { 
        return "</em>";
    }

    if (preg_match('/<figcaption[^>]*>/s', $token)) {
        return "</figcaption>";
    }

    if (preg_match('/<figure[^>]*>/s', $token)) {
        return "</figure>";
    }
    
    if (preg_match('/<fieldset[^>]*>/s', $token)) { 
        return "</fieldset>";
    }
    
    if (preg_match('/<font[^>]*>/s', $token)) { 
        return "</font>";
    }

    if (preg_match('/<footer[^>]*>/s', $token)) { 
        return "</footer>";
    }
    
    if (preg_match('/<form[^>]*>/s', $token)) { 
        return "</form>";
    }

    if (preg_match('/<frameset[^>]*>/s', $token)) {
        return "</frameset>";
    }
    
    if (preg_match('/<frame[^>]*>/s', $token)) {
        return "</frame>";
    }
    
    if (preg_match('/<h1[^>]*>/s', $token)) { 
        return "</h1>";
    }

    if (preg_match('/<h2[^>]*>/s', $token)) { 
        return "</h2>";
    }
    
    if (preg_match('/<h3[^>]*>/s', $token)) { 
        return "</h3>";
    }
    
    if (preg_match('/<h4[^>]*>/s', $token)) { 
        return "</h4>";
    }
    
    if (preg_match('/<h5[^>]*>/s', $token)) { 
        return "</h5>";
    }
    
    if (preg_match('/<h6[^>]*>/s', $token)) { 
        return "</h6>";
    }
    if (preg_match('/<header[^>]*>/s', $token)) { 
        return "</header>";
    }

    if (preg_match('/<head[^>]*>/s', $token)) { 
        return "</head>";
    }
    
    if (preg_match('/<html[^>]*>/s', $token)) { 
        return "</html>";
    }

    if (preg_match('/<iframe[^>]*>/s', $token)) {
        return "</iframe>";
    }
    
    if (preg_match('/<input[^>]*>/s', $token)) { 
        return "</input>";
    }

    if (preg_match('/<ins[^>]*>/s', $token)) {
        return "</ins>";
    }

    if (preg_match('/<i[^>]*>/s', $token)) {  
        return "</i>";
    }
    
    if (preg_match('/<kbd[^>]*>/s', $token)) {
        return "</kbd>";
    }
    
    if (preg_match('/<keygen[^>]*>/s', $token)) { 
        return "</keygen>";
    }
    
    if (preg_match('/<label[^>]*>/s', $token)) { 
        return "</label>";
    }
    
    if (preg_match('/<legend[^>]*>/s', $token)) { 
        return "</legend>";
    }
    
    if (preg_match('/<li[^>]*>/s', $token)) { 
        return "</li>";
    }

    if (preg_match('/<main[^>]*>/s', $token)) {
        return "</main>";
    }
    
    if (preg_match('/<map[^>]*>/s', $token)) {
        return "</map>";
    }   
    
    if (preg_match('/<mark[^>]*>/s', $token)) { 
        return "</mark>";
    }

    if (preg_match('/<menuitem[^>]*>/s', $token)) {
        return "</menuitem>";
    }
    
    if (preg_match('/<menu[^>]*>/s', $token)) {
        return "</menu>";
    }
    
    if (preg_match('/<meter[^>]*>/s', $token)) {
        return "</meter>";
    }
    
    if (preg_match('/<nav[^>]*>/s', $token)) {
        return "</nav>";
    }
    
    if (preg_match('/<noframes[^>]*>/s', $token)) {
        return "</noframes>";
    }
    
    if (preg_match('/<noscript[^>]*>/s', $token)) {
        return "</noscript>";
    }
    
    if (preg_match('/<object[^>]*>/s', $token)) {
        return "</object>";
    }
    
    if (preg_match('/<ol[^>]*>/s', $token)) { 
        return "</ol>";
    }

    if (preg_match('/<option[^>]*>/s', $token)) { 
        return "</option>";
    }

    if (preg_match('/<optgroup[^>]*>/s', $token)) { 
        return "</optgroup>";
    }

    if (preg_match('/<output[^>]*>/s', $token)) {
        return "</output>";
    }
    
    if (preg_match('/<pre[^>]*>/s', $token)) {
        return "</pre>";
    }
    
    if (preg_match('/<progress[^>]*>/s', $token)) {
        return "</progress>";
    }
    
    if (preg_match('/<p[^>]*>/s', $token)) { 
        return "</p>";
    }

    if (preg_match('/<q[^>]*>/s', $token)) { 
        return "</q>";
    }

    if (preg_match('/<rp[^>]*>/s', $token)) {
        return "</rp>";
    }
    
    if (preg_match('/<rt[^>]*>/s', $token)) {
        return "</rt>";
    }
    
    if (preg_match('/<ruby[^>]*>/s', $token)) {
        return "</ruby>";
    }
    
    if (preg_match('/<samp[^>]*>/s', $token)) {
        return "</samp>";
    }
    
    if (preg_match('/<script[^>]*>/s', $token)) {
        return "</script>";
    }

    if (preg_match('/<section[^>]*>/s', $token)) {
        return "</section>";
    }
    
    if (preg_match('/<select[^>]*>/s', $token)) { 
        return "</select>";
    }

    if (preg_match('/<small[^>]*>/s', $token)) { 
        return "</small>";
    }
    
    if (preg_match('/<span[^>]*>/s', $token)) { 
        return "</span>";
    }
    
    if (preg_match('/<strong[^>]*>/s', $token)) { 
        return "</strong>";
    }
    
    if (preg_match('/<style[^>]*>/s', $token)) {
        return "</style>";
    }
    
    if (preg_match('/<sub[^>]*>/s', $token)) { 
        return "</sub>";
    }
    
    if (preg_match('/<summary[^>]*>/s', $token)) { 
        return "</summary>";
    }
    
    if (preg_match('/<sup[^>]*>/s', $token)) { 
        return "</sup>";
    }
    
    if (preg_match('/<s[^>]*>/s', $token)) {
        return "</s>";
    }
    
    if (preg_match('/<strike[^>]*>/s', $token)) {
        return "</strike>";
    }
    
    if (preg_match('/<table[^>]*>/s', $token)) { 
        return "</table>";
    }
    if (preg_match('/<tbody[^>]*>/s', $token)) { 
        return "</tbody>";
    }
    
    if (preg_match('/<td[^>]*>/s', $token)) { 
        return "</td>";
    }

    if (preg_match('/<textarea[^>]*>/s', $token)) { 
        return "</textarea>";
    }
    if (preg_match('/<tfoot[^>]*>/s', $token)) { 
        return "</tfoot>";
    }
    
    if (preg_match('/<thead[^>]*>/s', $token)) { 
        return "</thead>";
    }

    if (preg_match('/<th[^>]*>/s', $token)) { 
        return "</th>";
    }

    if (preg_match('/<time[^>]*>/s', $token)) {
        return "</time>";
    }
    
    if (preg_match('/<title[^>]*>/s', $token)) {
        return "</title>";
    }
    
    if (preg_match('/<tr[^>]*>/s', $token)) { 
        return "</tr>";
    }
    
    if (preg_match('/<tt[^>]*>/s', $token)) {
        return "</tt>";
    }
    
    if (preg_match('/<ul[^>]*>/s', $token)) { 
        return "</ul>";
    }
    
    if (preg_match('/<u[^>]*>/s', $token)) { 
        return "</u>";
    }
    
    if (preg_match('/<var[^>]*>/s', $token)) { 
        return "</var>";
    }
    
    if (preg_match('/<video[^>]*>/s', $token)) {
        return "</video>";
    }
   
    if (preg_match('/<wbr[^>]*>/s', $token)) {
        return "</wbr>";
    }
}

