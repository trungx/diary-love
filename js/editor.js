/**
* bbCode control by subBlue design [ www.subBlue.com ]
* Includes unixsafe colour palette selector by SHS`
*/
// Startup variables
var form_name = 'post';
var form_name_chat = 'chat_post';
//var text_name = 'content';
var text_name_chat = 'chat_content';
var load_draft = false;
var upload = false;
var onload_functions = new Array();
// Define the bbCode tags
var bbcode = new Array();
var bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[left]','[/left]','[right]','[/right]','[center]','[/center]','[img]','[/img]','[s]','[/s]','[hl=#FFFF00]', '[/hl]','[music=400,300]','[/music]','[code]','[/code]','[php]\n<?php\n','\n?>\n[/php]','[flv=400,300]','[/flv]');
var imageTag = false;

// Helpline messages
var help_line = {
	b: 'Bold text: [b]text[/b]',
	i: 'Italic text: [i]text[/i]',
	u: 'Underline text: [u]text[/u]',
	q: 'Quote text: [quote]text[/quote]',
	c: 'Code display: [code]code[/code]',
	l: 'List: [list]text[/list]',
	o: 'Ordered list: [list=]text[/list]',
	p: 'Insert image: [img]http://image_url[/img]',
	w: 'Insert URL: [url]http://url[/url] or [url=http://url]URL text[/url]',
	a: 'Inline uploaded attachment: [attachment=]filename.ext[/attachment]',
	s: 'Font colour: [color=red]text[/color]  Tip: you can also use color=#FF0000',
	f: 'Font size: [size=85]small text[/size]',
	e: 'List: Add list element',
	d: 'Flash: [flash=width,height]http://url[/flash]'
		}

var panels = new Array('options-panel', 'attach-panel', 'poll-panel');
var show_panel = 'options-panel';

var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf('msie') != -1) && (clientPC.indexOf('opera') == -1));
var is_win = ((clientPC.indexOf('win') != -1) || (clientPC.indexOf('16bit') != -1));

var baseHeight;
onload_functions.push('initInsertions()');

/**
* Shows the help messages in the helpline window
*/
function helpline(help)
{
	document.forms[form_name].helpbox.value = help_line[help];
}



/**
* Fix a bug involving the TextRange object. From
* http://www.frostjedi.com/terra/scripts/demo/caretBug.html
*/
function initInsertions() 
{
	var doc;

	if (document.forms[form_name])
	{
		doc = document;
	}
	else 
	{
		doc = opener.document;
	}

	var textarea = doc.forms[form_name].elements[text_name];

	if (is_ie && typeof(baseHeight) != 'number')
	{
		textarea.focus();
		baseHeight = doc.selection.createRange().duplicate().boundingHeight;

		if (!document.forms[form_name])
		{
			document.body.focus();
		}
	}
}

/**
* bbstyle
*/
function bbstyle(bbnumber)
{
	if (bbnumber != -1)
	{
		bbfontstyle(bbtags[bbnumber], bbtags[bbnumber+1]);
	}
	else
	{
		insert_text('[*]');
		document.forms[form_name].elements[text_name].focus();
	}
}

function bbstyle2(bbnumber)
{
	if (bbnumber != -1)
	{
		bbfontstyle2(bbtags[bbnumber], bbtags[bbnumber+1]);
	}
	else
	{
		insert_emot('[*]');
		document.forms[form_name_chat].elements[text_name_chat].focus();
	}
}

function attach_inline(index, filename)
{
	insert_text('[attachment=' + index + ']' + filename + '[/attachment]');
	document.forms[form_name].elements[text_name].focus();
}

//##################### UPDATE ########################

// show color
function showcolor(color){
if(color=="#define#"){
    color = prompt(lang[57], lang[58]);
   }
   if(color!=null){
   bbfontstyle('[color=' + color + ']', '[/color]')
   }
}

function showcolor2(color){
if(color=="#define#"){
    color = prompt(lang[57], lang[58]);
   }
   if(color!=null){
   bbfontstyle2('[color=' + color + ']', '[/color]')
   }
}

// show font
function showfont(font){
if(font=="#define#"){
    font = prompt(lang[59], lang[60]);
   }
   if(font!=null){
   bbfontstyle('[font=' + font + ']', '[/font]')
   }
}

// show fsize
function showsize(size){
if(size=="#define#"){
    size = prompt(lang[61], lang[62]);
   }
   if(size!=null){
   bbfontstyle('[size=' + size + ']', '[/size]')
   }
}

