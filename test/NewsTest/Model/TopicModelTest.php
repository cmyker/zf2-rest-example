<?php
namespace NewsTest\Model;

use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

use News\Model\TopicModel;
use Zend\Stdlib\ArrayObject;
use News\Exception\RestException;

class TopicModelTest extends PHPUnit_Framework_TestCase
{
    public function testGetAllTopics()
    {
        $topic = new ArrayObject([
            'id' => 1,
            'title' => 'title',
            'text' => 'text',
        ]);
        $resultSet = new ResultSet();
        $resultSet->initialize([$topic]);
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['select'], [], '', false);
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->will($this->returnValue($resultSet));

        $model = new TopicModel($mockTableGateway);
        $this->assertSame($topic, $model->get(1));
    }
    public function testGetTopic()
    {
        $topic = new ArrayObject([
            'id' => 1,
            'title' => 'title',
            'text' => 'text',
        ]);
        $resultSet = new ResultSet();
        $resultSet->initialize([$topic]);
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['select'], [], '', false);
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 1))
            ->will($this->returnValue($resultSet));

        $model = new TopicModel($mockTableGateway);
        $this->assertSame($topic, $model->get(1));
    }

    public function testThrowsExceptionWhenGetNonexistentTopic()
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

        $model = new TopicModel($mockTableGateway);
        try {
            $model->get(1);
        } catch (RestException $e) {
            return;
        }
        $this->fail('Expected RestException was not thrown');
    }

    public function testCanDeleteTopicByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['delete'], [], '', false);
        $mockTableGateway->expects($this->once())
            ->method('delete')
            ->with(['id' => 1]);

        $model = new TopicModel($mockTableGateway);
        $model->delete(1);
    }

    public function testCreateTopic()
    {
        $topic = [
            'name' => 'title',
        ];
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', ['insert', 'getLastInsertValue'], [], '', false);
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($topic);
        $mockTableGateway->expects($this->once())
            ->method('getLastInsertValue')
            ->will($this->returnValue(1));

        $model = new TopicModel($mockTableGateway);
        $this->assertEquals(1, $model->create($topic));
    }
}