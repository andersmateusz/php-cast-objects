# Example usage
```php
class Publication {
    public $title;
    public $author;
}

class Book extends Publication {
    public $isbn;
}

$publication = new Publication();
$publication->title = 'The Great Gatsby';
$publication->author = 'F. Scott Fitzgerald';

$book = cast(Book::class, ['isbn' => '0743273567'], $publication);
echo get_class($book); // Book
echo $book->isbn; // 0743273567
echo $book->title; // The Great Gatsby
echo $book->author; // F. Scott Fitzgerald
```