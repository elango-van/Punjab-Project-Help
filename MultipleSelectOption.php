<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @import "http://fonts.googleapis.com/css?family=Droid+Serif";

        /* Above line is used for online google font */
        div.container {
            width: 960px;
            height: 610px;
            margin: 50px auto;
            font-family: 'Droid Serif', serif
        }

        div.main {
            width: 308px;
            float: left;
            border-radius: 5px;
            border: 2px solid #990;
            padding: 0 50px 20px
        }

        span {
            color: red;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 10px
        }

        b {
            color: green;
            font-weight: 700
        }

        h2 {
            background-color: #FEFFED;
            padding: 25px;
            margin: 0 -50px;
            text-align: center;
            border-radius: 5px 5px 0 0
        }

        hr {
            margin: 0 -50px;
            border: 0;
            border-bottom: 1px solid #ccc;
            margin-bottom: 25px
        }

        label {
            color: #464646;
            text-shadow: 0 1px 0 #fff;
            font-size: 14px;
            font-weight: 700;
            font-size: 17px
        }

        select {
            width: 100%;
            font-family: cursive;
            font-size: 16px;
            background: #f5f5f5;
            padding: 10px;
            border: 1px solid
        }

        input[type=radio] {
            margin-left: 15px;
            margin-top: 10px
        }

        input[type=submit] {
            padding: 10px;
            text-align: center;
            font-size: 18px;
            background: linear-gradient(#ffbc00 5%, #ffdd7f 100%);
            border: 2px solid #e5a900;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            border-radius: 5px
        }

        input[type=submit]:hover {
            background: linear-gradient(#ffdd7f 5%, #ffbc00 100%)
        }
    </style>
</head>

<body>
    <div style="width: 50%;">
        <form action="#" method="post">
        <label class="heading">To Select Multiple Options Press ctrl+left click :</label>
            <select name="Color[]" multiple> // Initializing Name With An Array
                <option value="Red">Red</option>
                <option value="Green">Green</option>
                <option value="Blue">Blue</option>
                <option value="Pink">Pink</option>
                <option value="Yellow">Yellow</option>
                <option value="White">White</option>
                <option value="Black">Black</option>
                <option value="Violet">Violet</option>
                <option value="Limegreen">Limegreen</option>
                <option value="Brown">Brown</option>
                <option value="Orange">Orange</option>
            </select>
            <br>
            <!-- <em>NIFTY</em> -->
            <br>
            <label class="heading">Radio Buttons :</label>
            <input name="radio" type="radio" value="Radio 1">Radio 1
            <input name="radio" type="radio" value="Radio 2">Radio 2
            <input name="radio" type="radio" value="Radio 3">Radio 3
            <input name="radio" type="radio" value="Radio 4">Radio 4
            <br>
            <br>
            <input type="submit" name="submit" value="Get Selected Values" />
        </form>
    </div>

    <?php
    if (isset($_POST['submit'])) {
        if (isset($_POST['Color'])) {
            // As output of $_POST['Color'] is an array we have to use foreach Loop to display individual value
            foreach ($_POST['Color'] as $select) {
                echo "You have selected :" . $select . "<br>"; // Displaying Selected Value
            }
        } else {
            echo "You have not selected : 'Color' <br>";  //  Displaying Selected Value
        }
        if (isset($_POST['radio'])) {
            echo "You have selected :" . $_POST['radio'] . "<br>";  //  Displaying Selected Value
        } else {
            echo "You have not selected : 'radio' <br>";  //  Displaying Selected Value
        }
    }
    ?>
</body>

</html>