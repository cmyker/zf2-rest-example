<?php

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use News\Config\ConfigAwareTrait;
use News\Response\JsonResponse;
use News\Exception\RestException;
use News\Model\ModelInterface;

class AbstractRestController extends AbstractActionController
{

    /**
     * to override
     */
    const NAME = null;

    //inject config
    use ConfigAwareTrait;

    /**
     * @var ModelInterface
     */
    protected $model;

    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Override parent's method to hadle exceptions
     *
     * @param MvcEvent $e
     */
    public function onDispatch(MvcEvent $e)
    {
        try {
            parent::onDispatch($e);
        } catch (RestException $e) {
            return $this->notFound([
                'error' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            if ($this->config['news']['development']) {
                return $this->error([
                    'error' => $e->getMessage()
                ]);
            } else {
                return $this->error();
            }
        }

    }

    public function getAction()
    {
        if ($data = $this->model->get($this->params()->fromRoute(static::NAME.'_id'))) {
            return $this->ok($data);
        } else {
            return $this->notFound();
        }
    }

    public function createAction()
    {
        return $this->ok([
            'id' => $this->model->create($this->params()->fromPost())
        ]);
    }


    public function deleteAction()
    {
        if ($this->model->delete($this->params()->fromRoute(static::NAME.'_id'))) {
            return $this->ok();
        } else {
            return $this->notFound();
        }
    }


    protected function ok($data = null)
    {
        $response = new JsonResponse();
        $response->setStatusCode(200);
        if ($data) {
            $response->setContent($data);
        }
        return $response;
    }

    protected function created()
    {
        $response = new JsonResponse();
        $response->setStatusCode(201);
        return $response;
    }

    protected function notFound($data = null)
    {
        $response = new JsonResponse();
        $response->setStatusCode(404);
        if ($data) {
            $response->setContent($data);
        }
        return $response;
    }

    protected function error($data = null)
    {
        $response = new JsonResponse();
        $response->setStatusCode(500);
        if ($data) {
            $response->setContent($data);
        }
        return $response;
    }
}