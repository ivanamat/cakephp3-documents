<?php

/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * File Documents/add.ctp
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

if (Configure::read('Documents.home')) {
    $home = Configure::read('Documents.home');
} else {
    $home = ['plugin' => false, 'controller' => 'Pages', 'action' => 'display', 'home'];
}

$params = $this->request->params;

$title = null;
if (isset($category) && $category != null) {
    $title = $category->name;
}
if (isset($document) && $document != null) {
    $title = $document->title;
}
if ($title != null) {
    $this->assign('title', $title);
}
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li><?php echo $this->Html->link(__('Home'), $home); ?></li>
        <li>
            <?php
            if ($params['controller'] == 'Documents'):
                echo '<b>' . $this->Html->link(__('Index'), ['plugin' => 'Documents', 'controller' => 'Documents', 'action' => 'index']) . '</b>';
            else:
                echo $this->Html->link(__('Index'), ['plugin' => 'Documents', 'controller' => 'Documents', 'action' => 'index']);
            endif;
            ?>
        </li>
        <li>
            <?php
            if ($params['controller'] == 'Categories' && $params['action'] == 'index'):
                echo '<b>' . $this->Html->link(__('Document categories'), ['plugin' => 'Documents', 'controller' => 'Categories', 'action' => 'index']) . '</b>';
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
            if (isset($params['slug']) && $slug == $this->Docs->getParentSlug($params['slug'])) {
                echo '<li><a href="/' . $this->plugin . DS . $slug . '"><strong>' . $categoryItem . '</strong></a></li>';
            } else {
                echo '<li><a href="/' . $this->plugin . DS . $slug . '">' . $categoryItem . '</a></li>';
            }
        endforeach;
        ?>
    </ul>
</nav>

<div class="large-9 medium-8 columns content">
    <?= $this->Form->create($document) ?>
    <fieldset>
        <legend><?= __('Add Document') ?></legend>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                echo $this->Form->input('title');
//                echo $this->Form->input('slug');
                echo $this->Form->input('category_id', ['options' => $categories, 'empty' => true, 'required' => false, 'escape' => false]);
                echo $this->Form->input('user_id', ['options' => $users]); # Todo: This field must be hidden o set in controller
                echo $this->Form->input('file',['type' => 'file','accept' => '.txt,.md','class' => 'hide','label' => false, 'onchange' => 'readSingleFile(this);']);
                echo $this->Form->button(__('Import file'),['id' => 'importBtn', 'class' => 'button']);
                echo $this->Form->input('body');
                ?>
            </div>
        </div>
    </fieldset>
    <div class="right">
        <?php echo $this->Form->button(__('Save'), ['class' => 'success']) ?>
        <?php echo $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'button']) ?>
    </div>
    <div class="clearfix"></div>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('Documents.init.js',['inline' => false]); ?>
<script type="text/javascript">
function displayContents(contents) {
    var element = document.getElementById('body');
    element.value = contents;
    element.style.height = element.scrollHeight+'px';
}

document.getElementById('body').style.overflow = 'hidden';
document.getElementById('body').style.height = document.getElementById('body').scrollHeight+'px';
document.getElementById('body').addEventListener("keydown", autoSize, false);
document.getElementById('body').addEventListener("keydown", listenTab, false);
document.getElementById('importBtn').addEventListener("click", function(e) {
    e.preventDefault();
    document.getElementById('file').click();
}, false);
</script>
