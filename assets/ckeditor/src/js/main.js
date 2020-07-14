/**
 * Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/* exported initSample */

if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
	CKEDITOR.tools.enableHtml5Elements( document );

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 200;
CKEDITOR.config.width = 'auto';
CKEDITOR.config.toolbar = [
   ['Bold','Italic','Underline','NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','TextColor','BGColor','Table'],
] ;
var initSample = ( function() {
	var wysiwygareaAvailable = isWysiwygareaAvailable(),
		isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

	return function() {
		var editorElement = CKEDITOR.document.getById( 'editor_1' );
		var editorElement_2 = CKEDITOR.document.getById( 'editor_2' );
		var editorElement_3 = CKEDITOR.document.getById( 'editor_3' );
		var editorElement_4 = CKEDITOR.document.getById( 'editor_4' );
		var editorElement_5 = CKEDITOR.document.getById( 'editor_5' );
		var editorElement_6 = CKEDITOR.document.getById( 'editor_6' );
		var editorElement_7 = CKEDITOR.document.getById( 'editor_7' );
		var editorElement_8 = CKEDITOR.document.getById( 'editor_8' );
		var editorElement_9 = CKEDITOR.document.getById( 'editor_9' );
		var editorElement_10 = CKEDITOR.document.getById( 'editor_10' );
		// :(((
		
		if ( isBBCodeBuiltIn ) {
			editorElement.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
			editorElement_2.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
			editorElement_3.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
			editorElement_4.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);

			editorElement_5.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
			editorElement_6.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
			editorElement_7.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
			editorElement_8.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
			editorElement_9.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
			editorElement_10.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
			);
		}

		// Depending on the wysiwygare plugin availability initialize classic or inline editor.
		if ( wysiwygareaAvailable ) {
			CKEDITOR.replace( 'editor_1' );
			CKEDITOR.replace( 'editor_2' );
			CKEDITOR.replace( 'editor_3' );
			CKEDITOR.replace( 'editor_4' );
			CKEDITOR.replace( 'editor_5' );
			CKEDITOR.replace( 'editor_6' );
			CKEDITOR.replace( 'editor_7' );
			CKEDITOR.replace( 'editor_8' );
			CKEDITOR.replace( 'editor_9' );
			CKEDITOR.replace( 'editor_10' );
		} else {
			editorElement.setAttribute( 'contenteditable', 'true' );
			CKEDITOR.inline( 'editor_1' );
			CKEDITOR.inline( 'editor_2' );
			CKEDITOR.inline( 'editor_3' );
			CKEDITOR.inline( 'editor_4' );
			CKEDITOR.inline( 'editor_5' );
			CKEDITOR.inline( 'editor_6' );
			CKEDITOR.inline( 'editor_7' );
			CKEDITOR.inline( 'editor_8' );
			CKEDITOR.inline( 'editor_9' );
			CKEDITOR.inline( 'editor_10' );

			// TODO we can consider displaying some info box that
			// without wysiwygarea the classic editor may not work.
		}
	};

	function isWysiwygareaAvailable() {
		// If in development mode, then the wysiwygarea must be available.
		// Split REV into two strings so builder does not replace it :D.
		if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
			return true;
		}

		return !!CKEDITOR.plugins.get( 'wysiwygarea' );
	}
} )();

