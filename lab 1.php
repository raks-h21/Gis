



















































































































































































































































































































































































































































































Q1. Array input in string

<?php
$color = array('white', 'green', 'red', 'blue', 'black');
$text = "The memory of that scene for me is like a frame of film forever frozen at that moment: 
the $color[2] carpet, the $color[1] lawn, the $color[0] house, the leaden sky. The new president and his first lady. - Richard M. Nixon";
echo $text;
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q2. Print in specific order 
<?php
$color = array('white', 'green', 'red');
echo implode(", ", $color) . "<br>";
foreach ($color as $col) {
    echo "• $col<br>";
}
?>

white, green, red
• white
• green
• red

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q3. Sort capital and country from array

<?php
$ceu = array(
    "Italy"=>"Rome", "Luxembourg"=>"Luxembourg", "Belgium"=>"Brussels", 
    "Denmark"=>"Copenhagen", "Finland"=>"Helsinki", "France"=>"Paris", 
    "Slovakia"=>"Bratislava", "Slovenia"=>"Ljubljana", "Germany"=>"Berlin", 
    "Greece"=>"Athens", "Ireland"=>"Dublin", "Netherlands"=>"Amsterdam", 
    "Portugal"=>"Lisbon", "Spain"=>"Madrid", "Sweden"=>"Stockholm", 
    "United Kingdom"=>"London", "Cyprus"=>"Nicosia", "Lithuania"=>"Vilnius", 
    "Czech Republic"=>"Prague", "Estonia"=>"Tallin", "Hungary"=>"Budapest", 
    "Latvia"=>"Riga", "Malta"=>"Valetta", "Austria"=>"Vienna", "Poland"=>"Warsaw"
);

asort($ceu);

foreach ($ceu as $country => $capital) {
    echo "The capital of $country is $capital<br>";
}
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q4. Delete an element, index normalized 

<?php
$x = array(1, 2, 3, 4, 5);
echo "Original Array:<br>";
var_dump($x);
echo"<br><br>";
unset($x[3]);
$x = array_values($x);
echo "Array after deletion<br>";
var_dump($x);
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q5. Print first element

<?php
$color = array(4 => 'white', 6 => 'green', 11 => 'red');
$first = reset($color);
echo $first;
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q6. Decode JSON string 

<?php
$json = '{
    "Title": "The Cuckoos Calling",
    "Author": "Robert Galbraith",
    "Detail": {
        "Publisher": "Little Brown"
    }
}';

$data = json_decode($json, true);

echo "Title : " . $data['Title'] . "<br>";
echo "Author : " . $data['Author'] . "<br>";
echo "Publisher : " . $data['Detail']['Publisher'] . "<br>";
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q7. Insert element in any position 

<?php
$array = array(1, 2, 3, 4, 5);

echo "Original array :<br>";
foreach ($array as $value) {
    echo $value . " ";
}
echo "<br><br>";
$position = 3;
array_splice($array, $position, 0, '$');

echo "After inserting '\$' the array is :<br>";
foreach ($array as $value) {
    echo $value . " ";
}
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q8. Sort by key and value in both ways

<?php
$people = array("Sophia" => "31", "Jacob" => "41", "William" => "39", "Ramesh" => "40");

asort($people);
echo "a) Ascending order sort by value:<br>";
foreach ($people as $key => $value) {
    echo "$key => $value<br>";
}
echo "<br><br>";

ksort($people);
echo "b) Ascending order sort by key:<br>";
foreach ($people as $key => $value) {
    echo "$key => $value<br>";
}
echo "<br><br>";

arsort($people);
echo "c) Descending order sort by value:<br>";
foreach ($people as $key => $value) {
    echo "$key => $value<br>";
}
echo "<br><br>";

krsort($people);
echo "d) Descending order sort by key:<br>";
foreach ($people as $key => $value) {
    echo "$key => $value<br>";
}
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q9. Calc avg, 5 lowest, 5 highest

<?php
$temperatures = array(
    78, 60, 62, 68, 71, 68, 73, 85, 66, 64, 76, 63, 75, 76,
    73, 68, 62, 73, 72, 65, 74, 62, 62, 65, 64, 68, 73, 75, 79, 73
);

$average = array_sum($temperatures) / count($temperatures);
echo "Average Temperature is : " . round($average, 1) . "<br>";
$unique_temps = array_unique($temperatures);
sort($unique_temps);
$lowest = array_slice($unique_temps, 0, 5);
$highest = array_slice($unique_temps, -5);

echo "List of seven lowest temperatures : " . implode(", ", $lowest) . "<br>";
echo "List of seven highest temperatures : " . implode(", ", $highest) . "<br>";
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q10. Merge 2 arrays by index


<?php
$array1 = array(array(77, 87), array(23, 45));
$array2 = array("w3resource", "com");

$result = array();

for ($i = 0; $i < count($array1); $i++) {
    $result[$i] = array_merge(array($array2[$i]), $array1[$i]);
}

print_r($result);
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q11. value to upper or lower case

<?php
function change_case($array, $case = 'lower') {
    if ($case == 'lower') {
        return array_map('strtolower', $array);
    } elseif ($case == 'upper') {
        return array_map('strtoupper', $array);
    } else {
        return $array;
    }
}

$Color = array('A' => 'Blue', 'B' => 'Green', 'c' => 'Red');
$lowercase = change_case($Color, 'lower');
echo "Values are in lower case.<br>";
print_r($lowercase);
$uppercase = change_case($Color, 'upper');
echo "<br><br>Values are in upper case.<br>";
print_r($uppercase);
?>

--------------------------------------------------------------------------------------------------------------------------------------------------------

Q12. all nos btw 200 and 250, divisible by 4

<?php
echo "Numbers divisible by 4 betwwen 200 and 250:<br>";
$numbers = range(200, 250);
$divBy4 = array_filter($numbers, fn($n) => $n % 4 === 0);
echo implode(",", $divBy4);
?>

