<?php

namespace News\Response;

use Zend\Http\Response;
use Zend\Json\Json;

/**
 * Represents an json response payload
 */
class JsonResponse extends Response
{
    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        $headers = parent::getHeaders();
        if (!$headers->has('content-type')) {
            $headers->addHeaderLine('content-type', 'application/json');
        }
        return $headers;
    }

    /**
     * @inheritdoc
     */
    public function setContent($value)
    {
        $this->content = Json::encode($value);
        return $this;
    }
}
