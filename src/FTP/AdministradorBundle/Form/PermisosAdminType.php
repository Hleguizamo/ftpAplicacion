<?php

namespace FTP\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PermisosAdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idAdministrador')
            ->add('idPermiso')
            ->add('tipoPermiso')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FTP\AdministradorBundle\Entity\PermisosAdmin'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ftp_administradorbundle_permisosadmin';
    }
}
