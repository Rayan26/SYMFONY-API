<?php

namespace App\Entity;

use App\Repository\HistoricQuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=HistoricQuestionRepository::class)
 */
class HistoricQuestion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $question_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $old_title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $new_title;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $old_status;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Choice({"draft", "published"})
     */
    private $new_status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $changedAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionId(): ?int
    {
        return $this->question_id;
    }

    public function setQuestionId(int $question_id): self
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getOldTitle(): ?string
    {
        return $this->old_title;
    }

    public function setOldTitle(string $old_title): self
    {
        $this->old_title = $old_title;

        return $this;
    }

    public function getNewTitle(): ?string
    {
        return $this->new_title;
    }

    public function setNewTitle(string $new_title): self
    {
        $this->new_title = $new_title;

        return $this;
    }

    public function getOldStatus(): ?string
    {
        return $this->old_status;
    }

    public function setOldStatus(string $old_status): self
    {
        $this->old_status = $old_status;

        return $this;
    }

    public function getChangedAt(): ?\DateTimeInterface
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeInterface $changedAt): self
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    public function getNewStatus(): ?string
    {
        return $this->new_status;
    }

    public function setNewStatus(string $new_status): self
    {
        $this->new_status = $new_status;

        return $this;
    }
}
