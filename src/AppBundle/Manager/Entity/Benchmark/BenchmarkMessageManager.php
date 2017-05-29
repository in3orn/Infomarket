<?php

namespace AppBundle\Manager\Entity\Benchmark;

use AppBundle\Entity\BenchmarkMessage;
use AppBundle\Manager\Entity\Base\BaseEntityManager;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Repository\Benchmark\BenchmarkMessageRepository;

class BenchmarkMessageManager extends BaseEntityManager {
	
	private $tokenStorage;
	
	public function __construct($doctrine, $paginator, $tokenStorage) {
		parent::__construct($doctrine, $paginator);
		$this->tokenStorage = $tokenStorage;
	}
	
	protected function getRepository() {
		$em = $this->doctrine->getManager();
		return new BenchmarkMessageRepository($em, $em->getClassMetadata(BenchmarkMessage::class));
	}
	
	/**
	 * Create new entry with request parameters.
	 * @param Request $request
	 * 
	 * @return BenchmarkMessage
	 */
	public function createFromRequest(Request $request) {
		$entry = new BenchmarkMessage();
		
		$entry->setProduct($this->getParam($request, Product::class));
		
		$entry->setAuthor($this->tokenStorage->getToken()->getUser());
		
		$entry->setReadByAdmin(false);
		$entry->setReadByAuthor(false);
		
		return $entry;
	}
	
	/**
	 * Create new entry with template parameters.
	 * @param BenchmarkMessage $template
	 * 
	 * @return BenchmarkMessage
	 */
	public function createFromTemplate($template) {
		/** @var BenchmarkMessage $entry */
		$entry = parent::createFromTemplate($template);
		
		$entry->setProduct($template->getProduct());
		
		$entry->setAuthor($this->tokenStorage->getToken()->getUser());
		
		$entry->setName($template->getName());
		$entry->setContent($template->getContent());
		
		$entry->setProduct($template->getProduct());
		
		$entry->setState(BenchmarkMessage::REPORTED_STATE);
		
		$entry->setReadByAdmin(false);
		$entry->setReadByAuthor(false);
		
		return $entry;
	}
	
	protected function getEntityType() {
		return BenchmarkMessage::class;
	}
}