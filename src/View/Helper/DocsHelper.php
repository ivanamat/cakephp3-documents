<?php

/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * Class DocsHelper
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @category CakePHP3
 * 
 * @package  Documents\View\Helper
 * 
 * @author Ivan Amat <dev@ivanamat.es>
 * @copyright Copyright 2016, Iván Amat
 * @license MIT http://opensource.org/licenses/MIT
 * @link https://github.com/ivanamat/cakephp3-documents
 */

namespace Documents\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\View\Helper;

class DocsHelper extends Helper {
    
    private $Categories = null;
    private $Documents = null;
    
    /**
     * Initialize the helper
     */
    public function initialize(array $config) {
        parent::initialize($config);
        
        $this->Categories = TableRegistry::get('Documents.Categories');
        $this->Documents = TableRegistry::get('Documents.Documents');
    }
    
    /**
     * slugCategory
     * @param int $id
     * @return string Returns category slug
     */
    public function slugCategory($id) {
        $c = $this->Categories->get($id,['contain' => []]);
        if(!empty($c)) {
            return $c->slug;
        }
        return null;
    }
    
    /**
     * slugDocument
     * @param int $id
     * @return string Returns document slug
     */
    public function slugDocument($id) {
        $c = $this->Documents->get($id,['contain' => []]);
        if(!empty($c)) {
            return $c->slug;
        }
        return null;
    }
    
    /**
     * getCategory
     * @param type $id
     * @return object Returns category
     */
    public function getCategory($id) {
        return $this->Categories->find('all')->where(['id' => $id])->first();
    }
    
    /**
     * getParentSlug
     * @param string $slug Slug
     * @return string Parent slug
     */
    public function getParentSlug($slug) {
        $slug = explode('/', $slug);
        
        if(count($slug) > 1) {
            unset($slug[count($slug)-1]);
        }
        
        $slug = implode('/',$slug);
        
        return $slug;
    }
    
    /**
     * getRelatedDocuments
     * @param type $categoryId
     * @return array Array of documents of the children of the specified category.
     */
    public function getRelatedDocuments($categoryId) {
        if(!is_int($categoryId)) {
            return false;
        }
        
        $ids = [];
        $documents = [];
        
        $childs =  $this->Categories->find('children',['for' => $categoryId]);
            foreach ($childs as $child):
                array_push($ids, $child->id);
            endforeach;
        
        if(count($ids) > 0) {
            $documents = $this->Documents->find('all')->where(['category_id IN' => $ids])->toArray();
        }
        
        return $documents;
    }
}
