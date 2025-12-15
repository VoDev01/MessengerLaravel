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
        let token = "";
        $("#register").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/v1/auth/register',
                type: "POST",
                data: $("#register").serialize(),
                success: function(data) {
                    token = data.access_token;
                    $("#register").append("<p>Your application is succesfully registered!</p>");
                    $("#register").append("<p>Here is your JWT token: " +  data.access_token.substring(0, 12) + "... " +
                        "<button type=\"button\" class=\"btn\" id=\"copyBtn\"><i class=\"bi bi-clipboard\"></i></button>" + "</p>");
                    let scopes = data.scopes.join(", ");
                    $("#register").append("<p>Your application has following permissions: " + scopes + "</p>");

                },
                error: function(errors) {
                    console.log(errors);
                    $(".error").get().forEach(element => {
                        errors.forEach(error => {
                            element.innerHtml = error.message;
                        });
                    });
                }

            })
        });


        $("#copyBtn").on('click', function(e){
            navigator.clipboard.writeText(token);
            this.innerHtml = "<i class=\"bi bi-clipboard-check\"></i>";
        });
    </script>
</x-api-layout>
