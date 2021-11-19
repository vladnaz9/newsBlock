<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\News;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('body')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_value' => 'name',
            ])
            ->add('tags');
        $builder
            ->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    if ($tagsAsArray == null) {
                        return '';
                    }
                    // transform the array to a string
                    return implode(", ", $tagsAsArray);
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(", ", $tagsAsString);
                }
            ));


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
