<?php

namespace FTP\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdministradorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('usuario')
            ->add('clave')
            ->add('email')
            ->add('seguimiento')
            ->add('fechaCreado')
            ->add('modificadoId')
            ->add('ultimoIngreso')
            ->add('ultimaIp')
            ->add('estado')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FTP\AdministradorBundle\Entity\Administrador'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ftp_administradorbundle_administrador';
    }
}
