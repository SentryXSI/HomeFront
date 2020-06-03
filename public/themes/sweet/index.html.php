<?php
declare(strict_types=1);

if( empty( $this->content ) ) {
    $this->content = '<p>Homepage content not found</p>';
}

$assetPath = $this->baseUrl . '/assets/css/';
$themePath = $this->baseUrl . '/themes/sweet/assets/css/';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HomeFront | NinjaSentry Fortified</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?=$assetPath;?>patternfly.min.css?v=a3261f32e" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?=$themePath;?>theme.css?v=ae8a0f4dc" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="<?=$this->baseUrl;?>/assets/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?=$this->baseUrl;?>/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=$this->baseUrl;?>/assets/js/patternfly.min.js"></script>
    <script type="text/javascript" src="<?=$this->baseUrl;?>/themes/sweet/assets/js/main.js"></script>
</head>
<body>
<?php echo $this->content; ?>
</body>
</html>