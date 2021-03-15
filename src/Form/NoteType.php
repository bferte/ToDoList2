<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                "attr" => [
                    "placeholder" => "Titre",
                    "class" => "col-2 border border-primary rounded w-25",
                ]
            ])
            ->add('description', null , [
                "attr" => [
                    "placeholder" => "Description",
                    "class" => "col border border-primary rounded w-50",
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
