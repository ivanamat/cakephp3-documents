<?php

/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * File Documents/view.ctp
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


use Cake\Core\Configure;

if(Configure::read('Documents.home')) {
    $home = Configure::read('Documents.home');
} else {
    $home = ['plugin' => false, 'controller' => 'Pages', 'action' => 'display', 'home'];
}

$params = $this->request->params;

$title = null;
if(isset($category) && $category != null) {
    $title = $category->name;
}
if(isset($document) && $document != null) {
    $title = $document->title;
}
if($title != null) {
    $this->assign('title', $title);
}
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li><?php echo $this->Html->link(__('Home'), $home); ?></li>
        <li>
            <?php 
            if($params['controller'] == 'Documents' && $params['action'] == 'index'):
                echo '<b>'.$this->Html->link(__('Index'), ['plugin' => 'Documents', 'controller' => 'Documents', 'action' => 'index']).'</b>'; 
            else:
                echo $this->Html->link(__('Index'), ['plugin' => 'Documents', 'controller' => 'Documents', 'action' => 'index']); 
            endif;
            ?>
        </li>
        <li>
            <?php 
            if($params['controller'] == 'Categories'):
                echo '<b>'.$this->Html->link(__('Document categories'), ['plugin' => 'Documents', 'controller' => 'Categories', 'action' => 'index']).'</b>'; 
            else:
                echo $this->Html->link(__('Document categories'), ['plugin' => 'Documents', 'controller' => 'Categories', 'action' => 'index']); 
            endif;
            ?>
        </li>
        <li><hr /></li>
        <li class="heading"><?php echo __('Categories') ?></li>
        <?php
        $rows = $categories->toArray();
        foreach ($rows as $id => $categoryItem):
            $slug = $this->Docs->slugCategory($id);
            if(isset($params['slug']) && $slug == $this->Docs->getParentSlug($params['slug'])) {
                echo '<li><a href="/' . $this->plugin . DS . $slug . '"><strong>'.$categoryItem.'</strong></a></li>';
            } else {
                echo '<li><a href="/' . $this->plugin . DS . $slug . '">'.$categoryItem.'</a></li>';
            }
        endforeach;
        ?>
    </ul>
</nav>

<div class="documents view large-9 medium-8 columns content">
    <?php echo $this->element('document'); ?>
</div>