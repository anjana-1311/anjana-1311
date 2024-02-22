<?php

/*
	https://stackoverflow.com/questions/67838616/combine-and-minify-css-with-php
	https://uncoverwp.com/course/increase-performance-combine-minify-css-with-php/
*/

header('Content-type: text/css');

function getMinified($content)
{
    $postdata = array('http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query( array('input' => $content) ) ) );
    return file_get_contents('https://cssminifier.com/raw', false, stream_context_create($postdata));
}

function minify($infiles)
{
    $outfiles = [];
    foreach ($infiles as $infile) {
        //$outfiles[] = getMinified(file_get_contents($infile));
       $outfiles[] = file_get_contents($infile);
    }
    return implode("\n", $outfiles);
}

$minified = minify([
	'font-awesome.min.css', 
	'theme.css', 
	'widget.css', 
	'style.css', 
	'style-purvi.css', 
	'style_responsive.css'
]);

echo $minified;
?>