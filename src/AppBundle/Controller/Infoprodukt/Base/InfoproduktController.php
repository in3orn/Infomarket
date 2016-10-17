<?php

namespace AppBundle\Controller\Infoprodukt\Base;

use AppBundle\Controller\Base\BaseEntityController;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Filter\Base\SimpleEntityFilter;
use AppBundle\Entity\Page;
use AppBundle\Entity\User;
use AppBundle\Form\Filter\Base\SimpleEntityFilterType;
use AppBundle\Manager\Params\Base\AdvertParamsManager;
use AppBundle\Manager\Params\Base\FooterParamsManager;
use AppBundle\Manager\Params\Infoprodukt\InfoproduktParamsManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Manager\Filter\Infoprodukt\Base\InfoproduktFilterManager;
use AppBundle\Entity\Base\SimpleEntity;
use AppBundle\Form\Base\SimpleEntityType;
use AppBundle\Form\Filter\Base\SearchFilterType;

abstract class InfoproduktController extends BaseEntityController
{	
	//---------------------------------------------------------------------------
	// Internal actions
	//---------------------------------------------------------------------------
	
	/**
	 *
	 * @param Request $request
	 * @param integer $page current page number
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function indexActionInternal(Request $request, $page)
	{
		$params = $this->createParams($this->getIndexRoute());
		$params = $this->getIndexParams($request, $params, $page);
		
		$rm = $this->getRouteManager();
		$rm->register($request, $params['route'], $params['routeParams']);
		
		$am = $this->getAnalyticsManager();
		$am->sendPageviewAnalytics($params['domain'], $params['route']);
		
		$viewParams = $params['viewParams'];
		
		
		
		
		$userRepository = $this->getDoctrine()->getRepository(User::class);
		
		
		
		$searchFilter = new SimpleEntityFilter($userRepository);
		$searchFilter->initValues($request);
		
		$searchFilterForm = $this->createForm(SimpleEntityFilterType::class, $searchFilter);
		$searchFilterForm->handleRequest($request);
		
		if ($searchFilterForm->isSubmitted() && $searchFilterForm->isValid()) {
			if ($searchFilterForm->get('search')->isClicked()) {
				return $this->redirectToRoute($this->getSearchRoute(), $searchFilter->getValues());
			}
		}
		$viewParams['searchFilterForm'] = $searchFilterForm->createView();
		
		
		
		$newsletter = new SimpleEntity();
		
		$newsletterForm = $this->createForm(SimpleEntityType::class, $newsletter);
		$newsletterForm->handleRequest($request);
		
		if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
			if ($newsletterForm->get('save')->isClicked()) {
				//TODO return $this->redirectToRoute($this->getSearchRoute(), $searchFilter->getValues());
			}
		}
		$viewParams['newsletterForm'] = $newsletterForm->createView();
		
		
		
		return $this->render($this->getIndexView(), $viewParams);
	}
	
	/**
	 *
	 * @param Request $request
	 * @param integer $id current entry ID
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function showActionInternal(Request $request, $id)
	{
		$params = $this->createParams($this->getShowRoute());
		$params = $this->getShowParams($request, $params, $id);
		
		$rm = $this->getRouteManager();
		$rm->register($request, $params['route'], $params['routeParams']);
		
		$am = $this->getAnalyticsManager();
		$am->sendPageviewAnalytics($params['domain'], $params['route']);
		$am->sendEventAnalytics($this->getEntityName(), 'show', $id);
		
		
		$viewParams = $params['viewParams'];
		
		$userRepository = $this->getDoctrine()->getRepository(User::class);
		
		
		
		$searchFilter = new SimpleEntityFilter($userRepository);
		$searchFilter->initValues($request);
		
		$searchFilterForm = $this->createForm(SearchFilterType::class, $searchFilter);
		$searchFilterForm->handleRequest($request);
		
		if ($searchFilterForm->isSubmitted() && $searchFilterForm->isValid()) {
			if ($searchFilterForm->get('search')->isClicked()) {
				return $this->redirectToRoute($this->getSearchRoute(), $searchFilter->getValues());
			}
		}
		$viewParams['searchFilterForm'] = $searchFilterForm->createView();
		
		
		
		$newsletter = new SimpleEntity();
		
		$newsletterForm = $this->createForm(SimpleEntityType::class, $newsletter);
		$newsletterForm->handleRequest($request);
		
		if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
			if ($newsletterForm->get('save')->isClicked()) {
				//TODO return $this->redirectToRoute($this->getSearchRoute(), $searchFilter->getValues());
			}
		}
		$viewParams['newsletterForm'] = $newsletterForm->createView();
	
		
		
		return $this->render($this->getShowView(), $viewParams);
	}
    
	//---------------------------------------------------------------------------
	// Parameters
	//---------------------------------------------------------------------------
	
	protected function getParams(Request $request, array $params) {
		$params = parent::getParams($request, $params);
	
		$cpm = $this->getContextParamsManager($request);
		$params = $cpm->getParams($request, $params);
	
		$apm = $this->getAdvertParamsManager();
		$params = $apm->getParams($request, $params);
	
		$fpm = $this->getFooterParamsManager();
		$params = $fpm->getParams($request, $params);
	
		return $params;
	}
	
	//---------------------------------------------------------------------------
	// Managers
	//---------------------------------------------------------------------------
	
	protected function getContextParamsManager(Request $request) {
		$doctrine = $this->getDoctrine();
	
		return new InfoproduktParamsManager($doctrine);
	}
	
	protected function getAdvertParamsManager() {
		$doctrine = $this->getDoctrine();
		$advertLocations = [Advert::TOP_LOCATION, Advert::SIDE_LOCATION];
	
		return new AdvertParamsManager($doctrine, $advertLocations);
	}
	
	protected function getFooterParamsManager() {
		$doctrine = $this->getDoctrine();
	
		return new FooterParamsManager($doctrine);
	}
	
	
	
	
	protected final function getFilterManager($doctrine) {
		$efm = $this->getEntryFilterManager($doctrine);
		$fm = new InfoproduktFilterManager($efm);
	
		$fm->setFilterByCategories($this->isFilterByCategories());
		$fm->setFilterBySubcategories($this->isFilterBySubcategories());
	
		return $fm;
	}
	
	protected abstract function getEntryFilterManager($doctrine);
	
	
	
	protected function isFilterByCategories() {
		return false;
	}
	
	protected function isFilterBySubcategories() {
		return false;
	}
	
	//---------------------------------------------------------------------------
	// Routes
	//---------------------------------------------------------------------------
	
	protected function getSearchRoute()
	{
		return $this->getDomain() . "_search";
	}
	
	//---------------------------------------------------------------------------
	// Domain
	//---------------------------------------------------------------------------
	
	
    protected function getDomain() {
    	return 'infoprodukt';
    }
}
