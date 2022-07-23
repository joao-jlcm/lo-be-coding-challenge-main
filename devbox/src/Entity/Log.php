<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRepository::class)]
#[ORM\Index(name: "service_name_idx", fields: ["service_name"])]
#[ORM\Index(name: "response_code_idx", fields: ["response_code"])]
#[ORM\Index(name: "timestamp_idx", fields: ["timestamp"])]
class Log
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $client_ip = null;

    #[ORM\Column(length: 255)]
    private ?string $http_path = null;

    #[ORM\Column(length: 255)]
    private ?string $http_verb = null;

    #[ORM\Column(length: 255)]
    private ?string $http_version = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $response_code = null;

    #[ORM\Column(length: 255)]
    private ?string $service_name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientIp(): ?string
    {
        return $this->client_ip;
    }

    public function setClientIp(?string $client_ip): self
    {
        $this->client_ip = $client_ip;

        return $this;
    }

    public function getHttpPath(): ?string
    {
        return $this->http_path;
    }

    public function setHttpPath(string $http_path): self
    {
        $this->http_path = $http_path;

        return $this;
    }

    public function getHttpVerb(): ?string
    {
        return $this->http_verb;
    }

    public function setHttpVerb(string $http_verb): self
    {
        $this->http_verb = $http_verb;

        return $this;
    }

    public function getHttpVersion(): ?string
    {
        return $this->http_version;
    }

    public function setHttpVersion(string $http_version): self
    {
        $this->http_version = $http_version;

        return $this;
    }

    public function getResponseCode(): ?int
    {
        return $this->response_code;
    }

    public function setResponseCode(int $response_code): self
    {
        $this->response_code = $response_code;

        return $this;
    }

    public function getServiceName(): ?string
    {
        return $this->service_name;
    }

    public function setServiceName(string $service_name): self
    {
        $this->service_name = $service_name;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(?string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
