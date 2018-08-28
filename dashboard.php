<?php
require_once('vendor/autoload.php'); 
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',    // The client ID assigned to you by the provider
    'clientSecret'            => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',   // The client password assigned to you by the provider
    'redirectUri'             => 'https://blastify.io/labs/topdrawer/dashboard.php',
    'urlAuthorize'            => 'https://genomelink.io/oauth/authorize',
    'urlAccessToken'          => 'https://genomelink.io/oauth/token', // ?
    'urlResourceOwnerDetails' => 'https://genomelink.io/oauth/resource' // ?
]);

include_once "db.php";

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
	$options = [
		'scope' => 'report:alpha-linolenic-acid report:beta-carotene report:blood-glucose report:caffeine-metabolite-ratio report:calcium report:depression report:neuroticism report:extraversion report:folate report:iron report:magnesium report:phosphorus report:sleep-duration report:smoking-behavior report:vitamin-e report:vitamin-d report:vitamin-b12 report:waist-hip-ratio report:vitamin-a report:endurance-performance report:bmi report:anger'
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

}
?>
<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="./img/icon.png" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./img/icon.png" />
    <!-- Generated: 2018-04-16 09:29:05 +0200 -->
    <title>TopDrawer : Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <script src="./assets/js/require.min.js"></script>
    <script>
      requirejs.config({
          baseUrl: '.'
      });
	  
	  function loadFeature(x) {
		alert("This feature is not yet available to the public");
	  }
    </script>
    <!-- Dashboard Core -->
    <link href="./assets/css/dashboard.css" rel="stylesheet" />
    <script src="./assets/js/dashboard.js"></script>
    <!-- c3.js Charts Plugin -->
    <link href="./assets/plugins/charts-c3/plugin.css" rel="stylesheet" />
    <script src="./assets/plugins/charts-c3/plugin.js"></script>
    <!-- Google Maps Plugin -->
    <link href="./assets/plugins/maps-google/plugin.css" rel="stylesheet" />
    <script src="./assets/plugins/maps-google/plugin.js"></script>
    <!-- Input Mask Plugin -->
    <script src="./assets/plugins/input-mask/plugin.js"></script>
  </head>
  <body class="">
    <div class="page">
      <div class="page-main">
        <div class="header py-4">
          <div class="container">
            <div class="d-flex">
              <a class="header-brand" href="./">
                <img src="img/icon.svg" style="margin-left: -10px; max-height: 1.75em;"> &nbsp;<img src="img/logo.png" class="header-brand-img" alt="tabler logo">
              </a>
              <div class="d-flex order-lg-2 ml-auto">
				<!--
                <div class="nav-item d-none d-md-flex">
                  <a href="https://github.com/tabler/tabler" class="btn btn-sm btn-outline-primary" target="_blank">Source code</a>
                </div>
				-->
                <div class="dropdown d-none d-md-flex">
                  <a class="nav-link icon" data-toggle="dropdown">
                    <i class="fe fe-bell"></i>
                    <span class="nav-unread"></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a href="#" class="dropdown-item d-flex">
                      <span class="avatar mr-3 align-self-center" style="background-image: url('./img/icon.svg')"></span>
                      <div>
                        <strong>@topdrawer</strong> posted an update: Added 7 new studies
                        <div class="small text-muted">10 minutes ago</div>
                      </div>
                    </a>
                    <a href="#" class="dropdown-item d-flex">
                      <span class="avatar mr-3 align-self-center" style="background-image: url('./img/icon.svg')"></span>
                      <div>
                        <strong>@topdrawer</strong> posted an update: Added BMI advice
                        <div class="small text-muted">1 hour ago</div>
                      </div>
                    </a>
                    <a href="#" class="dropdown-item d-flex">
                      <span class="avatar mr-3 align-self-center" style="background-image: url('./img/icon.svg')"></span>
                      <div>
                        <strong>@topdrawer</strong> posted an update: Fixed mobile scaling
                        <div class="small text-muted">2 hours ago</div>
                      </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center text-muted-dark">Mark all as read</a>
                  </div>
                </div>
				<!--
                <div class="dropdown">
                  <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                    <span class="avatar" style="background-image: url(./demo/faces/female/25.jpg)"></span>
                    <span class="ml-2 d-none d-lg-block">
                      <span class="text-default">Jane Pearson</span>
                      <small class="text-muted d-block mt-1">Administrator</small>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-user"></i> Profile
                    </a>
                    <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-settings"></i> Settings
                    </a>
                    <a class="dropdown-item" href="#">
                      <span class="float-right"><span class="badge badge-primary">6</span></span>
                      <i class="dropdown-icon fe fe-mail"></i> Inbox
                    </a>
                    <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-send"></i> Message
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-help-circle"></i> Need help?
                    </a>
                    <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-log-out"></i> Sign out
                    </a>
                  </div>
                </div>
              </div>
			  -->
              <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
              </a>
            </div>
          </div>
        </div>
        <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-3 ml-auto">
                <form class="input-icon my-3 my-lg-0">
                  <input type="search" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
                  <div class="input-icon-addon">
                    <i class="fe fe-search"></i>
                  </div>
                </form>
              </div>
              <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                  <li class="nav-item">
                    <a href="#" class="nav-link active"><i class="fa fa-user-o"></i> My Report</a>
                  </li>
				  <li class="nav-item">
                    <a href="#" onclick="loadFeature();" class="nav-link"><i class="fa fa-book"></i> Studies</a>
                  </li>
				  <li class="nav-item">
                    <a href="#" onclick="loadFeature();" class="nav-link"><i class="fa fa-gears"></i> Settings</a>
                  </li>
				  <li class="nav-item">
                    <a href="#" onclick="loadFeature();" class="nav-link"><i class="fa fa-question-circle-o"></i> Support</a>
                  </li>
				  <!--
                  <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-box"></i> Interface</a>
                    <div class="dropdown-menu dropdown-menu-arrow">
                      <a href="./cards.html" class="dropdown-item ">Cards design</a>
                      <a href="./charts.html" class="dropdown-item ">Charts</a>
                      <a href="./pricing-cards.html" class="dropdown-item ">Pricing cards</a>
                    </div>
                  </li>
				
                  <li class="nav-item dropdown">
                    <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-calendar"></i> Components</a>
                    <div class="dropdown-menu dropdown-menu-arrow">
                      <a href="./maps.html" class="dropdown-item ">Maps</a>
                      <a href="./icons.html" class="dropdown-item ">Icons</a>
                      <a href="./store.html" class="dropdown-item ">Store</a>
                      <a href="./blog.html" class="dropdown-item ">Blog</a>
                      <a href="./carousel.html" class="dropdown-item ">Carousel</a>
                    </div>
                  </li>
                  <li class="nav-item dropdown">
                    <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-file"></i> Pages</a>
                    <div class="dropdown-menu dropdown-menu-arrow">
                      <a href="./profile.html" class="dropdown-item ">Profile</a>
                      <a href="./login.html" class="dropdown-item ">Login</a>
                      <a href="./register.html" class="dropdown-item ">Register</a>
                      <a href="./forgot-password.html" class="dropdown-item ">Forgot password</a>
                      <a href="./400.html" class="dropdown-item ">400 error</a>
                      <a href="./401.html" class="dropdown-item ">401 error</a>
                      <a href="./403.html" class="dropdown-item ">403 error</a>
                      <a href="./404.html" class="dropdown-item ">404 error</a>
                      <a href="./500.html" class="dropdown-item ">500 error</a>
                      <a href="./503.html" class="dropdown-item ">503 error</a>
                      <a href="./email.html" class="dropdown-item ">Email</a>
                      <a href="./empty.html" class="dropdown-item ">Empty page</a>
                      <a href="./rtl.html" class="dropdown-item ">RTL mode</a>
                    </div>
                  </li>
                  <li class="nav-item dropdown">
                    <a href="./form-elements.html" class="nav-link"><i class="fe fe-check-square"></i> Forms</a>
                  </li>
                  <li class="nav-item">
                    <a href="./gallery.html" class="nav-link"><i class="fe fe-image"></i> Gallery</a>
                  </li>
                  <li class="nav-item">
                    <a href="./docs/index.html" class="nav-link"><i class="fe fe-file-text"></i> Documentation</a>
                  </li>
				  -->
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="my-3 my-md-5">
          <div class="container">
            <div class="page-header">
              <h1 class="page-title">
                Your Sexual Health Report:
              </h1>
            </div>
			
			<?php
			if (isset($_GET['code'])) {
				try {

					// Try to get an access token using the authorization code grant.
					$accessToken = $provider->getAccessToken('authorization_code', [
						'code' => $_GET['code']/*,
						'grant_type' => 'authorization_code'*/
					]);

					/*
					// We have an access token, which we may use in authenticated
					// requests against the service provider's API.
					echo 'Access Token: ' . $accessToken->getToken() . "<br>";
					echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
					echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
					echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";
*/

					// Using the access token, we may look up details about the
					// resource owner.
					//$resourceOwner = $provider->getResourceOwner($accessToken);

					//var_export($resourceOwner->toArray());

					// The provider provides a way to get an authenticated API request for
					// the service, using the access token; it returns an object conforming
					// to Psr\Http\Message\RequestInterface.
					
					$reports = array("calcium", "magnesium", "blood-glucose", "beta-carotene", "alpha-linolenic-acid", "depression", "folate", "iron", "phosphorus", "sleep-duration", "vitamin-d", "vitamin-b12", "vitamin-e", "waist-hip-ratio",
					"bmi", "neuroticism", "extraversion", "smoking-behavior", "caffeine-metabolite-ratio", "vitamin-a", "endurance-performance");
					
					/*
					foreach($reports as $report)
					{
						$request = $provider->getAuthenticatedRequest(
							'GET',
							'https://genomelink.io/v1/reports/'.$report.'/?population=european',
							$accessToken
						);
						$httpResponse = $provider->getResponse($request);

						$reportData = json_decode($httpResponse->getBody()->getContents());
						
						print "<br /><br />\n";
					}
					*/

				} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

					// Failed to get the access token or user details.
					exit($e->getMessage());

				}

			}
			?>

			<div class="row row-cards row-deck">
			    <div class="col-lg-5 col-xl-5 col-sm-12 col-xs-12">
					<div class="card">
					  <!--
					  <div class="card-header">
						<h3 class="card-title">Built card</h3>
						<div class="card-options">
						  <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
						  <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
						</div>
					  </div>
					  -->
					  <div class="card-body" style="text-align: center;">
						<div style="width: 40%; display: inline-block; vertical-align: top;">
							<img src="img/pulse2.svg" style="height: 160px"/>
						</div>
						<div style="width: 54%; text-align: right; display: inline-block; vertical-align: top;">
							<div id="opps" style="font-size: 110px; font-weight: bold; margin-bottom: -25px; margin-top: -18px;">?</div>
							<div style="color: grey; font-size: 20px;">OPPORTUNITIES FOUND</div>
							<div style="color: grey; opacity: 0.5; font-size: 14px;">FOR IMPROVING YOUR SEXUAL HEALTH</div>
						</div>
					  </div>
					</div>
				</div>
				 <div class="col-lg-7 col-xl-7 col-sm-12 col-xs-12">
					<div class="card">
					  <div class="card-body" style="text-align: left;">
						We scanned your genes looking for proven correlations and/or causations between certain phenotic traits (listed below) and certain genes (or more precisely, SNPs, which are specific foci in the genome where there is high variation among humans). Based on that, we generated a list of tips to help improve your sexual health. Click on a trait below to see the advice, and the links to the studies/sources referenced.
					  </div>
					</div>
				</div>
			</div>
			
            <div class="row row-cards row-deck">
              <div class="col-12">
                <div class="card">
                  <div class="table-responsive">
                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                      <thead>
                        <tr>
                          <th class="text-center w-1"><i class="icon-people"></i></th>
                          <th>Genetic Trait</th>
                          <th>Your Score</th>
                          <!--<th class="text-center">Payment</th>-->
                          <th>Predisposition</th>
                          <th class="text-center">At Risk?</th>
                          <th class="text-center"><i class="icon-settings"></i></th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php 
					  $moreWorse = array('blood-glucose', 'depression', 'bmi', 'neuroticism', 'extraversion', 'smoking-behavior');
					  $totalRisks = 0;
					  
					  foreach($reports as $report) {
						  $request = $provider->getAuthenticatedRequest(
							'GET',
							'https://genomelink.io/v1/reports/'.$report.'/?population=european',
							$accessToken
						);
						$httpResponse = $provider->getResponse($request);

						$reportData = json_decode($httpResponse->getBody()->getContents(), true);
						
						$name = $reportData['phenotype']['url_name'];
						$displayName = $reportData['phenotype']['display_name'];
						
						$score = $reportData['summary']['score'];
						$percentage = ($score / 4) * 100;
						$predispo = $reportData['summary']['text'];
						?>
                        <tr onclick="$('#details_<?php echo $name; ?>').slideToggle();">
                          <td class="text-center">
                            <div class="avatar d-block" style="background-image: url('https://genomelink.io/static/img/developers/phenotypes/<?php echo $name; ?>.png');">
                              <!--<span class="avatar-status bg-green"></span>-->
                            </div>
                          </td>
                          <td>
                            <div><?php echo $displayName; ?></div>
                          </td>
                          <td>
                            <div class="clearfix">
                              <div class="float-left">
                                <strong><?php echo $percentage; ?>%</strong>
                              </div>
                              <div class="float-right">
                                <small class="text-muted">                            
								<?php
							 $currentMoreWorse = in_array($name, $moreWorse);
								$at_risk = 0;
								$color = 'bg-green';
								
							 if(($currentMoreWorse && $score >=3) || (!$currentMoreWorse && $score <= 1))
							 {
								 $at_risk = 1;
								 //$color = 'bg-yellow';
								 //print "<span style='color:#ff4e4e'>AT RISK!</span>";
								 $totalRisks++;
							 }
							 if(($currentMoreWorse && $score >3) || (!$currentMoreWorse && $score < 1))
							 {
								 $at_risk = 2;
								 //$color = "bg-red";
							 }
							 
							 // SCORE EXPLANATIONS
							 if($currentMoreWorse)
							 {
								 $action = "Reducing"; 
								 
								 switch($score)
								 {
									case 0:
										$explanation = "a really great score that you should be happy about";
										$color = "bg-green";
										break;
									case 1: 
										$explanation = "a good score and no cause for worry";
										$color = "bg-green";
										break;
									case 2: 
										$explanation = "a moderate ranking which is neither good or bad";
										$color = "bg-yellow";
										break;
									case 3: 
										$explanation = "a slightly higher score than average and a slight cause for worry";
										$color = "bg-red";
										break;
									case 4:
										$explanation = "an extremely high score which should be viewed as a sexual health risk factor";
										$color = "bg-red";
										break;
								 }
							 }
							 else
							 {
								 $action = "Increasing";
								 
								 switch($score)
								 {
									case 0:
										$explanation = "an extremely low score which should be considered a sexual health risk factor";
										$color = "bg-red";
										break;
									case 1:
										$explanation = "a lower than average score which could be a potential risk factor";
										$color = "bg-red";
										break;
									case 2: 
										$explanation = "an average score which is neither good or bad";
										$color = "bg-yellow";
										break;
									case 3: 
										$explanation = "a higher than average score which is good";
										$color = "bg-green";
										break;
									case 4:
										$explanation = "a great score indicating no potential sexual health risk factors";
										$color = "bg-green";
										break;
								 }
							 }
							 
							 ?>
							 </small>
                              </div>
                            </div>
                            <div class="progress progress-xs">
                              <div class="progress-bar <?php echo $color; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%"
						     aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
						  <!--
                          <td class="text-center">
                            <i class="payment payment-visa"></i>
                          </td>
						  -->
                          <td>
                            <!--<div class="small text-muted">Last login</div>-->
                            <div><b><?php echo $predispo; ?></b></div>
                          </td>
                          <td class="text-center">
						  <!--
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.42" data-thickness="3" data-color="blue">
                              <div class="chart-circle-value">42%</div>
                            </div>
							-->
							<a href="#"><?php if($at_risk >= 1) print "<i class='fa fa-exclamation-triangle' style='color: #ff5050; font-size: 20px;'></i>"; ?></a>
                          </td>
                          <td class="text-center">
                            <div class="item-action dropdown">
                              <a href="#"  class="icon"><i class="fa fa-chevron-down"></i></a>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-tag"></i> Action </a>
                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Another action </a>
                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-message-square"></i> Something else here</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
						
						<tr class="details" id="details_<?php echo $name; ?>" style="display: none;">
							<td colspan="6" style="font-size: 16px; line-height: 28px;">
							<b><u>About:</u></b><br />
							<!--After scanning your DNA for 100,000 common SNPs,-->Your DNA revealed that your predisposition for <?php echo $displayName; ?> is: <?php echo $predispo; ?>. This is <?php echo $explanation; ?>. 
							<br />
							<b><u>Advice:</u></b><br />
							<?php echo $action; ?> your <?php echo $displayName; ?> levels can help:
							<br />
							<ul>
								<?php 
									$q = @mysql_query("SELECT * FROM advice WHERE report = '$name'") or die(mysql_error());
									while($arr = mysql_fetch_array($q))
									{
										if($arr['gender'] == "M") $sexicon = "<i class='fa fa-mars' style='color: blue'></i>";
										else if($arr['gender'] == "F") $sexicon = "<i class='fa fa-venus' style='color: pink'></i>";
										else $sexicon = "<i class='fa fa-venus-mars'></i>";
										
										print "<li style='width: 50vw;'><b>".$sexicon. " &nbsp;" . ucfirst($arr['benefit']) . "</b> (Source: <a href='".$arr['study_url']."' target='_blank'>".$arr['study_name']."</a>)";
									}
								?>
							</ul>
							
							</td>
						</tr>
						<?php
					  }
					  
					  print "<script>document.getElementById('opps').innerHTML = '" . $totalRisks . "';</script>";
					  ?>
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
			  
           
      </div>
      <div class="footer">
        <div class="container">
          <div class="row">
            <div class="col-lg-8">
              <div class="row">
			   <div class="col-6 col-md-3">
                  <ul class="list-unstyled mb-0">
                    <li><a href="#">Home</a></li>
                    <!--<li><a href="#">Sixth link</a></li>-->
                  </ul>
                </div>
                <div class="col-6 col-md-3">
                  <ul class="list-unstyled mb-0">
                    <li><a href="#">Medical Disclaimer</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                  </ul>
                </div>
                <div class="col-6 col-md-3">
                  <ul class="list-unstyled mb-0">
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Contact Us</a></li>
                  </ul>
                </div>
               
				<!--
                <div class="col-6 col-md-3">
                  <ul class="list-unstyled mb-0">
                    <li><a href="#">Other link</a></li>
                    <li><a href="#">Last link</a></li>
                  </ul>
                </div>
				-->
              </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
              Personalized recommendations for improving your sexual health, powered by insights from your own DNA.
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container">
          <div class="row align-items-center flex-row-reverse">
            <div class="col-auto ml-lg-auto">
			<!--
              <div class="row align-items-center">
                <div class="col-auto">
                  <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item"><a href="./docs/index.html">Documentation</a></li>
                    <li class="list-inline-item"><a href="./faq.html">FAQ</a></li>
                  </ul>
                </div>
                <div class="col-auto">
                  <a href="https://github.com/tabler/tabler" class="btn btn-outline-primary btn-sm">Source code</a>
                </div>
              </div>
			 -->
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
              Copyright © 2018 <a href="./">TopDrawer</a>. All rights reserved.
            </div>
          </div>
        </div>
      </footer>
    </div>
  </body>
</html>