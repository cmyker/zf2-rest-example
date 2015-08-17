<?php

namespace News\Model;

use Zend\Paginator\Paginator;

class ArticleModel extends AbstractModel
{

    const NAME = 'article';

    /**
     * Selects articles with paginator
     *
     * @param $topicId
     * @param int $page
     * @return array
     */
    public function getAllArticles($topicId, $page = 1)
    {
        $page = (int)$page;
        $paginator = $this->getPaginatorFactory()->create($this->tableGateway, [
            'topicId' => (int)$topicId
        ]);
        $paginator->setCurrentPageNumber($page);
        $rows = $paginator->getCurrentItems();
        $countPerPage = $paginator->getItemCountPerPage();
        $total = $paginator->getTotalItemCount();
        $to = $total < ($page * $countPerPage) ? $total : $page * $countPerPage;
        $from = ($page - 1) * $countPerPage + 1;
        return [
            'items' => $rows,
            'total' => $total,
            'from' => $from,
            'to' => $to,
        ];
    }

}