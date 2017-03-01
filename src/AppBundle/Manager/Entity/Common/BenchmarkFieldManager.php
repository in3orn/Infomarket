<?php

namespace AppBundle\Manager\Entity\Common;

use AppBundle\Entity\BenchmarkField;
use AppBundle\Manager\Entity\Base\BaseEntityManager;
use Symfony\Component\HttpFoundation\Request;

class BenchmarkFieldManager extends BaseEntityManager {
	
	/**
	 * Create new entry with request parameters.
	 * @param Request $request
	 * 
	 * @return BenchmarkField
	 */
	public function createFromRequest(Request $request) {
		$entry = new BenchmarkField();
		
		$entry->setCategory($request->get('category'));
		
		return $entry;
	}
	
	/**
	 * Create new entry with template parameters.
	 * @param BenchmarkField $template
	 * 
	 * @return BenchmarkField
	 */
	public function createFromTemplate($template) {
		/** @var BenchmarkField $entry */
		$entry = parent::createFromTemplate($template);
		
		$entry->setCategory($template->getCategory());
		
		$entry->setValueType($template->getValueType());
		$entry->setValueNumber($template->getValueNumber());
		
		$entry->setFieldName($template->getFieldName());
		$entry->setFieldType($template->getFieldType());
		$entry->setFieldNumber($template->getFieldNumber());
		$entry->setShowField($template->getShowField());
		
		$entry->setFilterName($template->getFilterName());
		$entry->setFilterType($template->getFilterType());
		$entry->setFilterNumber($template->getFilterNumber());
		$entry->setShowFilter($template->getShowFilter());
		
		return $entry;
	}
	
	protected function getEntityType() {
		return BenchmarkField::class;
	}
}