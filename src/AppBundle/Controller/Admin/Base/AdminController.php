<?php

namespace AppBundle\Controller\Admin\Base;

use AppBundle\Controller\Base\StandardController;
use AppBundle\Entity\Lists\Base\BaseEntityList;
use AppBundle\Filter\Base\Filter;
use AppBundle\Manager\Route\RouteManager;
use AppBundle\Utils\ClassUtils;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

abstract class AdminController extends StandardController {
	
	//---------------------------------------------------------------------------
	// Internal actions
	//---------------------------------------------------------------------------
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Controller\Base\StandardController::indexActionInternal()
	 */
	protected function indexActionInternal(Request $request, $page)
	{
		$this->denyAccessUnlessGranted($this->getShowRole(), null, 'Unable to access this page!');
		return parent::indexActionInternal($request, $page);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Controller\Base\StandardController::showActionInternal()
	 */
	protected function showActionInternal(Request $request, $id) {
		$this->denyAccessUnlessGranted($this->getShowRole(), null, 'Unable to access this page!');
		return parent::showActionInternal($request, $id);
	}
	
	/**
	 *
	 * @param Request $request
	 */
	protected function newActionInternal(Request $request)
	{
		$this->denyAccessUnlessGranted($this->getNewRole(), null, 'Unable to access this page!');
	
		$params = $this->createParams($this->getNewRoute());
		$params = $this->getNewParams($request, $params);
		
		
		$viewParams = $params['viewParams'];
		
		$response = $this->initNewForms($request, $viewParams);
		if($response) return $response;
		
		
		return $this->render($this->getEditView(), $viewParams);
	}
	
	/**
	 *
	 * @param Request $request
	 * @param unknown $id
	 */
	protected function copyActionInternal(Request $request, $id)
	{
		$this->denyAccessUnlessGranted($this->getCopyRole(), null, 'Unable to access this page!');
	
		$params = $this->createParams($this->getCopyRoute());
		$params = $this->getCopyParams($request, $params, $id);
		
		
		$viewParams = $params['viewParams'];
		
		$response = $this->initCopyForms($request, $viewParams);
		if($response) return $response;
		
		
		return $this->render($this->getEditView(), $viewParams);
	}
	
	protected function editActionInternal(Request $request, $id)
	{
		$this->denyAccessUnlessGranted($this->getEditRole(), null, 'Unable to access this page!');
	
		$params = $this->createParams($this->getEditRoute());
		$params = $this->getEditParams($request, $params, $id);
		
		$rm = $this->getRouteManager();
		$rm->register($request, $params['route'], $params['routeParams']);
		
		$am = $this->getAnalyticsManager();
		$am->sendPageviewAnalytics($params['domain'], $params['route']);
		$am->sendEventAnalytics($this->getEntityName(), 'show', $id);
		
		
		$viewParams = $params['viewParams'];
		
		$response = $this->initEditForms($request, $viewParams);
		if($response) return $response;
		
		
		return $this->render($this->getEditView(), $viewParams);
	}
	
	protected function deleteActionInternal(Request $request, $id)
	{
		$this->denyAccessUnlessGranted($this->getDeleteRole(), null, 'Unable to access this page!');
		
		$params = $this->createParams($this->getDeleteRoute());
		$params = $this->getEditParams($request, $params, $id);
		
		$viewParams = $params['viewParams'];
		$entry = $viewParams['entry'];
	
		//TODO ValidationManager??
		$validator = $this->get('validator');
		$errors = $validator->validate($entry, null, array('removal'));
	
		if (count($errors) > 0) {
			foreach ($errors as $error) {
				$this->addFlash('error', $error->getMessage());
			}
			return $this->redirectToReferer($request);
		} else {
			$em = $this->getDoctrine()->getManager();
			$em->getConnection()->beginTransaction();
				
			try {
				$errors = $this->deleteMore($entry);
				if (count($errors) > 0) {
					foreach ($errors as $error) {
						$this->addFlash('error', $error->getMessage());
					}
						
					$em->getConnection()->rollback();
					return $this->redirectToReferer($request);
				}
				else {
					$em->remove($entry);
					$em->flush();
						
					$em->getConnection()->commit();
				}
			} catch (Exception $ex) {
				$em->getConnection()->rollback();
				$this->addFlash('error', $ex->getMessage());
				return $this->redirectToReferer($request);
			}
		}
		
		/** @var RouteManager $rm */
		$rm = $this->getRouteManager();
		$rm->remove($request, $id);
		$lastRoute = $rm->getLastRoute($request, ['route' => $this->getIndexRoute(), 'routeParams' => array()]);
		
		return $this->redirectToRoute($lastRoute['route'], $lastRoute['routeParams']);
	}
	
	//---------------------------------------------------------------------------
	// Actions blocks
	//---------------------------------------------------------------------------
	
	protected function initIndexForms(Request $request, array &$viewParams) {
		$response = parent::initIndexForms($request, $viewParams);
		if($response) return $response;
		
		$response = $this->initIndexFilterForm($request, $viewParams);
		if($response) return $response;
		
		$response = $this->initIndexListForm($request, $viewParams);
		if($response) return $response;
		
		return null;
	}
	
	protected function initIndexFilterForm(Request $request, array &$viewParams) {
		$filter = $viewParams['entryFilter'];
		$options = $this->getFormOptions();
		
		$filterForm = $this->createForm($this->getFilterFormType(), $filter, $options);
		$filterForm->handleRequest($request);
		
		if ($filterForm->isSubmitted() && $filterForm->isValid()) {
				
			if ($filterForm->get('search')->isClicked()) {
				return $this->redirectToRoute($this->getIndexRoute(), $filter->getRequestValues());
			}
				
			if ($filterForm->get('clear')->isClicked()) {
				$filter->clearRequestValues();
				return $this->redirectToRoute($this->getIndexRoute(), $filter->getRequestValues());
			}
		}
		
		$viewParams['filter'] = $filterForm->createView();
		
		return null;
	}
	
	protected function initIndexListForm(Request $request, array &$viewParams) {
		$filter = $viewParams['entryFilter'];
		$items = $viewParams['entries'];
		$selectedEntries = $this->getSelectedEntries($filter, $items);
		
		$listItems = $this->getListItems($items);
		
		$form = $this->createForm($this->getListFormType(), $selectedEntries, ['choices' => $listItems]);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid())
		{
			if ($form->get('selectAll')->isClicked()) {
			foreach ($listItems as $item) {
				$filter->addSelected($item);
			}
		}
		
		if ($form->get('selectNone')->isClicked()) {
			$filter->clearSelected();
		}
		
		if ($form->get('deleteSelected')->isClicked()) {
			$data = $form->getData();
			$entries = $data->getEntries();
			$filter->clearSelected();
			$this->deleteSelected($entries);
		}
		
		return $this->redirectToRoute($this->getIndexRoute(), $filter->getRequestValues());
		}
		
		$viewParams['form'] = $form->createView();
	
		return null;
	}
	
	
	
