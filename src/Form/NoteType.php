<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Note;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note',IntegerType::class,['attr'=>['class'=>'form-control']])
            ->add('matiere',EntityType::class,['attr'=>['class'=>'form-control'],'class'=> Matiere::class,'choice_label' => 'name'])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
