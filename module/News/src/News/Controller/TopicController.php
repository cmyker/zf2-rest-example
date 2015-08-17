<?php
namespace News\Controller;


class TopicController extends AbstractRestController
{

    const NAME = 'topic';

    public function getAllAction()
    {
        return $this->ok($this->model->getAll());
    }

}
