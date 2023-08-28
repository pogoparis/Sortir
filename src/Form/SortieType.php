<?php

namespace App\Form;


use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class, array(
                'widget' => 'single_text',))
            ->add('dateHeureFin', DateTimeType::class, array(
                'widget' => 'single_text',))
            ->add('dateLimiteInscription', DateTimeType::class, array(
                'widget' => 'single_text',))
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('lieu', EntityType::class, [
                        'class' => Lieu::class,
                        'choice_label' => 'nom'
                    ])
            ->add('ajouter', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-neutral m-1',
                    ],
                    ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
