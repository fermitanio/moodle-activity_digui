function undoHighlight_ex() {

upageversion = document.getElementById("upageversion");

if (upageversion.value > 1) {
	upageversion = document.getElementById("upageversion");
	upageversion.value = parseInt(upageversion.value) - 1;
	
	// if (window.currentVersion == 0) {
	// 	setSaveButton(false);
	// }
	
	var form = document.getElementById("undoform");
	form.submit();
}
}

function redoHighlight_ex() {

rpageversion = document.getElementById("rpageversion");
maxpageversion = document.getElementById("maxpageversion");
if (rpageversion.value < maxpageversion.value) {
	rpageversion = document.getElementById("rpageversion");
	rpageversion.value = parseInt(rpageversion.value) + 1;
	
	// if (window.currentVersion == 0) {
	// 	setSaveButton(false);
	// }
	
	var form = document.getElementById("redoform");
	form.submit();
}
}

function sendSubrayado() {

var range = window.getSelection().getRangeAt(0);
// range.deleteContents();

var selectionlength = document.getElementById("selectionlength");
selectionlength.value = range.toString().length;

// Insert a span tag at the beginning of the selected text. Inserting this span
// cause the same tag to be inserted in the whole text. We use this tag, to 
// search for this tag in the whole text of digui, and in this way get the 
// start position (in characters) of the selected text within the whole text of 
// digui.
range.insertNode(document.createTextNode("&lt;openingtag&gt;"));

// Insert a span tag at the end of the selected text. Inserting this span
// cause the same tag to be inserted in the whole text. We use this tag, to 
// search for this tag in the whole text of digui, and in this way get the 
// end position (in characters) of the selected text within the whole text of 
// digui.
range.collapse(false);
range.insertNode(document.createTextNode("&lt;closingtag&gt;"));

var wholeText = document.getElementById("textdiv").innerHTML;

// Before, we inserted '&lt;openingtag&gt;' tag in the text, but Javascript replaces 
// '&' character with 'amp;' string. In php scripts, we'll consider '&' 
// character only, so now we must replace 'amp;lt;openingtag&amp;gt;' string with the 
// original '&lt;openingtag&gt;' string.
wholeText = wholeText.replace(/&amp;/g, "&");

// Replace accented letters by equivalent html code, because in php scripts 
// we'll consider html entities only.
wholeText = htmlEntities(wholeText);

wholeText = wholeText.replace(/</g, "openinganglebracket");
wholeText = wholeText.replace(/>/g, "closinganglebracket");

var wholeTextField = document.getElementById("wholetext");
wholeTextField.value = wholeText;

//alert(wholeText);

var form = document.getElementById("hform");
form.submit();

}

function sendNotes() {

var textareahidden = document.getElementById("textareahidden");
var textarea = document.getElementById("textarea");
textareahidden.value = textarea.value;
var form = document.getElementById("sform");
form.submit();

}

function sendSelectedGroupId() {

var form = document.getElementById("formdata");
var selecttag = document.getElementById("selecttag");
var selectedgroupidhidden = document.getElementById("selectedgroupidhidden");
selectedgroupidhidden.value = selecttag.options[selecttag.selectedIndex].value;
form.submit();
}

function sendSelectedSubdiguiId() {

var form = document.getElementById("formdata");
var selecttag = document.getElementById("selecttag");
var selectedsubdiguiidhidden = document.getElementById("selectedsubdiguiidhidden");
selectedsubdiguiidhidden.value = selecttag.options[selecttag.selectedIndex].value;
form.submit();
}

function unHighlightText() 
{
// La primera vez que el usuario edita la página, 
// debemos guardar la versión original de esa página.
if (window.pageVersions === undefined || window.pageVersions === null) {
	window.pageVersions = new Array();
	window.currentVersion = 0;
	pushVersion();
	}
	
var textdiv = document.getElementById("textdiv");
textdiv.innerHTML = remove_tags(textdiv.innerHTML);
pushVersion();
}

function remove_tags(text) {

newtext = '';
for (i = 0, j = text.length; i < j; i++) {
    if (text[i] == '<') {
        for (; text[i] != '>'; i++);
    }
    else {
       newtext += text[i];
    }
}
return newtext;
}

function setSaveButton(enabled) {
var boton = document.getElementById("Guardar");

if (enabled) {
	boton.disabled = false;
}
else {
	boton.disabled = true;
}
}

function isChildNode(parent, child) {
	 if (parent == child) {
 			 return true;
         }
	 var node = child.parentNode;
     while (node != null) {
         if (node == parent) {
 			 return true;
         }
         node = node.parentNode;
     }
    return false;
}

function pushVersion() {

// El usuario ha modificado la página, pero ha presionado previamente
// el botón de "Deshacer". Debemos borrar estas versiones anteriores.
if (window.currentVersion != window.pageVersions.length - 1) {
	for (i = window.pageVersions.length; i > window.currentVersion + 1; i--) {
		window.pageVersions.pop();
	}
}

var textdiv = document.getElementById("textdiv");
window.pageVersions.push(textdiv.innerHTML);

window.currentVersion = window.pageVersions.length - 1;
}

/*
 * This function is similar to the fm_htmlentities_ex function, and must 
 * include the characters of this function.
 */
