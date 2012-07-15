CrugeMailer
===========

Permite enviar correos electronicos basados en esquema de vistas y patrones (views & layouts), ayudando ademas a la centralizacion del despacho de los correos.

creado por:

	Yii Framework en Espa�ol
	licencia: http://www.yiiframeworkenespanol.org/licence

versiones:

	10-JUL-2012 Carlos Belisario <carlos.belisario.gonzalez@gmail.com>	addexception, sendEmail
	09-JUL-2012	Christian Salazar <christiansalazarh@gmail.com>			initial-commit
	
###Uso (ejemplo)

Supongamos que tienes un modelo cualquiera:

		$juanperez = new Usuario();					// tu modelo
		$juanperez->email = 'juanperez@gmail.com';	// argumentos de prueba
		$juanperez->nombres = "Juan Perez";			// 
	
el componente se usaria asi:

		Yii::app()->crugemailer->tuMetodoConTuNombreEspecifico($juanperez,"asunto");
	
		Importante: tuMetodoConTuNombreEspecifico es un nombre de ejemplo, 
		tu puedes poner aqui cosas reales como: 
		$tumodelo->sendPasswordRecovery()


esa llamada invocaria a un layout y una vista para darle formato a ese correo electronico, las vistas de ejemplo ser�an:

1. crear en application.views.layouts un archivo mailer.php, con el layout deseado, comun para cada correo a ser enviado.

2. luego crear en applications.views.mailer la cantidad de archivos necesarios que representen a cada vista de cada correo a ser enviado.

###Como Implementar

	1. crea o busca una carpeta llamada "extensions" dentro de protected.
		ejemplo: E:\code\test3\protected\extensions
		
	2. copia todo el paquete original de CrugeMailer dentro de ella.
		ejemplo: E:\code\test3\protected\extensions\crugemailer\
		aqui adentro deben haber 3 archivos: 
			ICrugeMailer		es una interfaz para que puedas crear tus implementaciones
			CrugeMailerBase		es el codigo base que tiene el trabajo fuerte
			CrugeMailer			es tu propia clase en la cual crearas tus propios metodos
								tal como en este ejemplo se creo a tuMetodoConTuNombreEspecifico()
            CrugeSwiftMailer    es una la clase que paso a ser la principal para implementar la extensi�n, la misma nos permite utilizar la 
                                libreria Swiftmailer http://swiftmailer.org/ y todos los beneficios que esta nos ofrece
            CrugeSiwftMailerContainer es la clase contenedora de SwiftMailer, esta nos provee los m�todos b�sicos para trabajar con la librer�a
		
	3. configura el componente en tu propio archivo protected/config/main.php de esta forma:
			'crugemailer'=>array(
				'class' => 'application.extensions.crugemailer.CrugeSwiftMailer',
				'mailfrom' => 'christiansalazarh@gmail.com',
                'transport' => 'gmail' // gmail para usar el stmp de gmail (recomendado), no especificarlo trabajara la librer�a con la funci�n mail de php
                /**
                *
                * obligatorios si el transporte es gmail
                */
                'gmailAcount' => 'carlos.belisario.gonzalez@gmail.com',
                'gmailPassword' => 'password de gmail',       
       
				'subjectprefix' => 'Prefijo que deseas agregar, es opcional - ',
			),
		
        nota aqui que application.extensions.crugemailer.CrugeSiwftMailer es la clase principal de la librer�a donde los desarrolladores estamos haciendo mantenimiento, sin embargo puedes trabajar sobre cualquiera de las clases CrugeMailer o CrugeSwiftMailer de la extension ya que est�n listas para usar y ampliar, por tanto
		puedes usarla directamente.

       		
		luego configura los 'imports' para que la extension CrugeMailer sea cargada:
			'import'=>array(
				'application.models.*',
				'application.components.*',
				'application.extensions.crugemailer.*',
			),
	
	4. crea un layout para tus correos llamado 'mailer.php' en:
		E:\code\test3\protected\views\layouts\mailer.php
		con el siguiente contenido de ejemplo:
		
			<h1>Titulo General del Correo</h1>
			<h2>mas contenido html</h2>
			<p>puedes darle cualquier contenido al correo usando html tal cual como siempre creas vistas
			en Yii</p>
			<?php echo $content;?>
			<h2>un pie de pagina</h2>		
		
	5. crea una carpeta llamada "mailer" dentro de tu carpeta views
		E:\code\test3\protected\views\mailer  (aqui dentro iran las vistas de cada correo)

		
