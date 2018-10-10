<?php

namespace FTP\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zona')
            ->add('codigo')
            ->add('bloqueoD')
            ->add('drogueria')
            ->add('nit')
            ->add('bloqueoR')
            ->add('asociado')
            ->add('nitDv')
            ->add('direcion')
            ->add('codMun')
            ->add('ciudad')
            ->add('ruta')
            ->add('unGeogra')
            ->add('depto')
            ->add('telefono')
            ->add('centro')
            ->add('pS')
            ->add('pCarga')
            ->add('diskette')
            ->add('clienteTiempo')
            ->add('email')
            ->add('emailAsociado')
            ->add('tipoCliente')
            ->add('cupoAsociado')
            ->add('codigoDestinatario')
            ->add('direccion')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FTP\AdministradorBundle\Entity\Cliente'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ftp_administradorbundle_cliente';
    }
}
