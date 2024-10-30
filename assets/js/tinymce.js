function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function insertlomadeewprocode() {


	var tagtext;
	var category_ddb = document.getElementById('lomadeewpro_category');
	var category     = category_ddb.value;
        var keywords_ddb = document.getElementById('lomadeewpro_keywords');
        var keywords     = keywords_ddb.value;
        
	if( ( category == "" ) && ( keywords == "" ) )
            return;

        tagtext = "[lomadeewpro category='" + category + "' keywords='" + keywords + "'";

        window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext + ']');
	tinyMCEPopup.editor.execCommand('mceRepaint');
	tinyMCEPopup.close();
	return;
}

