<?php

namespace AppBundle\Factory\Item\Assignments;

use AppBundle\Entity\Assignments\NewsletterBlockAdvertAssignment;
use AppBundle\Factory\Item\Base\ItemFactory;

class NewsletterBlockAdvertAssignmentFactory extends ItemFactory {

	public function __construct() {
		parent::__construct(NewsletterBlockAdvertAssignment::class);
	}
}