<?php
require_once('vendor/autoload.php'); 
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'sQjuE5VpBchtAV8DlsjBMEfKPS1JvEjdm8pSNj23',    // The client ID assigned to you by the provider
    'clientSecret'            => 'IbPFp8iJp8SaSZh3OaOKcfqxbTNhTNTffakRnW0X56kUwFaD5DIgVGqja1iYVvayn6Npte8NL0mIMPGuWI3xkDao1EJeY6NunxoSHwKbDV0opAFrbehO48iyRSckuq6A',   // The client password assigned to you by the provider
    'redirectUri'             => 'https://blastify.io/labs/ddoctor/login.php',
    'urlAuthorize'            => 'https://genomelink.io/oauth/authorize',
    'urlAccessToken'          => 'https://genomelink.io/oauth/token', // ?
    'urlResourceOwnerDetails' => 'https://genomelink.io/oauth/resource' // ?
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
	$options = [
		'scope' => 'report:alpha-linolenic-acid report:beta-carotene report:blood-glucose report:caffeine-metabolite-ratio report:calcium report:depression report:neuroticism report:extraversion report:folate report:iron report:magnesium report:phosphorus report:sleep-duration report:smoking-behavior report:vitamin-e report:vitamin-d report:vitamin-b12 report:waist-hip-ratio report:vitamin-a report:endurance-performance report:bmi'
	];
    $authorizationUrl = $provider->getAuthorizationUrl($options);

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    
    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']/*,
			'grant_type' => 'authorization_code'*/
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo 'Access Token: ' . $accessToken->getToken() . "<br>";
        echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
        echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
        echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";

        // Using the access token, we may look up details about the
        // resource owner.
        //$resourceOwner = $provider->getResourceOwner($accessToken);

        //var_export($resourceOwner->toArray());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
		
		$reports = array("calcium", "magnesium", "blood-glucose", "beta-carotene", "alpha-linolenic-acid", "depression", "folate", "iron", "phosphorus", "sleep-duration", "vitamin-d", "vitamin-b12", "vitamin-e", "waist-hip-ratio",
		"bmi", "neuroticism", "extraversion", "smoking-behavior", "caffeine-metabolite-ratio", "vitamin-a", "endurance-performance");
		
		foreach($reports as $report)
		{
			$request = $provider->getAuthenticatedRequest(
				'GET',
				'https://genomelink.io/v1/reports/'.$report.'/?population=european',
				$accessToken
			);
			$httpResponse = $provider->getResponse($request);

			print $httpResponse->getBody()->getContents();
			print "<br /><br />\n";
		}

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}
////////////////////////////////////////
?>