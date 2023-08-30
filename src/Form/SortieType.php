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
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentDateTime = new \DateTime();

        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class, array(
                'widget' => 'single_text',
                'attr' => ['min' => $currentDateTime->format('Y-m-d\TH:i')],
                ))
            ->add('dateHeureFin', DateTimeType::class, array(
                'widget' => 'single_text',
                'attr' => ['min' => $currentDateTime->format('Y-m-d\TH:i')],
                ))
            ->add('dateLimiteInscription', DateTimeType::class, array(
                'widget' => 'single_text',
                'attr' => ['min' => $currentDateTime->format('Y-m-d\TH:i')],
                ))
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
                    ])
            ->add('imageFile', VichImageType::class,[
                'label' => 'Image Sortie',
                'required' => false,
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
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
