<?php

namespace FTP\AdministradorBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;

use FTP\AdministradorBundle\Entity\sipproveedores\Cliente;
use FTP\AdministradorBundle\Entity\sipproveedores\Inventario;
use FTP\AdministradorBundle\Entity\sipproveedores\Bonificacion;
use FTP\AdministradorBundle\Entity\sipproveedores\InventarioKits;
use FTP\AdministradorBundle\Entity\sipproveedores\DetalleKits;
use FTP\AdministradorBundle\Entity\sipproveedores\Pedidos;

class SIPProveedoresPedidos{


  private $container;
  private $conexionSP;
  private $conexionFTP;
  private $emsp;
  private $log;
  private $logArchivo;
  private $rutaLocal;
  private $rutaServidorFTP;

  public function __construct($container) {

    $this->container = $container;
    $this->conexionSP = $this->container->get('Doctrine')->getManager('sipproveedores')->getConnection();
    $this->conexionFTP = $this->container->get('Doctrine')->getManager()->getConnection();
    $this->emsp = $this->container->get('Doctrine')->getManager('sipproveedores');
    $this->rutaLocal = $container->get('kernel')->getRootDir().'/../web/sip_proveedores/';
  
  }


  public function inicioCargaProveedores($jecutadaDesdeInterfaz=null, $codProveedor=null){

    set_time_limit ( 0 );
    ini_set('memory_limit', '2048M');

    $novedades = array();
    $mensajeNovedad = '';
    $mensajeInterfaz= array();
    
    //Recuperar Proveedores habilitador
    if($codProveedor){
      $proveedores = $this->estadoSipProveedores($codProveedor);
    }else{
      $proveedores = $this->estadoSipProveedores();
    }
    
     //echo "entra servicio";exit('ok');
     
    if( $proveedores ){

      //Se cargan los Asociados disponibles
      $asociados = $this->cargarAsociados();

      //Se recorre cada proveedor para procesar su respectiva carpeta.
      foreach ($proveedores as $k => $proveedor ) {
          
        //SE REALIZA UN BACKUP DE LOS ARCHIVOS DEL PROVEEDOR
//        echo "Backup de archivos del Proveedor:  ".$proveedor['proveedor']."[".$proveedor['codigoCopi']."] \n\n"; 
        $this->backupArchivos( $proveedor );

        $this->log = $this->registralog( 'SIPPROVEEDORES. Proveedor:  '.$proveedor['proveedor'].'['.$proveedor['codigoCopi'].']'  );
//        echo "SIPPROVEEDORES. Proveedor:  ".$proveedor['proveedor']."[".$proveedor['codigoCopi']."] \n\n"; 
        
        //Se cargan los Transferencistas de ese Proveedor.
        $transferencistas = $this->cargarTransferencista( $proveedor[ 'codigoCopi' ] );

        //Se carga el inventario del Proveedor en proceso.
        $inventario = $this->cargarInventario( $proveedor[ 'codigoCopi' ] );
        $kits = $this->cargarKits( $proveedor[ 'codigoCopi' ] );
        $prepack = $this->cargarPrePacks( $proveedor[ 'codigoCopi' ] );


        
        
        
        //SE CARGAN LOS EVENTOS DISPONIBLES DEL PROVEEDOR
//        echo "Carga de eventos disponibles para el  Proveedor:  ".$proveedor['proveedor']."[".$proveedor['codigoCopi']."] \n\n"; 
        $eventos = $this->cargarEventos($proveedor[ 'codigoCopi' ]);

        if( $transferencistas && $inventario ){

          $rutaLocal = $this->rutaLocal;

          //SE ELIMINAN LOS ARCHIVOS QUE ESTAN EN LA CARPETA TEMPORAL
          $temp  = opendir($rutaLocal);

          while($arcTemp = readdir($temp)){

            if(is_file($arcTemp)){
              @unlink($arcTemp);

//              echo "se elimina el archivo ".$arcTemp." se sesion anterior \n";
            }
              
          }


          //Se Recuperan los archivos del proceedor
          $archivos = $this->recuperarArchivos( $proveedor );

          
          /*$rutalocal = "C:\Ampps\www\julianApps\ftp\web\sip_proveedores";
          $archivos[] = "jj-2016-06-15-163503.csv";
          $archivos[]="jj-2016-06-15-170003.csv";
          $archivos[]="jj-2016-06-15-172503.csv";

          $contador=1;
          foreach ($archivos as $archivo ) {
  
              if($contador == 1){
                $this->procesarArchivo($archivo, $asociados, $proveedor, $transferencistas, $inventario,$kits, $prepack );
              }
              $contador++;

            
          }

          exit("termina proceso");*/


          $rep  = opendir($rutaLocal);

          while($arc = readdir($rep)){
              if($arc != '..' && $arc !='.' && $arc !='' && $arc !='backup' ){


                  $archivos[] = $arc; 
//                  echo "Recorreo Archivo ".$arc."\n\n";

                  //se carga los productos de transferencias pendientes
                  $transferencias = $this->validarTransferenciaPendiente($proveedor[ 'codigoCopi' ]);

                  //$this->procesarArchivo($arc, $asociados, $proveedor, $transferencistas, $inventario,$kits, $prepack, $eventos );
                  $mensajeInterfaz[] = $this->procesarArchivo($arc, $asociados, $proveedor, $transferencistas, $inventario,$kits, $prepack, $eventos, $transferencias );


                
            
              }
          }
//exit("exit al primer proveedor");
          //var_dump($archivos);exit();

          /*if( $archivos ){

            $cantArchivos =1;
            foreach ($archivos as $archivo ) {


              $this->procesarArchivo($archivo, $asociados, $proveedor, $transferencistas, $inventario,$kits, $prepack );


              
            }//end foreach

            //se elimina la ruta del proveedor en FTP.
            $this->rutaServidorFTP = null;
            unset($archivos, $archivo);

          }//end if
          */

        }else{
          $mensajeNovedad = 'SIPPROVEEDORES. Se presento un problema al tratar de recuperar los datos del Transferencista ['.count( $transferencistas ).'] o del Inventario ['.count( $inventario ).']';

//          echo 'SIPPROVEEDORES. Se presento un problema al tratar de recuperar los datos del Transferencista ['.count( $transferencistas ).'] o del Inventario ['.count( $inventario ).']';
        }
        unset($transferencistas, $inventario);

        $novedades[] = $mensajeNovedad;

        $mensajeInterfaz[] = $novedades;
        $mensajeNovedad = '';

      }//end foreach
      //exit('SALIO FOREACHPROVEEDORES');
      //$this->registrarNovedad( $novedades );
      unset( $asociados );

    }else{

      $novedades[] = 'No hay proveedores habilitados FTP SIPPROVEEDORES';
      //$this->registrarNovedad( $novedades );

    }

    if($jecutadaDesdeInterfaz){

      return $mensajeInterfaz;

      /*return  $this->container->get('templating')->render('FTPAdministradorBundle:Proveedor:informeTodosProveedorTrans.html.twig', array(
        'mensajeInterfaz' => $mensajeInterfaz
      ));*/
    }

  }//end function


  /**
  * Acción que verifica el estado de los Proveedores para acceder a SipProveedores y los separa en un array con indice [habilitado,deshabilitado]
  * @param $proveedores= array de proveedores recuperados por el sistema.
  * @return array con los proveedores habilitados y deshabilitados.
  * @author JuliÃ¡n casas <j.casas@waplicaciones.com.co>
  * @since 2.6
  * @category FTP\Proveedor
  */
  private function estadoSipProveedores($codProveedor=null){

    $conexionsp = $this->conexionFTP;

    if($codProveedor){
      $query = ' SELECT proveedor, estado_transferencia AS estadoTransferencia, carpeta_transferencista AS carpetaTransferencista, codigo_proveedor AS codigoCopi FROM  proveedores WHERE estado_transferencia = 1 AND codigo_proveedor="'.$codProveedor.'" ';
    }else{
      $query = ' SELECT proveedor, estado_transferencia AS estadoTransferencia, carpeta_transferencista AS carpetaTransferencista, codigo_proveedor AS codigoCopi FROM  proveedores WHERE estado_transferencia = 1  ';
    }

    
   
    //$query = ' SELECT proveedor, estado_transferencia AS estadoTransferencia, carpeta_transferencista AS carpetaTransferencista, codigo_proveedor AS codigoCopi FROM  proveedores WHERE codigo_proveedor = "D69"';
    $aux = $conexionsp->prepare( $query );
    $aux->execute();

    $proveedores = $aux->fetchAll();

    return $proveedores;

  }//end function


