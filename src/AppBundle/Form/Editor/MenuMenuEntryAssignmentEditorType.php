<?php

namespace AppBundle\Form\Editor;

use AppBundle\Entity\Menu;
use AppBundle\Entity\MenuEntry;
use AppBundle\Entity\MenuMenuEntryAssignment;
use AppBundle\Form\Editor\Base\BaseEntityEditorType;
use AppBundle\Repository\MenuEntryRepository;
use AppBundle\Repository\MenuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class MenuMenuEntryAssignmentEditorType extends BaseEntityEditorType
{
	/**
	 *
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Base\FormType::addMainFields()
	 */
	protected function addMoreFields(FormBuilderInterface $builder, array $options) {
		
		$builder
		->add('menu', EntityType::class, array(
				'class'			=> Menu::class,
				'query_builder' => function (MenuRepository $repository) {
					return $repository->createQueryBuilder('e')
					->orderBy('e.name', 'ASC');
				},
				'required' 		=> true,
				'expanded'      => false,
				'multiple'      => false,
				'placeholder'	=> 'label.choose.menu'
		))
		->add('menuEntry', EntityType::class, array(
				'class'			=> MenuEntry::class,
				'query_builder' => function (MenuEntryRepository $repository) {
					return $repository->createQueryBuilder('e')
					->orderBy('e.name', 'ASC');
				},
				'required' 		=> true,
				'expanded'      => false,
				'multiple'      => false,
				'placeholder'	=> 'label.choose.menuEntry'
		))
		->add('orderNumber', NumberType::class, array(
				'required' => true
		))
		;
	}
	
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Base\ImageEntityType::getEntityType()
	 */
	protected function getEntityType() {
		return MenuMenuEntryAssignment::class;
	}
}