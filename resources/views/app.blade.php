<!DOCTYPE html>
<html>
    <head>
        <meta id="token" name="token" value="{{ csrf_token() }}">
        <title>Music City</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Music City</h1>
                    <a href="/">Home</a>
                    <a href="/artists">Your Artists</a>
                    <a href="/albums">Your Record Collection</a>
                </div>
            </div>

            <div class="row">
                @yield('content')
            </div>
        </div>
    </body>
</html>