  private function cargarAsociados(){

    $conexionsp = $this->conexionSP;

    //cargar Asociados
    $aux = $conexionsp->prepare(' SELECT codigo, centro, ciudad, un_geogra AS geogra, pedidos_sip AS pedidosSip from cliente ');
    $aux->execute();

    $asociados = array();
    foreach ($aux as $v){ 
        $asociados[ (int)$v['codigo'] ]['codigo'] = (int)$v['codigo'];
        $asociados[ (int)$v['codigo'] ]['centro'] = (int)$v['centro'];
        $asociados[ (int)$v['codigo'] ]['ciudad'] = $v['ciudad'];
        $asociados[ (int)$v['codigo'] ]['dep'] = $v['geogra'];

        if($v['pedidosSip'] == 1){
          $asociados[ (int)$v['codigo'] ]['pedidosSip'] = 1;
        }else{
          $asociados[ (int)$v['codigo'] ]['pedidosSip'] = 0;
        }
    }
    unset($aux);


    return $asociados;

  }//end function


  private function cargarTransferencista( $codigoCopi ){

    $conexionsp = $this->conexionSP;

    $proveedor =  $conexionsp->prepare(' SELECT id from proveedor WHERE codigo_copidrogas =  "'.$codigoCopi.'" ');
    $proveedor->execute();
    $proveedor = $proveedor->fetch();

    //cargar transferencistas
    $aux = $conexionsp->prepare(' SELECT id from transferencista WHERE id_proveedor =  "'.$proveedor['id'].'" ');
    $aux->execute();

    $transferencistas = array();
    foreach ($aux as $v) 
     $transferencistas[ $v['id'] ] = $v['id'];

    unset($aux);
    return $transferencistas;

  }//end function


  

  private function cargarInventario( $proveedor ){

    $conexionsp = $this->conexionSP;

    //cargar inventario
    //$aux = $conexionsp->prepare(' SELECT centro, material, denominacion, lote, empaque, cantidad, precio_real, precio_corriente, precio_marcado, impuesto, bonificacion, codigo_barras, fecha_ingreso, disponibilidad, obsequio FROM inventario WHERE proveedor_id = "'.$proveedor.'" GROUP BY  material, centro DESC ');
    //consulta Julian Restrepo
    $productos=$conexionsp->query('SELECT * FROM inventario WHERE proveedor_id = "'.$proveedor.'" ORDER BY precio_real ASC ');


    
    return $productos->fetchAll();

  }//end function


  private function cargarKits( $proveedor ){

    $conexionsp = $this->conexionSP;

    //cargar inventario
    //$aux = $conexionsp->prepare(' SELECT codigo FROM inventario_kits WHERE cod_copi_proveedor = "'.$proveedor.'" GROUP BY  codigo DESC ');

    //CONSULTA DE KITS
    $aux = $conexionsp->prepare(' SELECT * FROM inventario_kits WHERE cod_copi_proveedor = "'.$proveedor.'"  ');
    $aux->execute();

    $kit = array();

    //SE PONE EN COMENTARIO POR QUE SE RETORNA EL RESULTADO LIMPIO DE LA CONSULTAD SQL
    /*foreach ($aux as $v) 
      $kit[ $v['codigo'] ] = $v;

    unset($aux);*/

    //SE ASIGNA EL RESULTADO DE LA CONSULTA A LA VARIABLE $kit
    $kit = $aux;

    return $kit;

  }//end function

  private function cargarPrePacks( $proveedor ){

    $conexionsp = $this->conexionSP;

    //cargar inventario
    //$aux = $conexionsp->prepare(' SELECT codigo FROM inventario_prepacks WHERE cod_copi_proveedor = "'.$proveedor.'" GROUP BY  codigo DESC ');

    //CONSULTA DE PREPACKS
    $aux = $conexionsp->prepare(' SELECT * FROM inventario_prepacks WHERE cod_copi_proveedor = "'.$proveedor.'" ');

    $aux->execute();

    $prepack = array();

    //SE PONE EN COMENTARIO POR QUE SE RETORNA EL RESULTADO LIMPIO DE LA CONSULTAD SQL
    /*foreach ($aux as $v) 
      $prepack[ $v['codigo'] ] = $v;

    unset($aux);*/

    $prepack = $aux;

    return $prepack;

  }//end if


  private function recuperarArchivos( $dato ){

    if( !is_null( $dato[ 'carpetaTransferencista' ] ) && $dato[ 'carpetaTransferencista' ] != '' ){

      //Conectar y descargar los archivos para ese proveedor por FTP
      $archivosFTP = $this->archivosFTP( $dato[ 'carpetaTransferencista' ] );

      $rutaLocal = $this->rutaLocal;
      $rep  = opendir($rutaLocal);    //Abrimos el directorio

      $archivos = array();
      while ($arc = readdir($rep)) {  //Leemos el arreglo de archivos contenidos en el directorio: readdir recibe como parametro el directorio abierto

        if($arc != '..' && $arc !='.' && $arc !=''){

          $archivos[] = $arc; 
      
        }

      }

      closedir($rep);         //Cerramos el directorio
      clearstatcache();     //Limpia la caché de estado de un archivo

      return $archivos;

      //verificar archivo
      /*$pesoMaxPermitido = 3000;
      $fp = @fopen($archivo , 'rb');
      $peso = @filesize($fp) / 1000;
      $cargoInventario = false;*/

    }else{

      return false;

    }

  }//end function


