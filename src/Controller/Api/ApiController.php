<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Http\ServerRequest;
use Cake\I18n\Time;

class ApiController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        
        $this->loadComponent('RequestHandler'); //Enable data view

        $this->Auth->allow(['login', 'token']);
        $this->Authentication->addUnauthenticatedActions(['login','token']);

    }

    /* Your service code here*/
    
    public function login()
    {
        // $newToken = $this->request->getParam('Authentication');

        //$title = $this->request->getData('data');
        
        
        //REST Methods
        $this->request->allowMethod(['get', 'post']);
        
        $redirect = $this->request->getQuery('redirect', [
            'controller' => 'Articles',
            'action' => 'index',
        ]);

        if ($this->request->is('post')) {
                $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                $token=null;
                $success=false;
                $key=Security::getSalt();

                /*
                method to try JWT encode
                */


                $success = true;
                
                // $token=JWT::jsonEncode($token);
                
                $this->set([
                'success' => $success,
                'data' => [
                'token' =>  JWT::encode(
                    [
                        'sub' => $user['id'],
                        'iat' => $date,
                        'exp' =>  time() + 345600
                    ], 
                    
                $key
                )],
                '_serialize' => ['success', 'data']
                ]);

                return $this->redirect($redirect);
            }
            else {
            $this->Flash->error(__('Auth error. Invalid login'));
            }
        }

        $this->set('articles', $this->paginate($this->Articles));
        $this->viewBuilder()->setOption('serialize', ['success', 'data'], true);

    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);

        // $this->set('users', $this->Users->find()->all());
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }


    // token signature
    // header.payload.signature

    // $header = 
    // $token = Auth::getToken();

   // $result = Auth::_decode($token)
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) 
        {
            $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) 
                {
                    $msg = 'The user has been saved.';
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect(['action' => 'add']);
                }
                $msg = 'The user could not be saved. Please, try again.';
                $this->Flash->error(__('Unable to add the user.'));
    
            }

            extract($msg);
            
            $this->set('msg');

            $this->viewBuilder()->setOption('serialize', ['user', 'msg']);

    }



    

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
/*
    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }


        $token=null;
        $success=false;

            
            method to try JWT encode
            

            $token = JWT::encode(
                [
                    'sub' => $user['id'],
                    'iat' => $date,
                    'exp' =>  time() + 345600
                ]);

            $success = true;
            
           // $token=JWT::jsonEncode($token);
            
        $this->set([
            'success' => $success,
            'data' => [
            'token' =>  $token,
            Security::getSalt()
            
        ],'_serialize' => ['success', 'data']
        'user', $user]);
        

        $this->viewBuilder()->setOption('serialize', ['success','data']);

        header('Authorization: Bearer <token>', true, 1); 
    }

    */
}