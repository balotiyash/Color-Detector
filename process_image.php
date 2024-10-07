<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    // Load the image
    $filePath = $_FILES['image']['tmp_name'];
    $image = imagecreatefromstring(file_get_contents($filePath));

    // Check if the image was created successfully
    if ($image === false) {
        die("Error loading image.");
    }

    // Get the dimensions of the image
    $width = imagesx($image);
    $height = imagesy($image);

    // Define the region to check (central region)
    $regionWidth = intval($width * 0.25);  // 50% of the width
    $regionHeight = intval($height * 0.25); // 50% of the height
    $startX = intval(($width - $regionWidth) / 2); // Start from the middle horizontally
    $startY = intval(($height - $regionHeight) / 2); // Start from the middle vertically

    // Initialize RGB sums
    $totalR = 0;
    $totalG = 0;
    $totalB = 0;
    $pixelCount = 0;

    // Helper function to convert pixel to RGB
    function intToRGB($colorInt) {
        $r = ($colorInt >> 16) & 0xFF;
        $g = ($colorInt >> 8) & 0xFF;
        $b = $colorInt & 0xFF;
        return [$r, $g, $b];
    }

    // Loop through the central region of the image to get the sum of RGB values
    for ($y = $startY; $y < $startY + $regionHeight; $y++) {
        for ($x = $startX; $x < $startX + $regionWidth; $x++) {
            // Get the color of the pixel
            $rgbInt = imagecolorat($image, $x, $y);
            list($r, $g, $b) = intToRGB($rgbInt);

            // Accumulate the RGB values
            $totalR += $r;
            $totalG += $g;
            $totalB += $b;
            $pixelCount++;
        }
    }

    // Calculate the average RGB values for the region
    $avgR = $totalR / $pixelCount;
    $avgG = $totalG / $pixelCount;
    $avgB = $totalB / $pixelCount;

    // Determine the dominant color based on the average RGB values
    if ($avgR > $avgG && $avgR > $avgB && $avgR > 100) {
        echo "The dominant color is Red.<br>";
    } elseif ($avgG > $avgR && $avgG > $avgB && $avgG > 100) {
        echo "The dominant color is Green.<br>";
    } elseif ($avgB > $avgR && $avgB > $avgG && $avgB > 100) {
        echo "The dominant color is Blue.<br>";
    } else {
        echo "No dominant red, green, or blue color detected.<br>";
    }

    // Clean up
    imagedestroy($image);
} else {
    echo "No image uploaded.";
}
?>
