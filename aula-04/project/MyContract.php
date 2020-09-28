<?php

use Web3\Contract;
use Web3\Web3;

class MyContract
{
    protected $base;
    protected $data;
    protected $byteCode;
    protected $response;

    public function init()
    {
        $this->byteCode = file_get_contents('.env');
        $this->base = new Web3('http://192.168.99.100:8545/');
        $this->data = file_get_contents('data.json');
        $this->response = '';
    }

    public function create()
    {
        $this->init();
        $contract = new Contract($this->base->provider, json_decode($this->data));

        $this->base->eth->accounts(function ($err, $accounts) use ($contract) {
            if ($err === null) {
                if (isset($accounts)) {
                    $accounts = $accounts;
                }

                $fromAccount = $accounts[0];
                $toAccount = $accounts[1];


                $contract->bytecode($this->byteCode)
                    ->new(1000000, 'Game Token', 1, 'GT', ['from' => $fromAccount, 'gas' => '0x200b20'],
                        function ($err, $result) use ($contract, $fromAccount, $toAccount) {
                            if ($err !== null) {
                                echo $err;
                            } else {
                                throw new RuntimeException('Certifique-se de ter acesso ao servidor.');
                            }

                            if (!!$result) {
                                $this->response = "\n A transação foi feita :) id: " . $result . "\n";
                            }

                            $transactionId = $result;

                            $contract->eth->getTransactionReceipt($transactionId,
                                function ($err, $transaction) use ($contract, $fromAccount, $toAccount) {
                                    if ($err !== null) {
                                        throw $err;
                                    }

                                    if ($transaction) {
                                        $contractAddress = $transaction->contractAddress;
                                        $this->response = "\n A transação está em análise :) número do bloco: "
                                            . $transaction->blockNumber
                                            . "\n";

                                        $contract->at($contractAddress)->send('transfer', $toAccount, 16, [
                                            'from' => $fromAccount,
                                            'gas' => '0x200b20'
                                        ], function ($err, $result) use ($contract, $fromAccount, $toAccount) {
                                            if ($err !== null) {
                                                throw $err;
                                            }
                                            if ($result) {
                                                $this->response = "\n A transação foi realizada :) id: " . $result . "\n";
                                            }
                                            $transactionId = $result;

                                            $contract->eth->getTransactionReceipt($transactionId,
                                                function ($err, $transaction) use ($fromAccount, $toAccount) {
                                                    if ($err !== null) {
                                                        throw $err;
                                                    }
                                                    if ($transaction) {
                                                        $this->response = "\n A transação está em mente :) número do bloco: "
                                                            . $transaction->blockNumber
                                                            . "\n Saída da transação: \n";
                                                        var_dump($transaction);
                                                    }
                                                });
                                        });
                                    }
                                });
                        });
            } else {
                $this->response = 'Por favor, isira um valor de bitcoin válido!';
            }
        });

        echo $this->response;
    }
}
