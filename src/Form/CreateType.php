<?php

namespace App\Form;

use App\Entity\Sold;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Console\Descriptor\TextDescriptor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('Description',TextareaType::class,[
                'attr' => ['required'   => false,]
            ])
            ->add('Price', MoneyType::class, [
                'attr' => ['required'   => false,],

            ])
            ->add('Count', null, [
                'attr' => ['required'   => false,]
            ])
            ->add('SoldOut', ChoiceType::class, [
                'choices'  => [
                    'YES' => true,
                    'NO' => false,
                ],
                'attr' => ['required'   => false]])

            ->add("Submit", SubmitType::class, [
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
