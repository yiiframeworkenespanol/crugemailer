<?php

require_once 'swiftmailer/lib/swift_required.php';
/**
*
* class SwiftMailerContainer container for the lib swiftmailer
* @author Carlos Belisario <carlos.belisario.gonzalez@gmail.com>
* @version 1.0
*/
class SwiftMailerContainer 
{

	/**
	 * 
	 * @var array $config
	 */
	private $config;

	public function __construct(array $config = null) 
	{
		spl_autoload_unregister(array('YiiBase', 'autoload')); // Disable Yii autoloader for use the SwiftMailer				
		$this->config = $config;
	}

	/**
	 * setter of the config transpor
	 * @param array $config   
	 * @param boolean $override 
	 */
	public function setConfig(array $config = null, $override = true) 
	{
		if($override) {
			$this->config = $config;
		}  else {
			$this->config = !empty($this->config) ? array_merge($this->config = $config) : $config;
		}
		return $this;
	}

	/**
	 * @method getTransport return the transport of the mail 
	 * By default this class return the function mail() of php transport
	 * @param  array $config array with the configurations for the transport of the mail
	 * @return Swift_SendmailTransport
	 * 
	 */
	protected function _getTransport($config)
	{
		if('gmail' == $config['transport'])	{
			$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
        		->setUsername($config['mail'])
        		->setPassword($config['password']);
		} elseif(null === $config) {
			$transport = Swift_MailTransport::newInstance();
		} else {
			$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
		}
		return $transport;		
	}
	
	/**
	 * @method getMailer return the mailer object for send the email
	 * @param  array $config array with the configurations for the transport of the mail
	 * @return Swift_Mailer
	 */
	public function getMailer() 
	{
		$mailer = Swift_Mailer::newInstance($this->_getTransport($this->config));
		return $mailer;
	}

	/**
	 * 
	 * @param  string $suject 
	 * @return Swift_Message
	 */
	public function getMessenger($suject = '')
	{		
		return Swift_Message::newInstance($suject);
	}
}
