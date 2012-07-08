<?php
/**
 * OpenSGA - Sistema de Gest�o Acad�mica
 *   Copyright (C) 2010-2011  INFOmoz (Inform�tica-Mo�ambique)
 * 
 * Este programa � um software livre: Voc� pode redistribuir e/ou modificar
 * todo ou parte deste programa, desde que siga os termos da licen�a por nele
 * estabelecidos. Grande parte do c�digo deste programa est� sob a licen�a 
 * GNU Affero General Public License publicada pela Free Software Foundation.
 * A vers�o original desta licen�a est� dispon�vel na pasta raiz deste software.
 * 
 * Este software � distribuido sob a perspectiva de que possa ser �til para 
 * satisfazer as necessidades dos seus utilizadores, mas SEM NENHUMA GARANTIA. Veja
 * os termos da licen�a GNU Affero General Public License para mais detalhes
 * 
 * As redistribui��es deste software, mesmo quando o c�digo-fonte for modificado significativamente,
 * devem manter est� informa��o legal, assim como a licen�a original do software.
 * 
 * @copyright     Copyright 2010-2011, INFOmoz (Inform�tica-Mo�ambique) (http://infomoz.net)
 ** @link          http://opensga.com OpenSGA  - Sistema de Gestão Académica
 * @author		  Elisio Leonardo (elisio.leonardo@gmail.com)
 * @package       opensga
 * @subpackage    opensga.core.controller
 * @since         OpenSGA v 0.10.0.0

 * 
 */
 
 
class User extends AppModel {
	var $name = 'User';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
    
    
	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Funcionario' => array(
			'className' => 'Funcionario',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Aluno' => array(
			'className' => 'Aluno',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	var $actsAs = array('Acl' => array('type' => 'requester'),'Auditable.Auditable');
 
	 function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}
		if (isset($this->data['User']['group_id'])) {
			$groupId = $this->data['User']['group_id'];
		} else {
			$groupId = $this->field('group_id');
		}
		if (!$groupId) {
			return null;
		} else {
			return array('Group' => array('id' => $groupId));
		}
	}
	 
	/*
	function parentNode(){
		return null;
	}
*/
    
    /*
	function bindNode($user) {
		return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
	}
	*/

	
		function getUserByFuncionario($funcionario_id){
            $query = "SELECT us.id FROM users us, funcionarios tf WHERE tf.user_id = us.id AND tf.id = {$funcionario_id} ";
           	$resultado = $this->query($query);
			return $resultado;
        }
		
		function getAlunoIDByUser($user_id){
            $query = "SELECT ta.id FROM users us, Alunos ta WHERE ta.user_id = us.id AND us.id = {$user_id} ";		
           	$resultado = $this->query($query);			
			return $resultado[0]["ta"]["id"];
        }

		function getFuncionarioIDByUser($user_id){
            $query = "SELECT tf.id FROM users us, funcionarios tf WHERE tf.user_id = us.id AND us.id = {$user_id} ";
           	$resultado = $this->query($query);
			return $resultado[0]["tf"]["id"];
        }
		
		
		function deleteUser($user_id){
            $query = "delete from users where id = {$user_id} ";
           	$resultado = $this->query($query);
			//var_dump($query);
			return $resultado;
        }
		
		function beforeSave(){
            //So gera Password e Username se for novo cadastro
            if(isset($this->request->data['User']['password'])){
                $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
                $this->request->data['User']['username'] = $this->geraUsername($this->request->data['User']['name']);
            }
			return true;
		}
		
		/**
		 * Gera o nome de Usuario de Cada Utilizador de acordo com criterios pre-estabelecidos
		 * Por Enquanto Ele usa a estrutura "primeironome.ultimonome<sequencia>"
		 */
		function geraUsername($nome){
			$nomes = explode(' ', $nome);
			
			$username = strtolower($nomes[0]).".".strtolower(end($nomes));
			
			$username1 = $username;
			$numero = 1;
			$linha=1;
			while($linha!=0){
				$users = $this->find('all',array('conditions'=>array('username'=>$username)));
				$linha = count($users);
				if($linha==0){
					
					return $username;
				}
				else{
					
					$username = $username1.$numero;
					$numero++;
				}	
			}
            
            return $username;
		}
        
        public function alteraPassword($data){
            $user_id = SessionComponent::read('Auth.User.id');
            $this->id = $user_id;
            if($data['User']['novasenha1'] != $data['User']['novasenha2']){
                return false;
            }
            
            $senha_antiga = AuthComponent::password($data['User']['senhaantiga']);
            $senha  = $this->field('password');
            if($senha != $senha_antiga){
                return false;
            }
            
            $this->set('password', AuthComponent::password($data['User']['novasenha1']));
            $this->save();
            return true;
        }
        
}
?>