DIGUI FOR MOODLE
================ 

We've coded a new module for Moodle 3.1. We've upload the necessary files to https://github.com/fermitanio/moodle-activity_digui. The compressed file 
digui.zip, contains the complete structure of the module, including readme 
files. As you may know, the file must be uncompressed into the mod directory 
of your Moodle distribution to be tested.

To see videos about Digui, go to https://www.youtube.com/watch?v=3nSKcknzSJQ. 

Please, contact with us by email for any question (fermitanio@hotmail.com). 

Thank you.


SYSTEM REQUERIMENTS
===================

* Digui requires Moodle 2.4.11 or higher, with MySQL database installed. 
  Digui has not been tested with PostgreSQL databases.

* Digui supports the following browsers: Chrome, Firefox, Opera and Safari.
  Explorer and Edge not tested.

* Digui requires JavaScript enabled in your browser.
  
  
QUICK INSTALL
=============

   Here is a basic outline of the installation process, which normally takes 
   only a few minutes:

   1º The module is zipped in "Digui.zip" compressed file. Unzip the file and 
   move the entire structure into the "moodle/mod/" directory.

   2º Log in Moodle as administrator.

   3º Follow the instructions.
   
   To uninstall the module, please follow next steps:

   1º Log in Moodle as administrator.

   2º Go to Settings > Site Administration > Plugins > Activities, and click the 
   Manage Activities link. 

   3º Find the module in the list of modules, and use the link in the delete 
   column.
   
   
WHAT DOES THIS ACTIVITY DO
==========================

   Many times, the teacher provides to students text pages, and the student has 
   to read it. However, most of the time students won't want to read the entire 
   document, but just sections of it. Digui comes with a digital highlighter 
   pen that lets you mark up and colorize the text in your document. With digui 
   activity, you can highlight your texts, and save the results on a file. Use 
   the Highlight tool to mark and find important text on a webpage, just like 
   you'd on paper. You may decide to emphasise:

    * a sentence or word that sums up an important idea,
    * quotations,
    * statistics,
    * specialised terms,
    * important or useful data,
    * examples or links to other ideas.
	   
   Using highlighters to mark key texts is a great idea. Highlighting is 
   convenient when a summary is important to understanding the text. 
   Highlighting helps you when to think critically 
   and formulate your own response to the text. Also, highlighting can improve 
   retention of selected text material, because it draws attention to 
   individual facts. 
   
   
DIGUI DESCRIPTION
=================

Digui is a tool for highlighting text, making annotations and exporting the 
results to a file. 


BENEFITS OF USING DIGUI
=======================

Digui is a tool for learning and group collaboration. Although Digui is an 
activity for Moodle, teachers can use Digui to plan more than one activity,
according to the characteristics of their subject. For example, with Digui
teachers could design this activities:

- Their students must answer questions, highlighting the answers from a text.
This activity is suitable for any subject.
- Assess reading comprehension of the students, highlighting key words or key
phrases. This activity is suitable for any subject.
- Do summaries and document synthesis. This activity is suitable for any 
subject.
- Highlight hyperlinks and other hypertext elements. This activity is suitable 
for a science computer subject.
- Highlight nouns and other grammatical elements. This activity is suitable 
for a literature subject.
- Highlight data types, literals and other programming elements. This activity 
is suitable for a programming subject. 
- Highlight queries, commands and other programming instructions. This activity 
is suitable for a database subject. 
- Etc.


BASIC FUNCTIONS
===============

- More than one user can highlights the same block text, and this block will 
be painted in gray color.
- Supports import from epub, html and txt files, and export to txt files. 
- Supports up to 11 users for each digui, in other words, up to 11 users can
edit the same digui.
- If a page is being edited by an user, the following user who wants to edit 
the same page, must wait for the first user leaves the page, or that the first
user be inactive for 5 minutes.
- Supports the Moodle modes individual or collaborative.
- Supports the Moodle groups without groups, visible groups or separate groups.


INTERFACE OVERVIEW
==================

The interface of Digui is organized in four tabs: View, Edit, Grading, and 
Export. View and Edit tabs show both one text page each time, and the user can 
navigate through the text pages, using the links below. The Grading tab is for 
users whose role is teacher, it will not be shown if the current user'role is 
student. The teachers can use the Grading tab for grading their students. And 
finally, any user can save the highlighted text in a text file, through the 
Export tab.


USER MANUAL
===========

Before you can highlight, you must upload the file that contain the text. This 
file is unique, and must be a text plain file (.txt), a web page (.html), or a 
epub file (.epub). After this process, Digui extracts the text and ignore images 
and other elements of the file. 

From this moment, you can select and highlight the text. To do this, you must 
open the Edit tab and begin to drag the mouse over the text. 

Same text can be highlighted multiple times by different users. In this case, 
the text color appears gray, the darker more users have highlighted the text.

