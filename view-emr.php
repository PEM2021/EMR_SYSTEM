<?php
// Database config
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'emr_db';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query all EMR records
$sql = "SELECT * FROM emr_records ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Submitted EMR Records</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
      background: #f4f4f4;
    }

    h2 {
      color: #333;
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
      vertical-align: top;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    td {
      word-break: break-word;
    }
  </style>
</head>
<body>

  <h2>Submitted EMR Records</h2>

  <table>
    <tr>
      <th>ID</th>
      <th>Patient Name</th>
      <th>DOB</th>
      <th>Visit Date</th>
      <th>Provider</th>
      <th>Chief Complaint</th>
      <th>HPI</th>
      <th>ROS</th>
      <th>Vitals</th>
      <th>Physical Exam</th>
      <th>Diagnosis</th>
      <th>Plan</th>
      <th>CPT Codes</th>
      <th>HCPCS Codes</th>
      <th>Modifiers</th>
      <th>Submitted At</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['patient_name']) ?></td>
          <td><?= $row['dob'] ?></td>
          <td><?= $row['visit_date'] ?></td>
          <td><?= htmlspecialchars($row['provider']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['chief_complaint'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['hpi'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['ros'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['vitals'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['physical_exam'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['diagnosis'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['plan'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['cpt_codes'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['hcpcs_codes'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['modifiers'])) ?></td>
          <td><?= $row['submitted_at'] ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="16">No records found.</td></tr>
    <?php endif; ?>
  </table>

</body>
</html>

<?php $conn->close(); ?>
