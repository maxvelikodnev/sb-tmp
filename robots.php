<?php
/**
 * Rewrites robots.txt from robots.php
 * @author     Ari <ari@fluid7.co.uk>
 */

$siteBases = array (
                '.aero',
                '.asia',
                '.biz',
                '.cat',
                '.com',
                '.co.uk',
                '.coop',
                '.edu',
                '.gov',
                '.info',
                '.int',
                '.jobs',
                '.mil',
                '.mobi',
                '.museum',
                '.name',
                '.net',
                '.org',
                '.pro',
                '.tel',
                '.travel',
                '.xxx'
);

//eg: www.panthro.co.uk to www.pantho
$domainParts = explode ( '.', str_replace( $siteBases, '', $_SERVER ['HTTP_HOST'] ));

//Sites without a prefix or with the prefix of www
if(count($domainParts) == 1 || $domainParts[0] == 'www'){
        $liveSite = true;
//All other prefixes
}else {
        $liveSite = false;
}

header ( 'Content-type: text/plain' );
if ($liveSite) {
        // This is the content for the live site.
        //@Author Hardik
        $file = new SplFileObject("robots.txt");
        // Loop until we reach the end of the file.
        while (!$file->eof()) {
            // Echo one line from the file.
            echo $file->fgets();
        }
        // Unset the file to call __destruct(), closing the file handle.
        $file = null;

        //Adding Sitemap: START <---
        $sitemapFolder = str_replace('www.', '', $_SERVER['HTTP_HOST']);
        if(isset($_SERVER['HTTPS'])) {
                $requestScheme = 'https://';
        }else {
                $requestScheme = 'http://';
        }

        echo 'Sitemap: '.$requestScheme.$_SERVER['HTTP_HOST'].'/sitemap.xml';
        //--> Sitemap END
} else {
        // This is the content for the dev sites
        echo "
# Block everything
User-agent: *
Disallow: /
        ";
}

?>
