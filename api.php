<?php
$apiKey = 'hehehe siuu';

function fetchPopularBooks()
{
    global $apiKey;
    $url = "https://www.googleapis.com/books/v1/volumes?q=subject:popular&maxResults=40&key={$apiKey}";
    $response = file_get_contents($url);
    $data = json_decode($response);

    if (isset($data->items)) {
        $allBooks = $data->items;

        $booksWithImages = array_filter($allBooks, function ($book) {
            return isset($book->volumeInfo->imageLinks->thumbnail);
        });

        $booksWithImages = array_values($booksWithImages);

        shuffle($booksWithImages);

        $randomBooks = array_slice($booksWithImages, 0, 5);

        return $randomBooks;
    } else {
        return null;
    }
}

function fetchRecommendedBooks()
{
    global $apiKey;

    $authors = array(
        'dee lestari',
        'jk rowling',
        'boy candra',
        'ika natassa',
        'emha ainun najib',
        'pramoedya ananta toer',
        'percy jackson',
        'the witcher',
        'harry potter',
        'Andrea Hirata',
        'Ayu Utami',
        'Ahmad Tohari',
        'Eka Kurniawan',
        'Leila S. Chudori',
        'Raditya Dika',
        'Tia Setiadi',
        'N.H. Dini',
        'Djenar Maesa Ayu',
        'Laksmi Pamuntjak',
        'William Shakespeare',
        'Charles Dickens',
        'Jane Austen',
        'Leo Tolstoy',
        'Gabriel García Márquez',
        'To Kill a Mockingbird',
        '1984',
        'Pride and Prejudice',
        'The Great Gatsby',
        'War and Peace',
        'Klara and the Sun',
        'No One Is Talking About This',
        'The Push',
        'The Four Winds',
        'The Sanatorium',
    );

    $randomAuthor = $authors[array_rand($authors)];

    $query = urlencode($randomAuthor);
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&maxResults=5&key={$apiKey}";

    $response = file_get_contents($url);

    return json_decode($response)->items;
}

function fetchBooks($query)
{
    global $apiKey;
    $query = urlencode($query);
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&key={$apiKey}&maxResults=40&orderBy=relevance";
    $response = file_get_contents($url);
    $data = json_decode($response);

    if (!isset($data->items)) {
        return null;
    }

    $searchResults = $data->items;
    usort($searchResults, function($a, $b) {
        $ratingA = isset($a->volumeInfo->averageRating) ? $a->volumeInfo->averageRating : 0;
        $ratingB = isset($b->volumeInfo->averageRating) ? $b->volumeInfo->averageRating : 0;
        return $ratingB - $ratingA;
    });

    return $searchResults;
}

function displayBooks($books)
{
    if (($books) === null) {
        echo '<p>Buku yang Anda cari tidak ada.</p>';
    } else {
        echo '<div class="grid-row">';
        foreach ($books as $book) {
            echo '<div class="book" data-book-id="' . $book->id . '">';
            if (isset($book->volumeInfo->imageLinks->thumbnail)) {
                echo '<img src="' . $book->volumeInfo->imageLinks->thumbnail . '" alt="' . $book->volumeInfo->title . '">';
            } else {
                echo '<p> No image available </p>';
            }
            $title = $book->volumeInfo->title;
            if (strlen($title) > 50) {
                $title = substr($title, 0, 50) . '...';
            }
            echo '<h3>' . $title . '</h3>';
            if (isset($book->volumeInfo->authors)) {
                $authors = $book->volumeInfo->authors;
                $authorText = $authors[0];
                if (count($authors) > 1) {
                    $authorText .= ' dkk.';
                }
                echo '<p>' . $authorText . '</p>';
            } else {
                echo '<p>No authors available</p>';
            }
            if (isset($book->volumeInfo->description)) {
                echo '<p class="warp">' . substr($book->volumeInfo->description, 0, 50) . '...</p>';
            } else {
                echo '<p>No description available</p>';
            }
            if (isset($book->volumeInfo->publishedDate)) {
                echo '<p>' . substr($book->volumeInfo->publishedDate, 0, 50) . '</p>';
            } else {
                echo '<p>No volume information</p>';
            }
            echo '</div>';
        }
        echo '</div>';
    }
}
?>
