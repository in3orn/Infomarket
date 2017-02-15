<?php

namespace AppBundle\Repository\Infomarket;

use AppBundle\Entity\BranchCategoryAssignment;
use AppBundle\Entity\Category;
use AppBundle\Repository\Base\BaseEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\Base\Filter;

class CategoryRepository extends BaseEntityRepository
{
	protected function buildJoins(QueryBuilder &$builder, Filter $filter) {
		$builder->innerJoin(BranchCategoryAssignment::class, 'bca', Join::WITH, 'e.id = bca.category');
	}
	
	protected function buildOrderBy(QueryBuilder &$builder, Filter $filter) {
		$builder->addOrderBy('e.name', 'ASC');
		$builder->addOrderBy('e.subname', 'ASC');
	}
	
	
	
	protected function getSelectFields(QueryBuilder &$builder, Filter $filter) {
		$fields = parent::getSelectFields($builder, $filter);
	
		$fields[] = 'e.name';
		$fields[] = 'e.subname';
		$fields[] = 'e.image';
		$fields[] = 'e.vertical';
	
		return $fields;
	}
	
	protected function getWhere(QueryBuilder &$builder, Filter $filter) {
		/** @var BranchDependentFilter $filter */
		$where = parent::getWhere($builder, $filter);
	
		$expr = $builder->expr();
	
		$where->add($expr->eq('e.infomarket', 1));
		$where->add('e.parent IS NULL');
		$where->add($expr->eq('bca.branch', $filter->getContextBranch()));
			
		$builder->where($where);
	
		return $where;
	}
	
	
	
	public function findFilterItemsByBranch($branchId) {
		$items = $this->queryFilterItemsByBranch($branchId)->getScalarResult();
		
		$filterItems = array();
		foreach ($items as $item) {
			$filterItems[$item['name'] . ' ' . $item['subname']] = $item['id'];
		}
		
		return $filterItems;
	}
	
	protected function queryFilterItemsByBranch($branchId)
	{
		$builder = new QueryBuilder($this->getEntityManager());
			
		$builder->select("e.id, e.name, e.subname");
		$builder->from($this->getEntityType(), "e");
		
		$builder->innerJoin(BranchCategoryAssignment::class, 'bca', Join::WITH, 'e.id = bca.category');
	
		$where = $builder->expr()->andX();
		$where->add($builder->expr()->eq('e.infomarket', 1));
		$where->add($builder->expr()->eq('bca.branch', $branchId));
		$where->add('e.parent IS NULL');
	
		$builder->where($where);
		
		$builder->orderBy('e.name', 'ASC');
			
		return $builder->getQuery();
	}
	
	
	
	public function findContextItems($branchId) {
		return $this->queryContextItems($branchId)->getScalarResult();
	}
	
	protected function queryContextItems($branchId)
	{
		$builder = new QueryBuilder($this->getEntityManager());
		 
		$builder->select("e.id");
		$builder->from($this->getEntityType(), "e");
		
		$builder->innerJoin(BranchCategoryAssignment::class, 'bca', Join::WITH, 'e.id = bca.category');
		
		$where = $builder->expr()->andX();
		$where->add($builder->expr()->eq('e.infomarket', 1));
		$where->add($builder->expr()->eq('bca.branch', $branchId));
		$where->add('e.parent IS NULL');
		
		$builder->where($where);
		 
		return $builder->getQuery();
	}
	
	
	
	public function findMenuItems($rootIds) {
		$items = $this->queryMenuItems($rootIds)->getScalarResult();

		$rootItems = $this->getRootItemsWithLimit($items, 5);
		
		$index = 0;
		$size = count($rootItems);
		for($i = 0; $i < $size; $i++) {
			$rootItem = $rootItems[$i];
			$rootItems[$i] = $this->assignChildrenWithLimit($rootItem, $items, $index, 11);
		}
		
		return $rootItems;
	}
	
	protected function queryMenuItems($rootsIds)
	{
		$builder = new QueryBuilder($this->getEntityManager());
			
		$builder->select("e.id, IDENTITY(e.parent) AS parent, e.preleaf, e.name, e.subname");
		$builder->from($this->getEntityType(), "e");
		
		$builder->leftJoin(Category::class, 'p', Join::WITH, 'p.id = e.parent');
		$builder->leftJoin(Category::class, 'pp', Join::WITH, 'pp.id = p.parent');
		
		$where = $builder->expr()->andX();
		$where->add($builder->expr()->eq('e.infomarket', 1));
		$where->add($builder->expr()->eq('e.featured', 1));
		$where->add('(e.parent IS NULL OR p.preleaf = 0)');
		$where->add('(p.parent IS NULL OR pp.preleaf = 0)');
		
		if(count($rootsIds) > 0) {
			$rootWhere = $builder->expr()->orX();
			$rootWhere->add($builder->expr()->in('e.rootId', $rootsIds));
			$rootWhere->add($builder->expr()->in('e.id', $rootsIds));
			
			$where->add($rootWhere);
		}
			
		$builder->where($where);
		
		$builder->orderBy('e.treePath', 'ASC');
			
		return $builder->getQuery();
	}
	
	
	
	public function findSubcategories($category) {
		return $this->queryTopItems($category)->getScalarResult();
	}
	
	protected function queryTopItems($category) {
		$builder = new QueryBuilder($this->getEntityManager());
	
		$builder->select('e.id, e.name, e.subname');
		$builder->from($this->getEntityType(), "e");
	
		$expr = $builder->expr();
		
		$where = $expr->andX();
		$where->add($expr->eq('e.infomarket', 1));
		$where->add($expr->eq('e.parent', $category));
		
		$builder->where($where);
	
		$builder->addOrderBy('e.orderNumber', 'ASC');
		$builder->addOrderBy('e.name', 'ASC');
		$builder->addOrderBy('e.subname', 'ASC');
	
		return $builder->getQuery();
	}
	
    /**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return Category::class ;
	}
}