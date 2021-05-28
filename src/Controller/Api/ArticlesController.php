<?php
declare(strict_types=1);

namespace App\Controller\Api;



use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Http\ServerRequest;
use Cake\I18n\Time;


/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash'); // Include the FlashComponent
        $this->loadComponent('RequestHandler'); //Enable data view

        $this->Auth->allow(['index','delete']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET');
        header('Access-Control-Allow-Headers: *');

        $this->request->allowMethod(['get']);

        $this->set('articles', $this->paginate($this->Articles));
        
        $this->viewBuilder()->setOption('serialize', 'articles');

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
        header('Access-Control-Allow-Methods: POST, GET');
        header('Access-Control-Allow-Headers: *');

        $this->request->allowMethod('post');
        
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);

        // Just added the categories list to be able to choose
        // one category for an article
        $categories = $this->Articles->Categories->find('treeList')->all();
        $this->set(compact('categories'));

        $this->viewBuilder()->setOption('serialize', 'article');
        $this->RequestHandler->renderAs($this, 'json');

    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id  = null)
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, DELETE');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');

        $this->request->allowMethod(['post','delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article with id: {0} has been deleted.', h($id)));
        } else {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
        }

        $this->RequestHandler->renderAs($this, 'json');
    }
}