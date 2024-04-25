<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isRequired = $options['is_edit'] ? false : true;
        
        $builder

            ->add('title', TextType::class)

            ->add('description', TextareaType::class)

            ->add('img', FileType::class, [
                'label' => 'Image',
                'multiple' => false,
                'data_class' => null,
                'empty_data' => '',
                'attr' => ['accept' => '.png, .jpeg, .jpg, .webp, .svg'],
                'required' => $isRequired,
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'is_edit' => false,
        ]);
    }
}
