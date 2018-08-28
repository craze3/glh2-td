<?php

// API client credentials
$GENOMELINK_CLIENT_ID = "sQjuE5VpBchtAV8DlsjBMEfKPS1JvEjdm8pSNj23"; //getenv('GENOMELINK_CLIENT_ID');
$GENOMELINK_CLIENT_SECRET = "IbPFp8iJp8SaSZh3OaOKcfqxbTNhTNTffakRnW0X56kUwFaD5DIgVGqja1iYVvayn6Npte8NL0mIMPGuWI3xkDao1EJeY6NunxoSHwKbDV0opAFrbehO48iyRSckuq6A"; //getenv('GENOMELINK_CLIENT_SECRET');

if ($_POST) {
    // You'll acquire an access token issued per user as a POST parameter
    // after they have successfully uploaded their DNA data via the modal window.
    $genomelinkToken = $_POST['genomelinkToken'];

    // With this `genomelinkToken` value, you can call the API endpoint for acquiring DNA report.
    $curl = curl_init('https://genomelink.io/v1/enterprise/reports/weight-loss/');
    $headers = array("Authorization: Bearer $GENOMELINK_CLIENT_SECRET");
    $data = array('token' => $genomelinkToken);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $data = json_decode($response, true);
    $errno = curl_errno($curl);
    curl_close($curl);

    if (!$errno) {
        switch ($http_code) {
        case 200:
            // If the `genomelinkToken` is valid, a DNA report will be returnd as a JSON response.
            //
            // ```
            // {'reports': [{'phenotype': {'display_name': 'Genetic weight', ...
            //               'summary': {'text': 'Intermediate', ...
            // ```
            $reports = $data['reports'];
            echo "<div class='alert alert-success' role='alert'>
                    <strong>Success:</strong>
                  </div>";
            echo "<table class='table'>
                    <thead>
                      <tr>
                        <th>Phenotype</th>
                        <th>Genetic tendency</th>
                      </tr>
                    </thead>
                  <tbody>";
            foreach ($reports as $report) {
                echo "<tr><td>";
                echo $report['phenotype']['display_name'];
                echo "</td><td>";
                echo $report['summary']['text'];
                echo "</td></tr>";
            }
            echo "</tbody></table>";
            break;
        default:
            echo "<div class='alert alert-danger' role='alert'>
                    <strong>Error ($http_code):</strong>
                  </div>";
        }
    }
}

echo "
<!DOCTYPE html>
<html>
  <head>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  </head>
  <body>
    <div class='container'>
      <div class='page-header'>
        <p class='lead'>&#x1F346;	Doctor</p>
      </div>

      <form action='/' method='POST'>
        <script src='https://sdk.genomelink.io/genomelink.js'
                class='genomelink-button'
                data-key='$GENOMELINK_CLIENT_ID'
                data-email='me@genomelink.io'
                >
        </script>
      </form>
    </div>
  </body>
</html>
";