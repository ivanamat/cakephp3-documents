<?php

/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * Class DocumentsController
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @category CakePHP3
 * 
 * @package  Documents\Controller
 * 
 * @author Ivan Amat <dev@ivanamat.es>
 * @copyright Copyright 2016, Iván Amat
 * @license MIT http://opensource.org/licenses/MIT
 * @link https://github.com/ivanamat/cakephp3-documents
 */

namespace Documents\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Utility\Inflector;
use Documents\Controller\AppController;
use Documents\Model\Entity\Document;
/**
 * Documents Controller
 *
 * @property \App\Model\Table\DocumentsTable $Documents
 */
class DocumentsController extends AppController {

    public function initialize() {
        parent::initialize();

        if(Configure::read('Documents.auth.allow')) {
            $this->Auth->Allow(Configure::read('Documents.auth.allow'));
        }
                
        $this->loadModel('Documents.Categories');
        $this->loadComponent('Documents.Docs');
        
        return null;
    }
    
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->helpers(['Markdown.Markdown']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($slug = null) {
        $file = null;
        if(Configure::read('Documents.index')) {
            $file = Configure::read('Documents.index');
        }
        
        $results = $this->Categories->find('all')->order(['lft' => 'ASC']);
        $categories = $results->find('treeList', ['spacer' => '&nbsp;&nbsp;&nbsp;&nbsp;'])->where(['public' => 1]);
        $document = $this->Documents->find('all')->where(['slug' => 'index'])->first();
                
        if ($document == null && is_file($file)) {
            $md = file_get_contents($file);
            $document = new Document([
                'id' => 0,
                'category_id' => 0,
                'user_id' => 0,
                'title' => __('Index'),
                'slug' => 'index',
                'body' => $md,
                'created' => new \DateTime('NOW')
            ]);
        }
        
        $this->set(compact('categories', 'document'));
        $this->set('_serialize', ['categories', 'document']);
    }

    /**
     * View method
     *
     * @param string|null $id Document id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($slug = null) {
        $document = $this->Documents->find('all')->where(['slug' => $slug])->first();
        $categories = $this->Categories->find('treeList', ['spacer' => '&nbsp;&nbsp;&nbsp;&nbsp;'])
                ->where(['public' => 1])
                ->order(['lft' => 'ASC']);
        
        $categorySlug = ($document != null) ? $this->Docs->getParentSlug($slug) : $slug;
        $category = $this->Categories->find('all')->where(['slug' => $categorySlug])->first();
        
        if ($category == null && $document == null) {
            debug('Aquello que estás buscando no existe tolai!');
        }

        $this->set(compact(['document','category','categories']));
        $this->set('_serialize', ['document','category','categories']);

        if($document == null && $category != null) {
            $documents = $this->Documents->find('all')->where(['category_id' => $category->id])->toArray();
            if(count($documents) > 0) {
                $this->set(compact('documents'));
                $this->set('_serialize', ['documents']);
            }
            
            $this->render('index');
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($categoryId = null) {
        $document = $this->Documents->newEntity();
        if ($this->request->is('post')) {
            
            /**
             * Create slug for new entry
             */
            $parentCategory = $this->Categories->find('all')->where(['id' => $this->request->data['category_id']])->first();
            if($parentCategory != null):
                $this->request->data['slug'] = $parentCategory->slug . DS . Inflector::slug(strtolower($this->request->data['title']));
            else:
                $this->request->data['slug'] = Inflector::slug(strtolower($this->request->data['title']));
            endif;
            
            $document = $this->Documents->patchEntity($document, $this->request->data);
            if ($this->Documents->save($document)) {
                $this->Flash->success(__('The document has been saved.'));

                return $this->redirect(DS . $this->plugin . DS . $document->slug);
            } else {
                $this->Flash->error(__('The document could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Categories->find('treeList', ['spacer' => '&nbsp;&nbsp;&nbsp;&nbsp;'])
                ->where(['public' => 1])
                ->order(['lft' => 'ASC']);
        if($categoryId != null) {
            $this->request->data['category_id'] = $categoryId;
        }
        
        $users = $this->Documents->Users->find('list', ['limit' => 200]);
        $this->set(compact('document', 'categories', 'users'));
        $this->set('_serialize', ['document']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Document id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $document = $this->Documents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            
            /**
             * Create slug for new entry
             */
            $parentCategory = $this->Categories->find('all')->where(['id' => $this->request->data['category_id']])->first();
            if($parentCategory != null):
                $this->request->data['slug'] = $parentCategory->slug . DS . Inflector::slug(strtolower($this->request->data['title']));
            endif;
            
            $document = $this->Documents->patchEntity($document, $this->request->data);
            if ($this->Documents->save($document)) {
                $this->Flash->success(__('The document has been saved.'));

                // return $this->redirect(['action' => 'index']);
                return $this->redirect(DS . $this->plugin . DS . $document->slug);
            } else {
                $this->Flash->error(__('The document could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Categories->find('treeList', ['spacer' => '&nbsp;&nbsp;&nbsp;&nbsp;'])
                ->where(['public' => 1])
                ->order(['lft' => 'ASC']);
        $users = $this->Documents->Users->find('list', ['limit' => 200]);
        $this->set(compact('document', 'categories', 'users'));
        $this->set('_serialize', ['document']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Document id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $document = $this->Documents->get($id);
        if ($this->Documents->delete($document)) {
            $this->Flash->success(__('The document has been deleted.'));
        } else {
            $this->Flash->error(__('The document could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
