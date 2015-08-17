<?php

namespace FP\Larmo\Agents\WebHookAgent\Services;

use FP\Larmo\Agents\WebHookAgent\Request;
use FP\Larmo\Agents\WebHookAgent\Exceptions\EventTypeNotFoundException;
use FP\Larmo\Agents\WebHookAgent\Exceptions\InvalidSecretSignatureException;
use FP\Larmo\Agents\WebHookAgent\Exceptions\ServiceNotFoundException;

class ServiceFactory
{
    public static function create($serviceName, Request $request, array $secrets = [])
    {
        try {
            /* Check that security signature is set and is correct */
            $securityClass = '\\FP\\Larmo\\Agents\\WebHookAgent\\Services\\' . ucfirst($serviceName) . '\\SecuritySignature';

            if (!empty($secrets) && class_exists($securityClass)) {
                $securitySignatureTest = new $securityClass($request, $secrets);

                if (!$securitySignatureTest->isSecuritySignatureCorrect()) {
                    throw new InvalidSecretSignatureException;
                }
            }

            /* Caution: full namespace path is necessary for class_exists() to work correctly */
            $serviceClass = '\\FP\\Larmo\\Agents\\WebHookAgent\\Services\\' . ucfirst($serviceName) . '\\' . ucfirst($serviceName) . 'Data';

            if (!class_exists($serviceClass)) {
                throw new ServiceNotFoundException;
            }

            return new $serviceClass($request->getDecodedPayload(), $request->getHeaders());
        } catch (EventTypeNotFoundException $e) {
            throw $e;
        } catch (InvalidSecretSignatureException $e) {
            throw $e;
        } catch (ServiceNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            file_put_contents('php://stderr', $e->getMessage());
            throw new \Exception('Service could not be created for unknown reason', $e->getCode(), $e);
        }
    }
}