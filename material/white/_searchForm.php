<?php
/***
 * @var $object \app\models\Object;
 * @var $model \app\modules\data\models\ImportModel
 */

use app\models\Object;
use \app\modules\shop\models\Product;
?>
<div class="form-group row">
    <div class="col-md-12">
        <fieldset>
            <legend><?= Yii::t('app', 'Filter') ?></legend>
            <?php
            $product = Yii::$container->get(Product::class);
            if ($object->id == Object::getForClass(get_class($product))->id) {
                echo \app\backend\widgets\filterForm\filterFormCategory::widget();
            }
            ?>
            <?= \app\backend\widgets\filterForm\filterFormProperty::widget(['objectId' => $object->id]) ?>
            <?= \app\backend\widgets\filterForm\filterFormFields::widget(['objectId' => $object->id]) ?>
        </fieldset>
    </div>
</div>