// insert emotion
function insert_emot(text, spaces, popup)
{
	var textarea;

	if (!popup)
	{
		textarea = document.forms[form_name_chat].elements[text_name_chat];
	}
	else
	{
		textarea = opener.document.forms[form_name_chat].elements[text_name_chat];
	}
	if (spaces)
	{
		text = ' ' + text + ' ';
	}

	if (!isNaN(textarea.selectionStart))
	{
		var sel_start = textarea.selectionStart;
		var sel_end = textarea.selectionEnd;

		mozWrap(textarea, text, '')
		textarea.selectionStart = sel_start + text.length;
		textarea.selectionEnd = sel_end + text.length;
	}
	else if (textarea.createTextRange && textarea.caretPos)
	{
		if (baseHeight != textarea.caretPos.boundingHeight)
		{
			textarea.focus();
			storeCaret(textarea);
		}

		var caret_pos = textarea.caretPos;
		caret_pos.text = caret_pos.text.charAt(caret_pos.text.length - 1) == ' ' ? caret_pos.text + text + ' ' : caret_pos.text + text;
	}
	else
	{
		textarea.value = textarea.value + text;
	}
	if (!popup)
	{
		textarea.focus();
	}
}


//insert images
function addimage(){
    url=prompt(lang[70],'http://');
    if(url!=null){
        align=prompt(lang[63],'c');
        width=prompt(lang[73],'');
        if(align!='c'){
            align_insert = " align="+align;
        }else{
            align_insert="";
        }
        if(width!=''){
            width_insert = " w="+width;
        }else{
            width_insert ="";
        }

      insert_text('[img'+align_insert+width_insert+']' + url + '[/img]');
    }else{
        return false;
      }
}

// insert file
function addfile(){
    url=prompt(lang[64],"http://");
    name=prompt(lang[65],lang[66]);
    if(url!=null){
      insert_text('[file=' + url + ']' + name + '[/file]');
      }
}

function addfile_chat(){
    url=prompt(lang[64],"http://");
    name=prompt(lang[65],lang[66]);
    if(url!=null){
      insert_emot('[file=' + url + ']' + name + '[/file]');
      }
}

// insert file for member
function addsfile(){
    url=prompt(lang[64],"http://");
    name=prompt(lang[65],lang[66]);
    if(url!=null){
      insert_text('[mfile=' + url + ']' + name + '[/mfile]');
      }
}

function addsfile_chat(){
    url=prompt(lang[64],"http://");
    name=prompt(lang[65],lang[66]);
    if(url!=null){
      insert_emot('[mfile=' + url + ']' + name + '[/mfile]');
      }
}

// insertt link
function addurl(){
    url=prompt(lang[67],"http://");
    if(url!=null){
        title=prompt(lang[71],'');
        name=prompt(lang[72],'');
        if(name==null||name==''){
            name=url;
        }
        insert_text('[url=' + url + ',' + title + ']' + name + '[/url]');
    }
}

// insert email
function addemail(){
    url=prompt(lang[68],lang[69]);
    if(url!=null){
        insert_text('[email]' + url + '[/email]');
    }
}

//  insert media
function addmedia(mediatype) {
	txt=prompt(lang[54],"http://");
	if(txt!=null) {
        width=prompt(lang[55],"400");
	    height=prompt(lang[56],"300");
        insert_text('['+mediatype+'=' + width + ',' + height + ']' + txt + '[/'+mediatype+']');
	}else{
        return false;
    }
}


// addattachment
function addattach(name){
        insert_text('[attach]' + name + '[/attach]');
}