  private function buscarProducto($material,$lote,$centro,$inventarioTemp,$kits,$prepacks){
    $conexionsp = $this->conexionSP;

    //echo "Entra a conultar".$material.">>>>>>>>>>\n\n";

    $productoEncontrado = 0;

    $arrayKit = array();
    $arrayPrepack = array();
    $contar=0;
    foreach($inventarioTemp as $key => $producto){//echo "Entra for";
      $contar++;
      if($lote !=''){
        if($producto['material'] == $material && $producto['lote'] == $lote && $producto['centro'] == $centro){
          return $producto;
        }

      }else{
        //echo "eentra sin lote  ";
        if($producto['material'] == $material && $producto['centro'] == $centro){
          return $producto;
        }

      }
    }
    if($lote!=""){
      foreach($inventarioTemp as $key => $producto){
        if($producto['material'] == $material && $producto['centro'] == $centro){
          return $producto;
        }
      }
    }
    //SE BUSCA EN KITS
    foreach($kits as $key => $kit){

      if($kit['codigo']==$material && $kit['centro']==$centro){

        $detalleKits = $conexionsp->prepare(' SELECT descripcion, campo2  FROM detalle_kits WHERE codigo_kit ='.$kit['codigo'].' AND centro ='.$centro);
        $detalleKits->execute();

        $totalPrecio = 0;
        $cantidadKit = 0;

        foreach($detalleKits as $detalle){
            $totalPrecio += str_replace(" ", "", $detalle['campo2']);
            $cantidadKit += $detalle['descripcion'];
        }

        $kit['cantidadArticulos'] = $cantidadKit;
        $kit['precio_real'] = $totalPrecio;
        $kit['tipo_producto'] = 'kit';


        $kit['centro'] = $kit['centro'];
        $kit['calsificacion'] = '';
        $kit['proveedor'] = $kit['proveedor'];
        $kit['proveedor_id'] = $kit['cod_copi_proveedor'];
        $kit['material'] = $kit['codigo'];
        $kit['denominacion'] = $kit['descripcion'];
        $kit['lote'] = 'Kit';
        $kit['empaque'] = '';
        $kit['cantidad'] = $cantidadKit;
        $kit['precio_real'] = $totalPrecio;
        $kit['precio_corriente'] = $totalPrecio;
        $kit['impuesto'] = '';
        $kit['fecha_ingreso'] = $kit['tiempo'];
        $kit['plazo'] = 'ZPTR';
        $kit['codigo_barras'] = '';
        $kit['tipo_solicitud'] = 1;
        $kit['tipo_ingreso'] = 1;
        $kit['descuentos'] = 0;
        $kit['disponibilidad'] = 0;
        $kit['obsequio'] = 0;
        $kit['tipo_pedido'] = '';
        $kit['expedicion'] = '';
        $kit['iva'] = 0;

        return $kit;
      }
    }

    //SE BUSCA EN PREPACKS
    foreach($prepacks as $key => $prepack){

      if($prepack['codigo']==$material && $prepack['centro']==$centro){

        $detallePrepacks = $conexionsp->prepare(' SELECT cantidad, campo2 FROM detalle_prepacks WHERE codigo_prepack ='.$prepack['codigo'].' AND centro ='.$centro);
        $detallePrepacks->execute();

        $totalPrecioPrepack = 0;
        $cantidadPrepack = 0;

        foreach($detallePrepacks as $detalle){

            $totalPrecioPrepack += str_replace(" ", "", $detalle['campo2']);
            $cantidadPrepack += $detalle['cantidad'];
        }

        $prepack['cantidadArticulos'] = $cantidadPrepack;
        $prepack['precio_real'] = $totalPrecioPrepack;
        $prepack['tipo_producto'] = 'prepack';

        $prepack['centro'] = $prepack['centro'];
        $prepack['calsificacion'] = '';
        $prepack['proveedor'] = $prepack['proveedor'];
        $prepack['proveedor_id'] = $prepack['cod_copi_proveedor'];
        $prepack['material'] = $prepack['codigo'];
        $prepack['denominacion'] = $prepack['descripcion'];
        $prepack['lote'] = 'Kit';
        $prepack['empaque'] = '';
        $prepack['cantidad'] = $cantidadPrepack;
        $prepack['precio_real'] = $totalPrecioPrepack;
        $prepack['precio_corriente'] = $totalPrecioPrepack;
        $prepack['impuesto'] = '';
        $prepack['fecha_ingreso'] = $prepack['tiempo'];
        $prepack['plazo'] = 'ZPTR';
        $prepack['codigo_barras'] = '';
        $prepack['tipo_solicitud'] = 1;
        $prepack['tipo_ingreso'] = 1;
        $prepack['descuentos'] = 0;
        $prepack['disponibilidad'] = 0;
        $prepack['obsequio'] = 0;
        $prepack['tipo_pedido'] = '';
        $prepack['expedicion'] = '';
        $prepack['iva'] = 0;


        return $prepack;
      }
    }

    return null;
  }

