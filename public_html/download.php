<?php
// Function to filter data based on branch and year
function filter_feedback_data($data, $Branch, $Year) {
    $filtered_data = array_filter($data, function ($row) use ($Branch, $Year) {
        return $row[17] == $Branch && $row[1] == $Year;
    });

    return array_values($filtered_data); // Reset array keys
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $Branch = $_POST['branch'];
    $Year = $_POST['year'];

    // Load the CSV file into a PHP array
    $csv_file_path = '../data/faculty.csv';

    // Check if the file exists
    if (!file_exists($csv_file_path)) {
        die('CSV file not found.');
    }

    // Read CSV file
    $csv_data = array_map('str_getcsv', file($csv_file_path));

    // Check if the file is empty
    if (empty($csv_data)) {
        die('CSV file is empty or cannot be read.');
    }

    // Filter data based on branch and year
    $filtered_data = filter_feedback_data($csv_data, $Branch, $Year);

    // Check if there is filtered data
    if (empty($filtered_data)) {
        die('No data found for the selected branch and year.');
    }

    // Create the 'tmp' directory if it doesn't exist
    if (!file_exists('tmp')) {
        mkdir('tmp', 0777, true);
    }

    // Save filtered data to a new CSV file
    $filtered_file_path = 'tmp/' . $Branch . '_' . $Year . '_faculty.csv';
    $output_handle = fopen($filtered_file_path, 'w');
    foreach ($filtered_data as $row) {
        fputcsv($output_handle, $row);
    }
    fclose($output_handle);

    // Create an array to store teacher and subject feedback calculations
    $teacher_feedback = array();

    // Process filtered data to calculate feedback for each teacher and subject
    foreach ($filtered_data as $row) {
        $teacher = $row[3]; // Assuming teacher's name is in column index 3
        $subject = $row[4]; // Assuming subject is in column index 4

        // Create an entry for each teacher and subject if not exists
        if (!isset($teacher_feedback[$teacher][$subject])) {
            $teacher_feedback[$teacher][$subject] = array(
                'SyllabusCoverage' => 0,
                'Preparation' => 0,
                'Communication' => 0,
                'TeachingApproach' => 0,
                'Fairness' => 0,
                'Discussion' => 0,
                'Illustration' => 0,
                'ICTUsage' => 0,
                'Quality' => 0,
                'Effectiveness' => 0,
                'Count' => 0
            );
        }

        // Update feedback for each teacher and subject
        $teacher_feedback[$teacher][$subject]['SyllabusCoverage'] += intval($row[5]);
        $teacher_feedback[$teacher][$subject]['Preparation'] += intval($row[6]);
        $teacher_feedback[$teacher][$subject]['Communication'] += intval($row[7]);
        $teacher_feedback[$teacher][$subject]['TeachingApproach'] += intval($row[8]);
        $teacher_feedback[$teacher][$subject]['Fairness'] += intval($row[9]);
        $teacher_feedback[$teacher][$subject]['Discussion'] += intval($row[10]);
        $teacher_feedback[$teacher][$subject]['Illustration'] += intval($row[11]);
        $teacher_feedback[$teacher][$subject]['ICTUsage'] += intval($row[12]);
        $teacher_feedback[$teacher][$subject]['Quality'] += intval($row[13]);
        $teacher_feedback[$teacher][$subject]['Effectiveness'] += intval($row[14]);
        $teacher_feedback[$teacher][$subject]['Count']++;
    }

    // Save teacher feedback to a new CSV file
    $teacher_feedback_file_path = 'tmp/' . $Branch . '_' . $Year . '_teacher_feedback.csv';
    $output_handle = fopen($teacher_feedback_file_path, 'w');

    // Add headers to the CSV file
    $headers = array(
        'Academic Year', 'Teacher', 'Subject',
        'Syllabus Coverage', 'Preparation', 'Communication',
        'Teaching Approach', 'Fairness', 'Discussion',
        'Illustration', 'ICT Usage', 'Quality',
        'Effectiveness'
    );
    fputcsv($output_handle, $headers);

    // Write teacher feedback to the CSV file
    foreach ($teacher_feedback as $teacher => $subjects) {
        foreach ($subjects as $subject => $feedback) {
            $row = array(
                $Year, $teacher, $subject,
                $feedback['SyllabusCoverage'] / $feedback['Count'],
                $feedback['Preparation'] / $feedback['Count'],
                $feedback['Communication'] / $feedback['Count'],
                $feedback['TeachingApproach'] / $feedback['Count'],
                $feedback['Fairness'] / $feedback['Count'],
                $feedback['Discussion'] / $feedback['Count'],
                $feedback['Illustration'] / $feedback['Count'],
                $feedback['ICTUsage'] / $feedback['Count'],
                $feedback['Quality'] / $feedback['Count'],
                $feedback['Effectiveness'] / $feedback['Count']
            );
            fputcsv($output_handle, $row);
        }
    }

    fclose($output_handle);

    // Create a zip file
    $zip_file_path = 'tmp/' . $Branch . '_' . $Year . '_feedback.zip';
    $zip = new ZipArchive();
    if ($zip->open($zip_file_path, ZipArchive::CREATE) === true) {
        // Add both CSV files to the zip
        $zip->addFile($filtered_file_path, basename($filtered_file_path));
        $zip->addFile($teacher_feedback_file_path, basename($teacher_feedback_file_path));

        $zip->close();

        // Send the zip file as a download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zip_file_path) . '"');
        readfile($zip_file_path);

        // Clean up: Remove temporary CSV files and the zip file
        unlink($filtered_file_path);
        unlink($teacher_feedback_file_path);
        unlink($zip_file_path);

        exit;
    } else {
        die('Failed to create the zip file.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form Download</title>
</head>
<body>

    <h1>Feedback Form Download</h1>

    <form action="" method="post">
        <label for="year">Select Year:</label>
        <select name="year" id="year">
            <option value="SE">SE</option>
            <option value="TE">TE</option>
            <option value="BE">BE</option>
        </select>

        <label for="branch">Select Branch:</label>
        <select name="branch" id="branch">
            <option value="CS">CS</option>
            <option value="IT">IT</option>
            <option value="AIDS">AIDS</option>
        </select>

        <button type="submit">Download Feedback Form</button>
    </form>

</body>
</html>
