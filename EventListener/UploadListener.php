<?php

namespace Webridge\LargeFileBundle\EventListener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener
{
    private $uploadDirectory;

    public function __construct($uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $response = $event->getResponse();
        //$response['file_path'] = $this->uploadDirectory . $event->getFile()->getFilename();
        $response['status'] = 'success';
        $response['files'] = array(
            array(
                'name' => $event->getFile()->getFilename(),
                'url' => $this->uploadDirectory . $event->getFile()->getFilename()
            )
        );
        return $response;
    }
}