  private function procesarArchivo($archivo, $asociados, $proveedor, $transferencistas, $inventarioSQL, $kits, $prepack, $eventos, $transferencias ){

    //var_dump($inventarioSQL->fetchAll());exit();
    $id = $this->log;

    //Conexiones
    $conexionsp = $this->conexionSP;
    $emsp = $this->emsp;
    $conexionftp = $this->conexionFTP;

    $productosEncontrados = $estructuraArchivo = array();
    $novedades = $productosDuplicados = array();
    $reporteFTP = array();
    $drogueriasProcesadas = array();
    $respuestaInterfaz = array();
    $cuentaFilas = 1;
    $mensajeNovedad = '';
    $rutaLocalArchivo = $this->rutaLocal.$archivo;
    $rutaLocalFTP = $this->rutaLocal;
    $archivoFTP = $archivo;
  
    $fp = @fopen($rutaLocalArchivo , 'rb');

    $fecha = new \DateTime('now');
    $fecha = $fecha->format('Y-m-d H:i:s');

    
    //echo "inicia proceso de archivo \n";
    try{
      $query = ' INSERT INTO log_archivos (archivo, peso_mb, proveedor, fecha_procesado, log_admin) VALUES (?, ?, ?, ?, ?)';
      $aux = $conexionftp->prepare( $query );
      $aux->bindValue(1, $archivo);
      $aux->bindValue(2, number_format(( @filesize( $rutaLocalArchivo ) / 1000000),7,'.',',') );  
      $aux->bindValue(3, $proveedor['proveedor'].'('.$proveedor['codigoCopi'].')');
      $aux->bindValue(4, $fecha);
      $aux->bindValue(5, $id);

      $aux->execute();
    }catch(\Exception $e){
      dump($e->getMessage());
    }


    $this->logArchivo = $conexionftp->lastInsertId();
//    echo "se procesa el archivo ".$archivo;//exit();
    $respuestaInterfaz[] = "se procesa el archivo ".$archivo;
    while ($datos = @fgetcsv($fp, 1000, ";")){
      
      $estructuraArchivo[] = 'Fila: '.$cuentaFilas.' ( '.json_encode($datos).' ) ' ;

      if(isset($datos[0],$datos[1],$datos[2],$datos[3],$datos[6],$datos[7])){

        $datos[0] = (int)trim($datos[0]); // Drogueria
        $datos[1] = stripslashes(trim($datos[1])); // Tipo Pedido 
        $datos[2] = (int)(trim($datos[2])); // Material 

//        echo "Producto: ".$datos[0].' - '.$datos[2];
        $datos[3] = (int) trim($datos[3]); // Cantidad 
        $datos[4] = stripslashes(trim($datos[4])); // Lote 
        $datos[6] = stripslashes(trim($datos[6])); // Centro 
        $datos[7] = stripslashes(trim($datos[7])); // Ttransferencista 

        //Se valida si la fila se puede procesar.
        if( $datos[0] && ($datos[3] > 0 && $datos[3] < 999) ){//Para validar si la cantidad corresponde a un entero

//          echo "se busca la drogueria -".$datos[0]."- \n";
          if(isset($asociados[ (int)$datos[0] ])){


            if(isset( $transferencistas[ $datos[7] ] )){
                
                //SE VALIDA SI EL CENTRO DEL PRODUCTO = CENTRO DE LA DROGUERIA
                if($asociados[(int)$datos[0]]['centro'] == $datos[6]){
                    
                    //SE VALIDA SI TIENE EVENTOS ACTIVOS
                    $aprobacionEvento = true;
                    $idEvento=0;
                    $tituloEvento = "";
                    if($datos[1] == "ZPEV"){
                        if(isset($eventos[$datos[6]]) || isset($eventos[ $asociados[(int)$datos[0]]['ciudad'] ]) || isset($eventos[ $asociados[(int)$datos[0]]['dep'] ]) ){
                            $aprobacionEvento = true;

                            if( isset($eventos[$datos[6]]) ){
                              $idEvento = $eventos[$datos[6]]['id'];
                              $tituloEvento = $eventos[$datos[6]]['titulo'];

                            }elseif( isset($eventos[ $asociados[(int)$datos[0]]['ciudad'] ]) ){
                              $idEvento = $eventos[ $asociados[(int)$datos[0]]['ciudad'] ]['id'];
                              $tituloEvento = $eventos[ $asociados[(int)$datos[0]]['ciudad'] ]['titulo'];

                            }elseif( isset($eventos[ $asociados[(int)$datos[0]]['dep'] ]) ){
                              $idEvento = $eventos[ $asociados[(int)$datos[0]]['dep'] ]['id'];
                              $tituloEvento = $eventos[ $asociados[(int)$datos[0]]['dep'] ]['titulo'];

                            }

                        }else{
                            $aprobacionEvento = false;
                        }
                    }else{
                      $datos[1] = "ZPTR";
                    }
                    
                    if($aprobacionEvento){
                        
                        //NUEVA FUNCION DE BUSQUEDA DE PRODUCTOS
//                        echo "cant inventario ".count($inventarioSQL)." - material=".$datos[2]." **";
                        $resultadoBusqueda = $this->buscarProducto($datos[2],$datos[4],$datos[6],$inventarioSQL,$kits,$prepack);


                        if(is_array($resultadoBusqueda)){

                            //$material = $inventario[ $datos[2] ] [ 'material' ];
                            $material = $resultadoBusqueda['material'];

                            if( isset($transferencias[$datos[0]][$material]) ){

                                $mensajeNovedad .= 'Producto registrado y pendiente :'.$datos[2].' en la transferencia: '.$transferencias[$datos[0]][$material].' .Fila : '.$cuentaFilas;
                                $reporteFTP[] = 'Producto registrado y pendiente :'.$datos[2].' en la transferencia: '.$transferencias[$datos[0]][$material];

                                $respuestaInterfaz[] = 'Producto ya registrado y pendiente :'.$datos[2].' en la transferencia: '.$transferencias[$datos[0]][$material].' .Fila : '.$cuentaFilas;

                            }else{

                              if( empty( $productosDuplicados ) || !isset( $productosDuplicados[ $datos[0].$datos[2] ] ) ){

                                foreach($resultadoBusqueda as $key => $prodEncontrado){
                                  $productosEncontrados[ $datos[0].$material ][$key] = $prodEncontrado;
                                }
  //                echo "Registra producto*****    ";
                                $productosEncontrados[ $datos[0].$material ] [ 'cantidadPedida' ] = $datos[3];
                                $productosEncontrados[ $datos[0].$material ] [ 'drogueria' ] = $datos[0];
                                $productosEncontrados[ $datos[0].$material ] [ 'transferencista' ] = $datos[7];
                                $productosEncontrados[ $datos[0].$material ] [ 'plazo' ] = $datos[1];
                                $productosEncontrados[ $datos[0].$material ] [ 'proveedor' ] = $proveedor['proveedor'];
                                $productosEncontrados[ $datos[0].$material ] [ 'codigoCopi' ] = $proveedor['codigoCopi'];

                                $productosDuplicados[ $datos[0].$datos[2] ] = $datos[0].$datos[2];
                                
                                  $drogueriasProcesadas[$datos[0]]['codigo'] = $datos[0];
                                  $drogueriasProcesadas[$datos[0]]['idTransferencista'] = $datos[7];
                                  $drogueriasProcesadas[$datos[0]]['proveedor'] = $proveedor['codigoCopi'];
                                  $drogueriasProcesadas[$datos[0]]['clasificacion'] = $datos[1];
                                  $drogueriasProcesadas[$datos[0]]['idEvento'] = $idEvento;
                                  $drogueriasProcesadas[$datos[0]]['tituloEvento'] = $tituloEvento;
                                  $drogueriasProcesadas[$datos[0]]['pedidosSip'] = $asociados[(int)$datos[0]]['pedidosSip'];

  //                              echo 'Producto insertado: '.$productosEncontrados[ $datos[0].$material ] [ 'material' ]."\n";

                              }else{

                                $mensajeNovedad .= 'Producto duplicado : '.$datos[2].' Drogueria: '.$datos[0];
                                $reporteFTP[] = 'Producto duplicado : '.$datos[2];

                                $respuestaInterfaz[] = 'Producto duplicado : '.$datos[2].' Drogueria: '.$datos[0];

  //                              echo 'Producto duplicado : '.$datos[2].' Drogueria: '.$datos[0]."\n";

                              }


                            }

                            /*if( empty( $productosDuplicados ) || !isset( $productosDuplicados[ $datos[0].$datos[2] ] ) ){

                              foreach($resultadoBusqueda as $key => $prodEncontrado){
                                $productosEncontrados[ $datos[0].$material ][$key] = $prodEncontrado;
                              }
//                echo "Registra producto*****    ";
                              $productosEncontrados[ $datos[0].$material ] [ 'cantidadPedida' ] = $datos[3];
                              $productosEncontrados[ $datos[0].$material ] [ 'drogueria' ] = $datos[0];
                              $productosEncontrados[ $datos[0].$material ] [ 'transferencista' ] = $datos[7];
                              $productosEncontrados[ $datos[0].$material ] [ 'plazo' ] = $datos[1];
                              $productosEncontrados[ $datos[0].$material ] [ 'proveedor' ] = $proveedor['proveedor'];
                              $productosEncontrados[ $datos[0].$material ] [ 'codigoCopi' ] = $proveedor['codigoCopi'];

                              $productosDuplicados[ $datos[0].$datos[2] ] = $datos[0].$datos[2];
                              
                                $drogueriasProcesadas[$datos[0]]['codigo'] = $datos[0];
                                $drogueriasProcesadas[$datos[0]]['idTransferencista'] = $datos[7];
                                $drogueriasProcesadas[$datos[0]]['proveedor'] = $proveedor['codigoCopi'];
                                $drogueriasProcesadas[$datos[0]]['clasificacion'] = $datos[1];
                                $drogueriasProcesadas[$datos[0]]['pedidosSip'] = $asociados[(int)$datos[0]]['pedidosSip'];

//                              echo 'Producto insertado: '.$productosEncontrados[ $datos[0].$material ] [ 'material' ]."\n";

                            }else{

                              $mensajeNovedad .= 'Producto duplicado : '.$datos[2].' Drogueria: '.$datos[0];
                              $reporteFTP[] = 'Producto duplicado : '.$datos[2];

                              $respuestaInterfaz[] = 'Producto duplicado : '.$datos[2].' Drogueria: '.$datos[0];

//                              echo 'Producto duplicado : '.$datos[2].' Drogueria: '.$datos[0]."\n";

                            }*/


                        }else{

                          $mensajeNovedad .= 'Producto inexistente : '.$datos[2].' .Fila : '.$cuentaFilas;
                          $reporteFTP[] = 'Producto inexistente : '.$datos[2];

                          $respuestaInterfaz[] = 'Producto inexistente : '.$datos[2].' .Fila : '.$cuentaFilas;

//                         echo 'Producto inexistente : '.$datos[2].' .Fila : '.$cuentaFilas."\n";

                        }
                        
                    }else{
                        
                        $mensajeNovedad .= 'Drogueria no habilitada para pedido con evento. Fila : '.$cuentaFilas;
                        $reporteFTP[] = 'Drogueria no habilitada para pedido con evento. Fila : '.$cuentaFilas;

                        $respuestaInterfaz[] = 'Drogueria no habilitada para pedido con evento. Fila : '.$cuentaFilas;

//                        echo 'Drogueria no habilitada para pedido con evento. Fila : '.$cuentaFilas."\n";
                    }
                    
                    
                    
                }else{
                    $mensajeNovedad .= 'Producto no valido '.$datos[2].'[centro:'.$datos[6].'] para la drogueria '.$datos[0].'[centro:'.$asociados[(int)$datos[0]]['centro'].'] .Fila : '.$cuentaFilas;
                    $reporteFTP[] = 'Producto no valido '.$datos[2].'[centro:'.$datos[6].'] para la drogueria '.$datos[0].'[centro:'.$asociados[(int)$datos[0]]['centro'].'] .Fila : '.$cuentaFilas;

                    $respuestaInterfaz[] = 'Producto no valido '.$datos[2].'[centro:'.$datos[6].'] para la drogueria '.$datos[0].'[centro:'.$asociados[(int)$datos[0]]['centro'].'] .Fila : '.$cuentaFilas;

//                    echo 'Producto no valido '.$datos[2].'[centro:'.$datos[6].'] para la drogueria '.$datos[0].'[centro:'.$asociados[(int)$datos[0]]['centro'].'] .Fila : '.$cuentaFilas."\n";
                }
                



            }else{
              $mensajeNovedad .= 'Transferencista inexistente : '.$datos[7].' .Fila : '.$cuentaFilas;
              $reporteFTP[] = 'Transferencista inexistente : '.$datos[7];

              $respuestaInterfaz[] = 'Transferencista inexistente : '.$datos[7].' .Fila : '.$cuentaFilas;

//              echo 'Transferencista inexistente : '.$datos[7].' .Fila : '.$cuentaFilas."\n";
            }


          }else{
            $mensajeNovedad .= 'Asociado inexistente : '.$datos[0].' .Fila : '.$cuentaFilas;
            $reporteFTP[] = 'Asociado inexistente : '.$datos[0];

            $respuestaInterfaz[] = 'Asociado inexistente : '.$datos[0].' .Fila : '.$cuentaFilas;

//            echo 'Asociado inexistente : '.$datos[0].' .Fila : '.$cuentaFilas."\n";
          }
          



        }//end if

      }//end if

      $cuentaFilas++;
      $novedades[ ] = $mensajeNovedad;
      $mensajeNovedad = '';

    }// end while

//    echo "se termina de procesar el archivo $archivo \n";

    //Se registra la estructura del archivo
    $this->registrarContenidoArchivo( $estructuraArchivo, $proveedor, $archivo );
 
    //Se registran novedades en el archivo
    //$this->registrarNovedad( $novedades );


    //Si se registraron productos en el array.
    if( $productosEncontrados )
      $this->insertarProducto( $productosEncontrados );



    unset( $productosEncontrados, $estructuraArchivo, $proveedor, $archivo, $productosDuplicados,$novedades );
 
    //Droguerias procesadas. Productos pendientes por confirmar en estado 7.
    //$drogueriasProcesadas = $this->drogueriasProcesadas();

    $pedidosCreados = array();
    foreach ($drogueriasProcesadas as $drogueria){

      $pedidoDescripcion=$emsp->createQueryBuilder()
                              ->select('COUNT(dp.id) totalProductos, SUM((dp.precioReal * dp.cantidadPedida)+((dp.precioReal * dp.cantidadPedida * dp.impuesto)/100))  totalPedido, dp.division')
                              ->from('SipproveedoresEntity:DescripcionPedido','dp')
                              ->where(' dp.estadoPedido=:estadoPedido')
                              ->andWhere('dp.codigoAsociado=:codigoAsociado')
                              ->andWhere('dp.idTransferencista=:idTransferencista')
                              ->setParameter(':estadoPedido',7)
                              ->setParameter(':codigoAsociado',$drogueria['codigo'])
                              ->setParameter(':idTransferencista',$drogueria['idTransferencista'])
                              ->getQuery()
                              ->getOneOrNullResult();


      $pedido=new Pedidos();
      $pedido->setFechaConfirmado(new \DateTime('now'));
      $pedido->setEstado(1);
      $pedido->setTipo('ftp');
      $pedido->setCupo(0);
      $pedido->setCodigoAsociado($drogueria['codigo']);
      $pedido->setCantidadProductos($pedidoDescripcion['totalProductos']);
      $pedido->setPrecioPedido(intval($pedidoDescripcion['totalPedido']));
      $pedido->setCodigoPedido($drogueria['proveedor']);
      $pedido->setIdTransferencista($drogueria['idTransferencista']);


      $datosComplementarios=$emsp->createQueryBuilder()
                              ->select('t.nombre,p.id,p.razonSocial')
                              ->from('SipproveedoresEntity:Transferencista','t')
                              ->Join('t.idProveedor', 'p')
                              ->where('t.id=:id')
                              ->setParameter(':id',$drogueria['idTransferencista'])
                              ->getQuery()
                              ->getOneOrNullResult();

      $pedido->setProveedorId($datosComplementarios['id']);
      $pedido->setProveedorRazonSocial($datosComplementarios['razonSocial']);
      $pedido->setTansferencistaNombre($datosComplementarios['nombre']);

      $division = ( isset($drogueria['division']) )?$drogueria['division']:' ';
      $pedido->setDivisionesTransferencista($division);

      $evento = trim($drogueria['clasificacion']);

      if($evento == 'ZPTR'){
        $pedido->setEvento(0);
      }else{
        $pedido->setEvento($drogueria['idEvento']);
        $pedido->setEventoTitulo($drogueria['tituloEvento']);
      }
      //$evento = ( $evento == 'ZPTR' )?0:-1;
      //$pedido->setEvento( $evento );

      /*if($drogueria['pedidosSip'] == 1){
        $pedido->setPedidosSip(1);
      }*/
      $pedido->setPedidosSip(1);
      
      $emsp->persist($pedido);
      $emsp->flush();

      $pedido->setCodigoPedido($drogueria['proveedor'].'-'.$pedido->getId());
      $emsp->persist($pedido);
      $emsp->flush();
                           
      $emsp->createQueryBuilder()
            ->update('SipproveedoresEntity:DescripcionPedido','dp')
            ->set('dp.estadoPedido','?1')
            ->set('dp.idPedido','?2')
            ->where('dp.estadoPedido=?3 AND dp.idTransferencista=?4 AND dp.codigoAsociado=?5 ')
            ->setParameter(1,'1')
            ->setParameter(2,$pedido->getId())
            ->setParameter(3,7)
            ->setParameter(4,$pedido->getIdTransferencista())
            ->setParameter(5,$drogueria['codigo'])
            ->getQuery()
            ->execute();

      $pedidosCreados[ $pedido->getCodigoPedido() ][ 'asociado' ] = $pedido->getCodigoAsociado();
      $pedidosCreados[ $pedido->getCodigoPedido() ][ 'estado' ] = $pedido->getEstado();
      $pedidosCreados[ $pedido->getCodigoPedido() ][ 'codigoPedido' ] = $pedido->getCodigoPedido();
      $pedidosCreados[ $pedido->getCodigoPedido() ][ 'total' ] = $pedido->getPrecioPedido();
      $pedidosCreados[ $pedido->getCodigoPedido() ][ 'productos' ] = $pedido->getCantidadProductos();
      $pedidosCreados[ $pedido->getCodigoPedido() ][ 'fechaConfirmado' ] = $pedido->getFechaConfirmado()->format('Y-m-d H:i:s');
      $pedidosCreados[ $pedido->getCodigoPedido() ][ 'proveedor' ] = $drogueria['proveedor'];

      $reporteFTP[] = 'Pedido : '.$pedido->getCodigoPedido().' NumProductos:'.$pedido->getCantidadProductos().' Drogueria:'.$pedido->getCodigoAsociado().' Centro:'.$asociados[$pedido->getCodigoAsociado()]['centro'];

      $respuestaInterfaz[] = 'Pedido : '.$pedido->getCodigoPedido().' NumProductos:'.$pedido->getCantidadProductos().' Drogueria:'.$pedido->getCodigoAsociado().' Centro:'.$asociados[$pedido->getCodigoAsociado()]['centro'];

//      echo 'Pedido : '.$pedido->getCodigoPedido().' NumProductos:'.$pedido->getCantidadProductos().' Drogueria:'.$pedido->getCodigoAsociado()." Centro:".$asociados[$pedido->getCodigoAsociado()]['centro']." \n";

      unset($pedido, $pedidoDescripcion);
    }//fin foreach

    $this->logPedidos( $pedidosCreados );

    //exit("termina");

    //Finalizar procesamiento del archivo.
    @fclose( $fp );

    //Enviar el archivo a la carpeta procesados
    $this->cargarAProcesadosFTP( $rutaLocalFTP, $archivoFTP );
    
    //crear archivo novedades FTP.
    $nomFile = 'Reporte_'.$archivoFTP.'_'.date('dmYHis').'.txt';
    $fileHandle = fopen($rutaLocalFTP.$nomFile, 'w');

//    var_dump($reporteFTP);
    foreach ( $reporteFTP as $val )
      fwrite($fileHandle, $val . "\r\n");
        
    fclose($fileHandle); 
    

    $this->cargarAProcesadosFTP( $rutaLocalFTP, $nomFile );

    unset($reporteFTP, $val); 
   
    //Descartar archivo local 
//    echo "se elimina el archivo $rutaLocalArchivo \n";
    @unlink( $rutaLocalArchivo );

    //Descartar archivo de logs local
    //echo "se elimina el archivo ".$rutaLocalFTP.$nomFile." \n";
    @unlink( $rutaLocalFTP.$nomFile );


    //Descartar variables internas.
    unset($drogueriasProcesadas, $drogueria, $pedidosCreados, $ftpNovedades );

    $this->archivo = null;//Atributo que guarda el id de log_archivos creado.

    //echo "se termina de procesar el archivo \n";


    return $respuestaInterfaz;

  }//end function

  
  private function insertarProducto( $productosEncontrados ){
    
    $emsp=$this->conexionSP;

    /*
    $insert = ' INSERT INTO descripcion_pedido (centro, clasificacion, proveedor, proveedor_id, material, denominacion, lote, empaque, cantidad,'
              .' precio_real, precio_corriente, precio_marcado, impuesto, bonificacion, tiempo, fecha_ingreso, plazo, codigo_barras, codigo_asociado,'
              .'cantidad_pedida, id_transferencista, estado_pedido, tipo_solicitud, tipo_ingreso, descuentos, id_pedido, disponibilidad, obsequio, tipo_pedido,'
              .'expedicion, iva ) VALUES';
    */

    $insert = ' INSERT INTO descripcion_pedido (centro, clasificacion, proveedor, proveedor_id, material, denominacion, lote, empaque, cantidad,'
              .' precio_real, precio_corriente, precio_marcado, impuesto, bonificacion, tiempo, fecha_ingreso, plazo, codigo_barras, codigo_asociado,'
              .'cantidad_pedida, id_transferencista, estado_pedido, tipo_solicitud, tipo_ingreso, descuentos, id_pedido, disponibilidad, obsequio, tipo_pedido,'
              .'expedicion, iva,division ) VALUES';

    $values = $plazo = '';
    $i = 1;
    $productosRegistrados = array();
    $limpioPedido = false;

    //echo count($productosEncontrados);exit();


    foreach ($productosEncontrados as $key => $material) {
      //var_dump($material);exit();

      if(!$limpioPedido){

        //Limpiar pedido
        $sql = ' DELETE FROM descripcion_pedido WHERE id_transferencista = "'.$material[ 'transferencista' ].'" AND (estado_pedido = 0 OR estado_pedido =7)  AND codigo_asociado = "'.$material[ 'drogueria' ].'"';
        $emsp->query( $sql );
        $limpioPedido = true;

      }

      if( $values != '' ){
        $values .= ', ';
      }
        $values .= "( ".$material[ 'centro' ].", ";


        $plazo = ( $material[ 'plazo' ] == "ZPEV" )?"ZPEV":"ZPTR";
        $values .= " '".$plazo."', ";

        $values .= " '".$material[ 'proveedor' ]."', ";
        $values .= " '".$material[ 'codigoCopi' ]."', ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " '".$material[ 'codigo' ]."', ";
        }else{
          $values .= " '".$material[ 'material' ]."', ";
        }
        //$values .= " '".$material[ 'material' ][ 'material' ]."', ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " '".$material[ 'descripcion' ]."', ";
        }else{
          $values .= " '".$material[ 'denominacion' ]."', ";
        }
        //$values .= " '".$material[ 'material' ][ 'denominacion' ]."', ";

