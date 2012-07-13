<?php 
/**	ICrugeMailer

	interfaz para el manejo de envio de correos

	si un componente del usuario requiere personalizar el envio de correos puede crear 
	un nuevo componente que implemente esta interfaz y extienda de CrugeMailer
	
	@author: Christian Salazar H. <christiansalazarh@gmail.com> @bluyell
	http://www.yiiframeworkenespanol.org/licencia
*/
interface ICrugeMailer {

	public function t($text);

    /**
     * @method sendMail send the mail 
     * @param  string $body 
     * @param  array  $to 
     * @param  mixed $from  
     * @param  string $subject
     * @return boolean
     */
    public function sendEmail($body, array $to, array $from = null, $subject = '');
}
