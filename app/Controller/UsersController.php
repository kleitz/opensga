<?php

App::uses('AppController', 'Controller');
App::uses('OpenSGAAmazonS3', 'Lib');

/**
 * OpenSGA - Sistema de Gest�o Acad�mica
 *   Copyright (C) 2010-2011  INFOmoz (Inform�tica-Mo�ambique)
 *
 * @copyright       Copyright 2010-2011, INFOmoz (Inform�tica-Mo�ambique) (http://infomoz.net)
 * * @link          http://opensga.com OpenSGA  - Sistema de Gestão Académica
 * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
 * @package         opensga
 * @subpackage      opensga.core.controller
 * @since           OpenSGA v 0.10.0.0
 *
 * @property User $User
 *
 */
class UsersController extends AppController
{

    public function acessos()
    {
        $this->User->contain(['Group', 'Entidade']);
        $users = $this->User->find('all', ['conditions' => ['DATE(ultimo_login)' => date('Y-m-d')]]);

        $this->set(compact('users'));
    }

    public function admin_login()
    {
        $this->redirect(['controller' => 'users', 'action' => 'login', 'admin' => false]);
    }

    function after_login()
    {
        $this->redirect(['controller' => 'pages', 'action' => 'homepage']);
    }

    public function altera_unidade_organica_admin()
    {
        if ($this->request->is('post')) {

            $unidadeOrganica = $this->User->Funcionario->UnidadeOrganica->findById($this->request->data['UnidadeOrganica']['id']);
            if ($this->User->Funcionario->UnidadeOrganica->exists()) {
                $this->Session->write('Auth.User.unidade_organica_id',
                    $this->request->data['User']['unidade_organica']);
                $this->Session->write('Auth.User.unidade_organica', $unidadeOrganica['UnidadeOrganica']['name']);
            }

            $this->redirect('/');
        }
    }

