<?php

namespace App\Form;

use App\Entity\Filtre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Rechercher'
                    ]]
            )
            // Recherche par Date
            ->add('dateMin', DateTimeType::class,
            [
                'widget' => 'single_text',
                'required'   => false,
            ])
            ->add('dateMax', DateTimeType::class,
            [
                'widget' => 'single_text',
                'required'   => false,
            ])
            // Checkbox Organisateur
            ->add('organisateur', CheckboxType::class, [
                'label' => "Sorties dont je suis l'organisateur",
                'required' => false,
            ])
            // Checkbox Inscrit
            ->add('inscrit', CheckboxType::class, [
                'label' => "Sorties auxquelles je suis inscrit",
                'required' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filtre::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

        public function getBlockPrefix()
        {
            return '';
        }


}