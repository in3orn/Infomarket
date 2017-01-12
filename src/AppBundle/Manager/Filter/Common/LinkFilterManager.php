<?php

namespace AppBundle\Manager\Filter\Common;

use AppBundle\Entity\Filter\LinkFilter;
use AppBundle\Entity\User;
use AppBundle\Manager\Filter\Base\BaseEntityFilterManager;

class LinkFilterManager extends BaseEntityFilterManager {
	
	protected function create() {
		$userRepository = $this->doctrine->getRepository(User::class);
		
		return new LinkFilter($userRepository);
	}
}