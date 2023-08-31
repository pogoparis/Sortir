<?php

namespace App\Form;

use App\Entity\Filtre;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreFormType extends AbstractType
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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

            ->add('site', EntityType::class ,
                ['class' => Site::class,
                    'placeholder' => 'Choisir votre site',
                    'required'   => false,
                    'choice_label' => 'nom'
                ]

            );


            if ($this->security->isGranted('IS_AUTHENTICATED')) {
                $builder
                    ->add('organisateur', CheckboxType::class, [
                        'label' => "Sorties dont je suis l'organisateur",
                        'required' => false,
                    ])
                    ->add('inscrit', CheckboxType::class, [
                        'label' => "Sorties auxquelles je suis inscrit",
                        'required' => false,
                    ])
                ->add('sortiesPassees', CheckboxType::class, [
                    'label' => "Sorties déjà passées",
                    'required' => false,
                ]);
            }  else {
                // si l'utilisateur n'est pas connecté les checkbox ne s'affichent pas.
                $builder
                    ->add('organisateur', CheckboxType::class, [
                        'attr' => [
                            'style' => 'display: none;',
                        ],
                        'label' => false,
                        'required' => false,
                        'data' => false,
                    ])
                    ->add('inscrit', CheckboxType::class, [
                        'attr' => [
                            'style' => 'display: none;',
                        ],
                        'label' => false,
                        'required' => false,
                        'data' => false,
                    ]);
            }
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