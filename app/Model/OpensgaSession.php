<?php
App::uses('AppModel', 'Model');
/**
 * OpensgaSessiom Model
 *
 */

App::uses('AuthComponent', 'Controller/Component');

class OpensgaSession extends AppModel
{
    public $actsAs = ['Containable'];

    // Add the relation between the User and the UserSession
    public $belongsTo = ['User'];

    // Time in seconds before a session no longer should be seen as active
    private $_activeSessionThreshold = 600;

    public $useTable = 'cake_sessions';

    /**
     * Before Save callback
     * used to add the User's ID to session data (if any, otherwise null is used)
     *
     * @param array $options The options passed to the save method
     *
     * @return bool
     */
    public function beforeSave($options = [])
    {
        $this->data[$this->alias]['user_id'] = AuthComponent::user('id');

        return true;
    }

    public function getActiveUsers(){
        $minutes = 1000; // Conditions for the interval of an active session
        $sessionData = $this->find('all',array(
            'conditions' => array(
                'expires >=' => time() - ($minutes * 60) // Making sure we only get recent user sessions
            )
        ));


        $activeUsers = array();
        foreach($sessionData as $session){
            $data = $session['OpensgaSession']['data'];
            // Clean the string from unwanted characters
            $data = str_replace('Config','',$data);
            $data = str_replace('Message','',$data);
            $data = str_replace('Auth','',$data);
            $data = substr($data, 1); // Removes the first pipe, don't need it

            // Explode the string so we get an array of data
            $data = explode('|',$data);

            // Unserialize all the data so we can use it
            $auth = unserialize($data[3]);

            // Check if we are dealing with a signed-in user
            if(!isset($auth['User']) || is_null($auth['User']['id'])) continue;

            /* Because a user session contains all the data of a user
                 * (except the password), I will only return the User id
                 * and the first and last name of the user */

            /* First check if a user id hasn't already been saved
                 * (can happen because of multiple sign-ins on different
                 * browsers / computers!) */

            if(!in_array($auth['User']['id'],$activeUsers)){
                $activeUsers[$auth['User']['id']] = array('username' => $auth['User']['username']);

                /* Keep in mind, your User table needs to contain
                 * a first- and lastname to return them. If not,
                 * you could use the email address or username
                 * instead of this data. */

            }
        }
        return $activeUsers;
    }
}
