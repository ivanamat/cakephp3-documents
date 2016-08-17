<h3><?php echo (isset($title)) ? $title : __('Subcategories') ; ?></h3>
<ul class="side-nav">
<?php
$childs =  $categories->find('children',['for' => $category->id]);
foreach ($childs as $id => $categoryItem):
    $slug = $this->Documents->slugCategory($id);
    if(isset($this->request->params['slug']) && $slug == $this->request->params['slug']) {
        echo '<li><a href="/' . $this->plugin . DS . $slug . '"><strong>'.$categoryItem.'</strong></a></li>';
    } else {
        echo '<li><a href="/' . $this->plugin . DS . $slug . '">'.$categoryItem.'</a></li>';
    }
endforeach;
?>
</ul>