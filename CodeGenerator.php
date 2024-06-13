<!DOCTYPE html>
<html>
<body>

<?php
$string = "Progress in Veterinary Science";

function initials($str) {
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret;
}

echo initials($string) . "<br>";

$temp = explode(' ', $string);
$result = '';
foreach($temp as $t)
    $result .= $t[0];
echo $result . "<br>";
?>

</body>
</html>