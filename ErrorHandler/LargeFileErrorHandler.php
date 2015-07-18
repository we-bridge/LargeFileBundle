<?php

namespace Webridge\LargeFileBundle\ErrorHandler;

use Exception;
use Oneup\UploaderBundle\Uploader\ErrorHandler\ErrorHandlerInterface;
use Oneup\UploaderBundle\Uploader\Response\AbstractResponse;

class LargeFileErrorHandler implements ErrorHandlerInterface
{
    public function addException(AbstractResponse $response, Exception $exception)
    {
        dump($response);
        dump($exception);
        $message = $exception->getMessage();
        $response['error'] = $message;
    }
}