<?php

namespace AppBundle\Repository;

use AppBundle\Entity\AdvertCategoryAssignment;
use AppBundle\Repository\Base\BaseEntityRepository;

/**
 * AdvertCategoryAssignmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertCategoryAssignmentRepository extends BaseEntityRepository
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return AdvertCategoryAssignment::class ;
	}
}