        if(isset($material['tipo_producto']) && $material['tipo_producto'] == "kit" ){
          $values .= " 'kit', ";
        }elseif(isset($material['tipo_producto']) && $material['tipo_producto'] == "prepack"){
          $values .= " 'prepack', ";
        }else{
          $values .= " '".$material[ 'lote' ]."', ";
        }
        //$values .= " '".$material[ 'lote' ]."', ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " '', ";
        }else{
          $values .= " '".$material[ 'empaque' ]."', ";
        }
        //$values .= " '".$material[ 'material' ][ 'empaque' ]."', ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " '".$material[ 'cantidadArticulos' ]."', ";
        }else{
          $values .= " '".$material[ 'cantidad' ]."', ";
        }
        //$values .= " '".$material[ 'material' ][ 'cantidad' ]."', ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= $material[ 'precio_real' ].", ";
        }else{
          $values .= $material[ 'precio_real' ].", ";
        }
        //$values .= $material[ 'material' ][ 'precio_real' ].", ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= $material[ 'precio_real' ].", ";
        }else{
          $values .= $material[ 'precio_corriente' ].", ";
        }
        //$values .= $material[ 'material' ][ 'precio_corriente' ].", ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " NULL, ";
        }else{
          $values .= $material[ 'precio_marcado' ].", ";
        }
        //$values .= $material[ 'material' ][ 'precio_marcado' ].", ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " '', ";
        }else{
          $values .= " ' ".$material[ 'impuesto' ]." ', ";
        }
        //$values .= " ' ".$material[ 'material' ][ 'impuesto' ]." ', ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " NULL, ";
        }else{
          $values .= $material[ 'bonificacion' ].", ";
        }
        //$values .= $material[ 'material' ][ 'bonificacion' ].", ";

        $values .= " NOW(), ";


        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $fechaIngreso =  new \DateTime( $material[ 'tiempo' ] );
          $values .= " '".$fechaIngreso->format('Y-m-d')."', ";
        }else{
          $fechaIngreso =  new \DateTime( $material[ 'fecha_ingreso' ] );
          $values .= " '".$fechaIngreso->format('Y-m-d')."', ";
        }
        //$fechaIngreso =  new \DateTime( $material[ 'material' ][ 'fecha_ingreso' ] );
        //$values .= " '".$fechaIngreso->format('Y-m-d')."', ";

        $values .= " '".$plazo."', ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " ' ', ";
        }else{
          $values .= " '".$material[ 'codigo_barras' ]."', ";
        }
        //$values .= " '".$material[ 'material' ][ 'codigo_barras' ]."', ";


        $values .= " '".$material[ 'drogueria' ]."', ";

        $values .= $material[ 'cantidadPedida' ].", ";
        //$values .= $material[ 'cantidad' ].", ";
        $values .= $material[ 'transferencista' ].", ";
        $values .= "7, ";
        $values .= "1, ";
        $values .= "1, ";

        $values .= "0, ";
        $values .= "0, ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " '0', ";
        }else{
          $values .= " '".$material[ 'disponibilidad' ]."', ";
        }
        //$values .= " '".$material[ 'material' ][ 'disponibilidad' ]."', ";

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $values .= " '0', ";
        }else{
          $values .= $material[ 'obsequio' ].", ";
        }
        //$values .= $material[ 'material' ][ 'obsequio' ].", ";

        $values .= ' "" '.", ";

        $values .= ' "" '.", ";

        $iva = 0;

        if(isset($material['tipo_producto']) && ($material['tipo_producto'] == "kit" || $material['tipo_producto'] == "prepack")){
          $iva = 0;
        }else{
          
          if (is_numeric(substr( $material[ 'impuesto' ] , 0, 1))){

            $vec = explode('%', $material[ 'impuesto' ] );
            if (!empty($vec[0])){

              if ($vec[0] != 0){

                if(is_numeric($vec[0])){
                    $iva = $vec[0] / 100;
                }
              }
            }
            unset($vec);
          }
        }

        $division = ( isset($material['division']) )?$material['division']:'';
        $values .= "".$iva.", '".$division."' ) ";

        //$values .= "".$iva." ) ";
