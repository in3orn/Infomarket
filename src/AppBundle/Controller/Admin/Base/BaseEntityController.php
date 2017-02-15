<?php

namespace AppBundle\Controller\Admin\Base;

use AppBundle\Entity\Lists\Base\BaseEntityList;
use AppBundle\Entity\User;
use AppBundle\Filter\Admin\Base\AuditFilter;
use AppBundle\Form\Editor\Base\BaseEntityEditorType;
use AppBundle\Form\Filter\Base\BaseEntityFilterType;
use AppBundle\Form\Lists\Base\BaseEntityListType;
use AppBundle\Manager\Filter\Base\FilterManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\Admin\Main\UserRepository;



abstract class BaseEntityController extends AdminController
{	
	//---------------------------------------------------------------------------
	// Internal logic
	//---------------------------------------------------------------------------
	
	protected function getFormOptions() {
		$options = parent::getFormOptions();
		
		/** @var UserRepository $userRepository */
		$userRepository = $this->getDoctrine()->getRepository(User::class);
		$options['users'] = $userRepository->findFilterItems();
		
		return $options;
	}
	
	//---------------------------------------------------------------------------
	// Managers
	//---------------------------------------------------------------------------
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Controller\Base\BaseEntityController::getFilterManager()
	 */
	protected function getFilterManager($doctrine) {	
		return new FilterManager(new AuditFilter());
	}
	
	//---------------------------------------------------------------------------
	// EntityType related
	//---------------------------------------------------------------------------
	
	protected function createNewList() {
		return new BaseEntityList();
	}
	
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Controller\Admin\Base\AdminController::getEditorFormType()
	 */
	protected function getEditorFormType() {
		return BaseEntityEditorType::class;
	}
	
	/**
	 *
	 * @return FilterFormType
	 */
	protected function getFilterFormType() {
		return BaseEntityFilterType::class;
	}
	
	/**
	 *
	 * @return BaseEntityListType
	 */
	protected function getListFormType() {
		return BaseEntityListType::class;
	}
	
	//---------------------------------------------------------------------------
	// Roles
	//---------------------------------------------------------------------------
	
	protected function getDeleteRole() {
		return 'ROLE_EDITOR';
	}
}