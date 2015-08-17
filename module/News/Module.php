<?php

namespace News;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\Router\Http\RouteMatch as HttpRouteMatch;
use Zend\InputFilter;
use News\Response\JsonResponse;

class Module
{

    protected $config;

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function onBootstrap(MvcEvent $e)
    {
        $this->config = $e->getApplication()->getServiceManager()->get('config');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        //handle all dispatch errors with custom handler
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'returnErrorResponce'], 0);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'returnErrorResponce'], 0);
        //here we do the validation of parameters. Config is module.config.php in inputFilters section
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function(MvcEvent $e) {
            //do the checks
            $request = $e->getRequest();
            if (!$request instanceof HttpRequest) {
                return;
            }
            $httpMethod = $request->getMethod();
            $routeMatch = $e->getRouteMatch();
            if (!$routeMatch instanceof HttpRouteMatch) {
                return;
            }
            if (!$controller = $routeMatch->getParam('controller', false)) {
                return;
            }
            if (!$action = $routeMatch->getParam('action', false)) {
                return;
            }
            if (!empty($this->config['news']['inputFilters'][$controller][$action])
            && $inputFiltersConfig = $this->config['news']['inputFilters'][$controller][$action]) {
                //create input filters from factory
                $factory = new InputFilter\Factory();
                $inputFilter = $factory->createInputFilter($inputFiltersConfig);
                //get data depending on request type
                switch ($httpMethod) {
                    case 'GET':
                        $inputFilter->setData($request->getQuery());
                        break;
                    case 'POST':
                        $inputFilter->setData($request->getPost());
                        break;
                }
                //validate
                if (!$inputFilter->isValid()) {
                    $errors = [];
                    foreach ($inputFilter->getMessages() as $param => $messages) {
                        $errors[] = "$param: ".implode(', ', $messages)."\n";
                    }
                    $response = new JsonResponse();
                    return $response->setContent([
                        'errors' => $errors
                    ]);
                }
            }
        }, 0);
    }

    /**
     * Handle error and display a meaningful error text
     *
     * @param MvcEvent $e
     * @return JsonModel
     */
    public function returnErrorResponce(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('config');
        $response = new JsonResponse();
        $response->setStatusCode(500);
        if (!empty($config['news']['development']) && $error = $e->getError()) {
            $exception = $e->getParam('exception');
            $exceptionJson = [];
            if ($exception) {
                $exceptionJson = [
                    'class' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'stacktrace' => $exception->getTraceAsString()
                ];
            }
            $errors = [
                'message'   => 'An error occurred during execution; please try again later.',
                'error'     => $error,
                'exception' => $exceptionJson,
            ];
            if ($error == 'error-router-no-match') {
                $errors['message'] = 'Resource not found';
            }
            $response->setContent($errors);
        }
        $e->stopPropagation(true);
        return $e->setResponse($response);
    }
}
