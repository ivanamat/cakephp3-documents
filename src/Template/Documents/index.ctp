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
    # Category
    if(isset($category)) {
        echo '<h1 class="left">'.$category->name.'</h1>';
            # Create document button
            if($this->request->session()->check('Auth.User.id') && $this->AclManager->check(['Users' => ['id' => $this->request->session()->read('Auth.User.id')]],'controllers/Documents/Documents/add')) {
                echo '<div class="right">' . $this->Html->link(__('New document'),['controller' => false, 'action' => 'add',$category->id],['class' => 'button']) . '</div>';
            }
            echo '<div class="clearfix"></div>';
        echo '<hr />';
    }
    
    # Document
    if (isset($document)) {
        echo $this->element('document');
    }

    # Documents
    if(isset($documents)) {
        echo $this->element('documents');
    }

    # Show related documents
    if(!isset($document) && !isset($documents) && isset($category)) {
        $documents = $this->Documents->getRelatedDocuments($category->id);
        if(count($documents) > 0) {
            echo $this->element('documents',['title' => __('Related documents'), 'documents' => $documents, 'related' => true]);
        }
    }
    
    # Show 'create document' advice
    if (!isset($document) && !isset($documents)) {
        $element = ($params['controller'] == 'Documents' && 
                $params['action'] == 'index') ? 'add_index' : 'add_document' ;
        echo $this->element($element);
    }
    ?>
    
</div>
