<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Perpustakaan Online - Temukan dan cari buku-buku terbaik">
    <meta name="keywords" content="Buku, Baca, Cari Buku">
    <meta name="author" content="@bimoarifw">
    <meta name="robots" content="index, follow">
    <title>Library - @bimoarifw</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>

<body>
    <header>
        <a href="/index.php">
            <h1 class="logo"><span style="color: #ff9100;">lib</span>r<span style="color: #ff9100;">ary</span></h1>
        </a>
        <form method="GET" action="index.php">
            <input required type="text" name="query" id="search" placeholder="Cari buku..." value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
            <button type="submit" id="searchBtn">Cari</button>
        </form>
        <?php
        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $query = $_GET['query'];
            echo '<div class="search-message">Menampilkan hasil untuk "' . htmlspecialchars($query) . '"</div>';
        }
        ?>
    </header>
    <div class="menu">
        <?php
        if (isset($_GET['query'])) {
            include 'api.php';
            $query = $_GET['query'];
            if (!empty($query)) {
                $books = fetchBooks($query);
                displayBooks($books);
            } else {
                header('Location: index.php');
                exit;
            }
        } else {
            include 'api.php';
            $popularBooks = fetchPopularBooks();
            echo '<div class="judulpopuler">Daftar 5 Buku Terpopuler:</div>';
            echo '<div class="displaypopuler">';
            displayBooks($popularBooks);
            echo '</div>';
            $recommendedBooks = fetchRecommendedBooks();
            echo '<div class="judulrekomendasi">Daftar 5 Buku Rekomendasi:</div>';
            echo '<div class="displayrekomendasi">';
            displayBooks($recommendedBooks);
            echo '</div>';
        }
        ?>
    </div>
    <button id="scrollToTopBtn" title="Kembali ke Atas">↑</button>
    <footer>
        <a href="https://bimoarifw.my.id">
            <p>@bimoarifw</p>
        </a>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var input = document.getElementById("search");
            var searchBtn = document.getElementById("searchBtn");
            input.addEventListener("keyup", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    searchBtn.click();
                }
            });
        });
        var scrollToTopBtn = document.getElementById("scrollToTopBtn");
        scrollToTopBtn.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
        window.addEventListener("scroll", function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        });
    </script>
</body>

</html>
