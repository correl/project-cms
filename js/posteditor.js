function postEditor() {
	this.element = jQuery("<div></div>");
	this.element.dialog({
			"autoOpen": false,
			"modal": true,
			"title": "Edit",
			"width": "85%",
			"height": jQuery(window).height() * .98,
			"buttons": {
				"Save": function() { editor.save(); },
				"Cancel": function() { $(this).dialog("close"); }
			}
	});
	this.url = "edit.php";
	this.tinymceopts = {
		"script_url": "",

		"theme": "advanced",
		"theme_advanced_toolbar_location": "top",
		"theme_advanced_toolbar_align": "left",
		"theme_advanced_statusbar_location": "bottom",
		"theme_advanced_resizing": false,
		"height": "20em",
		"width": "85%",

		"plugins": "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		"theme_advanced_buttons1": "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		"theme_advanced_buttons2": "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		"theme_advanced_buttons3": "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		"theme_advanced_buttons4": "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		//"theme_advanced_blockformats": "p,address,code,pre,h1,h2,h3,h4,h5,h6"
		"theme_advanced_blockformats": "p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp"
	};
}
postEditor.prototype = {
	"editPage": function(page, project) {
		project = typeof(project) == "undefined" ? 0 : project;
		this.element.load(this.url, {"page": page, "project": project}, function() {
				$(this).dialog("open");
				$(this.element).find('textarea').tinymce(editor.tinymceopts);
		});
	},
	"editPost": function(post, project) {
		project = typeof(project) == "undefined" ? 0 : project;
		this.element.load(this.url, {"post": post, "project": project}, function() {
				$(this).dialog("open");
				$(this.element).find('textarea').tinymce(editor.tinymceopts);
		});
	},
	"save": function() {
		var form = $(this.element.find("form"));
		if (form.length == 0) return;
		form = $(form[0]);
		for (var i = 0; i < form.find("textarea[name]").length; i++) {
			//jQuery.wymeditors(i).update();
		}
		var elements = form.find("input, select, textarea");
		var fields = new Object;
		for (var i = 0; i < elements.length; i++) {
			fields[$(elements[i]).attr("name")] = $(elements[i]).val();
		}
		fields["save"] = 1;
		this.element.load(this.url, fields, function() {
				if ($(this).html().length == 0) {
					$(this).dialog("close");
					document.location = document.location;
				}
		});
	}
};

editor = new postEditor;