    public function alterar_senha()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User']['user_id'] = $this->Session->read('Auth.User.id');
            if ($this->User->alteraPassword($this->request->data)) {
                $this->Session->setFlash(__('Senha Alterada com Sucesso'), 'default',
                    ['class' => 'alert success']);
                $this->redirect('/');
            }
        }
    }

    public function alterar_senha_email($userId)
    {
        if ($this->request->is('post')) {
            if ($this->request->data['User']['definir_nova_senha'] == 1) {
                if ($this->request->data['User']['novasenha1'] == $this->request->data['User']['novasenha2']) {
                    $this->Session->setFlash('A senha do Email do Usuario sera alterada em breve. Os dados serao enviados por sms',
                        'default', ['class' => 'alert alert-success']);
                    CakeResque::enqueue(
                        'default', 'UserShell',
                        ['changeEmailPassword', $userId, $this->request->data['User']['senhanova1']]
                    );
                    $this->Redirect('/');
                } else {
                    $this->Session->setFlash('Problemas com a Password. Tente novamente', 'default',
                        ['class' => 'alert alert-danger']);
                }
            } elseif ($this->request->data['User']['usar_senha_aleatoria']) {
                $this->request->data['User']['novasenha1'] = $this->request->data['User']['senha_aleatoria'];
                $this->User->alteraPassword($this->request->data);
                $this->Session->setFlash('A senha do Email do Usuario sera alterada em breve. Os dados serao enviados por sms',
                    'default', ['class' => 'alert alert-success']);
                CakeResque::enqueue(
                    'default', 'UserShell',
                    ['changeEmailPassword', $userId, $this->request->data['User']['senhanova1']]
                );
                $this->Redirect('/');
            }
        }

        $randomPassword = $this->User->generatePassword();
        $this->set(compact('randomPassword', 'userId'));
    }

    public function alterar_senha_sistema($userId, $defaultPassword = '')
    {
        if ($this->request->is('post')) {
            if ($this->request->data['User']['definir_nova_senha'] == 1) {
                if ($this->User->alteraPassword($this->request->data)) {
                    $this->Session->setFlash('Senha Alterada Com Sucesso. Uma SMS e um Email serao enviados para o usuario com a notificacao',
                        'default', ['class' => 'alert alert-success']);

                    $this->Redirect('/');
                } else {
                    $this->Session->setFlash('Problemas com a Password. Tente novamente', 'default',
                        ['class' => 'alert alert-danger']);
                }
            } elseif ($this->request->data['User']['usar_senha_aleatoria']) {
                $this->request->data['User']['novasenha1'] = $this->request->data['User']['senha_aleatoria'];
                $this->request->data['User']['novasenha2'] = $this->request->data['User']['senha_aleatoria'];
                $this->User->alteraPassword($this->request->data);
                $this->Session->setFlash('Senha Alterada Com Sucesso. Uma SMS e um Email serao enviados para o usuario com a notificacao',
                    'default', ['class' => 'alert alert-success']);

                $this->Redirect('/');
            } elseif ($this->request->data['User']['usar_senha_padrao']) {
                $this->request->data['User']['novasenha1'] = $this->request->data['User']['senha_padrao'];
                $this->request->data['User']['novasenha2'] = $this->request->data['User']['senha_padrao'];
                $this->User->alteraPassword($this->request->data);
                $this->Session->setFlash('Senha Alterada Com Sucesso. Uma SMS e um Email serao enviados para o usuario com a notificacao',
                    'default', ['class' => 'alert alert-success']);

                $this->Redirect('/');
            } else {
                $this->Session->setFlash('Nao foi solicitada nenhuma opcao de senha. Tente Novamente', 'default',
                    ['class' => 'alert alert-success']);
            }
        }
        $randomPassword = $this->User->geraPassword();
        $this->set(compact('randomPassword', 'defaultPassword', 'userId'));
    }

    function beforeFilter()
    {

        parent::beforeFilter();

        $this->Auth->allow(['login', 'logout', 'opauth_complete', 'perfil', 'google_login', 'googlelogin','get_profile_picture']);
        $this->Security->unlockedActions = ['login'];

        if ($this->action == 'login' or $this->action == 'logout') {

            $this->Security->validatePost = false;


        }

    }

    public function get_profile_picture($username){
        $AmazonS3 = new OpenSGAAmazonS3();
        $signedUrl = '';
        $user = $this->User->findByUsername($username);
        if(empty($user)){
            $file = '/Fotos/profile2.png';
            $signedUrl = $AmazonS3->getSignedUrl($file,null,'+1000 minutes');
            $this->response->body($signedUrl);
            return $this->response;
        }
        if($this->User->isAluno($user['User']['id'])){
            $aluno = $this->User->Entidade->Aluno->findByUserId($user['User']['id']);

            $file = '/Fotos/Estudantes/' . $aluno['Aluno']['ano_ingresso'] . '/' . $aluno['Aluno']['codigo'] . '.jpg';
       if (!$signedUrl = $AmazonS3->getSignedUrl($file,null,'+1000 minutes')) {
           $file = '/Fotos/profile2.png';
           $signedUrl = $AmazonS3->getSignedUrl($file,null,'+1000 minutes');
           $this->response->body($signedUrl);
       }
            $this->response->body($signedUrl);
        } else{
            $file = '/Fotos/profile2.png';
            $signedUrl = $AmazonS3->getSignedUrl($file,null,'+1000 minutes');
            $this->response->body($signedUrl);

        }

        return $this->response;
    }

    function beforeRender()
    {
        parent::beforeRender();

        if ($this->action == 'logout') {
            $this->response->disableCache();
        }
    }

    /**
     * Tira a foto do aluno usando a WebCam
     */
    public function captura_foto()
    {
        if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
            $jpg = $GLOBALS["HTTP_RAW_POST_DATA"];
            $entidade_id = $this->Session->read('SGATemp.entidade_id_4_foto');
            $this->log('Erro ao capturar foto' . $entidade_id, 'testelog');
            if ($entidade_id != null) {
                $filename = APP . $entidade_id . ".jpg";
                file_put_contents($filename, $jpg);
            }
        } else {
            $this->log('Erro ao capturar foto');
        }
    }

    public function docente_login()
    {
        if ($this->Auth->user()) {
            $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }

        $this->redirect(['action' => 'login', 'docente' => false]);
    }

    public function docente_logout()
    {

        $this->redirect(['action' => 'logout', 'docente' => false]);
    }

    public function docente_mostrar_foto($codigo)
    {
        $this->viewClass = 'Media';
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        $this->loadModel('Aluno');
        $this->Aluno->contain();
        $aluno = $this->Aluno->findByCodigo($codigo);
        if (!empty($aluno)) {
            App::uses('File', 'Utility');
            $path = APP . 'Assets' . DS . 'Fotos' . DS . 'Estudantes' . DS . $aluno['Aluno']['ano_ingresso'] . DS;

            $file_path = $path . $codigo . '.jpg';
            $folder_novo = new Folder($path);

            $file = new File($file_path);

            if (!$file->exists()) {
                $codigo = 'default_profile_picture';
                $path = WWW_ROOT . DS . 'img' . DS;
            }


            $params = [
                'id' => $codigo . '.jpg',
                'name' => 'fotografia',
                'extension' => 'jpg',
                'mimeType' => [
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                'path' => $path,
            ];
            $this->set($params);
        } else {
            throw new NotFoundException('Estudante não encontrado. Mostrar foto');
        }
    }

    public function docente_perfil()
    {
        $this->redirect(['controller' => 'docentes', 'action' => 'meu_perfil', 'docente' => true]);
    }

    function docente_trocar_senha($id = null)
    {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('Usuário não encontrado');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $senha_antiga = $this->data['User']['senha_antiga'];
            $senha_nova1 = $this->data['User']['nova_senha'];
            $senha_nova2 = $this->data['User']['confirmar_nova_senha'];

            $senha_bd = $this->User->findById($this->Session->read('Auth.User.id'));
            $storedHash = $senha_bd['User']['password'];
            $newHash = Security::hash($senha_antiga, 'blowfish', $storedHash);
            $correct = $storedHash == $newHash;
            if ($correct) {
                if ($senha_nova1 == $senha_nova2) {
                    $this->request->data['User']['password'] = Security::hash($senha_nova1, 'blowfish');
                    $this->User->id = $id;
                    $this->User->set('password', Security::hash($senha_nova1, 'blowfish'));
                    if ($this->User->save()) {
                        $this->Session->setFlash(__('Senha alterada com sucesso'), 'default',
                            ['class' => 'alert success']);
                        $this->redirect(['controller' => 'pages', 'action' => 'home', 'docente' => true]);
                    } else {
                        $this->Session->setFlash(sprintf(__('Erro ao alterar a senha. Por favor, tente de novo',
                            true),
                            'user'), 'default', ['class' => 'alert error']);
                    }
                } else {
                    $this->Session->setFlash(sprintf(__('As senhas introduzidas não são idênticas', true), 'user'),
                        'default', ['class' => 'alert error']);
                }
            } else {
                $this->Session->setFlash(sprintf(__('A senha antiga nao confere', true), 'user'), 'default',
                    ['class' => 'alert error']);
            }
        }

        $this->User->contain(['Entidade']);
        $user = $this->User->findById($id);
        $this->set(compact('user'));
    }

    function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(sprintf(__('ID do Usuário Inválido', true), 'user'), 'flasherror');
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(sprintf(__('Os dados do usuário foram actualizados com sucesso', true),
                    'user'), 'flashok');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash('Erro ao gravar dados. Por favor tente de novo.', 'flasherror');
            }
        }
        if (empty($this->data)) {
            $this->data = $this->User->read(null, $id);
        }
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    public function estudante_after_fb_login()
    {
        $fb = new Facebook\Facebook(Configure::read('FB_AUTH'));

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

// Logged in
        echo '<h3>Access Token</h3>';
        var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId(Configure::read('FB_APP_ID'));
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,name', $accessToken->getValue());
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user = $response->getGraphUser();

        debug($user);

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
    }

    public function estudante_login()
    {
        if ($this->Auth->user()) {
            $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
        $this->redirect(['action' => 'login', 'estudante' => false]);
    }

    public function estudante_logout()
    {
        $this->redirect(['action' => 'logout', 'estudante' => false]);
    }

    public function estudante_mostrar_foto($userId)
    {
        $this->viewClass = 'Media';
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        $this->loadModel('Aluno');
        $this->Aluno->contain('Entidade');
        $aluno = $this->User->Entidade->Aluno->find('first',
            ['conditions' => ['Entidade.user_id' => $userId]]);
        if (!empty($aluno)) {
            App::uses('File', 'Utility');
            $path = APP . 'Assets' . DS . 'Fotos' . DS . 'Estudantes' . DS . $aluno['Aluno']['ano_ingresso'] . DS;

            $file_path = $path . $aluno['Aluno']['codigo'] . '.jpg';
            $folder_novo = new Folder($path);

            $file = new File($file_path);

            if (!$file->exists()) {
                $codigo = 'default_profile_picture';
                $path = WWW_ROOT . DS . 'img' . DS;
            }


            $params = [
                'id' => $aluno['Aluno']['codigo'] . '.jpg',
                'name' => 'fotografia',
                'extension' => 'jpg',
                'mimeType' => [
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                'path' => $path,
            ];
            $this->set($params);
        } else {
            throw new NotFoundException('Estudante não encontrado. Mostrar foto');
        }
    }

    public function estudante_perfil()
    {
        $this->redirect(['controller' => 'alunos', 'action' => 'perfil', 'estudante' => true]);
    }

    public function estudante_trocar_senha()
    {
        $id = $this->Session->read('Auth.User.id');
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('Usuário não encontrado');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $senhAntiga = $this->request->data['User']['senha_antiga'];
            $senhaNova1 = $this->request->data['User']['nova_senha'];
            $senhaNova2 = $this->request->data['User']['confirmar_nova_senha'];

            $senhaBd = $this->User->findById($this->Session->read('Auth.User.id'));
            $storedHash = $senhaBd['User']['password'];
            $newHash = Security::hash($senhAntiga, 'blowfish', $storedHash);
            $correct = $storedHash == $newHash;
            if ($correct) {
                if ($senhaNova1 == $senhaNova2) {
                    $this->request->data['User']['password'] = Security::hash($senhaNova1, 'blowfish');
                    $this->User->id = $id;
                    $this->User->set('password', Security::hash($senhaNova1, 'blowfish'));
                    if ($this->User->save()) {
                        $this->Session->setFlash(__('Senha alterada com sucesso'), 'default',
                            ['class' => 'alert alert-success']);
                        $this->redirect(['controller' => 'pages', 'action' => 'home', 'estudante' => true]);
                    } else {
                        $this->Session->setFlash(sprintf(__('Erro ao alterar a senha. Por favor, tente de novo',
                            true),
                            'user'), 'default', ['class' => 'alert alert-danger']);
                    }
                } else {
                    $this->Session->setFlash(sprintf(__('As senhas introduzidas não são idênticas', true), 'user'),
                        'default', ['class' => 'alert alert-danger']);
                }
            } else {
                $this->Session->setFlash(sprintf(__('A senha antiga nao confere', true), 'user'), 'default',
                    ['class' => 'alert alert-danger']);
            }
        }

        $this->User->contain(['Entidade' => ['Aluno' => ['Curso']]]);
        $user = $this->User->findById($id);
        if ($user['User']['ultimo_login'] == null) {
            if (!password_verify($user['Entidade']['Aluno']['codigo'], $user['User']['password'])) {
                $arrayUser = [
                    'User' => [
                        'user_id' => $user['User']['id'],
                        'novasenha1' => $user['User']['codigocartao'],
                        'novasenha2' => $user['User']['codigocartao'],
                    ],
                ];
                $this->User->alteraPassword($arrayUser);
            }
        }
        $this->set(compact('user'));
        $this->layout = "guest_users";
    }

    public function faculdade_alterar_senha_sistema($userId, $defaultPassword = '')
    {

        if ($this->request->is('post')) {
            $arrayRetorno = [
                'controller' => $this->request->params['named']['return_controller'],
                'action' => $this->request->params['named']['return_action'],
                $this->request->params['named']['return_id'],
            ];
            if ($this->request->data['User']['definir_nova_senha'] == 1) {
                if ($this->User->alteraPassword($this->request->data)) {
                    $this->Session->setFlash('Senha Alterada Com Sucesso. Uma SMS e um Email serao enviados para o usuario com a notificacao',
                        'default', ['class' => 'alert alert-success']);

                    $this->Redirect($arrayRetorno);
                } else {
                    $this->Session->setFlash('Problemas com a Password. Tente novamente', 'default',
                        ['class' => 'alert alert-danger']);
                }
            } elseif ($this->request->data['User']['usar_senha_aleatoria']) {
                $this->request->data['User']['novasenha1'] = $this->request->data['User']['senha_aleatoria'];
                $this->request->data['User']['novasenha2'] = $this->request->data['User']['senha_aleatoria'];
                $this->User->alteraPassword($this->request->data);
                $this->Session->setFlash('Senha Alterada Com Sucesso. Uma SMS e um Email serao enviados para o usuario com a notificacao',
                    'default', ['class' => 'alert alert-success']);

                $this->Redirect($arrayRetorno);
            } elseif ($this->request->data['User']['usar_senha_padrao']) {
                $this->request->data['User']['novasenha1'] = $this->request->data['User']['senha_padrao'];
                $this->request->data['User']['novasenha2'] = $this->request->data['User']['senha_padrao'];
                $this->User->alteraPassword($this->request->data);
                $this->Session->setFlash('Senha Alterada Com Sucesso. Uma SMS e um Email serao enviados para o usuario com a notificacao',
                    'default', ['class' => 'alert alert-success']);

                $this->Redirect($arrayRetorno);
            } else {
                $this->Session->setFlash('Nao foi solicitada nenhuma opcao de senha. Tente Novamente', 'default',
                    ['class' => 'alert alert-success']);
            }
        }
        $randomPassword = $this->User->geraPassword();
        $this->set(compact('randomPassword', 'defaultPassword', 'userId'));
        $this->render('alterar_senha_sistema');

    }

    public function faculdade_login()
    {
        $this->redirect(['action' => 'login', 'docente' => false, 'faculdade' => false]);
    }

    public function faculdade_logout()
    {
        $this->Auth->logout();
        $this->Session->delete('SGAConfig');
        $this->redirect($this->redirect($this->Auth->logout()));
    }

    public function faculdade_mostrar_foto($codigo)
    {
        $this->viewClass = 'Media';
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        App::uses('File', 'Utility');
        $codigo = 'default_profile_picture';
        $path = WWW_ROOT . DS . 'img' . DS;
        $params = [
            'id' => $codigo . '.jpg',
            'name' => 'fotografia',
            'extension' => 'jpg',
            'mimeType' => [
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
            'path' => $path,
        ];
        $this->set($params);
    }

    function faculdade_trocar_senha($id = null)
    {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('Usuário não encontrado');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $senha_antiga = $this->data['User']['senhaantiga'];
            $senha_nova1 = $this->data['User']['novasenha1'];
            $senha_nova2 = $this->data['User']['novasenha2'];

            $senha_bd = $this->User->findById($this->Session->read('Auth.User.id'));
            $storedHash = $senha_bd['User']['password'];
            $newHash = Security::hash($this->request->data['User']['senhaantiga'], 'blowfish', $storedHash);
            $correct = $storedHash == $newHash;
            if ($correct) {
                if ($senha_nova1 == $senha_nova2) {
                    $this->request->data['User']['password'] = Security::hash($senha_nova1, 'blowfish');
                    $this->User->id = $id;
                    $this->User->set('password', Security::hash($senha_nova1, 'blowfish'));
                    if ($this->User->save()) {
                        $this->Session->setFlash(__('Senha alterada com sucesso'), 'default',
                            ['class' => 'alert success']);
                        $this->redirect('/');
                    } else {
                        $this->Session->setFlash(sprintf(__('Erro ao alterar a senha. Por favor, tente de novo',
                            true),
                            'user'), 'default', ['class' => 'alert error']);
                    }
                } else {
                    $this->Session->setFlash(sprintf(__('As senhas introduzidas não são idênticas', true), 'user'),
                        'default', ['class' => 'alert error']);
                }
            } else {
                $this->Session->setFlash(sprintf(__('A senha antiga nao confere', true), 'user'), 'default',
                    ['class' => 'alert error']);
            }
        }
    }

    function login()
    {
        if ($this->Session->read('Auth.User')) {
            $this->Flash->info('Já está logado');

            $this->redirect(['controller' => 'pages', 'action' => 'home']);
        }
        if ($this->request->is('post')) {
            if (!isset($this->request->data['User'])) {
                $this->redirect('/');
            }
            $username = $this->request->data['User']['username'];
            if (is_numeric($username)) {
                $this->User->Entidade->Aluno->contain(['Entidade' => ['User']]);
                $aluno = $this->User->Entidade->Aluno->findByCodigo($username);
                if ($aluno) {
                    $ultimo_login = $aluno['Entidade']['User']['ultimo_login'];
                    if ($ultimo_login == null) {
                        $this->Auth->login($aluno['Entidade']['User']);
                        $entidade = ['Entidade' => $aluno['Entidade']];
                        $this->Session->write('Auth.User.name', $entidade['Entidade']['name']);
                        //Temos de Certificar que o Aro existe, principalmente para estudantes importados
                        $aro = $this->User->Aro->find('first',
                            [
                                'conditions' => [
                                    'model' => $this->User->alias,
                                    'foreign_key' => $aluno['Entidade']['User']['id'],
                                ],
                            ]);
                        if (empty($aro)) {
                            $new_aro = [
                                'parent_id' => $aluno['Entidade']['User']['group_id'],
                                'foreign_key' => $aluno['Entidade']['User']['id'],
                                'model' => $this->User->alias,
                            ];
                            $this->User->Aro->create();
                            $this->User->Aro->save($new_aro);
                        }
                        $this->User->actualizaUltimoLogin($aluno['Entidade']['User']['id']);
                        $this->User->actualizaLoginHistory($aluno['Entidade']['User']['id'],
                            $aluno['Entidade']['User']['group_id'], date('Y-m-d H:i:s'),
                            $this->request->clientIp());
                        $message = [
                            'Command' => 'OpenSGAAcl',
                            'Action' => '--username',
                            'username' => $aluno['Entidade']['User']['username'],
                        ];
                        CakeRabbit::publish($message);
                        $this->User->createOrUpdateOpenFire($aluno['Entidade']['User']['username'],$this->request->data['User']['password'],$aluno['Entidade']['name']);
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'trocar_senha',
                            '?' => ['primeiro' => 'login'],
                            'estudante' => true,
                        ]);
                    } else {
                        $this->Flash->error('Esta conta ja esta activa. Se esqueceu a senha, contacte o Registo Academico da sua Faculdade');
                        $this->redirect(['controller' => 'users', 'action' => 'login']);
                    }
                }
            }
            if ($this->Auth->login()) {
                $password_login = $this->request->data['User']['password'];
                $this->Session->write('SGAConfig.ano_lectivo_id', Configure::read('OpenSGA.ano_lectivo_id'));
                $this->Session->write('SGAConfig.ano_lectivo', Configure::read('OpenSGA.ano_lectivo'));
                $this->Session->write('Config.language', 'por');
                $User = $this->Session->read('Auth.User');
                if (!in_array($User['estado_objecto_id'], [1, null])) {
                    $this->redirect(['action' => 'logout']);
                }
                $entidade = $this->User->Entidade->findByUserId($User['id']);
                $this->Session->write('Auth.User.name', $entidade['Entidade']['name']);
                //Temos de Certificar que o Aro existe, principalmente para estudantes importados
                $aro = $this->User->Aro->find('first',
                    ['conditions' => ['model' => $this->User->alias, 'foreign_key' => $User['id']]]);
                if (empty($aro)) {
                    $new_aro = [
                        'parent_id' => $User['group_id'],
                        'foreign_key' => $User['id'],
                        'model' => $this->User->alias,
                    ];
                    $this->User->Aro->create();
                    $this->User->Aro->save($new_aro);
                }

                // Vamos pegar todos os grupos e colocar na Sessao
                $this->User->GroupsUser->contain('Group');
                $grupos = $this->User->GroupsUser->find('all', [
                    'conditions' => ['user_id' => $User['id']],
                    'fields' => ['GroupsUser.group_id', 'Group.name'],
                ]);
                $grupos_combine = Hash::combine($grupos, '{n}.Group.id', '{n}.Group.name');
                //Actualizamos o Ultimos Login
                $this->User->id = $User['id'];
                $this->User->set('ultimo_login', date('Y-m-d H:i:s'));
                $this->User->save();
                $this->User->actualizaLoginHistory($User['id'], $User['group_id'], date('Y-m-d H:i:s'),
                    $this->request->clientIp());
                $this->Session->write('Auth.User.Groups', $grupos_combine);
                $message = [
                    'Command' => 'OpenSGAAcl',
                    'Action' => '--username',
                    'username' => $User['username'],
                ];
                RabbitMQ::publish($message);
                $this->User->createOrUpdateOpenFire($User['username'],$this->request->data['User']['password'],$entidade['Entidade']['name']);
                if ($User['group_id'] == 1) {
                    $unidade_organicas = $this->User->Funcionario->UnidadeOrganica->find('list');
                    $this->Session->write('Auth.User.unidade_organicas', $unidade_organicas);
                    $this->Session->write('Auth.User.unidade_organica_id', 29);
                    //die(var_dump($unidade_organicas));
                } elseif ($User['group_id'] == 3) {
                    if ($password_login == 'dra02062013') {
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'trocar_senha',
                            $User['id'],
                            'estudante' => true,
                        ]);
                    }
                    $this->redirect(['controller' => 'pages', 'action' => 'home', 'estudante' => true]);
                } elseif ($User['group_id'] == 4) {
                    if (in_array($password_login, ['12345', 'siga12345UEM', 'uem1234567dra'])) {
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'trocar_senha',
                            'docente' => true,
                            $User['id'],
                        ]);
                    }
                    $this->redirect(['controller' => 'pages', 'action' => 'home', 'docente' => true]);
                } elseif ($User['group_id'] == 2) {
                    $this->User->contain([
                        'Funcionario' => [
                            'UnidadeOrganica',
                        ],
                    ]);
                    $user_data = $this->User->findById($User['id']);
                    $this->Session->write('Auth.User.unidade_organica_id',
                        $user_data['Funcionario'][0]['unidade_organica_id']);
                    $this->Session->write('Auth.User.unidade_organica',
                        $user_data['Funcionario'][0]['UnidadeOrganica']['name']);
                    if ($this->User->isFromFaculdade($User['id'])) {
                        if (in_array($password_login, ['12345', 'siga12345UEM', 'uem1234567dra'])) {
                            $this->redirect([
                                'controller' => 'users',
                                'action' => 'trocar_senha',
                                $User['id'],
                                'faculdade' => true,
                            ]);
                        }
                        $this->redirect(['controller' => 'pages', 'action' => 'home', 'faculdade' => true]);
                    }
                }
                if (in_array($password_login, ['12345', 'siga12345UEM', 'uem1234567dra'])) {
                    $this->redirect(['controller' => 'users', 'action' => 'trocar_senha', $User['id']]);
                }
                $this->redirect(['controller' => 'pages', 'action' => 'home']);
            } else {
                if ($this->request->data['User']['password'] == 'dra02062013' || $this->request->data['User']['password'] == '12345') {
                    $this->Session->setFlash(__('O Nome de Usuario ou a senha estao incorrectos. </br> Esta a Usar a senha inicial de acesso ao SIGA. Verifique se ainda nao trocou a senha anteriormente. Caso nunca tenha tentado aceder ao SIGA usando este nome de Usuario, contacte com Urgencia a Direccao de Registo Academico'),
                        'default', ['class' => 'alert alert-danger']);
                } else {
                    $this->Session->setFlash(__('Nome de Usuário ou Senha Invalidos'), 'default',
                        ['class' => 'alert alert-danger']);
                }
            }
        }

        $this->layout = 'login';
    }

    function logout()
    {
        $this->Auth->logout();
        $this->Session->delete('SGAConfig');
        $this->redirect($this->redirect($this->Auth->logout()));
    }

    public function mostrar_foto($codigo)
    {
        $this->User->Entidade->Aluno->contain('Entidade');
        $aluno = $this->User->Entidade->Aluno->find('first',
            ['conditions' => ['Entidade.user_id' => $codigo]]);
        if (!empty($aluno)) {
            $path = S3BUCKET . '/Fotos/Estudantes/' . $aluno['Aluno']['ano_ingresso'] . '/' . $aluno['Aluno']['codigo'] . '.jpg';
            $path = S3BUCKET . '/Fotos/profile2.png';
            $s3Client = Aws\S3\S3Client::factory([
                'key' => S3KEY,
                'secret' => S3SECRET,
                'region' => S3REGION,
            ]);

            $request = $s3Client->get($path);
            $signedUrl = $s3Client->createPresignedUrl($request, '+10 minutes');
            $this->autoRender = false;

            return $signedUrl;


        } else {
            throw new NotFoundException('Estudante não encontrado. Mostrar foto');
        }

    }

    public function opauth_complete()
    {
        debug($this->data);
    }

    public function perfil()
    {


        if (!$this->Auth->user()) {
            $this->redirect(['action' => 'login']);
        }
        $groupId = $this->Session->read('Auth.User.group_id');
        if ($groupId == 4) {
            $this->redirect(['controller' => 'docentes', 'action' => 'meu_perfil']);
        }
        if ($groupId == 3) {
            $this->redirect(['controller' => 'alunos', 'action' => 'meu_perfil']);
        }
        $this->redirect(['controller' => 'funcionarios', 'action' => 'meu_perfil']);
    }

    function trocar_senha($id = null)
    {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('Usuário não encontrado');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $senha_antiga = $this->data['User']['senhaantiga'];
            $senha_nova1 = $this->data['User']['novasenha1'];
            $senha_nova2 = $this->data['User']['novasenha2'];

            $senha_bd = $this->User->findById($this->Session->read('Auth.User.id'));
            $storedHash = $senha_bd['User']['password'];
            $newHash = Security::hash($this->request->data['User']['senhaantiga'], 'blowfish', $storedHash);
            $correct = $storedHash == $newHash;

            if ($correct) {
                if ($senha_nova1 == $senha_nova2) {
                    $this->request->data['User']['password'] = Security::hash($senha_nova1, 'blowfish');
                    $this->User->id = $id;
                    $this->User->set('password', Security::hash($senha_nova1, 'blowfish'));
                    if ($this->User->save()) {
                        $this->Session->setFlash(sprintf(__('Senha alterada com sucesso', true), 'user'),
                            'flashok');
                        $this->redirect('/');
                    } else {
                        $this->Session->setFlash(sprintf(__('Erro ao alterar a senha. Por favor, tente de novo',
                            true),
                            'user'), 'flasherror');
                    }
                } else {
                    $this->Session->setFlash(sprintf(__('As senhas introduzidas não são idênticas', true), 'user'),
                        'flasherror');
                }
            } else {
                $this->Session->setFlash(sprintf(__('A senha antiga nao confere', true), 'user'));
            }
        }
    }


    public function docente_changeLoginProfile()
    {
        if ($this->request->is('post')) {
            $resultado = $this->User->changeLoginProfile($this->request->data);
            if ($resultado[0] == true) {
                $this->redirect(['controller' => 'pages', 'action' => 'home', 'docente' => false]);
            }

        }

    }

    public function faculdade_changeLoginProfile()
    {
        if ($this->request->is('post')) {
            $resultado = $this->User->changeLoginProfile($this->request->data);
            if ($resultado[0] == true) {
                $this->redirect(['controller' => 'pages', 'action' => 'home', 'docente' => false]);
            }
        }
    }

    public function estudante_changeLoginProfile()
    {
        if ($this->request->is('post')) {
            $resultado = $this->User->changeLoginProfile($this->request->data);
            if ($resultado[0] == true) {
                $this->redirect(['controller' => 'pages', 'action' => 'home', 'estudante' => false]);
            }
        }
    }


    /**
     * This function will makes Oauth Api reqest
     */
    public function googlelogin()
    {
        $this->autoRender = false;
        $client = new Google_Client();
        $client->setAuthConfigFile(Configure::read('OpenSGA.save_path') . DS . 'client_secret_siga.json');
        $client->setRedirectUri(GOOGLE_OAUTH_REDIRECT_URI);
        $client->setScopes(array(
            'https://www.googleapis.com/auth/plus.login',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/plus.me'
        ));
        $client->setApprovalPrompt('auto');
        $url = $client->createAuthUrl();
        $this->redirect($url);
    }

    /**
     * This function will handle Oauth Api response
     */
    public function google_login()
    {
        $this->autoRender = false;
        $client = new Google_Client();

        $client->setAuthConfigFile(Configure::read('OpenSGA.save_path') . DS . 'client_secret_siga.json');
        $client->setRedirectUri(GOOGLE_OAUTH_REDIRECT_URI);
        $client->setScopes(array(
            'https://www.googleapis.com/auth/plus.login',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/plus.me'
        ));
        $client->setApprovalPrompt('auto');


        $oauth2 = new Google_Service_Oauth2($client);
        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']); // Authenticate
            $_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
        }

        if (isset($_SESSION['access_token'])) {
            $client->setAccessToken($_SESSION['access_token']);
        }

        if ($client->getAccessToken()) {
            $_SESSION['access_token'] = $client->getAccessToken();
            $user = $oauth2->userinfo->get();
            try {
                if (!empty($user)) {

                    $result = $this->User->findByUsername($user['email']);
                    if (!empty($result)) {
                        if ($this->Auth->login($result['User'])) {

                            $this->Session->write('SGAConfig.ano_lectivo_id',
                                Configure::read('OpenSGA.ano_lectivo_id'));
                            $this->Session->write('SGAConfig.ano_lectivo', Configure::read('OpenSGA.ano_lectivo'));
                            $this->Session->write('Config.language', 'por');
                            $User = $this->Session->read('Auth.User');
                            if (!in_array($User['estado_objecto_id'], [1, null])) {
                                $this->redirect(['action' => 'logout']);
                            }
                            $entidade = $this->User->Entidade->findByUserId($User['id']);
                            $this->Session->write('Auth.User.name', $entidade['Entidade']['name']);
                            //Temos de Certificar que o Aro existe, principalmente para estudantes importados
                            $aro = $this->User->Aro->find('first',
                                ['conditions' => ['model' => $this->User->alias, 'foreign_key' => $User['id']]]);
                            if (empty($aro)) {
                                $new_aro = [
                                    'parent_id' => $User['group_id'],
                                    'foreign_key' => $User['id'],
                                    'model' => $this->User->alias,
                                ];
                                $this->User->Aro->create();
                                $this->User->Aro->save($new_aro);
                            }

                            // Vamos pegar todos os grupos e colocar na Sessao
                            $this->User->GroupsUser->contain('Group');
                            $grupos = $this->User->GroupsUser->find('all', [
                                'conditions' => ['user_id' => $User['id']],
                                'fields' => ['GroupsUser.group_id', 'Group.name'],
                            ]);
                            $grupos_combine = Hash::combine($grupos, '{n}.Group.id', '{n}.Group.name');
                            //Actualizamos o Ultimos Login
                            $this->User->id = $User['id'];
                            $this->User->set('ultimo_login', date('Y-m-d H:i:s'));
                            $this->User->save();
                            $this->User->actualizaLoginHistory($User['id'], $User['group_id'], date('Y-m-d H:i:s'),
                                $this->request->clientIp());
                            $this->Session->write('Auth.User.Groups', $grupos_combine);
                            $message = [
                                'Command' => 'OpenSGAAcl',
                                'Action' => '--username',
                                'username' => $User['username'],
                            ];
                            RabbitMQ::publish($message);
                            if ($User['group_id'] == 1) {
                                $unidade_organicas = $this->User->Funcionario->UnidadeOrganica->find('list');
                                $this->Session->write('Auth.User.unidade_organicas', $unidade_organicas);
                                $this->Session->write('Auth.User.unidade_organica_id', 29);
                                //die(var_dump($unidade_organicas));
                            } elseif ($User['group_id'] == 3) {

                                $this->redirect(['controller' => 'pages', 'action' => 'home', 'estudante' => true]);
                            } elseif ($User['group_id'] == 4) {

                                $this->redirect(['controller' => 'pages', 'action' => 'home', 'docente' => true]);
                            } elseif ($User['group_id'] == 2) {
                                $this->User->contain([
                                    'Funcionario' => [
                                        'UnidadeOrganica',
                                    ],
                                ]);
                                $user_data = $this->User->findById($User['id']);
                                $this->Session->write('Auth.User.unidade_organica_id',
                                    $user_data['Funcionario'][0]['unidade_organica_id']);
                                $this->Session->write('Auth.User.unidade_organica',
                                    $user_data['Funcionario'][0]['UnidadeOrganica']['name']);
                                if ($this->User->isFromFaculdade($User['id'])) {

                                    $this->redirect(['controller' => 'pages', 'action' => 'home', 'faculdade' => true]);
                                }
                            }
                            $this->redirect(['controller' => 'pages', 'action' => 'home']);
                            $this->Session->setFlash('GOOGLE_LOGIN_SUCCESS', 'default',
                                array('class' => 'message success'), 'success');
                            $this->redirect(['action' => 'after_login']);
                        } else {
                            $this->Session->setFlash('GOOGLE_LOGIN_FAILURE', 'default',
                                array('class' => 'message error'), 'error');
                            $this->redirect(['action' => 'login']);
                        }

                    } else {
                        die(debug($user));
                    }

                } else {
                    die(debug($user));
                    $this->Session->setFlash('GOOGLE_LOGIN_FAILURE', 'default', array('class' => 'message error'),
                        'error');
                    $this->redirect(['action' => 'login']);
                }
            } catch (Exception $e) {
                die(debug($e));
                $this->Session->setFlash('GOOGLE_LOGIN_FAILURE', 'default', array('class' => 'message error'), 'error');
                $this->redirect(['action' => 'login']);
            }
        }

        exit;
    }


}

?>