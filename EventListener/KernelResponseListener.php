<?php

namespace Webridge\LargeFileBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class KernelResponseListener implements EventSubscriberInterface
{

    //string against which we'll check the content to
    //determine if we need to inject our javascript
    const CONTENT_CHECK = 'data-largefile-field';

    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();

        // do not capture redirects or modify XML HTTP Requests
        if ($request->isXmlHttpRequest()) {
            return;
        }

        $this->injectUploader($response);
    
    }

    /**
     * Injects the uploader javascript into the given Response if the page
     * has a largefile field.
     *
     * @param Response $response A Response instance
     */
    protected function injectUploader(Response $response)
    {
        $content = $response->getContent();

        if (strpos($content, self::CONTENT_CHECK) === false) {
            return;
        }

        $pos = strripos($content, '</body>');

        if (false !== $pos) {
            $uploader = "\n".str_replace("\n", '', $this->twig->render(
                'WebridgeLargeFileBundle::uploader_js.html.twig'
            ))."\n";
            $content = substr($content, 0, $pos).$uploader.substr($content, $pos);
            $response->setContent($content);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(KernelEvents::RESPONSE => 'onKernelResponse');
    }
}
