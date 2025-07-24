<?php
    $name = "Rei";
    $food = "sushi";
    $age = 20;
    $gpa = 1.44;
    $money = 200; 
    $allowance = 50;
    $total_money = null;

    echo "{$name} <br>";
    echo "Hello {$name}! <br>";
    echo "You like {$food}! <br>";
    echo "I am {$age} years old! <br>";
    echo "My GPA is {$gpa} <br>";
    echo "I have \${$money} in my bank! <br>";

    echo "I have \${$money} and I have \${$allowance} <br>";
    $total_money = $money + $allowance;

    echo "Therefore my total money is \${$total_money} <br>";

    // Arithmetic Operation
    // + - * / ** %

    $x = 10;
    $y = 5;
    $z = $x + $y;
    echo "The sum of {$x} and {$y} is {$z} <br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <br>
    <button> Click Me!</button>
</body>
</html>