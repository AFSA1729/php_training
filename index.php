<?php
$question = "";
$msg = "سوال خود را بپرس!";
$answers = fopen("messages.txt", "r");
$people_contents = file_get_contents('people.json');
$people_decoded = json_decode($people_contents);
$names_array = array();
$j = 1;
foreach ($people_decoded as $en_val => $per_out) 
{
    $names_array[$j] = $en_val;
    $j++;
}
$anss_array = array();
$i = 0;
while (!feof($answers))
{
    $anss_array[$i] = fgets($answers);

    $i++;
}
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $en_name = $_POST["person"];
    $question = $_POST["question"];
    $coded = hash('crc32b', $question . $en_name);
    $coded = hexdec($coded);
    $final_ans_num = ($coded % 16);
    $msg = $anss_array[$final_ans_num];
    foreach ($people_decoded as $en_val => $per_out)
    {
        if ($en_val == $en_name) {
            $fa_name = $per_out;
        }
    }
}
else 
{
    $random = array_rand($names_array);
    $en_name = $names_array[$random];
    foreach ($people_decoded as $en_val => $per_out) {
        if ($en_val == $en_name) {
            $fa_name = $per_out;
        }
    }
}
$a = "/^آیا/iu";$b = "/\?$/i";$c = "/؟$/u";
if(! preg_match($a , $question) || !(preg_match($b , $question) || preg_match($c , $question)) ) 
{
    $msg = "سوال درستی پرسیده نشده";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>

<body>
    <p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
    <div id="wrapper">
        <div id="title">
            <span id="label">
                <?php
                if ($question != "") {
                    echo "پرسش:";
                }
                ?>
            </span>
            <span id="question"><?php echo $question ?></span>
        </div>
        <div id="container">
            <div id="message">
                <p><?php
                    if ($question == "") {
                        echo "سوال خود را بپرس!";
                    } else
                        echo $msg
                    ?></p>
            </div>
            <div id="person">
                <div id="person">
                    <img src="images/people/<?php echo "$en_name.jpg" ?>" />
                    <p id="person-name"><?php echo $fa_name ?></p>
                </div>
            </div>
        </div>
        <div id="new-q">
            <form method="post">
                سوال
                <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..." />
                را از
                <select name="person" value="<?php echo $fa_name ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <?php
                    foreach ($people_decoded as $en_val => $per_out) {
                        if ($en_name == $en_val) {

                            echo "<option value=$en_val selected> $per_out</option> ";
                        } else {
                            echo "<option value=$en_val > $per_out</option> ";
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="بپرس" />
            </form>
        </div>
    </div>
</body>

</html>