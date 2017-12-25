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
 * Convert special characters to HTML entities. 
 * @param string $string, text that contains characters to convert.
 * @return string, text with characters converted.
 *
 * @author Fernando Martín (fermitanio@hotmail.com).
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * 
 */ 

    $search = array(
        
// West european characters.
        'á',
        'é',
        'í',
        'ó',
        'ú',
        'Á',
        'É',
        'Í',
        'Ó',
        'Ú',

        'à',
        'è',
        'ì',
        'ò',
        'ù',
        'À',
        'È',
        'Ì',
        'Ò',
        'Ù',

        'â',
        'ê',
        'î',
        'ô',
        'û',
        'Â',
        'Ê',
        'Î',
        'Ô',
        'Û',

        'ä',
        'ë',
        'ï',
        'ö',
        'ü',
        'Ä',
        'Ë',
        'Ï',
        'Ö',
        'Ü',

        // Spanish characters.
        'ñ',
        'Ñ',
        'ç',
        'Ç',

        // Commas, accents and cedillas.
        '“',
        '”',
        '‘',
        '’',
        '¨', // Spacing dieresis.
        '´', // Acute accent.
        '·', // Georgian comma.
        '¸', // Spacing cedilla.

        // Punctuation marks.
        '«',
        '»',
        '¿',
        '¡',
        '…',

        // Book marks. 
        '—',  // Em dash
        '•',  // Bullet.
        '†',  // Dagger.
        '‡',  // Double dagger.
        '§',  // Section.
        '¶',  // Pilcrow - paragraph sign.
             
        // Currency marks.
        '€', // Euro.
        '¢', // Cent.
        '£', // Pound.
        '¤', // Currency.
        '¥', // Yen.
             
        // Math marks.
        '°', // Degree sign.
        '±', // Plus or minus.
        'º', // Masculine ordinal indicator.
        '¹', // Spacing cedilla.
        '²', // Superscript two - squared.
        '³', // Superscript three - cubed.
        'µ', // Micro sign.
        '¼', // Fraction one quarter.
        '½', // Fraction one half
        '¾', // Fraction three quarters.
        'ø', // Latin letter o with slash.
        'Ø', // Latin letter O with slash.
        '÷', // Division sign.
        '×', // Multiplication sign.
        'ƒ', // Latin small f with hook = function = florin.
        '‾', // Overline = spacing overscore.
        '⁄', // Fraction slash.
        '℘', // Script capital P = power set = Weierstrass p.
        'ℑ', // Blackletter capital I = imaginary part.
        'ℜ', // Blackletter capital R = real part.
        '←', // Leftwards arrow.
        '↑', // Upwards arrow.
        '→', // Rightwards arrow.
        '↓', // Downwards arrow.
        '↔', // Left right arrow.
        '↔', // Left right arrow.
        '⇐', // Leftwards double arrow.
        '⇑', // Upwards double arrow.
        '⇒', // Rightwards double arrow.
        '⇓', // Downwards double arrow.
        '⇔', // Left right double arrow.
        '∀', // For all.
        '∂', // Partial differential.
        '∃', // There exists.
        '∅', // Empty set = diameter.
        '∇', // Nabla = backward difference.
        '∈', // Element of.
        '∉', // Not an element of.
        '∋', // Contains as member.
        '∏', // n-ary product = product sign.
        '∑', // n-ary sumation.
        '−', // Minus sign.
        '∗', // Asterisk operator.
        '√', // Square root = radical sign.
        '∝', // Proportional to.
        '∞', // Infinity.
        '∠', // Angle.
        '∧', // Logical and.
        '∨', // Logical or = vee.
        '∩', // Intersection = cap.
        '∪', // union = cup.
        '∫', // Integral.
        '∴', // Therefore.
        '∼', // Tilde operator = varies with = similar to.
        '≅', // Approximately equal to.
        '≈', // Almost equal to = asymptotic to.
        '≠', // Not equal to.
        '≡', // Identical to.
        '≤', // Less-than or equal to.
        '≥', // Greater-than or equal to.
        '⊂', // Subset of.
        '⊃', // Superset of.
        '⊄', // Not a subset of.
        '⊆', // Subset of or equal to.
        '⊇', // Superset of or equal to.
        '⊕', // Circled plus = direct sum.
        '⊗', // Circled times = vector product.
        '⊥', // Up tack.
             
        // Time marks.
        '′',  // Prime = minutes = feet.
        '″',  // Double prime = seconds = inches.
             
        // Other marks.
        '©', // Copyright.
        '®', // Registered trade mark.
        '™',  // Trade mark.
             
        // Greek characters.
        'Α', // Alpha.
        'Β', // Beta.
        'Γ', // Gamma.
        'Δ', // Delta.
        'Ε', // Epsilon.
        'Ζ', // Zeta.
        'Η', // Eta.
        'Θ', // Theta.
        'Ι', // Iota.
        'Κ', // Kappa.
        'Λ', // Lambda.
        'Μ', // Mu.
        'Ν', // Nu.
        'Ξ', // Xi.
        'Ο', // Omicron.
        'Π', // Pi.
        'Ρ', // Rho.
        'Σ', // Sigma.
        'Τ', // Tau.
        'Υ', // Upsilon.
        'Φ', // Phi.
        'Χ', // Chi.
        'Ψ', // Psi.
        'Ω', // Omega.
        'α', // Alpha.
        'β', // Beta.
        'γ', // Gamma.
        'δ', // Delta.
        'ε', // Epsilon.
        'ζ', // Zeta.
        'η', // Eta.
        'θ', // Theta.
        'ι', // Iota.
        'κ', // Kappa.
        'λ', // Lambda.
        'μ', // Mu.
        'ν', // Nu.
        'ξ', // Xi.
        'ο', // Omicron.
        'π', // Pi.
        'ρ', // Rho.
        'ς', // Sigmaf.
        'σ', // Sigma.
        'τ', // Tau.
        'υ', // Upsilon.
        'φ', // Phi.
        'χ', // Chi.
        'ψ', // Psi.
        'ω', // Omega.
             
        // Hebrew characters.
        '֑',  // Accent Etnahta.
        '֒',  // Accent Segol.
        '֓',  // Accent Shalshelet.
        '֔',  // Accent Zaqef Qatan.
        '֔',  // Accent Zaqef Gadol.
        '֖',  // Accent Tipeha.
        '֗',  // Accent Revia.
        '֘',  // Accent Zarqa.
        '֙',  // Accent Pashta.
        '֚',  // Accent Yetiv.
        '֛',  // Accent Tevir.
        '֜',  // Accent Geresh.
        '֝',  // Accent Geresh Muqdam.
        '֞',  // Accent Gershayim.
        '֟',  // Accent Qarney Para.
        '֠',  // Accent Telisha Gedola.
        '֡',  // Accent Pazer.
        '֢',  // Accent atnah Hafukh.
        '֣',  // Accent Munah.
        '֤',  // Accent Mahapakh.
        '֥',  // Accent Merkha.
        '֦',  // Accent Kefula.
        '֧',  // Accent Darga.
        '֨',  // Accent Qadma.
        '֩',  // Accent Telisha Qetana.
        '֪',  // Accent Yerah Ben Yomo.
        '֫',  // Accent Ole.
        '֭',  // Accent Iluy.
        '֭',  // Accent Dehi.
        '֮',  // Accent Zinor.
        '֯',  // Mark Masora circle.
        'ְ',  // Point Sheva.
        'ֱ',  // Hataf segol.
        'ֲ',  // Hataf patah.
        'ֳ',  // Hataf qamats.
        'ִ',  // Point Hiriq.
        'ֵ',  // Point Tsere.
        'ֶ',  // Point Segol.
        'ַ',  // Point Patah.
        'ָ',  // Point Qamats.
        'ֹ',  // Point Holam.
        'ֺ',  // Point Holam Haser for Vav.
        'ֻ',  // Point Qubuts.
        'ּ',  // Point Dagesh or Mapiq.
        'ֽ',  // Point Meteg.
        '־	',  // Punctuaction Maqaf.,
        'ֿ',  // Point Rafe.
        '׀',  // Punctuaction Paseq.
        'ׁ',  // Point Shin dot.
        'ׂ',  // Point Sin dot.
        '׃	',  // Punctuation Sof pasuq.,
        'ׄ',  // Mark upper dot.
        'ׅ',  // Mark lower dot.
        '׆	',  // Punctuation Nun hafukha.,
        'ׇ',  // Point Qamats Qatan.
        'א',  // Letter Alef.
        'ב',  // Letter Bet.
        'ג',  // Letter Gimel.
        'ד',  // Letter Dalet.
        'ה',  // Letter He.
        'ו',  // Letter Vav.
        'ז',  // Letter Zayin.
        'ח',  // Letter Het.
        'ט',  // Letter Tet.
        'י',  // Letter Yod.
        'כ',  // Letter Kaf.
        'ל	', // Letter Lamed.,
        'ם',  // Letter final Mem.
        'מ	', // Letter Mem.,
        'ן',  // Letter final Nun.
        'נ',  // Letter Nun.
        'ס',  // Letter Samekh.
        'ע',  // Letter Ayin.
        'ף',  // Letter final Pe.
        'פ',  // Letter Pe.
        'ץ',  // Letter final Tsadi.
        'צ',  // Letter Tsadi.
        'ק',  // Letter Qof.
        'ר',  // Letter Resh.
        'ש',  // Letter Shin.
        'ת',  // Letter Tav.
        'װ',  // Ligature Yiddish double Vav.
        'ױ	', // Ligature Yiddish Vav Yod.,
        'ײ	', // Ligature yiddish double Yod.,
        '׳',  // Punctuation Geresh.
        '״',  // Punctuation Gershayim.
             
        // Cyrillic (Russia).
        // Uppercase.
        'А',  // A.
        'Б',  // BE.
        'В',  // VE.
        'Г',  // GHE.
        'Д',  // DE.
        'Е',  // IE.
        'Ж',  // ZHE.
        'З',  // ZE.
        'И',  // I.
        'Й',  // SHORT.
        'К',  // KA.
        'Л',  // EL.
        'М',  // EM.
        'Н',  // EN.
        'О',  // O.
        'П',  // PE.
        'Р',  // ER.
        'С',  // ES.
        'Т',  // TE.
        'У',  // U.
        'Ф',  // EF.
        'Х',  // HA.
        'Ц',  // TSE.
        'Ч',  // CHE.
        'Ш',  // SHA.
        'Щ',  // SHCHA.
        'Ъ',  // SIGN.
        'Ы',  // YERU.
        'Ь',  // SOFT.
        'Э',  // E.
        'Ю',  // YU.
        'Я',  // YA.
             
        // Lowercase.
        'а',  // A.
        'б',  // BE.
        'в',  // VE.
        'г',  // GHE.
        'д',  // DE.
        'е',  // IE.
        'ж',  // ZHE.
        'з',  // ZE.
        'и',  // I.
        'й',  // SHORT I.
        'к',  // KA.
        'л',  // EL.
        'м',  // EM.
        'н',  // EN.
        'о',  // O.
        'п',  // PE.
        'р',  // ER.
        'с',  // ES.
        'т',  // Te.
        'у',  // U.
        'ф',  // EF.
        'х',  // HA.
        'ц',  // TSE.
        'ч',  // CHE.
        'ш',  // SHA.
        'щ',  // SHCA.
        'ъ',  // Hard sign.
        'ы',  // Yeru.
        'ь',  // Soft sign.
        'э',  // E.
        'ю',  // Yu.
        'я',  // Ya.
             
        // Cyrillic (Ukrania, Serbia, Bielorrusia).
        // Uppercase.
        'Ѐ',  // IE WITH GRAVE.
        'Ё',  // IO.
        'Ђ',  // DJE.
        'Ѓ',  // GJE.
        'Є',  // IE.
        'Ѕ',  // DZE.
        'І',  // I.
        'Ї',  // YI.
        'Ј',  // JE.
        'Љ',  // LJE.
        'Њ',  // NJE.
        'Ћ',  // TSHE.
        'Ќ',  // KJE.
        'Ѝ',  // I WITH GRAVE.
        'Ў',  // U.
        'Џ',  // DZHE.
             
        // Lowercase.
        'ѐ',  // IE WITH GRAVE.
        'ё',  // IO.
        'ђ',  // DJE.
        'ѓ',  // GJE.
        'є',  // UKRAINIAN IE.
        'ѕ',  // DZE.
        'і',  // BYELORUSSIAN-UKRAINIAN I.
        'ї',  // YI.
        'ј',  // JE.
        'љ',  // LJE.
        'њ',  // NJE.
        'ћ',  // TSHE.
        'ќ',  // KJE.
        'ѝ',  // I WITH GRAVE.
        'ў',  // SHORT U.
        'џ',  // DZHE.
             
        // Armenian marks.
        // Uppercase.
        'Ա', // Ayb.
        'Բ', // Ben. 
        'Գ', // Gim. 
        'Դ', // Da. 
        'Ե', // Ech. 
        'Զ', // Za. 
        'Է', // Eh. 
        'Ը', // Et. 
        'Թ', // To.
        'Ժ', // Zhe. 
        'Ի', // Ini. 
        'Լ', // Liwn. 
        'Խ', // Xeh.
        'Ծ', // Ca. 
        'Կ', // Ken. 
        'Հ', // Ho. 
        'Ձ', // Ja. 
        'Ղ', // Ghad. 
        'Ճ', // Cheh. 
        'Մ', // Men.
        'Յ', // Yi. 
        'Ն', // Now. 
        'Շ', // Sha. 
        'Ո', // Vo. 
        'Չ', // Cha. 
        'Պ', // Peh. 
        'Ջ', // Jheh. 
        'Ռ', // Ra.
        'Ս', // Seh. 
        'Վ', // Vew. 
        'Տ', // Tiwn. 
        'Ր', // Reh. 
        'Ց', // Co. 
        'Ւ', // Yiwn. 
        'Փ', // Piwr. 
        'Ք', // Keh. 
        'Օ', // Oh.
        'Ֆ', // Feh. 
             
        // Lowercase.
        'ա', // Ayb.
        'բ', // Ben. 
        'գ', // Gim. 
        'դ', // Da. 
        'ե', // Ech. 
        'զ', // Za. 
        'է', // Eh. 
        'ը', // Et. 
        'թ', // To. 
        'ժ', // Zhe. 
        'ի', // Ini. 
        'լ', // Liwn.
        'խ', // Xeh.
        'ծ', // Ca. 
        'կ', // Ken. 
        'հ', // Ho. 
        'ձ', // Ja. 
        'ղ', // Ghad. 
        'ճ', // Cheh. 
        'մ', // Men. 
        'յ', // Yi.
        'ն', // Now. 
        'շ', // Sha. 
        'ո', // Vo. 
        'չ', // Cha. 
        'պ',// Peh.
        'ջ', // Jheh. 
        'ռ', // Ra. 
        'ս', // Seh. 
        'վ', // Vew. 
        'տ', // Tiwn.
        'ր', // Reh. 
        'ց', // Co. 
        'ւ', // Yiwn. 
        'փ', // Piwr.
        'ք', // Keh. 
        'օ', // Oh. 
        'ֆ', // Feh. 
             
        // Armenia.
        'ɋ', // Ligature ech Yiwn.
        '՚՚՚', // Apostrophe. 
        '՛', // Emphasis mark.
        '՜', // Exclamation mark. 
        '՝', // Comma.
        '՞', // Question mark. 
        '՟', // Abbreviation mark. 
        '։', // Full stop.
        '֊', // Hyphen.
             
        // Hiragana (Japan).
        'ぁ', // A lowercase. 
        'あ', // A uppercase.
        'ぃ', // I lowercase.
        'い', // I uppercase.
        'ぅ', // U lowercase. 
        'う', // U uppercase. 
        'ぇ', // E lowercase. 
        'え', // E uppercase.
        'ぉ', // O lowercase.
        'お', // O uppercase.
        'か', // Ka.
        'が', // Ga.
        'き', // Ki.
        'ぎ', // Gi.
        'く', // Ku. 
        'ぐ', // Gu. 
        'け', // Ke.
        'げ', // Ge.
        'こ', // Ko.
        'ご', // Go.
        'さ', // Sa.
        'ざ', // Za.
        'し', // Si.
        'じ', // Zi.
        'す', // Su.
        'ず', // Zu.
        'せ', // Se.
        'ぜ', // Ze.
        'そ', // So.
        'ぞ', // Zo.
        'た', // Ta.
        'だ', // Da.
        'ち', // Ti.
        'ぢ', // Di.
        'っ', // Tu lowercase. 
        'つ', // Tu uppercase.
        'づ', // Du.
        'て', // Te.
        'で', // De.
        'と', // To. 
        'ど', // Do.
        'な', // Na.
        'に', // Ni.
        'ぬ', // Nu.
        'ね', // Ne.
        'の', // No.
        'は', // Ha.
        'ば', // Ba.
        'ぱ', // Pa.
        'ひ', // Hi.
        'び', // Bi.
        'ぴ', // Pi.
        'ふ', // Hu.
        'ぶ', // Bu.
        'ぷ', // Pu.
        'へ', // He.
        'べ', // Be.
        'ぺ', // Pe.
        'ほ', // Ho.
        'ぼ', // Bo.
        'ぽ', // Po.
        'ま', // Ma.
        'み', // Mi.
        'む', // Mu.
        'め', // Me.
        'も', // Mo.
        'ゃ', // Ya lowercase.
        'や', // Ya uppercase.
        'ゅ', // Yu lowercase.
        'ゆ', // Yu uppercase.
        'ょ', // Yo lowercase. 
        'よ', // Yo uppercase.
        'ら', // Ra.
        'り', // Ri. 
        'る', // Ru.
        'れ', // Re.
        'ろ', // Ro.
        'ゎ', // Wa lowercase.
        'わ', // Wa uppercase.
        'ゐ', // Wi.
        'ゑ', // We.
        'を', // Wo.
        'ん', // N.
        'ゔ', // Vu.
        'ゕ', // Ka lowercase.
        'ゖ', // Ke lowercase.
        '゛', // Voiced sound. 
        '゜', // Semi-voiced sound. 
        'ゝ', // Iteration.
        'ゞ', // Voiced iteration.
        'ゟ', // Digraph Yori.
             
        // Katakana (Japan).
        '゠', // Double hyphen. 
        'ァ', // A lowercase. 
        'ア', // A uppercase.
        'ィ', // I lowercase. 
        'イ', // I uppercase.
        'ゥ', // I lowercase. 
        'ウ', // U uppercase.
        'ェ', // E lowercase. 
        'エ', // E uppercase.
        'ォ', // O lowercase.
        'オ', // O uppercase.
        'カ', // Ka.
        'ガ', // Ga.
        'キ', // Ki.
        'ギ', // Gi.
        'ク', // Ku.
        'グ', // Gu.
        'ケ', // Ke.
        'ゲ', // Ge.
        'コ', // Ko.
        'ゴ', // Go.
        'サ', // Sa.
        'ザ', // Za.
        'シ', // Si.
        'ジ', // Zi.
        'ス', // Su.
        'ズ', // Zu.
        'セ', // Se.
        'ゼ', // Ze.
        'ソ', // So.
        'ゾ', // Zo.
        'タ', // Ta.
        'ダ', // Da.
        'チ', // Ti.
        'ヂ', // Di.
        'ッ', // Tu lowercase. 
        'ツ', // Tu uppercase.
        'ヅ', // Du.
        'テ', // Te.
        'デ', // De.
        'ト', // To.
        'ド', // Do.
        'ナ', // Na.
        'ニ', // Ni.
        'ヌ', // Nu.
        'ネ', // Ne.
        'ノ', // No.
        'ハ', // Ha.
        'バ', // Ba.
        'パ', // Pa.
        'ヒ', // Hi. 
        'ビ', // Bi.
        'ピ', // Pi.
        'フ', // Hu.
        'ブ', // Bu.
        'プ', // Pu.
        'ヘ', // He.
        'ベ', // Be.
        'ペ', // Pe.
        'ホ', // Ho.
        'ボ', // Bo.
        'ポ', // Po.
        'マ', // Ma.
        'ミ', // Mi. 
        'ム', // Mu.
        'メ', // Me.
        'モ', // Mo.
        'ャ', // Ya lowercase.
        'ヤ', // Ya uppercase.
        'ュ', // Yu lowercase.
        'ユ', // Yu uppercase.
        'ョ', // Yo lowercase. 
        'ヨ', // Yo uppercase. 
        'ラ', // Ra.
        'リ', // Ri. 
        'ル', // Ru.
        'レ', // Re.
        'ロ', // Ro.
        'ヮ', // Wa lowercase. 
        'ワ', // Wa uppercase.
        'ヰ', // Wi.
        'ヱ', // We.
        'ヲ', // Wo.
        'ン', // N.
        'ヴ', // Vu.
        'ヵ', // Ka lowercase. 
        'ヶ', // Ke lowercase.
        'ヷ', // Va.
        'ヸ', // Vi.
        'ヹ', // Ve.
        'ヺ', // Vo.
        '・', // Middle dot. 
        'ー', // Prolonged sound.
        'ヽ', // Iteration.
        'ヾ', // Voice iteration.
        'ヿ');// Digraph KoTo.
    
    $replace = array(
        
        // West european characters.
        '&#225;', // (á)
        '&#233;', // (é)
        '&#237;', // (í)
        '&#243;', // (ó)
        '&#250;', // (ú)
        '&#193;', // (Á)
        '&#201;', // (É)
        '&#205;', // (Í)
        '&#211;', // (Ó)
        '&#218;', // (Ú)
                  
        '&#224;', // (à)
        '&#232;', // (è)
        '&#236;', // (ì)
        '&#242;', // (ò)
        '&#249;', // (ù)
        '&#192;', // (À)
        '&#200;', // (È)
        '&#204;', // (Ì)
        '&#210;', // (Ò)
        '&#217;', // (Ù)
                  
        '&#226;', // (â)
        '&#234;', // (ê)
        '&#238;', // (î)
        '&#244;', // (ô)
        '&#251;', // (û)
        '&#194;', // (Â)
        '&#202;', // (Ê)
        '&#206;', // (Î)
        '&#212;', // (Ô)
        '&#219;', // (Û)
                  
        '&#228;', // (ä)
        '&#235;', // (ë)
        '&#239;', // (ï)
        '&#246;', // (ö)
        '&#252;', // (ü)
        '&#196;', // (Ä)
        '&#203;', // (Ë)
        '&#207;', // (Ï)
        '&#214;', // (Ö)
        '&#220;', // (Ü)
                  
        // Spanish characters.
        '&#241;', // (ñ)
        '&#209;', // (Ñ)
        '&#231;', // (ç)
        '&#199;', // (Ç)
                  
        // Commas, accents and cedillas.
        '&#8220;', // (“)
        '&#8221;', // (”)
        '&#8216;', // (‘)
        '&#8217;', // (’)
        '&#168;',  // Spacing dieresis.  // (¨)
        '&#180;',  // Acute accent.      // (´)
        '&#183;',  // Georgian comma.    // (·)
        '&#184;',  // Spacing cedilla.   // (¸)
                  
        // Punctuation marks,           
        '&#171;', // («)
        '&#187;', // (»)
        '&#191;', // (¿)
        '&#161;', // (¡)
        '&#8230;',// (…)
                  
        // Book marks.                  
        '&#8212;', // Em dash.                                       (—)
        '&#8226;', // Bullet.                                        (•)
        '&#8224;', // Dagger.                                        (†)
        '&#8225;', // Double dagger.                                 (‡)
        '&#167;',  // Section.                                       (§)
        '&#182;',  // Pilcrow - paragraph sign.                      (¶)
                     
        // Currency marks               
        '&#8364;', // Euro.                                          (€)
        '&#162;',  // Cent.                                          (¢)
        '&#163;',  // Pound.                                         (£)
        '&#164;',  // Currency.                                      (¤)
        '&#165;',  // Yen.                                           (¥)
                     
        // Math marks.                  
        '&#176;', // Degree sign.                                    (°)
        '&#177;', // Plus or minus.                                  (±)
        '&#186;', // Masculine ordinal indicator.                    (º)
        '&#185;', // Spacing cedilla.                                (¹)
        '&#178;', // Superscript two - squared.                      (²)
        '&#179;', // Superscript three - cubed.                      (³)
        '&#181;', // Micro sign.                                     (µ)
        '&#188;', // Fraction one quarter.                           (¼)
        '&#189;', // Fraction one half                               (½)
        '&#190;', // Fraction three quarters.                        (¾)
        '&#248;', // Latin letter o with slash.                      (ø)
        '&#216;', // Latin letter O with slash.                      (Ø)
        '&#247;', // Division sign.                                  (÷)
        '&#215;', // Multiplication sign.                            (×)
        '&#402;', // Latin small f with hook = function = florin.    (ƒ)
        '&#8254;',// Overline = spacing overscore.                   (‾)
        '&#8260;', // Fraction slash.                                (⁄)
        '&#8472;', // Script capital P = power set = Weierstrass p.  (℘)
        '&#8465;', // Blackletter capital I = imaginary part.        (ℑ)
        '&#8476;', // Blackletter capital R = real part.             (ℜ)
        '&#8592;', // Leftwards arrow.                               (←)
        '&#8593;', // Upwards arrow.                                 (↑)
        '&#8594;', // Rightwards arrow.                              (→)
        '&#8595;', // Downwards arrow.                               (↓)
        '&#8596;', // Left right arrow.                              (↔)
        '&#8596;', // Left right arrow.                              (↔)
        '&#8656;', // Leftwards double arrow.                        (⇐)
        '&#8657;', // Upwards double arrow.                          (⇑)
        '&#8658;', // Rightwards double arrow.                       (⇒)
        '&#8659;', // Downwards double arrow.                        (⇓)
        '&#8660;', // Left right double arrow.                       (⇔)
        '&#8704;', // For all.                                       (∀)
        '&#8706;', // Partial differential.                          (∂)
        '&#8707;', // There exists.                                  (∃)
        '&#8709;', // Empty set = diameter.                          (∅)
        '&#8711;', // Nabla = backward difference.                   (∇)
        '&#8712;', // Element of.                                    (∈)
        '&#8713;', // Not an element of.                             (∉)
        '&#8715;', // Contains as member.                            (∋)
        '&#8719;', // n-ary product = product sign.                  (∏)
        '&#8721;', // n-ary sumation.                                (∑)
        '&#8722;', // Minus sign.                                    (−)
        '&#8727;', // Asterisk operator.                             (∗)
        '&#8730;', // Square root = radical sign.                    (√)
        '&#8733;', // Proportional to.                               (∝)
        '&#8734;', // Infinity.                                      (∞)
        '&#8736;', // Angle.                                         (∠)
        '&#8743;', // Logical and.                                   (∧)
        '&#8744;', // Logical or = vee.                              (∨)
        '&#8745;', // Intersection = cap.                            (∩)
        '&#8746;', // union = cup.                                   (∪)
        '&#8747;', // Integral.                                      (∫)
        '&#8756;', // Therefore.                                     (∴)
        '&#8764;', // Tilde operator = varies with = similar to.     (∼)
        '&#8773;', // Approximately equal to.                        (≅)
        '&#8776;', // Almost equal to = asymptotic to.               (≈)
        '&#8800;', // Not equal to.                                  (≠)
        '&#8801;', // Identical to.                                  (≡)
        '&#8804;', // Less-than or equal to.                         (≤)
        '&#8805;', // Greater-than or equal to.                      (≥)
        '&#8834;', // Subset of.                                     (⊂)
        '&#8835;', // Superset of.                                   (⊃)
        '&#8836;', // Not a subset of.                               (⊄)
        '&#8838;', // Subset of or equal to.                         (⊆)
        '&#8839;', // Superset of or equal to.                       (⊇)
        '&#8853;', // Circled plus = direct sum.                     (⊕)
        '&#8855;', // Circled times = vector product.                (⊗)
        '&#8869;', // Up tack.                                       (⊥)
                     
        // Time marks.                  
        '&#8242;', // Prime = minutes = feet.                        (′)
        '&#8243;', // Double prime = seconds = inches.               (″)
                     
        // Other marks.                 
        '&#169;',  // Copyright.                                     (©)
        '&#174;',  // Registered trade mark.                         (®)
        '&#8482;', // Trade mark.                                    (™)
                     
        // Greek characters.            
        '&#913;',  // Alpha.                                         (Α)
        '&#914;',  // Beta.                                          (Β)
        '&#915;',  // Gamma.                                         (Γ)
        '&#916;',  // Delta.                                         (Δ)
        '&#917;',  // Epsilon.                                       (Ε)
        '&#918;',  // Zeta.                                          (Ζ)
        '&#919;',  // Eta.                                           (Η)
        '&#920;',  // Theta.                                         (Θ)
        '&#921;',  // Iota.                                          (Ι)
        '&#922;',  // Kappa.                                         (Κ)
        '&#923;',  // Lambda.                                        (Λ)
        '&#924;',  // Mu.                                            (Μ)
        '&#925;',  // Nu.                                            (Ν)
        '&#926;',  // Xi.                                            (Ξ)
        '&#927;',  // Omicron.                                       (Ο)
        '&#928;',  // Pi.                                            (Π)
        '&#929;',  // Rho.                                           (Ρ)
        '&#931;',  // Sigma.                                         (Σ)
        '&#932;',  // Tau.                                           (Τ)
        '&#933;',  // Upsilon.                                       (Υ)
        '&#934;',  // Phi.                                           (Φ)
        '&#935;',  // Chi.                                           (Χ)
        '&#936;',  // Psi.                                           (Ψ)
        '&#937;',  // Omega.                                         (Ω)
        '&#945;',  // Alpha.                                         (α)
        '&#946;',  // Beta.                                          (β)
        '&#947;',  // Gamma.                                         (γ)
        '&#948;',  // Delta.                                         (δ)
        '&#949;',  // Epsilon.                                       (ε)
        '&#950;',  // Zeta.                                          (ζ)
        '&#951;',  // Eta.                                           (η)
        '&#952;',  // Theta.                                         (θ)
        '&#953;',  // Iota.                                          (ι)
        '&#954;',  // Kappa.                                         (κ)
        '&#955;',  // Lambda.                                        (λ)
        '&#956;',  // Mu.                                            (μ)
        '&#957;',  // Nu.                                            (ν)
        '&#958;',  // Xi.                                            (ξ)
        '&#959;',  // Omicron.                                       (ο)
        '&#960;',  // Pi.                                            (π)
        '&#961;',  // Rho.                                           (ρ)
        '&#962;',  // Sigmaf.                                        (ς)
        '&#963;',  // Sigma.                                         (σ)
        '&#964;',  // Tau.                                           (τ)
        '&#965;',  // Upsilon.                                       (υ)
        '&#966;',  // Phi.                                           (φ)
        '&#967;',  // Chi.                                           (χ)
        '&#968;',  // Psi.                                           (ψ)
        '&#969;',  // Omega.                                         (ω)
                     
        // Hebrew charact               
        '&#1425;', // Accent Etnahta.                                (֑)
        '&#1426;', // Accent Segol.                                  (֒)
        '&#1427;', // Accent Shalshelet.                             (֓)
        '&#1428;', // Accent Zaqef Qatan.                            (֔)
        '&#1429;', // Accent Zaqef Gadol.                            (֔)
        '&#1430;', // Accent Tipeha.                                 (֖)
        '&#1431;', // Accent Revia.                                  (֗)
        '&#1432;', // Accent Zarqa.                                  (֘)
        '&#1433;', // Accent Pashta.                                 (֙)
        '&#1434;', // Accent Yetiv.                                  (֚)
        '&#1435;', // Accent Tevir.                                  (֛)
        '&#1436;', // Accent Geresh.                                 (֜)
        '&#1437;', // Accent Geresh Muqdam.                          (֝)
        '&#1438;', // Accent Gershayim.                              (֞)
        '&#1439;', // Accent Qarney Para.                            (֟)
        '&#1440;', // Accent Telisha Gedola.                         (֠)
        '&#1441;', // Accent Pazer.                                  (֡)
        '&#1442;', // Accent atnah Hafukh.                           (֢)
        '&#1443;', // Accent Munah.                                  (֣)
        '&#1444;', // Accent Mahapakh.                               (֤)
        '&#1445;', // Accent Merkha.                                 (֥)
        '&#1446;', // Accent Kefula.                                 (֦)
        '&#1447;', // Accent Darga.                                  (֧)
        '&#1448;', // Accent Qadma.                                  (֨)
        '&#1449;', // Accent Telisha Qetana.                         (֩)
        '&#1450;', // Accent Yerah Ben Yomo.                         (֪)
        '&#1451;', // Accent Ole.                                    (֫)
        '&#1452;', // Accent Iluy.                                   (֭)
        '&#1453;', // Accent Dehi.                                   (֭)
        '&#1454;', // Accent Zinor.                                  (֮)
        '&#1455;', // Mark Masora circle.                            (֯)
        '&#1456;', // Point Sheva.                                   (ְ)
        '&#1457;', // Hataf segol.                                   (ֱ)
        '&#1458;', // Hataf patah.                                   (ֲ)
        '&#1459;', // Hataf qamats.                                  (ֳ)
        '&#1460;', // Point Hiriq.                                   (ִ)
        '&#1461;', // Point Tsere.                                   (ֵ)
        '&#1462;', // Point Segol.                                   (ֶ)
        '&#1463;', // Point Patah.                                   (ַ)
        '&#1464;', // Point Qamats.                                  (ָ)
        '&#1465;', // Point Holam.                                   (ֹ) 
        '&#1466;', // Point Holam Haser for Vav.                     (ֺ) 
        '&#1467;', // Point Qubuts.                                  (ֻ)
        '&#1468;', // Point Dagesh or Mapiq.                         (ּ)
        '&#1469;', // Point Meteg.                                   (ֽ)
        '&#1470;', // Punctuaction Maqaf.                            (־	
        '&#1471;', // Point Rafe.                                    (ֿ)
        '&#1472;', // Punctuaction Paseq.                            (׀)
        '&#1473;', // Point Shin dot.                                (ׁ)
        '&#1474;', // Point Sin dot.                                 (ׂ)
        '&#1475;', // Punctuation Sof pasuq.                         (׃	
        '&#1476;', // Mark upper dot.                                (ׄ)
        '&#1477;', // Mark lower dot.                                (ׅ)
        '&#1478;', // Punctuation Nun hafukha.                       (׆	
        '&#1479;', // Point Qamats Qatan.                            (ׇ)
        '&#1488;', // Letter Alef.                                   (א)
        '&#1489;', // Letter Bet.                                    (ב)
        '&#1490;', // Letter Gimel.                                  (ג)
        '&#1491;', // Letter Dalet.                                  (ד)
        '&#1492;', // Letter He.                                     (ה)
        '&#1493;', // Letter Vav.                                    (ו)
        '&#1494;', // Letter Zayin.                                  (ז)
        '&#1495;', // Letter Het.                                    (ח)
        '&#1496;', // Letter Tet.                                    (ט)
        '&#1497;', // Letter Yod.                                    (י)
        '&#1499;', // Letter Kaf.                                    (כ)
        '&#1500;', // Letter Lamed.                                  (ל	
        '&#1501;', // Letter final Mem.                              (ם)
        '&#1502;', // Letter Mem.                                    (מ	
        '&#1503;', // Letter final Nun.                              (ן)
        '&#1504;', // Letter Nun.                                    (נ)
        '&#1505;', // Letter Samekh.                                 (ס)
        '&#1506;', // Letter Ayin.                                   (ע)
        '&#1507;', // Letter final Pe.                               (ף)
        '&#1508;', // Letter Pe.                                     (פ)
        '&#1509;', // Letter final Tsadi.                            (ץ)
        '&#1510;', // Letter Tsadi.                                  (צ)
        '&#1511;', // Letter Qof.                                    (ק)
        '&#1512;', // Letter Resh.                                   (ר)
        '&#1513;', // Letter Shin.                                   (ש)
        '&#1514;', // Letter Tav.                                    (ת)
        '&#1520;', // Ligature Yiddish double Vav.                   (װ)
        '&#1521;', // Ligature Yiddish Vav Yod.                      (ױ	
        '&#1522;', // Ligature yiddish double Yod.                   (ײ	
        '&#1523;', // Punctuation Geresh.                            (׳)
        '&#1524;', // Punctuation Gershayim.                         (״)
                     
        // Cyrillic (Russia)            
        // Uppercase.                   
        '&#1040;', // A.                                             (А)
        '&#1041;', // BE.                                            (Б)
        '&#1042;', // VE.                                            (В)
        '&#1043;', // GHE.                                           (Г)
        '&#1044;', // DE.                                            (Д)
        '&#1045;', // IE.                                            (Е)
        '&#1046;', // ZHE.                                           (Ж)
        '&#1047;', // ZE.                                            (З)
        '&#1048;', // I.                                             (И)
        '&#1049;', // SHORT.                                         (Й)
        '&#1050;', // KA.                                            (К)
        '&#1051;', // EL.                                            (Л)
        '&#1052;', // EM.                                            (М)
        '&#1053;', // EN.                                            (Н)
        '&#1054;', // O.                                             (О)
        '&#1055;', // PE.                                            (П)
        '&#1056;', // ER.                                            (Р)
        '&#1057;', // ES.                                            (С)
        '&#1058;', // TE.                                            (Т)
        '&#1059;', // U.                                             (У)
        '&#1060;', // EF.                                            (Ф)
        '&#1061;', // HA.                                            (Х)
        '&#1062;', // TSE.                                           (Ц)
        '&#1063;', // CHE.                                           (Ч)
        '&#1064;', // SHA.                                           (Ш)
        '&#1065;', // SHCHA.                                         (Щ)
        '&#1066;', // SIGN.                                          (Ъ)
        '&#1067;', // YERU.                                          (Ы)
        '&#1068;', // SOFT.                                          (Ь)
        '&#1069;', // E.                                             (Э)
        '&#1070;', // YU.                                            (Ю)
        '&#1071;', // YA.                                            (Я)
                     
        // Lowercase.                   
        '&#1072;', // A.                                             (а)
        '&#1073;', // BE.                                            (б)
        '&#1074;', // VE.                                            (в)
        '&#1075;', // GHE.                                           (г)
        '&#1076;', // DE.                                            (д)
        '&#1077;', // IE.                                            (е)
        '&#1078;', // ZHE.                                           (ж)
        '&#1079;', // ZE.                                            (з)
        '&#1080;', // I.                                             (и)
        '&#1081;', // SHORT I.                                       (й)
        '&#1082;', // KA.                                            (к)
        '&#1083;', // EL.                                            (л)
        '&#1084;', // EM.                                            (м)
        '&#1085;', // EN.                                            (н)
        '&#1086;', // O.                                             (о)
        '&#1087;', // PE.                                            (п)
        '&#1088;', // ER.                                            (р)
        '&#1089;', // ES.                                            (с)
        '&#1090;', // Te.                                            (т)
        '&#1091;', // U.                                             (у)
        '&#1092;', // EF.                                            (ф)
        '&#1093;', // HA.                                            (х)
        '&#1094;', // TSE.                                           (ц)
        '&#1095;', // CHE.                                           (ч)
        '&#1096;', // SHA.                                           (ш)
        '&#1097;', // SHCA.                                          (щ)
        '&#1098;', // Hard sign.                                     (ъ)
        '&#1099;', // Yeru.                                          (ы)
        '&#1100;', // Soft sign.                                     (ь)
        '&#1101;', // E.                                             (э)
        '&#1102;', // Yu.                                            (ю)
        '&#1103;', // Ya.                                            (я)
                     
        // Cyrillic (Ukrania, Serbia, Bielorrusia)                   // 
        // Uppercase.                   
        '&#1024;', // IE WITH GRAVE.                                 (Ѐ)
        '&#1025;', // IO.                                            (Ё)
        '&#1026;', // DJE.                                           (Ђ)
        '&#1027;', // GJE.                                           (Ѓ)
        '&#1028;', // IE.                                            (Є)
        '&#1029;', // DZE.                                           (Ѕ)
        '&#1030;', // I.                                             (І)
        '&#1031;', // YI.                                            (Ї)
        '&#1032;', // JE.                                            (Ј)
        '&#1033;', // LJE.                                           (Љ)
        '&#1034;', // NJE.                                           (Њ)
        '&#1035;', // TSHE.                                          (Ћ)
        '&#1036;', // KJE.                                           (Ќ)
        '&#1037;', // I WITH GRAVE.                                  (Ѝ)
        '&#1038;', // U.                                             (Ў)
        '&#1039;', // DZHE.                                          (Џ)
                     
        // Lowercase.                   
        '&#1104;', // IE WITH GRAVE.                                 (ѐ)
        '&#1105;', // IO.                                            (ё)
        '&#1106;', // DJE.                                           (ђ)
        '&#1107;', // GJE.                                           (ѓ)
        '&#1108;', // UKRAINIAN IE.                                  (є)
        '&#1109;', // DZE.                                           (ѕ)
        '&#1110;', // BYELORUSSIAN-UKRAINIAN I.                      (і)
        '&#1111;', // YI.                                            (ї)
        '&#1112;', // JE.                                            (ј)
        '&#1113;', // LJE.                                           (љ)
        '&#1114;', // NJE.                                           (њ)
        '&#1115;', // TSHE.                                          (ћ)
        '&#1116;', // KJE.                                           (ќ)
        '&#1117;', // I WITH GRAVE.                                  (ѝ)
        '&#1118;', // SHORT U.                                       (ў)
        '&#1119;', // DZHE.                                          (џ)
                     
        // Armenian marks.              
        // Uppercase.                   
        '&#1329;', // Ayb.                                           (Ա)
        '&#1330;', // Ben.                                           (Բ) 
        '&#1331;', // Gim.                                           (Գ) 
        '&#1332;', // Da.                                            (Դ) 
        '&#1333;', // Ech.                                           (Ե) 
        '&#1334;', // Za.                                            (Զ) 
        '&#1335;', // Eh.                                            (Է) 
        '&#1336;', // Et.                                            (Ը) 
        '&#1337;', // To.                                            (Թ)
        '&#1338;', // Zhe.                                           (Ժ) 
        '&#1339;', // Ini.                                           (Ի) 
        '&#1340;', // Liwn.                                          (Լ) 
        '&#1341;', // Xeh.                                           (Խ)
        '&#1342;', // Ca.                                            (Ծ) 
        '&#1343;', // Ken.                                           (Կ) 
        '&#1344;', // Ho.                                            (Հ) 
        '&#1345;', // Ja.                                            (Ձ) 
        '&#1346;', // Ghad.                                          (Ղ) 
        '&#1347;', // Cheh.                                          (Ճ) 
        '&#1348;', // Men.                                           (Մ)
        '&#1349;', // Yi.                                            (Յ) 
        '&#1350;', // Now.                                           (Ն) 
        '&#1351;', // Sha.                                           (Շ) 
        '&#1352;', // Vo.                                            (Ո) 
        '&#1353;', // Cha.                                           (Չ) 
        '&#1354;', // Peh.                                           (Պ) 
        '&#1355;', // Jheh.                                          (Ջ) 
        '&#1356;', // Ra.                                            (Ռ)
        '&#1357;', // Seh.                                           (Ս) 
        '&#1358;', // Vew.                                           (Վ) 
        '&#1359;', // Tiwn.                                          (Տ) 
        '&#1360;', // Reh.                                           (Ր) 
        '&#1361;', // Co.                                            (Ց) 
        '&#1362;', // Yiwn.                                          (Ւ) 
        '&#1363;', // Piwr.                                          (Փ) 
        '&#1364;', // Keh.                                           (Ք) 
        '&#1365;', // Oh.                                            (Օ)
        '&#1366;', // Feh.                                           (Ֆ) 
                     
        // Lowercase.                   
        '&#1377;', // Ayb.                                           (ա)
        '&#1378;', // Ben.                                           (բ) 
        '&#1379;', // Gim.                                           (գ) 
        '&#1380;', // Da.                                            (դ) 
        '&#1381;', // Ech.                                           (ե) 
        '&#1382;', // Za.                                            (զ) 
        '&#1383;', // Eh.                                            (է) 
        '&#1384;', // Et.                                            (ը) 
        '&#1385;', // To.                                            (թ) 
        '&#1386;', // Zhe.                                           (ժ) 
        '&#1387;', // Ini.                                           (ի) 
        '&#1388;', // Liwn.                                          (լ) 
        '&#1389;', // Xeh.                                           (խ)
        '&#1390;', // Ca.                                            (ծ) 
        '&#1391;', // Ken.                                           (կ) 
        '&#1392;', // Ho.                                            (հ) 
        '&#1393;', // Ja.                                            (ձ) 
        '&#1394;', // Ghad.                                          (ղ) 
        '&#1395;', // Cheh.                                          (ճ) 
        '&#1396;', // Men.                                           (մ) 
        '&#1397;', // Yi.                                            (յ) 
        '&#1398;', // Now.                                           (ն) 
        '&#1399;', // Sha.                                           (շ) 
        '&#1400;', // Vo.                                            (ո) 
        '&#1401;', // Cha.                                           (չ) 
        '&#1402;', // Peh.                                           (պ)
        '&#1403;', // Jheh.                                          (ջ) 
        '&#1404;', // Ra.                                            (ռ) 
        '&#1405;', // Seh.                                           (ս) 
        '&#1406;', // Vew.                                           (վ) 
        '&#1407;', // Tiwn.                                          (տ)
        '&#1408;', // Reh.                                           (ր( 
        '&#1409;', // Co.                                            (ց) 
        '&#1410;', // Yiwn.                                          (ւ) 
        '&#1411;', // Piwr.                                          (փ)
        '&#1412;', // Keh.                                           (ք) 
        '&#1413;', // Oh.                                            (օ) 
        '&#1414;', // Feh.                                           (ֆ) 
                     
        // Armenia.                     
        '&#587;',  // Ligature ech Yiwn.                             (ɋ)
        '&#1370;', // Apostrophe.                                    (՚՚՚) 
        '&#1371;', // Emphasis mark.                                 (՛) 
        '&#1372;', // Exclamation mark.                              (՜) 
        '&#1373;', // Comma.                                         (՝) 
        '&#1374;', // Question mark.                                 (՞) 
        '&#1375;', // Abbreviation mark.                             (՟) 
        '&#1417;', // Full stop.                                     (։) 
        '&#1418;', // Hyphen.                                        (֊) 
                     
        // Hiragana (Japan).            
        '&#12353;', // A lowercase.                                  (ぁ) 
        '&#12354;', // A uppercase.                                  (あ)
        '&#12355;', // I lowercase.                                  (ぃ)
        '&#12356;', // I uppercase.                                  (い)
        '&#12357;', // U lowercase.                                  (ぅ) 
        '&#12358;', // U uppercase.                                  (う) 
        '&#12359;', // E lowercase.                                  (ぇ) 
        '&#12360;', // E uppercase.                                  (え)
        '&#12361;', // O lowercase.                                  (ぉ)
        '&#12362;', // O uppercase.                                  (お)
        '&#12363;', // Ka.                                           (か)
        '&#12364;', // Ga.                                           (が)
        '&#12365;', // Ki.                                           (き)
        '&#12366;', // Gi.                                           (ぎ)
        '&#12367;', // Ku.                                           (く) 
        '&#12368;', // Gu.                                           (ぐ) 
        '&#12369;', // Ke.                                           (け)
        '&#12370;', // Ge.                                           (げ)
        '&#12371;', // Ko.                                           (こ)
        '&#12372;', // Go.                                           (ご)
        '&#12373;', // Sa.                                           (さ)
        '&#12374;', // Za.                                           (ざ)
        '&#12375;', // Si.                                           (し)
        '&#12376;', // Zi.                                           (じ)
        '&#12377;', // Su.                                           (す)
        '&#12378;', // Zu.                                           (ず)
        '&#12379;', // Se.                                           (せ)
        '&#12380;', // Ze.                                           (ぜ)
        '&#12381;', // So.                                           (そ)
        '&#12382;', // Zo.                                           (ぞ)
        '&#12383;', // Ta.                                           (た)
        '&#12384;', // Da.                                           (だ)
        '&#12385;', // Ti.                                           (ち)
        '&#12386;', // Di.                                           (ぢ)
        '&#12387;', // Tu lowercase.                                 (っ) 
        '&#12388;', // Tu uppercase.                                 (つ)
        '&#12389;', // Du.                                           (づ)
        '&#12390;', // Te.                                           (て)
        '&#12391;', // De.                                           (で)
        '&#12392;', // To.                                           (と) 
        '&#12393;', // Do.                                           (ど)
        '&#12394;', // Na.                                           (な)
        '&#12395;', // Ni.                                           (に)
        '&#12396;', // Nu.                                           (ぬ)
        '&#12397;', // Ne.                                           (ね)
        '&#12398;', // No.                                           (の)
        '&#12399;', // Ha.                                           (は)
        '&#12400;', // Ba.                                           (ば)
        '&#12401;', // Pa.                                           (ぱ)
        '&#12402;', // Hi.                                           (ひ)
        '&#12403;', // Bi.                                           (び)
        '&#12404;', // Pi.                                           (ぴ)
        '&#12405;', // Hu.                                           (ふ)
        '&#12406;', // Bu.                                           (ぶ)
        '&#12407;', // Pu.                                           (ぷ)
        '&#12408;', // He.                                           (へ)
        '&#12409;', // Be.                                           (べ)
        '&#12410;', // Pe.                                           (ぺ)
        '&#12411;', // Ho.                                           (ほ)
        '&#12412;', // Bo.                                           (ぼ)
        '&#12413;', // Po.                                           (ぽ)
        '&#12414;', // Ma.                                           (ま)
        '&#12415;', // Mi.                                           (み)
        '&#12416;', // Mu.                                           (む)
        '&#12417;', // Me.                                           (め)
        '&#12418;', // Mo.                                           (も)
        '&#12419;', // Ya lowercase.                                 (ゃ)
        '&#12420;', // Ya uppercase.                                 (や)
        '&#12421;', // Yu lowercase.                                 (ゅ)
        '&#12422;', // Yu uppercase.                                 (ゆ)
        '&#12423;', // Yo lowercase.                                 (ょ) 
        '&#12424;', // Yo uppercase.                                 (よ)
        '&#12425;', // Ra.                                           (ら)
        '&#12426;', // Ri.                                           (り) 
        '&#12427;', // Ru.                                           (る)
        '&#12428;', // Re.                                           (れ)
        '&#12429;', // Ro.                                           (ろ)
        '&#12430;', // Wa lowercase.                                 (ゎ)
        '&#12431;', // Wa uppercase.                                 (わ)
        '&#12432;', // Wi.                                           (ゐ)
        '&#12433;', // We.                                           (ゑ)
        '&#12434;', // Wo.                                           (を)
        '&#12435;', // N.                                            (ん)
        '&#12436;', // Vu.                                           (ゔ)
        '&#12437;', // Ka lowercase.                                 (ゕ)
        '&#12438;', // Ke lowercase.                                 (ゖ)
        '&#12443;', // Voiced sound.                                 (゛) 
        '&#12444;', // Semi-voiced sound.                            (゜) 
        '&#12445;', // Iteration.                                    (ゝ)
        '&#12446;', // Voiced iteration.                             (ゞ)
        '&#12447;', // Digraph Yori.                                 (ゟ)
                     
        // Katakana (Japan).            
        '&#12448;', // Double hyphen.                                (゠)
        '&#12449;', // A lowercase.                                  (ァ) 
        '&#12450;', // A uppercase.                                  (ア)
        '&#12451;', // I lowercase.                                  (ィ) 
        '&#12452;', // I uppercase.                                  (イ)
        '&#12453;', // I lowercase.                                  (ゥ) 
        '&#12454;', // U uppercase.                                  (ウ)
        '&#12455;', // E lowercase.                                  (ェ) 
        '&#12456;', // E uppercase.                                  (エ)
        '&#12457;', // O lowercase.                                  (ォ)
        '&#12458;', // O uppercase.                                  (オ)
        '&#12459;', // Ka.                                           (カ)
        '&#12460;', // Ga.                                           (ガ)
        '&#12461;', // Ki.                                           (キ)
        '&#12462;', // Gi.                                           (ギ)
        '&#12463;', // Ku.                                           (ク)
        '&#12464;', // Gu.                                           (グ)
        '&#12465;', // Ke.                                           (ケ)
        '&#12466;', // Ge.                                           (ゲ)
        '&#12467;', // Ko.                                           (コ)
        '&#12468;', // Go.                                           (ゴ)
        '&#12469;', // Sa.                                           (サ)
        '&#12470;', // Za.                                           (ザ)
        '&#12471;', // Si.                                           (シ)
        '&#12472;', // Zi.                                           (ジ)
        '&#12473;', // Su.                                           (ス)
        '&#12474;', // Zu.                                           (ズ)
        '&#12475;', // Se.                                           (セ)
        '&#12476;', // Ze.                                           (ゼ)
        '&#12477;', // So.                                           (ソ)
        '&#12478;', // Zo.                                           (ゾ)
        '&#12479;', // Ta.                                           (タ)
        '&#12480;', // Da.                                           (ダ)
        '&#12481;', // Ti.                                           (チ)
        '&#12482;', // Di.                                           (ヂ)
        '&#12483;', // Tu lowercase.                                 (ッ) 
        '&#12484;', // Tu uppercase.                                 (ツ)
        '&#12485;', // Du.                                           (ヅ)
        '&#12486;', // Te.                                           (テ)
        '&#12487;', // De.                                           (デ)
        '&#12488;', // To.                                           (ト)
        '&#12489;', // Do.                                           (ド)
        '&#12490;', // Na.                                           (ナ)
        '&#12491;', // Ni.                                           (ニ)
        '&#12492;', // Nu.                                           (ヌ)
        '&#12493;', // Ne.                                           (ネ)
        '&#12494;', // No.                                           (ノ)
        '&#12495;', // Ha.                                           (ハ)
        '&#12496;', // Ba.                                           (バ)
        '&#12497;', // Pa.                                           (パ)
        '&#12498;', // Hi.                                           (ヒ) 
        '&#12499;', // Bi.                                           (ビ)
        '&#12500;', // Pi.                                           (ピ)
        '&#12501;', // Hu.                                           (フ)
        '&#12502;', // Bu.                                           (ブ)
        '&#12503;', // Pu.                                           (プ)
        '&#12504;', // He.                                           (ヘ)
        '&#12505;', // Be.                                           (ベ)
        '&#12506;', // Pe.                                           (ペ)
        '&#12507;', // Ho.                                           (ホ)
        '&#12508;', // Bo.                                           (ボ)
        '&#12509;', // Po.                                           (ポ)
        '&#12510;', // Ma.                                           (マ)
        '&#12511;', // Mi.                                           (ミ) 
        '&#12512;', // Mu.                                           (ム)
        '&#12513;', // Me.                                           (メ)
        '&#12514;', // Mo.                                           (モ)
        '&#12515;', // Ya lowercase.                                 (ャ)
        '&#12516;', // Ya uppercase.                                 (ヤ)
        '&#12517;', // Yu lowercase.                                 (ュ)
        '&#12518;', // Yu uppercase.                                 (ユ)
        '&#12519;', // Yo lowercase.                                 (ョ) 
        '&#12520;', // Yo uppercase.                                 (ヨ) 
        '&#12521;', // Ra.                                           (ラ)
        '&#12522;', // Ri.                                           (リ) 
        '&#12523;', // Ru.                                           (ル)
        '&#12524;', // Re.                                           (レ)
        '&#12525;', // Ro.                                           (ロ)
        '&#12526;', // Wa lowercase.                                 (ヮ) 
        '&#12527;', // Wa uppercase.                                 (ワ)
        '&#12528;', // Wi.                                           (ヰ)
        '&#12529;', // We.                                           (ヱ)
        '&#12530;', // Wo.                                           (ヲ)
        '&#12531;', // N.                                            (ン)
        '&#12532;', // Vu.                                           (ヴ)
        '&#12533;', // Ka lowercase.                                 (ヵ) 
        '&#12534;', // Ke lowercase.                                 (ヶ)
        '&#12535;', // Va.                                           (ヷ)
        '&#12536;', // Vi.                                           (ヸ)
        '&#12537;', // Ve.                                           (ヹ)
        '&#12538;', // Vo.                                           (ヺ)
        '&#12539;', // Middle dot.                                   (・) 
        '&#12540;', // Prolonged sound.                              (ー)
        '&#12541;', // Iteration.                                    (ヽ)
        '&#12542;', // Voice iteration.                              (ヾ)
        '&#12543;');// Digraph KoTo.                                 (ヿ)
    
