<?php

namespace NewsTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class ArticleControllerTest extends AbstractHttpControllerTestCase
{

    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    public function testGetArticleActionCanBeAccessed()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory('News\Model\ArticleModel', function() {
            $mockModel = $this->getMockBuilder('News\Model\ArticleModel')
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

        $this->dispatch('/article/1');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('News');
        $this->assertControllerName('Controller\Article');
        $this->assertControllerClass('ArticleController');
        $this->assertMatchedRouteName('article.id/get');
    }

    public function testGetAllArticlesForTopicIdActionCanBeAccessed()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory('News\Model\ArticleModel', function() {
            $mockModel = $this->getMockBuilder('News\Model\ArticleModel')
                ->disableOriginalConstructor()
                ->getMock();
            $mockModel->expects($this->once())
                ->method('getAllArticles')
                ->with(1)
                ->will($this->returnValue([]));
            return $mockModel;
        });

        $this->dispatch('/article?topicId=1');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('News');
        $this->assertControllerName('Controller\Article');
        $this->assertControllerClass('ArticleController');
        $this->assertMatchedRouteName('article/getAll');
    }

    public function testCreateArticleActionCanBeAccessed()
    {
        $data = [
            'topicId' => 1,
            'title' => 'test',
            'author' => 'test',
            'text' => 'test',
        ];
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory('News\Model\ArticleModel', function() use ($data) {
            $mockModel = $this->getMockBuilder('News\Model\ArticleModel')
                ->disableOriginalConstructor()
                ->getMock();
            $mockModel->expects($this->once())
                ->method('create')
                ->with($data)
                ->will($this->returnValue(1));
            return $mockModel;
        });

        $this->dispatch('/article', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('News');
        $this->assertControllerName('Controller\Article');
        $this->assertControllerClass('ArticleController');
        $this->assertMatchedRouteName('article/create');
    }

    public function testDeleteArticleActionCanBeAccessed()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory('News\Model\ArticleModel', function() {
            $mockModel = $this->getMockBuilder('News\Model\ArticleModel')
                ->disableOriginalConstructor()
                ->getMock();
            $mockModel->expects($this->once())
                ->method('delete')
                ->with(1)
                ->will($this->returnValue(true));
            return $mockModel;
        });

        $this->dispatch('/article/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('News');
        $this->assertControllerName('Controller\Article');
        $this->assertControllerClass('ArticleController');
        $this->assertMatchedRouteName('article.id/delete');
    }

}
