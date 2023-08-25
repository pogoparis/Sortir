<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('pseudo',TextType::class,["label" => "Pseudo : ",
                'invalid_message' => 'Le mots de passe doit être le même.',
                'constraints'=> [
                    new NotBlank([
                        'message'=> 'Pseudo manquant.'
                    ]),
                    new Length([
                        'min'=> 3,
                        'minMessage' => 'Le pseudo doit faire au moins {{ limit }} caractères.',
                        'max' => 30,
                        'maxMessage' => 'Le pseudo ne doit pas dépasser {{ limit }} caractères.'
                    ]),

                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('telephone',null,["label"=>"Téléphone (0) : "])
            ->add('email')
            ->add('siteEni', EntityType::class,
                ['class' => Site::class,
                    'choice_label' =>'nom',
                    'label'=>'Site de l\'ENI : '
                ])
            ->add('imageFile', VichImageType::class,[
                'label' => 'Image de profil',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image',
                'download_label' => true,
                'constraints' => [
                    new File ([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG)',
                    ])
                ]
            ])

            ->add('Modifier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
