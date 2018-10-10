<?php

namespace FTP\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PedidoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consecutivo')
            ->add('fecha')
            ->add('codigoDrogueria')
            ->add('estado')
            ->add('totalPesos')
            ->add('productos')
            ->add('numeroCajas')
            ->add('fechaEnviadoProveedor')
            ->add('fechaRecibidoCopidrogas')
            ->add('fechaEnviadoAsociado')
            ->add('numeroFactura')
            ->add('fechaEliminado')
            ->add('cargadoFtp')
            ->add('facturaCopidrogas')
            ->add('remision')
            ->add('numeroProductos')
            ->add('fechaPedido')
            ->add('numeroPedido')
            ->add('fechaFactura')
            ->add('pedidoProcesado')
            ->add('fechaProcesado')
            ->add('proveedor')
            ->add('transferencista')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FTP\AdministradorBundle\Entity\Pedido'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ftp_administradorbundle_pedido';
    }
}
