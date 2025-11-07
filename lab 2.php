










































































































































































































































































































































































































































































// Q1 Sorting andDisplaying Top Students
<?php
$students = ["Rahul" => 85, "Priya" => 92, "Arun" => 78, "Meena" => 95, "Kiran" => 88];

arsort($students); 

echo "<table border='1' cellpadding='5' cellspacing='0'>
<tr><th>Name</th><th>Marks</th></tr>";
$count = 0;
foreach ($students as $name => $marks) {
    echo "<tr><td>$name</td><td>$marks</td></tr>";
    $count++;
    if ($count == 3) break;
}
echo "</table>";
?>

------------------------------------------------------------------------------------------------------------------------

// Q2. Arrays + File Handling– Reading Products fromFile
<?php
$lines = file("products.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$products = [];

foreach ($lines as $line) {
    list($name, $price) = explode(",", $line);
    $products[$name] = (int)$price;
}

asort($products); 

echo "<table border='1' cellpadding='5' cellspacing='0'>
<tr><th>Product</th><th>Price</th></tr>";
foreach ($products as $name => $price) {
    echo "<tr><td>$name</td><td>$price</td></tr>";
}
echo "</table>";
?>

file:-
Laptop,55000
Mouse,500
Keyboard,1500
Monitor,12000

------------------------------------------------------------------------------------------------------------------------

Q3. Validating Email Addresses
<?php
$emails = ["john@example.com", "wrong-email@", "me@site", "user123@gmail.com"];

foreach ($emails as $email) {
    if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/", $email)) {
        echo "$email<br>";
    }
}
?>

------------------------------------------------------------------------------------------------------------------------

Q4. Regular Expressions + Error Handling– Password Validation

<?php
class PasswordException extends Exception {}

function validatePassword($password) {
    if (strlen($password) < 8) throw new PasswordException("Password must be at least 8 characters");
    if (!preg_match("/[A-Z]/", $password)) throw new PasswordException("Password must contain at least one uppercase letter");
    if (!preg_match("/[0-9]/", $password)) throw new PasswordException("Password must contain at least one digit");
    if (!preg_match("/[@#$%]/", $password)) throw new PasswordException("Password must contain at least one special character (@, #, $, %)");
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];

    try {
        validatePassword($password);
        echo "<p style='color:green;'>Password is valid!</p>";
    } catch (PasswordException $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<form method="post">
    Enter Password: <input type="password" name="password" required>
    <button type="submit">Check</button>
</form>

------------------------------------------------------------------------------------------------------------------------

Q5.  File Handling– Writing and ReadingLogs

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = "admin";
$action = "Logged In";
$timestamp = date("Y-m-d H:i:s");
$logEntry = "$username – $timestamp – $action" . PHP_EOL;

file_put_contents("access.log", $logEntry, FILE_APPEND);

echo "<h2>Access Log</h2>";
echo "<pre>";

if (file_exists("access.log")) {
    $logs = file("access.log", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($logs as $line) {
        echo htmlspecialchars($line) . "\n";
    }
} else {
    echo "Log file not found.";
}

echo "</pre>";
?>

------------------------------------------------------------------------------------------------------------------------

Q6. Date and Time– Calculation

<?php
date_default_timezone_set('Asia/Kolkata');


echo "<h3>Current Date & Time: " . date("d-m-Y H:i:s") . "</h3>";

$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dob_raw = $_POST['dob'] ?? '';

    if (empty($dob_raw)) {
        $message = "<p style='color:red;'>Please enter your Date of Birth.</p>";
    } else {
       
        $birth = DateTime::createFromFormat('Y-m-d', $dob_raw);
        $errors = DateTime::getLastErrors();

        if ($birth === false || $errors['error_count'] || $errors['warning_count']) {
            $message = "<p style='color:red;'>Invalid date format. Please use the date picker (YYYY-MM-DD).</p>";
        } else {
            
            $today = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
            $currentYear = (int)$today->format('Y');

            $bMonth = (int)$birth->format('m');
            $bDay   = (int)$birth->format('d');

          
            if ($bMonth === 2 && $bDay === 29) {
                if (checkdate(2, 29, $currentYear)) {
                    $nextBirthday = DateTime::createFromFormat('Y-m-d', sprintf('%04d-02-29', $currentYear));
                } else {
                    
                    $nextBirthday = DateTime::createFromFormat('Y-m-d', sprintf('%04d-02-28', $currentYear));
                }
            } else {
                $nextBirthday = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $currentYear, $bMonth, $bDay));
            }

            
            if ($nextBirthday < $today) {
                $nextBirthday->modify('+1 year');
            }

            $interval = $today->diff($nextBirthday);
            $daysLeft = (int)$interval->days;
            $formattedNext = $nextBirthday->format('d-m-Y');
            $weekday = $nextBirthday->format('l');

            if ($daysLeft === 0) {
                $message = "<p style='color:green; font-weight:bold;'>Happy Birthday! 🎉 Your birthday is today ({$formattedNext}).</p>";
            } else {
                $message  = "<p>Your next birthday will be on <strong>{$formattedNext}</strong> ({$weekday}).<br>";
                $message .= "Days left until next birthday: <strong>{$daysLeft}</strong>.</p>";
            }
        }
    }
}
?>

