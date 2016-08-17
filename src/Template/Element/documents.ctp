<h3><?php echo (isset($title)) ? $title : __('Documents') ; ?></h3>
<ul class="side-nav">
    <?php foreach ($documents as $document): ?>
        <?php if(isset($related) && $related == true): ?>
            <li>
                <?php
                $category = $this->Documents->getCategory($document->category_id);
                echo $this->Html->link($document->title . ' ('.$category->name.')', DS . $this->request->params['controller'] . DS . $document->slug); 
                ?>
            </li>
        <?php else: ?>
            <li><?php echo $this->Html->link($document->title, DS . $this->request->params['controller'] . DS . $document->slug); ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>