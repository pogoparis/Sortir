<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo',null,["label"=>"Pseudo : "])
            ->add('nom',null,["label"=>"Nom : "])
            ->add('prenom',null,["label"=>"Prénom : "])
            ->add('telephone',null,["label"=>"Téléphone : "])
            ->add('email',null,["label"=>"Email : "])
            ->add('siteEni', EntityType::class,
            ['class' => Site::class,
                'choice_label' =>'nom',
                'label'=>'Site de l\'ENI : '
            ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Le mots de passe doit être le même.',
            'options' => ['attr' => ['class' => 'password-field input input-bordered input-sm w-full max-w-xs m-1']],
            'required' => true,
            'first_options' => ['label' => 'Mots de passe '],
            'second_options' => ['label' => 'Répeter le mots de passe '],
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Merci d\'entrer le mots de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Le mots de passe doit faire moins de {{ limit }} charactères',
                    'max' => 4096,
                ]),
            ],
        ])
            ->add('modifier', SubmitType::class)
            ->add('ajouter', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
