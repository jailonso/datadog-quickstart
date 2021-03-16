<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'Predis/Autoloader.php';
require '/var/www/html/vendor/autoload.php';
Predis\Autoloader::register();
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use DataDog\DogStatsd;
use Monolog\Formatter\JsonFormatter;
$logger = new Logger('guestbook');
$formatter = new JsonFormatter();
$stream = new StreamHandler("php://stdout", Logger::DEBUG);
$stream->setFormatter($formatter);
$logger->pushHandler($stream);
$logger->pushProcessor(function ($record) {
      $span = \DDTrace\GlobalTracer::get()->getActiveSpan();
      if (null === $span) {
          return $record;
      }
      $record['datadog'] = array(
         'dd.trace_id' => $span->getTraceId(),
         'dd.span_id' => $span->getSpanId(),
  );
            return $record;
});

$statsd = new DogStatsd(
            array('host' => getenv('DD_AGENT_HOST'),
                  'port' => 8125,
          )
   );
if (isset($_GET['cmd']) === true) {
  $host = 'redis-master';
  if (getenv('GET_HOSTS_FROM') == 'env') {
    $host = getenv('REDIS_MASTER_SERVICE_HOST');
  }
  header('Content-Type: application/json');

  if ($_GET['cmd'] == 'set' && $_GET['value'] == ',long') {
      $client = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => $host,
        'port'   => 6379,

      $logger->warning('Received message with this length', array('length' => '-20'));
      $statsd->gauge('guestbook_revenue',-20, array['source' => 'php-service']);
  } elseif ($_GET['cmd'] == 'set' && $_GET['value'] == ',virus') {
      $client = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => $host,
        'port'   => 6379,
      ]);

      $client->set($_GET['key'], $_GET['value']);
      print('{"message": "Updated"}');

      $logger->warning('Received message with this length', array('length' => '-20'));
      $statsd->gauge('guestbook_revenue',-20, array['source' => 'php-service']);
  } elseif ($_GET['cmd'] == 'set') {
    $client = new Predis\Client([
      'scheme' => 'tcp',
      'host'   => $host,
      'port'   => 6379,
    ]);

    $client->set($_GET['key'], $_GET['value']);
    print('{"message": "Updated"}');
    $string = $_GET['value'];
    $logger->Info('Received message with this lenght', array['length'=> strlen($string)]);
    $statsd->gauge('guestbook_revenue',strlen($string), array['service'=>'php-service']);
  } else {
    $host = 'redis-slave';
    if (getenv('GET_HOSTS_FROM') == 'env') {
      $host = getenv('REDIS_SLAVE_SERVICE_HOST');
    }
    $client = new Predis\Client([
      'scheme' => 'tcp',
      'host'   => $host,
      'port'   => 6379,
    ]);

    $value = $client->get($_GET['key']);
    print('{"data": "' . $value . '"}');
    }
} else {
  phpinfo();
}
 ?>
