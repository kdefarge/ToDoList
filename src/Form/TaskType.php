<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;

class TaskType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', TextareaType::class)
            ->add('author', TextType::class, [
                'disabled' => true,
            ])
            ->setDataMapper($this)
        ;
    }

    public function mapDataToForms($viewData, $forms): void
    {
        if (!$viewData instanceof Task) {
            throw new UnexpectedTypeException($viewData, Color::class);
        }

        /** @var FormInterface $forms */
        $forms = iterator_to_array($forms);

        $forms['title']->setData($viewData->getTitle());
        $forms['content']->setData($viewData->getContent());
        $forms['author']->setData($viewData->getAuthor()->getUsername());
    }

    public function mapFormsToData($forms, &$viewData): void
    {
        /** @var FormInterface $forms */
        $forms = iterator_to_array($forms);

        /** @var Task $viewData */
        $viewData->setTitle($forms['title']->getData());
        $viewData->setContent($forms['content']->getData());
    }
}
