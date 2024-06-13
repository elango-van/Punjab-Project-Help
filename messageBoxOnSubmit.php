<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    if (isset($_POST['submit'])) {
        //data checks here...
        //database stuff here...      

        echo '<div class="msg">'
            . 'YOUR MESSAGE IN HERE'
            . '</div>';

        echo '<script>alert("Welcome to Geeks for Geeks")</script>';
    }
    ?>

    <style>
        .msg {
            border: 1px solid #bbb;
            padding: 5px;
            margin: 10px 0px;
            background: #eee;
        }
    </style>

    <form action="" method="post">
        Name:<input type="text" id="name" name="name" />
        <input type="submit" value="submit" name="submit" />
    </form>
</body>

</html>