<?php

namespace AppBundle\Controller\Infoprodukt;

use AppBundle\Controller\Infoprodukt\Base\SimpleEntityController;
use AppBundle\Entity\Category;
use AppBundle\Entity\Filter\ProductFilter;
use AppBundle\Entity\Product;
use AppBundle\Entity\Segment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Brand;
use AppBundle\Entity\User;

class ProductController extends SimpleEntityController
{   
	/**
	 * 
	 * @param Request $request
	 */
	public function indexAction(Request $request, $page)
	{
		return $this->indexActionInternal($request, $page);
	}
	
	/**
	 * 
	 * @param Request $request
	 * @param unknown $id
	 */
	public function showAction(Request $request, $id)
	{
		return $this->showActionInternal($request, $id);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Controller\Infomarket\Base\BaseEntityController::getEntityType()
	 */
    protected function getEntityType()
    {
    	return Product::class;
    }
    
    protected function getEntityFilter(Request $request)
    {
    	$filter = parent::getEntityFilter($request);
    
    	$category = $this->getParamById($request, Category::class, null);
    	if($category) {
    		$filter->setCategories([$category]);
    	}
    
    	return $filter;
    }
    
    protected function createNewFilter() {
    	$userRepository = $this->getDoctrine()->getRepository(User::class);
    	$categoryRepository = $this->getDoctrine()->getRepository(Category::class);
    	$segmentRepository = $this->getDoctrine()->getRepository(Segment::class);
    	$brandRepository = $this->getDoctrine()->getRepository(Brand::class);
    
    	return new ProductFilter($userRepository, $categoryRepository, $brandRepository, $segmentRepository);
    }
}
