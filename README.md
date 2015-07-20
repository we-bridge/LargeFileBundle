WebridgeLargeFileBundle
===================

The WebridgeLargeFileBundle for Symfony2 adds support for handling asynchronous upload of large files.
At the moment, it simply adds a progress bar during file upload.


Dependencies
-------------

- OneupUploaderBundle (https://github.com/1up-lab/OneupUploaderBundle)
- JQuery file upload (https://github.com/blueimp/jQuery-File-Upload)

Configuration
-------------

- Add the bundles to the kernel

```php
new Oneup\UploaderBundle\OneupUploaderBundle(),
new Webridge\LargeFileBundle\WebridgeLargeFileBundle(),
```

- Import the bundle routes in routing.yml

```yaml
webridge_largefile:
    resource: "@WebridgeLargeFileBundle/Resources/config/routing.yml"
    prefix:   /
```

- Configure the OneupUploaderBundle mappings

eg:
```yaml
oneup_uploader:
    mappings:
        video:
            allowed_mimetypes: ["video/mp4"]
            max_size: 47063040
            storage:
                directory: uploads%upload_video_rel_directory%
```

- Add WebridgeLargeFileBundle to assetic's bundles

- Add the following assetic assets alias:

```yaml
fileupload:
    inputs:
        - '../vendor/blueimp/jquery-file-upload/js/vendor/jquery.ui.widget.js'
        - '../vendor/blueimp/jquery-file-upload/js/jquery.iframe-transport.js'
        - '../vendor/blueimp/jquery-file-upload/js/jquery.fileupload.js'
```

Usage
-------

Add a field of type `largefile` to your form

```php
$builder
    ->add(
        'videoFile',
        'largefile',
        [
            'label' => 'Upload Videos',
            'media' => 'video',
            'mimeTypesMessage' => "Upload only mp4",
            'maxSizeMessage' => "Maximum size is 40 MB",
        ],
    )
```

The field will be displayed as an input file styled as a button using bootstrap classes

- `label`: Label of the button
- `media`: refers to the mapping in OneupUploaderBundle's configuration
- `mimeTypesMessage`: Localized error message to display for incorrect mime type input
- `maxSizeMessage`: Localized error message to display if upload file is larger than the maximum size allowed

The bundle will add an extra hidden field to the form with the name of the file field appended with the string "Name" (eg: videoFileName).
This field can be used by the application to link the uploaded filename with an entity.
