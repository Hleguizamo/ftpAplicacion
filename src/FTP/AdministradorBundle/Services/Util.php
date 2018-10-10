<?php
namespace FTP\AdministradorBundle\Services;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use FTP\AdministradorBundle\Entity\LogAdmin;
class Util {

    /**
     * Contenedor de servicios
     */
    private $container;

    /**
     * Costructor de la clase
     */
    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * Registra en la tabla de logs las 
     * actividades de los usuarios.
     * @param string $actividad Actividad que realizo el usuario
     * @param string $rol Rol del usuario
     */
    public function registralog($actividad,$id) {
        $conexion = $this->container->get('Doctrine')->getManager()->getConnection();
        $ip = $this->container->get('request')->getClientIp();
        $fecha = new \DateTime('now');
        
        $sql=' INSERT INTO log_administrador VALUES(null,?,?,?,?) ';
        $aux=$conexion->prepare($sql);
        $aux->bindParam(1,$id);
        $aux->bindValue(2,$fecha->format('Y-m-d H:i:s'));
        $aux->bindParam(3,$actividad);
        $aux->bindParam(4,$ip);
        $aux->execute();

    }

    /**
     * Registra en la tabla de logs las 
     * actividades de los proveedores.
     */
    public function registralogProveedor($archivo,$transacciones,$codigoDrogueria,$estado,$pedido,$totalPedido,$numProductos,$proveedorId) {
        
        $conexion = $this->container->get('Doctrine')->getManager()->getConnection();
        $sql=' INSERT INTO log_proveedores (nombre_archivo,transacciones,codigo_drogueria,estado,codigo_pedido,total_pedido,num_productos,fecha_confirmado,proveedor_id) VALUES(?,?,?,?,?,?,?,?,?)';
       
        //echo $proveedorId;exit();
        $fechaConfirmado = new \DateTime('now');
        $fechaConfirmado=$fechaConfirmado->format('Y-m-d H:i:s');
        $aux=$conexion->prepare($sql);
        $aux->bindParam(1,$archivo);
        $aux->bindParam(2,$transacciones);
        $aux->bindParam(3,$codigoDrogueria);
        $aux->bindParam(4,$estado);
        $aux->bindParam(5,$pedido);
        $aux->bindParam(6,$totalPedido);
        $aux->bindParam(7,$numProductos);
        $aux->bindParam(8,$fechaConfirmado);
        $aux->bindParam(9,$proveedorId);
        $aux->execute();

    }

    /**
     * Funcion para enviar correos.
     * @param string $remitente Email del remitente
     * @param array $destinatarios Lista de destinatarios
     * @param string $body Cuerpo del mensaje
     * @param string $asunto Asunto del mensaje
     * @param array $adjuntos Arrary con los path de los archivos adjuntos.
     * Ejemeplo: $adjuntos = array('/path/to/image.jpg', 'image/jpeg'), 
     * 
     * si son mas de un destinatario el array debe tener dos posiciones, 
     * el destinatario y los adjuntos de ese destinatario.
     * Ejemeplo: $adjuntos = array('ej1@mail.com' => array('/path/to/image1.jpg', 'image/jpeg1'),
     *                             'ej2@mail.com' => array('/path/to/image2.jpg', 'image/jpeg2') ), 
     */
    public function enviarCorreo($remitente, $destinatarios, $body = '', $asunto = '', $adjuntos = array(), 
        $Cc = array()) {
        $n_destinatarios = count($destinatarios);
        foreach ($destinatarios as $destinatario) {

            $message = \Swift_Message::newInstance()
                    ->setSubject($asunto)
                    ->setFrom($remitente)
                    ->setTo($destinatario)
                    ->setBody($body)
                    ->setCc($Cc)
                    ->setContentType('text/html');

            if (count($adjuntos) > 0) {
                if ($n_destinatarios > 1) {
                    foreach ($adjuntos[$destinatario] as $path) {
                        $attachment = \Swift_Attachment::fromPath($path);
                        $message->attach($attachment);
                    }
                } else {
                    foreach ($adjuntos as $path) {
                        $attachment = \Swift_Attachment::fromPath($path);
                        $message->attach($attachment);
                    }
                }
            }

            $this->container->get('mailer')->send($message);
        }
    }

