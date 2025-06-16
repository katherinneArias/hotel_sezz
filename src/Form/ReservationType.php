<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'label' => 'Date d’arrivée',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de départ',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('prixTotal', null, [
                'label' => 'Prix total (€)',
                'attr' => ['class' => 'form-control', 'readonly' => true]
            ])
            ->add('chambre', EntityType::class, [
                'class' => Chambre::class,
                'choice_label' => function (Chambre $chambre) {
                    return sprintf('Chambre %s (%s €)', $chambre->getNumero(), $chambre->getPrix());
                },
                'choice_attr' => function (Chambre $chambre) {
                    return ['data-prix' => $chambre->getPrix()];
                },
                'label' => 'Chambre',
                'attr' => ['class' => 'form-select chambre-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}


