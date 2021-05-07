<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\EventInterface;

// code additions
use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use App\Controller\Api;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add', 'token']);
        
    }

  

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        

        $this->Authentication->addUnauthenticatedActions(['login']);
   
        header('Authorization: Bearer <token>', true, 1);

    }

    public function login()
    {
        if ($this->request->is('post')) 
        {
                
                $token=null;
                $success=false;

                    /*
                    method to try JWT encode
                    */

                    $token = JWT::encode(
                        [
                            'sub' => $user['id'],
                            'iat' => $date,
                            'exp' =>  time() + 345600
                        ],
                    Security::getSalt());

                    $success = true;
                    
                    $bearer = JWT::jsonEncode($token);
                    
                    $this->set([
                        'bearer' => $bearer,
                        'success' => $success,
                        'data' => [
                            'token' =>  $token
                        ],
                        '_serialize' => ['success', 'data']
                    ]);
                    
                    

                    // success = 1 or 'success' = true
        }

        //REST Methods
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();


        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {

            // redirect to /articles after login success
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Articles',
                'action' => 'index',
            ]);
            
        
           
            return $this->redirect($redirect);
        }

        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid email or password'));
        }

       
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
        $this->set(compact('user'));
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
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect(['action' => 'add']);
                }
                
                $this->Flash->error(__('Unable to add the user.'));
    

                $token=null;
                $success=false;

                    /*
                    method to try JWT encode
                    */

                    $token = JWT::encode(
                        [
                            'sub' => $user['id'],
                            'iat' => $date,
                            'exp' =>  time() + 345600
                        ],
                    Security::getSalt());

                    $success = true;
                    
                
                    
                $this->set([
                    'success' => $success,
                    'data' => [
                    'token' =>  $token
                ],
                'user', $user]);

        }

        $this->viewBuilder()->setOption('serialize', ['success','user','data']);

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

    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }

                $token=null;
                $success=false;

                    /*
                    method to try JWT encode
                    */

                    $token = JWT::encode(
                        [
                            'sub' => $user['id'],
                            'iat' => $date,
                            'exp' =>  time() + 345600
                        ],
                    Security::getSalt());

                    $success = true;
                    
                
                    
                $this->set([
                    'success' => $success,
                    'data' => [
                    'token' =>  $token
                ],
                'user', $user]);

        }

        $this->viewBuilder()->setOption('serialize', ['success','data']);
    }
}
