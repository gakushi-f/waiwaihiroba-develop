<?php

namespace Customize\Form\Extension;

use Eccube\Form\Type\Front\EntryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class EntryTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $builder->get('birth')->getOptions();

        $options['required'] = true;

        $builder
            ->add('birth', BirthdayType::class, $options)
            ->add('user_image', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return EntryType::class;
    }
}
