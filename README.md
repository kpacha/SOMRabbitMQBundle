SOMRabbitMQBundle
=========

Success-O-Meter Bundle with RabbitMQ for decoupling the pixel generation from the
analytics collection.

Simple tracking bundle for easy and scalable analytics collection and management.

##Requirements

- PHP 5.4
- RabbitMQ (http://www.rabbitmq.com/)

##Third-Party components

- oldsound/rabbitmq-bundle (https://github.com/videlalvaro/rabbitmqbundle)
- kpacha/som-bundle (https://github.com/kpacha/SOMBundle)

##Installation

- Add composer dependency

        "require": {
            "kpacha/som-rabbit-bundle": "dev-master"
        },
        "repositories": [
            {"type": "vcs", "url": "https://github.com/kpacha/SOMRabbitMQBundle"}
         ],

- Update dependencies

        composer update

- Register the bundles into app/AppKernel.php

        public function registerBundles()
        {
            $bundles = array(
                ...
                // SOM RabbitMQ
                new Kpacha\SOMRabbitMQBundle\KpachaSOMRabbitMQBundle(),
                new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
                ...

- Add RabbitMQ configuration into app/config/config.yml

        # OldSound bundle configuration required for the SOMRabbitMQBundle
        old_sound_rabbit_mq:
            connections:
                default:
                    host:      %rabbit_mq_host%
                    port:      %rabbit_mq_port%
                    user:      %rabbit_mq_user%
                    password:  %rabbit_mq_password%
                    vhost:     %rabbit_mq_vhost%
            producers:
                track:
                    connection: default
                    exchange_options: {name: 'track', type: direct}
            consumers:
                track:
                    connection: default
                    exchange_options: {name: 'track-consumer', type: direct}
                    queue_options:    {name: 'track-queue'}
                    callback:         kpacha_som.track_consumer

- Add RabbitMQ parameters into app/config/parameters.yml

        rabbit_mq_host:     'localhost'
        rabbit_mq_port:     5672
        rabbit_mq_user:     'USER_NAME'
        rabbit_mq_password: 'PASSWORD'
        rabbit_mq_vhost:    '/'

- Check the queue binding! Sometimes, it declares the consumer as producer

##Set up the consumer

Consume 100 tracks:

    php app/console rabbitmq:consumer -m 100 track

From the command line help:

    rabbitmq:consumer [-m|--messages[="..."]] [-r|--route[="..."]] [-l|--memory-limit[="..."]] [-d|--debug] [-w|--without-signals] name

##TO-DO

- Improve documentation
- Full code coverage
