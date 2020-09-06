<?php

header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$uploadDir = time();
mkdir($uploadDir, 0750);

// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vérifie si le fichier a été uploadé sans erreur.
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
        $allowed = array("csv" => "text/csv");
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];

        // Vérifie l'extension du fichier
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");

        // Vérifie la taille du fichier - 5Mo maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: La taille du fichier est supérieure à la limite autorisée.");

        // Vérifie le type MIME du fichier
        if(in_array($filetype, $allowed)){
            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists($_FILES["photo"]["name"])){
                echo $_FILES["photo"]["name"] . " existe déjà.";
            } else{
                move_uploaded_file($_FILES["photo"]["tmp_name"], $uploadDir."/CSVExtraction.csv");
                echo "Votre fichier a été téléchargé avec succès.";
            } 
        } else{
            die( "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer."); 
        }
    } else{
        die( "Error: " . $_FILES["photo"]["error"]);
    }
}

# Traitement du fichier csv -> html -> pdf
	$command = escapeshellcmd('/usr/bin/python3 /var/www/my_webapp__3/www/FCP/pandas_csv.py ' . $uploadDir);	
	Shell_exec($command);

# Exportation du fichier généré
	header("Content-type:application/pdf");
	header("Content-Disposition:attachment;filename=".$uploadDir."output.pdf");
	readfile($uploadDir."/output.pdf");

# Supprime les fichiers temporaires du serveur
	unlink($uploadDir.'/output.pdf');
	unlink($uploadDir.'/output.html');
	unlink($uploadDir.'/CSVExtraction.csv');
	rmdir($uploadDir);
?>


