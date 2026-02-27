<?php
namespace App\Domain\Book;


use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'books')]
final class Book
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;


    #[ORM\Column(type: 'string')]
    private string $title;

    #[ORM\Column(type: 'boolean')]
    private bool $available = true;


    public function __construct(BookId $id, string $title)
    {
        $this->id = $id->value();
        $this->title = $title;
    }

    public function borrow(): void
    {
        if (!$this->available) {
            throw new \DomainException('Book not available');
        }


        $this->available = false;
    }
    public function id(): BookId
    {
        return new BookId($this->id);
    }

    public function return(): void
    {
        $this->available = true;
    }
    public function isAvailable(): bool
    {
        return $this->available;
    }   
}