/**
* Apply bbcodes
*/
function bbfontstyle(bbopen, bbclose)
{
	theSelection = false;

	var textarea = document.forms[form_name].elements[text_name];

	textarea.focus();

	if ((clientVer >= 4) && is_ie && is_win)
	{
		// Get text selection
		theSelection = document.selection.createRange().text;

		if (theSelection)
		{
			// Add tags around selection
			document.selection.createRange().text = bbopen + theSelection + bbclose;
			document.forms[form_name].elements[text_name].focus();
			theSelection = '';
			return;
		}
	}
	else if (document.forms[form_name].elements[text_name].selectionEnd && (document.forms[form_name].elements[text_name].selectionEnd - document.forms[form_name].elements[text_name].selectionStart > 0))
	{
		mozWrap(document.forms[form_name].elements[text_name], bbopen, bbclose);
		document.forms[form_name].elements[text_name].focus();
		theSelection = '';
		return;
	}
	
	//The new position for the cursor after adding the bbcode
	var caret_pos = getCaretPosition(textarea).start;
	var new_pos = caret_pos + bbopen.length;		

	// Open tag
	insert_text(bbopen + bbclose);

	// Center the cursor when we don't have a selection
	// Gecko and proper browsers
	if (!isNaN(textarea.selectionStart))
	{
		textarea.selectionStart = new_pos;
		textarea.selectionEnd = new_pos;
	}
	// IE
	else if (document.selection)
	{
		var range = textarea.createTextRange(); 
		range.move("character", new_pos); 
		range.select();
		storeCaret(textarea);
	}

	textarea.focus();
	return;
}

function bbfontstyle2(bbopen, bbclose)
{
	theSelection = false;

	var textarea = document.forms[form_name_chat].elements[text_name_chat];

	textarea.focus();

	if ((clientVer >= 4) && is_ie && is_win)
	{
		// Get text selection
		theSelection = document.selection.createRange().text;

		if (theSelection)
		{
			// Add tags around selection
			document.selection.createRange().text = bbopen + theSelection + bbclose;
			document.forms[form_name_chat].elements[text_name_chat].focus();
			theSelection = '';
			return;
		}
	}
	else if (document.forms[form_name_chat].elements[text_name_chat].selectionEnd && (document.forms[form_name_chat].elements[text_name_chat].selectionEnd - document.forms[form_name_chat].elements[text_name_chat].selectionStart > 0))
	{
		mozWrap(document.forms[form_name_chat].elements[text_name_chat], bbopen, bbclose);
		document.forms[form_name_chat].elements[text_name_chat].focus();
		theSelection = '';
		return;
	}

	//The new position for the cursor after adding the bbcode
	var caret_pos = getCaretPosition(textarea).start;
	var new_pos = caret_pos + bbopen.length;

	// Open tag
	insert_emot(bbopen + bbclose);

	// Center the cursor when we don't have a selection
	// Gecko and proper browsers
	if (!isNaN(textarea.selectionStart))
	{
		textarea.selectionStart = new_pos;
		textarea.selectionEnd = new_pos;
	}
	// IE
	else if (document.selection)
	{
		var range = textarea.createTextRange();
		range.move("character", new_pos);
		range.select();
		storeCaret(textarea);
	}

	textarea.focus();
	return;
}

/**
* Insert text at position
*/
function insert_text(text, spaces, popup)
{
	var textarea;

	if (!popup)
	{
		textarea = document.forms[form_name].elements[text_name];
	}
	else
	{
		textarea = opener.document.forms[form_name].elements[text_name];
	}
	if (spaces)
	{
		text = ' ' + text + ' ';
	}

	if (!isNaN(textarea.selectionStart))
	{
		var sel_start = textarea.selectionStart;
		var sel_end = textarea.selectionEnd;

		mozWrap(textarea, text, '')
		textarea.selectionStart = sel_start + text.length;
		textarea.selectionEnd = sel_end + text.length;
	}
	else if (textarea.createTextRange && textarea.caretPos)
	{
		if (baseHeight != textarea.caretPos.boundingHeight)
		{
			textarea.focus();
			storeCaret(textarea);
		}

		var caret_pos = textarea.caretPos;
		caret_pos.text = caret_pos.text.charAt(caret_pos.text.length - 1) == ' ' ? caret_pos.text + text + ' ' : caret_pos.text + text;
	}
	else
	{
		textarea.value = textarea.value + text;
	}
	if (!popup)
	{
		textarea.focus();
	}
}

/**
* Add inline attachment at position
*/
function attach_inline(index, filename)
{
	insert_text('[attachment=' + index + ']' + filename + '[/attachment]');
	document.forms[form_name].elements[text_name].focus();
}

