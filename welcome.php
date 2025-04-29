<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $headline = trim($_POST["headline"]);

    if (!empty($headline)) {
        $api_url = "http://127.0.0.1:5000/predict";
        $post_data = json_encode(["headline" => $headline]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
    } else {
        $error_message = "Please enter a news headline.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fake News Finder</title>
  <!-- <link rel="stylesheet" href="styles.css"> -->

</head>
<body>
  <div class="container mt-5">
    <h2 class="text-center">Fake News Finder</h2>
    <form method="POST">
      <div class="mb-3">
        <textarea name="headline" class="form-control" rows="3" placeholder="Enter a news headline"></textarea>
      </div>
      <button type="submit" class="btn btn-primary w-100">SUBMIT</button>
    </form>

    <?php if (!empty($result)): ?>
      <div class="mt-4 text-center">
        <p><strong>Model Prediction:</strong> <?php echo $result["model_prediction"]; ?></p>
        <p><strong>API Validation:</strong> <?php echo $result["api_validation"]; ?></p>
      </div>
    <?php elseif (!empty($error_message)): ?>
      <div class="mt-4 text-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

  </div>
</body>
</html>
