security:
    encoders:
        administrador:
            class: Circulares\AdministradorBundle\Entity\Administradores
            algorithm: plaintext
        drogueria:
            class: Circulares\DrogueriaBundle\Entity\Asociado
            algorithm: plaintext
       
    providers:
        administrador:
            entity: 
                class: Circulares\AdministradorBundle\Entity\Administradores
                property: usuario
        drogueria:
            entity: 
                class: Circulares\DrogueriaBundle\Entity\Asociado
                property: codigoDrogeria
                manager_name: clientes
       
    firewalls:
        administrador_login:
             pattern:  ^/administrador/default/login$
             security: false
        administrador_recordar_clave:
            pattern: ^/administrador/default/recordarClave
            security: false     
        administrador_login_error:
             pattern:  ^/administrador/default/loginError/*
             security: false

        administrador_secured_area:
            pattern:    ^/administrador/
            provider: administrador
            form_login:
                check_path: /administrador/default/login_check
                login_path: /administrador/default/login
                default_target_path: /administrador/default/
                always_use_default_target_path: true
                success_handler: administrador.login_success_handler.class
                failure_handler: administrador.login_success_handler.class

        drogueria_login:
             pattern:  ^/drogueria/default/login$
             security: false
        drogueria_recordar_clave:
            pattern: ^/drogueria/default/recordarClave
            security: false     
        drogueria_login_error:
             pattern:  ^/drogueria/default/loginError/*
             security: false

        drogueria_secured_area:
            pattern:    ^/drogueria/
            provider: drogueria
            form_login:
                check_path: /drogueria/default/login_check
                login_path: /drogueria/default/login
                default_target_path: /drogueria/default/
                always_use_default_target_path: true
                #success_handler: drogueria.login_success_handler.class
               # failure_handler: drogueria.login_success_handler.class

    access_control:
        administrador_login:
            path: /administrador/defult/login
            roles: IS_AUTHENTICATED_ANONYMOUSLY
        administrador_login_check:
            path: /administrador/defult/login_check
            roles: IS_AUTHENTICATED_ANONYMOUSLY
        administrador_area:
            path: /administrador/.*
            roles: [ROLE_ADMINISTRADOR]

        drogueria_login:
            path: /drogueria/defult/login
            roles: IS_AUTHENTICATED_ANONYMOUSLY
        drogueria_login_check:
            path: /drogueria/defult/login_check
            roles: IS_AUTHENTICATED_ANONYMOUSLY
        drogueria_area:
            path: /drogueria/.*
            roles: [ROLE_DROGUERIA]
			
			
			
			

			