	protected function initNewForms(Request $request, array &$viewParams) {
		return $this->initUpdateForms($request, $viewParams);
	}
	
	protected function initCopyForms(Request $request, array &$viewParams) {
		return $this->initUpdateForms($request, $viewParams);
	}
	
	protected function initEditForms(Request $request, array &$viewParams) {
		return $this->initUpdateForms($request, $viewParams);
	}
	
	protected function initUpdateForms(Request $request, array &$viewParams) {
		$response = $this->initUpdateForm($request, $viewParams);
		if($response) return $response;
	
		return null;
	}
	
	protected function initUpdateForm(Request $request, array &$viewParams) {
		$entry = $viewParams['entry'];
	
		$form = $this->createForm($this->getEditorFormType(), $entry);
	
		$form->handleRequest($request);
	
		if ($form->isSubmitted() && $form->isValid())
		{
			$this->saveEntry($entry);
	
			$translator = $this->get('translator');
			$message = $translator->trans('success.created');
			$message = str_replace('%type%', '<b>' . ClassUtils::getClassName($this->getEntityType()) . '</b>', $message);
			$this->addFlash('success', $message);
				
			if ($form->get('save')->isClicked()) {
				return $this->redirectToRoute($this->getEditRoute(), array('id' => $entry->getId()));
			}
		}
		
		$viewParams['form'] = $form->createView();
		
		return null;
	}
	
	//---------------------------------------------------------------------------
	// Params
	//---------------------------------------------------------------------------
	protected function getNewParams(Request $request, array $params) {
		$params = $this->getParams($request, $params);

		$em = $this->getEntryParamsManager();
		$params = $em->getNewParams($request, $params);
		
		return $params;
	}
	
	protected function getCopyParams(Request $request, array $params, $id) {
		$params = $this->getParams($request, $params);
	
		$em = $this->getEntryParamsManager();
		$params = $em->getCopyParams($request, $params, $id);
	
		return $params;
	}
	
