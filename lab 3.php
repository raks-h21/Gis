





































































































































































































































































































































































































































































// Q1: Cookie-Based Visit Counter
<?php
if (isset($_COOKIE['visits'])) {
    $count = intval($_COOKIE['visits']) + 1;
} else {
    $count = 1;
}
setcookie('visits', $count, time() + 3600, "/");
?>
<!doctype html>
<html>
<head><title></title></head>
<body>
    <h1>Q1: Cookie-Based Visit Counter</h1>
    <p>Welcome! You have visited this page <?php echo $count; ?> time<?php echo $count>1 ? 's' : ''; ?>.</p>
    <p><a href="Q1_cookie_counter.php">Refresh</a> | <a href="Q1_cookie_reset.php">Reset</a></p>
</body>
</html>

<?php
// Q1 helper: reset visits cookie
setcookie('visits', '', time() - 3600, "/");
header('Location: q1.php');
exit;
?>

--------------------------------------------------------------------------------------------------------

// Q2: Session-Based Login Authentication (login form + processing)
<?php
session_start();
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    // Demo credentials: admin / 1234
    if ($user === 'admin' && $pass === '1234') {
        $_SESSION['user'] = 'admin';
        header('Location: Q2_welcome.php');
        exit;
    } else {
        $err = 'Invalid credentials.';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q2 - Login</title></head>
<body>
    <h1>Q2: Login</h1>
    <?php if ($err): ?><p style="color:red;"><?php echo htmlspecialchars($err); ?></p><?php endif; ?>
    <form method="post" action="Q2_login.php">
        <label>Username: <input type="text" name="user" required></label><br><br>
        <label>Password: <input type="password" name="pass" required></label><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>

<?php
// Q2: logout
session_start();
session_unset();
session_destroy();
header('Location: Q2_login.php');
exit;
?>

------------------------------------------------------------------------------------------------------

// Q3: Remember Me Functionality with Cookies
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    if (!empty($_POST['remember'])) {
        // store for 30 days
        setcookie('username', $username, time() + 60*60*24*30, "/");
    } else {
        setcookie('username', '', time() - 3600, "/");
    }
    $_SESSION['user'] = $username ?: 'Guest';
    header('Location: Q3_remember_me.php');
    exit;
}
$cookieUser = $_COOKIE['username'] ?? '';
$welcome = $_SESSION['user'] ?? ($cookieUser ?: 'Guest');
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q3 - Remember Me</title></head>
<body>
    <h1>Q3: Remember Me</h1>
    <p>Welcome <?php echo htmlspecialchars($welcome); ?></p>
    <form method="post" action="Q3_remember_me.php">
        <label>Username: 
        <input type="text" name="username" value="<?php echo htmlspecialchars($cookieUser); ?>"></label><br><br>
        <label><input type="checkbox" name="remember" value="1"> Remember Me</label><br><br>
        <button type="submit">Save</button>
    </form>
    <p><a href="Q3_remember_clear.php">Clear Remembered Username</a></p>
</body>
</html>

<?php
// Q3 helper: clear remembered username cookie
setcookie('username', '', time() - 3600, "/");
header('Location: Q3_remember_me.php');
exit;
?>

------------------------------------------------------------------------------------------------------

// Q4: Simple CAPTCHA image using GD
<?php
session_start();
$captcha = strval(rand(1000, 9999));
$_SESSION['captcha'] = $captcha;
$w = 100; $h = 40;
$image = imagecreate($w, $h);
$bg = imagecolorallocate($image, 255, 255, 255);
$noise_color = imagecolorallocate($image, 200, 200, 200);
$text_color = imagecolorallocate($image, 0, 0, 0);
for ($i=0;$i<6;$i++) {
    imageline($image, rand(0,$w), rand(0,$h), rand(0,$w), rand(0,$h), $noise_color);
}
imagestring($image, 5, 18, 10, $captcha, $text_color);
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>

<?php
// Q4: CAPTCHA verification form
session_start();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['captcha'] ?? '');
    if (isset($_SESSION['captcha']) && $input === $_SESSION['captcha']) {
        $msg = "CAPTCHA verified successfully.";
    } else {
        $msg = "Wrong CAPTCHA. Try again.";
    }
    unset($_SESSION['captcha']);
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q4 - CAPTCHA</title></head>
<body>
    <h1>Q4: CAPTCHA Demo</h1>
    <?php if ($msg): ?><p><?php echo htmlspecialchars($msg); ?></p><?php endif; ?>
    <form method="post" action="Q4_captcha_form.php">
        <p><img src="Q4_captcha_image.php" alt="CAPTCHA image"></p>
        <label>Enter CAPTCHA: <input type="text" name="captcha" required></label><br><br>
        <button type="submit">Verify</button>
    </form>
