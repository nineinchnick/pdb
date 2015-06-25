<?php
/**
 * Created by PhpStorm.
 * User: jwas
 * Date: 23.06.15
 * Time: 23:23
 */

namespace app\models;

use yii\base\Exception;

class ProductAttributeParser extends \nineinchnick\sync\models\Parser
{
    /**
     * Either uploads a prepared file or downloads next file from a remote service.
     * Returns bool false if no files are available.
     * @param File $file
     * @return File|bool bool true when uploading, bool false when downloading and no files are available
     */
    public function transfer($file = null)
    {
        $file->sent_on = date('Y-m-d H:i:s');
        $file->save(false);
        return true;
    }

    /**
     * Parses the files contents.
     * @param $file
     * @return bool
     */
    public function process($file)
    {
        set_time_limit(300);
        $client = new ShoperClient((array)json_decode($this->parser_options));
        $attributes = [];
        foreach ($client->getAttributesList() as $attribute) {
            $attribute = (array)$attribute;
            $attributes[$attribute['name']] = $attribute['attribute_id'];
        }
        $products = [];
        foreach (array_chunk($client->getProductsList(false), 1000) as $chunk) {
            foreach ($client->getProductsList(true, $chunk) as $product) {
                $product = (array)$product;
                $products[$product['code']] = $product;
            }
        }
        $content = base64_decode($file->content);
        $counter = 0;
        foreach (explode("\r\n", $content) as $line) {
            if ($counter++ === 0) {
                continue;
            }
            $fields = str_getcsv($line, "\t");
            if (count($fields) !== 6) {
                throw new Exception('Invalid number of fields in line '.$counter.': '.$line);
            }
            list($code, $isPromo, $price, $sex, $size, $color) = $fields;
            if (!isset($products[$code]['product_id'])) {
                continue;
            }
            if ($isPromo === 'Y') {
                //$client->setPrice($products[$code]['product_id'], $price, $products[$code]['specialOffer']);
            } else {
                //$client->delPrice($products[$code]['product_id']);
            }
            $setAttributes = [];
            if (!empty($sex)) {
                $setAttributes[$sex] = $sex;
            }
            if (!empty($size)) {
                $setAttributes[$size]  = $size;
            }
            if (!empty($color)) {
                $setAttributes[$color] = $color;
            }
            if (!empty($setAttributes)) {
                //$client->setAttributes($products[$code]['product_id'], $setAttributes);
            }
        }
        exit(0);

        $file->processed_on = date('Y-m-d H:i:s');
        $file->save(false);
        return true;
    }

    /**
     * Acknowledges the processing of a downloaded file in a remote service.
     * @param $file
     * @return bool
     */
    public function acknowledge($file)
    {
        $file->acknowledged_on = date('Y-m-d H:i:s');
        $file->save(false);
        return true;
    }
}
