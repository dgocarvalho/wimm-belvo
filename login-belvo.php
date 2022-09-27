<?php

    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    */
    
    require_once 'global.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Diogo Carvalho">
    <title><?php echo $_ENV['APP_NAME']; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.belvo.io/belvo-widget-1-stable.js" async></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div id="belvo"></div> <!-- Belvo div to load the widget -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        function successCallbackFunction(link, institution) {
            // Sending the link Id and Institution to PHP to validate and save into the Database if necessary
            // for this sample I'll just put the data on PHP Session
            $.post("php/post-link-id.php", 
                    {
                        'link': link,
                        'institution': institution,
                        'user_id' : '<?php echo (isset($_GET['user_id']) ? $_GET['user_id'] : '' ); ?>'
                    },
                    function(data, status){
                        if (status == 'success') {
                            window.location.replace("index.php");
                        }
                    });
        }

        function onExitCallbackFunction(data) {
            // Ready to implement - So far no needed
        }

        function onEventCallbackFunction(data) {
            // Ready to implement - So far no needed
        }

        // call PHP Server to get the token - JSON is expected
        function getAccessToken () { 
            // Make sure to change /get-access-token to point to your server-side.
            return fetch('php/get-access-token.php', { method: 'GET' }) 
                .then(response => response.json())
                .then((data) => data.access)
                .catch(error => console.error('Error:', error))
        }

        function openBelvoWidget(accessToken) {
            belvoSDK.createWidget(accessToken, {
                
                // Setting some parameters to create the widget
                country_codes: ['BR'],
                locale: 'en',
                //institutions: ['erebor_br_retail'],

                callback: (link, institution) => successCallbackFunction(link, institution),

            }).build();
        }

        // Once the access token is retrieved, the widget is started.
        getAccessToken().then(openBelvoWidget) 

        const widget = document.createElement('script');
        widget.setAttribute('src','https://cdn.belvo.io/belvo-widget-1-stable.js');
        document.body.appendChild(widget);
    </script>

</body>

</html>



