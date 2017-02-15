<?php

namespace AppBundle\Repository\Infoprodukt;

use AppBundle\Entity\ArticleCategory;
use AppBundle\Repository\Base\BaseEntityRepository;
use Doctrine\ORM\QueryBuilder;

class ArticleCategoryRepository extends BaseEntityRepository
{
	//TODO inherit from parent!
	public function findFilterItems() {
		$items = $this->queryFilterItems()->getScalarResult();
		
		$filterItems = array();
		foreach ($items as $item) {
			$filterItems[$item['name'] . ' ' . $item['subname']] = $item['id'];
		}
		
		return $filterItems;
	}
	
	public function queryFilterItems()
	{
		$builder = new QueryBuilder($this->getEntityManager());
			
		$builder->select("e.id, e.name, e.subname");
		$builder->from($this->getEntityType(), "e");
	
		$where = $builder->expr()->andX();
		$where->add($builder->expr()->eq('e.infoprodukt', 1));
		$builder->where($where);
		
		$builder->addOrderBy('e.name', 'ASC');
	
		return $builder->getQuery();
	}
	
	
	
	public function findHomeItems() {
		return $this->queryHomeItems()->getScalarResult();
	}
	
	public function queryHomeItems()
	{
		$builder = new QueryBuilder($this->getEntityManager());
			
		$builder->select("e.id, e.name, e.subname, e.image, e.vertical");
		$builder->from($this->getEntityType(), "e");
	
		$builder->where($builder->expr()->eq('e.infoprodukt', 1));
	
		return $builder->getQuery();
	}
	
	
	
    /**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return ArticleCategory::class;
	}
}