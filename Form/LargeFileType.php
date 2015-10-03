<?php

namespace Webridge\LargeFileBundle\Form;

use Symfony\Component\Form\FormView,
    Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class LargeFileType extends AbstractType
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('media'));
        $resolver->setRequired(array('mimeTypesMessage'));
        $resolver->setRequired(array('maxSizeMessage'));
        $resolver->setOptional(array('previewContainerId'));
        $resolver->setOptional(array('cancelMessage'));

        $resolver->setDefaults(array(
            'previewContainerId' => '',
            'cancelMessage' => 'Cancel'
        ));
    }

    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $form->getParent()->add(
                    $form->getConfig()->getName() . 'Name',
                    'hidden',
                    array(
                        'mapped' => false
                    )
                );
                $form->getParent()->add(
                    $form->getConfig()->getName() . 'OriginalName',
                    'hidden',
                    array(
                        'mapped' => false
                    )
                );
            }
        );
    }

    /**
     * Pass the file URL to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['view_id_prefix'] = $view->parent->vars['id'] . '_';
        $view->vars['largefile_field'] = $form->getConfig()->getName() . 'Name';
        $view->vars['largefile_field_original'] = $form->getConfig()->getName() . 'OriginalName';
        $view->vars['label'] = $options['label'];
        $view->vars['largefile_media'] = $options['media'];
        $view->vars['mimeTypesMessage'] = $options['mimeTypesMessage'];
        $view->vars['maxSizeMessage'] = $options['maxSizeMessage'];
        $view->vars['previewContainerId'] = $options['previewContainerId'];
        $view->vars['cancelMessage'] = $options['cancelMessage'];
    }

    public function getName()
    {
        return 'largefile';
    }

}