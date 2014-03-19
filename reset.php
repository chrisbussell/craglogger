<?php
	// Execute common code
    	require("includes/common.php");
    	require("includes/functions.php");

	// include and register Twig auto-loader
        include 'Twig/Autoloader.php';
        Twig_Autoloader::register();

        // define template directory location
        $loader = new Twig_Loader_Filesystem('templates');

        // initialize Twig environment
        $twig = new Twig_Environment($loader);

        // load template
        $template = $twig->loadTemplate('reset.tmpl');

	





	// set template variables
        // render template
        echo $template->render(array (
        'updated' => '14 Feb 2014',
        'pageTitle' => 'Craglogger',
        'php_self' =>$_SERVER['PHP_SELF'],
  ));

    
?>
