<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ArticleTagAssignment;
use AppBundle\Repository\Base\BaseEntityRepository;

/**
 * ArticleTagAssignmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleTagAssignmentRepository extends BaseEntityRepository
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return ArticleTagAssignment::class ;
	}
}