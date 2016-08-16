<?php

/**
 * CakePHP 3.x - MD Documents
 * 
 * PHP version 5
 * 
 * Class DocumentsHelper
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
 * @link https://github.com/ivanamat/cakephp3-markdown
 */

namespace Documents\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\View\Helper;

class DocumentsHelper extends Helper {
    
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
    
    public function slugCategory($id) {
        $c = $this->Categories->get($id,['contain' => []]);
        if(!empty($c)) {
            return $c->slug;
        }
        return null;
    }
    
    public function slugDocument($id) {
        $c = $this->Documents->get($id,['contain' => []]);
        if(!empty($c)) {
            return $c->slug;
        }
        return null;
    }

    public function getParentSlug($slug) {
        $slug = explode('/', $slug);
        
        if(count($slug) > 1) {
            unset($slug[count($slug)-1]);
        }
        
        $slug = implode('/',$slug);
        
        return $slug;
    }
}
