<?php

namespace AppBundle\Repository;

use AppBundle\Entity\NewsletterBlockArticleAssignment;
use AppBundle\Repository\Base\BaseEntityRepository;

/**
 * NewsletterBlockArticleAssignmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsletterBlockArticleAssignmentRepository extends BaseEntityRepository
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityType() {
		return NewsletterBlockArticleAssignment::class ;
	}
}
