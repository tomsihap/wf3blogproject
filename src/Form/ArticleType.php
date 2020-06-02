<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de votre article',
                'data' => 'Titre par défaut',
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'constraints' => [
                    new Length([
                        'min' => '10',
                        'minMessage' => 'Le titre n\'est pas assez long.'
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'data'=> 'Contenu par défaut depuis le articleType'
            ])
            ->add('created_at')
            ->add('short_content')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