	protected function getEditParams(Request $request, array $params, $id) {
		$params = $this->getParams($request, $params);
	
		$em = $this->getEntryParamsManager();
		$params = $em->getEditParams($request, $params, $id);
	
		return $params;
	}
	
	//---------------------------------------------------------------------------
	// Internal logic
	//---------------------------------------------------------------------------
	
	protected function getFormOptions() {
		return array();
	}
	
	protected function getListItems($items) {
		$listItems = array();
		foreach($items as $item) {
			$key = implode(' ', $this->getListItemKeyFields($item));
			$listItems[$key] = $item['id'];
		}
		return $listItems;
	}
	
	protected function getListItemKeyFields($item) {
		return [$item['id']];
	}
	
	/**
	 * Get entries selected by the list checkboxes.
	 *
	 * @param SimpleEntityFilter $filter
	 * @param array $allEntries
	 *
	 * @return BaseEntityList
	 */
	protected function getSelectedEntries($filter, $listItems) {
		$result = $this->createNewList();
	
		foreach ($listItems as $item) {
			if(in_array($item['id'], $filter->getSelected())) {
				$result->addEntry($item['id']);
			}
		}
		
		return $result;
	}
	
	/**
	 * 
	 * @param unknown $entries
	 */
	protected function deleteSelected($entries)
	{
		$this->denyAccessUnlessGranted($this->getDeleteRole(), null, 'Unable to access this page!');
	
		$em = $this->getDoctrine()->getManager();
		
		$validator = $this->get('validator');
	
		foreach ($entries as $entry) {
			$entry = $this->getEntry($entry);
			
			$entryErrors = $validator->validate($entry, null, array('removal'));
			
			if (count($entryErrors) > 0) {
				foreach ($entryErrors as $error) {
					$this->addFlash('error', $error->getMessage());
				}
			}
			else {
				$errors = $this->deleteMore($entry);
				if (count($errors) > 0) {
					foreach ($errors as $error) {
						$this->addFlash('error', $error->getMessage());
					}
				} else {
					$em->remove($entry);
				}
			}
		}
		
		$em->flush();
	}
	
	/**
	 * 
	 * @param unknown $entry
	 */
	protected function saveEntry($entry) {
		$em = $this->getDoctrine()->getManager();
	
		$this->prepareEntry($entry);
			
		$em->persist($entry);
		$em->flush();
		
		$this->saveMore($entry);
	}
	
	/**
	 * 
	 * @param unknown $entry
	 */
	protected function prepareEntry(&$entry) { }
	
	protected function saveMore($entry) { }
	
	protected function getRepository() {
		return $this->getDoctrine()->getRepository($this->getEntityType());
	}
	
	protected function deleteMore($entry)
	{
		return array();
	}
	
	//---------------------------------------------------------------------------
	// EntityType related
	//---------------------------------------------------------------------------
	
	/**
	 * 
	 */
	abstract protected function createNewList();
	
	/**
	 *
	 */
	abstract protected function getEditorFormType();
	
	/**
	 *
	 */
	abstract protected function getFilterFormType();
	
	/**
	 *
	 */
	abstract protected function getListFormType();
	
	//---------------------------------------------------------------------------
	// Roles
	//---------------------------------------------------------------------------
	
	protected function getShowRole() {
		return 'ROLE_USER';
	}
	
	protected function getEditRole() {
		return 'ROLE_EDITOR';
	}
	
	protected function getNewRole() {
		return $this->getEditRole();
	}
	
	protected function getCopyRole() {
		return $this->getNewRole();
	}
	
	protected function getDeleteRole() {
		return 'ROLE_ADMIN';
	}
	
	//---------------------------------------------------------------------------
	// Routes
	//---------------------------------------------------------------------------
	
	protected function getNewRoute()
	{
		return $this->getIndexRoute() . '_new';
	}
	
	protected function getCopyRoute()
	{
		return $this->getIndexRoute() . '_copy';
	}
	
	protected function getEditRoute()
	{
		return $this->getIndexRoute() . '_edit';
	}
	
	protected function getDeleteRoute()
	{
		return $this->getIndexRoute() . '_delete';
	}
	
	//---------------------------------------------------------------------------
	// Views
	//---------------------------------------------------------------------------
	
	protected function getEditView()
	{
		return $this->getDomain() . '/' . $this->getEntityName() . '/editor.html.twig';
	}
	
	//---------------------------------------------------------------------------
	// Domain
	//---------------------------------------------------------------------------
	
	protected function getDomain() {
		return 'admin';
	}
}