//        echo "se inserta el prod ".$key." ".$material['material']."\n";
        //var_dump($material);exit();
        
        $productosRegistrados[] = 'SIPPROVEEDORES. Asoc: ['.$material[ 'drogueria' ].'] Mat: '.$material[ 'material' ].', Prov: '.$material[ 'proveedor' ].'['.$material[ 'codigoCopi' ].']'.', Q: '.$material[ 'cantidad' ].', Lote: '.$material[ 'lote' ].', Centro: '.$material[ 'centro' ] ;

        if($i == 1){
          
          $emsp->query( $insert.$values );
          $i = 0;
          $values = '';

        }//end if

      $i++;

    }//end foreach
    
    if( $values != '' ){
      
      $emsp->query( $insert.$values );

    }//end if
//exit();
    $this->registrarProducosInsertados( $productosRegistrados );

  }  //end function


  private function drogueriasProcesadas(){

    //Recuperar Droguerías procesadas.
    $conexionSP=$this->conexionSP;
    $sql=" SELECT DISTINCT `clasificacion`, `proveedor_id` AS proveedor, `codigo_asociado` AS codigo, `id_transferencista` AS idTransferencista FROM `descripcion_pedido` WHERE `estado_pedido` = 7 ";

    $conexionSP->query($sql);
    $sqlDroguerias=$conexionSP->query($sql);
    $sqlDroguerias=$sqlDroguerias->fetchAll();

    $drogueriasProcesadas=array();
    foreach ($sqlDroguerias as $value) {

      $drogueriasProcesadas[$value['codigo'].$value['proveedor'].$value['clasificacion'].$value['idTransferencista']]['codigo']=$value['codigo'];
      $drogueriasProcesadas[$value['codigo'].$value['proveedor'].$value['clasificacion'].$value['idTransferencista']]['proveedor']=$value['proveedor'];
      $drogueriasProcesadas[$value['codigo'].$value['proveedor'].$value['clasificacion'].$value['idTransferencista']]['clasificacion']=$value['clasificacion'];
      $drogueriasProcesadas[$value['codigo'].$value['proveedor'].$value['clasificacion'].$value['idTransferencista']]['idTransferencista']=$value['idTransferencista'];

    }

    unset($sqlDroguerias, $value);

    return $drogueriasProcesadas;

  }//end if

   /**
   * Registra en la tabla de logs las 
   * actividades de los usuarios.
   * @param string $proveedor
   * @param string $rol Rol del usuario
  */
  public function registralog( $proveedor ) {

      $conexion = $this->conexionFTP;
      $ip='';
      
      $fecha = new \DateTime('now');
      
      $sql=' INSERT INTO log_sip_proveedores (fecha, proveedor) VALUES(?,?) ';
      $aux=$conexion->prepare($sql);
      $aux->bindValue(1,$fecha->format('Y-m-d H:i:s'));
      $aux->bindParam(2,$proveedor);
      $aux->execute();

      return $conexion->lastInsertId();

  } 

   /**
   * Registra la estructura del archivo procesado.
   * actividades de los usuarios.
   * @param string $array con campos recorridos del csv
  */
  public function registrarContenidoArchivo( $array, $proveedor, $archivo ) {

    $log = $this->log;
    $archivo = $this->logArchivo;

    $conexion = $this->conexionFTP;

    $insert = ' INSERT INTO log_estructura_archivos (fecha, contenido, log_archivo, log_admin ) VALUES ';
    $values = '';
    $i = 0;

    $fecha = new \DateTime('now');
    $fecha = $fecha->format('Y-m-d H:i:s');

    foreach ( $array as $data ) {
      
      if( $values != '' )
        $values .= ', ';

      $values .= ' ( "'.$fecha.'", ' ;

      $data = str_replace('"','', $data );
      $data = str_replace(',',':', $data );
      $values .= '"'.$data.'", ';

      $values .= $archivo.', ';
      $values .= $log .') ';

      if( $i == 10){
        //dump($insert.$values);exit();
        $conexion->query( $insert.$values );
        $i = 0;  
        $values = '';

      }// end if

      $i++;

    }//end for

    if( $values != '' )
      $conexion->query( $insert.$values );

  } //end function

  /**
   * Registra la estructura del archivo procesado.
   * actividades de los usuarios.
   * @param string $array con campos recorridos del csv
  */
  public function registrarProducosInsertados( $array ) {

      $conexion = $this->conexionFTP;
      $id = $this->log;
 
      $insert = ' INSERT INTO log_productos (fecha, contenido, log_archivo, log_admin ) VALUES ';
      $values = '';
      $i = 0;

      $log = $this->log;
      $archivo = $this->logArchivo;

      $fecha = new \DateTime('now');
      $fecha = $fecha->format('Y-m-d H:i:s');

      foreach ( $array as $data ) {
        
        if( $values != '' )
          $values .= ', ';

        $values .= ' ( "'.$fecha.'", ' ;
        $values .= '"'.$data.'", ' ;
        $values .= $archivo .', ' ;
        $values .= $id .') ' ;

        if( $i == 10){
          //dump($insert.$values);exit();
          $conexion->query( $insert.$values );
          $i = 0;  
          $values = '';

        }// end if

        $i++;

      }//end for

      if( $values != '' )
        $conexion->query( $insert.$values );

  } //end function

  public function registrarNovedad( $novedades ){

    $conexion = $this->conexionFTP;
    $id = $this->log;

    $insert = ' INSERT INTO log_novedades (novedad, log_archivo,fecha , log_admin ) VALUES ';
    $values = '';
    $i = 0;

    $log = $this->log;
    $archivo = $this->logArchivo;

    $fecha = new \DateTime('now');
    $fecha = $fecha->format('Y-m-d H:i:s');

    foreach ( $novedades as $data ) {

      if( $data != '' ){

        if( $values != '' )
          $values .= ', ';

        $values .= '( "'.$data.'", ' ;
        $values .= $archivo .', ' ;
        $values .= 'NOW(), ' ;
        $values .= $id .') ' ;

        if( $i == 10){
          
          $conexion->query( $insert.$values );
          $i = 0;  
          $values = '';

        }// end if

        $i++;

      }//end if

    }//end for

    if( $values != '' )
      $conexion->query( $insert.$values );

  }//end function

  public function logPedidos( $pedidosCreados ){

    $conexion = $this->conexionFTP;
    $id = $this->log;

    $insert = ' INSERT INTO log_proveedores (codigo_drogueria, estado, codigo_pedido, total_pedido, num_productos, fecha_confirmado, proveedor_codigo_copi, log_archivo, log_admin ) VALUES ';
    $values = '';
    $i = 0;

    $log = $this->log;
    $archivo = $this->logArchivo;


    foreach ( $pedidosCreados as $pedido ) {

      if( $values != '' )
        $values .= ', ';

      $values .= '( "'.$pedido[ 'asociado' ].'", ' ;
      $values .= $pedido[ 'estado' ].', ' ;
      $values .= ' "'.$pedido[ 'codigoPedido' ].'", ' ;
      $values .= ' "'.$pedido[ 'total' ].'", ' ;
      $values .= ' "'.$pedido[ 'productos' ].'", ' ;
      $values .= ' "'.$pedido[ 'fechaConfirmado' ].'", ' ;
      $values .= ' "'.$pedido[ 'proveedor' ].'", ' ;
      $values .= ' '.$archivo.', ' ;
      $values .= ' '.$log .') ' ;

      if( $i == 10){
        
        $conexion->query( $insert.$values );
        $i = 0;  
        $values = '';

      }// end if

      $i++;


    }//end for

    if( $values != '' )
      $conexion->query( $insert.$values );

  }

  private function archivosFTP( $carpeta ){

    $container = $this->container;

    $host = $this->container->getParameter('host_ftp');
    $port = $this->container->getParameter('port');
    $user = $this->container->getParameter('user_ftp');
    $pass = $this->container->getParameter('pass_ftp');

    $dir = $this->rutaLocal;
    $arrayArchivosAProcesar = array();

    //conexion a FTP.
    $conexionFTP=@ftp_connect( $host ,$port );
    @ftp_login($conexionFTP, $user, $pass);
  
    if(!@ ftp_chdir( $conexionFTP, $carpeta ) ){

      $raiz=explode('/',$carpeta );

      if(!@ ftp_chdir( $conexionFTP, $raiz[0] ) ){ //Si la carpeta del Laboratorio no existe

        @ftp_mkdir( $conexionFTP, $raiz[0] ); //La carpeta es creada

        @ftp_chdir( $conexionFTP, $raiz[0] ); //Entramos dentro de la carpeta del Labratorio

      }

      if( isset( $raiz[1] ) ){  //si en la variable $carpeta esta el nombre de la carpeta a crear.

        if(!@ ftp_chdir( $conexionFTP, $raiz[1] ) ){ //Si la carpeta no existe es creada

          @ ftp_mkdir( $conexionFTP, $raiz[1] ); //Si la carpeta no existe es creada

          @ ftp_chdir( $conexionFTP, $raiz[1] );//Entramos dentro de la carpeta

        }

      }else{

        //si en la variable $carpeta no contiene el nombre e la careta se crea el folder transferencias.
        if(!@ftp_chdir($conexionFTP,'transferencias')){

          @ftp_mkdir($conexionFTP,'transferencias');

          @ftp_chdir($conexionFTP,'transferencias');//Entramos dentro de la carpeta
    
        }

      }

      if(!@ftp_chdir($conexionFTP,'procesados')){

          @ftp_mkdir($conexionFTP,'procesados');

      }else{

          @ftp_chdir($conexionFTP,'../');

      }

      if(!@ftp_chdir($conexionFTP,'inventario')){

          @ftp_mkdir($conexionFTP,'inventario');

      }else{

          @ftp_chdir($conexionFTP,'../');

      }         
    }

    $ruta=ftp_pwd($conexionFTP);
    $this->rutaServidorFTP = $ruta;

    //Recuperamos los archivos disponibles.
    $documentos = ftp_nlist($conexionFTP, ".");

    if($documentos){

      foreach ($documentos as $file) {

        if(substr($file, -3)=='csv'||substr($file, -3)=='CSV'||
           substr($file, -3)=='TXT'||substr($file, -3)=='txt' ){

          //El archivo se descarga en una carpeta local
          @ftp_get($conexionFTP,$dir.$file,$file, FTP_BINARY);
          @ftp_delete($conexionFTP, $file);

          $arrayArchivosAProcesar[]=$file;

        }
      }//end for
    }//end if 

    ftp_close( $conexionFTP );

    return $arrayArchivosAProcesar;

  }//end function

  private function cargarAProcesadosFTP( $rutaLocalFTP, $archivoFTP ){

    $remoto = $this->rutaServidorFTP.'/procesados/'.$archivoFTP;
    $rutaArchivoLocal = $rutaLocalFTP.'/'.$archivoFTP;

    //Login FTP
    $container = $this->container;

    $host = $this->container->getParameter('host_ftp');
    $port = $this->container->getParameter('port');
    $user = $this->container->getParameter('user_ftp');
    $pass = $this->container->getParameter('pass_ftp');


    //conexion a FTP.
    $conexionFTP = @ftp_connect( $host ,$port );
    @ftp_login($conexionFTP, $user, $pass);

    

    //Carga del archivo a la carpeta del Proveedor FTP.
    @ftp_put($conexionFTP, $remoto, $rutaArchivoLocal ,FTP_BINARY);


    ftp_close( $conexionFTP );

  }//end function
  
  
  private function cargarEventos($codigoProveedor){

    $conexionsp = $this->conexionSP;

    //cargar Eventos disponibles
    
    $fecha = date('Y-m-d H:i');
    $eventos = $conexionsp->query("SELECT ep.aplica_a AS aplicaA, ep.id_evento AS idEvento, e.titulo
            FROM evento e 
            JOIN evento_proveedor ep ON e.id = ep.id_evento 
            JOIN proveedor pr ON ep.id_proveedor = pr.id
            WHERE ('$fecha' BETWEEN e.fecha_inicio AND e.fecha_finalizacion) AND e.estado=1 AND pr.codigo_copidrogas='$codigoProveedor' group by ep.aplica_a ")->fetchAll();
    
    
    $eventosArray = array();
    foreach ($eventos as $v){ 
        
        //$eventosArray[(string)$v['aplicaA']] = $v['aplicaA'];
        $eventosArray[(string)$v['aplicaA']]['id'] = $v['idEvento'];
        $eventosArray[(string)$v['aplicaA']]['titulo'] = $v['titulo'];
    }
    unset($eventos);

    return $eventosArray;

  }//end function
  
  
  private function backupArchivos( $datosProveedor ){

    if( !is_null( $datosProveedor[ 'carpetaTransferencista' ] ) && $datosProveedor[ 'carpetaTransferencista' ] != '' ){

        //Conectar y descargar los archivos para ese proveedor por FTP

        $carpeta = $datosProveedor[ 'carpetaTransferencista' ];
        
        $container = $this->container;

        $host = $this->container->getParameter('host_ftp');
        $port = $this->container->getParameter('port');
        $user = $this->container->getParameter('user_ftp');
        $pass = $this->container->getParameter('pass_ftp');
      
        $dir = $this->rutaLocal;
        $dir = $dir."backup/".$datosProveedor['codigoCopi'];
        
        if(!is_dir($dir)){
            mkdir($dir, 0777);
        }
      
        //conexion a FTP.
        $conexionFTP=@ftp_connect( $host ,$port );
        @ftp_login($conexionFTP, $user, $pass);
      
        
        if(!@ ftp_chdir( $conexionFTP, $carpeta ) ){

            $raiz=explode('/',$carpeta );

            if(!@ ftp_chdir( $conexionFTP, $raiz[0] ) ){ //Si la carpeta del Laboratorio no existe

              @ftp_mkdir( $conexionFTP, $raiz[0] ); //La carpeta es creada

              @ftp_chdir( $conexionFTP, $raiz[0] ); //Entramos dentro de la carpeta del Labratorio

            }

            if( isset( $raiz[1] ) ){  //si en la variable $carpeta esta el nombre de la carpeta a crear.

              if(!@ ftp_chdir( $conexionFTP, $raiz[1] ) ){ //Si la carpeta no existe es creada

                @ ftp_mkdir( $conexionFTP, $raiz[1] ); //Si la carpeta no existe es creada

                @ ftp_chdir( $conexionFTP, $raiz[1] );//Entramos dentro de la carpeta

              }

            }else{

              //si en la variable $carpeta no contiene el nombre e la careta se crea el folder transferencias.
              if(!@ftp_chdir($conexionFTP,'transferencias')){

                @ftp_mkdir($conexionFTP,'transferencias');

                @ftp_chdir($conexionFTP,'transferencias');//Entramos dentro de la carpeta

              }

            }

       
        }

        $ruta=ftp_pwd($conexionFTP);
        $this->rutaServidorFTP = $ruta;

        //Recuperamos los archivos disponibles.
        $documentos = ftp_nlist($conexionFTP, ".");

        if($documentos){

          foreach ($documentos as $file) {

            if(substr($file, -3)=='csv'||substr($file, -3)=='CSV'||
               substr($file, -3)=='TXT'||substr($file, -3)=='txt' ){

              //El archivo se descarga en una carpeta local
              @ftp_get($conexionFTP, $dir."/".$file, $file, FTP_BINARY);

            }
          }//end for
        }//end if 

        ftp_close( $conexionFTP );


      clearstatcache();     //Limpia la caché de estado de un archivo
    }

  }//end function


  private function validarTransferenciaPendiente($codProveedor){


    $conexionsp = $this->conexionSP;

    $sqlProductosPendientes = "SELECT dp.*, p.codigo_pedido
      FROM pedidos p 
      JOIN descripcion_pedido dp ON p.id = dp.id_pedido 
      WHERE p.pedidos_sip=1 and (dp.procesado_sip is null or dp.procesado_sip=0)  AND dp.proveedor_id='".$codProveedor."' ";

    $productosPendientes = $conexionsp->query($sqlProductosPendientes);


    $drogueriaProductos = array();
    foreach($productosPendientes as $productos){
        $drogueriaProductos[$productos['codigo_asociado']][$productos['material']] = $productos['codigo_pedido'];
    }

    return $drogueriaProductos;

  }

}//end class