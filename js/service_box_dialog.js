tinyMCEPopup.requireLangPack();



var ServiceBox = {

	init : function(ed) {

		var action, elm, f = document.forms[0];

		this.editor = ed;
		elm = ed.dom.getParent(ed.selection.getNode(), 'A');
		v = ed.dom.getAttrib(elm, 'name') || ed.dom.getAttrib(elm, 'id');

		if (v) {

			this.action = 'update';
			f.anchorName.value = v;

		}

		f.insert.value = ed.getLang(elm ? 'update' : 'insert');

	},



	update : function() {

		var ed = this.editor, elm, name = document.forms[0].anchorName.value, content = document.forms[0].service_box_content.value, attribName;

/*

		if (!name || !/^[a-z][a-z0-9\-\_:\.]*$/i.test(name)) {
			tinyMCEPopup.alert('advanced_dlg.anchor_invalid');
			return;

		}

*/

		tinyMCEPopup.restoreSelection();



		if (this.action != 'update') ed.selection.collapse(1);





		elm = ed.dom.getParent(ed.selection.getNode(), 'div.service_box');

		if (elm) {

			elm.setAttribute(attribName, name);
			elm[attribName] = name;
			ed.undoManager.add();

		} else {

			// create with zero-sized nbsp so that in Webkit where anchor is on last line by itself caret cannot be placed after it

			var attrs =  {'class' : 'service_box'};
			attrs[attribName] = name;

			var h3 = '<h3>'+name+'</h3>';			

			ed.execCommand('mceInsertContent', 0, ed.dom.createHTML('div', attrs, h3+content));

			ed.nodeChanged();

		}



		tinyMCEPopup.close();

	}

};



tinyMCEPopup.onInit.add(ServiceBox.init, ServiceBox);