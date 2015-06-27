<?php

namespace app\models;
use yii\base\Exception;

/**
 * Created by PhpStorm.
 * User: jwas
 * Date: 24.06.15
 * Time: 22:08
 */
class ShoperClient extends \yii\base\Object
{
    /**
     * @var resource cURL handle
     */
    private $handle;
    /**
     * @var string
     */
    private $session;
    /**
     * @var string
     */
    public $url = 'http://shop.example.com/webapi/json';
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;


    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_URL, $this->url);
        curl_setopt($this->handle, CURLOPT_POST, true);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
        $this->session = $this->login($this->username, $this->password);
    }

    /**
     * Logowanie do API
     *
     * @param string $login    Login użytkownika
     * @param string $password Hasło użytkownika
     * @return string Indentyfikator sesji użytkownika
     */
    private function login($login, $password)
    {
        $params = [
            "method" => "login",
            "params" => [$login, $password]
        ];
        curl_setopt($this->handle, CURLOPT_POSTFIELDS, "json=" . json_encode($params));
        $result = (array)json_decode(curl_exec($this->handle));
        if (isset($result['error'])) {
            throw new Exception($result['error']);
        }

        return $result[0];
    }

    /**
     * Pobranie błędów
     *
     * @return array
     */
    private function getError()
    {
        $params = [
            "method" => "call",
            "params" => [$this->session, 'internals.validation.errors', null]
        ];
        curl_setopt($this->handle, CURLOPT_POSTFIELDS, "json=" . json_encode($params));
        $result = (array)json_decode(curl_exec($this->handle));

        return $result;
    }

    private function call($params)
    {
        $postParams = "json=" . json_encode($params);
        curl_setopt($this->handle, CURLOPT_POSTFIELDS, $postParams);

        $data = curl_exec($this->handle);
        $result = (array)json_decode($data);
        if (isset($result['error'])) {
            throw new \yii\base\Exception($result['error']);
        }

        return $result;
    }

	/**
	 * @param bool $extended czy zwrócić informacje o obiektach
	 * @param bool $translations czy zwrocić dodatkowo informacje o tłumaczeniach
	 * @param bool $options czy zwrócić dodatkowo informacje o wariantach
	 * @param bool $gfx czy zwrócić dodatkowo informacje o zdjęciach
	 * @param bool $attributes czy zwrócić dodatkowo informacje o atrybutach
	 * @param array $products tablica identyfikatorów obiektów do pobrania lub **null** w celu pobrania wszystkich dostępnych obiektów
	 * @return array
	 */
    public function getProductsList($extended = true, $translations = false, $options = false, $gfx = false, $attributes = false, $products = null)
    {
        $params = [
            "method" => "call",
            "params" => [
				$this->session,
				"product.list",
                [
                    $extended,
                    $translations,
                    $options,
                    $gfx,
                    $attributes,
                    $products,
                ],
            ],
        ];

        return $this->call($params);
    }

	/**
	 * @param bool $extended czy zwrócić tylko listę identyfikatorów (false), czy tablicę, której wartościami
	 *                       są tablice asocjacyjne informacji o żądanych obiektach (true)
	 * @param array $attributes tablica identyfikatorów obiektów do pobrania lub null w celu pobrania
	 *                          wszystkich dostępnych obiektów
	 * @return array
	 */
    public function getAttributesList($extended = true, $attributes = null)
    {
        $params = [
            "method" => "call",
            "params" => [
				$this->session, "attribute.list", [
					$extended,
					$attributes,
				],
            ],
        ];

        return $this->call($params);
    }

    public function setAttributes($product_id, $attributes, $force = true)
    {
        $params = [
            "method" => "call",
            "params" => [
                $this->session, "product.attributes.save", [
                    $product_id,
                    $attributes,
                    $force,
                ],
            ],
        ];

		$result = $this->call($params);
		if ($result[0] === -1) {
			throw new Exception('While setting product '.$product_id.' attributes to '.var_export($attributes, true)
				. ' the following errors occured: '.implode('',$this->getError()));
		}
		return $result;
    }

	public function getPrice($product_id)
	{
		$params = [
			"method" => "call",
			"params" => [
				$this->session, "product.promo.info", [$product_id],
			],
		];

		try {
			$promo = $this->call($params);
		} catch (\yii\base\Exception $e) {
			$promo = null;
		}
		return $promo;
	}

    public function setPrice($product_id, $price, $promo = null)
    {
        if ($promo === null) {
			$promo = $this->getPrice($product_id);
        }

        $params = [
            "method" => "call",
            "params" => [
				$this->session,
				"product.promo.create",
			   	[
                    $product_id,
					[
						'datefrom' => is_array($promo) ? $promo['date_from'] : date('Y-m-d'),
						'dateto' => is_array($promo) ? $promo['date_to'] : '2038-01-01',
						'promoprice' => $price,
					],
                ],
            ],
        ];

        return $this->call($params);
    }

    public function delPrice($product_id)
    {
        $params = [
            "method" => "call",
            "params" => [
				$this->session,
				"product.promo.delete",
                [
                    $product_id,
                ],
            ],
        ];

        return $this->call($params);
    }
}