/**
* Add quote text to message
*/
function addquote(post_id, username)
{
	var message_name = 'message_' + post_id;
	var theSelection = '';
	var divarea = false;

	if (document.all)
	{
		divarea = document.all[message_name];
	}
	else
	{
		divarea = document.getElementById(message_name);
	}

	// Get text selection - not only the post content :(
	if (window.getSelection)
	{
		theSelection = window.getSelection().toString();
	}
	else if (document.getSelection)
	{
		theSelection = document.getSelection();
	}
	else if (document.selection)
	{
		theSelection = document.selection.createRange().text;
	}

	if (theSelection == '' || typeof theSelection == 'undefined' || theSelection == null)
	{
		if (divarea.innerHTML)
		{
			theSelection = divarea.innerHTML.replace(/<br>/ig, '\n');
			theSelection = theSelection.replace(/<br\/>/ig, '\n');
			theSelection = theSelection.replace(/&lt\;/ig, '<');
			theSelection = theSelection.replace(/&gt\;/ig, '>');
			theSelection = theSelection.replace(/&amp\;/ig, '&');
			theSelection = theSelection.replace(/&nbsp\;/ig, ' ');
		}
		else if (document.all)
		{
			theSelection = divarea.innerText;
		}
		else if (divarea.textContent)
		{
			theSelection = divarea.textContent;
		}
		else if (divarea.firstChild.nodeValue)
		{
			theSelection = divarea.firstChild.nodeValue;
		}
	}

	if (theSelection)
	{
		insert_text('[quote="' + username + '"]' + theSelection + '[/quote]');
	}

	return;
}

/**
* From http://www.massless.org/mozedit/
*/
function mozWrap(txtarea, open, close)
{
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	var scrollTop = txtarea.scrollTop;

	if (selEnd == 1 || selEnd == 2)
	{
		selEnd = selLength;
	}

	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);

	txtarea.value = s1 + open + s2 + close + s3;
	txtarea.selectionStart = selEnd + open.length + close.length;
	txtarea.selectionEnd = txtarea.selectionStart;
	txtarea.focus();
	txtarea.scrollTop = scrollTop;

	return;
}

/**
* Insert at Caret position. Code from
* http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
*/
function storeCaret(textEl)
{
	if (textEl.createTextRange)
	{
		textEl.caretPos = document.selection.createRange().duplicate();
	}
}

/**
* Color pallette
*/
function colorPalette(dir, width, height)
{
	var r = 0, g = 0, b = 0;
	var numberList = new Array(6);
	var color = '';

	numberList[0] = '00';
	numberList[1] = '40';
	numberList[2] = '80';
	numberList[3] = 'BF';
	numberList[4] = 'FF';

	document.writeln('<table cellspacing="1" cellpadding="0" border="0">');

	for (r = 0; r < 5; r++)
	{
		if (dir == 'h')
		{
			document.writeln('<tr>');
		}

		for (g = 0; g < 5; g++)
		{
			if (dir == 'v')
			{
				document.writeln('<tr>');
			}

			for (b = 0; b < 5; b++)
			{
				color = String(numberList[r]) + String(numberList[g]) + String(numberList[b]);
				document.write('<td bgcolor="#' + color + '" style="width: ' + width + 'px; height: ' + height + 'px;">');
				document.write('<a href="#" onclick="bbfontstyle(\'[color=#' + color + ']\', \'[/color]\'); return false;"><img src="images/spacer.gif" width="' + width + '" height="' + height + '" alt="#' + color + '" title="#' + color + '" /></a>');
				document.writeln('</td>');
			}

			if (dir == 'v')
			{
				document.writeln('</tr>');
			}
		}

		if (dir == 'h')
		{
			document.writeln('</tr>');
		}
	}
	document.writeln('</table>');
}


/**
* Caret Position object
*/
function caretPosition()
{
	var start = null;
	var end = null;
}


/**
* Get the caret position in an textarea
*/
function getCaretPosition(txtarea)
{
	var caretPos = new caretPosition();

	// simple Gecko/Opera way
	if(txtarea.selectionStart || txtarea.selectionStart == 0)
	{
		caretPos.start = txtarea.selectionStart;
		caretPos.end = txtarea.selectionEnd;
	}
	// dirty and slow IE way
	else if(document.selection)
	{

		// get current selection
		var range = document.selection.createRange();

		// a new selection of the whole textarea
		var range_all = document.body.createTextRange();
		range_all.moveToElementText(txtarea);

		// calculate selection start point by moving beginning of range_all to beginning of range
		var sel_start;
		for (sel_start = 0; range_all.compareEndPoints('StartToStart', range) < 0; sel_start++)
		{
			range_all.moveStart('character', 1);
		}

		txtarea.sel_start = sel_start;

		// we ignore the end value for IE, this is already dirty enough and we don't need it
		caretPos.start = txtarea.sel_start;
		caretPos.end = txtarea.sel_start;
	}

	return caretPos;
}








