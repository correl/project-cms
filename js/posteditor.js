function postEditor() {
	this.element = jQuery("<div></div>");
	this.element.dialog({
			"autoOpen": false,
			"modal": true,
			"title": "Edit",
			"width": "80%",
			"buttons": {
				"Save": function() { editor.save(); },
				"Cancel": function() { $(this).dialog("close"); }
			}
	});
	this.url = "edit.php";
}
postEditor.prototype = {
	"editPage": function(page, project) {
		project = typeof(project) == "undefined" ? 0 : project;
		this.element.load(this.url, {"page": page, "project": project}, function() {
				$(this).dialog("open");
				$(this.element).find('textarea').wymeditor();
		});
	},
	"save": function() {
		var form = $(this.element.find("form"));
		if (form.length == 0) return;
		form = $(form[0]);
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