    /**
     * Verifica los permisos del usuario.
     * @param string $controlador Permiso al que se intenta eccesar
     * @param int $nivel Nivel de permiso.
     * Permisos:
     * 1 Adminsitrador total, listado, creacion, edicion, eliminacion etc..
     * 2 Administrador lectura y edicion.
     * 3 Administrador consulta solo lectura de los datos.
     */
    public function seguridad_http($contolador, $nivel, $permisos) {
        
        if(!isset($permisos[$contolador]['permiso'])) {
            return false;
        }else {
            if ($permisos[$contolador]['permiso'] <= $nivel) {
                
            } else {
                if(!$this->validarSesion()){
                        return false;
                }
                return false;
            }
        }
        return true;
    }
    
    /**
     * Retorna un array con los atributos de configuracion
     * valores del año correspondiente
     * @param string $anio Año en caso que esta variable no sea pasada
     * se tomara el año actual 
     */
    public function getConfiguracion($anio = null){
    	$em = $this->container->get('Doctrine')->getManager();
    	$anio = ($anio == null)?date('Y'):$anio;
    	return $em->createQueryBuilder()
    		->select('DISTINCT c.id as config_id, c.nombre as nombre, ca.id as config_anio_id, '.
    				'ca.valor as valor, td.nombre as tipo_campo')
    		->from('WABackendBundle:ConfiguracionAnio', 'ca')
    		->join('ca.configuracion','c')
    		->join('ca.anio','a')
    		->join('ca.tipoDato','td')
    		->where('a.anio = '.$anio)
    		->getQuery()->execute();
    }
    
    /**
     * Limpia una cadena de texto de caracteres especiales y espacios
     * @param string $cadena URL o cadena de texto que se desea limpiar
     * @param unknown_type $separador Separador para los espacios
     * @return string cadena de texto limpia
     * @author Emmanuel Camacho <desarrollo1@waplicaciones.co>
     */
    public function getSlug($cadena, $separador = '_') {
    	$slug = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
    	$slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
    	$slug = strtolower(trim($slug, $separador));
    	$slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
    	return $slug;
    }
    
    /**
     * Valida la sesion del usuario
     * @author Emmanuel Camacho <desarrollo1@waplicaciones.co>
     */
    public function validarSesion(){
    	$valid = '';
    	$sesion = $this->container->get('session');
    	$lastTime = $sesion->get('last_time');
    	$lifetime = $sesion->getMetadataBag()->getLifetime();
    	if(time() < ($lastTime + $lifetime)){
    		$sesion->set('last_time', time());
    		$valid = true;
    	}else{
    		$sesion->invalidate();
    		$valid = false;
    	}
    	return $valid;
    }
    
    /**
     * Encripta el texto enviado
     * @param string $raw texto a encriptar 
     * @param string $salt (opcional) salt
     * @return string valor encriptado
     */
    public function encodePassword($raw, $salt = ''){
    	$iteraciones = $this->container->getParameter('wa.clave_iteraciones');
    	$encoder = new MessageDigestPasswordEncoder('sha512',true,$iteraciones);
    	return $encoder->encodePassword($raw, $salt);
    }
    
