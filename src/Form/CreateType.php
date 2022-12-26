<?php

namespace App\Form;

use App\Entity\Sold;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Attachment', FileType::class, [
//                'mapped' => false
            ])
            ->add('Title')
            ->add('Description')
            ->add('Price')
            ->add('Count')
            ->add('SoldOut', ChoiceType::class, [
                'choices'  => [
                    'YES' => true,
                    'NO' => false,
                ]])
            ->add("SUBMIT", SubmitType::class, [
                'attr' =>
                    ['class' => 'btn btn-success float-end']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
