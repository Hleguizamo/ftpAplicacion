<?php

namespace FTP\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InventarioProductoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('codigoBarras')
            ->add('descripcion')
            ->add('presentacion')
            ->add('precio')
            ->add('marcado')
            ->add('precioReal')
            ->add('iva')
            ->add('foto')
            ->add('descuento')
            ->add('fechaCreado')
            ->add('fechaActualizado')
            ->add('edicion')
            ->add('fotoTemporal')
            ->add('descuentoTemporal')
            ->add('inicioDescuento')
            ->add('finDescuento')
            ->add('proveedor')
            ->add('linea')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FTP\AdministradorBundle\Entity\InventarioProducto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ftp_administradorbundle_inventarioproducto';
    }
}
