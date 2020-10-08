<?php

namespace Customize\Form\Type\Front;

use Eccube\Common\EccubeConfig;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class ProductDetailSearchType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Sort', ChoiceType::class, [
                'label' => null,
                'multiple' => false,
                'mapped' => false,
                'expanded' => false,
                'choices' => array('新着' => '0', '高評価' => '1', '低評価' => '2'),
            ])
            ->add('name', SearchType::class, [
                'required' => false,
                'label' => 'common.search_keyword',
                'attr' => [
                    'maxlength' => 50,
                ],
            ]);
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => 'Eccube\Entity\CouponType',
    //     ]);
    // }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'product_detail_search';
    }
}
