<?php

namespace App\Http;

use Psr\Http\Message\ResponseInterface;
use App\Http\Message;
use Psr\Http\Message\StreamInterface;

class Response extends Message implements ResponseInterface
{
    private int $statusCode;
    private string $reasonPhrase;


    public function __construct(int $status = 200, $headers = [], ?string $body = null, string $reasonPhrase = "")
    {
        parent::__construct();
        $this->statusCode = $status;
        $this->headers = $headers;
        $this->body = $body ?: $this->createBody();
        $this->reasonPhrase = $reasonPhrase ?: $this->getReasonPhraseByStatusCode($status);

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase ?: $this->getReasonPhraseByStatusCode($code);
        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function getReasonPhraseByStatusCode(int $code): string
    {

        return HttpStatus::from($code)->getReasonPhrase();
    }

    public function send()
    {
        // Send HTTP headers
        header(sprintf('HTTP/%s %d %s', $this->getProtocolVersion(), $this->statusCode, $this->reasonPhrase));
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        // Send HTTP body
        echo $this->body;
    }

    public static function fromHttpStatus(HttpStatus $status)
    {
        $data = [
            "code" => $status->value,
        ];

        if (!empty($status->getReasonPhrase())) {
            $data['reason'] = $status->getReasonPhrase();
        }

        ResponseFactory::createResponse(json_encode($data, JSON_PRETTY_PRINT), $status)->send();
        return;
    }
}
