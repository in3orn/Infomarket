<?php

namespace AppBundle\Form\Filter;

use AppBundle\Entity\Filter\Base\SimpleEntityFilter;
use AppBundle\Entity\Filter\MagazineFilter;
use AppBundle\Form\Filter\Base\ImageEntityFilterType;
use AppBundle\Form\Filter\Base\SimpleEntityFilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class MagazineFilterType extends ImageEntityFilterType
{	
	protected function addMoreFields(FormBuilderInterface $builder, array $options) {
	
		$featuredChoices = array(
				'label.all'			=> SimpleEntityFilter::ALL_VALUES,
				'label.featured' 	=> SimpleEntityFilter::TRUE_VALUES,
				'label.notFeatured' => SimpleEntityFilter::FALSE_VALUES
		);
		
		$builder
			->add('featured', ChoiceType::class, array(
					'choices'		=> $featuredChoices,
					'expanded'      => false,
					'multiple'      => false
			))
		;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Entity\Filter\Base\SimpleEntityFilterType::getEntityType()
	 */
	protected function getEntityType() {
		return MagazineFilter::class;
	}
}