<?php

namespace PivotalTracker\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;
use PivotalTracker\HttpClient\Message\ResponseMediator;
use PivotalTracker\Exception\ErrorException;
use PivotalTracker\Exception\RuntimeException;
use PivotalTracker\Exception\ValidationFailedException;
use Psr\Http\Message\ResponseInterface;

/**
 *
 */
class PivotalTrackerExceptionThrower implements Plugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        return $next($request)->then(function (ResponseInterface $response) use ($request) {
            if ($response->getStatusCode() < 400 || $response->getStatusCode() > 600) {
                return $response;
            }

            $content = ResponseMediator::getContent($response);
            if (is_array($content) && isset($content['error'])) {
                if (400 == $response->getStatusCode()) {
                    throw new ErrorException($content['error'], 400);
                } elseif (isset($content['validation_errors'])) {
                    $errors = array();
                    foreach ($content['validation_errors'] as $error) {
                        switch ($error['code']) {
                            case 'missing':
                                $errors[] = sprintf('The %s does not exist, for resource "%s"', $error['field'], $error['problem']);
                                break;

                            case 'missing_field':
                                $errors[] = sprintf('Field "%s" is missing, for resource "%s"', $error['field'], $error['problem']);
                                break;

                            case 'invalid_parameter':
                                $errors[] = sprintf('Field "%s" is invalid, for resource "%s"', $error['field'], $error['problem']);
                                break;

                            case 'already_exists':
                                $errors[] = sprintf('Field "%s" already exists, for resource "%s"', $error['field'], $error['problem']);
                                break;

                            default:
                                $errors[] = $error['general_problem'];
                                break;

                        }
                    }

                    throw new ValidationFailedException('Validation Failed: '.implode(', ', $errors), 422);
                }
            }

            throw new RuntimeException(isset($content['error']) ? $content['error'] : $content, $response->getStatusCode());
        });
    }
}
