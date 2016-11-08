<?php

namespace AppBundle\Manager\Filter\Common;

use AppBundle\Entity\Advert;
use AppBundle\Entity\Article;
use AppBundle\Entity\Filter\Base\BaseEntityFilter;
use AppBundle\Entity\Filter\NewsletterBlockFilter;
use AppBundle\Entity\NewsletterBlockTemplate;
use AppBundle\Entity\NewsletterPage;
use AppBundle\Entity\User;
use AppBundle\Manager\Filter\Base\BaseFilterManager;

class NewsletterBlockFilterManager extends BaseFilterManager {
	
	public function adaptToView(BaseEntityFilter $filter, array $params) {
		/** @var NewsletterBlockFilter $filter */
		$filter = parent::adaptToView($filter, $params);
	
		$filter->setOrderBy('e.createdAt DESC');
	
		return $filter;
	}
	
	protected function create() {
		$userRepository = $this->doctrine->getRepository(User::class);
		$newsletterPageRepository = $this->doctrine->getRepository(NewsletterPage::class);
		$newsletterBlockTemplateRepository = $this->doctrine->getRepository(NewsletterBlockTemplate::class);
		$advertRepository = $this->doctrine->getRepository(Advert::class);
		$articleRepository = $this->doctrine->getRepository(Article::class);
    	
    	return new NewsletterBlockFilter($userRepository, $newsletterPageRepository, $newsletterBlockTemplateRepository, $advertRepository, $articleRepository);
	}
}