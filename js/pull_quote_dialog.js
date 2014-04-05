tinyMCEPopup.requireLangPack();



var PullQuote = {

	init : function(ed) {

		var action, elm, f = document.forms[0];

		this.editor = ed;
		elm = ed.dom.getParent(ed.selection.getNode(), 'A');
		v = ed.dom.getAttrib(elm, 'name') || ed.dom.getAttrib(elm, 'id');

		if (v) {

			this.action = 'update';
			f.pq_title.value = v;

		}

		f.insert.value = ed.getLang(elm ? 'update' : 'insert');

	},



	update : function() {

		var ed = this.editor, elm, name = document.forms[0].pq_title.value, 
		content = document.forms[0].pull_quote_content.value, attribName, 
		align = document.forms[0].pull_quote_align.value, attribName,
		width = document.forms[0].pull_quote_width.value, attribName;

/*

		if (!name || !/^[a-z][a-z0-9\-\_:\.]*$/i.test(name)) {
			tinyMCEPopup.alert('advanced_dlg.anchor_invalid');
			return;

		}

*/

		tinyMCEPopup.restoreSelection();



		if (this.action != 'update') ed.selection.collapse(1);





		elm = ed.dom.getParent(ed.selection.getNode(), 'div.pull_quote');

		if (elm) {

			elm.setAttribute(attribName, name);
			elm[attribName] = name;
			ed.undoManager.add();

		} else {

			// create with zero-sized nbsp so that in Webkit where anchor is on last line by itself caret cannot be placed after it

			var attrs =  {'class' : 'pq '+align+' '+width};
			attrs[attribName] = name;

			var h3 = '<h3>'+name+'</h3>';

			ed.execCommand('mceInsertContent', 0, ed.dom.createHTML('blockquote', attrs, h3+content));

			ed.nodeChanged();

		}



		tinyMCEPopup.close();

	}

};



tinyMCEPopup.onInit.add(PullQuote.init, PullQuote);