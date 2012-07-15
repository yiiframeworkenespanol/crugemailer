<?php 
	/** CrugeMailer
	
		Provee la creacion de correos electronicos basados en esquema de vistas,
		ayudando ademas a la centralizacion del despacho de los correos y ayudando
		tambien a "componentizar".
		
		instalacion: en config/main
		
			'crugemailer'=>array(
				'class' => 'application.modules.cruge.extensions.crugemailer.CrugeMailer',
				'mailfrom' => 'christiansalazarh@gmail.com',
				'subjectprefix' => 'CrugeMailer - ',
			),
		
		uso:
			$juanperez = new Usuario();					// tu modelo
			$juanperez->email = 'juanperez@gmail.com';	// argumentos de prueba
			$juanperez->nombres = "Juan Perez";			// 
			
			Yii::app()->crugemailer->enviarUnCorreoA($juanperez,"asunto");
		
		entonces, crear un metodo en CrugeMailer:
		
			public function enviarUnCorreoA($persona,$asunto){
				$this->sendemail($persona->email,self::t($asunto)
					,$this->render('enviarUnCorreoA',array('data'=>$persona))
				);
			}
			
			la llamada a $this->render con argumento 'enviarUnCorreoA' invocara
			tanto un layout con nombre 'mailer' como una vista llamada 'enviarUnCorreoA'
			ambos ubicados en la carpeta views/layout y views/mailer respectivamente.
			
		si se ejecuta bajo un modulo, entonces sobreescribir el metodo init y
		hacer una llamada a setModule(con el modulo en cuestion apuntado aqui);
			
		y para darle formato al correo:

			deben existir dos archivos:
				views/layout/mailer.php , tal cual como se usan los layouts en Yii,
			y por cada correo a enviar se puede crear una vista en:
				views/mailer/enviarUnCorreoA.php (siguiendo el ejemplo de arriba)
			
		
		para controlar el despacho de los correos: 
			editar a CrugeMailerBase.php o crear un metodo sendemail con los mismos argumentos para que se ajuste a las necesidades.
		
		@author: Christian Salazar H. <christiansalazarh@gmail.com> @bluyell
		http://www.yiiframeworkenespanol.org/licencia		
	*/
	class CrugeMailer extends CrugeMailerBase implements ICrugeMailer {
	
		// debido a que es un componente.
		public function init(){
			parent::init();
			//$this->setModule(un modulo aqui si necesitas que aplique a un modulo);
		}
	
		// un traductor
		public function t($text){
			return $text;
		}
		
		public function enviarClave(Usuario $usuario){
            $email = new Email();
            $email->setTo($usuario)
                ->setSubject(self::t("recuperacion de clave"))
                ->setBody($this->render('enviarclave',array('data'=>$usuario)));			
            $this->sendEmail($email);

		}
		/*
		    este metodo se coloca aqui para que puedas personalizar el envio de correo
		    usando tu propia metodo, si quieres usar el metodo por defecto (mail) entonces
		    simplemente llamas a parent::sendEmail.  
		*/
	        public function sendEmail(Email $email) 
	        {
	            if(!parent::sendEmail($email)) {
	               throw new CrugeMailerException(self::t('El Mail no ha sido enviado revise la configuración del servidor'));
	            }
	            return true;
	        }
	}
?>
