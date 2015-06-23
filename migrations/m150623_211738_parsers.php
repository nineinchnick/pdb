<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_211738_parsers extends Migration
{
    protected $schemaName = 'public';

    public function safeUp()
    {
        $this->insert('{{%sync.parsers}}', [
            'name' => 'shoper prices',
            'class' => 'netis\assortment\models\ProductPricing',
            'parser_class' => 'app\models\PriceParser',
            'parser_options' => null,
            'author_id' => 1,
            'editor_id' => 1,
        ]);
        $this->insert('{{%sync.parsers}}', [
            'name' => 'shoper attributes',
            'class' => 'netis\assortment\models\Product',
            'parser_class' => 'app\models\ProductAttributeParser',
            'parser_options' => null,
            'author_id' => 1,
            'editor_id' => 1,
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%sync.parsers}}', ['in', 'name', ['shoper prices', 'shoper attributes']]);
    }
}
