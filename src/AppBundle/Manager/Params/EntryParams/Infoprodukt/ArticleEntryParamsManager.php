<?php

namespace AppBundle\Manager\Params\EntryParams\Infoprodukt;

use AppBundle\Entity\Article;
use AppBundle\Manager\Params\EntryParams\Common\ArticleEntryParamsManager as BaseArticleEntryParamsManager;
use AppBundle\Repository\Infoprodukt\ArticleRepository;

class ArticleEntryParamsManager extends BaseArticleEntryParamsManager {
	
	protected function getRepository($em) {
		return new ArticleRepository($em, $em->getClassMetadata(Article::class));
	}
}