<form method="post" style="margin-top:10px;">
    <label>Enter your Date of Birth (YYYY-MM-DD): </label>
    <input type="date" name="dob" required>
    <button type="submit">Check</button>
</form>

<hr>

<?php

if (!empty($message)) {
    echo $message;
}
?>

------------------------------------------------------------------------------------------------------------------------

Q7. Arrays + Error Handling– Average of Numbers

<?php

class EmptyArrayException extends Exception {}


function calculateAverage($numbers) {
    if (empty($numbers)) {
        throw new EmptyArrayException("No numbers provided");
    }
    return array_sum($numbers) / count($numbers);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST["numbers"]; 
    $numbers = explode(",", $input); 

    try {
        $avg = calculateAverage($numbers);
        echo "Average of numbers is: " . $avg;
    } catch (EmptyArrayException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<form method="post">
    Enter numbers separated by comma: <input type="text" name="numbers" required>
    <button type="submit">Calculate</button>
</form>

------------------------------------------------------------------------------------------------------------------------

Q8.  File Handling + Regular Expressions– Extracting Mobile Numbers

<?php
$inputFile  = __DIR__ . '/data.txt';
$outputFile = __DIR__ . '/numbers.txt';

if (!file_exists($inputFile)) {
    $sampleText = "Call me at 9876543210 or at office 9123456789. Old: 12345";
    file_put_contents($inputFile, $sampleText);
}

$data = file_get_contents($inputFile);
preg_match_all('/\b[6-9][0-9]{9}\b/', $data, $matches);

$numbers = array_values(array_unique($matches[0]));

echo "<h3>Extracted Mobile Numbers:</h3>";
if (!empty($numbers)) {
    echo "<ul>";
    foreach ($numbers as $num) {
        echo "<li>$num</li>";
    }
    echo "</ul>";
    file_put_contents($outputFile, implode(PHP_EOL, $numbers) . PHP_EOL);
    echo "<p>Saved " . count($numbers) . " number(s) to numbers.txt</p>";
} else {
    echo "<p>No valid mobile numbers found.</p>";
    file_put_contents($outputFile, "");
}
?>

file:- 
Call me at 9876543210 or at office 9123456789. Wrong: 12345

------------------------------------------------------------------------------------------------------------------------

Q9. Date and Time+FileHandling - Backup FileC reation

<?php
$originalFile = __DIR__ . '/data.txt';

if (!file_exists($originalFile)) {
    die("Error: Original file 'data.txt' not found in " . __DIR__);
}

$dateTime = date("Y-m-d_H-i-s");
$fileInfo = pathinfo($originalFile);
$backupFile = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . '_' . $dateTime . '.' . $fileInfo['extension'];

$result = copy($originalFile, $backupFile);

if ($result) {
    echo "Backup created successfully: " . basename($backupFile);
    echo "<br>Full path: " . $backupFile;
} else {
    echo "Error: Could not create backup in folder " . $fileInfo['dirname'];
}
?>

------------------------------------------------------------------------------------------------------------------------

Q10. CombinedTask– Student Records Validation

<?php
$inputFile = __DIR__ . '/students.txt';
$errorFile = __DIR__ . '/errors.log';

if (!file_exists($inputFile)) {
    die("Error: students.txt not found");
}

$students = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$validRecords = [];
$invalidRecords = [];

foreach ($students as $line) {
    $parts = explode(",", $line);

    if (count($parts) != 3) {
        $invalidRecords[] = $line;
        continue;
    }

    list($name, $email, $dob) = array_map('trim', $parts);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $invalidRecords[] = $line;
        continue;
    }

    $birth = DateTime::createFromFormat('Y-m-d', $dob);
    if (!$birth) {
        $invalidRecords[] = $line;
        continue;
    }

    $today = new DateTime();
    $age = $today->diff($birth)->y;

    $validRecords[] = [
        'name' => $name,
        'email' => $email,
        'age' => $age
    ];
}

if (!empty($invalidRecords)) {
    file_put_contents($errorFile, implode(PHP_EOL, $invalidRecords) . PHP_EOL, FILE_APPEND);
}

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Name</th><th>Email</th><th>Age</th></tr>";

foreach ($validRecords as $student) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($student['name']) . "</td>";
    echo "<td>" . htmlspecialchars($student['email']) . "</td>";
    echo "<td>" . $student['age'] . "</td>";
    echo "</tr>";
}

echo "</table>";
?>


file:- 
Anita,anita123@gmail.com,2000-06-15
Rahul,rahul99@example.com,1998-12-05
Priya,wrong-email@,2001-03-22
Arun,arun_kumar@gmail.com,1999-07-30
Sonia,sonia#mail.com,2002-01-10
Kiran,kiran@example.com,not-a-date

