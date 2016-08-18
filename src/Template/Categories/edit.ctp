<?php

/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * File Categories/edit.ctp
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
            if(isset($this->request->params['slug']) && $slug == $this->request->params['slug']) {
                echo '<li><a href="/' . $this->plugin . DS . $slug . '"><strong>'.$categoryItem.'</strong></a></li>';
            } else {
                echo '<li><a href="/' . $this->plugin . DS . $slug . '">'.$categoryItem.'</a></li>';
            }
        endforeach;
        ?>
    </ul>
</nav>

<div class="categories index large-6 medium-6 columns content">
    <h1><?php echo __('Categories') ?></h1>
    <hr />
    <table cellpadding="0" cellspacing="0">
        <tbody>
            <?php foreach ($categories as $id => $category): ?>
            <tr>
                <td><?php echo $category; ?></td>
                <td>
                    <?php echo $this->Form->postLink(__('down'), ['action' => 'moveDown', $id], ['confirm' => __('Are you sure you want to move down # {0}?', $category)]) ?> ·
                    <?php echo $this->Form->postLink(__('up'), ['action' => 'moveUp', $id], ['confirm' => __('Are you sure you want to move up # {0}?', $category)]) ?>
                </td>
                <td class="actions">
                    <?php echo $this->Html->link(__('Edit'), ['action' => 'edit', $id]) ?> · 
                    <?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $id], ['confirm' => __('Are you sure you want to delete # {0}?', $category)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="large-3 medium-2 columns content">
    <div class="panel panel-default">
        <div class="panel-body">
            <h4><?php echo __('Edit category') ?></h4>
            <?php echo $this->Form->create($category,['url' => ['controller' => 'Categories', 'action' => 'edit',$this->request->data['id']]]) ?>
                <fieldset>
                    <?php
                        echo $this->Form->input('id', ['type' => 'hidden']);
                        echo $this->Form->input('parent_id', ['options' => $categories, 'empty' => true, 'escape' => false]);
                        echo $this->Form->input('name');
                        echo $this->Form->input('slug',['disabled' => true]);
                        echo $this->Form->textarea('description');
                    ?>
                    <label>
                        <?php echo $this->Form->checkbox('public') . ' ' . __('Public'); ?>
                    </label>
                </fieldset>
                <div class="right">
                    <?php echo $this->Form->button(__('Save'), ['class' => 'success']) ?>
                    <?php echo $this->Html->link(__('Cancel'), ['action' => 'index'],['class' => 'button']) ?>
                </div>
                <div class="clearfix"></div>
            <?php echo $this->Form->end() ?>
        </div>
    </div>
</div>
