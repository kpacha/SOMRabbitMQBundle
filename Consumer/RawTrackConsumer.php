<?php

namespace Kpacha\SOMRabbitMQBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Kpacha\SOMBundle\Service\AbstractTrackService;
use Kpacha\SOMBundle\Document\RawTrack;

class RawTrackConsumer implements ConsumerInterface
{

    /**
     * @var RabbitMongoTrackService
     */
    protected $trackService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(AbstractTrackService $trackService, LoggerInterface $logger)
    {
        $this->trackService = $trackService;
        $this->logger = $logger;
    }

    public function execute(AMQPMessage $msg)
    {
        $rawTrack = unserialize($msg->body);
        $this->logger->debug("New message received! " . print_r($rawTrack, true));

        // check the content type and the response from the service
        return ($rawTrack instanceof RawTrack) && $this->trackService->process($rawTrack);
    }

}