In future versions, you will be able of make your own notes with Digui. Digui 
gives you margin space available for writing. 

To view the results, you can open the View tab. If your students are 
separated in groups, you can configure Digui to reflect this organization. If 
Digui is configured in separate groups, the View tab shows only the results of 
the users are in the current user's group. If Digui is configured in visible 
groups, the View tab shows the results of the user groups you choose.

To export the selected text to a file, you use the Export section. 

Only teachers can evaluate the rest of users, through the Grade section. 
However, after a teacher have evaluated all users, the students can view their 
grades through the Grade section.


HOW DIGUI WORKS
===============

The first step to use Digui, is logging in Moodle as administrator, and create 
a digui activity. "Digui name" and "Title book" fields are mandatory. In 
addition, the user can set the digui mode as a individual or collaborative. If 
the digui mode is individual, each page of the digui can be highlighted by one 
user only. If the digui mode is collaborative, each page of the digui can be 
highlighted by several users.

After the user creates a digui activity, the second step is upload the text 
source to be highlighted. The text sources can be epub files, html files or txt 
files. The user can upload one file only. During the uploading process, Digui 
converts the source file in formatted text (if the file format is epub or html),
and splits the file in several text pages, up 4000 characters approximately each
page. Images and other elements are discarded. This pages are saved in the the 
"cachedcontent" field of the mdl_digui_pages table. This field can not be 
modified, it is for read only access. 

After uploading the file, the user can highlight the text using the Edit tab. 
The user can highlight blocks of text, by dragging the mouser over the text. If 
the digui mode is individual, the Edit tab shows the highlight marks of the 
current user. If the digui mode is collaborative, the Export tab shows the 
highlight marks of users who have highlighted the current page. 

When a user clicks on a Digui activity link for the first time, this user is 
added to the digui_subdiguis table. Also, Digui assigns a highlight color to 
this user, which is different from the rest of user colors. This assignment is 
stored in the mdl_digui_colors_assignments table. The text highlighted by this 
user, will be painted with this color. Two users can not have the same color. 
When two or more users highlight the same block of text, the resulting color 
will be gray. The darker is the gray, this means that more users have 
highlighted the same block of text. There are eleven colors available, so this 
means that up to eleven different users can edit the same digui. The available 
colors are stored in the mdl_digui_colors table. Future Digui versions could 
add support for more users.

Each time a user highlights a block of text, a new entry is added in the 
mdl_digui_spans table. This table stores the marks that each user has done in 
each page. The mdl_digui_spans and mdl_digui_page_version are related by using 
the common "pageversion" field. When the user highlights a text section for the 
first time, a new span is added to the mdl_digui_spans table, and a new  
entry is added to the mdl_digui_page_version too, with the "pageversion" field 
set to 1. After that, when the user highlights again, a new mark is added to 
the mdl_digui_spans table, and the "pageversion" field in the 
mdl_digui_page_version, increments by 1. When the user press the "Undo" button, 
the "pageversion" field in the mdl_digui_page_version, decrements by 1. When 
the user press the "Redo" button, the "pageversion" field in 
the mdl_digui_page_version, increments by 1. Only will be shown marks whose 
pageversion field (in the mdl_digui_spans table), be equal to the pageversion 
field in the mdl_digui_page_version table. 

Altough a text box are shown in the Edit tab, the current Digui version does 
not support page notations. In future Digui versions this funcionality could be 
enabled.

The Grading tab is useful to grade the students. Also, this tab shows 
information about users who are currently editing the Digui, users pending of 
be graded, and the grade of each one, which can be modified each time by a user 
with teacher role. To count the amount of users who need be graded, Digui uses 
the mdl_digui_last_user_modification table.


HIGHLIGHTING TECHNIQUES
=======================

a. Highlighting blocks of text

   When the mouse pointer turns into an marker pen, do the following:

   1. Click at the start of the block of text you want to highlight. 
   2. Hold down the Shift key on your keyboard (The Shift keys are the ones 
   with the block arrows on them, pointing upwards).
   3. With the Shift key held down, click your left mouse button at the end of 
   the block of text you want to highlight.
   4. A block of text will be highlighted

b. Highlight by dragging

   When the mouse pointer turns into an marker pen, do the following:
 
   1. Click with your left mouse button at the start of the text you want to 
   highlight. 
   2. Keep your left mouse button held down.
   3. Drag your mouse pointer across the screen.
   4. When you've reached the end of the text you want to highlight, let go of 
   the mouse button.
   5. Your text is highlighted.

c. Highlighting a single word

   When the mouse pointer turns into an marker pen, do the following:

   1. Double click on the word with your left mouse button. 	
   2. A single word will be highlighted.

d. Remove highlighting from a document 

   Press "Undo" button to remove highlighting from a text page of the document.


THANKS
======
   
Thank you for using Digui!

Fernando Martín
Antonio Gabriel López