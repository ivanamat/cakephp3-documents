<h3>Documents</h3>
<div class="panel info">
    <div class="panel-body">
        <h3><?php echo __('There are no documents in the category {0}', $category->name); ?></h3>
        <p><?php echo $this->Html->link(__('Create a new document now'),['controller' => false,'action' => 'add',$category->id]); ?>.</p>
    </div>
</div>