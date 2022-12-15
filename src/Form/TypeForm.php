<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('choix', ChoiceType::class, [
                'choices'  => [
                    'EHPAD' => 'EHPAD',
                    'Résidence Services Seniors' => 'Résidence Services Seniors'
                ],
            ])
            ->add('chercher', \Symfony\Component\Form\Extension\Core\Type\SearchType::class, [
                'required' => true])
            ->add('rechercher', SubmitType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
