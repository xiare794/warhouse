<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.js"></script>
    <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.css">

    <script type="text/javascript">
        $(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');
        });
    </script>
</head>
<body>
    <select id="id_select" class="selectpicker bla bla bli" multiple data-live-search="true">
        <option>cow</option>
        <option>bull</option>
        <option class="get-class" disabled>ox</option>
        <optgroup label="test" data-subtext="another test" data-icon="icon-ok">
            <option>ASD</option>
            <option selected>Bla</option>
            <option>Ble</option>
        </optgroup>
    </select>
</body>
</html>
