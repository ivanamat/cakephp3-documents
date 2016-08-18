<?php
/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * File Element/documents.ctp
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
?>

<h3><?php echo (isset($title)) ? $title : __('Documents') ; ?></h3>
<ul class="side-nav">
    <?php foreach ($documents as $document): ?>
        <?php if(isset($related) && $related == true): ?>
            <li>
                <?php
                $category = $this->Docs->getCategory($document->category_id);
                echo $this->Html->link($document->title . ' ('.$category->name.')', DS . $this->request->params['controller'] . DS . $document->slug); 
                ?>
            </li>
        <?php else: ?>
            <li><?php echo $this->Html->link($document->title, DS . $this->request->params['controller'] . DS . $document->slug); ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>