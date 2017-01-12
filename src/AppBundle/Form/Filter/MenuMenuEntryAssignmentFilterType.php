<?php

namespace AppBundle\Form\Filter;

use AppBundle\Entity\Filter\MenuMenuEntryAssignmentFilter;
use AppBundle\Entity\Menu;
use AppBundle\Entity\MenuEntry;
use AppBundle\Form\Filter\Base\BaseEntityFilterType;
use AppBundle\Form\Filter\Base\SimpleEntityFilterType;
use AppBundle\Repository\MenuEntryRepository;
use AppBundle\Repository\MenuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class MenuMenuEntryAssignmentFilterType extends BaseEntityFilterType
{	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Base\BaseFormType::addMoreFields()
	 */
	protected function addMoreFields(FormBuilderInterface $builder, array $options) {
		
		$builder
		->add('menus', EntityType::class, array(
				'class'			=> Menu::class,
				'query_builder' => function (MenuRepository $repository) {
				return $repository->createQueryBuilder('e')
				->orderBy('e.name', 'ASC');
				},
				'required'		=> false,
				'expanded'      => false,
				'multiple'      => true,
				'placeholder'	=> 'label.choose.menu'
		))
		->add('menuEntries', EntityType::class, array(
				'class'			=> MenuEntry::class,
				'query_builder' => function (MenuEntryRepository $repository) {
					return $repository->createQueryBuilder('e')
					->orderBy('e.name', 'ASC');
				},
				'required'		=> false,
				'expanded'      => false,
				'multiple'      => true,
				'placeholder'	=> 'label.choose.menuEntry'
		))
		;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Entity\Filter\Base\SimpleEntityFilterType::getEntityType()
	 */
	protected function getEntityType() {
		return MenuMenuEntryAssignmentFilter::class;
	}
}