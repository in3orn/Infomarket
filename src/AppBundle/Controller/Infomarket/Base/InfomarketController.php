<?php

namespace AppBundle\Controller\Infomarket\Base;

use AppBundle\AppBundle;
use AppBundle\Controller\Base\BaseEntityController;
use AppBundle\Entity\Advert;
use AppBundle\Manager\Params\Base\AdvertParamsManager;
use AppBundle\Manager\Params\Base\FooterParamsManager;
use AppBundle\Manager\Params\Infomarket\InfomarketParamsManager;
use AppBundle\Manager\Route\RouteManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Manager\Filter\Infomarket\Base\InfomarketFilterManager;
use AppBundle\Entity\NewsletterUser;
use AppBundle\Form\NewsletterUserType;

abstract class InfomarketController extends BaseEntityController
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
	
	
	
	
// 		$userRepository = $this->getDoctrine()->getRepository(User::class);
	
// 		$searchFilter = new SimpleEntityFilter($userRepository);
// 		$searchFilter->initValues($request);
	
// 		$searchFilterForm = $this->createForm(SimpleEntityFilterType::class, $searchFilter);
// 		$searchFilterForm->handleRequest($request);
	
// 		if ($searchFilterForm->isSubmitted() && $searchFilterForm->isValid()) {
// 			if ($searchFilterForm->get('search')->isClicked()) {
// 				return $this->redirectToRoute($this->getSearchRoute(), $searchFilter->getValues());
// 			}
// 		}
// 		$viewParams['searchFilterForm'] = $searchFilterForm->createView();
	
	
	
		$newsletter = new NewsletterUser();
	
		$newsletterForm = $this->createForm(NewsletterUserType::class, $newsletter);
		$newsletterForm->handleRequest($request);
	
		if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
			if ($newsletterForm->get('save')->isClicked()) {
				$this->subscribe($newsletter);
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
	
		
		
// 		$userRepository = $this->getDoctrine()->getRepository(User::class);
	
// 		$searchFilter = new SimpleEntityFilter($userRepository);
// 		$searchFilter->initValues($request);
	
// 		$searchFilterForm = $this->createForm(SearchFilterType::class, $searchFilter);
// 		$searchFilterForm->handleRequest($request);
	
// 		if ($searchFilterForm->isSubmitted() && $searchFilterForm->isValid()) {
// 			if ($searchFilterForm->get('search')->isClicked()) {
// 				return $this->redirectToRoute($this->getSearchRoute(), $searchFilter->getValues());
// 			}
// 		}
// 		$viewParams['searchFilterForm'] = $searchFilterForm->createView();
	
	
	
		$newsletter = new NewsletterUser();
	
		$newsletterForm = $this->createForm(NewsletterUserType::class, $newsletter);
		$newsletterForm->handleRequest($request);
	
		if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
			if ($newsletterForm->get('save')->isClicked()) {
				$this->subscribe($newsletter);
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
	
		$rm = new RouteManager();
		$lastRoute = $rm->getLastRoute($request, $this->getHomeRoute());
		$lastRouteParams = $lastRoute['routeParams'];
		
		if(!$lastRouteParams) {
			$lastRouteParams = array();
		}
	
		return new InfomarketParamsManager($doctrine, $lastRouteParams);
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
		$fm = new InfomarketFilterManager($efm);
	
		$fm->setFilterByBranches($this->isFilterByBranches());
		$fm->setFilterByCategories($this->isFilterByCategories());
	
		return $fm;
	}
	
	protected abstract function getEntryFilterManager($doctrine);
	
	
	
	protected function isFilterByBranches() {
		return false;
	}
	
	protected function isFilterByCategories() {
		return false;
	}
	
	//---------------------------------------------------------------------------
	// Internal logic
	//---------------------------------------------------------------------------
	protected function subscribe($entry) {
		$em = $this->getDoctrine()->getManager();
	
		$repository = $this->getDoctrine()->getRepository(NewsletterUser::class);
		$persistent = $repository->findOneBy(['name' => $entry->getName()]);
	
		if($persistent) {
			if(!$persistent->getSubscribed()) {
				$persistent->setSubscribed(true);
	
				$em->persist($persistent);
				$em->flush();
			}
		} else {
			$entry->setPublished(true);
			$entry->setSubscribed(true);
	
			$em->persist($entry);
			$em->flush();
		}
	
		$this->addFlash('success', 'success.subscribed');
	}
	
    //---------------------------------------------------------------------------
    // Routes
    //---------------------------------------------------------------------------
    protected function getHomeRoute() {
    	return array('route' => $this->getIndexView(), 'routeParams' => array());
    }
	
    //---------------------------------------------------------------------------
    // Domain
    //---------------------------------------------------------------------------
    protected function getDomain() {
    	return 'infomarket';
    }
}