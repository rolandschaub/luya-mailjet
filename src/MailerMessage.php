<?php

namespace luya\mailjet;

use yii\mail\BaseMessage;
use luya\Exception;

/**
 * Mailjet Message.
 * 
 * Inspired by https://github.com/weluse/yii2-mailjet
 * 
 * @author Basil Suter <basil@nadar.io>
 */
class MailerMessage extends BaseMessage
{
    private $_charset;
    
    private $_from;
    
    private $_to;
    
    private $_replyTo;
    
    private $_cc;
    
    private $_bcc;
    
    private $_subject;
    
    private $_textBody;
    
    private $_htmlBody;
    
    /**
     * @inheritdoc
     */
    public function getCharset()
    {
        return $this->_charset;
    }
    
    /**
     * @inheritdoc
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
    }
    
    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return $this->_from;
    }
    
    
    /**
     * @inheritdoc
     */
    public function setFrom($from)
    {
        if (is_array($from)) {
            $this->_from = [
                'Email' => key($from),
                'Name' => array_shift($from),
            ];
        } else {
            $this->_from['Email'] = $from;
        }
        
        return $this;
    }
    
    private $_template;
    
    /**
     * Set the template id from mailjet.
     * 
     * > Transactional Templates
     * 
     * @param integer $id
     * @return \luya\mailjet\MailerMessage
     */
    public function setTemplate($id)
    {
        $this->_template = $id;
        $this->_templateLanguage = true;
        
        return $this;
    }
    
    private $_variables;
    
    /**
     * Set variables to a template.
     * 
     * Where key is the variable name.
     * 
     * @param array $vars
     * @return \luya\mailjet\MailerMessage
     */
    public function setVariables(array $vars)
    {
        $this->_variables = $vars;
        
        return $this;
    }
    
    public function getVariables()
    {
        return $this->_variables;
    }
    
    public function getTemplate()
    {
        return $this->_template;
    }
    
    private $_templateLanguage;
    
    public function getTemplateLanguage()
    {
        return $this->_templateLanguage;
    }
    
    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->_to;
    }
    
    /**
     * @inheritdoc
     */
    public function setTo($to)
    {
        // $to = 'foo@bar.com';
        // $to = ['foo@bar.com', 'quix@baz.com'];
        // $to = ['foo@bar.com' => 'John Doe'];
        
        $to = (array) $to;
        $recipients = [];
        foreach ($to as $key => $value) {
            if (is_numeric($key)) {
                // email only
                $recipients[] = ['Email' => $value, 'Name' => $value];
            } else {
                // key is email, value is name
                $recipients[] = ['Email' => $key, 'Name' => $value];
            }
        }
        
        $this->_to = $recipients;
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function getReplyTo()
    {
        return $this->_replyTo;
    }
    
    /**
     * @inheritdoc
     */
    public function setReplyTo($replyTo)
    {
        $this->_replyTo = $replyTo;
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function getCc()
    {
        return $this->_cc;
    }
    
    /**
     * @inheritdoc
     */
    public function setCc($cc)
    {
        $this->_cc = $cc;
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function getBcc()
    {
        return $this->_bcc;
    }
    
    /**
     * @inheritdoc
     */
    public function setBcc($bcc)
    {
        $this->_bcc = $bcc;
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->_subject;
    }
    
    /**
     * @inheritdoc
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }
    
    /**
     * return the plain text for the mail
     */
    public function getTextBody()
    {
        return $this->_textBody;
    }
    
    /**
     * @inheritdoc
     */
    public function setTextBody($text)
    {
        $this->_textBody = $text;
        return $this;
    }
    
    /**
     * return the html text for the mail
     */
    public function getHtmlBody()
    {
        return $this->_htmlBody;
    }
    
    /**
     * @inheritdoc
     */
    public function setHtmlBody($html)
    {
        $this->_htmlBody = $html;
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function attach($fileName, array $options = [])
    {
        /*
         * 'Attachments' => [
                [
                    'ContentType' => "text/plain",
                    'Filename' => "test.txt",
                    'Base64Content' => "VGhpcyBpcyB5b3VyIGF0dGFjaGVkIGZpbGUhISEK"
                ]
            ]
         */
        throw new Exception('Not Implemented');
    }
    
    /**
     * @inheritdoc
     */
    public function attachContent($content, array $options = [])
    {
        throw new Exception('Not Implemented');
    }
    
    /**
     * @inheritdoc
     */
    public function embed($fileName, array $options = [])
    {
        throw new Exception('Not Implemented');
    }
    
    /**
     * @inheritdoc
     */
    public function embedContent($content, array $options = [])
    {
        throw new Exception('Not Implemented');
    }
    
    /**
     * @inheritdoc
     */
    public function toString()
    {
        return implode(',', $this->getTo()) . "\n"
            . $this->getSubject() . "\n"
                . $this->getTextBody();
    }
}