var LargeFile = (function() {
    'use strict';

    var PROGRESSS_SUFFIX = "_progress";
    var ERROR_SUFFIX = "_error";

    var setup = function() {

        $('.largefile').fileupload({
        dataType: 'json',
        type: 'POST',
        formData: {},
        progress: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            uploadProgress(this, progress);
        },
        add: function (e, data) {
            showCancel(this, data.submit());
        },
        error: function(e, data) {
            var inputField = document.getElementById(this.fileInput[0].id);
            uploadFinished(inputField, false);
        },
        success: function (files, data, xhr) {
            var inputField = document.getElementById(this.fileInput[0].id);
            var largeFileField = document.getElementById(
                inputField.getAttribute('data-largefile-field')
            );
            var largeFileFieldOriginal = document.getElementById(
                inputField.getAttribute('data-largefile-field-original')
            );

            getErrorPlaceHolder(inputField).html('');

            if (files.error) {

                displayError(inputField, files.error);
                largeFileField.value = null;
                uploadFinished(inputField, false);
                return;
            }
            largeFileField.value = files.files[0].name;
            largeFileFieldOriginal.value = files.files[0].originalName;
            //remove the required attribute of the input field
            //to allow form submission
            inputField.removeAttribute('required');                
            uploadFinished(inputField, true);

            //show preview of loaded item
            showPreview(inputField, files.files[0].url);
        }
        });
    };

    var uploadProgress = function (inputField, progress) {
        
        inputField.disabled = true;

        $('.largefile_progress').css(
            'display',
            'block'
        );
        var progressBar = getProgressBar(inputField);
        progressBar.css(
            'width',
            progress + '%'
        );
        progressBar.html(progress + '%');
    };
    
    var uploadFinished = function (inputField, success) {
        inputField.removeAttribute('disabled');
        var progressBar = getProgressBar(inputField);
        if (!success) {
            progressBar.css(
                'width',
                '0px'
            );
            progressBar.html('');
        }
        hideCancel(inputField);
    };

    var displayError = function(inputField, error) {

        var largeFileErrorPlaceHolder = getErrorPlaceHolder(inputField);
        if (error == 'error.whitelist') {
            largeFileErrorPlaceHolder.html(
                inputField.getAttribute('data-mimeTypesMessage')
            );
        } else if (error == 'error.maxsize') {
            largeFileErrorPlaceHolder.html(
                inputField.getAttribute('data-maxSizeMessage')
            );
        }
    }

    var getProgressBar = function (inputField) {
        return $(
            '#' + 
            inputField.getAttribute('data-largefile-field') + 
            PROGRESSS_SUFFIX + 
            ' .bar'
        );
    };

    var getErrorPlaceHolder = function (inputField) {
        return $(
            '#' + 
            inputField.getAttribute('data-largefile-field') + 
            ERROR_SUFFIX 
        );
    };

    var showPreview = function (inputField, url) {
        if (inputField.hasAttribute('data-previewcontainer') &&
            inputField.getAttribute('data-previewcontainer') != ''
        ) {
            var container = document.getElementById(
                inputField.getAttribute('data-previewcontainer')
            );
            if (url.substring(0,1) !== '/') {
                url = '/' + url;
            }
            container.src = url;

            if (container.load) {
                container.load();
            }
        }
    };

    var showCancel = function(inputField, uploader) {
        
        $('#' + inputField.getAttribute('data-largefile-field') + '-upload').
            addClass('hidden');

        var cancelButton = 
            $('#' + inputField.getAttribute('data-largefile-field') + '-cancel');

        cancelButton.removeClass('hidden');

        cancelButton.click(function() { 
            uploader.abort();
        });
    };

    var hideCancel = function(inputField) {
        
        $('#' + inputField.getAttribute('data-largefile-field') + '-cancel').
            addClass('hidden');

        var uploadButton = 
            $('#' + inputField.getAttribute('data-largefile-field') + '-upload');

        uploadButton.removeClass('hidden');
    };

    return {
        setup: setup
    };

}());
window.onload =  LargeFile.setup;
