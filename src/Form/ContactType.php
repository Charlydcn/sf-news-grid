<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', ChoiceType::class, [
                'choices' => [
                    'I want to join your team' => 'join_team',
                    'I want a partnership' => 'partnership',
                    'I want an ice-cream' => 'ice_cream',
                    'Other' => 'other'
                ],
                'label' => 'Select your subject<span class="mandatory">*</span>',
                'label_html' => true,
                'attr' => ['class' => 'full-width'],
                'required' => true,
            ])
            
            ->add('first_name', TextType::class, [
                'label' => 'First name<span class="mandatory">*</span>',
                'label_html' => true,
                'attr' => ['class' => 'half-width'],
                'required' => true,    
            ])
            
            ->add('last_name', TextType::class, [
                'label' => 'Last name<span class="mandatory">*</span>',
                'label_html' => true,
                'attr' => ['class' => 'half-width'],
                'required' => true,    
            ])
            
            ->add('country', TextType::class, [
                'label' => 'Country',
                'attr' => ['class' => 'third-width'],
                'required' => false,    
            ])
            
            ->add('email', EmailType::class, [
                'label' => 'E-Mail<span class="mandatory">*</span>',
                'label_html' => true,
                'attr' => ['class' => 'third-width'],
                'required' => true,    
            ])
            
            ->add('phone_number', TextType::class, [
                'label' => 'Phone number',
                'attr' => ['class' => 'third-width'],
                'required' => false,    
            ])
            
            ->add('website', TextType::class, [
                'label' => 'Website',
                'attr' => ['class' => 'two-thirds-width'],
                'required' => false,    
            ])
            
            ->add('company_name', TextType::class, [
                'label' => 'Company name',
                'attr' => ['class' => 'third-width'],
                'required' => false,    
            ])
            
            ->add('content', TextareaType::class, [
                'label' => 'Content<span class="mandatory">*</span>',
                'label_html' => true,
                'attr' => ['cols' => '30', 'rows' => '10', 'class' => 'full-width'],
                'required' => true,    
            ])
            
            ->add('send', SubmitType::class, [
                'label' => 'SEND',
                'attr' => ['class' => 'full-width btn-primary']          
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