function htmlEntities(text) {
    // @TODO: add all spacial characters.
    text = text.replace(/á/g,'&#225;');
    text = text.replace(/é/g,'&#233;');
    text = text.replace(/í/g,'&#237;');
    text = text.replace(/ó/g,'&#243;');
    text = text.replace(/ú/g,'&#250;');
    text = text.replace(/Á/g,'&#193;');
    text = text.replace(/É/g,'&#201;');
    text = text.replace(/Í/g,'&#205;');
    text = text.replace(/Ó/g,'&#211;');
    text = text.replace(/Ú/g,'&#218;');
           
    text = text.replace(/à/g,'&#224;');
    text = text.replace(/è/g,'&#232;');
    text = text.replace(/ì/g,'&#236;');
    text = text.replace(/ò/g,'&#242;');
    text = text.replace(/ù/g,'&#249;');
    text = text.replace(/À/g,'&#192;');
    text = text.replace(/È/g,'&#200;');
    text = text.replace(/Ì/g,'&#204;');
    text = text.replace(/Ò/g,'&#210;');
    text = text.replace(/Ù/g,'&#217;');

    text = text.replace(/â/g,'&#226;');
    text = text.replace(/ê/g,'&#234;');
    text = text.replace(/î/g,'&#238;');
    text = text.replace(/ô/g,'&#244;');
    text = text.replace(/û/g,'&#251;');
    text = text.replace(/Â/g,'&#194;');
    text = text.replace(/Ê/g,'&#202;');
    text = text.replace(/Î/g,'&#206;');
    text = text.replace(/Ô/g,'&#212;');
    text = text.replace(/Û/g,'&#219;');

    text = text.replace(/ä/g,'&#228;');
    text = text.replace(/ë/g,'&#235;');
    text = text.replace(/ï/g,'&#239;');
    text = text.replace(/ö/g,'&#246;');
    text = text.replace(/ü/g,'&#252;');
    text = text.replace(/Ä/g,'&#196;');
    text = text.replace(/Ë/g,'&#203;');
    text = text.replace(/Ï/g,'&#207;');
    text = text.replace(/Ö/g,'&#214;');
    text = text.replace(/Ü/g,'&#220;');

    text = text.replace(/ñ/g,'&#241;');
    text = text.replace(/Ñ/g,'&#209;');
    text = text.replace(/ç/g,'&#231;');
    text = text.replace(/Ç/g,'&#199;');
    text = text.replace(/å/g,'&#229;');
    text = text.replace(/Å/g,'&#197;');
    
	// Commas, accents and cedillas.
    text = text.replace(/“/g,'&#8220;');
    text = text.replace(/”/g,'&#8221;');
    text = text.replace(/‘/g,'&#8216;');
    text = text.replace(/’/g,'&#8217;');
    text = text.replace(/¨/g,'&#168;'); // Spacing dieresis.
    text = text.replace(/´/g,'&#180;'); // Acute accent.
    text = text.replace(/·/g,'&#183;'); // Georgian comma.
    text = text.replace(/¸/g,'&#184;'); // Spacing cedilla.

    // Punctuation marks
    text = text.replace(/«/g,'&#171;');
    text = text.replace(/»/g,'&#187;');
    text = text.replace(/¿/g,'&#191;');
    text = text.replace(/¡/g,'&#161;');
    text = text.replace(/…/g,'&#8230;');

    // Book marks.
    text = text.replace(/—/g,'&#8212;'); // Em dash
    text = text.replace(/•/g,'&#8226;'); // Bullet.
    text = text.replace(/†/g,'&#8224;'); // Dagger.
    text = text.replace(/‡/g,'&#8225;'); // Double dagger.
    text = text.replace(/§/g,'&#167;');  // Section.
    text = text.replace(/¶/g,'&#182;');  // Pilcrow - paragraph sign.
    
    // Currency marks.
    text = text.replace(/€/g,'&#8364;'); // Euro.
    text = text.replace(/¢/g,'&#162;'); // Cent.
    text = text.replace(/£/g,'&#163;'); // Pound.
    text = text.replace(/¤/g,'&#164;'); // Currency.
    text = text.replace(/¥/g,'&#165;'); // Yen.
    
    // Math marks.
    text = text.replace(/°/g,'&#176;'); // Degree sign.
    text = text.replace(/±/g,'&#177;'); // Plus or minus.
    text = text.replace(/º/g,'&#186;'); // Masculine ordinal indicator.
    text = text.replace(/¹/g,'&#185;'); // Spacing cedilla.
    text = text.replace(/²/g,'&#178;'); // Superscript two - squared.
    text = text.replace(/³/g,'&#179;'); // Superscript three - cubed.
    text = text.replace(/µ/g,'&#181;'); // Micro sign.
    text = text.replace(/¼/g,'&#188;'); // Fraction one quarter.
    text = text.replace(/½/g,'&#189;'); // Fraction one half
    text = text.replace(/¾/g,'&#190;'); // Fraction three quarters.
    text = text.replace(/ø/g,'&#248;'); // Latin letter o with slash.
    text = text.replace(/Ø/g,'&#216;'); // Latin letter O with slash.
    text = text.replace(/÷/g,'&#247;'); // Division sign.
    text = text.replace(/×/g,'&#215;'); // Multiplication sign.
    text = text.replace(/ƒ/g,'&#402;');// Latin small f with hook = function = florin.
    text = text.replace(/‾/g,'&#8254;');// Overline = spacing overscore.
    text = text.replace(/⁄/g,'&#8260;'); // Fraction slash.
    text = text.replace(/℘/g,'&#8472;'); // Script capital P = power set = Weierstrass p.
    text = text.replace(/ℑ/g,'&#8465;'); // Blackletter capital I = imaginary part.
    text = text.replace(/ℜ/g,'&#8476;'); // Blackletter capital R = real part.
    text = text.replace(/←/g,'&#8592;'); // Leftwards arrow.
    text = text.replace(/↑/g,'&#8593;'); // Upwards arrow.
    text = text.replace(/→/g,'&#8594;'); // Rightwards arrow.
    text = text.replace(/↓/g,'&#8595;'); // Downwards arrow.
    text = text.replace(/↔/g,'&#8596;'); // Left right arrow.
    text = text.replace(/↔/g,'&#8596;'); // Left right arrow.
    text = text.replace(/⇐/g,'&#8656;'); // Leftwards double arrow.
    text = text.replace(/⇑/g,'&#8657;'); // Upwards double arrow.
    text = text.replace(/⇒/g,'&#8658;'); // Rightwards double arrow.
    text = text.replace(/⇓/g,'&#8659;'); // Downwards double arrow.
    text = text.replace(/⇔/g,'&#8660;'); // Left right double arrow.
    text = text.replace(/∀/g,'&#8704;'); // For all.
    text = text.replace(/∂/g,'&#8706;'); // Partial differential.
    text = text.replace(/∃/g,'&#8707;'); // There exists.
    text = text.replace(/∅/g,'&#8709;'); // Empty set = diameter.
    text = text.replace(/∇/g,'&#8711;'); // Nabla = backward difference.
    text = text.replace(/∈/g,'&#8712;'); // Element of.
    text = text.replace(/∉/g,'&#8713;'); // Not an element of.
    text = text.replace(/∋/g,'&#8715;'); // Contains as member.
    text = text.replace(/∏/g,'&#8719;'); // n-ary product = product sign.
    text = text.replace(/∑/g,'&#8721;'); // n-ary sumation.
    text = text.replace(/−/g,'&#8722;'); // Minus sign.
    text = text.replace(/∗/g,'&#8727;'); // Asterisk operator.
    text = text.replace(/√/g,'&#8730;'); // Square root = radical sign.
    text = text.replace(/∝/g,'&#8733;'); // Proportional to.
    text = text.replace(/∞/g,'&#8734;'); // Infinity.
    text = text.replace(/∠/g,'&#8736;'); // Angle.
    text = text.replace(/∧/g,'&#8743;'); // Logical and.
    text = text.replace(/∨/g,'&#8744;'); // Logical or = vee.
    text = text.replace(/∩/g,'&#8745;'); // Intersection = cap.
    text = text.replace(/∪/g,'&#8746;'); // union = cup.
    text = text.replace(/∫/g,'&#8747;'); // Integral.
    text = text.replace(/∴/g,'&#8756;'); // Therefore.
    text = text.replace(/∼/g,'&#8764;'); // Tilde operator = varies with = similar to.
    text = text.replace(/≅/g,'&#8773;'); // Approximately equal to.
    text = text.replace(/≈/g,'&#8776;'); // Almost equal to = asymptotic to.
    text = text.replace(/≠/g,'&#8800;'); // Not equal to.
    text = text.replace(/≡/g,'&#8801;'); // Identical to.
    text = text.replace(/≤/g,'&#8804;'); // Less-than or equal to.
    text = text.replace(/≥/g,'&#8805;'); // Greater-than or equal to.
    text = text.replace(/⊂/g,'&#8834;'); // Subset of.
    text = text.replace(/⊃/g,'&#8835;'); // Superset of.
    text = text.replace(/⊄/g,'&#8836;'); // Not a subset of.
    text = text.replace(/⊆/g,'&#8838;'); // Subset of or equal to.
    text = text.replace(/⊇/g,'&#8839;'); // Superset of or equal to.
    text = text.replace(/⊕/g,'&#8853;'); // Circled plus = direct sum.
    text = text.replace(/⊗/g,'&#8855;'); // Circled times = vector product.
    text = text.replace(/⊥/g,'&#8869;'); // Up tack.
    
    // Time marks.
    text = text.replace(/′/g,'&#8242;'); // Prime = minutes = feet.
    text = text.replace(/″/g,'&#8243;'); // Double prime = seconds = inches.
    
    // Other marks.
    text = text.replace(/©/g,'&#169;'); // Copyright.
    text = text.replace(/®/g,'&#174;'); // Registered trade mark.
    text = text.replace(/™/g,'&#8482;'); // Trade mark.

    // Greek characters.
    text = text.replace(/Α/g,'&#913;'); // Alpha.
    text = text.replace(/Β/g,'&#914;'); // Beta.
    text = text.replace(/Γ/g,'&#915;'); // Gamma.
    text = text.replace(/Δ/g,'&#916;'); // Delta.
    text = text.replace(/Ε/g,'&#917;'); // Epsilon.
    text = text.replace(/Ζ/g,'&#918;'); // Zeta.
    text = text.replace(/Η/g,'&#919;'); // Eta.
    text = text.replace(/Θ/g,'&#920;'); // Theta.
    text = text.replace(/Ι/g,'&#921;'); // Iota.
    text = text.replace(/Κ/g,'&#922;'); // Kappa.
    text = text.replace(/Λ/g,'&#923;'); // Lambda.
    text = text.replace(/Μ/g,'&#924;'); // Mu.
    text = text.replace(/Ν/g,'&#925;'); // Nu.
    text = text.replace(/Ξ/g,'&#926;'); // Xi.
    text = text.replace(/Ο/g,'&#927;'); // Omicron.
    text = text.replace(/Π/g,'&#928;'); // Pi.
    text = text.replace(/Ρ/g,'&#929;'); // Rho.
    text = text.replace(/Σ/g,'&#931;'); // Sigma.
    text = text.replace(/Τ/g,'&#932;'); // Tau.
    text = text.replace(/Υ/g,'&#933;'); // Upsilon.
    text = text.replace(/Φ/g,'&#934;'); // Phi.
    text = text.replace(/Χ/g,'&#935;'); // Chi.
    text = text.replace(/Ψ/g,'&#936;'); // Psi.
    text = text.replace(/Ω/g,'&#937;'); // Omega.
    text = text.replace(/α/g,'&#945;'); // Alpha.
    text = text.replace(/β/g,'&#946;'); // Beta.
    text = text.replace(/γ/g,'&#947;'); // Gamma.
    text = text.replace(/δ/g,'&#948;'); // Delta.
    text = text.replace(/ε/g,'&#949;'); // Epsilon.
    text = text.replace(/ζ/g,'&#950;'); // Zeta.
    text = text.replace(/η/g,'&#951;'); // Eta.
    text = text.replace(/θ/g,'&#952;'); // Theta.
    text = text.replace(/ι/g,'&#953;'); // Iota.
    text = text.replace(/κ/g,'&#954;'); // Kappa.
    text = text.replace(/λ/g,'&#955;'); // Lambda.
    text = text.replace(/μ/g,'&#956;'); // Mu.
    text = text.replace(/ν/g,'&#957;'); // Nu.
    text = text.replace(/ξ/g,'&#958;'); // Xi.
    text = text.replace(/ο/g,'&#959;'); // Omicron.
    text = text.replace(/π/g,'&#960;'); // Pi.
    text = text.replace(/ρ/g,'&#961;'); // Rho.
    text = text.replace(/ς/g,'&#962;'); // Sigmaf.
    text = text.replace(/σ/g,'&#963;'); // Sigma.
    text = text.replace(/τ/g,'&#964;'); // Tau.
    text = text.replace(/υ/g,'&#965;'); // Upsilon.
    text = text.replace(/φ/g,'&#966;'); // Phi.
    text = text.replace(/χ/g,'&#967;'); // Chi.
    text = text.replace(/ψ/g,'&#968;'); // Psi.
    text = text.replace(/ω/g,'&#969;'); // Omega.
    
    // Hebrew characters.
    text = text.replace(/֑/g,'&#1425;'); // Accent Etnahta.
    text = text.replace(/֒/g,'&#1426;'); // Accent Segol.
    text = text.replace(/֓/g,'&#1427;'); // Accent Shalshelet.
    text = text.replace(/֔/g,'&#1428;'); // Accent Zaqef Qatan.
    text = text.replace(/֔/g,'&#1429;'); // Accent Zaqef Gadol.
    text = text.replace(/֖/g,'&#1430;'); // Accent Tipeha.
    text = text.replace(/֗/g,'&#1431;'); // Accent Revia.
    text = text.replace(/֘/g,'&#1432;'); // Accent Zarqa.
    text = text.replace(/֙/g,'&#1433;'); // Accent Pashta.
    text = text.replace(/֚/g,'&#1434;'); // Accent Yetiv.
    text = text.replace(/֛/g,'&#1435;'); // Accent Tevir.
    text = text.replace(/֜/g,'&#1436;'); // Accent Geresh.
    text = text.replace(/֝/g,'&#1437;'); // Accent Geresh Muqdam.
    text = text.replace(/֞/g,'&#1438;'); // Accent Gershayim.
    text = text.replace(/֟/g,'&#1439;'); // Accent Qarney Para.
    text = text.replace(/֠/g,'&#1440;'); // Accent Telisha Gedola.
    text = text.replace(/֡/g,'&#1441;'); // Accent Pazer.
    text = text.replace(/֢/g,'&#1442;'); // Accent atnah Hafukh.
    text = text.replace(/֣/g,'&#1443;'); // Accent Munah.
    text = text.replace(/֤/g,'&#1444;'); // Accent Mahapakh.
    text = text.replace(/֥/g,'&#1445;'); // Accent Merkha.
    text = text.replace(/֦/g,'&#1446;'); // Accent Kefula.
    text = text.replace(/֧/g,'&#1447;'); // Accent Darga.
    text = text.replace(/֨/g,'&#1448;'); // Accent Qadma.
    text = text.replace(/֩/g,'&#1449;'); // Accent Telisha Qetana.
    text = text.replace(/֪/g,'&#1450;'); // Accent Yerah Ben Yomo.
    text = text.replace(/֫/g,'&#1451;'); // Accent Ole.
    text = text.replace(/֭/g,'&#1452;'); // Accent Iluy.
    text = text.replace(/֭/g,'&#1453;'); // Accent Dehi.
    text = text.replace(/֮/g,'&#1454;'); // Accent Zinor.
    text = text.replace(/֯/g,'&#1455;'); // Mark Masora circle.
    text = text.replace(/ְ/g,'&#1456;'); // Point Sheva.
    text = text.replace(/ֱ/g,'&#1457;'); // Hataf segol.
    text = text.replace(/ֲ/g,'&#1458;'); // Hataf patah.
    text = text.replace(/ֳ/g,'&#1459;'); // Hataf qamats.
    text = text.replace(/ִ/g,'&#1460;'); // Point Hiriq.
    text = text.replace(/ֵ/g,'&#1461;'); // Point Tsere.
    text = text.replace(/ֶ/g,'&#1462;'); // Point Segol.
    text = text.replace(/ַ/g,'&#1463;'); // Point Patah.
    text = text.replace(/ָ/g,'&#1464;'); // Point Qamats.
    text = text.replace(/ֹ/g,'&#1465;'); // Point Holam.
    text = text.replace(/ֺ/g,'&#1466;'); // Point Holam Haser for Vav.
    text = text.replace(/ֻ/g,'&#1467;'); // Point Qubuts.
    text = text.replace(/ּ/g,'&#1468;'); // Point Dagesh or Mapiq.
    text = text.replace(/ֽ/g,'&#1469;'); // Point Meteg.
    text = text.replace(/־	/g,'&#1470;'); // Punctuaction Maqaf.
    text = text.replace(/ֿ/g,'&#1471;'); // Point Rafe.
    text = text.replace(/׀/g,'&#1472;'); // Punctuaction Paseq.
    text = text.replace(/ׁ/g,'&#1473;'); // Point Shin dot.
    text = text.replace(/ׂ/g,'&#1474;'); // Point Sin dot.
    text = text.replace(/׃	/g,'&#1475;'); // Punctuation Sof pasuq.
    text = text.replace(/ׄ/g,'&#1476;'); // Mark upper dot.
    text = text.replace(/ׅ/g,'&#1477;'); // Mark lower dot.
    text = text.replace(/׆	/g,'&#1478;'); // Punctuation Nun hafukha.
    text = text.replace(/ׇ/g,'&#1479;'); // Point Qamats Qatan.
    text = text.replace(/א/g,'&#1488;'); // Letter Alef.
    text = text.replace(/ב/g,'&#1489;'); // Letter Bet.
    text = text.replace(/ג/g,'&#1490;'); // Letter Gimel.
    text = text.replace(/ד/g,'&#1491;'); // Letter Dalet.
    text = text.replace(/ה/g,'&#1492;'); // Letter He.
    text = text.replace(/ו/g,'&#1493;'); // Letter Vav.
    text = text.replace(/ז/g,'&#1494;'); // Letter Zayin.
    text = text.replace(/ח/g,'&#1495;'); // Letter Het.
    text = text.replace(/ט/g,'&#1496;'); // Letter Tet.
    text = text.replace(/י/g,'&#1497;'); // Letter Yod.
    text = text.replace(/כ/g,'&#1499;'); // Letter Kaf.
    text = text.replace(/ל	/g,'&#1500;'); // Letter Lamed.
    text = text.replace(/ם/g,'&#1501;'); // Letter final Mem.
    text = text.replace(/מ	/g,'&#1502;'); // Letter Mem.
    text = text.replace(/ן/g,'&#1503;'); // Letter final Nun.
    text = text.replace(/נ/g,'&#1504;'); // Letter Nun.
    text = text.replace(/ס/g,'&#1505;'); // Letter Samekh.
    text = text.replace(/ע/g,'&#1506;'); // Letter Ayin.
    text = text.replace(/ף/g,'&#1507;'); // Letter final Pe.
    text = text.replace(/פ/g,'&#1508;'); // Letter Pe.
    text = text.replace(/ץ/g,'&#1509;'); // Letter final Tsadi.
    text = text.replace(/צ/g,'&#1510;'); // Letter Tsadi.
    text = text.replace(/ק/g,'&#1511;'); // Letter Qof.
    text = text.replace(/ר/g,'&#1512;'); // Letter Resh.
    text = text.replace(/ש/g,'&#1513;'); // Letter Shin.
    text = text.replace(/ת/g,'&#1514;'); // Letter Tav.
    text = text.replace(/װ/g,'&#1520;'); // Ligature Yiddish double Vav.
    text = text.replace(/ױ	/g,'&#1521;'); // Ligature Yiddish Vav Yod.
    text = text.replace(/ײ	/g,'&#1522;'); // Ligature yiddish double Yod.
    text = text.replace(/׳/g,'&#1523;'); // Punctuation Geresh.
    text = text.replace(/״/g,'&#1524;'); // Punctuation Gershayim.

    // Cyrillic (Russia)
    // Uppercase.
    text = text.replace(/А/g,'&#1040;'); // A.
    text = text.replace(/Б/g,'&#1041;'); // BE.
    text = text.replace(/В/g,'&#1042;'); // VE.
    text = text.replace(/Г/g,'&#1043;'); // GHE.
    text = text.replace(/Д/g,'&#1044;'); // DE.
    text = text.replace(/Е/g,'&#1045;'); // IE.
    text = text.replace(/Ж/g,'&#1046;'); // ZHE.
    text = text.replace(/З/g,'&#1047;'); // ZE.
    text = text.replace(/И/g,'&#1048;'); // I.
    text = text.replace(/Й/g,'&#1049;'); // SHORT.
    text = text.replace(/К/g,'&#1050;'); // KA.
    text = text.replace(/Л/g,'&#1051;'); // EL.
    text = text.replace(/М/g,'&#1052;'); // EM.
    text = text.replace(/Н/g,'&#1053;'); // EN.
    text = text.replace(/О/g,'&#1054;'); // O.
    text = text.replace(/П/g,'&#1055;'); // PE.
    text = text.replace(/Р/g,'&#1056;'); // ER.
    text = text.replace(/С/g,'&#1057;'); // ES.
    text = text.replace(/Т/g,'&#1058;'); // TE.
    text = text.replace(/У/g,'&#1059;'); // U.
    text = text.replace(/Ф/g,'&#1060;'); // EF.
    text = text.replace(/Х/g,'&#1061;'); // HA.
    text = text.replace(/Ц/g,'&#1062;'); // TSE.
    text = text.replace(/Ч/g,'&#1063;'); // CHE.
    text = text.replace(/Ш/g,'&#1064;'); // SHA.
    text = text.replace(/Щ/g,'&#1065;'); // SHCHA.
    text = text.replace(/Ъ/g,'&#1066;'); // SIGN.
    text = text.replace(/Ы/g,'&#1067;'); // YERU.
    text = text.replace(/Ь/g,'&#1068;'); // SOFT.
    text = text.replace(/Э/g,'&#1069;'); // E.
    text = text.replace(/Ю/g,'&#1070;'); // YU.
    text = text.replace(/Я/g,'&#1071;'); // YA.

    // Lowercase.
    text = text.replace(/а/g,'&#1072;'); // A.
    text = text.replace(/б/g,'&#1073;'); // BE.
    text = text.replace(/в/g,'&#1074;'); // VE.
    text = text.replace(/г/g,'&#1075;'); // GHE.
    text = text.replace(/д/g,'&#1076;'); // DE.
    text = text.replace(/е/g,'&#1077;'); // IE.
    text = text.replace(/ж/g,'&#1078;'); // ZHE.
    text = text.replace(/з/g,'&#1079;'); // ZE.
    text = text.replace(/и/g,'&#1080;'); // I.
    text = text.replace(/й/g,'&#1081;'); // SHORT I.
    text = text.replace(/к/g,'&#1082;'); // KA.
    text = text.replace(/л/g,'&#1083;'); // EL.
    text = text.replace(/м/g,'&#1084;'); // EM.
    text = text.replace(/н/g,'&#1085;'); // EN.
    text = text.replace(/о/g,'&#1086;'); // O.
    text = text.replace(/п/g,'&#1087;'); // PE.
    text = text.replace(/р/g,'&#1088;'); // ER.
    text = text.replace(/с/g,'&#1089;'); // ES.
    text = text.replace(/т/g,'&#1090;'); // Te.
    text = text.replace(/у/g,'&#1091;'); // U.
    text = text.replace(/ф/g,'&#1092;'); // EF.
    text = text.replace(/х/g,'&#1093;'); // HA.
    text = text.replace(/ц/g,'&#1094;'); // TSE.
    text = text.replace(/ч/g,'&#1095;'); // CHE.
    text = text.replace(/ш/g,'&#1096;'); // SHA.
    text = text.replace(/щ/g,'&#1097;'); // SHCA.
    text = text.replace(/ъ/g,'&#1098;'); // Hard sign.
    text = text.replace(/ы/g,'&#1099;'); // Yeru.
    text = text.replace(/ь/g,'&#1100;'); // Soft sign.
    text = text.replace(/э/g,'&#1101;'); // E.
    text = text.replace(/ю/g,'&#1102;'); // Yu.
    text = text.replace(/я/g,'&#1103;'); // Ya.

    // Cyrillic (Ukrania, Serbia, Bielorrusia)
    // Uppercase.
    text = text.replace(/Ѐ/g,'&#1024;'); // IE WITH GRAVE.
    text = text.replace(/Ё/g,'&#1025;'); // IO.
    text = text.replace(/Ђ/g,'&#1026;'); // DJE.
    text = text.replace(/Ѓ/g,'&#1027;'); // GJE.
    text = text.replace(/Є/g,'&#1028;'); // IE.
    text = text.replace(/Ѕ/g,'&#1029;'); // DZE.
    text = text.replace(/І/g,'&#1030;'); // I.
    text = text.replace(/Ї/g,'&#1031;'); // YI.
    text = text.replace(/Ј/g,'&#1032;'); // JE.
    text = text.replace(/Љ/g,'&#1033;'); // LJE.
    text = text.replace(/Њ/g,'&#1034;'); // NJE.
    text = text.replace(/Ћ/g,'&#1035;'); // TSHE.
    text = text.replace(/Ќ/g,'&#1036;'); // KJE.
    text = text.replace(/Ѝ/g,'&#1037;'); // I WITH GRAVE.
    text = text.replace(/Ў/g,'&#1038;'); // U.
    text = text.replace(/Џ/g,'&#1039;'); // DZHE.

    // Lowercase.    
    text = text.replace(/ѐ/g,'&#1104;'); // IE WITH GRAVE.
    text = text.replace(/ё/g,'&#1105;'); // IO.
    text = text.replace(/ђ/g,'&#1106;'); // DJE.
    text = text.replace(/ѓ/g,'&#1107;'); // GJE.
    text = text.replace(/є/g,'&#1108;'); // UKRAINIAN IE.
    text = text.replace(/ѕ/g,'&#1109;'); // DZE.
    text = text.replace(/і/g,'&#1110;'); // BYELORUSSIAN-UKRAINIAN I.
    text = text.replace(/ї/g,'&#1111;'); // YI.
    text = text.replace(/ј/g,'&#1112;'); // JE.
    text = text.replace(/љ/g,'&#1113;'); // LJE.
    text = text.replace(/њ/g,'&#1114;'); // NJE.
    text = text.replace(/ћ/g,'&#1115;'); // TSHE.
    text = text.replace(/ќ/g,'&#1116;'); // KJE.
    text = text.replace(/ѝ/g,'&#1117;'); // I WITH GRAVE.
    text = text.replace(/ў/g,'&#1118;'); // SHORT U.
    text = text.replace(/џ/g,'&#1119;'); // DZHE.

    // Armenian marks
    // Uppercase.
    text = text.replace(/Ա/g,'&#1329;'); // Ayb.
    text = text.replace(/Բ/g,'&#1330;'); // Ben.
    text = text.replace(/Գ/g,'&#1331;'); // Gim.
    text = text.replace(/Դ/g,'&#1332;'); // Da.
    text = text.replace(/Ե/g,'&#1333;'); // Ech.
    text = text.replace(/Զ/g,'&#1334;'); // Za.
    text = text.replace(/Է/g,'&#1335;'); // Eh.
    text = text.replace(/Ը/g,'&#1336;'); // Et.
    text = text.replace(/Թ/g,'&#1337;'); // To.
    text = text.replace(/Ժ/g,'&#1338;'); // Zhe.
    text = text.replace(/Ի/g,'&#1339;'); // Ini.
    text = text.replace(/Լ/g,'&#1340;'); // Liwn.
    text = text.replace(/Խ/g,'&#1341;'); // Xeh.
    text = text.replace(/Ծ/g,'&#1342;'); // Ca.
    text = text.replace(/Կ/g,'&#1343;'); // Ken.
    text = text.replace(/Հ/g,'&#1344;'); // Ho.
    text = text.replace(/Ձ/g,'&#1345;'); // Ja.
    text = text.replace(/Ղ/g,'&#1346;'); // Ghad.
    text = text.replace(/Ճ/g,'&#1347;'); // Cheh.
    text = text.replace(/Մ/g,'&#1348;'); // Men.
    text = text.replace(/Յ/g,'&#1349;'); // Yi.
    text = text.replace(/Ն/g,'&#1350;'); // Now.
    text = text.replace(/Շ/g,'&#1351;'); // Sha.
    text = text.replace(/Ո/g,'&#1352;'); // Vo.
    text = text.replace(/Չ/g,'&#1353;'); // Cha.
    text = text.replace(/Պ/g,'&#1354;'); // Peh.
    text = text.replace(/Ջ/g,'&#1355;'); // Jheh.
    text = text.replace(/Ռ/g,'&#1356;'); // Ra.
    text = text.replace(/Ս/g,'&#1357;'); // Seh.
    text = text.replace(/Վ/g,'&#1358;'); // Vew.
    text = text.replace(/Տ/g,'&#1359;'); // Tiwn.
    text = text.replace(/Ր/g,'&#1360;'); // Reh.
    text = text.replace(/Ց/g,'&#1361;'); // Co.
    text = text.replace(/Ւ/g,'&#1362;'); // Yiwn.
    text = text.replace(/Փ/g,'&#1363;'); // Piwr.
    text = text.replace(/Ք/g,'&#1364;'); // Keh.
    text = text.replace(/Օ/g,'&#1365;'); // Oh.
    text = text.replace(/Ֆ/g,'&#1366;'); // Feh.
    
    // Lowercase.
    text = text.replace(/ա/g,'&#1377;'); // Ayb.
    text = text.replace(/բ/g,'&#1378;'); // Ben.
    text = text.replace(/գ/g,'&#1379;'); // Gim.
    text = text.replace(/դ/g,'&#1380;'); // Da.
    text = text.replace(/ե/g,'&#1381;'); // Ech.
    text = text.replace(/զ/g,'&#1382;'); // Za.
    text = text.replace(/է/g,'&#1383;'); // Eh.
    text = text.replace(/ը/g,'&#1384;'); // Et.
    text = text.replace(/թ/g,'&#1385;'); // To.
    text = text.replace(/ժ/g,'&#1386;'); // Zhe.
    text = text.replace(/ի/g,'&#1387;'); // Ini.
    text = text.replace(/լ/g,'&#1388;'); // Liwn.
    text = text.replace(/խ/g,'&#1389;'); // Xeh.
    text = text.replace(/ծ/g,'&#1390;'); // Ca.
    text = text.replace(/կ/g,'&#1391;'); // Ken.
    text = text.replace(/հ/g,'&#1392;'); // Ho.
    text = text.replace(/ձ/g,'&#1393;'); // Ja.
    text = text.replace(/ղ/g,'&#1394;'); // Ghad.
    text = text.replace(/ճ/g,'&#1395;'); // Cheh.
    text = text.replace(/մ/g,'&#1396;'); // Men.
    text = text.replace(/յ/g,'&#1397;'); // Yi.
    text = text.replace(/ն/g,'&#1398;'); // Now.
    text = text.replace(/շ/g,'&#1399;'); // Sha.
    text = text.replace(/ո/g,'&#1400;'); // Vo.
    text = text.replace(/չ/g,'&#1401;'); // Cha.
    text = text.replace(/պ/g,'&#1402;'); // Peh.
    text = text.replace(/ջ/g,'&#1403;'); // Jheh.
    text = text.replace(/ռ/g,'&#1404;'); // Ra.
    text = text.replace(/ս/g,'&#1405;'); // Seh.
    text = text.replace(/վ/g,'&#1406;'); // Vew.
    text = text.replace(/տ/g,'&#1407;'); // Tiwn.
    text = text.replace(/ր/g,'&#1408;'); // Reh.
    text = text.replace(/ց/g,'&#1409;'); // Co.
    text = text.replace(/ւ/g,'&#1410;'); // Yiwn.
    text = text.replace(/փ/g,'&#1411;'); // Piwr.
    text = text.replace(/ք/g,'&#1412;'); // Keh.
    text = text.replace(/օ/g,'&#1413;'); // Oh.
    text = text.replace(/ֆ/g,'&#1414;'); // Feh.
    
    // Armenia
    text = text.replace(/ɋ/g,'&#587;'); // Ligature ech Yiwn.
    text = text.replace(/՚՚՚/g,'&#1370;'); // Apostrophe.
    text = text.replace(/՛/g,'&#1371;'); // Emphasis mark.
    text = text.replace(/՜/g,'&#1372;'); // Exclamation mark.
    text = text.replace(/՝/g,'&#1373;'); // Comma.
    text = text.replace(/՞/g,'&#1374;'); // Question mark.
    text = text.replace(/՟/g,'&#1375;'); // Abbreviation mark.
    text = text.replace(/։/g,'&#1417;'); // Full stop.
    text = text.replace(/֊/g,'&#1418;'); // Hyphen.
    
    // Hiragana (Japan)
    text = text.replace(/ぁ/g,'&#12353;'); // A lowercase.
    text = text.replace(/あ/g,'&#12354;'); // A uppercase.
    text = text.replace(/ぃ/g,'&#12355;'); // I lowercase.
    text = text.replace(/い/g,'&#12356;'); // I uppercase.
    text = text.replace(/ぅ/g,'&#12357;'); // U lowercase.
    text = text.replace(/う/g,'&#12358;'); // U uppercase.
    text = text.replace(/ぇ/g,'&#12359;'); // E lowercase.
    text = text.replace(/え/g,'&#12360;'); // E uppercase.
    text = text.replace(/ぉ/g,'&#12361;'); // O lowercase.
    text = text.replace(/お/g,'&#12362;'); // O uppercase.
    text = text.replace(/か/g,'&#12363;'); // Ka.
    text = text.replace(/が/g,'&#12364;'); // Ga.
    text = text.replace(/き/g,'&#12365;'); // Ki.
    text = text.replace(/ぎ/g,'&#12366;'); // Gi.
    text = text.replace(/く/g,'&#12367;'); // Ku.
    text = text.replace(/ぐ/g,'&#12368;'); // Gu.
    text = text.replace(/け/g,'&#12369;'); // Ke.
    text = text.replace(/げ/g,'&#12370;'); // Ge.
    text = text.replace(/こ/g,'&#12371;'); // Ko.
    text = text.replace(/ご/g,'&#12372;'); // Go.
    text = text.replace(/さ/g,'&#12373;'); // Sa.
    text = text.replace(/ざ/g,'&#12374;'); // Za.
    text = text.replace(/し/g,'&#12375;'); // Si.
    text = text.replace(/じ/g,'&#12376;'); // Zi.
    text = text.replace(/す/g,'&#12377;'); // Su.
    text = text.replace(/ず/g,'&#12378;'); // Zu.
    text = text.replace(/せ/g,'&#12379;'); // Se.
    text = text.replace(/ぜ/g,'&#12380;'); // Ze.
    text = text.replace(/そ/g,'&#12381;'); // So.
    text = text.replace(/ぞ/g,'&#12382;'); // Zo.
    text = text.replace(/た/g,'&#12383;'); // Ta.
    text = text.replace(/だ/g,'&#12384;'); // Da.
    text = text.replace(/ち/g,'&#12385;'); // Ti.
    text = text.replace(/ぢ/g,'&#12386;'); // Di.
    text = text.replace(/っ/g,'&#12387;'); // Tu lowercase.
    text = text.replace(/つ/g,'&#12388;'); // Tu uppercase.
    text = text.replace(/づ/g,'&#12389;'); // Du.
    text = text.replace(/て/g,'&#12390;'); // Te.
    text = text.replace(/で/g,'&#12391;'); // De.
    text = text.replace(/と/g,'&#12392;'); // To.
    text = text.replace(/ど/g,'&#12393;'); // Do.
    text = text.replace(/な/g,'&#12394;'); // Na.
    text = text.replace(/に/g,'&#12395;'); // Ni.
    text = text.replace(/ぬ/g,'&#12396;'); // Nu.
    text = text.replace(/ね/g,'&#12397;'); // Ne.
    text = text.replace(/の/g,'&#12398;'); // No.
    text = text.replace(/は/g,'&#12399;'); // Ha.
    text = text.replace(/ば/g,'&#12400;'); // Ba.
    text = text.replace(/ぱ/g,'&#12401;'); // Pa.
    text = text.replace(/ひ/g,'&#12402;'); // Hi.
    text = text.replace(/び/g,'&#12403;'); // Bi.
    text = text.replace(/ぴ/g,'&#12404;'); // Pi.
    text = text.replace(/ふ/g,'&#12405;'); // Hu.
    text = text.replace(/ぶ/g,'&#12406;'); // Bu.
    text = text.replace(/ぷ/g,'&#12407;'); // Pu.
    text = text.replace(/へ/g,'&#12408;'); // He.
    text = text.replace(/べ/g,'&#12409;'); // Be.
    text = text.replace(/ぺ/g,'&#12410;'); // Pe.
    text = text.replace(/ほ/g,'&#12411;'); // Ho.
    text = text.replace(/ぼ/g,'&#12412;'); // Bo.
    text = text.replace(/ぽ/g,'&#12413;'); // Po.
    text = text.replace(/ま/g,'&#12414;'); // Ma.
    text = text.replace(/み/g,'&#12415;'); // Mi.
    text = text.replace(/む/g,'&#12416;'); // Mu.
    text = text.replace(/め/g,'&#12417;'); // Me.
    text = text.replace(/も/g,'&#12418;'); // Mo.
    text = text.replace(/ゃ/g,'&#12419;'); // Ya lowercase.
    text = text.replace(/や/g,'&#12420;'); // Ya uppercase.
    text = text.replace(/ゅ/g,'&#12421;'); // Yu lowercase.
    text = text.replace(/ゆ/g,'&#12422;'); // Yu uppercase.
    text = text.replace(/ょ/g,'&#12423;'); // Yo lowercase.
    text = text.replace(/よ/g,'&#12424;'); // Yo uppercase.
    text = text.replace(/ら/g,'&#12425;'); // Ra.
    text = text.replace(/り/g,'&#12426;'); // Ri.
    text = text.replace(/る/g,'&#12427;'); // Ru.
    text = text.replace(/れ/g,'&#12428;'); // Re.
    text = text.replace(/ろ/g,'&#12429;'); // Ro.
    text = text.replace(/ゎ/g,'&#12430;'); // Wa lowercase.
    text = text.replace(/わ/g,'&#12431;'); // Wa uppercase.
    text = text.replace(/ゐ/g,'&#12432;'); // Wi.
    text = text.replace(/ゑ/g,'&#12433;'); // We.
    text = text.replace(/を/g,'&#12434;'); // Wo.
    text = text.replace(/ん/g,'&#12435;'); // N.
    text = text.replace(/ゔ/g,'&#12436;'); // Vu.
    text = text.replace(/ゕ/g,'&#12437;'); // Ka lowercase.
    text = text.replace(/ゖ/g,'&#12438;'); // Ke lowercase.
    text = text.replace(/゛/g,'&#12443;'); // Voiced sound.
    text = text.replace(/゜/g,'&#12444;'); // Semi-voiced sound.
    text = text.replace(/ゝ/g,'&#12445;'); // Iteration.
    text = text.replace(/ゞ/g,'&#12446;'); // Voiced iteration.
    text = text.replace(/ゟ/g,'&#12447;'); // Digraph Yori.
    
    // Katakana (Japan)
    text = text.replace(/゠/g,'&#12448;'); // Double hyphen. 
    text = text.replace(/ァ/g,'&#12449;'); // A lowercase.
    text = text.replace(/ア/g,'&#12450;'); // A uppercase.
    text = text.replace(/ィ/g,'&#12451;'); // I lowercase.
    text = text.replace(/イ/g,'&#12452;'); // I uppercase.
    text = text.replace(/ゥ/g,'&#12453;'); // I lowercase.
    text = text.replace(/ウ/g,'&#12454;'); // U uppercase.
    text = text.replace(/ェ/g,'&#12455;'); // E lowercase.
    text = text.replace(/エ/g,'&#12456;'); // E uppercase.
    text = text.replace(/ォ/g,'&#12457;'); // O lowercase.
    text = text.replace(/オ/g,'&#12458;'); // O uppercase.
    text = text.replace(/カ/g,'&#12459;'); // Ka.
    text = text.replace(/ガ/g,'&#12460;'); // Ga.
    text = text.replace(/キ/g,'&#12461;'); // Ki.
    text = text.replace(/ギ/g,'&#12462;'); // Gi.
    text = text.replace(/ク/g,'&#12463;'); // Ku.
    text = text.replace(/グ/g,'&#12464;'); // Gu.
    text = text.replace(/ケ/g,'&#12465;'); // Ke.
    text = text.replace(/ゲ/g,'&#12466;'); // Ge.
    text = text.replace(/コ/g,'&#12467;'); // Ko.
    text = text.replace(/ゴ/g,'&#12468;'); // Go.
    text = text.replace(/サ/g,'&#12469;'); // Sa.
    text = text.replace(/ザ/g,'&#12470;'); // Za.
    text = text.replace(/シ/g,'&#12471;'); // Si.
    text = text.replace(/ジ/g,'&#12472;'); // Zi.
    text = text.replace(/ス/g,'&#12473;'); // Su.
    text = text.replace(/ズ/g,'&#12474;'); // Zu.
    text = text.replace(/セ/g,'&#12475;'); // Se.
    text = text.replace(/ゼ/g,'&#12476;'); // Ze.
    text = text.replace(/ソ/g,'&#12477;'); // So.
    text = text.replace(/ゾ/g,'&#12478;'); // Zo.
    text = text.replace(/タ/g,'&#12479;'); // Ta.
    text = text.replace(/ダ/g,'&#12480;'); // Da.
    text = text.replace(/チ/g,'&#12481;'); // Ti.
    text = text.replace(/ヂ/g,'&#12482;'); // Di.
    text = text.replace(/ッ/g,'&#12483;'); // Tu lowercase.
    text = text.replace(/ツ/g,'&#12484;'); // Tu uppercase.
    text = text.replace(/ヅ/g,'&#12485;'); // Du.
    text = text.replace(/テ/g,'&#12486;'); // Te.
    text = text.replace(/デ/g,'&#12487;'); // De.
    text = text.replace(/ト/g,'&#12488;'); // To.
    text = text.replace(/ド/g,'&#12489;'); // Do.
    text = text.replace(/ナ/g,'&#12490;'); // Na.
    text = text.replace(/ニ/g,'&#12491;'); // Ni.
    text = text.replace(/ヌ/g,'&#12492;'); // Nu.
    text = text.replace(/ネ/g,'&#12493;'); // Ne.
    text = text.replace(/ノ/g,'&#12494;'); // No.
    text = text.replace(/ハ/g,'&#12495;'); // Ha.
    text = text.replace(/バ/g,'&#12496;'); // Ba.
    text = text.replace(/パ/g,'&#12497;'); // Pa.
    text = text.replace(/ヒ/g,'&#12498;'); // Hi.
    text = text.replace(/ビ/g,'&#12499;'); // Bi.
    text = text.replace(/ピ/g,'&#12500;'); // Pi.
    text = text.replace(/フ/g,'&#12501;'); // Hu.
    text = text.replace(/ブ/g,'&#12502;'); // Bu.
    text = text.replace(/プ/g,'&#12503;'); // Pu.
    text = text.replace(/ヘ/g,'&#12504;'); // He.
    text = text.replace(/ベ/g,'&#12505;'); // Be.
    text = text.replace(/ペ/g,'&#12506;'); // Pe.
    text = text.replace(/ホ/g,'&#12507;'); // Ho.
    text = text.replace(/ボ/g,'&#12508;'); // Bo.
    text = text.replace(/ポ/g,'&#12509;'); // Po.
    text = text.replace(/マ/g,'&#12510;'); // Ma.
    text = text.replace(/ミ/g,'&#12511;'); // Mi.
    text = text.replace(/ム/g,'&#12512;'); // Mu.
    text = text.replace(/メ/g,'&#12513;'); // Me.
    text = text.replace(/モ/g,'&#12514;'); // Mo.
    text = text.replace(/ャ/g,'&#12515;'); // Ya lowercase.
    text = text.replace(/ヤ/g,'&#12516;'); // Ya uppercase.
    text = text.replace(/ュ/g,'&#12517;'); // Yu lowercase.
    text = text.replace(/ユ/g,'&#12518;'); // Yu uppercase.
    text = text.replace(/ョ/g,'&#12519;'); // Yo lowercase.
    text = text.replace(/ヨ/g,'&#12520;'); // Yo uppercase.
    text = text.replace(/ラ/g,'&#12521;'); // Ra.
    text = text.replace(/リ/g,'&#12522;'); // Ri.
    text = text.replace(/ル/g,'&#12523;'); // Ru.
    text = text.replace(/レ/g,'&#12524;'); // Re.
    text = text.replace(/ロ/g,'&#12525;'); // Ro.
    text = text.replace(/ヮ/g,'&#12526;'); // Wa lowercase.
    text = text.replace(/ワ/g,'&#12527;'); // Wa uppercase.
    text = text.replace(/ヰ/g,'&#12528;'); // Wi.
    text = text.replace(/ヱ/g,'&#12529;'); // We.
    text = text.replace(/ヲ/g,'&#12530;'); // Wo.
    text = text.replace(/ン/g,'&#12531;'); // N.
    text = text.replace(/ヴ/g,'&#12532;'); // Vu.
    text = text.replace(/ヵ/g,'&#12533;'); // Ka lowercase.
    text = text.replace(/ヶ/g,'&#12534;'); // Ke lowercase.
    text = text.replace(/ヷ/g,'&#12535;'); // Va.
    text = text.replace(/ヸ/g,'&#12536;'); // Vi.
    text = text.replace(/ヹ/g,'&#12537;'); // Ve.
    text = text.replace(/ヺ/g,'&#12538;'); // Vo.
    text = text.replace(/・/g,'&#12539;'); // Middle dot.
    text = text.replace(/ー/g,'&#12540;'); // Prolonged sound.
    text = text.replace(/ヽ/g,'&#12541;'); // Iteration.
    text = text.replace(/ヾ/g,'&#12542;'); // Voice iteration.
    text = text.replace(/ヿ/g,'&#12543;'); // Digraph KoTo.
	
    return text;
}
