<?php
/**
 * CakePHP ACL Link Helper
 *
 * Based on Joel Stein AclLinkHelper
 * http://bakery.cakephp.org/articles/joel.stein/2010/06/26/acllinkhelper
 *
 * @author      Shahril Abdullah - shahonseven
 * @link
 * @package     Helper
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('FormHelper', 'View/Helper');
App::uses('AclComponent', 'Controller/Component');

class AclLinkHelper extends FormHelper
{

    public $userModel = 'User';

    public $primaryKey = 'id';

    public function __construct(View $View, $settings = [])
    {
        parent::__construct($View, $settings);

        if (is_array($settings) && isset($settings['userModel'])) {
            $this->userModel = $settings['userModel'];
        }

        if (is_array($settings) && isset($settings['primaryKey'])) {
            $this->primaryKey = $settings['primaryKey'];
        }
    }

    public function link($title, $url = null, $options = [], $confirmMessage = null)
    {
        if ($this->_aclCheck($url)) {
            return $this->Html->link($title, $url, $options, $confirmMessage);
        }

        return '';
    }

    protected function _aclCheck($url)
    {
        $plugin = '';
        if (isset($url['plugin'])) {
            $plugin = Inflector::camelize($url['plugin']) . '/';
        }

        $controller = '';
        if (isset($url['controller'])) {
            $controller = Inflector::camelize($url['controller']) . '/';
        }

        $action = 'index';
        if (isset($url['action'])) {
            $action = $url['action'];
        }

        $collection = new ComponentCollection();
        $acl = new AclComponent($collection);
        $aro = [
            $this->userModel => [
                $this->primaryKey => AuthComponent::user($this->primaryKey),
            ],
        ];
        $aco = $plugin . $controller . $action;

        return $acl->check($aro, $aco);
    }

    public function postLink($title, $url = null, $options = [], $confirmMessage = false)
    {
        if ($this->_aclCheck($url)) {
            return parent::postLink($title, $url, $options, $confirmMessage);
        }

        return '';
    }

}
