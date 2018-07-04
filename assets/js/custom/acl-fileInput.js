var urls_docs = {
    delete: '/eaudit/docs/services/deleteImage.php',
    upload: '/eaudit/docs/services/uploadImage.php'
};


/**
 * [fileinput_image description]
 * @param  {[type]} place      place to put the file input
 * @param  {[type]} ini_prev   ini -> array with image links
 *                             config -> array with each image config
 * @param  {[type]} extra_data [description]
 * @param  {[type]} urls       [description]
 * @param  {[type]} async      [description]
 * @return {[type]}            [description]
 */
function fileinput_image(place, ini_prev, extra_data, urls, async) {
    var fileInput = fileinput_json_image(ini_prev, extra_data, urls, async);
    $(place).fileinput(fileInput);
}


function fileinput_json_image(ini_prev, extra_data, urls, async) {
    var fileInput = {};
    if (ini_prev != null) {
        fileInput.initialPreview = ini_prev.ini;
        fileInput.initialPreviewConfig = ini_prev.config;
        fileInput.initialPreviewAsData = true;
        fileInput.deleteUrl = urls.delete;
        fileInput.deleteExtraData = extra_data.delete;
        fileInput.overwriteInitial = false;
    }

    fileInput.previewFileType = "image";
    fileInput.browseClass = "btn btn-success";
    fileInput.browseLabel = "Pick Image";
    fileInput.browseIcon = "<i class=\"glyphicon glyphicon-picture\"></i> ";
    fileInput.removeClass = "btn btn-danger";
    fileInput.removeLabel = "Delete";
    fileInput.removeIcon = "<i class=\"glyphicon glyphicon-trash\"></i> ";
    fileInput.uploadAsync = async;
    fileInput.browseOnZoneClick = true;
    fileInput.allowedFileExtensions = ['jpg', 'gif', 'png', 'jpeg'];

    if (async) {
        fileInput.uploadClass = "btn btn-info";
        fileInput.uploadLabel = "Upload";
        fileInput.uploadIcon = "<i class=\"glyphicon glyphicon-upload\"></i> ";
        fileInput.uploadUrl = urls.upload;
        fileInput.uploadExtraData = extra_data.upload;
    } else {
        fileInput.showUpload = false; // hide upload button
    }
    return fileInput;
}

function fileinput_json_basic(extensions, urls, extra_data) {
    var fileInput = {};
    fileInput.previewFileType = 'any';
    fileInput.browseOnZoneClick = true;
    fileInput.uploadClass = "btn btn-info";
    fileInput.removeClass = "btn btn-danger";
    fileInput.browseClass = "btn btn-primary";
    fileInput.browseLabel = "Buscar fichero";
    fileInput.allowedFileExtensions = extensions;
    fileInput.uploadUrl = urls.upload;
    fileInput.deleteUrl = urls.delete;
    fileInput.uploadExtraData = extra_data.upload;
    fileInput.deleteExtraData = extra_data.delete;
    return fileInput;
}

