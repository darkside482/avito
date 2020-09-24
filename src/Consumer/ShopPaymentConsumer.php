<?php

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ShopPaymentConsumer implements ConsumerInterface
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * ShopPaymentConsumer constructor.
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param AMQPMessage $msg
     * @return mixed|void
     * @throws TransportExceptionInterface
     */
    public function execute(AMQPMessage $msg)
    {
        $data = json_decode($msg->getBody(), true);

        $this->client->request('POST', $data['shopUrl'].'?sessionId='.$data['sessionId']);
    }
}
