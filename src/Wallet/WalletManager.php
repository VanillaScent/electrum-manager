<?php

namespace ElectrumManager\Wallet;

use ElectrumManager\RPC\RPCClient;

class WalletManager {

    public function __construct(RPCClient $client)
    {
        $this->client = $client;
    }
    
    public function createRequest(string $amount, string $memo){
        $res = $this->client->execute("addrequest", [
            "amount" => $amount, "memo" => $memo,
            "force" => "true", "expiration" => "1800"]);

        //failed to call BTC addrequest
        if(!$res){
            return false;
        }

        return $res;
    }

    public function getRequest(string $addr){
        $res = $this->client->execute("getrequest", [
            "key" => $addr]
        );
        $this->logger->debug("RPC getRequest:", ["res" => $res]);
        return $res;
    }

    public function checkBalance(string $addr){
        $res = $this->client->execute('getaddressbalance', ["address" => $addr]);
        $this->logger->debug("RPC getbalance:", $res, $addr);
        return $res;
    }

    public function getWalletBalance(){
                $res = $this->client->execute("getbalance", array());

                $this->logger->debug('RPC > wallet balance: ', $res['confirmed']);
        return $res;

        }

}