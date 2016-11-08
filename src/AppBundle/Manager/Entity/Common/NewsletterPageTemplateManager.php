<?php

namespace AppBundle\Manager\Entity\Common;

use AppBundle\Entity\NewsletterPageTemplate;
use AppBundle\Manager\Entity\Base\SimpleEntityManager;
use Symfony\Component\HttpFoundation\Request;

class NewsletterPageTemplateManager extends SimpleEntityManager {
	
	/**
	 * Create new entry with request parameters.
	 * @param Request $request
	 * 
	 * @return NewsletterPageTemplate
	 */
	public function createFromRequest(Request $request) {
		/** @var NewsletterPageTemplate $entry */
		$entry = parent::createFromRequest($request);
		
		$entry->setContent($request->get('content'));
		
		return $entry;
	}
	
	/**
	 * Create new entry with template parameters.
	 * @param NewsletterPageTemplate $template
	 * 
	 * @return NewsletterPageTemplate
	 */
	public function createFromTemplate($template) {
		/** @var NewsletterPageTemplate $entry */
		$entry = parent::createFromTemplate($template);
		
		$entry->setContent($template->getContent());
		
		return $entry;
	}
	
	protected function getEntityType() {
		return NewsletterPageTemplate::class;
	}
}