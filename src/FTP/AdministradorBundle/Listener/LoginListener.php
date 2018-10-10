<?php

namespace FTP\AdministradorBundle\Listener;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use FTP\AdministradorBundle\Entity\Administrador;

/**
 * Custom authentication success handler
 */
class LoginListener implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface {
    private $container;
    private $em;
    /**
     * Constructor
     * @param container   $container
     */
    public function __construct($container){
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
        /*
        $em= $this->container->get('doctrine')->getManager();
        $admin=$em->getRepository('SIPAdministradorBundle:Administradores')->findAll();
        var_dump($admin);exit();
        */
    }
    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from AbstractAuthenticationListener.
     * @param Request        $request
     * @param TokenInterface $token
     * @return Response The response to return
     */
    function onAuthenticationSuccess(Request $request, TokenInterface $token){
        if ($token->getUser()->getEstado() == 1){
      
            $conexion=$this->em->getConnection();
            $session = $request->getSession();
            $adm = $this->em->getRepository('FTPAdministradorBundle:Administrador')->findOneBy(array('usuario' => $token->getUser()->getUsuario()));
            $session->set('administradorId',$adm->getId());
            $session->set('nombre',$adm->getNombre());
            $arrayPermisos=array();

            /* PERMISOS ACTUALES */
 
              //traer todos los modulos del menú.
              // colocar el id como llave del array.
              $arrayModulos=$conexion->query(' SELECT id_permisos AS id, descripcion_permiso AS nombre,modulo_defecto FROM menu_administradores ');
              $modulos=array();
              foreach ($arrayModulos as $k => $v) {
                $modulos[$v['id']]['id']=$v['nombre'];   
                $modulos[$v['id']]['modulo']=$v['modulo_defecto']; 
              }
              unset($arrayModulos);
              
              //exit;

              //traer todos los permisos el administrador seleccionado.
              //colocael el id como llave del array.
              $aux=$conexion->prepare('SELECT id_permiso AS modulo, tipo_permiso AS permiso FROM permisos_administradores WHERE id_administrador=? ');
              $aux->bindValue(1,$adm->getId());
              $aux->execute();
              $arrayPermisos=$aux->fetchAll();
              //dump($arrayPermisos);exit;
              $permisos=array();
              foreach ($arrayPermisos as $k => $v) {
                $permisos[$modulos[$v['modulo']]['id']]['permiso']=$v['permiso'];
                $permisos[$modulos[$v['modulo']]['id']]['modulo']=$modulos[$v['modulo']]['modulo'];
              }
              unset($arrayPermisos);
              //dump($permisos);exit();

               //registrar último ingreso.
              $fecha = new \DateTime('now');
              $aux=$conexion->prepare(' UPDATE administrador SET ultimo_ingreso=?, ultima_ip=? WHERE id=?');
              $aux->bindValue(1,$fecha->format('Y-m-d H:i:s'));
              $aux->bindValue(2,$request->getClientIp());
              $aux->bindValue(3,$adm->getId());
              $aux->execute();
             
              $session->set('permisos',$permisos);
              
              $util=$this->container->get('utilidadesAdministrador');
              $util->registralog(' Inicia sesion. ',$adm->getId());
              
              //varificar si se tiene acceso por lo menos a un módulo.
              //dump($permisos);exit;
              
              $uri=false;

              foreach ($permisos as $key => $value) {
                if($value['permiso']!=0){
                  $uri = $this->container->get('router')->generate($value['modulo']);
                  return new RedirectResponse($uri);
                }
                  
              }
              if($uri){
                return new RedirectResponse($uri);
              }else{
                session_destroy();
                $uri = $this->container->get('router')->generate('administrador_default_login');
                return new RedirectResponse($uri);
              }
             
            
            
            $uri = $this->container->get('router')->generate('administrador');
        }else{

            session_destroy();
            $uri = $this->container->get('router')->generate('administrador_default_login');
        }
        
        return new RedirectResponse($uri);
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception){
        $usuario = $request->request->get('_username');
        $request->getSession()->getFlashBag()->add('error', $exception->getMessage());
        $uri = $this->container->get('router')->generate('administrador_default_login');
        $response = new RedirectResponse($uri);
        return $response;

        return new RedirectResponse($request);
    }

    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @param Request $request
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        if ($this->container->get('security.context')->getToken())
        {
            $session=$request->getSession();
            $session->invalidate();
        }

        $uri = $this->container->get('router')->generate('administrador_homepage');
        $response = new RedirectResponse($uri);
        return $response;
    }

}
