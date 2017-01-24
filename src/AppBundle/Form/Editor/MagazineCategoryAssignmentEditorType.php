<?php

namespace AppBundle\Form\Editor;

use AppBundle\Entity\Category;
use AppBundle\Entity\Magazine;
use AppBundle\Entity\MagazineCategoryAssignment;
use AppBundle\Form\Editor\Base\BaseEntityEditorType;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\MagazineRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class MagazineCategoryAssignmentEditorType extends BaseEntityEditorType
{
	/**
	 *
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Base\FormType::addMainFields()
	 */
	protected function addMoreFields(FormBuilderInterface $builder, array $options) {
		$builder
			->add('magazine', EntityType::class, array(
					'class'			=> Magazine::class,
					'query_builder' => function (MagazineRepository $repository) {
						return $repository->createQueryBuilder('e')
						->orderBy('e.name', 'ASC');
					},
					'required' 		=> true,
					'expanded'      => false,
					'multiple'      => false,
					'placeholder'	=> 'label.choose.magazine'
			))
			->add('category', EntityType::class, array(
					'class'			=> Category::class,
					'query_builder' => function (CategoryRepository $repository) {
						return $repository->createQueryBuilder('e')
						->orderBy('e.name', 'ASC');
					},
					'required' 		=> true,
					'expanded'      => false,
					'multiple'      => false,
					'placeholder'	=> 'label.choose.category'
			))
		;
	}
	
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Base\ImageEntityType::getEntityType()
	 */
	protected function getEntityType() {
		return MagazineCategoryAssignment::class;
	}
}