<?php

namespace App\Entity;

use ApiPlatform\Metadata as Api;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\State\CustomerProcessor;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\Table(name: 'customer')]
#[ORM\UniqueConstraint(name: 'uniq_customer_email_per_client', columns: ['client_id', 'email'])]
#[ORM\HasLifecycleCallbacks]
#[Api\ApiResource(
    operations: [
        new Api\GetCollection(security: "is_granted('ROLE_CLIENT')"),
        new Api\Get(security: "is_granted('ROLE_CLIENT') and object.getClient() == user"),
        // processor will attach the authenticated Client on POST
        new Api\Post(
            securityPostDenormalize: "is_granted('ROLE_CLIENT')",
            processor: CustomerProcessor::class
        ),
        new Api\Delete(security: "is_granted('ROLE_CLIENT') and object.getClient() == user"),
    ],
    normalizationContext: ['groups' => ['customer:read']],
    denormalizationContext: ['groups' => ['customer:write']]
)]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'email', 'fullName'])]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'partial', 'fullName' => 'partial'])]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['customer:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['customer:read','customer:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Groups(['customer:read','customer:write'])]
    private ?string $fullName = null;

    #[ORM\Column]
    #[Groups(['customer:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $this->createdAt ??= new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getFullName(): ?string { return $this->fullName; }
    public function setFullName(string $fullName): static { $this->fullName = $fullName; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getClient(): ?Client { return $this->client; }
    public function setClient(?Client $client): static { $this->client = $client; return $this; }
}
