<?php

namespace Webridge\LargeFileBundle\EventListener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener
{
    public function onUpload(PostPersistEvent $event)
    {
        $response = $event->getResponse();
        $uploadDirectory = $event->getConfig()['storage']['directory'];

        $fileBag = $event->getRequest()->files->all();
        $uploadFile = null;
        array_walk_recursive($fileBag, function($item, $key) use (&$uploadFile) {
           if ($item instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
               $uploadFile = $item;
           }
        });
        $response['status'] = 'success';
        $response['files'] = array(
            array(
                'originalName' => $uploadFile->getClientOriginalName(),
                'name' => $event->getFile()->getFilename(),
                'url' => $uploadDirectory . '/' . $event->getFile()->getFilename()
            )
        );
        return $response;
    }
}