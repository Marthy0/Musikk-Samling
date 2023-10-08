<?php

// receive.php
// print_r($_FILES);
// print_r($_POST);
$orgKatalog = "../ms_bildealbum_org/";
$katalog = "../ms_bildealbum/";
$filnavn = $_POST["filnavn"];
$melding = "";

function visMeldingOgAvslutt($melding) {
    echo $melding;
    echo "<script>alert('$melding')</script>";
    exit;
}

if (is_uploaded_file($_FILES["image"]["tmp_name"]) == true) {

    echo "File successfully received through HTTP POST.<br/>";

    // Validate the file size, accept files under 5 MB (~5e+6 bytes).

    if ($_FILES['image']['size'] > 5000000) visMeldingOgAvslutt("Filen er for stor, over 5MB");
    if ($_FILES['image']['type'] != "image/jpeg") visMeldingOgAvslutt("Feil filtype, bare jpeg er støttet!");
    // if (strtoupper(substr($filnavn, -4, 4)) != ".JPG") $filnavn = $filnavn . ".JPG";

        echo "File size: ".$_FILES["image"]["size"]." bytes.<br/>";

        // Resize and save the image.

        $image = $_FILES["image"]["tmp_name"];
        $orientering = exif_read_data($image)["Orientation"];
        $filOrg = $orgKatalog.$filnavn;
        $filLite = $katalog.$filnavn;
        
        if (file_exists($filLite)) visMeldingOgAvslutt("Filen $filLite finnes allerede, ikke lastet!");
        if (file_exists($filOrg)) visMeldingOgAvslutt("Filen $filOrg finnes allerede, ikke lastet!");
    
        // copy($image, $resizedDestination);

        $imageSize = getImageSize($image);
        $imageWidth = $imageSize[0];
        $imageHeight = $imageSize[1];

        $DESIRED_WIDTH = 250;
        $proportionalHeight = round(($DESIRED_WIDTH * $imageHeight) / $imageWidth);

        $originalImage = imageCreateFromJPEG($image);
        $rotert = false;
        var_dump($orientering);
        if (!empty($orientering)) {
            var_dump($orientering);
            switch ($orientering) {
                case 3:
                    $originalImage = imagerotate($originalImage, 180, 0);
                    $rotert = true;
                    break;

                case 6:
                    $originalImage = imagerotate($originalImage, -90, 0);
                    $rotert = true;var_dump($orientering);
                    break;

                case 8:
                    $originalImage = imagerotate($originalImage, 90, 0);
                    $rotert = true;
                    break;
            }
        }
        if ($rotert) $melding = $melding . "Bilde rotert\\n";


        $resizedImage = imageCreateTrueColor($DESIRED_WIDTH, $proportionalHeight);

        imageCopyResampled($resizedImage, $originalImage, 0, 0, 0, 0, $DESIRED_WIDTH+1, $proportionalHeight+1, $imageWidth, $imageHeight);
        if (imageJPEG($resizedImage, $filLite)) $melding = $melding . "Krympet bilde lagret på $filLite\\n";


        // Save the original image.
        if ($rotert) {
           if (imageJPEG($originalImage, $filOrg)) $melding = $melding . "Orginalt rotert bilde lagret på $filOrg";
        } else {
           if (move_uploaded_file($image, $filOrg)) $melding = $melding . "Originalt bilde lagret på $filOrg";
        }

        imageDestroy($originalImage);
        imageDestroy($resizedImage);

        // Save the original image.

        visMeldingOgAvslutt($melding);
    

}

?>
