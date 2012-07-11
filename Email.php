<?php
class Email
{
    /**
    *
    * @var mail $to
    */
    protected $_to;
    
    /**
    *
    * @var mail $from 
    */
    protected $_from;

    /**
    *
    * @var String $subject
    */
    protected $_subject;
    
    /**
    *
    * @var String $body
    */
    protected $_body;

    /**
    *
    * @method getTo retorna el destinatario del Mail
    */
    public function getTo() 
    {
        return $this->_to;        
    }

    /**
    *
    * @method setTo indica el email del destinatario
    * @param email $to
    */    
    public function setTo($to)
    {
        var_dump(filter_var($to, FILTER_VALIDATE_EMAIL));
        if(!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new CrugeMailerException('Mail del destinatario errado');
        } else {
            $this->_to = filter_var($to, FILTER_VALIDATE_EMAIL);            
        }
        return $this;
    }

    /**
    *
    * @method getTo retorna el destinatario del Mail
    */
    public function getFrom() 
    {
        return $this->_from;        
    }

    /**
    *
    * @method setTo indica el email del que envia
    * @param email $to
    */    
    public function setFrom($from)
    {
       if(!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new CrugeMailerException('Mail del remitente errado');
        } else {
            $this->_to = filter_var($to, FILTER_VALIDATE_EMAIL);            
        }
        return $this;
    }
    
    
    public function getSubject()
    {
        return $this->_subject;
    }

    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function setBody($body)
    {
        $this->_body = $body;
        return $this;
    }
}
