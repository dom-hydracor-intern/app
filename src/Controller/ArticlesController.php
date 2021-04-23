<?php
declare(strict_types=1);

namespace App\Controller;

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
        $this->loadComponent('RequestHandler'); // Include the RequestHandler
        $this->loadComponent('Flash'); // Include the FlashComponent
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        $allarticles = $this->Articles->find('all')->all();
        $this->set('articles', $allarticles);
        $this->viewBuilder()->setOption('serialize', ['articles']);

    /*
        $articles = $this->paginate($this->Articles);

        $this->set(compact('articles'));

    */
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->get($id);
        $this->set('article', $article);
        $this->viewBuilder()->setOption('serialize', ['article']);

    /*

        $article = $this->Articles->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('article'));

    */
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $article = $this->Articles->newEntity($this->request->getData());
        if ($this->Articles->save($article)) {
            $this->Flash->success(__('Your article has been saved.'));
        }
        else 
        {
            $this->Flash->error(__('Unable to add your article.'));
        }

        $this->set([
            'article' => $article,
        ]);
        $this->viewBuilder()->setOption('serialize', ['article']);

    /*
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);

        // Just added the categories list to be able to choose
        // one category for an article
        $categories = $this->Articles->Categories->find('treeList')->all();
        $this->set(compact('categories'));

    */
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $this->request->allowMethod(['patch', 'post', 'put']);
        $article = $this->Article->get($id);
        $article = $this->Article->patchEntity($article, $this->request->getData());
        if ($this->Articles->save($article)) {
            $this->Flash->success(__('Your article has been updated.'));

        }
        else 
        {
            $this->Flash->error(__('Unable to update your article.'));
        }
        $this->set([
            'article' => $article,
        ]);
        $this->viewBuilder()->setOption('serialize', ['article']);


    /*

        $article = $this->Articles->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }
        $this->set('article', $article);

    */
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $article = $this->Articles->get($id);

        if(!$this->Articles->delete($article)){
            $this->Flash->success(__('Your article has been updated.'));

        }
        else 
        {
            $this->Flash->error(__('Unable to update your article.'));
        }

    /*
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article with id: {0} has been deleted.', h($id)));
        } else {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
        }
    */
    }
}
