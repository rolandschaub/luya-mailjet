<?php

namespace luya\mailjet;

use yii\base\BaseObject;
use Mailjet\Client;
use Mailjet\Resources;
use yii\base\InvalidConfigException;

/**
 * Sync contacts to lists.
 * 
 * ```php
 * $this->app->mailjet->contacts()
 *     ->list(12345)
 *         ->add('basil+1@nadar.io')
 *         ->add('basil+2@nadar.io')
 *         ->add('basil+3@nadar.io', ['firstname' => 'Basil'])
 *         ->sync();
 * ```
 * 
 * All users will be snyced to all given lists:
 * 
 * ```php
 * 
 * ->contacts()
 *     ->list(1)
 *       ->add('1@foo.com')
 *     ->list(2)
 *       ->add('2@foo.com')
 * ```
 * 
 * Now 1@foo.com and 2@foo.com are both synced to list 1 and 2.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Contacts extends BaseObject
{
    const ACTION_ADDFORCE = 'addforce';
    
    const ACTION_ADDNOFORCE = 'addnoforce';
    
    const ACTION_REMOVE = 'remove';
    
    const ACTION_UNSUBSCRIBE = 'unsub';
    
    /**
     * @var Client
     */
    public $client;
    
    public function __construct(Client $client, array $config = [])
    {
        $this->client = $client;
        parent::__construct($config);
    }
    
    private $_contacts = [];
    
    /**
     * @param string $email
     * @param array $properties Where the key is the property, check: https://app.mailjet.com/contacts/lists/properties
     * @return \luya\mailjet\Contacts
     */
    public function add($email, array $properties = [])
    {
        $item = ['Email' => $email, 'Properties' => $properties];
        
        $this->_contacts[] = array_filter($item);

        return $this;
    }
    
    private $_lists = [];
    
    /**
     * 
     * @param integer $id
     * @param string $action
     * @return \luya\mailjet\Contacts
     */
    public function list($id, $action = self::ACTION_ADDNOFORCE)
    {
        $this->_lists[] = ['ListId' => $id, 'action' => $action];
        
        return $this;
    }
    
    /**
     * 
     * @throws InvalidConfigException
     * @return boolean
     */
    public function sync()
    {
        if (empty($this->_lists)) {
            throw new InvalidConfigException("You have to define at list one list where the contacts should be synced to. call list().");
        }
        
        $body = [
            'Contacts' => $this->_contacts,
            'ContactsLists' => $this->_lists
        ];
        
        $response = $this->client->post(Resources::$ContactManagemanycontacts, ['body' => $body]);
        
        return $response->success();
    }
}