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
        error: function(e, data) {
            console.log('error');
            var inputField = document.getElementById(this.fileInput[0].id);
            uploadFinished(inputField, false);
        },
        success: function (files, data, xhr) {
            var inputField = document.getElementById(this.fileInput[0].id);
            var largeFileField = document.getElementById(inputField.getAttribute('data-largefile-field'));

            getErrorPlaceHolder(inputField).html('');

            if (files.error) {

                displayError(inputField, files.error);
                largeFileField.value = null;
                uploadFinished(inputField, false);
                return;
            }

            largeFileField.value = files.files[0].name;
            //remove the required attribute of the input field
            //to allow form submission
            inputField.removeAttribute('required');                
            uploadFinished(inputField, true);

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

    return {
        setup: setup
    };

}());
window.onload =  LargeFile.setup;