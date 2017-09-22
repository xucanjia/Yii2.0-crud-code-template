<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
// use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="wrapper wrapper-content">
                    <div class="ibox-content">
    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Create ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'hover'=>true,
        'floatHeader'=>true,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            // ['class' => 'yii\grid\SerialColumn'],
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>
            [
              'class' => 'yii\grid\ActionColumn',
              'template'=>'{view} {update} {delete}',
              'header' => '操作',
              'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a("查看", $url, [
                                'title' => '详情',
                                // btn-update 目标class
                                'class' => 'btn btn-info btn-xs',
                        ]);
                    },

                    'update' => function ($url, $model, $key) {
                        return Html::a("修改", $url, [
                                'title' => '修改',
                                // btn-update 目标class
                                'class' => 'btn btn-danger btn-xs',
                        ]);
                    },

                    'delete' => function ($url, $model, $key) {
                        return Html::a('删除', $url, [
                            'title' => '删除',
                            'class' => 'btn btn-default btn-xs',
                            'data' => [
                                'confirm' => '确定要删除么?',
                                'method' => 'post',
                            ],
                        ]);
                    },

              ],
            ],
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
</div>
