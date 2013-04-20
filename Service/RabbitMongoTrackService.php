<?php

namespace Kpacha\SOMRabbitMQBundle\Service;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Kpacha\SOMBundle\Document\RawTrack;
use Kpacha\SOMBundle\Service\MongoTrackService;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class RabbitMongoTrackService extends MongoTrackService
{

    /**
     * @var Producer
     */
    protected $producer;

    public function __construct(ManagerRegistry $doctrineMongoManager, LoggerInterface $logger, Producer $producer)
    {
        parent::__construct($doctrineMongoManager, $logger);
        $this->producer = $producer;
    }

    protected function _storeRawTrack(RawTrack $rawTrack)
    {
        self::$_logger->debug("Publishing to the raw track queue " . print_r($rawTrack, 1));
        $this->producer->publish(serialize($rawTrack));
    }

    public function track($clientIp, $queryString, $referer, $cookie)
    {
        return $this->_track($clientIp, $queryString, $referer, $cookie);
    }

}
