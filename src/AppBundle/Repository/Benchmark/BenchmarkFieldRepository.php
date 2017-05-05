<?php

namespace AppBundle\Repository\Benchmark;

use AppBundle\Entity\BenchmarkField;
use AppBundle\Entity\Category;
use AppBundle\Repository\Admin\Base\AuditRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;

class BenchmarkFieldRepository extends AuditRepository
{	
	public function findShowItemsByCategory($categoryId) {
		return $this->queryShowItemsByCategory($categoryId)->getScalarResult();
	}
	
	protected function queryShowItemsByCategory($categoryId)
	{
		$builder = new QueryBuilder($this->getEntityManager());
			
		$builder->select("e.valueType, e.valueNumber, e.fieldType, e.fieldName, e.decimalPlaces, e.noteType, e.noteWeight, e.betterThanType");
		$builder->from($this->getEntityType(), "e");
	
		$expr = $builder->expr();
		
		$where = $expr->andX();
		$where->add($expr->eq('e.category', $categoryId));
		$where->add($expr->eq('e.showField', 1));
	
		$builder->where($where);
	
		$builder->orderBy('e.fieldNumber', 'ASC');
			
		return $builder->getQuery();
	}
	
	public function findFilterItemsByCategory($categoryId) {
		return $this->queryFilterItemsByCategory($categoryId)->getScalarResult();
	}
	
	protected function queryFilterItemsByCategory($categoryId)
	{
		$builder = new QueryBuilder($this->getEntityManager());
			
		$builder->select("e.valueType, e.valueNumber, e.filterType, e.filterName");
		$builder->from($this->getEntityType(), "e");
	
		$expr = $builder->expr();
	
		$where = $expr->andX();
		$where->add($expr->eq('e.category', $categoryId));
		$where->add($expr->eq('e.showFilter', 1));
	
		$builder->where($where);
	
		$builder->orderBy('e.filterNumber', 'ASC');
			
		return $builder->getQuery();
	}
	
	
	
	
	public function findNumberItemsByCategory($categoryId) {
		return $this->queryNumberItemsByCategory($categoryId)->getScalarResult();
	}
	
	protected function queryNumberItemsByCategory($categoryId)
	{
		return $this->queryItemsByTypeAndCategory($categoryId, [BenchmarkField::DECIMAL_FIELD_TYPE, BenchmarkField::INTEGER_FIELD_TYPE]);
	}
	
	public function findEnumItemsByCategory($categoryId) {
		return $this->queryEnumItemsByCategory($categoryId)->getScalarResult();
	}
	
	protected function queryEnumItemsByCategory($categoryId)
	{
		return $this->queryItemsByTypeAndCategory($categoryId, [BenchmarkField::SINGLE_ENUM_FIELD_TYPE, BenchmarkField::MULTI_ENUM_FIELD_TYPE]);
	}
	
	public function findBoolItemsByCategory($categoryId) {
		return $this->queryBoolItemsByCategory($categoryId)->getScalarResult();
	}
	
	protected function queryBoolItemsByCategory($categoryId)
	{
		return $this->queryItemsByTypeAndCategory($categoryId, [BenchmarkField::BOOLEAN_FIELD_TYPE]);
	}
	
	
	protected function queryItemsByTypeAndCategory($categoryId, $types)
	{
		$builder = new QueryBuilder($this->getEntityManager());
			
		$builder->select("e.valueType, e.valueNumber, e.fieldName, e.decimalPlaces");
		$builder->from($this->getEntityType(), "e");
	
		$builder->innerJoin(Category::class, 'c', Join::WITH, 'e.category = c.id');
	
		$expr = $builder->expr();
	
		$where = $expr->andX();
		$where->add($expr->eq('e.showField', 1));
		$where->add($expr->like('c.treePath', $expr->literal('%-' . $categoryId . '#%')));
		$where->add($expr->in('e.fieldType', $types));
	
		$builder->where($where);
	
		$builder->orderBy('e.fieldNumber', 'ASC');
			
		return $builder->getQuery();
	}
	
    /**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return BenchmarkField::class;
	}
}
