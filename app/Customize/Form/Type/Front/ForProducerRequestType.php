<?php

namespace Customize\Form\Type\Front;

use Eccube\Common\EccubeConfig;

use Symfony\Component\Form\AbstractType;
use Eccube\Form\Type\RepeatedEmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ForProducerRequestType extends AbstractType
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
            ->add('email', RepeatedEmailType::class)
            ->add('policy_check', CheckboxType::class, [
                'required' => true,
                'label' => null,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('alcohol_check', CheckboxType::class, [
                'required' => true,
                'label' => null,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('nagano_check', CheckboxType::class, [
                'required' => true,
                'label' => null,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(),
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
        return 'for_producer';
    }
}
