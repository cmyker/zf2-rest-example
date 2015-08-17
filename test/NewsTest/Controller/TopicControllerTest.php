<?php

namespace NewsTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class TopicControllerTest extends AbstractHttpControllerTestCase
{

    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    public function testGetTopicActionCanBeAccessed()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory('News\Model\TopicModel', function() {
            $mockModel = $this->getMockBuilder('News\Model\TopicModel')
                ->disableOriginalConstructor()
                ->getMock();
            $mockModel->expects($this->once())
                ->method('get')
                ->with(1)
                ->will($this->returnValue([
                    'id' => 1
                ]));
            return $mockModel;
        });

        $this->dispatch('/topic/1');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('News');
        $this->assertControllerName('Controller\Topic');
        $this->assertControllerClass('TopicController');
        $this->assertMatchedRouteName('topic.id/get');
    }

    public function testGetAllTopicsActionCanBeAccessed()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory('News\Model\TopicModel', function() {
            $mockModel = $this->getMockBuilder('News\Model\TopicModel')
                ->disableOriginalConstructor()
                ->getMock();
            $mockModel->expects($this->once())
                ->method('getAll')
                ->will($this->returnValue([]));
            return $mockModel;
        });

        $this->dispatch('/topic');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('News');
        $this->assertControllerName('Controller\Topic');
        $this->assertControllerClass('TopicController');
        $this->assertMatchedRouteName('topic/getAll');
    }

    public function testCreateTopicActionCanBeAccessed()
    {
        $data = [
            'name' => 'test',
        ];
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory('News\Model\TopicModel', function() use ($data) {
            $mockModel = $this->getMockBuilder('News\Model\TopicModel')
                ->disableOriginalConstructor()
                ->getMock();
            $mockModel->expects($this->once())
                ->method('create')
                ->with($data)
                ->will($this->returnValue(1));
            return $mockModel;
        });

        $this->dispatch('/topic', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('News');
        $this->assertControllerName('Controller\Topic');
        $this->assertControllerClass('TopicController');
        $this->assertMatchedRouteName('topic/create');
    }

    public function testDeleteTopicActionCanBeAccessed()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory('News\Model\TopicModel', function() {
            $mockModel = $this->getMockBuilder('News\Model\TopicModel')
                ->disableOriginalConstructor()
                ->getMock();
            $mockModel->expects($this->once())
                ->method('delete')
                ->with(1)
                ->will($this->returnValue(true));
            return $mockModel;
        });

        $this->dispatch('/topic/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('News');
        $this->assertControllerName('Controller\Topic');
        $this->assertControllerClass('TopicController');
        $this->assertMatchedRouteName('topic.id/delete');
    }

}
