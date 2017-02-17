<?php

namespace AppBundle\Form\Editor\Main;

use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use AppBundle\Form\Editor\Base\ImageEntityEditorType;
use AppBundle\Utils\FormUtils;
use Doctrine\Common\Persistence\ObjectManager;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\Editor\Transformer\ArticleToNumberTransformer;
use AppBundle\Form\Editor\Transformer\UserToNumberTransformer;

class ArticleEditorType extends ImageEntityEditorType
{
	protected $em;
	
	public function __construct(ObjectManager $em)
	{
		$this->em = $em;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Base\SimpleEntityType::addMoreFields()
	 */
	protected function addMoreFields(FormBuilderInterface $builder, array $options) {
		
		/** @var ArticleRepository $articleRepository */
		$articleRepository = $this->em->getRepository(Article::class);
		$articles = $articleRepository->findFilterItems();
		
		/** @var UserRepository $userRepository */
		$userRepository = $this->em->getRepository(User::class);
		$users = $userRepository->findFilterItems();
		
		$layoutChoices = array(
				'label.article.layout.left'			=> Article::LEFT_LAYOUT,
				'label.article.layout.mid'			=> Article::MID_LAYOUT,
				'label.article.layout.right'		=> Article::RIGHT_LAYOUT,
				'label.article.layout.bottom'		=> Article::BOTTOM_LAYOUT,
		);
		
		$imageSizeChoices = array(
				'label.article.imageSize.small'			=> Article::SMALL_IMAGE,
				'label.article.imageSize.medium'		=> Article::MEDIUM_IMAGE,
				'label.article.imageSize.large'			=> Article::LARGE_IMAGE,
		);
		
		$builder
		->add('featured', null, array(
				'required' => false
		))
		->add('archived', null, array(
				'required' => false
		))
		->add('subname', TextType::class, array(
				'required' => false
		))
		
		->add('parent', ChoiceType::class, array(
			'choices' 		=> $articles,
			'choice_label' => function ($value, $key, $index) { return FormUtils::getListLabel($value, $key, $index); },
			'choice_translation_domain' => false,
			'required'		=> false,
			'expanded'      => false,
			'multiple'      => false
		))
			
		->add('layout', ChoiceType::class, array(
				'choices'		=> $layoutChoices,
				'expanded'      => false,
				'multiple'      => false
		))
		->add('imageSize', ChoiceType::class, array(
				'choices'		=> $imageSizeChoices,
				'expanded'      => false,
				'multiple'      => false
		))
		->add('page', NumberType::class, array(
				'required' => false
		))
		->add('orderNumber', NumberType::class, array(
				'required' => false
		))
		
		->add('intro', CKEditorType::class, array(
				'config' => array(
						'uiColor' => '#ffffff'),
				'required' => false
		))
		->add('content', CKEditorType::class, array(
				'config' => array(
						'uiColor' => '#ffffff'),
				'required' => false
		))
		
		->add('date', DateTimeType::class, array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy HH:mm',
				'required' => true,
				'attr' => [
						'class' => 'form-control input-inline datetimepicker',
						'data-provide' => 'datetimepicker',
						'data-date-format' => 'DD/MM/YYYY HH:mm',
						'placeholder' => 'label.article.date'
				]
		))
		->add('endDate', DateTimeType::class, array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy HH:mm',
				'required' => false,
				'attr' => [
						'class' => 'form-control input-inline datetimepicker',
						'data-provide' => 'datetimepicker',
						'data-date-format' => 'DD/MM/YYYY HH:mm',
						'placeholder' => 'label.article.endDate'
				]
		))
		->add('author', ChoiceType::class, array(
				'choices' 		=> $users,
				'choice_label' => function ($value, $key, $index) { return FormUtils::getListLabel($value, $key, $index); },
				'choice_translation_domain' => false,
				'required'		=> false,
				'expanded'      => false,
				'multiple'      => false
		))
		;
		
		$builder->get('parent')->addModelTransformer(new ArticleToNumberTransformer($this->em));
		$builder->get('author')->addModelTransformer(new UserToNumberTransformer($this->em));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \AppBundle\Form\Base\SimpleEntityType::getEntityType()
	 */
	protected function getEntityType() {
		return Article::class;
	}
}