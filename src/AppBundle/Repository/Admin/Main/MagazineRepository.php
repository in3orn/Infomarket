<?php

namespace AppBundle\Repository\Admin\Main;

use AppBundle\Entity\Magazine;
use AppBundle\Entity\MagazineBranchAssignment;
use AppBundle\Entity\MagazineCategoryAssignment;
use AppBundle\Filter\Admin\Main\MagazineFilter;
use AppBundle\Filter\Base\Filter;
use AppBundle\Repository\Admin\Base\FeaturedEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class MagazineRepository extends FeaturedEntityRepository
{
	protected function  buildJoins(QueryBuilder &$builder, Filter $filter) {
		/** @var MagazineFilter $filter */
		parent::buildJoins($builder, $filter);
		
		if(count($filter->getBranches()) > 0) {
			$builder->leftJoin(MagazineBranchAssignment::class, 'mba', Join::WITH, 'e.id = mba.magazine');
		}
		
		if(count($filter->getCategories()) > 0) {
			$builder->leftJoin(MagazineCategoryAssignment::class, 'mca', Join::WITH, 'e.id = mca.magazine');
		}
	}
	
	protected function getWhere(QueryBuilder &$builder, Filter $filter) {
		/** @var MagazineFilter $filter */
		$where = parent::getWhere($builder, $filter);
		
		if(count($filter->getParents()) > 0) {
			$where->add($builder->expr()->in('e.parent', $filter->getParents()));
		}
		
		if(count($filter->getBranches()) > 0) {
			$where->add($builder->expr()->in('mba.branch', $filter->getBranches()));
		}
		
		if(count($filter->getCategories()) > 0) {
			$where->add($builder->expr()->in('mca.category', $filter->getCategories()));
		}
	
		return $where;
	}
	
	
	
    /**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return Magazine::class;
	}
}
