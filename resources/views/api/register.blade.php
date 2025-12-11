<x-api-layout>
    <x-slot name="title">API register</x-slot>
    <h1>Register your application</h1>
    <div style="width: 450px;">
        <form action="register" method="post" id="register">
            <div class="mb-3">
                <label for="app_url" class="form-label">App url</label>
                <input type="text" class="form-control" name="app_url" id="app_url" />
            </div>
            <x-error field="app_url" />
            <div class="mb-3">
                <label for="app_api_endpoint" class="form-label">App api endpoint</label>
                <input type="text" class="form-control" name="app_api_endpoint" id="app_api_endpoint" />
            </div>
            <x-error field="app_api_endpoint" />
            <button type="submit" class="btn btn-primary">
                Submit
            </button>
        </form>
    </div>
    <script type="module">
        $("#register").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/v1/auth/register',
                type: "POST",
                data: $("#register").serialize(),
                success: function(data) {
                    $("#register").append("<p>Your application succesfully registered!</p>");
                    $("#register").append("<p>Here is your app JWT key:</p>");
                    $("#register").append("<span>" + data.responseJson.token + "</span>")
                },
                error: function(errors) {
                    console.log(errors);
                    $(".error").forEach(element => {
                        errors.forEach(error => {
                            element.innerHtml = error.message;
                        });
                    });
                }

            })
        });
    </script>
</x-api-layout>
