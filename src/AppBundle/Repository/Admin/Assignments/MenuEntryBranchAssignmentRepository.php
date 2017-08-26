<?php

namespace AppBundle\Repository\Admin\Assignments;

use AppBundle\Entity\MenuEntry;
use AppBundle\Entity\MenuEntryBranchAssignment;
use AppBundle\Entity\Branch;
use AppBundle\Filter\Base\Filter;
use AppBundle\Repository\Admin\Base\AuditRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;

class MenuEntryBranchAssignmentRepository extends AuditRepository {

	protected function getSelectFields(QueryBuilder &$builder, Filter $filter) {
		$fields = parent::getSelectFields($builder, $filter);
		
		$fields[] = 'me.id AS menuEntryId';
		$fields[] = 'me.name AS menuEntryName';
		
		$fields[] = 'b.id AS branchId';
		$fields[] = 'b.name AS branchName';
		
		return $fields;
	}

	protected function buildJoins(QueryBuilder &$builder, Filter $filter) {
		parent::buildJoins($builder, $filter);
		
		$builder->innerJoin(MenuEntry::class, 'me', Join::WITH, 'me.id = e.menuEntry');
		$builder->innerJoin(Branch::class, 'b', Join::WITH, 'b.id = e.branch');
	}

	protected function getWhere(QueryBuilder &$builder, Filter $filter) {
		$where = parent::getWhere($builder, $filter);
		/** @var MenuEntryBranchAssignmentFilter $filter */
		
		$this->addArrayWhere($builder, $where, 'e.menuEntry', $filter->getMenuEntries());
		$this->addArrayWhere($builder, $where, 'e.branch', $filter->getBranches());
		
		return $where;
	}

	protected function buildOrderBy(QueryBuilder &$builder, Filter $filter) {
		$builder->addOrderBy('me.name', 'ASC');
		$builder->addOrderBy('b.name', 'ASC');
	}

	protected function getEntityType() {
		return MenuEntryBranchAssignment::class;
	}
}
