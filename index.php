<!DOCTYPE html>
<html>
<head>
    <title>MP3 File Uploader</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        a {
            display: inline-block;
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 10px;
        }

        /* Other CSS styles */

        input[type="file"] {
            padding: 10px;
            background-color: #f2f2f2;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #333333;
            cursor: pointer;
        }

        /* Additional styles for file input label */

        .file-label {
            display: inline-block;
            padding: 6px 12px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Hide the default file input text */

        input[type="file"]::-webkit-file-upload-button {
            visibility: hidden;
        }
    </style>
</head>
<body>
<?php
    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the files were uploaded successfully
        if (isset($_FILES['mp3File1']) && isset($_FILES['mp3File2'])) {
            // Specify the directory where you want to save the uploaded files
            $uploadDir = 'uploads/';

            // Get the file names
            $mp3File1Name = $_FILES['mp3File1']['name'];
            $mp3File2Name = $_FILES['mp3File2']['name'];

            // Generate unique file names to avoid conflicts
            $mp3File1Path = $uploadDir . 'mp3File1.mp3';
            $mp3File2Path = $uploadDir . 'mp3File2.mp3';

            // Move the uploaded files to the specified directory
            if (move_uploaded_file($_FILES['mp3File1']['tmp_name'], $mp3File1Path) &&
                move_uploaded_file($_FILES['mp3File2']['tmp_name'], $mp3File2Path)) {
                echo '<div class="success-message">Files uploaded successfully!</div>';
                include './my.php';

                $path = 'uploads/mp3File1.mp3';
                $path1 = 'uploads/mp3File2.mp3';
                $mp3 = new PHPMP3($path);

                $newpath = 'uploads/merged.mp3';
                $mp3->striptags();

                $mp3_1 = new PHPMP3($path1);
                $mp3->mergeBehind($mp3_1);
                $mp3->striptags();
                $mp3->setIdv3_2('01','Track Title','Artist','Album','Year','Genre','Comments','Composer','OrigArtist',
                    'Copyright','url','encodedBy');
                $mp3->save($newpath);

                $path = 'uploads/merged.mp3';

                $mp3 = new PHPMP3($path);
                $mp3->setFileInfoExact();
                echo '<div class="success-message">File Merged successfully!</div>';

                // Perform any further operations with the uploaded files, such as merging or processing
                // You can use the $mp3File1Path and $mp3File2Path variables to access the file paths

                // Specify the output file path for the merged MP3 file
                $mergedFile = 'uploads/merged.mp3';

                // Check if the merged MP3 file exists
                if (file_exists($mergedFile)) {
                    echo '<br><a href="' . $mergedFile . '" download>Download Merged MP3</a>';
                }
            } else {
                echo 'Failed to upload files.';
            }
        } else {
            echo 'Please select two MP3 files to upload.';
        }
    }
    ?>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <input type="file" name="mp3File1" accept=".mp3">
        <br>
        <input type="file" name="mp3File2" accept=".mp3">
        <br>
        <input type="submit" value="Upload">
    </form>

</body>
</html>