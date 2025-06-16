<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('type', ChoiceType::class, [
                'label' => 'Type de chambre',
                'choices' => [
                    'Simple' => 'simple',
                    'Double' => 'double',
                    'Suite' => 'suite',
                ],
                'placeholder' => 'Choisir un type',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('prix')
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Libre' => 'libre',
                    'Occupée' => 'occupée',
                    'Nettoyage' => 'nettoyage',
                ],
                'placeholder' => 'Choisir un statut',
                'attr' => ['class' => 'form-select'],
            ])

      ->add('photos', FileType::class, [
    'label' => 'Ajouter des photos',
    'mapped' => false,
    'required' => false,
    'multiple' => true,
    'attr' => ['class' => 'form-control'],
    'constraints' => [
        new All([
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                    'mimeTypesMessage' => 'Merci de choisir une image valide (jpg, png, webp)',
                ])
            ]
        ])
    ],
]);
    
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