    /**
     * Valida si un password es valido
     * @param string $encoded string codificado
     * @param string $raw string a comparar
     * @param string $salt (opcional) salt
     * @return bool true si es valido false en caso contrario
     * @author Emmanuel Camacho <ecamacho@waplicaciones.co>
     */
    public function isPasswordValid($encoded, $raw, $salt = ''){
    	$iteraciones = $this->container->getParameter('wa.clave_iteraciones');
    	$encoder = new MessageDigestPasswordEncoder('sha512',true,$iteraciones);
    	return $encoder->isPasswordValid($encoded, $raw, $salt);
    }
    
    
    
    
    public function encrypt($string) {
        $key="ticket";
        $result = '';
        for($i=0; $i<strlen($string); $i++) {
           $char = substr($string, $i, 1);
           $keychar = substr($key, ($i % strlen($key))-1, 1);
           $char = chr(ord($char)+ord($keychar));
           $result.=$char;
        }
        return base64_encode($result);
    }
    public function decrypt($string) {
        $key="ticket";
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
           $char = substr($string, $i, 1);
           $keychar = substr($key, ($i % strlen($key))-1, 1);
           $char = chr(ord($char)-ord($keychar));
           $result.=$char;
        }
        return $result;
    }

    public function totalPedido($drogueriaId, $proveedor, $limit=0,$em,$nitProveedor) {

        if($limit>0){
          $q = $em->createQuery('SELECT count(pd.cantidadPedida) AS pedidos, SUM(
              (pd.productoPrecio * pd.cantidadPedida) + 
              ((pd.productoPrecio * pd.cantidadPedida) * pd.productoIva/100 ) -
              (   ( ( (pd.productoPrecio * pd.cantidadPedida) + ((pd.productoPrecio * pd.cantidadPedida) * pd.productoIva/100 ) )*ip.descuento)/100    )
              ) AS total 
            FROM FTPAdministradorBundle:InventarioProducto ip 
            LEFT JOIN ip.proveedor p 
            LEFT JOIN ip.linea l 
            LEFT JOIN FTPAdministradorBundle:PedidoDescripcion pd WITH pd.pdtoId = ip.id AND pd.drogueriaId=:drogueriaId '
                  . ' AND pd.productoEstado=10 WHERE p.id =:proveedor LIMIT '.$limit);
          $q->setParameter('drogueriaId', $drogueriaId);
          $q->setParameter('proveedor', $proveedor);
          foreach ($q->getResult() as $result) {
            $productos=$result['pedidos'];
            $total=$result['total'];
          }
        }
        else{
            $q = $em->createQuery(' SELECT count(pd.cantidadPedida) AS pedidos, SUM(
                (pd.productoPrecio * pd.cantidadPedida) + 
                ((pd.productoPrecio * pd.cantidadPedida) * pd.productoIva/100 ) -
                (   ( ( (pd.productoPrecio * pd.cantidadPedida) + ((pd.productoPrecio * pd.cantidadPedida) * pd.productoIva/100 ) )*ip.descuento)/100    )
                ) AS total 
              FROM FTPAdministradorBundle:InventarioProducto ip 
              LEFT JOIN ip.proveedor p 
              LEFT JOIN ip.linea l 
              LEFT JOIN FTPAdministradorBundle:PedidoDescripcion pd WITH pd.pdtoId = ip.id AND pd.drogueriaId=:drogueriaId AND p.codigoCopidrogas=?1 '
                    . ' AND pd.productoEstado=10 WHERE pd.productoEstado=10');
            $q->setParameter('drogueriaId', $drogueriaId);
            $q->setParameter(1, $nitProveedor);
                    
            $aux=$q->getResult();
            //dump($aux);exit;
           // foreach ($q->getResult() as $result) {
                $productos=$aux[0]['pedidos'];
                $total=$aux[0]['total'];
            //}
        }
        $totalProductos=($productos=="")?0:$productos;
        $regresa['pedidos']=$totalProductos;
        $regresa['total']=intval($total);
        return $regresa;
        
    }


    public function retornoEstadoCupo($codigoAsociado){
        $em = $this->container->get('Doctrine')->getManager('sipasociados');
        /* consumo de web service*/
        $cliente = $em->getRepository('FTPAdministradorBundle:Cliente')->findOneByCodigo($codigoAsociado);
        $cupo = $cupoActual = $estadoCupo = '';
        if ($cliente->getWebservice()) {
            $cupoWS = $this->consultaCupoWS($codigoAsociado);
            if($cupoWS['codigoRetono']=='0' || $cupoWS['codigoRetono']=='4'){
                $cupo=$cupoWS['cupo'];
                $cupoActual=$cupoWS['cupo'];
                $estadoCupo=$cupoWS['mensajeRetorno'];
            }else{
                $cupo=$cupoWS['cupo'];
                $cupoActual=$cupoWS['cupo'];
                $estadoCupo=$this->estadoCuopoLetras($cupoWS['codigoRetono']);
            }
        }else{
            $cupo=$cliente->getCupoAsociado();
            $cupoActual=$cliente->getCupoAsociado();
            $estadoCupo=$this->estadoCuopoLetras($cliente->getBloqueoR());
        }
        return array('cupo'=>$cupo,'cupoActual'=>$cupoActual,'estadoCupo'=>$estadoCupo);
    }
    
    
    public function consultaCupoWS($codigoAsociado){
        $em = $this->container->get('Doctrine')->getManager('sipasociados');
        /* consumo de web service*/
        try {
            ini_set('default_socket_timeout', 600);
            $usuarioSap='wsrf01';
            $claveSap='wssip4020';
            $client = new \SoapClient($this->container->getParameter('urlconsultarcupo'), array(
                'trace'    => true,
                'exceptions' => 1,
                'login'    => $usuarioSap,
                'password' => $claveSap,
                'cache_wsdl' => WSDL_CACHE_NONE
            ));
            $result = $client->ZCDWS_CUPOSSIP(array("CODIGODROGUERIA" => $codigoAsociado));
            return array('codigoRetono'=>$result->CODIGORETORNO,'mensajeRetorno'=>$result->MENSAJERETORNO,'cupo'=>(int)str_replace(' ', '', $result->VALORCUPO));
        } catch (\SoapFault $e) {
            $message = new \Swift_Message();
            $container = $this->container;
            $mailer = $container->get('mailer');
            $message->setFrom($container->getParameter('administrador_email'),'FTP')
                    ->setTo(array('c.preciado@waplicaciones.co'=>'Cristian Preciado','a.cardenas@coopidrogas.com.co'=>'Andersson Cardenas'))
                    ->setBcc(array('alejandro@waplicaciones.co'=>'Alejandro Ardila'))
                    ->setBody("WebService caido")
                    ->setSubject('Error WebService');

            
            //Ahora manualmente ejecutamos el envio -por ser un comando-
            $mailer->send($message);
            $spool = $mailer->getTransport()->getSpool();
            $transport = $container->get('swiftmailer.transport.real');
            $spool->flushQueue($transport);
            $cliente = $em->getRepository('FTPAdministradorBundle:Cliente')->findOneByCodigo($codigoAsociado);
            return array('codigoRetono'=>$cliente->getBloqueoR(),'mensajeRetorno'=>'Cupo Disponible','cupo'=>$cliente->getCupoAsociado());
        } catch (\Exception $e) { 
            echo "Exception"; exit();
        }
        
    }
    
    function estadoCuopoLetras($estado){
        switch($estado){
            case 1:
                return 'Insuficiencia cupo';
            break;
            case 2:
                return 'Mora en el pago';
            break;
            case 3:
                return 'Sanción cheque devuelto';
            break;
            case 4:
                return 'Cheque devuelto';
            break;
            case 5:
                return 'Copicrédito';
            break;
            case 6:
                return 'Pliego de cargos';
            break;
            case 7:
                return 'Venta droguería';
            break;
            case 8:
                return 'Otros motivos';
            break;
            case 9:
                return 'Fallecimiento';
            break;
            case 10:
                return 'Jurídico';
            break;
            case 11:
                return 'Retiro en tramite';
            break;
            case 12:
                return 'Error Dirección Contacto';
            break;
            case 13:
                return 'Venta Drog sin Reportar';
            break;
            case 14:
                return 'Mora Obligación Traslado';
            break;
            case 15:
                return 'Resolución Exclusión';
            break;
            case 16:
                return 'Confirmación Exclusión';
            break;
            case 50:
                return 'Suspendida';
            break;
            case 51:
                return 'Bloqueada';
            break;
            case 52:
                return 'Retirada';
            break;
            case 53:
                return 'Retirada Fallecido';
            break;
            case 54:
                return 'Retirada Traslados';
            break;
            case 55:
                return 'Retirada Exclusiones';
            break;
            case 56:
                return 'Retirada Forzoso';
            break;
            case 99:
                return 'test (e)';
            break;
            case '':
                return 'Activo';
            break;
            default :
                return 'Error no identificado';
            break;

        }
    }


}

?>
