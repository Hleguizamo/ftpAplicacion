<?php

namespace FTP\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransferenciaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('proveedor')
            ->add('codigoProveedor')
            ->add('nitProveedor')
            ->add('carpetaTransferencista')
            ->add('carpetaConvenios')
            ->add('estadoTransferencia')
            ->add('estadoConvenios')
            ->add('encargadoProveedor')
            ->add('emailEncargado')
            ->add('estado')
            ->add('ultimosPedidos')
            ->add('cantidad')
            ->add('ultCargueTransferencista')
            ->add('ultCargueConvenios')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FTP\AdministradorBundle\Entity\Transferencia'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ftp_administradorbundle_transferencia';
    }
}
