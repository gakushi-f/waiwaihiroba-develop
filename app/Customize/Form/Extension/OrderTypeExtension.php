<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Form\Extension;

use Eccube\Common\EccubeConfig;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Eccube\Form\Type\Shopping\OrderType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderTypeExtension extends AbstractTypeExtension
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
            ->add('PointSelect',
                ChoiceType::class,
                [
                    'label' => '利用方法',
                    'data' => '2',
                    'choices' => array('一部のポイントを使う' => '0', '全てのポイントを使う' => '1', 'ポイントを利用しない' => '2'),
                    'required' => true,
                    'mapped' => false,
                    'expanded' => true,
                    'multiple' => false,
                ]
            )            
            ->add('card_first_name', TextType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('card_last_name', TextType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('card_no', TextType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('card_limit_month', TextType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('card_limit_year', TextType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('card_code', TextType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return OrderType::class;
    }
}
