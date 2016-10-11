<?php

namespace AppBundle\Entity\Filter;

use AppBundle\Entity\Filter\Base\SimpleEntityFilter;
use AppBundle;
use AppBundle\Repository\UserRepository;

class TagFilter extends SimpleEntityFilter {

	/**
	 * 
	 */
	public function __construct(UserRepository $userRepository) {
		parent::__construct($userRepository);
		
		$this->filterName = 'tag_filter_';
	}
}