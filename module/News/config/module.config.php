<?php
/**
 * Zend Framework (http://framework.zend.com/]
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c] 2005-2015 Zend Technologies USA Inc. (http://www.zend.com]
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;
use News\Controller;
use News\Model;
use News\Config\ConfigAwareInterface;

return [
    //routing
    'router' => [
        'routes' => [
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'ControllerDefault',
                        'action' => 'notFound',
                    ),
                ),
            ),
            'article' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/article',
                    'defaults' => [
                        'controller' => 'Controller\Article',
                        'action' => 'getAll'
                    ],
                ],
                'child_routes' => [
                    'getAll' => [
                        'type' => 'Method',
                        'options' => [
                            'verb' => 'get', //accepts only get method
                            'defaults' => [
                                'controller' => 'Controller\Article',
                                'action' => 'getAll'
                            ],
                        ],
                    ],
                    'create' => [
                        'type' => 'Method',
                        'options' => [
                            'verb' => 'post', //accepts only get method
                            'defaults' => [
                                'controller' => 'Controller\Article',
                                'action' => 'create'
                            ],
                        ],
                    ],
                ]
            ],
            'article.id' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/article/:article_id',
                ],
                'child_routes' => [
                    'get' => [
                        'type' => 'Method',
                        'options' => [
                            'verb' => 'get', //accepts only put method
                            'defaults' => [
                                'controller' => 'Controller\Article',
                                'action' => 'get'
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => 'Method',
                        'options' => [
                            'verb' => 'delete', //accepts only put method
                            'defaults' => [
                                'controller' => 'Controller\Article',
                                'action' => 'delete'
                            ],
                        ],
                    ],
                ],
            ],
            //topics routing
            'topic' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/topic',
                    'defaults' => [
                        'controller' => 'Controller\Topic',
                        'action' => 'getAll'
                    ],
                ],
                'child_routes' => [
                    'getAll' => [
                        'type' => 'Method',
                        'options' => [
                            'verb' => 'get', //accepts only get method
                            'defaults' => [
                                'controller' => 'Controller\Topic',
                                'action' => 'getAll'
                            ],
                        ],
                    ],
                    'create' => [
                        'type' => 'Method',
                        'options' => [
                            'verb' => 'post', //accepts only get method
                            'defaults' => [
                                'controller' => 'Controller\Topic',
                                'action' => 'create'
                            ],
                        ],
                    ],
                ]
            ],
            'topic.id' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/topic/:topic_id',
                ],
                'child_routes' => [
                    'get' => [
                        'type' => 'Method',
                        'options' => [
                            'verb' => 'get', //accepts only put method
                            'defaults' => [
                                'controller' => 'Controller\Topic',
                                'action' => 'get'
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => 'Method',
                        'options' => [
                            'verb' => 'delete', //accepts only put method
                            'defaults' => [
                                'controller' => 'Controller\Topic',
                                'action' => 'delete'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'News\AbstractFactory\Autoload', //autoload other classes, lowest priority
            'News\AbstractFactory\TableGateway', //factory to create tableGateway for models
        ],
        'factories' => [
            'News\Model\ArticleModel' => function($sm) {
                /* @var ServiceManager $sm */
                $model = new Model\ArticleModel($sm->create('News\TableGateway\Articles')); //$sm->create creates single object, not service
                $model->setPaginatorFactory($sm->create('News\Model\PaginatorFactory'));
                return $model;
            },
            'News\Model\TopicModel' => function($sm) {
                /* @var ServiceManager $sm */
                return new Model\TopicModel($sm->create('News\TableGateway\Topics')); //$sm->create creates single object, not service
            },
        ],
        'initilizers' => [
            //inject config to classes that reqiure it
            'config' => function($service, $sm) {
                if ($service instanceof ConfigAwareInterface) {
                    $service->setConfig($sm->get('config'));
                }
            }
        ]
    ],
    'controllers' => [
        'factories' => [
            'Controller\Article' => function($cm) {
                /* @var ControllerManager $cm */
                $controller = new Controller\ArticleController();
                $controller->setModel($cm->getServiceLocator()->create('News\Model\ArticleModel'));
                return $controller;
            },

            'Controller\Topic' => function($cm) {
                /* @var ControllerManager $cm */
                $controller = new Controller\TopicController();
                $controller->setModel($cm->getServiceLocator()->create('News\Model\TopicModel'));
                return $controller;
            }
        ],
        'invokables' => [
            'Controller\Default' => 'News\Controller\DefaultController',
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'news' => [
        //validators for recieved data
        'inputFilters' => [
            'Controller\Article' => [
                'create' => [
                    [
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'Zend\I18n\Validator\IsInt',
                                'options' => [],
                            ],
                        ],
                        'filters' => [],
                        'name' => 'topicId',
                    ],
                    [
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'Zend\Validator\NotEmpty',
                                'options' => [],
                            ],
                        ],
                        'filters' => [],
                        'name' => 'title',
                    ],
                    [
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'Zend\Validator\NotEmpty',
                                'options' => [],
                            ],
                        ],
                        'filters' => [],
                        'name' => 'author',
                    ],
                    [
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'Zend\Validator\NotEmpty',
                                'options' => [],
                            ],
                        ],
                        'filters' => [],
                        'name' => 'text',
                    ],
                ],
                'getAll' => [
                    [
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'Zend\I18n\Validator\IsInt',
                                'options' => [],
                            ],
                        ],
                        'filters' => [],
                        'name' => 'topicId',
                    ],
                ],
            ],
            'Controller\Topic' => [
                'create' => [
                    [
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'Zend\Validator\NotEmpty',
                                'options' => [],
                            ],
                        ],
                        'filters' => [],
                        'name' => 'name',
                    ],
                ],
            ]
        ]
    ],
];
