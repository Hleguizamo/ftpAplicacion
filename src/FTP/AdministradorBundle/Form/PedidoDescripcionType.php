<?php

namespace FTP\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PedidoDescripcionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pdtoId')
            ->add('drogueriaId')
            ->add('cantidadPedida')
            ->add('productoCodigo')
            ->add('productoCodigoBarras')
            ->add('productoDescripcion')
            ->add('productoPresentacion')
            ->add('productoPrecio')
            ->add('productoMarcado')
            ->add('productoReal')
            ->add('productoIva')
            ->add('productoDescuento')
            ->add('productoFecha')
            ->add('productoEstado')
            ->add('cantidadFinal')
            ->add('productoFoto')
            ->add('linea')
            ->add('transferencista')
            ->add('pedido')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FTP\AdministradorBundle\Entity\PedidoDescripcion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ftp_administradorbundle_pedidodescripcion';
    }
}
