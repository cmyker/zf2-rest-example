<?php

namespace News\Controller;

class ArticleController extends AbstractRestController
{

    const NAME = 'article';

    public function getAllAction()
    {
        return $this->ok($this->model->getAllArticles(
            $this->params()->fromQuery('topicId'),
            $this->params()->fromQuery('page') ?: 1)
        );
    }

}
