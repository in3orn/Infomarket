<?php

namespace AppBundle\Controller\Infomarket;

use AppBundle\Controller\Infomarket\Base\InfomarketController;
use AppBundle\Entity\Article;
use AppBundle\Manager\Entity\Common\ArticleManager;
use AppBundle\Manager\Filter\Common\ArticleFilterManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\NewsletterUser;
use AppBundle\Form\NewsletterUserType;
use AppBundle\Manager\Params\EntryParams\Common\ArticleEntryParamsManager;
use AppBundle\Manager\Entity\Base\EntityManager;
use AppBundle\Manager\Filter\Base\FilterManager;

class ArticleController extends InfomarketController
{   
	//---------------------------------------------------------------------------
	// Actions
	//---------------------------------------------------------------------------
	/**
	 *
	 * @param Request $request
	 * @param integer $page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction(Request $request, $page)
	{
		return $this->indexActionInternal($request, $page);
	}
	
	/**
	 *
	 * @param Request $request
	 * @param integer $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showAction(Request $request, $id, $page)
	{
		return $this->previewActionInternal($request, $id, $page);
	}
	
	//---------------------------------------------------------------------------
	// Internal actions
	//---------------------------------------------------------------------------
	/**
	 *
	 * @param Request $request
	 * @param integer $id current entry ID
	 * @param integer $page page of current entry
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function previewActionInternal(Request $request, $id, $page)
	{
		$params = $this->createParams($this->getShowRoute());
		$params = $this->getPreviewParams($request, $params, $id, $page);
	
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
	
	/**
	 *
	 * @param array $params
	 * @return array
	 */
	protected function getPreviewParams(Request $request, array $params, $id, $page) {
		$params = $this->getParams($request, $params);
	
		$em = $this->getEntryParamsManager();
		$params = $em->getPreviewParams($request, $params, $id, $page);
	
		return $params;
	}
	
	//---------------------------------------------------------------------------
	// Managers
	//---------------------------------------------------------------------------
	
	protected function getInternalEntryParamsManager(EntityManager $em, FilterManager $fm, $doctrine) {
		return new ArticleEntryParamsManager($em, $fm, $doctrine);
	}
	
	protected function getEntityManager($doctrine, $paginator) {
		$tokenStorage = $this->get('security.token_storage');
		return new ArticleManager($doctrine, $paginator, $tokenStorage);
	}
	
	protected function getEntryFilterManager($doctrine) {
		return new ArticleFilterManager($doctrine);
	}
	
	protected function isFilterByCategories() {
		return true;
	}
	
	//---------------------------------------------------------------------------
	// EntityType related
	//---------------------------------------------------------------------------
    /**
     * 
     * {@inheritDoc}
     * @see \AppBundle\Controller\Infomarket\Base\SimpleEntityController::getEntityType()
     */
    protected function getEntityType()
    {
    	return Article::class;
    }
}