Una vez que hayas instalado cruge el uso de este dependera del nombre que le hayas dado al componente, en este caso (paso 3) le dimos el nombre: "crugemailer", por tanto la llamada y uso del componente sera: Yii::app()->crugemailer

###Agregando tus funciones
	
 Debemos crear una nueva clase que extienda de CrugeMailerBase y que implemente la interfaz ICrugeMailer la cual nos garantizara que nuestra clase extendida tendr� los metodos necesarios para funcionar, mientras que CrugeMailerBase nos dar� el codigo necesario para que todo funcione transparentemente.
	
 El paquete ya trae su clase lista para usar: CrugeMailer, a la cual solo quedaria agregarle nuevos
 metodos y nada mas:
 
 *Ejemplo:* 
 
  Necesitas hacer lo siguiente, crear un metodo llamado "enviarClave", el cual le enviara al usuario la clave de su usuario en tu sistema.
  
	Supongamos que el modelo Usuario existe (es un ejemplo usas cualquier cosa), y que tiene
	los atributos: nombres, email, password
  
	1. Editas CrugeMailer.php y agregas un nuevo metodo que cumpla tus necesidades:
	
		public function enviarClave(Usuario $usuario){
			$this->sendemail($usuario->email,self::t("recuperacion de clave")
				,$this->render('enviarclave',array('data'=>$usuario))
			);
		}
  
	2. Con este metodo ya podrias hacer la siguiente llamada:
		
		$model = Usuario::model()->findByAttributes(array('email'=>$correo));
	
		Yii::app()->crugemailer->enviarClave($model);
  
	3. El formato del correo se manejara asi:
	
		CrugeMailer cargara primero el layout que hiciste en el paso 4 arriba,
		y luego sustituira la variable php $content con el contenido de la vista de
		nombre "enviarclave", este nombre se lo diste cuando hiciste: $this->render, un poco mas arriba.
	
		Supongamos de nuevo que el contenido de 
			E:\code\test3\protected\views\mailer\enviarclave.php
		es:
			<p>Estimado usuario: <?php echo $data->nombres;?></p>
			<p>su clave es: <?php echo $data->password;?></p>
		
		y que el contenido del layout almacenado en:
			E:\code\test3\protected\views\layouts\mailer.php
		es:
			<h1>Titulo General del Correo</h1>
			<h2>mas contenido html</h2>
			<p>puedes darle cualquier contenido al correo usando html tal cual como siempre creas vistas
			en Yii</p>
			<?php echo $content;?>
			<h2>un pie de pagina</h2>
			
		por tanto el correo que tu cliente recibira ser�:
<div style='color: gray;'>
			<h1>Titulo General del Correo</h1>
			<h2>mas contenido html</h2>
			<p>puedes darle cualquier contenido al correo usando html tal cual como siempre creas vistas
			en Yii</p>
				<p>Estimado usuario: Juan Perez</p>
				<p>su clave es: 12345678</p>
			<h2>un pie de pagina</h2>		
</div>



###Probando en un action de siteController

Solo a modo de ejemplo para que veas como usarla:

	public function actionIndex()
	{
        //trabajando con CrugeSiwftMailer
		Yii::app()->crugemailer->sendEmail('el cuerpo de lo que va a ser enviado en el mensaje', 
            /*destinatario del mensaje*/array('carlos.belisario.gonzalez@gmail.com'), /*opcional puede configurarse en el application/config/main*/ array('contac@midominio.com'), /*opcional*/'Asunto del Correo Electr�nico');
		
		// aqui render('index') no tiene nada que ver con el ejemplo, es para mostrarte
		// que crugemailer no tiene nada que ver con renders de controllers normales,
		//	
		$this->render('index');
	}

###Donde personalizar la funcion mail() ?

Para personalizar la extensi�n puedes crear tu propia clase que extienda del CrugeMailerBase y sobreescribir el m�todo sendEmail(), que es implementado de la interfaz, puedes ver un ejemplo en CrugeSiwftMailer
