<?php

namespace Customize\Form\Extension;

use Eccube\Entity\Category;
use Eccube\Repository\CategoryRepository;
use Eccube\Form\Type\SearchProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchProductTypeExtention extends AbstractTypeExtension
{

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductType constructor.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('Category', ChoiceType::class, [
            'choice_label' => 'Name',
            'multiple' => true,
            'mapped' => false,
            'expanded' => true,
            'choices' => $this->categoryRepository->getList(null, true),
            'choice_value' => function (Category $Category = null) {
                return $Category ? $Category->getId() : null;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return SearchProductType::class;
    }
}
