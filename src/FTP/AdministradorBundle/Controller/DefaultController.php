<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
     public function loginAction(){
        $request = $this->getRequest();
        $session = $request->getSession();
 
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('FTPAdministradorBundle:Default:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
            'provBloqueado' => null
        ));
    }//fin action

    public function loginErrorAction(){
  
        $peticion = $this->getRequest();
        $sesion = $peticion->getSession();
        $error = $peticion->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR, $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
        return $this->render('FTPAdministradorBundle:Default:login.html.twig', array(
                    'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                    'provBloqueado' => true
                ));
    }//fin action

    public function logoutAction(Request $request) {

            $session=$request->getSession();
            
            $util=$this->container->get('utilidadesAdministrador');
            $util->registralog(' Finaliza sesion. ',$session->get('administradorId'));
            
            $session->invalidate();
            return $this->redirect($this->generateUrl('administrador_default_login'));
    }

    public function recordarClaveAction(Request $request){   

      
        try{
        	
             $response=new Response();
            $json=array();
            $response->headers->set('Content-Type', 'application/json');
            $sanitized = filter_var($request->get('codigo-recordar'), FILTER_SANITIZE_EMAIL);
            if (!(filter_var($sanitized, FILTER_VALIDATE_EMAIL))) {
                $json['respuesta']='1';
                $response->setContent(json_encode($json));
                return $response;
            }

            $em = $this->getDoctrine()->getManager();
            $entity = $em->createQuery(" SELECT a FROM FTPAdministradorBundle:Administrador a WHERE a.email= '".$request->get('codigo-recordar')."'")->getSingleResult();
            
            if($entity){
                //dump($entity);exit;
                //j.casas@waplicaciones.com.co
                $message = new \Swift_Message();
                $message->setSubject('Recordatorio de Clave')->setFrom($this->container->getParameter('administrador_email'),$this->container->getParameter('administrador_nombre'))->setContentType('text/html');
                $message->setTo($entity->getEmail());
               // $message->setTo('j.casas@waplicaciones.com.co');

              
                $message->setBody('usuario: '.$entity->getUsuario().' <br>Password: '.$entity->getClave());

                $this->get('mailer')->send($message);
                $json['respuesta']='2';

            }
            $response->setStatusCode(200);  
        }catch(\Exception $e){
            $response->setStatusCode(200); 
            $json['respuesta']='1';
        }        
        
        $response->setContent(json_encode($json));
        return $response;
		
    }
}