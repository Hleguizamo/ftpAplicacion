<?php

namespace FTP\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AceptacionContratoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoDrogeria')
            ->add('nitAsociado')
            ->add('estado')
            ->add('clave')
            ->add('tiempo')
            ->add('pruebas')
            ->add('ultimoAcceso')
            ->add('cambioClave')
            ->add('clave1')
            ->add('clave2')
            ->add('ultimaClave')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FTP\AdministradorBundle\Entity\AceptacionContrato'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ftp_administradorbundle_aceptacioncontrato';
    }
}
