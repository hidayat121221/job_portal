@extends('front.layout.app')

@section('main')
    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;</div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Register</h1>
                        <form action="" method="POST" name="registrationform" id="registrationform">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="mb-2">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter Name">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Email</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Enter Email">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter Password">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Confirm Password</label>
                                <input type="password" name="confrim_password" id="confrim_password" class="form-control"
                                    placeholder="Enter  your confirm Password">
                                <p></p>
                            </div>
                            <button class="btn btn-primary mt-2" name="submit">Register</button>
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Have an account? <a href="{{ route('account.login') }}">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customjs')
    <script>               
        $("#registrationform").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('account.processRegistration') }}',
                type: 'post',
                data: $("#registrationform").serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === false) {
                        var errors = response.errors;


                        if (errors.name) {
                            $("#name").addClass('is-invalid');
                            $("#name").siblings('p').addClass('invalid-feedback').html(errors.name);
                        } else {
                            $("#name").removeClass('is-invalid');
                            $("#name").siblings('p').addClass('invalid-feedback').html('');
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid');
                            $("#email").siblings('p').addClass('invalid-feedback').html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid');
                            $("#email").siblings('p').addClass('invalid-feedback').html('');
                        }

                        if (errors.password) {
                            $("#password").addClass('is-invalid');
                            $("#password").siblings('p').addClass('invalid-feedback').html(errors
                                .password);
                        } else {
                            $("#password").removeClass('is-invalid');
                            $("#password").siblings('p').addClass('invalid-feedback').html('');
                        }

                        if (errors.confrim_password) {
                            $("#confrim_password").addClass('is-invalid');
                            $("#confrim_password").siblings('p').addClass('invalid-feedback').html(
                                errors.confrim_password);
                        } else {
                            $("#confrim_password").removeClass('is-invalid');
                            $("#confrim_password").siblings('p').addClass('invalid-feedback').html('');
                        }
                    } else {
                        $("#name").addClass('is-invalid');
                        $('is-invalid').siblings('p').addClass('invalid-feedback').html('');

                        $("#email").addClass('is-invalid');
                        $('is-invalid').siblings('p').addClass('invalid-feedback').html('');


                        $("#password").addClass('is-invalid');
                        $('is-invalid').siblings('p').addClass('invalid-feedback').html('');


                        $("#confrim_password").addClass('is-invalid');
                        $('is-invalid').siblings('p').addClass('invalid-feedback').html('');

                        window.location.href='{{ route("account.login") }}';
                    }
                }
            });
        });
    </script>
@endsection
