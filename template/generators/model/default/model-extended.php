<?php
/**
 * This is the template for generating the model class of a specified table.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name => label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name => relation declaration)
 */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use \<?= $generator->ns ?>\base\<?= $className ?> as Base<?= $className ?>;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "<?= $tableName ?>".
 * Modified by Defri Indra M
 */
class <?= $className ?> extends Base<?= $className . "\n" ?>
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    /**
     * @inheiritance
     */
    public function fields()
    {
        $parent = parent::fields();

<?php foreach ($labels as $name => $label): ?>
        if(isset($parent['<?=$name?>'])) :
            unset($parent['<?=$name?>']);
            $parent['<?=$name?>'] = function($model) {
                return $model-><?=$name?>;
            };
        endif;
<?php endforeach; ?>
        return $parent;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
    
    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name',\n" ?>
<?php endforeach; ?>
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        return $parent;
    }

    /**
     * Simplify return data xD
     */
    public function render() {
        return [
            "model" => $this
        ];
    }

    /**
     * override validate
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors);
    }

    /**
     * override load
     */
    public function load($data, $formName = null, $service = "web")
    {
        return parent::load($data, $formName);
    }
}
