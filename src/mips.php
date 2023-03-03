<?php

namespace nadimattari\mips;

use Exception;

/**
 * MIPS e-commerce integration
 */
class mips
{
    private const URL_load_payment_zone = 'https://api.mips.mu/api/load_payment_zone';
    private const URL_decrypt_imn_data  = 'https://api.mips.mu/api/decrypt_imn_data';

    private array  $payload    = [];
    private string $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Safari/537.36';

    private string $id_merchant     = '';
    private string $id_entity       = '';
    private string $operator        = '';
    private string $operator_pass   = '';
    private string $salt            = '';
    private string $cipher_key      = '';
    private string $basic_auth_user = '';
    private string $basic_auth_pass = '';

    public function __construct(array $params = [])
    {
        !empty($params['id_merchant'])     && $this->setIdMerchant($params['id_merchant']);
        !empty($params['id_entity'])       && $this->setIdEntity($params['id_entity']);
        !empty($params['operator'])        && $this->setOperator($params['operator']);
        !empty($params['operator_pass'])   && $this->setOperatorPass($params['operator_pass']);
        !empty($params['salt'])            && $this->setSalt($params['salt']);
        !empty($params['cipher_key'])      && $this->setCipherKey($params['cipher_key']);
        !empty($params['basic_auth_user']) && $this->setBasicAuthUser($params['basic_auth_user']);
        !empty($params['basic_auth_pass']) && $this->setBasicAuthPass($params['basic_auth_pass']);
    }

    /**
     * @param string $url
     * @param array $payload
     * @return array
     */
    private function curl_mips(string $url, array $payload): array
    {
        if (empty($url)) {
            return [];
        }

        $payload['authentify']   = [
            'id_merchant'       => $this->getIdMerchant(),
            'id_entity'         => $this->getIdEntity(),
            'id_operator'       => $this->getOperator(),
            'operator_password' => $this->getOperatorPass(),
        ];

        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL 			=> $url,
                CURLOPT_USERAGENT 		=> $this->getUserAgent(),
                CURLOPT_RETURNTRANSFER 	=> 1,
                CURLOPT_FOLLOWLOCATION 	=> false,
                CURLOPT_FORBID_REUSE 	=> true,
                CURLOPT_FRESH_CONNECT 	=> true,
                CURLOPT_VERBOSE         => 1,
                CURLOPT_SSL_VERIFYPEER 	=> true,
                CURLOPT_POST 			=> true,
                CURLOPT_POSTFIELDS 		=> json_encode($payload, JSON_THROW_ON_ERROR),
                CURLOPT_HTTPHEADER 		=> [
                    'Authorization: Basic ' . base64_encode( $this->getBasicAuthUser() . ':' . $this->getBasicAuthPass()),
                    'Cache-Control: no-cache'
                ]
            ]);

            $resp = curl_exec($curl);
            return json_decode(
                $resp,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @return array
     * @see https://docs.mips.mu/docs/merchant-api/a4cae076c9b7e-load-payment-zone
     */
    final public function loadPaymentZone(): array
    {
        $payload = $this->getPayload();
        $payload['request_mode'] = 'simple';
        $payload['touchpoint']   = 'web';

        try {
            return $this->curl_mips(
                self::URL_load_payment_zone,
                $payload
            );
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @return array
     * @see https://docs.mips.mu/docs/merchant-api/59cb694472f24-decrypt-imn-callback-data
     */
    final public function decryptImnData()
    {
        try {
            return $this->curl_mips(
                self::URL_decrypt_imn_data,
                $this->getPayload()
            );
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @return array
     */
    final public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     * @return mips
     */
    final public function setPayload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return string
     */
    final public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * @param string $user_agent
     * @return mips
     */
    final public function setUserAgent(string $user_agent): self
    {
        $this->user_agent = $user_agent;
        return $this;
    }

    /**
     * @return string
     */
    final public function getIdMerchant(): string
    {
        return $this->id_merchant;
    }

    /**
     * @param string $id_merchant
     * @return mips
     */
    final public function setIdMerchant(string $id_merchant): self
    {
        $this->id_merchant = $id_merchant;
        return $this;
    }

    /**
     * @return string
     */
    final public function getIdEntity(): string
    {
        return $this->id_entity;
    }

    /**
     * @param string $id_entity
     * @return mips
     */
    final public function setIdEntity(string $id_entity): self
    {
        $this->id_entity = $id_entity;
        return $this;
    }

    /**
     * @return string
     */
    final public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return mips
     */
    final public function setOperator(string $operator): self
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return string
     */
    final public function getOperatorPass(): string
    {
        return $this->operator_pass;
    }

    /**
     * @param string $operator_pass
     * @return mips
     */
    final public function setOperatorPass(string $operator_pass): self
    {
        $this->operator_pass = $operator_pass;
        return $this;
    }

    /**
     * @return string
     */
    final public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     * @return mips
     */
    final public function setSalt(string $salt): self
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @return string
     */
    final public function getCipherKey(): string
    {
        return $this->cipher_key;
    }

    /**
     * @param string $cipher_key
     * @return mips
     */
    final public function setCipherKey(string $cipher_key): self
    {
        $this->cipher_key = $cipher_key;
        return $this;
    }

    /**
     * @return string
     */
    final public function getBasicAuthUser(): string
    {
        return $this->basic_auth_user;
    }

    /**
     * @param string $basic_auth_user
     * @return mips
     */
    final public function setBasicAuthUser(string $basic_auth_user): self
    {
        $this->basic_auth_user = $basic_auth_user;
        return $this;
    }

    /**
     * @return string
     */
    final public function getBasicAuthPass(): string
    {
        return $this->basic_auth_pass;
    }

    /**
     * @param string $basic_auth_pass
     * @return mips
     */
    final public function setBasicAuthPass(string $basic_auth_pass): self
    {
        $this->basic_auth_pass = $basic_auth_pass;
        return $this;
    }
}
