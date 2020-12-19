<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

// Form Class
class Form {

    protected $users = array();
    public $formid;

    public function __construct($id) {
        $this->formid = $id;
    }

    public function connectUser($userid) {
        if(!$this->isUserConnected($userid)) array_push($this->users, $userid);
    }
    
    public function isUserConnected($userid) {
        
        foreach($this->users as $id) if($id == $userid) return true;    
      
        return false;

    }

    public function disconnectUser($userid) {
        
        $user_key = array_search($userid, $this->users);

        foreach($this->users as $key => $id) {
            
            if($userid == $id) {
                unset($this->users[$key]);
            }
        }


        return count($this->users);

    }

    public function getFormUsers() {
        return $this->users;
    }

    public function totalUsers() {
        return count($this->users);
    }
    
}

// Implementation class
class Chat implements MessageComponentInterface {

    protected $forms;
    protected $clients;
    protected $connections;
    protected $registered_users = array();
    protected $registered_forms = array();

    function __construct() {
        $this->log("------------------------------");
        $this->log("CREATING SERVER OBJECT STORAGE");
        $this->forms = new \SplObjectStorage;
        $this->clients = new \SplObjectStorage;
        $this->connections = new \SplObjectStorage;
        $this->log("-READY & LISTENING");
        $this->log("------------------------------");
    }

    function log($message) {
        echo $message."\n";
    }

    function getFormRegistrationKey($formid) {

        // Create form socket if doesnt exists
        if(!isset($this->registered_forms[$formid])) {

            $registered_key = new StdClass;

            $this->forms->attach($registered_key, new Form($formid));

            $this->registered_forms[$formid] = $registered_key;

            $this->log("Opened a new form socket");

        } else {
            $registered_key = $this->registered_forms[$formid];
        }

        return $registered_key;

    }

    public function onOpen(ConnectionInterface $conn) {
        $this->connections->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $message) {

        $message = json_decode($message);

        switch($message->type) {

            case "REGISTER":

                // Step 1: Register as client

                // If not already registered the connection object
                if(!isset($this->registered_users[$message->userid])) {
                    
                    $registered_key = new StdClass;

                    // Register the connection as client
                    $this->clients->attach($registered_key, $from);

                    // Add regitered key of connection to registered user
                    $this->registered_users[$message->userid] = $registered_key;

                    $this->connections->offsetSet($from, $registered_key);
                    
                    $this->log("Registered a new user connection");

                } else {

                    $registered_key = $this->registered_users[$message->userid];

                    // Check whether conenction is same or changed(New tab etc.)
                    $client_object = $this->clients[$registered_key];

                    // Update client registeration key
                    if($client_object != $from) {

                        // Remove the old connection
                        $client_object->close();

                        // Update connection object
                        $this->connections->detach($client_object);
                        $this->connections->attach($from, $registered_key);

                        // Update client object
                        $this->clients->offsetSet($registered_key, $from);

                        $this->log("Updated a user connection");

                    } else {
                        $this->log("Registered another request from same user");
                    }

                }
    
                // Step 2: Register client on form

                $registered_key = $this->getFormRegistrationKey($message->formid);

                $form = $this->forms->offsetGet($registered_key);

                $form->connectUser($message->userid);

                // Update form mannual
                $this->forms->offsetSet($registered_key, $form);

                $this->log("Connected a user to form socket");

                $this->logStat();

                break;

            case 'TYPING':
            case 'NOTYPING':

                // Prepare JSON object response for clients
                $response = json_encode(array(
                    "type" => $message->type,
                    "userid" => $message->userid,
                    "formid" => $message->formid,
                    "content" => "NULL"
                ));

                $form = $this->forms->offsetGet($this->getFormRegistrationKey($message->formid));

                if($form) {
                    
                    $form_users = $form->getFormUsers();

                    foreach($form_users as $user_id) {

                        $registered_key = $this->registered_users[$user_id];

                        $user_socket = $this->clients->offsetGet($registered_key);

                        if($user_socket && $from != $user_socket) {
                            $user_socket->send($response);
                        }
                        
                    }

                }

                break;

            case 'MESSAGE':

                if($message->userid && $message->formid) {
                    // Prepare JSON object response for clients
                    $response = json_encode(array(
                        "type" => 'MESSAGE',
                        "userid" => $message->userid,
                        "formid" => $message->formid,
                        "content" => "NULL"
                    ));

                    $form = $this->forms->offsetGet($this->getFormRegistrationKey($message->formid));

                    if($form) {
                        
                        $form_users = $form->getFormUsers();

                        foreach($form_users as $user_id) {

                            $registered_key = $this->registered_users[$user_id];

                            $user_socket = $this->clients->offsetGet($registered_key);

                            $user_socket->send($response);
                            
                        }

                    }


                }

                break;

            // End chat
            case 'END':

                $form = $this->forms->offsetGet($this->getFormRegistrationKey($message->formid));      

                try {
              
                    $total = $form->totalUsers();

                    if($total == 1 && $message->content == "NA") {
    
                        $form->disconnectUser($message->userid);
                        
                        $this->forms->detach($this->registered_forms[$form->formid]);

                        if(isset($this->registered_forms[$form->formid])) unset($this->registered_forms[$form->formid]);
                        
                    }
    
                } catch(Exception $exceptionwhichwewonthandle) {
    
                    // I don't care, I hate it, cause I never had seen it!
    
                }
                
                $this->logStat();

                break;

            // Delete form(SYS-Websockets only)
            case 'DELETE':
                break;

            default: break; // Error, no active specified;

        }
        
        

    }

    public function onClose(ConnectionInterface $conn) {

        $registered_key = $this->connections->offsetGet($conn);

        if($registered_key && $this->clients->offsetExists($registered_key)) $this->clients->detach($registered_key);

        $this->connections->detach($conn);

        // Remove Registered user
        $userid = array_search($registered_key, $this->registered_users);

        // Remove registered form is empty (Cause a little latency but prevent even bigger latency case)
        foreach($this->forms as $form ) {

            try {
                $form_object = $this->forms[$form];
            
                $form_users = $form_object->getFormUsers();

                if(!$form_object->disconnectUser($userid)) {

                    $this->forms->detach($form);

                    if(isset($this->registered_forms[$form_object->formid])) unset($this->registered_forms[$form_object->formid]);
                    
                }

            } catch(Exception $exceptionwhichwewonthandle) {

                // I don't care, I hate it, cause I never had seen it!

            }

        }

        if(isset($this->registered_users[$userid])) unset($this->registered_users[$userid]);

        $this->logStat();

    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occured: {$e->getMessage()}";
        $conn->close();
    }

    function logStat() {
        $this->log("-------------------------------------------------");  
        $this->log("Total open forms: ". $this->forms->count());
        $this->log("Total open clients: ". $this->clients->count());
        $this->log("Total open connections: ". $this->connections->count());
        $this->log("-------------------------------------------------");
    }

}

?>