</body>
</html>

------------------------------------------------------------------------------------------------------

// Q5: Embedding and Displaying an Uploaded Image
<?php
// Ensure "uploads/" exists and is writable
$uploadedUrl = '';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $targetDir = __DIR__ . '/uploads/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $f = $_FILES['file'];
    if ($f['error'] === UPLOAD_ERR_OK) {
        $info = getimagesize($f['tmp_name']);
        if ($info === false) {
            $message = "Uploaded file is not a valid image.";
        } else {
            $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', basename($f['name']));
            $dest = $targetDir . $safeName;
            if (move_uploaded_file($f['tmp_name'], $dest)) {
                $uploadedUrl = 'uploads/' . $safeName;
            } else {
                $message = "Failed to move uploaded file.";
            }
        }
    } else {
        $message = "Upload error code: " . $f['error'];
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q5 - Upload Image</title></head>
<body>
    <h1>Q5: Upload an Image</h1>
    <?php if ($message): ?><p style="color:red;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
    <form method="post" enctype="multipart/form-data" action="Q5_upload_image.php">
        <input type="file" name="file" accept="image/*" required><br><br>
        <button type="submit">Upload</button>
    </form>
    <?php if ($uploadedUrl): ?>
        <h2>Uploaded Image</h2>
        <img src="<?php echo htmlspecialchars($uploadedUrl); ?>" style="max-width:400px;">
    <?php endif; ?>
</body>
</html>

------------------------------------------------------------------------------------------------------

// Q6: Create and Draw Graphics Dynamically (GD)
<?php
$width = 300; $height = 200;
$img = imagecreate($width, $height);
$bg = imagecolorallocate($img, 255, 255, 255);
$red = imagecolorallocate($img, 255, 0, 0);
$green = imagecolorallocate($img, 0, 200, 0);
$blue = imagecolorallocate($img, 0, 0, 255);
imagerectangle($img, 20, 20, 120, 120, $red);
imagefilledellipse($img, 200, 80, 100, 60, $green);
imageline($img, 0, $height-1, $width-1, 0, $blue);
header('Content-Type: image/png');
imagepng($img);
imagedestroy($img);
?>

------------------------------------------------------------------------------------------------------

// Q7: Add Text on an Image (watermark)
<?php
// If sample.jpg not present, script creates a fallback image.
$sourcePath = __DIR__ . '/sample.jpg';
if (!file_exists($sourcePath)) {
    $img = imagecreatetruecolor(400, 200);
    $bg = imagecolorallocate($img, 230, 230, 230);
    imagefilledrectangle($img, 0, 0, 400, 200, $bg);
    $text = "No sample.jpg found";
    $black = imagecolorallocate($img, 0, 0, 0);
    imagestring($img, 5, 10, 90, $text, $black);
} else {
    $img = imagecreatefromjpeg($sourcePath);
    $blue = imagecolorallocate($img, 0, 0, 255);
    imagestring($img, 5, 10, 10, "VIT Chennai", $blue);
}
header('Content-Type: image/jpeg');
imagejpeg($img, null, 85);
imagedestroy($img);
?>

------------------------------------------------------------------------------------------------------

// Q8: Image Resizing and Scaling (upload + resize keeping aspect ratio)
<?php
$resizedUrl = '';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $f = $_FILES['image'];
    if ($f['error'] === UPLOAD_ERR_OK) {
        $info = getimagesize($f['tmp_name']);
        if ($info === false) {
            $message = "Invalid image.";
        } else {
            list($w, $h, $type) = $info;
            $maxWidth = 400;
            if ($w > $maxWidth) {
                $ratio = $maxWidth / $w;
                $newW = (int)($w * $ratio);
                $newH = (int)($h * $ratio);
            } else {
                $newW = $w; $newH = $h;
            }
            switch ($type) {
                case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($f['tmp_name']); break;
                case IMAGETYPE_PNG:  $src = imagecreatefrompng($f['tmp_name']); break;
                case IMAGETYPE_GIF:  $src = imagecreatefromgif($f['tmp_name']); break;
                default: $src = null; break;
            }
            if (!$src) {
                $message = "Unsupported image type.";
            } else {
                $dst = imagecreatetruecolor($newW, $newH);
                if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
                    imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }
                imagecopyresampled($dst, $src, 0,0,0,0, $newW, $newH, $w, $h);
                $targetDir = __DIR__ . '/uploads/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                $safe = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', basename($f['name']));
                $outPath = $targetDir . 'resized_' . $safe;
                imagejpeg($dst, $outPath, 85);
                imagedestroy($src);
                imagedestroy($dst);
                $resizedUrl = 'uploads/' . 'resized_' . $safe;
            }
        }
    } else {
        $message = "Upload error: " . $f['error'];
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q8 - Resize Image</title></head>
<body>
    <h1>Q8: Resize Image</h1>
    <?php if ($message): ?><p style="color:red;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
    <form method="post" enctype="multipart/form-data" action="Q8_resize_image.php">
        <input type="file" name="image" accept="image/*" required><br><br>
        <button type="submit">Upload & Resize</button>
    </form>
    <?php if ($resizedUrl): ?>
        <h2>Resized Image</h2>
        <img src="<?php echo htmlspecialchars($resizedUrl); ?>" style="max-width:600px;">
    <?php endif; ?>
</body>
</html>

------------------------------------------------------------------------------------------------------

// Q9: Color Palette Manipulation - random colored shapes using GD
<?php
$w = 300; $h = 300;
$img = imagecreatetruecolor($w, $h);
$bg = imagecolorallocate($img, 255, 255, 255);
imagefilledrectangle($img, 0, 0, $w, $h, $bg);
for ($i=0; $i<12; $i++) {
    $color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
    $x = rand(10, $w-10);
    $y = rand(10, $h-10);
    $rx = rand(15, 60);
    $ry = rand(15, 60);
    imagefilledellipse($img, $x, $y, $rx, $ry, $color);
}
header('Content-Type: image/png');
imagepng($img);
imagedestroy($img);
?>

------------------------------------------------------------------------------------------------------

// Q10: Send an Email with an Attachment (using mail())
<?php
// Note: mail() must be configured on the server
$sent = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = trim($_POST['to'] ?? '');
    $subject = trim($_POST['subject'] ?? 'No subject');
    $messageText = trim($_POST['message'] ?? '');
    $from = trim($_POST['from'] ?? 'noreply@example.com');
    $headers = "From: $from";
    if (isset($_FILES['attach']) && $_FILES['attach']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['attach']['tmp_name'];
        $fileName = basename($_FILES['attach']['name']);
        $data = file_get_contents($fileTmp);
        $base64 = chunk_split(base64_encode($data));
        $separator = md5(time());
        $eol = PHP_EOL;
        $headers .= $eol . "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
        $body = "--" . $separator . $eol;
        $body .= "Content-Type: text/plain; charset=\"utf-8\"" . $eol;
        $body .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
        $body .= $messageText . $eol;
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $fileName . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment; filename=\"" . $fileName . "\"" . $eol . $eol;
        $body .= $base64 . $eol;
        $body .= "--" . $separator . "--";
        if (mail($to, $subject, $body, $headers)) {
            $sent = true;
        } else {
            $error = "Mail failed (mail() returned false).";
        }
    } else {
        if (mail($to, $subject, $messageText, $headers)) {
            $sent = true;
        } else {
            $error = "Mail failed (mail() returned false).";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q10 - Send Email</title></head>
<body>
    <h1>Q10: Send Email with Attachment</h1>
    <?php if ($sent): ?><p style="color:green;">Mail sent successfully.</p><?php elseif ($error): ?><p style="color:red;"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <form method="post" enctype="multipart/form-data" action="Q10_send_email_attachment.php">
        <label>To: <input type="email" name="to" required></label><br><br>
        <label>From: <input type="email" name="from" value="noreply@example.com" required></label><br><br>
        <label>Subject: <input type="text" name="subject" value="Test Email"></label><br><br>
        <label>Message:<br><textarea name="message" rows="6" cols="60"></textarea></label><br><br>
        <label>Attachment: <input type="file" name="attach"></label><br><br>
        <button type="submit">Send</button>
    </form>
    <p>Note: server must be configured to send mail.</p>
</body>
</html>

------------------------------------------------------------------------------------------------------

// Q11: Generate Gradient Image
<?php
$w = 300; $h = 200;
$img = imagecreatetruecolor($w, $h);
for ($x = 0; $x < $w; $x++) {
    $r = (int)($x * 255 / $w);
    $g = 100;
    $b = 255 - $r;
    $col = imagecolorallocate($img, $r, $g, $b);
    imageline($img, $x, 0, $x, $h, $col);
}
header('Content-Type: image/png');
imagepng($img);
imagedestroy($img);
?>

------------------------------------------------------------------------------------------------------

// Q12: Dynamic Image Banner with Timestamp
<?php
$w = 600; $h = 100;
$img = imagecreate($w, $h);
$bg = imagecolorallocate($img, 240, 240, 240);
$black = imagecolorallocate($img, 0, 0, 0);
$text = "Generated on " . date("Y-m-d H:i:s");
$font = 5;
$tw = imagefontwidth($font) * strlen($text);
$th = imagefontheight($font);
$x = ($w - $tw) / 2;
$y = ($h - $th) / 2;
imagestring($img, $font, $x, $y, $text, $black);
header('Content-Type: image/png');
imagepng($img);
imagedestroy($img);
?>

------------------------------------------------------------------------------------------------------

// Q13: Personalized Greeting Using Sessions
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $_SESSION['name'] = trim($_POST['name']);
    header('Location: Q13_greet.php');
    exit;
}
$name = $_SESSION['name'] ?? 'Student';
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q13 - Greeting</title></head>
<body>
    <h1>Hello, <?php echo htmlspecialchars($name); ?>! Welcome to the PHP lab.</h1>
    <form method="post" action="Q13_greet.php">
        <label>Set your name: <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>"></label>
        <button type="submit">Save</button>
    </form>
    <p><a href="Q13_greet_clear.php">Clear Name</a></p>
</body>
</html>


<?php
// 13 helper
session_start();
unset($_SESSION['name']);
header('Location: Q13_greet.php');
exit;
?>


------------------------------------------------------------------------------------------------------

// Q14: Simple Contact Form with mail()
<?php
$sent = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = 'example@domain.com';
    $subject = 'Contact Form Message';
    $message = trim($_POST['message'] ?? '');
    $from = trim($_POST['from'] ?? 'user@domain.com');
    $headers = "From: " . $from;
    if (!empty($message)) {
        if (mail($to, $subject, $message, $headers)) {
            $sent = true;
        } else {
            $error = 'Mail failed (ensure mail() is configured).';
        }
    } else {
        $error = 'Message cannot be empty.';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q14 - Contact Form</title></head>
<body>
    <h1>Q14: Contact Form</h1>
    <?php if ($sent): ?><p style="color:green;">Mail Sent!</p><?php elseif ($error): ?><p style="color:red;"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <form method="post" action="Q14_contact.php">
        <label>Your Email: <input type="email" name="from" required></label><br><br>
        <label>Message:<br><textarea name="message" rows="6" cols="60" required></textarea></label><br><br>
        <button type="submit">Send</button>
    </form>
</body>
</html>

------------------------------------------------------------------------------------------------------

// Q15: Feedback Form with Session and Email
<?php
session_start();
$_SESSION['user'] = $_SESSION['user'] ?? 'Guest';
$sent = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = trim($_POST['feedback'] ?? '');
    $name = $_SESSION['user'] ?? 'Guest';
    if ($feedback === '') {
        $error = 'Feedback cannot be empty.';
    } else {
        $msg = "Feedback from $name: " . $feedback;
        if (mail('admin@vit.ac.in', 'Student Feedback', $msg, 'From: noreply@vit.ac.in')) {
            $sent = true;
        } else {
            $error = 'Failed to send feedback (mail not configured).';
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Q15 - Feedback</title></head>
<body>
    <h1>Q15: Feedback Form</h1>
    <p>Logged in as: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
    <?php if ($sent): ?><p style="color:green;">Thank you, <?php echo htmlspecialchars($_SESSION['user']); ?>. Feedback sent!</p><?php elseif ($error): ?><p style="color:red;"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <form method="post" action="Q15_feedback.php">
        <label>Feedback:<br><textarea name="feedback" rows="6" cols="60" required></textarea></label><br><br>
        <button type="submit">Send Feedback</button>
    </form>
</body>
</html>
