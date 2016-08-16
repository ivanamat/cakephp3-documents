function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
}

function isText(filename) {
    var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
        case 'txt':
        case 'md':
        case 'html':
        case 'sql':
        case 'json':
            return true;
    }
    return false;
}

function readSingleFile(e) {
    if (!isText(e.value)) {
        displayContents('Content not valid!');
        return false;
    }

    var file = e.files[0];
    
    if (!file) {
        return;
    }
    
    var reader = new FileReader();
    reader.onload = function (e) {
        var contents = e.target.result;
        displayContents(contents);
    };
    reader.readAsText(file);
}

function autoSize() {
    var e = this;
    var str = e.style.height;
    var height = str.replace("px", "");

    if (height < e.scrollHeight) {
        e.style.overflow = 'hidden';
        e.style.height = (e.scrollHeight) + "px";
    }
}

function listenTab(e) {
    if (e.keyCode === 9 && !e.shiftKey && !e.ctrlKey && !e.altKey) { // tab was pressed
        // get caret position/selection
        var start = this.selectionStart;
        var end = this.selectionEnd;
        
        // set textarea value to: text before caret + tab + text after caret
        this.value = this.value.substring(0, start)
                + "\t"
                + this.value.substring(end);

        // put caret at right position again (add one for the tab)
        this.selectionStart = this.selectionEnd = start + 1;

        // prevent the focus lose
        e.preventDefault();
    }
}

function documentKeyDown(e) {
    var keyCode = e.keyCode;

    // Submit form (Save Ctrl+s)
    if (e.which === 83 && (e.ctrlKey || e.metaKey)) {
        e.preventDefault();

        document.getElementsByTagName('form')[0].submit();

        return false;
    }
}

document.addEventListener("keydown", documentKeyDown, false);
//document.getElementById('body').addEventListener("onload", autoSize, false);
//document.getElementById('body').style.overflow = 'hidden';
//document.getElementById('body').style.height = document.getElementById('body').scrollHeight+'px';