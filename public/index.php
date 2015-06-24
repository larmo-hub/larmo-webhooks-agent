<?php

require_once('../vendor/autoload.php');

use FP\Larmo\Agents\WebHookAgent\Packet;
use FP\Larmo\Agents\WebHookAgent\Request;
use FP\Larmo\Agents\WebHookAgent\Routing;
use FP\Larmo\Agents\WebHookAgent\Metadata;
use FP\Larmo\Agents\WebHookAgent\Services;
use FP\Larmo\Agents\WebHookAgent\Services\ServiceFactory;
use FP\Larmo\Agents\WebHookAgent\Exceptions\MethodNotAllowedHttpException;
use FP\Larmo\Agents\WebHookAgent\Exceptions\ServiceNotFoundException;
use FP\Larmo\Agents\WebHookAgent\Exceptions\EventTypeNotFoundException;

require_once('/../config/config.php');

header('Content-type: application/json; charset=utf-8');

try {
    $input = file_get_contents('php://input');
    $request = new Request($_SERVER, $input);

    if (!$request->isPostMethod()) {
        throw new MethodNotAllowedHttpException;
    }

    /* Retrieve data from HTTP request */
    $uri = $request->getUri();

    /* Create appropriate service */
    $routing = new Routing($uri);
    $service = ServiceFactory::create($routing->getSourceIdentifier(), $request);

    /* Create metadata (header for packet) */
    $metadata = new Metadata($service->getServiceName(), $config['authentication']);

    /* Create and send packet */
    $packet = new Packet($metadata, $service);
    $packet->send();
} catch (MethodNotAllowedHttpException $e) {
    http_response_code(405); // POST only allowed
} catch (InvalidIncomingDataException $e) {
    http_response_code(400); // Data are incorrect
} catch (EventTypeNotFoundException $e) {
    http_response_code(400); // We got an event type we are not prepared to handle
} catch (ServiceNotFoundException $e) {
    http_response_code(404); // We do not have the ability to handle this service
} catch (InvalidArgumentException $e) {
    http_response_code(404);
} catch (Exception $e) {
    /* Unpredicted error */
    file_put_contents('php://stderr', $e->getMessage());
}
