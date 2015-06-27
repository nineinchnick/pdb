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
	const ATTR_TEXT = 0;
	const ATTR_BOOL = 1;
	const ATTR_LIST = 2;
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
        set_time_limit(0);
        $client = new ShoperClient((array)json_decode($this->parser_options));
        $attributes = [];
        foreach ($client->getAttributesList() as $attribute) {
            $attribute = (array)$attribute;
			if ((int)$attribute['type'] !== self::ATTR_LIST) {
				continue;
			}
			$attributes[$attribute['name']] = [];
			foreach ($attribute['options'] as $option) {
				$option = (array)$option;
				// prepend o to properly handle numeric strings as associative keys
				$attributes[trim($attribute['name'])]['o'.trim($option['value'])] = $attribute['attribute_id'];
			}
        }
        $products = [];
        foreach (array_chunk($client->getProductsList(false), 1000) as $chunk) {
            foreach ($client->getProductsList(true, false, false, false, true, $chunk) as $product) {
                $product = (array)$product;
                $products[$product['code']] = $product;
            }
        }
        $content = base64_decode($file->content);
        $counter = 0;
		$attributeNames = [];
        foreach (explode("\r\n", $content) as $line) {
            if ($counter++ === 0) {
				$headers = str_getcsv($line, "\t");
				if (count($headers) !== 6) {
					throw new Exception('Invalid number of fields in line '.$counter.': '.$line);
				}
				$code = array_shift($headers);
				$isPromo = array_shift($headers);
				$price = array_shift($headers);
				$attributeNames = array_map('trim', $headers);
                continue;
            }
            if (empty($line)) {
                continue;
            }
            $fields = str_getcsv($line, "\t");
            if (count($fields) !== 6) {
                throw new Exception('Invalid number of fields in line '.$counter.': '.$line);
            }
			$code = array_shift($fields);
			$isPromo = array_shift($fields);
			$price = array_shift($fields);
			$fields = array_map('trim', $fields);

			// process prices
            if (!isset($products[$code]['product_id'])) {
                continue;
            }
            if ($isPromo === 'Y') {
                $client->setPrice($products[$code]['product_id'], $price, isset($products[$code]['specialOffer']) ? $products[$code]['specialOffer'] : false);
            } elseif (isset($products[$code]['specialOffer'])) {
				$client->delPrice($products[$code]['product_id']);
            }

			// process attributes
            $setAttributes = [];
			foreach ($fields as $key => $field) {
				$attributeName = $attributeNames[$key];
				// prepend o to properly handle numeric strings as associative keys
				if (!empty($field) && isset($attributes[$attributeName], $attributes[$attributeName]['o'.$field])) {
					$setAttributes[$attributes[$attributeName]['o'.$field]] = $field;
				}
			}
            if (!empty($setAttributes)) {
                $result = $client->setAttributes($products[$code]['product_id'], $setAttributes);
				var_dump($result, $setAttributes);
				exit(0);
            }
        }

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
