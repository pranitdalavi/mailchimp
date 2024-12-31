<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="app">
        <div class="container-fluid my-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action nav-link" data-target="contact-creation">Contact Creation</a>
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action nav-link" data-target="contact-updation">Contact Updation</a>
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action nav-link" data-target="contact-updation-diff-tags">Contact Updation - Different Tags</a>
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action nav-link" data-target="download-csv">Download CSV</a>
                    </div>
                </div>

                <div class="col-md-9">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>

</html>


<script>
    $(document).ready(function() {
        $(".upload-form").hide();

        $(".nav-link").click(function() {
            var targetForm = $(this).data('target');
            $(".upload-form").hide();
            $("#" + targetForm).show();
        });
    });
</script>