function fm_htmlentities_ex($string) {
    
    global $search;
    global $replace;
    /*
    // West european characters.
    $string = str_replace('á','&#225;', $string);
    $string = str_replace('é','&#233;', $string);
    $string = str_replace('í','&#237;', $string);
    $string = str_replace('ó','&#243;', $string);
    $string = str_replace('ú','&#250;', $string);
    $string = str_replace('Á','&#193;', $string);
    $string = str_replace('É','&#201;', $string);
    $string = str_replace('Í','&#205;', $string);
    $string = str_replace('Ó','&#211;', $string);
    $string = str_replace('Ú','&#218;', $string);
           
    $string = str_replace('à','&#224;', $string);
    $string = str_replace('è','&#232;', $string);
    $string = str_replace('ì','&#236;', $string);
    $string = str_replace('ò','&#242;', $string);
    $string = str_replace('ù','&#249;', $string);
    $string = str_replace('À','&#192;', $string);
    $string = str_replace('È','&#200;', $string);
    $string = str_replace('Ì','&#204;', $string);
    $string = str_replace('Ò','&#210;', $string);
    $string = str_Replace('Ù','&#217;', $string);

    $string = str_replace('â','&#226;', $string);
    $string = str_replace('ê','&#234;', $string);
    $string = str_replace('î','&#238;', $string);
    $string = str_replace('ô','&#244;', $string);
    $string = str_replace('û','&#251;', $string);
    $string = str_replace('Â','&#194;', $string);
    $string = str_replace('Ê','&#202;', $string);
    $string = str_replace('Î','&#206;', $string);
    $string = str_replace('Ô','&#212;', $string);
    $string = str_replace('Û','&#219;', $string);

    $string = str_replace('ä','&#228;', $string);
    $string = str_replace('ë','&#235;', $string);
    $string = str_replace('ï','&#239;', $string);
    $string = str_replace('ö','&#246;', $string);
    $string = str_replace('ü','&#252;', $string);
    $string = str_replace('Ä','&#196;', $string);
    $string = str_replace('Ë','&#203;', $string);
    $string = str_replace('Ï','&#207;', $string);
    $string = str_replace('Ö','&#214;', $string);
    $string = str_replace('Ü','&#220;', $string);
    
    // Spanish characters.
    $string = str_replace('ñ','&#241;', $string);
    $string = str_replace('Ñ','&#209;', $string);
    $string = str_replace('ç','&#231;', $string);
    $string = str_replace('Ç','&#199;', $string);

    // Commas, accents and cedillas.
    $string = str_replace('“','&#8220;', $string);
    $string = str_replace('”','&#8221;', $string);
    $string = str_replace('‘','&#8216;', $string);
    $string = str_replace('’','&#8217;', $string);
    $string = str_replace('¨','&#168;', $string); // Spacing dieresis.
    $string = str_replace('´','&#180;', $string); // Acute accent.
    $string = str_replace('·','&#183;', $string); // Georgian comma.
    $string = str_replace('¸','&#184;', $string); // Spacing cedilla.

    // Punctuation marks
    $string = str_replace('«','&#171;', $string);
    $string = str_replace('»','&#187;', $string);
    $string = str_replace('¿','&#191;', $string);
    $string = str_replace('¡','&#161;', $string);
    $string = str_replace('…','&#8230;', $string);

    // Book marks.
    $string = str_replace('—','&#8212;', $string); // Em dash
    $string = str_replace('•','&#8226;', $string); // Bullet.
    $string = str_replace('†','&#8224;', $string); // Dagger.
    $string = str_replace('‡','&#8225;', $string); // Double dagger.
    $string = str_replace('§','&#167;', $string);  // Section.
    $string = str_replace('¶','&#182;', $string);  // Pilcrow - paragraph sign.
    
    // Currency marks.
    $string = str_replace('€','&#8364;', $string); // Euro.
    $string = str_replace('¢','&#162;', $string); // Cent.
    $string = str_replace('£','&#163;', $string); // Pound.
    $string = str_replace('¤','&#164;', $string); // Currency.
    $string = str_replace('¥','&#165;', $string); // Yen.
    
    // Math marks.
    $string = str_replace('°','&#176;', $string); // Degree sign.
    $string = str_replace('±','&#177;', $string); // Plus or minus.
    $string = str_replace('º','&#186;', $string); // Masculine ordinal indicator.
    $string = str_replace('¹','&#185;', $string); // Spacing cedilla.
    $string = str_replace('²','&#178;', $string); // Superscript two - squared.
    $string = str_replace('³','&#179;', $string); // Superscript three - cubed.
    $string = str_replace('µ','&#181;', $string); // Micro sign.
    $string = str_replace('¼','&#188;', $string); // Fraction one quarter.
    $string = str_replace('½','&#189;', $string); // Fraction one half
    $string = str_replace('¾','&#190;', $string); // Fraction three quarters.
    $string = str_replace('ø','&#248;', $string); // Latin letter o with slash.
    $string = str_replace('Ø','&#216;', $string); // Latin letter O with slash.
    $string = str_replace('÷','&#247;', $string); // Division sign.
    $string = str_replace('×','&#215;', $string); // Multiplication sign.
    $string = str_replace('ƒ','&#402;', $string);// Latin small f with hook = function = florin.
    $string = str_replace('‾','&#8254;', $string);// Overline = spacing overscore.
    $string = str_replace('⁄','&#8260;', $string); // Fraction slash.
    $string = str_replace('℘','&#8472;', $string); // Script capital P = power set = Weierstrass p.
    $string = str_replace('ℑ','&#8465;', $string); // Blackletter capital I = imaginary part.
    $string = str_replace('ℜ','&#8476;', $string); // Blackletter capital R = real part.
    $string = str_replace('←','&#8592;', $string); // Leftwards arrow.
    $string = str_replace('↑','&#8593;', $string); // Upwards arrow.
    $string = str_replace('→','&#8594;', $string); // Rightwards arrow.
    $string = str_replace('↓','&#8595;', $string); // Downwards arrow.
    $string = str_replace('↔','&#8596;', $string); // Left right arrow.
    $string = str_replace('↔','&#8596;', $string); // Left right arrow.
    $string = str_replace('⇐','&#8656;', $string); // Leftwards double arrow.
    $string = str_replace('⇑','&#8657;', $string); // Upwards double arrow.
    $string = str_replace('⇒','&#8658;', $string); // Rightwards double arrow.
    $string = str_replace('⇓','&#8659;', $string); // Downwards double arrow.
    $string = str_replace('⇔','&#8660;', $string); // Left right double arrow.
    $string = str_replace('∀','&#8704;', $string); // For all.
    $string = str_replace('∂','&#8706;', $string); // Partial differential.
    $string = str_replace('∃','&#8707;', $string); // There exists.
    $string = str_replace('∅','&#8709;', $string); // Empty set = diameter.
    $string = str_replace('∇','&#8711;', $string); // Nabla = backward difference.
    $string = str_replace('∈','&#8712;', $string); // Element of.
    $string = str_replace('∉','&#8713;', $string); // Not an element of.
    $string = str_replace('∋','&#8715;', $string); // Contains as member.
    $string = str_replace('∏','&#8719;', $string); // n-ary product = product sign.
    $string = str_replace('∑','&#8721;', $string); // n-ary sumation.
    $string = str_replace('−','&#8722;', $string); // Minus sign.
    $string = str_replace('∗','&#8727;', $string); // Asterisk operator.
    $string = str_replace('√','&#8730;', $string); // Square root = radical sign.
    $string = str_replace('∝','&#8733;', $string); // Proportional to.
    $string = str_replace('∞','&#8734;', $string); // Infinity.
    $string = str_replace('∠','&#8736;', $string); // Angle.
    $string = str_replace('∧','&#8743;', $string); // Logical and.
    $string = str_replace('∨','&#8744;', $string); // Logical or = vee.
    $string = str_replace('∩','&#8745;', $string); // Intersection = cap.
    $string = str_replace('∪','&#8746;', $string); // union = cup.
    $string = str_replace('∫','&#8747;', $string); // Integral.
    $string = str_replace('∴','&#8756;', $string); // Therefore.
    $string = str_replace('∼','&#8764;', $string); // Tilde operator = varies with = similar to.
    $string = str_replace('≅','&#8773;', $string); // Approximately equal to.
    $string = str_replace('≈','&#8776;', $string); // Almost equal to = asymptotic to.
    $string = str_replace('≠','&#8800;', $string); // Not equal to.
    $string = str_replace('≡','&#8801;', $string); // Identical to.
    $string = str_replace('≤','&#8804;', $string); // Less-than or equal to.
    $string = str_replace('≥','&#8805;', $string); // Greater-than or equal to.
    $string = str_replace('⊂','&#8834;', $string); // Subset of.
    $string = str_replace('⊃','&#8835;', $string); // Superset of.
    $string = str_replace('⊄','&#8836;', $string); // Not a subset of.
    $string = str_replace('⊆','&#8838;', $string); // Subset of or equal to.
    $string = str_replace('⊇','&#8839;', $string); // Superset of or equal to.
    $string = str_replace('⊕','&#8853;', $string); // Circled plus = direct sum.
    $string = str_replace('⊗','&#8855;', $string); // Circled times = vector product.
    $string = str_replace('⊥','&#8869;', $string); // Up tack.
    
    // Time marks.
    $string = str_replace('′','&#8242;', $string); // Prime = minutes = feet.
    $string = str_replace('″','&#8243;', $string); // Double prime = seconds = inches.
    
    // Other marks.
    $string = str_replace('©','&#169;', $string); // Copyright.
    $string = str_replace('®','&#174;', $string); // Registered trade mark.
    $string = str_replace('™','&#8482;', $string); // Trade mark.

    // Greek characters.
    $string = str_replace('Α','&#913;', $string); // Alpha.
    $string = str_replace('Β','&#914;', $string); // Beta.
    $string = str_replace('Γ','&#915;', $string); // Gamma.
    $string = str_replace('Δ','&#916;', $string); // Delta.
    $string = str_replace('Ε','&#917;', $string); // Epsilon.
    $string = str_replace('Ζ','&#918;', $string); // Zeta.
    $string = str_replace('Η','&#919;', $string); // Eta.
    $string = str_replace('Θ','&#920;', $string); // Theta.
    $string = str_replace('Ι','&#921;', $string); // Iota.
    $string = str_replace('Κ','&#922;', $string); // Kappa.
    $string = str_replace('Λ','&#923;', $string); // Lambda.
    $string = str_replace('Μ','&#924;', $string); // Mu.
    $string = str_replace('Ν','&#925;', $string); // Nu.
    $string = str_replace('Ξ','&#926;', $string); // Xi.
    $string = str_replace('Ο','&#927;', $string); // Omicron.
    $string = str_replace('Π','&#928;', $string); // Pi.
    $string = str_replace('Ρ','&#929;', $string); // Rho.
    $string = str_replace('Σ','&#931;', $string); // Sigma.
    $string = str_replace('Τ','&#932;', $string); // Tau.
    $string = str_replace('Υ','&#933;', $string); // Upsilon.
    $string = str_replace('Φ','&#934;', $string); // Phi.
    $string = str_replace('Χ','&#935;', $string); // Chi.
    $string = str_replace('Ψ','&#936;', $string); // Psi.
    $string = str_replace('Ω','&#937;', $string); // Omega.
    $string = str_replace('α','&#945;', $string); // Alpha.
    $string = str_replace('β','&#946;', $string); // Beta.
    $string = str_replace('γ','&#947;', $string); // Gamma.
    $string = str_replace('δ','&#948;', $string); // Delta.
    $string = str_replace('ε','&#949;', $string); // Epsilon.
    $string = str_replace('ζ','&#950;', $string); // Zeta.
    $string = str_replace('η','&#951;', $string); // Eta.
    $string = str_replace('θ','&#952;', $string); // Theta.
    $string = str_replace('ι','&#953;', $string); // Iota.
    $string = str_replace('κ','&#954;', $string); // Kappa.
    $string = str_replace('λ','&#955;', $string); // Lambda.
    $string = str_replace('μ','&#956;', $string); // Mu.
    $string = str_replace('ν','&#957;', $string); // Nu.
    $string = str_replace('ξ','&#958;', $string); // Xi.
    $string = str_replace('ο','&#959;', $string); // Omicron.
    $string = str_replace('π','&#960;', $string); // Pi.
    $string = str_replace('ρ','&#961;', $string); // Rho.
    $string = str_replace('ς','&#962;', $string); // Sigmaf.
    $string = str_replace('σ','&#963;', $string); // Sigma.
    $string = str_replace('τ','&#964;', $string); // Tau.
    $string = str_replace('υ','&#965;', $string); // Upsilon.
    $string = str_replace('φ','&#966;', $string); // Phi.
    $string = str_replace('χ','&#967;', $string); // Chi.
    $string = str_replace('ψ','&#968;', $string); // Psi.
    $string = str_replace('ω','&#969;', $string); // Omega.
    
    // Hebrew characters.
    $string = str_replace('֑','&#1425;', $string); // Accent Etnahta.
    $string = str_replace('֒','&#1426;', $string); // Accent Segol.
    $string = str_replace('֓','&#1427;', $string); // Accent Shalshelet.
    $string = str_replace('֔','&#1428;', $string); // Accent Zaqef Qatan.
    $string = str_replace('֔','&#1429;', $string); // Accent Zaqef Gadol.
    $string = str_replace('֖','&#1430;', $string); // Accent Tipeha.
    $string = str_replace('֗','&#1431;', $string); // Accent Revia.
    $string = str_replace('֘','&#1432;', $string); // Accent Zarqa.
    $string = str_replace('֙','&#1433;', $string); // Accent Pashta.
    $string = str_replace('֚','&#1434;', $string); // Accent Yetiv.
    $string = str_replace('֛','&#1435;', $string); // Accent Tevir.
    $string = str_replace('֜','&#1436;', $string); // Accent Geresh.
    $string = str_replace('֝','&#1437;', $string); // Accent Geresh Muqdam.
    $string = str_replace('֞','&#1438;', $string); // Accent Gershayim.
    $string = str_replace('֟','&#1439;', $string); // Accent Qarney Para.
    $string = str_replace('֠','&#1440;', $string); // Accent Telisha Gedola.
    $string = str_replace('֡','&#1441;', $string); // Accent Pazer.
    $string = str_replace('֢','&#1442;', $string); // Accent atnah Hafukh.
    $string = str_replace('֣','&#1443;', $string); // Accent Munah.
    $string = str_replace('֤','&#1444;', $string); // Accent Mahapakh.
    $string = str_replace('֥','&#1445;', $string); // Accent Merkha.
    $string = str_replace('֦','&#1446;', $string); // Accent Kefula.
    $string = str_replace('֧','&#1447;', $string); // Accent Darga.
    $string = str_replace('֨','&#1448;', $string); // Accent Qadma.
    $string = str_replace('֩','&#1449;', $string); // Accent Telisha Qetana.
    $string = str_replace('֪','&#1450;', $string); // Accent Yerah Ben Yomo.
    $string = str_replace('֫','&#1451;', $string); // Accent Ole.
    $string = str_replace('֭','&#1452;', $string); // Accent Iluy.
    $string = str_replace('֭','&#1453;', $string); // Accent Dehi.
    $string = str_replace('֮','&#1454;', $string); // Accent Zinor.
    $string = str_replace('֯','&#1455;', $string); // Mark Masora circle.
    $string = str_replace('ְ','&#1456;', $string); // Point Sheva.
    $string = str_replace('ֱ','&#1457;', $string); // Hataf segol.
    $string = str_replace('ֲ','&#1458;', $string); // Hataf patah.
    $string = str_replace('ֳ','&#1459;', $string); // Hataf qamats.
    $string = str_replace('ִ','&#1460;', $string); // Point Hiriq.
    $string = str_replace('ֵ','&#1461;', $string); // Point Tsere.
    $string = str_replace('ֶ','&#1462;', $string); // Point Segol.
    $string = str_replace('ַ','&#1463;', $string); // Point Patah.
    $string = str_replace('ָ','&#1464;', $string); // Point Qamats.
    $string = str_replace('ֹ','&#1465;', $string); // Point Holam.
    $string = str_replace('ֺ','&#1466;', $string); // Point Holam Haser for Vav.
    $string = str_replace('ֻ','&#1467;', $string); // Point Qubuts.
    $string = str_replace('ּ','&#1468;', $string); // Point Dagesh or Mapiq.
    $string = str_replace('ֽ','&#1469;', $string); // Point Meteg.
    $string = str_replace('־	','&#1470;', $string); // Punctuaction Maqaf.
    $string = str_replace('ֿ','&#1471;', $string); // Point Rafe.
    $string = str_replace('׀','&#1472;', $string); // Punctuaction Paseq.
    $string = str_replace('ׁ','&#1473;', $string); // Point Shin dot.
    $string = str_replace('ׂ','&#1474;', $string); // Point Sin dot.
    $string = str_replace('׃	','&#1475;', $string); // Punctuation Sof pasuq.
    $string = str_replace('ׄ','&#1476;', $string); // Mark upper dot.
    $string = str_replace('ׅ','&#1477;', $string); // Mark lower dot.
    $string = str_replace('׆	','&#1478;', $string); // Punctuation Nun hafukha.
    $string = str_replace('ׇ','&#1479;', $string); // Point Qamats Qatan.
    $string = str_replace('א','&#1488;', $string); // Letter Alef.
    $string = str_replace('ב','&#1489;', $string); // Letter Bet.
    $string = str_replace('ג','&#1490;', $string); // Letter Gimel.
    $string = str_replace('ד','&#1491;', $string); // Letter Dalet.
    $string = str_replace('ה','&#1492;', $string); // Letter He.
    $string = str_replace('ו','&#1493;', $string); // Letter Vav.
    $string = str_replace('ז','&#1494;', $string); // Letter Zayin.
    $string = str_replace('ח','&#1495;', $string); // Letter Het.
    $string = str_replace('ט','&#1496;', $string); // Letter Tet.
    $string = str_replace('י','&#1497;', $string); // Letter Yod.
    $string = str_replace('כ','&#1499;', $string); // Letter Kaf.
    $string = str_replace('ל	','&#1500;', $string); // Letter Lamed.
    $string = str_replace('ם','&#1501;', $string); // Letter final Mem.
    $string = str_replace('מ	','&#1502;', $string); // Letter Mem.
    $string = str_replace('ן','&#1503;', $string); // Letter final Nun.
    $string = str_replace('נ','&#1504;', $string); // Letter Nun.
    $string = str_replace('ס','&#1505;', $string); // Letter Samekh.
    $string = str_replace('ע','&#1506;', $string); // Letter Ayin.
    $string = str_replace('ף','&#1507;', $string); // Letter final Pe.
    $string = str_replace('פ','&#1508;', $string); // Letter Pe.
    $string = str_replace('ץ','&#1509;', $string); // Letter final Tsadi.
    $string = str_replace('צ','&#1510;', $string); // Letter Tsadi.
    $string = str_replace('ק','&#1511;', $string); // Letter Qof.
    $string = str_replace('ר','&#1512;', $string); // Letter Resh.
    $string = str_replace('ש','&#1513;', $string); // Letter Shin.
    $string = str_replace('ת','&#1514;', $string); // Letter Tav.
    $string = str_replace('װ','&#1520;', $string); // Ligature Yiddish double Vav.
    $string = str_replace('ױ	','&#1521;', $string); // Ligature Yiddish Vav Yod.
    $string = str_replace('ײ	','&#1522;', $string); // Ligature yiddish double Yod.
    $string = str_replace('׳','&#1523;', $string); // Punctuation Geresh.
    $string = str_replace('״','&#1524;', $string); // Punctuation Gershayim.

    // Cyrillic (Russia)
    // Uppercase.
    $string = str_replace('А','&#1040;', $string); // A.
    $string = str_replace('Б','&#1041;', $string); // BE.
    $string = str_replace('В','&#1042;', $string); // VE.
    $string = str_replace('Г','&#1043;', $string); // GHE.
    $string = str_replace('Д','&#1044;', $string); // DE.
    $string = str_replace('Е','&#1045;', $string); // IE.
    $string = str_replace('Ж','&#1046;', $string); // ZHE.
    $string = str_replace('З','&#1047;', $string); // ZE.
    $string = str_replace('И','&#1048;', $string); // I.
    $string = str_replace('Й','&#1049;', $string); // SHORT.
    $string = str_replace('К','&#1050;', $string); // KA.
    $string = str_replace('Л','&#1051;', $string); // EL.
    $string = str_replace('М','&#1052;', $string); // EM.
    $string = str_replace('Н','&#1053;', $string); // EN.
    $string = str_replace('О','&#1054;', $string); // O.
    $string = str_replace('П','&#1055;', $string); // PE.
    $string = str_replace('Р','&#1056;', $string); // ER.
    $string = str_replace('С','&#1057;', $string); // ES.
    $string = str_replace('Т','&#1058;', $string); // TE.
    $string = str_replace('У','&#1059;', $string); // U.
    $string = str_replace('Ф','&#1060;', $string); // EF.
    $string = str_replace('Х','&#1061;', $string); // HA.
    $string = str_replace('Ц','&#1062;', $string); // TSE.
    $string = str_replace('Ч','&#1063;', $string); // CHE.
    $string = str_replace('Ш','&#1064;', $string); // SHA.
    $string = str_replace('Щ','&#1065;', $string); // SHCHA.
    $string = str_replace('Ъ','&#1066;', $string); // SIGN.
    $string = str_replace('Ы','&#1067;', $string); // YERU.
    $string = str_replace('Ь','&#1068;', $string); // SOFT.
    $string = str_replace('Э','&#1069;', $string); // E.
    $string = str_replace('Ю','&#1070;', $string); // YU.
    $string = str_replace('Я','&#1071;', $string); // YA.

    // Lowercase.
    $string = str_replace('а','&#1072;', $string); // A.
    $string = str_replace('б','&#1073;', $string); // BE.
    $string = str_replace('в','&#1074;', $string); // VE.
    $string = str_replace('г','&#1075;', $string); // GHE.
    $string = str_replace('д','&#1076;', $string); // DE.
    $string = str_replace('е','&#1077;', $string); // IE.
    $string = str_replace('ж','&#1078;', $string); // ZHE.
    $string = str_replace('з','&#1079;', $string); // ZE.
    $string = str_replace('и','&#1080;', $string); // I.
    $string = str_replace('й','&#1081;', $string); // SHORT I.
    $string = str_replace('к','&#1082;', $string); // KA.
    $string = str_replace('л','&#1083;', $string); // EL.
    $string = str_replace('м','&#1084;', $string); // EM.
    $string = str_replace('н','&#1085;', $string); // EN.
    $string = str_replace('о','&#1086;', $string); // O.
    $string = str_replace('п','&#1087;', $string); // PE.
    $string = str_replace('р','&#1088;', $string); // ER.
    $string = str_replace('с','&#1089;', $string); // ES.
    $string = str_replace('т','&#1090;', $string); // Te.
    $string = str_replace('у','&#1091;', $string); // U.
    $string = str_replace('ф','&#1092;', $string); // EF.
    $string = str_replace('х','&#1093;', $string); // HA.
    $string = str_replace('ц','&#1094;', $string); // TSE.
    $string = str_replace('ч','&#1095;', $string); // CHE.
    $string = str_replace('ш','&#1096;', $string); // SHA.
    $string = str_replace('щ','&#1097;', $string); // SHCA.
    $string = str_replace('ъ','&#1098;', $string); // Hard sign.
    $string = str_replace('ы','&#1099;', $string); // Yeru.
    $string = str_replace('ь','&#1100;', $string); // Soft sign.
    $string = str_replace('э','&#1101;', $string); // E.
    $string = str_replace('ю','&#1102;', $string); // Yu.
    $string = str_replace('я','&#1103;', $string); // Ya.

    // Cyrillic (Ukrania, Serbia, Bielorrusia)
    // Uppercase.
    $string = str_replace('Ѐ','&#1024;', $string); // IE WITH GRAVE.
    $string = str_replace('Ё','&#1025;', $string); // IO.
    $string = str_replace('Ђ','&#1026;', $string); // DJE.
    $string = str_replace('Ѓ','&#1027;', $string); // GJE.
    $string = str_replace('Є','&#1028;', $string); // IE.
    $string = str_replace('Ѕ','&#1029;', $string); // DZE.
    $string = str_replace('І','&#1030;', $string); // I.
    $string = str_replace('Ї','&#1031;', $string); // YI.
    $string = str_replace('Ј','&#1032;', $string); // JE.
    $string = str_replace('Љ','&#1033;', $string); // LJE.
    $string = str_replace('Њ','&#1034;', $string); // NJE.
    $string = str_replace('Ћ','&#1035;', $string); // TSHE.
    $string = str_replace('Ќ','&#1036;', $string); // KJE.
    $string = str_replace('Ѝ','&#1037;', $string); // I WITH GRAVE.
    $string = str_replace('Ў','&#1038;', $string); // U.
    $string = str_replace('Џ','&#1039;', $string); // DZHE.

    // Lowercase.    
    $string = str_replace('ѐ','&#1104;', $string); // IE WITH GRAVE.
    $string = str_replace('ё','&#1105;', $string); // IO.
    $string = str_replace('ђ','&#1106;', $string); // DJE.
    $string = str_replace('ѓ','&#1107;', $string); // GJE.
    $string = str_replace('є','&#1108;', $string); // UKRAINIAN IE.
    $string = str_replace('ѕ','&#1109;', $string); // DZE.
    $string = str_replace('і','&#1110;', $string); // BYELORUSSIAN-UKRAINIAN I.
    $string = str_replace('ї','&#1111;', $string); // YI.
    $string = str_replace('ј','&#1112;', $string); // JE.
    $string = str_replace('љ','&#1113;', $string); // LJE.
    $string = str_replace('њ','&#1114;', $string); // NJE.
    $string = str_replace('ћ','&#1115;', $string); // TSHE.
    $string = str_replace('ќ','&#1116;', $string); // KJE.
    $string = str_replace('ѝ','&#1117;', $string); // I WITH GRAVE.
    $string = str_replace('ў','&#1118;', $string); // SHORT U.
    $string = str_replace('џ','&#1119;', $string); // DZHE.

    // Armenian marks
    // Uppercase.
    $string = str_replace('Ա','&#1329;', $string); // Ayb.
    $string = str_replace('Բ','&#1330;', $string); // Ben.
    $string = str_replace('Գ','&#1331;', $string); // Gim.
    $string = str_replace('Դ','&#1332;', $string); // Da.
    $string = str_replace('Ե','&#1333;', $string); // Ech.
    $string = str_replace('Զ','&#1334;', $string); // Za.
    $string = str_replace('Է','&#1335;', $string); // Eh.
    $string = str_replace('Ը','&#1336;', $string); // Et.
    $string = str_replace('Թ','&#1337;', $string); // To.
    $string = str_replace('Ժ','&#1338;', $string); // Zhe.
    $string = str_replace('Ի','&#1339;', $string); // Ini.
    $string = str_replace('Լ','&#1340;', $string); // Liwn.
    $string = str_replace('Խ','&#1341;', $string); // Xeh.
    $string = str_replace('Ծ','&#1342;', $string); // Ca.
    $string = str_replace('Կ','&#1343;', $string); // Ken.
    $string = str_replace('Հ','&#1344;', $string); // Ho.
    $string = str_replace('Ձ','&#1345;', $string); // Ja.
    $string = str_replace('Ղ','&#1346;', $string); // Ghad.
    $string = str_replace('Ճ','&#1347;', $string); // Cheh.
    $string = str_replace('Մ','&#1348;', $string); // Men.
    $string = str_replace('Յ','&#1349;', $string); // Yi.
    $string = str_replace('Ն','&#1350;', $string); // Now.
    $string = str_replace('Շ','&#1351;', $string); // Sha.
    $string = str_replace('Ո','&#1352;', $string); // Vo.
    $string = str_replace('Չ','&#1353;', $string); // Cha.
    $string = str_replace('Պ','&#1354;', $string); // Peh.
    $string = str_replace('Ջ','&#1355;', $string); // Jheh.
    $string = str_replace('Ռ','&#1356;', $string); // Ra.
    $string = str_replace('Ս','&#1357;', $string); // Seh.
    $string = str_replace('Վ','&#1358;', $string); // Vew.
    $string = str_replace('Տ','&#1359;', $string); // Tiwn.
    $string = str_replace('Ր','&#1360;', $string); // Reh.
    $string = str_replace('Ց','&#1361;', $string); // Co.
    $string = str_replace('Ւ','&#1362;', $string); // Yiwn.
    $string = str_replace('Փ','&#1363;', $string); // Piwr.
    $string = str_replace('Ք','&#1364;', $string); // Keh.
    $string = str_replace('Օ','&#1365;', $string); // Oh.
    $string = str_replace('Ֆ','&#1366;', $string); // Feh.
    
    // Lowercase.
    $string = str_replace('ա','&#1377;', $string); // Ayb.
    $string = str_replace('բ','&#1378;', $string); // Ben.
    $string = str_replace('գ','&#1379;', $string); // Gim.
    $string = str_replace('դ','&#1380;', $string); // Da.
    $string = str_replace('ե','&#1381;', $string); // Ech.
    $string = str_replace('զ','&#1382;', $string); // Za.
    $string = str_replace('է','&#1383;', $string); // Eh.
    $string = str_replace('ը','&#1384;', $string); // Et.
    $string = str_replace('թ','&#1385;', $string); // To.
    $string = str_replace('ժ','&#1386;', $string); // Zhe.
    $string = str_replace('ի','&#1387;', $string); // Ini.
    $string = str_replace('լ','&#1388;', $string); // Liwn.
    $string = str_replace('խ','&#1389;', $string); // Xeh.
    $string = str_replace('ծ','&#1390;', $string); // Ca.
    $string = str_replace('կ','&#1391;', $string); // Ken.
    $string = str_replace('հ','&#1392;', $string); // Ho.
    $string = str_replace('ձ','&#1393;', $string); // Ja.
    $string = str_replace('ղ','&#1394;', $string); // Ghad.
    $string = str_replace('ճ','&#1395;', $string); // Cheh.
    $string = str_replace('մ','&#1396;', $string); // Men.
    $string = str_replace('յ','&#1397;', $string); // Yi.
    $string = str_replace('ն','&#1398;', $string); // Now.
    $string = str_replace('շ','&#1399;', $string); // Sha.
    $string = str_replace('ո','&#1400;', $string); // Vo.
    $string = str_replace('չ','&#1401;', $string); // Cha.
    $string = str_replace('պ','&#1402;', $string); // Peh.
    $string = str_replace('ջ','&#1403;', $string); // Jheh.
    $string = str_replace('ռ','&#1404;', $string); // Ra.
    $string = str_replace('ս','&#1405;', $string); // Seh.
    $string = str_replace('վ','&#1406;', $string); // Vew.
    $string = str_replace('տ','&#1407;', $string); // Tiwn.
    $string = str_replace('ր','&#1408;', $string); // Reh.
    $string = str_replace('ց','&#1409;', $string); // Co.
    $string = str_replace('ւ','&#1410;', $string); // Yiwn.
    $string = str_replace('փ','&#1411;', $string); // Piwr.
    $string = str_replace('ք','&#1412;', $string); // Keh.
    $string = str_replace('օ','&#1413;', $string); // Oh.
    $string = str_replace('ֆ','&#1414;', $string); // Feh.
    
    // Armenia
    $string = str_replace('ɋ','&#587;', $string); // Ligature ech Yiwn.
    $string = str_replace('՚՚՚','&#1370;', $string); // Apostrophe.
    $string = str_replace('՛','&#1371;', $string); // Emphasis mark.
    $string = str_replace('՜','&#1372;', $string); // Exclamation mark.
    $string = str_replace('՝','&#1373;', $string); // Comma.
    $string = str_replace('՞','&#1374;', $string); // Question mark.
    $string = str_replace('՟','&#1375;', $string); // Abbreviation mark.
    $string = str_replace('։','&#1417;', $string); // Full stop.
    $string = str_replace('֊','&#1418;', $string); // Hyphen.
    
    // Hiragana (Japan)
    $string = str_replace('ぁ','&#12353;', $string); // A lowercase.
    $string = str_replace('あ','&#12354;', $string); // A uppercase.
    $string = str_replace('ぃ','&#12355;', $string); // I lowercase.
    $string = str_replace('い','&#12356;', $string); // I uppercase.
    $string = str_replace('ぅ','&#12357;', $string); // U lowercase.
    $string = str_replace('う','&#12358;', $string); // U uppercase.
    $string = str_replace('ぇ','&#12359;', $string); // E lowercase.
    $string = str_replace('え','&#12360;', $string); // E uppercase.
    $string = str_replace('ぉ','&#12361;', $string); // O lowercase.
    $string = str_replace('お','&#12362;', $string); // O uppercase.
    $string = str_replace('か','&#12363;', $string); // Ka.
    $string = str_replace('が','&#12364;', $string); // Ga.
    $string = str_replace('き','&#12365;', $string); // Ki.
    $string = str_replace('ぎ','&#12366;', $string); // Gi.
    $string = str_replace('く','&#12367;', $string); // Ku.
    $string = str_replace('ぐ','&#12368;', $string); // Gu.
    $string = str_replace('け','&#12369;', $string); // Ke.
    $string = str_replace('げ','&#12370;', $string); // Ge.
    $string = str_replace('こ','&#12371;', $string); // Ko.
    $string = str_replace('ご','&#12372;', $string); // Go.
    $string = str_replace('さ','&#12373;', $string); // Sa.
    $string = str_replace('ざ','&#12374;', $string); // Za.
    $string = str_replace('し','&#12375;', $string); // Si.
    $string = str_replace('じ','&#12376;', $string); // Zi.
    $string = str_replace('す','&#12377;', $string); // Su.
    $string = str_replace('ず','&#12378;', $string); // Zu.
    $string = str_replace('せ','&#12379;', $string); // Se.
    $string = str_replace('ぜ','&#12380;', $string); // Ze.
    $string = str_replace('そ','&#12381;', $string); // So.
    $string = str_replace('ぞ','&#12382;', $string); // Zo.
    $string = str_replace('た','&#12383;', $string); // Ta.
    $string = str_replace('だ','&#12384;', $string); // Da.
    $string = str_replace('ち','&#12385;', $string); // Ti.
    $string = str_replace('ぢ','&#12386;', $string); // Di.
    $string = str_replace('っ','&#12387;', $string); // Tu lowercase.
    $string = str_replace('つ','&#12388;', $string); // Tu uppercase.
    $string = str_replace('づ','&#12389;', $string); // Du.
    $string = str_replace('て','&#12390;', $string); // Te.
    $string = str_replace('で','&#12391;', $string); // De.
    $string = str_replace('と','&#12392;', $string); // To.
    $string = str_replace('ど','&#12393;', $string); // Do.
    $string = str_replace('な','&#12394;', $string); // Na.
    $string = str_replace('に','&#12395;', $string); // Ni.
    $string = str_replace('ぬ','&#12396;', $string); // Nu.
    $string = str_replace('ね','&#12397;', $string); // Ne.
    $string = str_replace('の','&#12398;', $string); // No.
    $string = str_replace('は','&#12399;', $string); // Ha.
    $string = str_replace('ば','&#12400;', $string); // Ba.
    $string = str_replace('ぱ','&#12401;', $string); // Pa.
    $string = str_replace('ひ','&#12402;', $string); // Hi.
    $string = str_replace('び','&#12403;', $string); // Bi.
    $string = str_replace('ぴ','&#12404;', $string); // Pi.
    $string = str_replace('ふ','&#12405;', $string); // Hu.
    $string = str_replace('ぶ','&#12406;', $string); // Bu.
    $string = str_replace('ぷ','&#12407;', $string); // Pu.
    $string = str_replace('へ','&#12408;', $string); // He.
    $string = str_replace('べ','&#12409;', $string); // Be.
    $string = str_replace('ぺ','&#12410;', $string); // Pe.
    $string = str_replace('ほ','&#12411;', $string); // Ho.
    $string = str_replace('ぼ','&#12412;', $string); // Bo.
    $string = str_replace('ぽ','&#12413;', $string); // Po.
    $string = str_replace('ま','&#12414;', $string); // Ma.
    $string = str_replace('み','&#12415;', $string); // Mi.
    $string = str_replace('む','&#12416;', $string); // Mu.
    $string = str_replace('め','&#12417;', $string); // Me.
    $string = str_replace('も','&#12418;', $string); // Mo.
    $string = str_replace('ゃ','&#12419;', $string); // Ya lowercase.
    $string = str_replace('や','&#12420;', $string); // Ya uppercase.
    $string = str_replace('ゅ','&#12421;', $string); // Yu lowercase.
    $string = str_replace('ゆ','&#12422;', $string); // Yu uppercase.
    $string = str_replace('ょ','&#12423;', $string); // Yo lowercase.
    $string = str_replace('よ','&#12424;', $string); // Yo uppercase.
    $string = str_replace('ら','&#12425;', $string); // Ra.
    $string = str_replace('り','&#12426;', $string); // Ri.
    $string = str_replace('る','&#12427;', $string); // Ru.
    $string = str_replace('れ','&#12428;', $string); // Re.
    $string = str_replace('ろ','&#12429;', $string); // Ro.
    $string = str_replace('ゎ','&#12430;', $string); // Wa lowercase.
    $string = str_replace('わ','&#12431;', $string); // Wa uppercase.
    $string = str_replace('ゐ','&#12432;', $string); // Wi.
    $string = str_replace('ゑ','&#12433;', $string); // We.
    $string = str_replace('を','&#12434;', $string); // Wo.
    $string = str_replace('ん','&#12435;', $string); // N.
    $string = str_replace('ゔ','&#12436;', $string); // Vu.
    $string = str_replace('ゕ','&#12437;', $string); // Ka lowercase.
    $string = str_replace('ゖ','&#12438;', $string); // Ke lowercase.
    $string = str_replace('゛','&#12443;', $string); // Voiced sound.
    $string = str_replace('゜','&#12444;', $string); // Semi-voiced sound.
    $string = str_replace('ゝ','&#12445;', $string); // Iteration.
    $string = str_replace('ゞ','&#12446;', $string); // Voiced iteration.
    $string = str_replace('ゟ','&#12447;', $string); // Digraph Yori.
    
    // Katakana (Japan)
    $string = str_replace('゠','&#12448;', $string); // Double hyphen. 
    $string = str_replace('ァ','&#12449;', $string); // A lowercase.
    $string = str_replace('ア','&#12450;', $string); // A uppercase.
    $string = str_replace('ィ','&#12451;', $string); // I lowercase.
    $string = str_replace('イ','&#12452;', $string); // I uppercase.
    $string = str_replace('ゥ','&#12453;', $string); // I lowercase.
    $string = str_replace('ウ','&#12454;', $string); // U uppercase.
    $string = str_replace('ェ','&#12455;', $string); // E lowercase.
    $string = str_replace('エ','&#12456;', $string); // E uppercase.
    $string = str_replace('ォ','&#12457;', $string); // O lowercase.
    $string = str_replace('オ','&#12458;', $string); // O uppercase.
    $string = str_replace('カ','&#12459;', $string); // Ka.
    $string = str_replace('ガ','&#12460;', $string); // Ga.
    $string = str_replace('キ','&#12461;', $string); // Ki.
    $string = str_replace('ギ','&#12462;', $string); // Gi.
    $string = str_replace('ク','&#12463;', $string); // Ku.
    $string = str_replace('グ','&#12464;', $string); // Gu.
    $string = str_replace('ケ','&#12465;', $string); // Ke.
    $string = str_replace('ゲ','&#12466;', $string); // Ge.
    $string = str_replace('コ','&#12467;', $string); // Ko.
    $string = str_replace('ゴ','&#12468;', $string); // Go.
    $string = str_replace('サ','&#12469;', $string); // Sa.
    $string = str_replace('ザ','&#12470;', $string); // Za.
    $string = str_replace('シ','&#12471;', $string); // Si.
    $string = str_replace('ジ','&#12472;', $string); // Zi.
    $string = str_replace('ス','&#12473;', $string); // Su.
    $string = str_replace('ズ','&#12474;', $string); // Zu.
    $string = str_replace('セ','&#12475;', $string); // Se.
    $string = str_replace('ゼ','&#12476;', $string); // Ze.
    $string = str_replace('ソ','&#12477;', $string); // So.
    $string = str_replace('ゾ','&#12478;', $string); // Zo.
    $string = str_replace('タ','&#12479;', $string); // Ta.
    $string = str_replace('ダ','&#12480;', $string); // Da.
    $string = str_replace('チ','&#12481;', $string); // Ti.
    $string = str_replace('ヂ','&#12482;', $string); // Di.
    $string = str_replace('ッ','&#12483;', $string); // Tu lowercase.
    $string = str_replace('ツ','&#12484;', $string); // Tu uppercase.
    $string = str_replace('ヅ','&#12485;', $string); // Du.
    $string = str_replace('テ','&#12486;', $string); // Te.
    $string = str_replace('デ','&#12487;', $string); // De.
    $string = str_replace('ト','&#12488;', $string); // To.
    $string = str_replace('ド','&#12489;', $string); // Do.
    $string = str_replace('ナ','&#12490;', $string); // Na.
    $string = str_replace('ニ','&#12491;', $string); // Ni.
    $string = str_replace('ヌ','&#12492;', $string); // Nu.
    $string = str_replace('ネ','&#12493;', $string); // Ne.
    $string = str_replace('ノ','&#12494;', $string); // No.
    $string = str_replace('ハ','&#12495;', $string); // Ha.
    $string = str_replace('バ','&#12496;', $string); // Ba.
    $string = str_replace('パ','&#12497;', $string); // Pa.
    $string = str_replace('ヒ','&#12498;', $string); // Hi.
    $string = str_replace('ビ','&#12499;', $string); // Bi.
    $string = str_replace('ピ','&#12500;', $string); // Pi.
    $string = str_replace('フ','&#12501;', $string); // Hu.
    $string = str_replace('ブ','&#12502;', $string); // Bu.
    $string = str_replace('プ','&#12503;', $string); // Pu.
    $string = str_replace('ヘ','&#12504;', $string); // He.
    $string = str_replace('ベ','&#12505;', $string); // Be.
    $string = str_replace('ペ','&#12506;', $string); // Pe.
    $string = str_replace('ホ','&#12507;', $string); // Ho.
    $string = str_replace('ボ','&#12508;', $string); // Bo.
    $string = str_replace('ポ','&#12509;', $string); // Po.
    $string = str_replace('マ','&#12510;', $string); // Ma.
    $string = str_replace('ミ','&#12511;', $string); // Mi.
    $string = str_replace('ム','&#12512;', $string); // Mu.
    $string = str_replace('メ','&#12513;', $string); // Me.
    $string = str_replace('モ','&#12514;', $string); // Mo.
    $string = str_replace('ャ','&#12515;', $string); // Ya lowercase.
    $string = str_replace('ヤ','&#12516;', $string); // Ya uppercase.
    $string = str_replace('ュ','&#12517;', $string); // Yu lowercase.
    $string = str_replace('ユ','&#12518;', $string); // Yu uppercase.
    $string = str_replace('ョ','&#12519;', $string); // Yo lowercase.
    $string = str_replace('ヨ','&#12520;', $string); // Yo uppercase.
    $string = str_replace('ラ','&#12521;', $string); // Ra.
    $string = str_replace('リ','&#12522;', $string); // Ri.
    $string = str_replace('ル','&#12523;', $string); // Ru.
    $string = str_replace('レ','&#12524;', $string); // Re.
    $string = str_replace('ロ','&#12525;', $string); // Ro.
    $string = str_replace('ヮ','&#12526;', $string); // Wa lowercase.
    $string = str_replace('ワ','&#12527;', $string); // Wa uppercase.
    $string = str_replace('ヰ','&#12528;', $string); // Wi.
    $string = str_replace('ヱ','&#12529;', $string); // We.
    $string = str_replace('ヲ','&#12530;', $string); // Wo.
    $string = str_replace('ン','&#12531;', $string); // N.
    $string = str_replace('ヴ','&#12532;', $string); // Vu.
    $string = str_replace('ヵ','&#12533;', $string); // Ka lowercase.
    $string = str_replace('ヶ','&#12534;', $string); // Ke lowercase.
    $string = str_replace('ヷ','&#12535;', $string); // Va.
    $string = str_replace('ヸ','&#12536;', $string); // Vi.
    $string = str_replace('ヹ','&#12537;', $string); // Ve.
    $string = str_replace('ヺ','&#12538;', $string); // Vo.
    $string = str_replace('・','&#12539;', $string); // Middle dot.
    $string = str_replace('ー','&#12540;', $string); // Prolonged sound.
    $string = str_replace('ヽ','&#12541;', $string); // Iteration.
    $string = str_replace('ヾ','&#12542;', $string); // Voice iteration.
    $string = str_replace('ヿ','&#12543;', $string); // Digraph KoTo.
    */
   
    // The PHP server replaces the html entities of this special characters 
    // with its symbols. To avoid this, we replace this symbols with words.
    $string = str_replace('©', ' Copyright ', $string);
    $string = str_replace('®', ' Registered ', $string);
    $string = str_replace('™', ' Trade mark ', $string);
    // Some html or epub documents, may contain non-breaking space characters.
    // This character is similar to space character, but although they seem
    // identical, the first " " character is different to the second " " 
    // character, in the next line. We must replace this special spaces with
    // space characters.
    $string = str_replace(" ", " ", $string);
    $string = str_replace($search, $replace, $string);
        
    return $string;
}

function fm_entity_decode_ex($string) {
    global $search;
    global $replace;
    
    $string = str_replace($replace, $search, $string);
    return $string;
}