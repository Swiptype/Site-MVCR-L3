<!DOCTYPE html> 
<html lang='fr'>
    <head>
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="skin/screen.css"/>

        <style>
            <?php echo $this->style;?>
        </style>

    </head>
    <body>
        <nav class="menu">
            <ul>
                <?php
                    foreach ($this->getMenu() as $text => $link) {
                        echo "<li><a href='$link'>$text</a></li>";
                    }
                ?>
            </ul>
        </nav>

        <main>
        <h1><?php echo $title; ?></h1>
        <p><?php echo $this->feedback; ?></p>
        <?php echo $content; ?>
        </main>

    </body>
</html>