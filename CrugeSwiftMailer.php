<?php 
//require_once 'SwiftMailerContainer.php';
/**
*
* class SwiftMailerContainer container for the lib swiftmailer
* @author Carlos Belisario <carlos.belisario.gonzalez@gmail.com>
* @version 1.0
*/

class CrugeSwiftMailer extends CrugeMailerBase implements ICrugeMailer 
{
	/**
	 * 
	 * @var SwiftMailerContainer $container
	 */
	private $container;

	/**
	 * 
	 * @var string
	 */
	public $transport;	

	/**
	 * 
	 * @var email
	 */
	public $gmailAcount;

	/**
	 * 
	 * @var string
	 */
	public $gmailPassword;

	/**
	 * 
	 * @override
	 */
	public function init(){		
		parent::init();		
		/*		  
		 * create the Object SwiftMailerContainer
		 */
		$this->container = new SwiftMailerContainer($this->getConfig());								 				
	}

	public function getConfig() {
		if(isset($this->gmailAcount)) {
			if(!filter_var($this->gmailAcount, FILTER_VALIDATE_EMAIL)) {
	            throw new CrugeMailerException('Error: mail format wrong');
	        } else {
	            $this->gmailAcount = filter_var($this->gmailAcount, FILTER_VALIDATE_EMAIL);            
	        }
	    }
	    if(!isset($this->transport)) {
	    	$config = null;
	    } else {
			$config = array(
				'transport' => $this->transport, 
				'mail' =>  isset($this->gmailAcount) ? $this->gmailAcount : null,				
				'password' => isset($this->gmailPassword) ? $this->gmailPassword : null,
			);
		}
		return $config;
	}

	/**
     * @method sendMail send the mail 
     * @param  string $body 
     * @param  array  $to 
     * @param  mixed $from  
     * @param  string $subject
     * @return boolean
     * 
     */
	public function sendEmail($body, array $to, array $from = null, $subject = '')
	{		
		if(empty($from)) {
			$from = array($this->mailfrom);		
		}		
		$message = $this->container->getMessenger($subject)			
			->setFrom($from)
			->setTo($to)
			->setBody($body);		
		$result = $this->container->getMailer()->send($message);
		spl_autoload_register(array('YiiBase', 'autoload')); // register Yii autoload
		if($result) {
			return $result;
		} else {
			throw new CrugeMailerException('Error: Revisar Configuración del Mail');
		}
	}

	public function t($text){
			return $text;
	}
}
