<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Http\ServerRequest;
use Cake\I18n\Time;

class UsersController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        
        $this->loadComponent('RequestHandler'); //Enable data view

        $this->Auth->allow(['token','add']);

    }

    /* Your service code here*/
    
    public function login()
    {
        
        //REST Methods
        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('post')) 
        {
                $user = $this->Auth->identify();

                $this->Auth->setUser($user);
        }
        else 
        {
            $this->Flash->error(__('Auth error. Invalid login'));
        }


    }

    public function logout()
    {
        $result = $this->Authentication->getResult();

        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $this->Authentication->logout();
        }
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     *
     * 
     */
    public function index()
    {
        $user = $this->Auth->identify();

        if ($user) {
            
            $this->Auth->setUser($user);
            $allusers = $this->paginate($this->Users);

            $this->set(compact('allusers'));
        }
        
        $this->RequestHandler->renderAs($this, 'json');
    }


    /** 
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        
        header('Access-Control-Allow-Origin: *');

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) 
        {
            $user = $this->Users->patchEntity($user, $this->request->getData());
                $this->Users->save($user);
                
                $token=null;
                $success=false;
                $date = new \DateTime();
                $key=Security::getSalt();

                /*
                method to try JWT encode
                */


                $success = true;
                
                
                $this->set([
                    'success' => $success,
                    'data' => [
                    'token' =>  JWT::encode(
                        [
                            'sub' => $user['id'],
                            'iat' => $date,
                            'exp' =>  time() + 345600
                        ], 
                        $key)]
                ]);

                    $msg = 'The user has been saved.';
                    $this->Flash->success(__('The user has been saved.'));
                

                $msg = 'The user could not be saved. Please, try again.';
                $this->Flash->error(__('Unable to add the user.'));
    
        }

            $this->viewBuilder()->setOption('serialize', ['success','data']);
            $this->RequestHandler->renderAs($this, 'json');


           // return $this->redirect(['controller' => 'Articles', 'action' => 'index']);

    }


    public function token()
    {
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET');
        header('Access-Control-Allow-Headers: *');

        $this->request->allowMethod(['get']);
        
        $user = $this->Auth->identify();
        

                $token=null;
                $success=false;
                $date = new \DateTime();
                $key=Security::getSalt();


                $success = true;
                
                $this->set([
                'success' => $success,
                'data' => [
                'token' =>  JWT::encode(
                    [
                        'iat' => $date,
                        'exp' =>  time() + 345600
                    ], 
                    $key)]
                ]);
        
        

        $this->viewBuilder()->setOption('serialize', ['data']);
        $this->RequestHandler->renderAs($this, 'json');

    }

}