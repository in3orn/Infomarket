<?php

namespace AppBundle\Repository\Admin\Main;

use AppBundle\Entity\ArticleCategory;
use AppBundle\Filter\Admin\Main\ArticleCategoryFilter;
use AppBundle\Filter\Base\Filter;
use AppBundle\Repository\Admin\Base\FeaturedEntityRepository;
use Doctrine\ORM\QueryBuilder;

class ArticleCategoryRepository extends FeaturedEntityRepository
{
	protected function buildOrderBy(QueryBuilder &$builder, Filter $filter) {
		$builder->addOrderBy('e.subname', 'ASC');
	}
	
	
	
	protected function getSelectFields(QueryBuilder &$builder, Filter $filter) {
		$fields = parent::getSelectFields($builder, $filter);
		
		$fields[] = 'e.subname';
		
		return $fields;
	}
	
	protected function getWhere(QueryBuilder &$builder, Filter $filter) {
		/** @var ArticleCategoryFilter $filter */
		$where = parent::getWhere($builder, $filter);
		
		if($filter->getSubname() && strlen($filter->getSubname()) > 0) {
			$where->add($this->buildStringsExpression($builder, 'e.subname', $filter->getSubname()));
		}
		
		return $where;
	} 
	
	
	
	
	protected function buildFilterOrderBy(QueryBuilder &$builder) {
		parent::buildFilterOrderBy($builder);
		
		$builder->addOrderBy('e.subname', 'ASC');
	}
	
	
	
	protected function getFilterSelectFields(QueryBuilder &$builder) {
		$fields = parent::getFilterSelectFields($builder);
		
		$fields[] = 'e.subname';
		
		return $fields;
	}
	
	
	protected function getFilterItemKeyFields($item) {
		$fields = parent::getFilterItemKeyFields($item);
	
		$fields[] = $item['subname'];
	
		return $fields;
	}
	
    /**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return ArticleCategory::class;
	}
}
