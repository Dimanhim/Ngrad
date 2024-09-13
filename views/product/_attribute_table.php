<?php if($model->_categories) : ?>
    <table class="table table-sm table-attributes-list">
        <tr>
            <th>Категория</th>
            <th>Расходник</th>
            <th>Кол-во, шт.(м.п.)</th>
        </tr>
        <?php foreach($model->_categories as $categoryValues) : ?>
            <?php if(isset($categoryValues['product_attributes']) and $categoryValues['product_attributes']) : ?>
                <?php foreach($categoryValues['product_attributes'] as $attributeValues) : ?>
                    <?php
                        $category = $categoryValues['product_attribute_category'];
                        $attribute = $attributeValues['product_attribute'];
                        $relation = $attributeValues['product_relation'];
                    ?>
                    <tr>
                        <td><?= $category->name ?></td>
                        <td><?= $attribute->name ?></td>
                        <td><?= $relation->qty ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
