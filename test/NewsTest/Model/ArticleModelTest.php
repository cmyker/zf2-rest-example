<?php
namespace NewsTest\Model;

use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

use News\Model\ArticleModel;
use Zend\Stdlib\ArrayObject;
use News\Exception\RestException;

class ArticleModelTest extends PHPUnit_Framework_TestCase
{
    public function testGetAllArticlesByTopicId()
    {
        $mockPaginator = $this->getMock('Zend\Paginator\Paginator',
            ['setCurrentPageNumber', 'getCurrentItems', 'getItemCountPerPage', 'getTotalItemCount'], [], '', false);
        $mockPaginator->expects($this->once())
            ->method('setCurrentPageNumber')
            ->with(1)
            ->will($this->returnValue($mockPaginator));
        $mockPaginator->expects($this->once())
            ->method('getCurrentItems')
            ->will($this->returnValue([]));
        $mockPaginator->expects($this->once())
            ->method('getItemCountPerPage')
            ->will($this->returnValue(50));
        $mockPaginator->expects($this->once())
            ->method('getTotalItemCount')
            ->will($this->returnValue(90));
        $mockPaginatorFactory = $this->getMock('News\Model\PaginatorFactory', ['create'], [], '', false);

        $mockPaginatorFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($mockPaginator));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['getSql'], [], '', false);

        $model = new ArticleModel($mockTableGateway);
        $model->setPaginatorFactory($mockPaginatorFactory);
        $resultArray = $model->getAllArticles(1, 1);

        $this->assertEquals(90, $resultArray['total']);
        $this->assertEquals(1, $resultArray['from']);
        $this->assertEquals(50, $resultArray['to']);
    }
    public function testGetArticle()
    {
        $article = new ArrayObject([
            'id' => 1,
            'title' => 'title',
            'text' => 'text',
        ]);
        $resultSet = new ResultSet();
        $resultSet->initialize([$article]);
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['select'], [], '', false);
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 1))
            ->will($this->returnValue($resultSet));

        $model = new ArticleModel($mockTableGateway);
        $this->assertSame($article, $model->get(1));
    }

    public function testThrowsExceptionWhenGetNonexistentArticle()
    {
        $mockResult = $this->getMock('Zend\Db\ResultSet\ResultSet', ['current'], [], '', false);
        $mockResult->expects($this->once())
            ->method('current')
            ->will($this->returnValue(false));
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['select'], [], '', false);
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(['id' => 1])
            ->will($this->returnValue($mockResult));

        $model = new ArticleModel($mockTableGateway);
        try {
            $model->get(1);
        } catch (RestException $e) {
            return;
        }
        $this->fail('Expected RestException was not thrown');
}

    public function testCanDeleteArticleByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['delete'], [], '', false);
        $mockTableGateway->expects($this->once())
             ->method('delete')
             ->with(['id' => 1]);

        $model = new ArticleModel($mockTableGateway);
        $model->delete(1);
    }

    public function testCreateArticle()
    {
        $article = [
            'topicId' => 1,
            'title' => 'title',
            'text' => 'text',
        ];
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['insert', 'getLastInsertValue'], [], '', false);
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($article);
        $mockTableGateway->expects($this->once())
            ->method('getLastInsertValue')
            ->will($this->returnValue(1));

        $model = new ArticleModel($mockTableGateway);
        $this->assertEquals(1, $model->create($article));
    }
}