<?php

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
            if($params['controller'] == 'Categories' && $params['action'] == 'index'):
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
            $slug = $this->Documents->slugCategory($id);
            if(isset($this->request->params['slug']) && $slug == $this->request->params['slug']) {
                echo '<li><a href="/' . $this->plugin . DS . $slug . '"><strong>'.$categoryItem.'</strong></a></li>';
            } else {
                echo '<li><a href="/' . $this->plugin . DS . $slug . '">'.$categoryItem.'</a></li>';
            }
        endforeach;
        ?>
    </ul>
</nav>

<div class="documents index large-9 medium-8 columns content">
    <?php 
    if(isset($category)) {
        echo '<h1 class="left">'.$category->name.'</h1>';
            echo '<div class="right">' . $this->Html->link(__('New document'),['controller' => false, 'action' => 'add',$category->id],['class' => 'button']) . '</div>';
            echo '<div class="clearfix"></div>';
        echo '<hr />';
    }

    if ($document != null) {
        ?>
        <h1 class="left"><?php echo $document->title; ?></h1>
        <?php if($document->id != 0): ?>
        <div class="right">
            <?php echo $this->Html->link(__('Edit'), ['controller' => false,'action' => 'edit', $document->id],['class' => 'button']); ?> 
            <?php echo $this->Form->postLink(__('Delete'), 
                ['plugin' => 'Documents', 'controller' => false, 'action' => 'delete', $document->id], 
                ['class' => 'button alert','confirm' => __('Are you sure you want to delete {0}?', $document->title)]); ?>
        </div>
        <?php endif; ?>
        <div class="clearfix"></div>
        <hr />
        <?php 
        echo $this->Markdown->parse($document->body);
    }
    
    if ($document == null && !isset($documents) || $document == null && count($documents) == 0) {
        $element = ($params['controller'] == 'Documents' && 
                $params['action'] == 'index') ? 'add_index' : 'add_document' ;
        echo $this->element($element);
    }
    ?>
    <?php if(isset($documents) && $documents != null): ?>
        <h3>Documents</h3>
        <?php if(isset($documents) && $documents != null): ?>
            <ul class="side-nav">
            <?php foreach ($documents as $document): ?>
                <li><?php echo $this->Html->link($document->title, DS.$this->request->params['controller'].DS.$document->slug); ?></li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
                <p><?php echo __('No documents'); ?></p>
        <?php endif; ?>
    <?php endif; ?>
    
</div>
