<div class="panel info">
    <div class="panel-body">
        <h2><?php echo __('Create your index or home page for Documents'); ?></h2>
        <p>You can create a custom index or home page.</p>

        <h3>Loading Markdown file</h3>
        <p>You can create a markdown file type, and specify the file path in your bootstrap configuration file <code>src/config/bootstrap.php</code>.</p>
        <?php
        $code = '

```php
    # my_app/src/configure/bootstrap.php  
    ...  
    Plugin::load(\'Migrations\');  
    Plugin::load(\'Documents\', [\'bootstrap\' => false, \'routes\' => true]);  
    Configure::write(\'path/to/your/file\.md\');  
    ...  
```

';
        echo $this->Markdown->parse($code);
        ?>
        <h3>Creating a new document</h3>
        <p>Create a new document called Index and do not set the category. <?php echo $this->Html->link(__('Create now'),['controller' => false,'action' => 'add']); ?>.</p>
    </div>
</div>
