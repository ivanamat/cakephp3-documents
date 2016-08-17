<h1 class="left"><?php echo $document->title; ?></h1>
<div class="right">
    <?php
    if ($this->request->session()->check('Auth.User.id') &&
            $this->AclManager->check(['Users' => ['id' => $this->request->session()->read('Auth.User.id')]], 'controllers/Documents/Documents/edit')) {
        echo $this->Html->link(__('Edit'), ['controller' => false, 'action' => 'edit', $document->id], ['class' => 'button']);
    }
    ?> 
    <?php
    if ($this->request->session()->check('Auth.User.id') &&
            $this->AclManager->check(['Users' => ['id' => $this->request->session()->read('Auth.User.id')]], 'controllers/Documents/Documents/delete')) {
        echo $this->Form->postLink(__('Delete'), ['plugin' => 'Documents', 'controller' => false, 'action' => 'delete', $document->id], ['class' => 'button alert', 'confirm' => __('Are you sure you want to delete {0}?', $document->title)]);
    }
    ?>
</div>
<div class="clearfix"></div>
<hr />
<?php echo $this->Markdown->parse($document->body); ?>