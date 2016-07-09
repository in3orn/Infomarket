<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Form\Base\ImageEntityType;
use AppBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class CategoryType extends ImageEntityType
{
	/**
	 * {@inheritDoc}
	 */
	protected function addMoreFields(FormBuilderInterface $builder, array $options) {
		$builder
			->add('subname', null, array(
					'required' => false
			))
			->add('orderNumber', null, array(
					'required' => false
			))
			->add('icon', null, array(
					'required' => false
			))
			->add('featuredImage', ElFinderType::class, array(
					'instance'=>'featured',
					'required' => false
			))
			->add('parent', EntityType::class, array(
					'class'			=> $this->getEntityType(),
					'query_builder' => function (CategoryRepository $repository) {
						return $repository->createQueryBuilder('e')
						->orderBy('e.name', 'ASC');
					},
					'choice_label' 	=> 'name',
					'required' 		=> false,
					'expanded'      => false,
					'multiple'      => false,
					'placeholder'	=> 'label.choose.category.parent'
			))
			->add('published', null, array(
					'required' => false
			))
			->add('preleaf', null, array(
					'required' => false
			))
			->add('featured', null, array(
					'required' => false
			))
			->add('content', CKEditorType::class, array(
					'config' => array(
							'uiColor' => '#ffffff'),
					'required' => false
			))
			;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => $this->getEntityClass(),
				'choices' => array(),
		));
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass() {
		return Category::class;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityName() {
		return 'category';
	}
	
	protected function getEntityType() {
		return 'AppBundle:Category';
	}
}