<?php

/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * Class CategoriesController
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

use Cake\Core\Configure;
use Cake\Utility\Inflector;
use Documents\Controller\AppController;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController
{

    public function initialize() {
        parent::initialize();

        if(Configure::read('Categories.auth.allow')) {
            $this->Auth->Allow(Configure::read('Categories.auth.allow'));
        }
                
        // $this->loadModel('Documents');
        return null;
    }
    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $results = $this->Categories->find('all')->order(['lft' => 'ASC']);
        $categories = $results->find('treeList',['spacer' => '&nbsp;&nbsp;&nbsp;&nbsp;']);
        $category = $this->Categories->newEntity();
        
        $this->set(compact('categories','category'));
        $this->set('_serialize', ['categories','category']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            
            /**
             * Create slug for new entry
             */
            $parentCategory = $this->Categories->find('all')->where(['id' => $this->request->data['parent_id']])->first();
            if($parentCategory != null):
                $this->request->data['slug'] = $parentCategory->slug . DS . Inflector::slug(strtolower($this->request->data['name']));
            else:
                $this->request->data['slug'] = Inflector::slug(strtolower($this->request->data['name']));
            endif;
            
            $category = $this->Categories->newEntity();
            $category = $this->Categories->patchEntity($category, $this->request->data);
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        } else {
            $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $category = $this->Categories->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            /**
             * Create slug for new entry
             */
            $parentCategory = $this->Categories->find('all')->where(['id' => $this->request->data['parent_id']])->first();
            if($parentCategory != null):
                $this->request->data['slug'] = $parentCategory->slug . DS . Inflector::slug(strtolower($this->request->data['name']));
            else:
                $this->request->data['slug'] = Inflector::slug(strtolower($this->request->data['name']));
            endif;
            
            $category = $this->Categories->patchEntity($category, $this->request->data);
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
                $this->viewBuilder()->render('index');
            }
        }

        $this->request->data = $category;
        
        // $categories = $this->Categories->ParentCategories->find('list', ['limit' => 200]);
        $results = $this->Categories->find('all')->order(['lft' => 'ASC']);
        $categories = $results->find('treeList',['spacer' => '&nbsp;&nbsp;&nbsp;&nbsp;']);
        
        // TODO: Terminar esta condicion: Buscar todos menos el que se va a editar.
        // $resultsParent = $this->Categories->find('all')->where('')->order(['lft' => 'ASC']);
        $this->set(compact('category', 'categories'));
        $this->set('_serialize', ['category']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function moveUp($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $category = $this->Categories->get($id);
        if ($this->Categories->moveUp($category)) {
            $this->Flash->success('The category has been moved Up.');
        } else {
            $this->Flash->error('The category could not be moved up. Please, try again.');
        }
        return $this->redirect($this->referer(['action' => 'index']));
    }

    public function moveDown($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $category = $this->Categories->get($id);
        if ($this->Categories->moveDown($category)) {
            $this->Flash->success('The category has been moved down.');
        } else {
            $this->Flash->error('The category could not be moved down. Please, try again.');
        }
        return $this->redirect($this->referer(['action' => 'index']));
    }
}
