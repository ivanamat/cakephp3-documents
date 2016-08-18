<?php
/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * File Element/add_document.ctp
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @category CakePHP3
 * 
 * @author Ivan Amat <dev@ivanamat.es>
 * @copyright Copyright 2016, Iván Amat
 * @license MIT http://opensource.org/licenses/MIT
 * @link https://github.com/ivanamat/cakephp3-documents
 */
?>

<h3><?php echo __('Documents'); ?></h3>
<div class="panel info">
    <div class="panel-body">
        <p><?php echo __('There are no documents in the category {0}', $category->name); ?></p>
        <p><?php echo $this->Html->link(__('Create document'),['controller' => false,'action' => 'add',$category->id]); ?></p>
    </div>
</div>