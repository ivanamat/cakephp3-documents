<?php
/**
 * CakePHP 3.x - Markdown Documents
 * 
 * PHP version 5
 * 
 * File Element/subcategories.ctp
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

<h3><?php echo (isset($title)) ? $title : __('Subcategories') ; ?></h3>
<ul class="side-nav">
<?php
$childs =  $categories->find('children',['for' => $category->id]);
foreach ($childs as $id => $categoryItem):
    $slug = $this->Docs->slugCategory($id);
    if(isset($this->request->params['slug']) && $slug == $this->request->params['slug']) {
        echo '<li><a href="/' . $this->plugin . DS . $slug . '"><strong>'.$categoryItem.'</strong></a></li>';
    } else {
        echo '<li><a href="/' . $this->plugin . DS . $slug . '">'.$categoryItem.'</a></li>';
    }
endforeach;
?>
</ul>