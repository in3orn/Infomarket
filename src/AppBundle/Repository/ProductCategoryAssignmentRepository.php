<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ProductCategoryAssignment;
use AppBundle\Repository\Base\BaseEntityRepository;

/**
 * ProductCategoryAssignmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductCategoryAssignmentRepository extends BaseEntityRepository
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return ProductCategoryAssignment::class ;
	}
}