<h3><?php echo __('Documents'); ?></h3>
<div class="panel info">
    <div class="panel-body">
        <p><?php echo __('There are no documents in the category {0}', $category->name); ?></p>
        <p><?php echo $this->Html->link(__('Create document'),['controller' => false,'action' => 'add',$category->id]); ?></p>
    </div>
</div>